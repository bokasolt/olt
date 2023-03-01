<?php


namespace App\Http\Livewire\Backend;


use App\Models\Content;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ContentsTable extends DataTableComponent
{
    public function query(): Builder
    {
        return Content::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('Path'), 'path')->format(function ($row){
                return new HtmlString('<a target="_blanc" href="' . route('frontend.content', $row->path) . '">' . $row->path . '</a>');
            })->searchable(),
            Column::make(__('Title'), 'title')->searchable(),
            Column::make(__('Actions'))
                ->format(function (Content $row) {
                    return view('backend.content.includes.actions')->withContent($row);
                })
        ];
    }
}