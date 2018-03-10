<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Contracts\ComparableNodeContract;
use Railken\SQ\Nodes as Nodes;
use Railken\SQ\Traits\SplitNodeTrait;
use Railken\SQ\Exceptions;

class ComparisonOperatorResolver implements ResolverContract
{
    use SplitNodeTrait;

    
    /**
     * Resolve token eq node
     *
     * @param NodeContract $node
     * @param integer $i
     *
     * @return NodeContract
     */
    public function resolve(NodeContract $node, $i = 0)
    {
        $childs = $node->getChilds();

        if (count($childs) > 0) {
            $this->resolve($node->getChildByIndex($i));

            $value = "";
            $positions = [];
            foreach ($node->getChilds() as $child) {
                if ($child instanceof Nodes\TextNode || $child instanceof Nodes\KeyNode) {
                    $value .= " ".$child->getValue();
                    $p = array_fill(0, strlen(" ".$child->getValue()), $child->getIndex());
                    $positions = array_merge($positions, $p);
                }
            }



            foreach ($this->regex as $regex) {
                preg_match($regex, $value, $match, PREG_OFFSET_CAPTURE);

                if ($match) {
                    $new_node = new $this->node;

                    $start = $match[0][1];
                    $length = strlen($match[0][0]);


                    $this->groupNode($node, $new_node, $start, $start+$length, $positions);
                    $this->resolveRelationsNode($node, $new_node);

                    // Search for another match in this node.
                    return $this->resolve($node->getParent(), $i);
                }
            }
        }


        return $node->next() ? $this->resolve($node->next()) : null;
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

        if ($new_node->prev() && $new_node->prev() instanceof ComparableNodeContract) {
            $new_node->moveNodeAsChild($new_node->prev());
        } else {
            throw new Exceptions\QuerySyntaxException($node->getRoot()->getValue());
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
        if ($new_node->next() && $new_node->next() instanceof ComparableNodeContract) {
            $new_node->moveNodeAsChild($new_node->next());
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
