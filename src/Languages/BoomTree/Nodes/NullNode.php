<?php

namespace Railken\SQ\Languages\BoomTree\Nodes;

class NullNode extends ComparisonOperatorNode
{
    /**
     * Operator
     *
     * @var string
     */
    public $value = 'IS_NULL';
}
