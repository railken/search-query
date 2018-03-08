<?php

namespace Railken\SQ\Nodes;

class NullNode extends ComparisonOperatorNode
{
    /**
     * Operator
     *
     * @var string
     */
    public $value = 'IS_NULL';
}
