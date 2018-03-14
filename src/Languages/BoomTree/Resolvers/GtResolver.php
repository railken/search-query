<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class GtResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\GtNode::class;

    /**
     * Regex
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])gt(?![^\s])/i',
        '/(?<![^\s])\>(?![^\s])/i',
    ];
}
