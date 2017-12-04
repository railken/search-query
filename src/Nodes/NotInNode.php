<?php

namespace Railken\SQ\Nodes;

class NotInNode extends KeyNode
{
    /**
     * Operator
     *
     * @var string
     */
    public $operator = 'not_in';

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
