<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class NotInResolver extends KeyResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\NotInNode::class;

    /**
     * Regex token 
     *
     * @var string
     */
    public $regex = [
        '/([\w]*) not in (("[^"]*"|[^\s]*))/i', 
        '/([\w]*) !=\[\] (("[^"]*"|[^\s]*))/i'
    ];

}
