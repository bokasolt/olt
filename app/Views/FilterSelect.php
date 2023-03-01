<?php

namespace App\Views;

/**
 * Class Filter.
 */
class FilterSelect extends Filter
{
    /**
     * @var array
     */
    public array $options = [];

    /**
     * @param array $options
     *
     * @return $this
     */
    public function select(array $options = []): FilterSelect
    {
        return $this->setOptions($options);
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options = []): FilterSelect
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * Get the options for a given filter if they exist
     *
     * @return array
     */
    public function getFilterOptions(): array
    {
        return collect($this->options())
            ->keys()
            ->reject(fn ($item) => $item === '' || $item === null)
            ->values()
            ->toArray();
    }

    /**
     * Check whether the filter has numeric keys or not
     *
     * @return bool
     */
    public function hasIntegerKeys(): bool
    {
        return is_int($this->getFilterOptions()[0] ?? null);
    }

    /**
     * @param $filter
     * @return int|string|null
     */
    public function getFilter($filter)
    {
        $filter = $this->hasIntegerKeys() ? (int)$filter : trim($filter);

        if (!isset($this->options()[$filter])) {
            return null;
        }
        return $filter;
    }

    public function filterToString($filter): string
    {
        return $this->options()[$filter];
    }

    /**
     * @param $filterValue
     * @return int|mixed|null
     */
    public function cleanFilter($filterValue)
    {
        if ($this->hasIntegerKeys()){
            if (!is_int($filterValue)) {
                return null;
            }
            $filterValue = int($filterValue);
        }
        if (array_key_exists($filterValue, $this->options())) {
            return $filterValue;
        }
        return null;
    }

    /**
     * @return string
     */
    public function view(): string
    {
        return 'livewire-tables::' . config('livewire-tables.theme') . '.components.table.filter-select';
    }
}
