<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;

class AndResolver extends ComparisonOperatorResolver implements ResolverContract
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\AndNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/(?<![^\s])and(?![^\s])/i',
        '/(?<![^\s])&&(?![^\s])/i',
    ];

    /**
     * Resolve previous node match
     *
     * @param NodeContract $node
     * @param NodeContract $new_node
     *
     * @return void
     */
    public function resolvePreviousNode(NodeContract $node, NodeContract $new_node)
    {
        if ($new_node->prev()) {
            $new_node->moveNodeAsChild($new_node->prev());
        } else {
            throw new Exceptions\QuerySyntaxException($node->getParent()->valueToString());
        }
    }

    /**
     * Resolve next node match
     *
     * @param NodeContract $node
     * @param NodeContract $new_node
     *
     * @return void
     */
    public function resolveNextNode(NodeContract $node, NodeContract $new_node)
    {
        if ($new_node->next()) {
            $new_node->moveNodeAsChild($new_node->next());
        } else {
            throw new Exceptions\QuerySyntaxException($node->getParent()->valueToString());
        }
    }

    /**
     * Resolve node relations with other nodes
     *
     * @param NodeContract $node
     * @param NodeContract $new_node
     *
     * @return void
     */
    public function resolveRelationsNode(NodeContract $node, NodeContract $new_node)
    {
        $this->resolvePreviousNode($node, $new_node);
        $this->resolveNextNode($node, $new_node);
        $this->resolveGroupParent($node, $new_node);
    }

    /**
     * Resolve node relations with other nodes
     *
     * @param NodeContract $node
     * @param NodeContract $new_node
     *
     * @return void
     */
    public function resolveGroupParent(NodeContract $node, NodeContract $new_node)
    {
        foreach ($new_node->getChilds() as $child) {
            if (get_class($child) === $this->node) {
                $new_node->replaceChild($child->getIndex(), $child->getChilds());
            }
        }

        if ($new_node->getParent() instanceof Nodes\GroupNode && $new_node->getParent()->countChilds() === 1) {
            $new_node->swapParentAndDelete($new_node->getParent(), $new_node->getParent()->getParent());
        }
    }
}
