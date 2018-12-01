<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class NullResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\NullNode::class;

    /**
     * Regex.
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])is null(?![^\s])/i',
    ];

    /**
     * Resolve previous node match.
     *
     * @param NodeContract $node
     * @param NodeContract $new_node
     */
    public function resolveNextNode(NodeContract $node, NodeContract $new_node)
    {
        // Nothing to resolve...
    }
}
