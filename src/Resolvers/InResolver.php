<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class InResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\InNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/in/i',
        '/=\[\]/i'
    ];
}
