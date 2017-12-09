<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;

class AndResolver implements ResolverContract
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\AndNode::class;

    /**
     * Regex token 
     *
     * @var string
     */
    public $regex = ["/^(\s?)and(\s?)$/i"];

    /**
     * Resolve token eq node
     *
     * @param Node
     *
     * @return $this
     */
    public function resolve(NodeContract $node)
    {
         // $node->content = "";

        $childs = $node->getChilds();
        
        if (count($childs) > 0) {
            foreach ($node->getChilds() as $child) {
                $this->resolve($child);
            }

            return;
        }
        
        if ($node instanceof Nodes\TextNode) {
            $this->resolveTextNode($node);
        }

    }

    public function resolveTextNode($node)
    {
        foreach ($this->regex as $regex) {
            preg_match($regex, $node->getValue(), $match, PREG_OFFSET_CAPTURE);

            if ($match) {
                $new_node = new $this->node;
                // $new_node->setKey($match[1][0]);
                // $new_node->setValue($match[2][0]);
                $start =  $match[0][1];
                $length = strlen($match[0][0]);

                $push = [];

                $node->getParent()->setOperator($new_node->getOperator());

                $node->getParent()->replaceChild($node->getPos(), []);
            }    
        }
    }
}
