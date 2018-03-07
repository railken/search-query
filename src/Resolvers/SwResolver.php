<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class SwResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\SwNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/sw/i',
        '/\^=/i'
    ];
}
