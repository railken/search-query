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
        $children = $node->getChildren();

        if (count($children) > 0) {
            $this->resolve($node->getChildByIndex(0));
        }

        if ($node instanceof Nodes\TextNode) {
            foreach ($this->regex as $regex) {
                $margin = 0;

                preg_match_all($regex, $node->getValue(), $matches, PREG_OFFSET_CAPTURE);

                $positions = [];

                foreach ($matches[0] as $match) {
                    $value = $match[0]; // Value
                    $start = $match[1]; // Offset

                    $length = strlen($value);
                    $new_node = new $this->node();
                    $new_node->setValue($this->parseValue($value));

                    $positions[] = [
                        'from' => $start,
                        'to'   => $start + $length,
                        'node' => $new_node,
                    ];
                }

                if (count($positions) > 0) {
                    $nodes = $this->splitMultipleNode(Nodes\TextNode::class, $node, $positions);

                    return $this->resolve($nodes[0]->getParent());
                }
            }
        }

        return $node->next() !== null ? $this->resolve($node->next()) : null;
    }

    /**
     * Parse the value before adding to the node.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function parseValue($value)
    {
        return $value;
    }
}
