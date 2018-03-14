<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class OrResolver extends AndResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\OrNode::class;

    /**
     * Regex token.
     *
     * @var string
     */
    public $regex = [
        '/(?<![^\s])or(?![^\s])/i',
        '/(?<![^\s])\|\|(?![^\s])/i',
    ];
}
