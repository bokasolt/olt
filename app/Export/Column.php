<?php

namespace App\Export;

use Rappasoft\LaravelLivewireTables\Views\Column as ColumnBase;

class Column extends ColumnBase
{
    /**
     * @var bool
     */
    protected $includeInExport = true;

    /**
     * @var bool
     */
    protected $exportOnly = false;

    /**
     * @var
     */
    protected $exportFormatCallback;
    
    /**
     * @return bool
     */
    public function hasExportFormat(): bool
    {
        return is_callable($this->exportFormatCallback);
    }

    /**
     * @param  callable  $callable
     *
     * @return $this
     */
    public function exportFormat(callable $callable): Column
    {
        $this->exportFormatCallback = $callable;

        return $this;
    }

    /**
     * @param $model
     * @param $column
     *
     * @return mixed
     */
    public function formattedForExport($model, $column)
    {
        return app()->call($this->exportFormatCallback, ['model' => $model, 'column' => $column]);
    }

    /**
     * @return bool
     */
    public function includedInExport(): bool
    {
        return $this->includeInExport === true;
    }

    /**
     * @return $this
     */
    public function exportOnly(): self
    {
        $this->hidden = true;
        $this->exportOnly = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExportOnly(): bool
    {
        return $this->exportOnly === true;
    }

    /**
     * @return $this
     */
    public function excludeFromExport(): self
    {
        $this->includeInExport = false;

        return $this;
    }
}
