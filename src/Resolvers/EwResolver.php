<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class EwResolver extends KeyResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\EwNode::class;

    /**
     * Regex token 
     *
     * @var string
     */
    public $regex = [
        '/([\w]*) ew (("[^"]*"|[^\s]*))/i', 
        '/([\w]*) \$= (("[^"]*"|[^\s]*))/i'
    ];

}
