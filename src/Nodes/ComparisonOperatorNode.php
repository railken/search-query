<?php
namespace Railken\SQ\Nodes;

use Railken\SQ\StringHelper;

class ComparisonOperatorNode extends Node
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

        if (count($r) > 0) {
            $helper = new StringHelper();
            $parameters = $helper->divideBy($r[2], ",");
            $this->filters[] = ['name' => $r[1], 'parameters' => $parameters];
        } else {
            $this->filters[] = ['name' => $filter];
        }

        return $this;
    }


    public function jsonSerialize()
    {
        return array_merge([
            'type' => get_class($this),
            'value' => $this->value,
            'childs' => array_map(function ($node) {
                return $node->jsonSerialize();
            }, $this->getChilds()),
        ]);
    }
}
