<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class CtResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\CtNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/(?<![^\s])ct(?![^\s])/i',
        '/(?<![^\s])\*=(?![^\s])/i',
    ];
}
