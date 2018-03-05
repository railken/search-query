<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;

class AndResolver extends LogicResolver implements ResolverContract
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
    public $regex = ["/^(\s*)and(\s*)$/i", "/^(\s*)&&(\s*)$/i"];

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
                $start =  $match[0][1];
                $length = strlen($match[0][0]);


                $push = [];

                $parent = $node->getParent();
                if ($parent instanceof Nodes\AndNode) {
                    $node->getParent()->replaceChild($node->getPos(), []);
                }

                // print_r($node->getParent()->toArray());

                // Get child before and after
                $childs_before = $parent->getChildsBeforeKey($node->getPos());
                $childs_after = $parent->getChildsAfterKey($node->getPos());


                if (count($childs_before) === 0 || count($childs_after) === 0) {
                    $node->getParent()->removeChild($node->getPos());
                } else {
                    $child_before = $childs_before[count($childs_before) - 1];
                    $child_after = $childs_after[0];


                    if ($parent instanceof Nodes\OrNode) {

                        // Set the new node
                    

                        // Remove old parent
                        $node->getParent()->removeChild($child_before->getPos());
                        $node->getParent()->removeChild($child_after->getPos());

                        // Set new parent
                        $new_node->addChild($child_before);
                        $new_node->addChild($child_after);


                        $node->getParent()->replaceChild($node->getPos(), [$new_node]);
                    }


                    if ($parent instanceof Nodes\UndefinedLogicNode or $parent instanceof Nodes\GroupNode) {
                        $this->swapNodeUndefinedOrGroup($node, $new_node);
                    }
                }
            }
        }
    }

    public function swapNodeUndefinedOrGroup($node, $new_node)
    {
        $node->getParent()->removeChild($node->getPos(), []);

        foreach ($node->getParent()->getChilds() as $child) {
            $new_node->addChild($child);
        }


        $node->getParent()->getParent()->replaceChild($node->getParent()->getPos(), [$new_node]);
    }
}
