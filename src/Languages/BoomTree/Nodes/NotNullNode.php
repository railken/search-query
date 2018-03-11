<?php

namespace Railken\SQ\Languages\BoomTree\Nodes;

class NotNullNode extends ComparisonOperatorNode
{
    /**
     * Operator
     *
     * @var string
     */
    public $value = 'IS_NOT_NULL';
}
