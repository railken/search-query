<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;
use Railken\SQ\Contracts\NodeContract;

class InResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\InNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/in/i',
        '/\?=\[\]/i'
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
        
        if ($node instanceof Nodes\TextNode || $node instanceof Nodes\KeyNode) {

              foreach ($this->regex as $regex) {

                preg_match($regex, $node->getValue(), $match, PREG_OFFSET_CAPTURE);

                if ($match) {

                    $new_node = new $this->node;

                    $start =  $match[0][1];
                    $length = strlen($match[0][0]);


                    $this->splitNode(get_class($node), $node, $new_node, $start, $start+$length);


                    if ($new_node->next() && ($new_node->next() instanceof Nodes\ValueNode || $new_node->next() instanceof Nodes\KeyNode)) {
                        $new_node->next()->setValue(explode(",", $new_node->next()->getValue()));
                        $new_node->moveNodeAsChild($new_node->next());

                    } else {

                        throw new Exceptions\QuerySyntaxException($node->getParent()->valueToString());
                    }

                    if ($new_node->prev() && ($new_node->prev() instanceof Nodes\ValueNode || $new_node->prev() instanceof Nodes\KeyNode)) {
                        $new_node->moveNodeAsChild($new_node->prev());

                    } else {
                        throw new Exceptions\QuerySyntaxException($node->getParent()->valueToString());

                    }


                    // Search for another match in this node.
                    return $this->resolve($node->getParent(), $i);
                }
            }
        }


        return $node->next() ? $this->resolve($node->next()) : null;
    }
}

