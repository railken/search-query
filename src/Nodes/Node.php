<?php

namespace Railken\SQ\Nodes;

use Railken\SQ\Contracts\NodeContract;

class Node implements NodeContract, \JsonSerializable
{
    /**
     * Value/Values of node
     *
     * @var mixed
     */
    public $value;

    /**
     * Index node
     *
     * @var int
     */
    public $index;

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
        is_array($value) && $value = array_map(function ($v) {
            return trim($v);
        }, $value);

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

    /**
     * Set index node
     *
     * @param int $index
     *
     * @return $this
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Retrieve index node
     *
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
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
     * Add a child
     *
     * @param Node $child
     *
     * @return $this
     */
    public function addChild(Node $child)
    {
        $this->childs[] = $child;

        $child->setParent($this);
        $child->setIndex(count($this->childs)-1);

        return $this;
    }

    /**
     * Add childs
     *
     * @param array $childs
     *
     * @return $this
     */
    public function addChilds($childs)
    {
        foreach ($childs as $child) {
            $this->addChild($child);
        }

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
     * Get a child by index
     *
     * @param integer $index
     *
     * @return Node
     */
    public function getChildByIndex($index)
    {
        return isset($this->childs[$index]) ? $this->childs[$index] : null;
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

    /**
     * Retrieve prev node
     *
     * @return Node
     */
    public function prev()
    {
        return $this->getParent() ? $this->getParent()->getChildByIndex($this->getIndex()-1) : null;
    }
    
    /**
     * Retrieve next node
     *
     * @return Node
     */
    public function next()
    {
        return $this->getParent() ? $this->getParent()->getChildByIndex($this->getIndex()+1) : null;
    }

    /**
     * Move the node from the old location to a new one as a child of $this
     *
     * @param Node $child
     *
     * @return $this
     */
    public function moveNodeAsChild(Node $child)
    {
        $child->getParent()->removeChild($child);
        $this->addChild($child);
    }


    /**
     * Retrieve first child by class name
     *
     * @param string $class
     *
     * @return Node
     */
    public function getFirstChildByClass($class)
    {
        foreach ($this->getChilds() as $child) {
            if ($child instanceof $class) {
                return $child;
            }
        }

        return null;
    }

    /**
     * Retrieve childs between indexes
     *
     * @param int $start
     * @param int $end
     *
     * @return array
     */
    public function getChildsBetweenIndexes($start, $end)
    {
        return array_slice($this->childs, $start, $end-$start+1);
    }


    /**
     * Replace a child by others
     *
     * @param integer $index
     * @param array $subs
     *
     * @return $this
     */
    public function replaceChild($index, $subs)
    {
        $first = array_slice($this->childs, 0, $index);
        $second = $index+1 >= count($this->childs) ? [] : array_slice($this->childs, $index+1, count($this->childs)-($index+1));

        $this->childs = [];
        
        foreach (array_merge($first, $subs, $second) as $child) {
            $child && $this->addChild($child);
        }
        $this->flush();
        return $this;
    }

    /**
     * Remove a child by index
     *
     * @param int $index
     *
     * @return $this
     */
    public function removeChildByIndex($index)
    {
        $this->childs[$index] = null;
        $this->flush();

        return $this;
    }

    /**
     * Remove a child
     *
     * @param Node $child
     *
     * @return $this
     */
    public function removeChild(Node $child)
    {
        return $this->removeChildByIndex($child->getIndex());
    }

    /**
     * Remove childs
     *
     * @param array $childs
     *
     * @return $this
     */
    public function removeChilds($childs)
    {
        array_splice($this->childs, $childs[0]->getIndex(), count($childs));
        $this->flush();

        return $this;
    }


    /**
     * Add childs before node
     *
     * @param array $childs
     * @param node $node
     *
     * @return $this
     */
    public function addChildBeforeNodeByIndex($child, $index)
    {
        return $this->replaceChild($index, [$child, $this->getChildByIndex($index)]);
    }

    /**
     * Add childs after node
     *
     * @param array $childs
     * @param node $node
     *
     * @return $this
     */
    public function addChildAfterNodeByIndex($child, $index)
    {
        return $this->replaceChild($index, [$this->getChildByIndex($index), $child]);
    }

    /**
     * Remove all childs
     *
     * @return $this
     */
    public function removeAllChilds()
    {
        foreach ($this->childs as $child) {
            unset($child);
        }

        $this->childs = [];

        return $this;
    }


    /**
     * Remove child by index
     *
     * @param int $index
     * @param bool $resort
     *
     * @return $this
     */
    public function removeChildByKey($index, $resort = true)
    {
        array_splice($this->childs, $index, 1);

        if ($resort) {
            $this->flush();
        }

        return $this;
    }

    /**
     * Reset parent and index of each node
     *
     * @return $this
     */
    public function flush()
    {
        $n = 0;
        $childs = [];
        foreach ($this->childs as $k => $child) {
            if ($child) {
                $child->setIndex($n);
                $child->setParent($this);
                $childs[] = $child;
                $n++;
            }
        }

        $this->childs = $childs;
        
        return $this;
    }


    /**
     * Swap parent and delete
     *
     * @param Node $old_parent
     * @param Node $new_parent
     *
     * @return $this
     */
    public function swapParentAndDelete(Node $old_parent, Node $new_parent)
    {
        $new_parent->moveNodeAsChild($this);
        $new_parent->removeChild($old_parent);

        return $this;
    }

    /**
     * Serialize to json
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Array representation of node
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => get_class($this),
            'value' => $this->getValue(),
            'childs' => array_map(function ($node) {
                return $node->jsonSerialize();
            }, $this->getChilds()),
        ];
    }

    /**
     * To string
     *
     * @param boolean $recursive
     *
     * @return string
     */
    public function valueToString($recursive = true)
    {
        if ($this->countChilds() === 0) {
            return $this->getValue();
        }
        
        return implode(" ", array_map(function ($node) use ($recursive) {
            return $recursive ? $node->valueToString() : $node->getValue();
        }, $this->getChilds()));
    }

    /**
     * Get root
     *
     * @return Node
     */
    public function getRoot()
    {
        if ($this->getParent()) {
            return $this->getParent()->getRoot();
        }

        return $this;
    }
}
