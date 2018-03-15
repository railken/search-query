<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Railken\SQ\Traits\SplitNodeTrait;

abstract class BaseResolver implements ResolverContract
{
    use SplitNodeTrait;

    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\ValueNode::class;

    /**
     * Regex.
     *
     * @var array<string>
     */
    public $regex = [];

    /**
     * Resolve node.
     *
     * @param NodeContract $node
     *
     * @return NodeContract|null
     */
    public function resolve(NodeContract $node)
    {
        $childs = $node->getChilds();

        if (count($childs) > 0) {
            $this->resolve($node->getChildByIndex(0));
        }

        if ($node instanceof Nodes\TextNode) {
            foreach ($this->regex as $regex) {
                preg_match($regex, $node->getValue(), $match, PREG_OFFSET_CAPTURE);

                if ($match) {
                    $new_node = new $this->node();
                    $new_node->setValue($match[1][0]);
                    $start = $match[0][1];
                    $length = strlen($match[0][0]);

                    $this->splitNode(Nodes\TextNode::class, $node, $new_node, $start, $start + $length);

                    // Search for another match in this node.
                    return $this->resolve($node->getParent());
                }
            }
        }

        return $node->next() ? $this->resolve($node->next()) : null;
    }
}
