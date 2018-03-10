<?php
namespace Railken\SQ\Nodes;

use Railken\SQ\StringHelper;
use Railken\SQ\Contracts\ComparableNodeContract;

class KeyNode extends Node implements ComparableNodeContract
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
