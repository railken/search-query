<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Exceptions as Exceptions;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class InResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\InNode::class;

    /**
     * Regex.
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])in(?![^\s])/i',
        '/(?<![^\s])\=\[\](?![^\s])/i',
    ];

    /**
     * Resolve previous node match.
     *
     * @param NodeContract $node
     * @param NodeContract $new_node
     */
    public function resolveNextNode(NodeContract $node, NodeContract $new_node)
    {
        if ($new_node->next() && ($new_node->next() instanceof Nodes\GroupNode)) {
            $values = $new_node->next()->valueToString();

            $new_node->next()->removeAllChildren();

            foreach (explode(',', $values) as $value) {
                $vn = new Nodes\ValueNode();
                $vn->setValue($value);

                $new_node->next()->addChild($vn);
            }

            $new_node->moveNodeAsChild($new_node->next());
        } else {
            throw new Exceptions\QuerySyntaxException($node->getRoot()->getValue());
        }
    }
}
