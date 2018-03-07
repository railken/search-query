<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class NotInResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\NotInNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/not in/i',
        '/!=\[\]/i'
    ];
}
