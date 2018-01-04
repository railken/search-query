<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class SwResolver extends KeyResolver
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
        '/([\w\.]*) sw (("[^"]*"|[^\s]*))/i',
        '/([\w\.]*) \^= (("[^"]*"|[^\s]*))/i'
    ];
}
