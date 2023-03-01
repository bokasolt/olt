<?php

namespace App\Export;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Class CSVExport.
 */
class DataTableExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * @var array
     */
    public $builder;

    /**
     * @var array
     */
    public $columns;

    /**
     * CSVExport constructor.
     *
     * @param Builder $builder
     * @param array $columns
     */
    public function __construct(Builder $builder, array $columns = [])
    {
        $this->builder = $builder;
        $this->columns = $columns;
    }

    /**
     * @return array|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return $this->builder;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headers = [];

        foreach ($this->columns as $column) {
            if ((method_exists($column, 'isExportOnly') && $column->isExportOnly())
                || (method_exists($column, 'includedInExport') && $column->includedInExport() && $column->isVisible())
                || (!method_exists($column, 'includedInExport') && $column->isVisible())) {
                $headers[] = $column->text();
            }
        }
        return $headers;
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        $map = [];

        foreach ($this->columns as $column) {
            if ((method_exists($column, 'isExportOnly') && $column->isExportOnly())
                || (method_exists($column, 'includedInExport') && $column->includedInExport() && $column->isVisible())
                || (!method_exists($column, 'includedInExport') && $column->isVisible())) {

                if (method_exists($column, 'hasExportFormat') && $column->hasExportFormat()) {
                    $map[] = $column->formattedForExport($row, $column);
                } else {
                    $map[] = strip_tags($column->formatted($row, $column));
                }
            }
        }

        return $map;
    }
}
