<?php

namespace Railken\SQ\Nodes;

class NotNullNode extends ComparisonOperatorNode
{
    /**
     * Operator
     *
     * @var string
     */
    public $value = 'not_null';
}
