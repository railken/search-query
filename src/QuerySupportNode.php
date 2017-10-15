<?php

namespace Railken\SQ;

class QuerySupportNode
{

    /**
     * Parts
     *
     * @var array
     */
    public $parts = [];

    /*
     * Parent
     *
     * @var FilterSupportNode
     */
    private $parent;

    /**
     * Set parent node
     *
     * @param FilterSupportNode $node
     *
     * @return void
     */
    public function setParent(QuerySupportNode $node)
    {
        $this->parent = $node;
    }

    /**
     * Get parent
     *
     * @return FilterSupportNode
     */
    public function getParent()
    {
        return $this->parent;
    }
}
