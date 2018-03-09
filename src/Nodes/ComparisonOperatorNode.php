<?php
namespace Railken\SQ\Nodes;

use Railken\SQ\StringHelper;

class ComparisonOperatorNode extends Node
{
    
    /**
     * Filters applied
     *
     * @var mixed
     */
    public $filters = [];


    /**
     * Array representation of node
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => get_class($this),
            'value' => $this->getValue(),
        ];
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
}
