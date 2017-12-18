<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;

class OrResolver implements ResolverContract
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\OrNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = ["/^(\s*)or(\s*)$/i", "/^(\s*)\|\|(\s*)$/i"];

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

                $parent = $node->getParent();

                if ($parent instanceof Nodes\OrNode) {
                    $node->getParent()->replaceChild($node->getPos(), []);
                } 

                if ($parent instanceof Nodes\AndNode) {

                    // Get the parent without all childs after this.
                    // Create a new OrNode with the first child the parent, and the rest the remaining childs

                    $parent->getParent()->replaceChild($parent->getPos(), [$new_node]);

                    $new_node->addChild($parent);

                    $childs_after = $parent->getChildsAfterKey($node->getPos()+1);
                    $parent->removeChildByKey($node->getPos());

                    foreach ($childs_after as $child) {
                        $parent->removeChildByKey($child->getPos(), false);
                        $new_node->addChild($child);
                    }

                }

                if ($parent instanceof Nodes\UndefinedLogicNode or $parent instanceof Nodes\GroupNode) {
                    // echo "Group..";
                    // print_r(json_encode($node->getParent()->getParent(), JSON_PRETTY_PRINT));
                    // print_r($node->getParent());
                    $node->getParent()->setOperator($new_node->getOperator());

                    $node->getParent()->replaceChild($node->getPos(), []);

                    foreach ($node->getParent()->getChilds() as $child) {
                        $new_node->addChild($child);
                    }

                    $p = $node->getParent()->getParent();

                    $node->getParent()->getParent()->replaceChild($node->getParent()->getPos(), [$new_node]);
                    // $node->getParent()->getParent()->setChildByKey($new_node, $node->getParent()->getPos());
                    
                    // print_r(json_encode($node->getParent()->getParent(), JSON_PRETTY_PRINT));

                    // print_r($node->getParent()->getParent());
                    // die();
                }
              
            }
        }
    }
}
