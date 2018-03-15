<?php

namespace Railken\SQ\Languages\BoomTree\Nodes;

use Railken\SQ\Contracts\ComparableNodeContract;

class ValueNode extends Node implements ComparableNodeContract
{
    /**
     * Array representation of node.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'type'  => get_class($this),
            'value' => $this->getValue(),
        ];
    }
}
