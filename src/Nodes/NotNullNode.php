<?php

namespace Railken\SQ\Nodes;

class NotNullNode extends ComparisonOperatorNode
{
    /**
     * Operator
     *
     * @var string
     */
    public $operator = 'not_null';
}
