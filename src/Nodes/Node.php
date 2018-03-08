<?php

namespace Railken\SQ\Nodes;

use Railken\SQ\Contracts\NodeContract;

class Node implements NodeContract, \JsonSerializable
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

    public $pos;

    /**
     * Childs
     *
     * @var array
     */
    public $childs = [];

    /**
     * Parent node
     *
     * @var Node
     */
    public $parent;

    /**
     * Construct
     */
    public function __construct()
    {
        // ...
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
        is_string($value) && $value = trim($value);
        is_array($value) && $value = array_map(function($v) { return trim($v); }, $value);

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

    /**
     * Set parent
     *
     * @var Node $parent
     *
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function setPos($pos)
    {
        $this->pos = $pos;

        return $this;
    }

    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Get parent
     *
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set childs
     *
     * @var array $childs
     *
     * @return $this
     */
    public function setChilds($childs)
    {
        $this->childs = $childs;
    
        return $this;
    }

    /**
     * Set childs
     *
     * @var array $childs
     *
     * @return $this
     */
    public function setChildByKey($child, $key)
    {
        $this->childs[$key] = $child;
        $child->setParent($this);
        $child->setPos($key);
    
        return $this;
    }


    /**
     * Add a child
     *
     * @param Node $child
     *
     * @return $this
     */
    public function addChild($child)
    {
        $this->childs[] = $child;

        $child->setParent($this);
        $child->setPos(count($this->childs)-1);

        return $this;
    }

    /**
     * Get childs
     *
     * @return array
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Get a child by key
     *
     * @param integer $key
     *
     * @return Node
     */
    public function getChild($key)
    {
        return isset($this->childs[$key]) ? $this->childs[$key] : null;
    }

    /**
     * Count childs
     *
     * @return integer
     */
    public function countChilds()
    {
        return count($this->childs);
    }

    public function next()
    {
        return $this->getParent() ? $this->getParent()->getChild($this->getPos()+1) : null;
    }

    public function prev()
    {
        return $this->getParent() ? $this->getParent()->getChild($this->getPos()-1) : null;
    }
    
    public function moveNodeAsChild($child)
    {   
        $child->getParent()->removeChild($child->getPos());
        $this->addChild($child);
    }

    public function getFirstChildByClass($class)
    {
        foreach ($this->getChilds() as $child) {
            if ($child instanceof $class)
                return $child;
        }

        return null;
    }


    /**
     * Replace a child by others
     *
     * @param integer $key
     * @param array $subs
     *
     * @return $this
     */
    public function replaceChild($key, $subs)
    {
        $first = array_slice($this->childs, 0, $key);
        $second = $key+1 >= count($this->childs) ? [] : array_slice($this->childs, $key+1, count($this->childs)-($key+1));

        $this->childs = [];
        
        foreach (array_merge($first, $subs, $second) as $child) {
            $this->addChild($child);
        }
        return $this;
    }

    public function removeChild($key)
    {
        return $this->replaceChild($key, []);
    }

    public function insertChildBefore($child, $key)
    {

        return $this->replaceChild($key, [$child, $this->getChild($key)]);
    }

    public function unsetChilds()
    {
        foreach ($this->childs as $child) {
            unset($child);
        }

        $this->childs = [];
    }


    public function removeChildByKey($key, $resort = true)
    {
        array_splice($this->childs, $key, 1);

        if ($resort) {
            $this->calculatePosChilds();
        }
    }

    public function calculatePosChilds()
    {
        foreach ($this->childs as $key => $child) {
            $child->setPos($key);
        }
    }

    public function getChildsAfterKey($key)
    {
        return array_slice($this->childs, $key+1);
    }
    public function getChildsBeforeKey($key)
    {
        return array_slice($this->childs, 0, $key);
    }



    public function jsonSerialize()
    {
        return [
            'type' => get_class($this),
            'value' => $this->getValue(),
        ];
    }

    public function toArray()
    {
        return $this->jsonSerialize();
    }

    public function valueToString()
    {
        if ($this->countChilds() === 0)
            return $this->getValue();
        
        return implode(" ", array_map(function ($node) {
            return $node->valueToString();
        }, $this->getChilds()));
    }
}
