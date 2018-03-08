<?php

namespace Railken\SQ\Traits;

trait SplitNodeTrait
{
    public function groupNode(&$node, &$new_node, $position_from, $position_to, $positions) {

        $position_node_from = $positions[$position_from];
        $position_node_to = $positions[$position_to];

        for ($i = $position_node_from; $i <= $position_node_to; $i++) {

            if ($i === $position_node_from) {

            } else if ($i === $position_node_to) {

            } else {

            }

            $node->removeChild($i);
        }


        $node->insertChildBefore($new_node, $i-1);
    }


	public function splitNode($class, &$node, &$new_node, $position_from, $position_to) {

        $push = [];

        $first = new $class();
        $first->setValue(substr($node->getValue(), 0, $position_from));

        if ($first->getValue() && !empty(rtrim($first->getValue()))) {
            $push[] = $first;
        }

        $push[] = $new_node;
        
        $second = new $class();
        $second->setValue(substr($node->getValue(), $position_to));

        if ($second->getValue() && !empty(rtrim($second->getValue()))) {
            $push[] = $second;
        }

        $node->getParent()->replaceChild($node->getPos(), $push);

	}
}