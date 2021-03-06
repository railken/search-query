<?php

namespace Railken\SQ\Traits;

trait SplitNodeTrait
{
    public function groupNode(&$node, &$new_node, $position_from, $position_to, $positions = [])
    {
        $position_node_from = $positions[$position_from];
        $position_node_to = $positions[$position_to - 1];

        for ($i = $position_node_from; $i <= $position_node_to; ++$i) {
            $node->removeChildByIndex($position_node_from);
        }

        $index = $position_node_from - 1;

        $index === -1
            ? $node->addChildBeforeNodeByIndex($new_node, 0)
            : $node->addChildAfterNodeByIndex($new_node, $index);
    }

    public function splitNode($class, &$node, &$new_node, $position_from, $position_to)
    {
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

        $node->getParent()->replaceChild($node->getIndex(), $push);

        return $push;
    }

    public function splitMultipleNode($class, &$node, $positions)
    {
        $push = [];

        // We assume that positions won't overlap beetween eachother and they're in order (sort asc by from)
        $last_position_index = 0;

        $string = $node->getValue();
        $last_i = 0;

        for ($i = 0; $i <= strlen($string); ++$i) {
            $position = isset($positions[$last_position_index]) ? $positions[$last_position_index] : null;

            if ((($position !== null && $position['from'] === $i) || strlen($string) === $i) && ($i - $last_i > 0)) {
                $value = substr($node->getValue(), $last_i, $i - $last_i);

                if (!empty(rtrim($value))) {
                    $filler = new $class();
                    $filler->setValue($value);
                    $push[] = $filler;
                }
            }

            if (($position !== null && $position['from'] === $i)) {
                $push[] = $position['node'];
                ++$last_position_index;
                $i = $position['to'];
                $last_i = $i;
            }
        }

        $node->getParent()->replaceChild($node->getIndex(), $push);

        return $push;
    }
}
