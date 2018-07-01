<?php

namespace Railken\SQ\Languages\BoomTree\Nodes;

use Railken\SQ\Contracts\NodeContract;

class Node implements NodeContract, \JsonSerializable
{
    /**
     * Value/Values of node.
     *
     * @var mixed
     */
    public $value;

    /**
     * Index node.
     *
     * @var int
     */
    public $index;

    /**
     * Childs.
     *
     * @var array<NodeContract>
     */
    public $childs = [];

    /**
     * Parent node.
     *
     * @var NodeContract|null
     */
    public $parent;

    /**
     * Construct.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * Set value.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        is_string($value) && $value = trim($value);

        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set parent.
     *
     * @var NodeContract|null
     *
     * @return $this
     */
    public function setParent(NodeContract $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return NodeContract|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set index node.
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
     * Retrieve index node.
     *
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set childs.
     *
     * @var array
     *
     * @return $this
     */
    public function setChilds($childs)
    {
        $this->childs = $childs;

        return $this;
    }

    /**
     * Add a child.
     *
     * @param NodeContract $child
     *
     * @return $this
     */
    public function addChild(NodeContract $child)
    {
        $this->childs[] = $child;

        $child->setParent($this);
        $child->setIndex(count($this->childs) - 1);

        return $this;
    }

    /**
     * Add childs.
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
     * Get childs.
     *
     * @return array<NodeContract>
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Get a child by index.
     *
     * @param int $index
     *
     * @return NodeContract|null
     */
    public function getChildByIndex($index)
    {
        return isset($this->childs[$index]) ? $this->childs[$index] : null;
    }

    /**
     * Count childs.
     *
     * @return int
     */
    public function countChilds()
    {
        return count($this->childs);
    }

    /**
     * Retrieve prev node.
     *
     * @return NodeContract|null
     */
    public function prev()
    {
        return $this->getParent() !== null ? $this->getParent()->getChildByIndex($this->getIndex() - 1) : null;
    }

    /**
     * Retrieve next node.
     *
     * @return NodeContract|null
     */
    public function next()
    {
        return $this->getParent() !== null ? $this->getParent()->getChildByIndex($this->getIndex() + 1) : null;
    }

    /**
     * Move the node from the old location to a new one as a child of $this.
     *
     * @param NodeContract $child
     *
     * @return $this
     */
    public function moveNodeAsChild(NodeContract $child)
    {
        if ($child->getParent() !== null) {
            $child->getParent()->removeChild($child);
            $this->addChild($child);
        }
    }

    /**
     * Retrieve first child by class name.
     *
     * @param string $class
     *
     * @return NodeContract
     */
    public function getFirstChildByClass(string $class)
    {
        foreach ($this->getChilds() as $child) {
            if ($child instanceof $class) {
                return $child;
            }
        }
    }

    /**
     * Retrieve childs between indexes.
     *
     * @param int $start
     * @param int $end
     *
     * @return array
     */
    public function getChildsBetweenIndexes(int $start, int $end)
    {
        return array_slice($this->childs, $start, $end - $start + 1);
    }

    /**
     * Replace a child by others.
     *
     * @param int   $index
     * @param array $subs
     *
     * @return $this
     */
    public function replaceChild($index, $subs)
    {
        $first = array_slice($this->childs, 0, $index);
        $second = $index + 1 >= count($this->childs) ? [] : array_slice($this->childs, $index + 1, count($this->childs) - ($index + 1));

        $this->childs = [];

        foreach (array_merge($first, $subs, $second) as $child) {
            if ($child) {
                $this->addChild($child);
            }
        }
        $this->flush();

        return $this;
    }

    /**
     * Remove a child by index.
     *
     * @param int $index
     *
     * @return $this
     */
    public function removeChildByIndex($index)
    {
        unset($this->childs[$index]);
        $this->flush();

        return $this;
    }

    /**
     * Remove a child.
     *
     * @param NodeContract $child
     *
     * @return $this
     */
    public function removeChild(NodeContract $child)
    {
        return $this->removeChildByIndex($child->getIndex());
    }

    /**
     * Remove childs.
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
     * Add childs before node.
     *
     * @param NodeContract $child
     * @param int          $index
     *
     * @return $this
     */
    public function addChildBeforeNodeByIndex(NodeContract $child, int $index)
    {
        return $this->replaceChild($index, [$child, $this->getChildByIndex($index)]);
    }

    /**
     * Add childs after node.
     *
     * @param NodeContract $child
     * @param int          $index
     *
     * @return $this
     */
    public function addChildAfterNodeByIndex(NodeContract $child, int $index)
    {
        return $this->replaceChild($index, [$this->getChildByIndex($index), $child]);
    }

    /**
     * Remove all childs.
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
     * Remove child by index.
     *
     * @param int  $index
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
     * Reset parent and index of each node.
     *
     * @return $this
     */
    public function flush()
    {
        $n = 0;
        $childs = [];
        foreach ($this->childs as $k => $child) {
            if ($child !== null) {
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
     * Swap parent and delete.
     *
     * @param NodeContract $old_parent
     * @param NodeContract $new_parent
     *
     * @return $this
     */
    public function swapParentAndDelete(NodeContract $old_parent, NodeContract $new_parent)
    {
        $new_parent->moveNodeAsChild($this);
        $new_parent->removeChild($old_parent);

        return $this;
    }

    /**
     * Serialize to json.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Array representation of node.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'type'   => get_class($this),
            'value'  => $this->getValue(),
            'childs' => array_map(function ($node) {
                return $node->jsonSerialize();
            }, $this->getChilds()),
        ];
    }

    /**
     * To string.
     *
     * @param bool $recursive
     *
     * @return string
     */
    public function valueToString($recursive = true)
    {
        if ($this->countChilds() === 0) {
            return $this->getValue();
        }

        return implode(' ', array_map(function ($node) use ($recursive) {
            return $recursive ? $node->valueToString() : $node->getValue();
        }, $this->getChilds()));
    }

    /**
     * Get root.
     *
     * @return NodeContract
     */
    public function getRoot()
    {
        if ($this->getParent() !== null) {
            return $this->getParent()->getRoot();
        }

        return $this;
    }
}
