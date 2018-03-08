<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class GteResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\GteNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/(?<![^\s])gte(?![^\s])/i',
        '/(?<![^\s])\>=(?![^\s])/i',
    ];
}
