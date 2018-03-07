<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;
use Railken\SQ\Traits\SplitNodeTrait;

class KeyResolver implements ResolverContract
{

    use SplitNodeTrait;

    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\KeyNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/([a-z_][a-z0-9_]*)/i',
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

        $childs = $node->getChilds();
        
        if (count($childs) > 0) {
            $this->resolve($node->getChild($i));
        }
        
        if ($node instanceof Nodes\TextNode) {

              foreach ($this->regex as $regex) {
                preg_match($regex, $node->getValue(), $match, PREG_OFFSET_CAPTURE);

                if ($match) {

                    $new_node = new $this->node;
                    $new_node->setValue($match[1][0]);
                    $start =  $match[0][1];
                    $length = strlen($match[0][0]);

                    $this->splitNode(Nodes\TextNode::class, $node, $new_node, $start, $start+$length);


                    if ($new_node->prev() && $new_node->prev() instanceof Nodes\KeyNode) {
                        $new_node->setValue($new_node->prev()->getValue()." ".$new_node->getValue());
                        $new_node->getParent()->removeChild($new_node->prev()->getPos());
                    }

                    if ($new_node->next() && $new_node->next() instanceof Nodes\KeyNode) {
                        $new_node->setValue($new_node->getValue()." ".$new_node->next()->getValue());
                        $new_node->getParent()->removeChild($new_node->next()->getPos());
                    }

                    // Search for another match in this node.
                    return $this->resolve($node->getParent(), $i);
                }
            }
        }


        return $node->next() ? $this->resolve($node->next()) : null;
    }
}
