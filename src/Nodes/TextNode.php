<?php
namespace Railken\SQ\Nodes;


class TextNode extends Node
{
    public function __construct($value = null)
    {
        $this->value = $value;
    }
}
