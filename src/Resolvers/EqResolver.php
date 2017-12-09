<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class EqResolver extends KeyResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\EqNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = ['/([\w]*) eq (("[^"]*"|[^\s]*))/i', '/([\w]*) = (("[^"]*"|[^\s]*))/i'];
}
