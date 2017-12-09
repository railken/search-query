<?php

namespace Railken\SQ\Nodes;

class LogicNode extends Node
{
    public function jsonSerialize()
    {
        return [
            'value' => $this->getChilds(),
        ];
    }

	
}
