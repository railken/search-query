<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class CtResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\CtNode::class;

    /**
     * Regex
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])ct(?![^\s])/i',
        '/(?<![^\s])\*=(?![^\s])/i',
    ];
}
