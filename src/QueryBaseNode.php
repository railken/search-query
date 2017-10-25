<?php

namespace Railken\SQ;

use Railken\SQ\StringHelper;


class QueryBaseNode
{


    /**
     * Operator of node
     *
     * @var string
     */
    public $operator;

    /**
     * Value/Values of node
     *
     * @var mixed
     */
    public $value;

    /**
     * Construct
     */
    public function __construct()
    {

    }

    /**
     * Set value
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * Set Operator
     *
     * @param string $operator
     *
     * @return $this
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

}