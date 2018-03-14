<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class NotEqResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\NotEqNode::class;

    /**
     * Regex
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])not eq(?![^\s])/i',
        '/(?<![^\s])<>(?![^\s])/i',
        '/(?<![^\s])!=(?![^\s])/i',
    ];
}
