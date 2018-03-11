<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Railken\SQ\Exceptions as Exceptions;

class FunctionResolver extends ComparisonOperatorResolver implements ResolverContract
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\FunctionNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [];

    /**
     * Construct
     *
     * @param array $functions
     */
    public function __construct(array $functions)
    {
        foreach ($functions as $function) {
            $this->regex[] = '/(?<![^\s])' . $function . '(?![^\s])/i';
        }
    }

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
        // Nothing ...
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
        // Nothing ...
        if ($new_node->next() && $new_node->next() instanceof Nodes\GroupNode) {

            $childs = [];

            foreach ($new_node->next()->getChilds() as $child) {
                if (trim($child->getValue()) !== ",") {
                    $childs[] = $child;
                }
            }

            $new_node->setChilds($childs);
            $new_node->getParent()->removeChild($new_node->next());
        } else {
            throw new Exceptions\QuerySyntaxException($node->getRoot()->getValue());
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
    }
}
