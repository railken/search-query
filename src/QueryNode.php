<?php

namespace Railken\SQ;

class QueryNode
{


    /**
     * Key/Attribute
     *
     * @param string
     */
    public $key;

    /**
     * Operator of node
     *
     * @var string
     */
    public $operator;

    /**
     * Value/Values of node
     *
     * @var mixed
     */
    public $value;

    /**
     * Filters applied
     *
     * @var mixed
     */
    public $filters = [];

    /**
     * Construct
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     */
    public function __construct()
    {
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {

        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * Set Operator
     *
     * @param string $operator
     *
     * @return $this
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set filters
     *
     * @param mixed $filters
     *
     * @return $this
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Get filters
     *
     * @return mixed
     */
    public function getFilters()
    {
        return $this->filters;
    } 

    /**
     * Add filter
     *
     * @return mixed
     */
    public function addFilter($filter)
    {
        $this->filters[] = $filter;

        return $this;
    }
}
