<?php

namespace App\Http\Livewire\Backend;

use App\Models\Order;
use App\Http\Traits\Exports;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Support\HtmlString;

/**
 * Class RolesTable.
 */
class OrdersTable extends DataTableComponent
{
    use Exports;

    /**
     * @var string
     */

    public string $sortField = 'created_at';

    /**
     * @var string
     */
    public $sortDirection = 'desc';

    public array $perPageAccepted = [30, 50, 100];


    /**
     * @var array
     */
    protected $options = [
        'bootstrap.container' => false,
        'bootstrap.classes.table' => 'table table-striped',
    ];


    public function __construct($id = null)
    {
        $this->exports = ['csv', 'xlsx'];
        $this->exportFileName = 'orders';

        parent::__construct($id);
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return Order::with('user');
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('Order date'), 'created_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Order Id'), 'id')
                ->sortable(),
            Column::make(__('User'), 'user_id')
                ->sortable()
                ->format(function ($row) {
                    return new HtmlString('<a href="' . route('admin.auth.user.show', $row->user_id) . '">' . ($row->user ? $row->user->name: 'USER DELETED') . '</a>');
                }),
            Column::make(__('Processed'), 'processed_at')
                ->searchable()
                ->sortable(),
            Column::make(__('Quantity'), 'quantity')
                ->sortable(),
            Column::make(__('Total'), 'total')
                ->sortable(),
            Column::make(__('Currency'), 'currency')
                ->searchable()
                ->sortable(),
            Column::make(__('Ref No'), 'refno')
                ->searchable()
                ->sortable(),
        ];
    }
}
