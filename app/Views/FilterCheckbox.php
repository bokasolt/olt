<?php

namespace App\Views;


/**
 * Class Filter.
 */
class FilterCheckbox extends Filter
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
    public function setOptions(array $options = []): FilterCheckbox
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
            ->reject(fn($item) => $item === '' || $item === null)
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
     * @return array
     */
    public function getFilter($filter): array
    {
        return array_keys($filter);
    }

    public function filterToString($filter): string
    {
        return implode(', ', array_intersect_key($this->options(), $filter));
    }

    /**
     * @param $filterValue
     * @return array|null
     */
    public function cleanFilter($filterValue)
    {
        //filter 0
        $filterValue = collect($filterValue)->filter(function ($item, $key) {
            if (!array_key_exists($key, $this->options())) {
                return false;
            }
            return $item;
        });

        if ($filterValue->isEmpty()) {
            return null;
        }

        return $filterValue->toArray();
    }

    /**
     * @return string
     */
    public function view(): string
    {
        return 'livewire-tables::' . config('livewire-tables.theme') . '.components.table.filter-checkbox';
    }
}
