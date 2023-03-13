<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\Role;
use App\Models\GoogleSheet;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class RolesTable.
 */
class GoogleSheetsTable extends DataTableComponent
{

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return GoogleSheet::when($this->getFilter('search'), fn ($query, $term) => $query->search($term));
    }

    public function columns(): array
    {
        return [
            Column::make(__('Name'))
                ->sortable(),
            Column::make(__('Url')),
            Column::make(__('Actions')),
        ];
    }

    public function rowView(): string
    {
        return 'backend.google-sheet.includes.row';
    }
}
