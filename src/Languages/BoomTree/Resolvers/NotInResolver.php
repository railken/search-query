<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class NotInResolver extends InResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\NotInNode::class;

    /**
     * Regex.
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])not in(?![^\s])/i',
        '/(?<![^\s])\!=\[\](?![^\s])/i',
    ];
}
