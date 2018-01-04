<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class GtResolver extends KeyResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\GtNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/([\w\.]*) gt (("[^"]*"|[^\s]*))/i',
        '/([\w\.]*) > (("[^"]*"|[^\s]*))/i'
    ];
}
