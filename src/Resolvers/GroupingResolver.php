<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;
use Railken\SQ\Traits\SplitNodeTrait;
use Railken\SQ\Exceptions;

class GroupingResolver implements ResolverContract
{
    use SplitNodeTrait;
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\GroupNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        Nodes\GroupOpeningNode::class => '/\(/i',
        Nodes\GroupClosingNode::class => '/\)/i'
    ];
    
    /**
     * Resolve token eq node
     *
     * @param Node
     *
     * @return $this
     */
    public function resolve(NodeContract $node, $i = 0)
    {
        $this->resolveParenthesis($node);
        $this->resolveGrouping($node);
    }

    public function resolveParenthesis(NodeContract $node)
    {
        foreach ($node->getChilds() as $child) {
            $this->resolveParenthesis($child);
        }

        if (!$node instanceof Nodes\TextNode) {
            return;
        }

        $value = "";
        $positions = [];

        foreach ($this->regex as $class => $regex) {
            preg_match($regex, $node->getValue(), $match, PREG_OFFSET_CAPTURE);

            if ($match) {
                $new_node = new $class;
                $new_node->setValue($match[0][0]);

                $start = $match[0][1];
                $length = strlen($match[0][0]);

                $nodes = $this->splitNode(Nodes\TextNode::class, $node, $new_node, $start, $start+$length);

                $this->resolveParenthesis($nodes[count($nodes) - 1]);
            }
        }
    }


    public function resolveGrouping(NodeContract $node)
    {
        if ($node->countChilds() === 0) {
            return;
        }

        foreach ($node->getChilds() as $child) {
            $this->resolveGrouping($child);
        }

        $p = 0;
        $last_opening = null;


        foreach ($node->getChilds() as $n => $child) {
            if ($child instanceof Nodes\GroupOpeningNode) {
                $p++;
                $last_opening = $n;
            }
            
            if ($child instanceof Nodes\GroupClosingNode) {
                $p--;
                // A group has found. Close and re-resolve

                if ($last_opening === null) {
                    throw new Exceptions\QuerySyntaxException("Unexpected closing bracket: ".$node->getParent()->valueToString());
                }


                $new_node = new $this->node;

                $childs = $node->getChildsBetween($last_opening, $n);
                $node->removeChilds($childs);
                $new_node->addChilds($childs);

                $new_node->removeChild(0);
                $new_node->removeChild($new_node->countChilds()-1);
                $node->insertChildAfter($new_node, $last_opening-1);
        

                return $this->resolveGrouping($node->getParent());
            }
        }

        if ($p > 0) {
            throw new Exceptions\QuerySyntaxException("Expected closing bracket: ".$node->getParent()->valueToString());
        }
    }
}
