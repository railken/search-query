<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;
use Railken\SQ\Traits\SplitNodeTrait;

class ComparisonOperatorResolver implements ResolverContract
{

    use SplitNodeTrait;

    
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
        
        if ($node instanceof Nodes\TextNode || $node instanceof Nodes\KeyNode) {

              foreach ($this->regex as $regex) {
                preg_match($regex, $node->getValue(), $match, PREG_OFFSET_CAPTURE);

                if ($match) {

                    $new_node = new $this->node;

                    $start =  $match[0][1];
                    $length = strlen($match[0][0]);

                    $this->splitNode(Nodes\TextNode::class, $node, $new_node, $start, $start+$length);


                    if (!$node->next()) {
                        // Throw exception. Expect Value|Column after eq
                    }

                    if (!$node->prev()) {
                        // Throw exception. Expect Value|Column before eq
                    }

                    if ($new_node->next() instanceof Nodes\ValueNode || $new_node->next() instanceof Nodes\KeyNode) {
                        $new_node->moveNodeAsChild($new_node->next());
                    }

                    if ($new_node->prev() instanceof Nodes\ValueNode || $new_node->prev() instanceof Nodes\KeyNode) {
                        $new_node->moveNodeAsChild($new_node->prev());
                    }


                    // Search for another match in this node.
                    return $this->resolve($node->getParent(), $i);
                }
            }
        }


        return $node->next() ? $this->resolve($node->next()) : null;
    }
}
