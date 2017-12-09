<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class InResolver extends KeyResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\InNode::class;

    /**
     * Regex token 
     *
     * @var string
     */
    public $regex = [
        '/([\w]*) in (("[^"]*"|[^\s]*))/i', 
        '/([\w]*) =\[\] (("[^"]*"|[^\s]*))/i'
    ];

}
