<?php

namespace Railken\SQ\Nodes;

class NotInNode extends ComparisonOperatorNode
{
    /**
     * Operator
     *
     * @var string
     */
    public $value = 'NOT_IN';

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
