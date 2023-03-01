<?php

namespace App\Http\Livewire\Frontend;

use App\Domains\Auth\Services\DomainUserService;
use App\Exceptions\DomainUserException;
use App\Models\Domain;
use App\Views\Filter;
use App\Views\FilterCheckbox;
use App\Views\FilterSelect;
use App\Views\FilterRange;

//use Rappasoft\LaravelLivewireTables\Views\FilterCheckbox;
//use Rappasoft\LaravelLivewireTables\Views\FilterSelect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class DomainsTable.
 */
class DomainsTable extends DataTableComponent
{
    /**
     * @var string
     */
    public $sortField = 'domain';

    public bool $showSearch = false;
    public bool $showSorting = false;
    public bool $showFilters = true;
    public bool $singleColumnSorting = true;

    public bool $toggleFilters = false;

    public int $perPage = 30;
    public array $perPageAccepted = [30, 50, 100];

    /**
     * @var array
     */
    public $options = [
        'bootstrap.classes.table' => 'table domain-table table-striped',
    ];

    public function __construct()
    {
        $this->paginationTheme = 'bootstrap';

        parent::__construct();
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = Domain::withUserAccess(Auth::user())
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

        if ($this->getFilter('hideOrdered') == 'yes'){
            $query->whereDoesntHave('users');
        }

        return $query;
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

        if (Auth::user()->isPaid()) {
            $filters['price'] = FilterCheckbox::make(__('Price range'))
                ->setOptions(['less200' => '$0 - $200', '201-500' => '$201 - $500', 'more500' => '$500+']);
        }
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

    public function hideDomainFormatter($row, $column)
    {
        $pos = strpos($row->domain, '.');
        if ($pos !== false) {
            return $row->domain[0] . '****' . substr($row->domain, $pos);
        }

        return '****';
    }

    public function hideTitleFormatter($row, $column)
    {
        return 'Title ********** of';
    }

    public function hideFormatter($row, $column)
    {
        return '****';
    }

    public function requirePaidAccess($row, $textForHidden, $textForPaid)
    {
        if ($row->users->isEmpty()) {
            return view('frontend.includes.wire-button', [
                'action' => 'purchaseDomainAccess(' . $row->id . ')',
                'text' => $textForHidden,
            ]);
        }
        return $textForPaid;
    }

    public function requirePaidAccessFormatter($row, $column)
    {
        return $this->requirePaidAccess($row,
            $this->hideFormatter($row, $column),
            $row->{$column});
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

    public function columns(): array
    {
        $user = Auth::user();

        $columns = [];
        $column = Column::make(__('DOMAIN'), 'domain')
            ->sortable();
        if ($user->isPaid()) {
            $column->searchable();
            $column->format(function ($row, $column) {
                return $this->requirePaidAccess(
                    $row,
                    $this->hideDomainFormatter($row, $column),
                    new HtmlString('<a target="_blanc" href="http://' . $row->domain . '">' . $row->domain . '</a>')
                );
            });
        } else {
            $column->format([$this, 'hideDomainFormatter']);
        }
        $columns[] = $column;

        $columns[] = Column::make(__('NICHE'), 'niche')
            ->sortable();
        $columns[] = Column::make(__('LANGUAGE'), 'lang')
            ->sortable();

        $columns[] = Column::make(__('Title of home page'), 'title')
            ->format(function ($row, $column) {
                return $this->requirePaidAccess(
                    $row,
                    $this->hideTitleFormatter($row, $column),
                    $row->title
                );
            })
            ->sortable();

        $columns[] = Column::make(__('Ahrefs DR'), 'ahrefs_dr')
            ->sortable();
        $columns[] = Column::make(__('Ahrefs Traffic'), 'ahrefs_traffic')
            ->sortable();
        $columns[] = Column::make(__('Linked domains'), 'linked_domains')
            ->sortable();
        $columns[] = Column::make(__('Ref. domains'), 'ref_domains')
            ->sortable();
        $columns[] = Column::make(__('Keywords TOP 10'), 'num_organic_keywords_top_10')
            ->sortable();

        $paidColumnsFormatter = 'requirePaidAccessFormatter';

        $columns[] = Column::make(__('Article provides by'), 'article_by')
            ->sortable();
        $columns[] = Column::make(__('Price (USD)'), 'price')
            ->format([$this, $paidColumnsFormatter]);
        $columns[] = Column::make(__('Sponsored label'), 'sponsored_label')
            ->sortable();
        $columns[] = Column::make(__('Type of publication'), 'type_of_publication')
            ->sortable();
        $columns[] = Column::make(__('Link type'), 'type_of_link')
            ->sortable();
        $columns[] = Column::make(__('Contact email'), 'contact_email')
            ->format([$this, $paidColumnsFormatter])
            ->sortable();
        $columns[] = Column::make(__('Contact form'), 'contact_form_link')
            ->format([$this, $paidColumnsFormatter])
            ->sortable();
        $columns[] = Column::make(__('Contact name'), 'contact_name')
            ->format([$this, $paidColumnsFormatter])
            ->sortable();
        $columns[] = Column::make(__('Additional notes'), 'additional_notes')
            ->format([$this, $paidColumnsFormatter])
            ->sortable();
        return $columns;
    }

    public function purchaseDomainAccess(Domain $domain)
    {
        $domainUserService = new DomainUserService();

        try {
            $domainUserService->allowAccessToDomain($domain);
            $this->emit('updateBalance');
        } catch (DomainUserException $e) {
            $this->emit('showPurchaseDialog');
        }
    }
}
