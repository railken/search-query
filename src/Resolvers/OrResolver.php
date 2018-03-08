<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;

class OrResolver extends LogicResolver implements ResolverContract
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
        
        if ($node instanceof Nodes\KeyNode) {
            $this->resolveKeyNode($node);
        }
    }

    public function resolveKeyNode($node)
    {
        foreach ($this->regex as $regex) {
            preg_match($regex, $node->getValue(), $match, PREG_OFFSET_CAPTURE);

            if ($match) {
                $new_node = new $this->node;
                $start =  $match[0][1];
                $length = strlen($match[0][0]);

                $push = [];

                $parent = $node->getParent();

                if ($parent instanceof Nodes\OrNode) {
                    $node->getParent()->replaceChild($node->getPos(), []);
                } elseif ($parent instanceof Nodes\UndefinedLogicNode or $parent instanceof Nodes\GroupNode) {
                    $this->swapNodeUndefinedOrGroup($node, $new_node);
                } else {
                    $node->getParent()->replaceChild($node->getPos(), []);
     
                    foreach ($node->getParent()->getChilds() as $child) {
                        $new_node->addChild($child);
                    }
     
                    $p = $node->getParent()->getParent();
                    $p->setChildByKey($new_node, $node->getParent()->getPos());
                }
            }
        }
    }
}
