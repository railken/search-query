<?php

namespace Railken\SQ\Nodes;

class InNode extends ComparisonOperatorNode
{
    /**
     * Operator
     *
     * @var string
     */
    public $value = 'IN';

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
