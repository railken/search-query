<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;

abstract class LogicResolver implements ResolverContract
{
    public function swapNodeUndefinedOrGroup($node, $new_node)
    {
        $node->getParent()->removeChild($node->getIndex(), []);

        foreach ($node->getParent()->getChilds() as $child) {
            $new_node->addChild($child);
        }


        $node->getParent()->getParent()->replaceChild($node->getParent()->getIndex(), [$new_node]);
    }
}
