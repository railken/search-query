<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class NotNullResolver extends KeyResolver
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
        '/([\w\.\-]*) is not null/i',
    ];
}
