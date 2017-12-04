<?php

namespace Railken\SQ;

use Railken\SQ\StringHelper;

class QueryKeyNode extends QueryBaseNode
{

    /**
     * Key/Attribute
     *
     * @param string
     */
    public $key;

    /**
     * Filters applied
     *
     * @var mixed
     */
    public $filters = [];

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
        preg_match('#(.*?)\((.*?)\)#', $filter, $r);

        $helper = new StringHelper();
        $parameters = $helper->divideBy($r[2], ",");
        
        $this->filters[] = ['name' => $r[1], 'parameters' => $parameters];

        return $this;
    }
}
