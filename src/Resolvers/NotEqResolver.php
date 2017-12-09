<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class NotEqResolver extends KeyResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\NotEqNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/([\w]*) not eq (("[^"]*"|[^\s]*))/i',
        '/([\w]*) <> (("[^"]*"|[^\s]*))/i',
        '/([\w]*) != (("[^"]*"|[^\s]*))/i'
    ];
}
