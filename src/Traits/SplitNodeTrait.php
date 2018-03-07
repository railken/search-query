<?php

namespace Railken\SQ\Traits;

trait SplitNodeTrait
{

	public function splitNode($class, &$node, &$new_node, $position_to, $position_from) {

        $push = [];

        $first = new $class(substr($node->getValue(), 0, $position_to));

        if ($first->getValue() && !empty(rtrim($first->getValue()))) {
            $push[] = $first;
        }

        $push[] = $new_node;
        
        $second = new $class(substr($node->getValue(), $position_from));

        if ($second->getValue() && !empty(rtrim($second->getValue()))) {
            $push[] = $second;
        }

        $node->getParent()->replaceChild($node->getPos(), $push);

	}
}