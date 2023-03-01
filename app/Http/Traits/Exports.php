<?php

namespace App\Http\Traits;

use Maatwebsite\Excel\Excel;
use App\Exceptions\UnsupportedExportFormatException;
use App\Export\DataTableExport;

/**
 * Trait Exports.
 */
trait Exports
{
    /**
     * @var string
     */
    public $exportFileName = 'data';

    /**
     * @var bool
     */
    public $exports = [];

    /**
     * @param $type
     *
     * @return mixed
     * @throws Exception
     */
    public function export($type)
    {
        $type = strtolower($type);

        if (! in_array($type, ['csv', 'xls', 'xlsx', 'pdf'], true)) {
            throw new UnsupportedExportFormatException(__('This export type is not supported.'));
        }

        if (! in_array($type, array_map('strtolower', $this->exports), true)) {
            throw new UnsupportedExportFormatException(__('This export type is not set on this table component.'));
        }

        switch ($type) {
            case 'csv':default:
                $writer = Excel::CSV;
            break;

            case 'xls':
                $writer = Excel::XLS;
            break;

            case 'xlsx':
                $writer = Excel::XLSX;
            break;

            case 'pdf':
                $writer = Excel::DOMPDF;
                $library = strtolower(config('laravel-livewire-tables.pdf_library'));

                if (! in_array($library, ['dompdf', 'mpdf'], true)) {
                    throw new UnsupportedExportFormatException(__('This PDF export library is not supported.'));
                }

                if ($library === 'mpdf') {
                    $writer = Excel::MPDF;
                }
            break;
        }

        return (new DataTableExport(
            $this->rowsQuery(),
            $this->columns(),
        ))->download($this->exportFileName.'.'.$type, $writer);
    }
}
