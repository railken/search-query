<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class LteResolver extends KeyResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\LteNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/([\w\.]*) lte (("[^"]+"|[^\s]+))/i',
        '/([\w\.]*) <= (("[^"]+"|[^\s]+))/i'
    ];
}
