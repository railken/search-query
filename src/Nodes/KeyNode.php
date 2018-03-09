<?php
namespace Railken\SQ\Nodes;

use Railken\SQ\StringHelper;

class KeyNode extends Node
{

    /**
     * Array representation of node
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => get_class($this),
            'value' => $this->getValue(),
        ];
    }
}
