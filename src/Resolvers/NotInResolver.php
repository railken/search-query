<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class NotInResolver extends InResolver
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
        '/(?<![^\s])not in(?![^\s])/i',
        '/(?<![^\s])>\!=\[\](?![^\s])/i',
    ];
}
