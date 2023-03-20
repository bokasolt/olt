<?php

namespace App\Http\Livewire\Backend;

use App\Http\Traits\DomainColumnsTable;
use App\Http\Traits\Exports;
use App\Export\Column;
use App\Models\Domain;
use App\Services\AhrefsStoreService;
use App\Views\Filter;
use App\Views\FilterCheckbox;
use App\Views\FilterSelect;
use App\Views\FilterRange;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

/**
 * Class DomainsTable.
 */
class DomainsTable extends DataTableComponent
{
    use DomainColumnsTable, Exports;

    /**
     * @var string
     */
    public $status;

    public bool $showFilters = true;
    public bool $toggleFilters = false;

    public int $perPage = 30;
    public array $perPageAccepted = [30, 50, 100];

    public $actions = 'backend.domain.includes.checkbox-actions';
    public $checkbox_all = false;

    public $checkbox_values = [];
    public $checkbox_actions = [];

    public $type = 'active';

    /**
     * @var array
     */
    public $options = [
        'bootstrap.classes.table' => 'table table-striped stick-2col',
    ];

    public function __construct()
    {
        if (Auth::user()->can('admin.export.domain')) {
            $this->exports = ['csv', 'xlsx'];
        }

        $this->paginationTheme = 'bootstrap';
        $this->checkbox_actions['delete'] = true;

        $this->exportFileName = 'domains';

        parent::__construct();
    }

    /**
     * @param string $type
     */
    public function mount($type = 'active'): void
    {
        $this->type = $type;
        if ($this->type == 'trashed') {
            unset($this->checkbox_actions['delete']);
        }
    }

    public function baseQuery(): Builder
    {
        switch ($this->type) {
            case 'failed':
                return Domain::query()->whereNotNull('ahrefs_error_message');
            case 'trashed':
                return Domain::query()->onlyTrashed();
            default:
                return Domain::query();
        }
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = $this->baseQuery()
            ->when($this->getFilter('niche'), fn($query, $filter) => $query->whereIn('niche', $filter))
            ->when($this->getFilter('lang'), fn($query, $filter) => $query->whereIn('lang', $filter))
            ->when($this->getFilter('article_by'), function ($query, $filter) {
                foreach ($filter as $k) {
                    $query->where('article_by', 'like', '%' . $k . '%');
                }
            })
            ->when($this->getFilter('sponsored_label'), fn($query, $filter) => $query->whereIn('sponsored_label', $filter))
            ->when($this->getFilter('type_of_publication'), function ($query, $filter) {
                foreach ($filter as $k) {
                    $query->where('type_of_publication', 'like', '%' . $k . '%');
                }
            })
            ->when($this->getFilter('type_of_link'), fn($query, $filter) => $query->whereIn('type_of_link', $filter))
            ->when($this->getFilter('price'), function ($query, $filter) {
                $query->where(function ($q) use ($filter) {
                    foreach ($filter as $k) {
                        switch ($k) {
                            case 'less200':
                                $q->orWhere('price', '<=', 200);
                                break;
                            case '201-500':
                                $q->orWhereBetween('price', [201, 500]);
                                break;
                            case 'more500':
                                $q->orWhere('price', '>', 500);
                                break;
                        }
                    }
                });
            });

        $rangeList = array('ahrefs_dr', 'ahrefs_traffic', 'linked_domains', 'ref_domains', 'num_organic_keywords_top_10');
        foreach ($rangeList as $k) {
            $filter = $this->getFilter($k);
            if (isset($filter)) {
                if (isset($filter['min'])) {
                    $query->where($k, '>=', $filter['min']);
                }
                if (isset($filter['max'])) {
                    $query->where($k, '<=', $filter['max']);
                }
            }
        }


        return $query->withCount('users');
    }

    public function updateAhrefsMetrics(Domain $domain)
    {
        try {
            $ahrefs = app(AhrefsStoreService::class);
            $ahrefs->updateDomain($domain);
        } catch (\Exception $e) {
            //TODO Report error
        }
    }

    public function columns(): array
    {
        $failed_columns = [];
        if ($this->type == 'failed') {
            $failed_columns[] = Column::make(__('Failed Message'), 'ahrefs_error_message');
        }

        return array_merge(
            [
                Column::make($this->type == 'trashed' ? 'Ordered' :'checkboxall', 'domain')
                    ->format(function (Domain $row) {
                        return view('backend.domain.includes.checkbox', ['domain' => $row]);
                    })
                    ->excludeFromExport()
            ],
            $this->domainColumns(true),
            $failed_columns,
            $this->commonColumns(true),
            [
                Column::make(__('Sync at'), 'ahrefs_sync_at', 'Sync')
                    ->sortable(),
                Column::make(__('Actions'), 'domain', 'Action')
                    ->format(function (Domain $row) {
                        return view('backend.domain.includes.actions', ['domain' => $row]);
                    })
                    ->excludeFromExport()
            ]
        );
    }

    public function deleteDomains(): int
    {
        if (empty($this->checkbox_values)) {
            return 0;
        }

        //We should delete row by row because someone can order domain in same time.
        /*$domains = Domain::whereDoesntHave('users')
            ->whereIn('id', $this->checkbox_values)
            ->get();*/
        $domains = Domain::whereIn('id', $this->checkbox_values)
            ->get();

        if ($domains->isEmpty()) {
            return 0;
        }

        $count = 0;
        foreach ($domains as $domain) {
            try {
                $domain->delete();
                $count++;
            } catch (QueryException $e) {
            }
        }

        return $count;
    }

