<?php


namespace App\Views;


class FilterRange extends Filter
{
    public $min = null;
    public $max = null;

    public function setRange($min, $max): FilterRange
    {
        $this->min = $min;
        $this->max = $max;
        return $this;
    }

    public function getMin() {
        return $this->min;
    }

    public function getMax() {
        return $this->max;
    }

    /**
     * @param $filter
     * @return array
     */
    public function getFilter($filter): array
    {
        return $filter;
    }

    public function filterToString($filter): string
    {
        return ($filter['min'] ?? $this->min) . ' - ' . ($filter['max'] ?? $this->max);
    }

    /**
     * @param $filterValue
     * @return array|null
     */
    public function cleanFilter($filterValue): ?array
    {
        $filterValue = collect($filterValue)->filter(function ($item, $key) {
            if (($key != 'min') && ($key != 'max')) {
                return false;
            }

            if (!is_numeric($item)) {
                return false;
            }

            return true;
        });
        if ($filterValue->isEmpty()) {
            return null;
        }

        if (isset($this->min) && isset($filterValue['min']) && $filterValue['min'] < $this->min) {
            $filterValue['min'] = $this->min;
        }

        if (isset($this->max) && isset($filterValue['max']) && $filterValue['max'] > $this->max) {
            $filterValue['max'] = $this->max;
        }

        return $filterValue->toArray();
    }

    /**
     * @return string
     */
    public function view(): string
    {
        return 'livewire-tables::' . config('livewire-tables.theme') . '.components.table.filter-range';
    }
}