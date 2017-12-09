<?php

namespace Railken\SQ\Nodes;

class RootNode extends Node
{
    public function jsonSerialize()
    {
        return [
            'value' => $this->getChilds(),
        ];
    }
}