    public function updatedCheckboxAll()
    {
        $checkbox_values = array_flip($this->checkbox_values);
        $domains = $this->getRowsProperty();
        foreach ($domains as $domain) {
            if ($this->checkbox_all) {
                $checkbox_values[$domain->id] = $this->checkbox_all;
            } else {
                unset($checkbox_values[$domain->id]);
            }
        }
        $this->checkbox_values = array_keys($checkbox_values);
    }

    public function restore(int $domain_id)
    {
        $domain = Domain::withTrashed()->find($domain_id);
        $domain->restore();
        session()->flash('flash_success', __('The domain ') . $domain->domain . __(' was successfully restored.'));
        return redirect()->route('admin.dashboard');
    }

    protected function buildOptionsFor($name)
    {
        $list = new Collection();
        Domain::select($name)
            ->whereNotNull($name)
            ->where($name, '!=', '')
            ->groupBy($name)
            ->get()
            ->map(function ($row) use ($name, $list) {
                foreach (explode(',',$row->{$name}) as $value) {
                    $list->push([$name => ucwords(trim($value))]);
                }
            });
        return $list->unique()->pluck($name, $name)->toArray();
    }

    public function filters(): array
    {
        $filters = [
            'niche' => FilterCheckbox::make(__('Niche'))
                ->setOptions(Domain::whereNotNull('niche')
                    ->where('niche', '!=', '')
                    ->groupBy('niche')
                    ->pluck('niche', 'niche')->toArray()),
            'lang' => FilterCheckbox::make(__('Language'))
                ->setOptions(Domain::whereNotNull('lang')
                    ->where('lang', '!=', '')
                    ->groupBy('lang')
                    ->pluck('lang', 'lang')->toArray()),
            'article_by' => FilterCheckbox::make(__('Article provides by'))
                ->setOptions($this->buildOptionsFor('article_by')),
            'sponsored_label' => FilterCheckbox::make(__('Sponsored label'))
                ->setOptions(Domain::whereNotNull('sponsored_label')
                    ->where('sponsored_label', '!=', '')
                    ->groupBy('sponsored_label')
                    ->pluck('sponsored_label', 'sponsored_label')->toArray()),
            'type_of_publication' => FilterCheckbox::make(__('Type of publication'))
                ->setOptions($this->buildOptionsFor('type_of_publication')),
            'type_of_link' => FilterCheckbox::make(__('Link type'))
                ->setOptions(Domain::whereNotNull('type_of_link')
                    ->where('type_of_link', '!=', '')
                    ->groupBy('type_of_link')
                    ->pluck('type_of_link', 'type_of_link')->toArray()),
        ];

        $filters['price'] = FilterCheckbox::make(__('Price range'))
            ->setOptions(['less200' => '$0 - $200', '201-500' => '$201 - $500', 'more500' => '$500+']);

        $filtersRange = Domain::select(
            DB::raw('min(ahrefs_dr) as ahrefs_dr_min, max(ahrefs_dr) as ahrefs_dr_max'),
            DB::raw('min(ahrefs_traffic) as ahrefs_traffic_min, max(ahrefs_traffic) as ahrefs_traffic_max'),
            DB::raw('min(linked_domains) as linked_domains_min, max(linked_domains) as linked_domains_max'),
            DB::raw('min(ref_domains) as ref_domains_min, max(ref_domains) as ref_domains_max'),
            DB::raw('min(num_organic_keywords_top_10) as num_organic_keywords_top_10_min, max(num_organic_keywords_top_10) as num_organic_keywords_top_10_max')
        )->first();

        $filters += [
            'ahrefs_dr' => FilterRange::make('Ahrefs DR')
                ->setRange($filtersRange->ahrefs_dr_min, $filtersRange->ahrefs_dr_max),
            'ahrefs_traffic' => FilterRange::make('Ahrefs Traffic')
                ->setRange($filtersRange->ahrefs_traffic_min, $filtersRange->ahrefs_traffic_max),
            'linked_domains' => FilterRange::make('Linked domains')
                ->setRange($filtersRange->linked_domains_min, $filtersRange->linked_domains_max),
            'ref_domains' => FilterRange::make('Ref. domains')
                ->setRange($filtersRange->ref_domains_min, $filtersRange->ref_domains_max),
            'num_organic_keywords_top_10' => FilterRange::make('Keywords TOP 10')
                ->setRange($filtersRange->num_organic_keywords_top_10_min, $filtersRange->num_organic_keywords_top_10_max),
        ];

        $filters['hideOrdered'] = FilterSelect::make(__('Hide Ordered'))
            ->setOptions(['' => 'Show', 'yes' => 'Hide']);

        return $filters;
    }

    public function cleanFilters(): void
    {
        // Filter $filters values
        $filters = collect($this->filters);

        $filters->filter(function ($filterValue, $filterName) {
            $filterDefinitions = $this->filters();

            // Ignore search
            if ($filterName === 'search') {
                return true;
            }


            // Filter out any keys that weren't defined as a filter
            if (!isset($filterDefinitions[$filterName])) {
                return false;
            }

            // Ignore null values
            if (is_null($filterValue)) {
                return true;
            }

            return true;
        });

        $filters = $filters->map(function ($filterValue, $filterName) {
            $filterDefinitions = $this->filters();
            if (isset($filterDefinitions[$filterName]) &&
                $filterDefinitions[$filterName] instanceof Filter) {
                return $filterDefinitions[$filterName]->cleanFilter($filterValue);
            }
            return $filterValue;
        });

        $this->filters = $filters->toArray();
    }

    public function getFilter(string $filter)
    {
        if ($this->hasFilter($filter)) {
            if (in_array($filter, collect($this->filters())->keys()->toArray(), true) &&
                $this->filters()[$filter] instanceof Filter) {
                return $this->filters()[$filter]->getFilter($this->filters[$filter]);
            }

            return trim($this->filters[$filter]);
        }

        return null;
    }

}