<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class GteResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\GteNode::class;

    /**
     * Regex token.
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])gte(?![^\s])/i',
        '/(?<![^\s])\>=(?![^\s])/i',
    ];
}
