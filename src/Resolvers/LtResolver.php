<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class LtResolver extends KeyResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\LtNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/([\w\.\-]+) lt (("[^"]+"|[^\s]+))/i',
        '/([\w\.\-]+) < (("[^"]+"|[^\s]+))/i'
    ];
}
