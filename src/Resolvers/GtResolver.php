<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class GtResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\GtNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/(?<![^\s])gt(?![^\s])/i',
        '/(?<![^\s])\>(?![^\s])/i',
    ];
}
