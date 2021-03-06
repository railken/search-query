<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class SwResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\SwNode::class;

    /**
     * Regex.
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])sw(?![^\s])/i',
        '/(?<![^\s])\^=(?![^\s])/i',
    ];
}
