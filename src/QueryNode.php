<?php

namespace Railken\SQ;

class QueryNode
{


    /**
     * Key/Attribute
     *
     * @param string
     */
    public $key;

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
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     */
    public function __construct()
    {
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
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
}
