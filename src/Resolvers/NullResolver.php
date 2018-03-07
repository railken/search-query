<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class NullResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\NullNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/is null/i',
    ];
}
