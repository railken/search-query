<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Exceptions;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Railken\SQ\Traits\SplitNodeTrait;

class GroupingResolver implements ResolverContract
{
    use SplitNodeTrait;
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\GroupNode::class;

    /**
     * Regex.
     *
     * @var array
     */
    public $regex = [
        Nodes\GroupOpeningNode::class => '/\(/i',
        Nodes\GroupClosingNode::class => '/\)/i',
    ];

    /**
     * Resolve node.
     *
     * @param NodeContract $node
     *
     * @return NodeContract
     */
    public function resolve(NodeContract $node)
    {
        $this->resolveParenthesis($node);
        $this->resolveGrouping($node);

        return $node;
    }

    /**
     * Resolve node parenthesis.
     *
     * @param NodeContract $node
     *
     * @return void
     */
    public function resolveParenthesis(NodeContract $node)
    {
        foreach ($node->getChilds() as $child) {
            $this->resolveParenthesis($child);
        }

        if (!$node instanceof Nodes\TextNode) {
            return;
        }

        $value = '';
        $positions = [];

        foreach ($this->regex as $class => $regex) {
            preg_match($regex, $node->getValue(), $match, PREG_OFFSET_CAPTURE);

            if ($match) {
                $new_node = new $class();
                $new_node->setValue($match[0][0]);

                $start = $match[0][1];
                $length = strlen($match[0][0]);

                $nodes = $this->splitNode(Nodes\TextNode::class, $node, $new_node, $start, $start + $length);

                $this->resolveParenthesis($nodes[count($nodes) - 1]);
            }
        }
    }

    /**
     * Resolve grouping.
     *
     * @param NodeContract $node
     *
     * @return void
     */
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
                    throw new Exceptions\QuerySyntaxException('Unexpected closing bracket: '.$node->getRoot()->getValue());
                }

                $new_node = new $this->node();

                $childs = $node->getChildsBetweenIndexes($last_opening, $n);
                $node->removeChilds($childs);
                $new_node->addChilds($childs);

                $new_node->removeChildByIndex(0);
                $new_node->removeChildByIndex($new_node->countChilds() - 1);
                $node->addChildAfterNodeByIndex($new_node, $last_opening - 1);

                $this->resolveGrouping($node->getParent());
                return;
            }
        }

        if ($p > 0) {
            throw new Exceptions\QuerySyntaxException('Expected closing bracket: '.$node->getRoot()->getValue());
        }

        return;
    }
}
