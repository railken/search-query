<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class NotNullResolver extends NullResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\NotNullNode::class;

    /**
     * Regex
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])is not null(?![^\s])/i',
    ];
}
