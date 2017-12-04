<?php

namespace Railken\SQ\Nodes;

class InNode extends KeyNode
{
    /**
     * Operator
     *
     * @var string
     */
    public $operator = 'in';

    /**
     * set value
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $value = explode(",", $value);

        return parent::setValue($value);
    }
}
