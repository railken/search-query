<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class CtResolver extends KeyResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\CtNode::class;

    /**
     * Regex token 
     *
     * @var string
     */
    public $regex = [
        '/([\w]*) ct (("[^"]*"|[^\s]*))/i', 
        '/([\w]*) \*= (("[^"]*"|[^\s]*))/i'
    ];

}
