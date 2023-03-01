<?php

namespace App\Http\Livewire\Backend;

use App\Http\Traits\DomainColumnsTable;
use App\Models\DomainTempImport;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

/**
 * Class DomainsTable.
 */
class DomainsTempImportTable extends DataTableComponent
{
    use DomainColumnsTable;

    /**
     * @var string
     */
    public $sortField = 'domain';

    /**
     * @var string
     */
    public $status;

    public $exports = ['csv', 'xlsx'];

    public $exportFileName = 'domains';

    /**
     * @var array
     */
    protected $options = [
        'bootstrap.container' => false,
        'bootstrap.classes.table' => 'table table-striped',
    ];

    /**
     * @param  string  $status
     */
    public function mount($status = 'active'): void
    {
        $this->status = $status;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = DomainTempImport::query()->with('counterpartDomain');
        return $query;
    }

    public function columns(): array
    {
        return array_merge($this->domainColumns(), $this->commonColumns());
    }

    public function setTableRowClass($model): ?string
    {
        if (isset($model->counterpartDomain)) {
            return 'text-secondary';
        }

        return null;
    }
}
