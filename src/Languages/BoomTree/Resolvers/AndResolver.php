<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Exceptions as Exceptions;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class AndResolver extends LogicResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\AndNode::class;

    /**
     * Regex.
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])and(?![^\s])/i',
        '/(?<![^\s])&&(?![^\s])/i',
    ];

    /**
     * Resolve previous node match.
     *
     * @param NodeContract $node
     * @param NodeContract $new_node
     */
    public function resolvePreviousNode(NodeContract $node, NodeContract $new_node)
    {
        if ($new_node->prev() !== null) {
            $new_node->moveNodeAsChild($new_node->prev());
        } else {
            throw new Exceptions\QuerySyntaxException($node->getRoot()->getValue());
        }
    }

    /**
     * Resolve next node match.
     *
     * @param NodeContract $node
     * @param NodeContract $new_node
     */
    public function resolveNextNode(NodeContract $node, NodeContract $new_node)
    {
        if ($new_node->next() !== null) {
            $new_node->moveNodeAsChild($new_node->next());
        } else {
            throw new Exceptions\QuerySyntaxException($node->getRoot()->getValue());
        }
    }

    /**
     * Resolve node relations with other nodes.
     *
     * @param NodeContract $node
     * @param NodeContract $new_node
     */
    public function resolveRelationsNode(NodeContract $node, NodeContract $new_node)
    {
        $this->resolvePreviousNode($node, $new_node);
        $this->resolveNextNode($node, $new_node);
        $this->resolveGroupParent($node, $new_node);
    }

    /**
     * Resolve node relations with other nodes.
     *
     * @param NodeContract $node
     * @param NodeContract $new_node
     */
    public function resolveGroupParent(NodeContract $node, NodeContract $new_node)
    {
        foreach ($new_node->getChildren() as $child) {
            if (get_class($child) === $this->node) {
                $new_node->replaceChild($child->getIndex(), $child->getChildren());
            }
        }

        if ($new_node->getParent() instanceof Nodes\GroupNode && $new_node->getParent()->countChildren() === 1 && $new_node->getParent()->getParent() !== null) {
            $new_node->swapParentAndDelete($new_node->getParent(), $new_node->getParent()->getParent());
        }
    }
}
