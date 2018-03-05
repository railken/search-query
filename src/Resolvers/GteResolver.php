<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class GteResolver extends KeyResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\GteNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/([\w\.\-]*) gte (("[^"]*"|[^\s]*))/i',
        '/([\w\.\-]*) >= (("[^"]*"|[^\s]*))/i'
    ];
}
