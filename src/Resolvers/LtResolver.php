<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class LtResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\LtNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/lt/i',
        '/</i'
    ];
}
