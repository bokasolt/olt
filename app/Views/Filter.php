<?php

namespace App\Views;


/**
 * Class Filter.
 */
abstract class Filter
{
    /**
     * @var string
     */
    public string $name;

    /**
     * Filter constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public static function make(string $name)
    {
        return new static($name);
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
    
    abstract public function view(): string;

    public function getFilter($filter)
    {
        return $filter;
    }

    public function filterToString($filter): string
    {
        return print_r($filter, 1);
    }

    public function cleanFilter($filterValue)
    {
        return $filterValue;
    }
}
