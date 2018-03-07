<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class NotNullResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\NotNullNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/is not null/i',
    ];
}
