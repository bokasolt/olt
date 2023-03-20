<?php

namespace App\Export;

use Illuminate\Support\Str;
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
    public ?string $short;

    /**
     * Column constructor.
     *
     * @param string|null $column
     * @param string|null $text
     */
    public function __construct(string $text = null, string $column = null, string $short = null)
    {
        $this->text = $text;

        if (! $column && $text) {
            $this->column = Str::snake($text);
        } else {
            $this->column = $column;
        }

        if (! $this->column && ! $this->text) {
            $this->blank = true;
        }

        $this->short = $short;
    }
    
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
     * @param string|null $column
     * @param string|null $text
     *
     * @return \Rappasoft\LaravelLivewireTables\Views\Column
     */
    public static function make(string $text = null, string $column = null, string $short = null): Column
    {
        return new static($text, $column, $short);
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
