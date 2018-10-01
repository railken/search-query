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
     * Children.
     *
     * @var array<NodeContract>
     */
    public $children = [];

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
     * Set children.
     *
     * @var array
     *
     * @return $this
     */
    public function setChildren($children)
    {
        $this->children = $children;

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
        $this->children[] = $child;

        $child->setParent($this);
        $child->setIndex(count($this->children) - 1);

        return $this;
    }

    /**
     * Add children.
     *
     * @param array $children
     *
     * @return $this
     */
    public function addChildren($children)
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * Get children.
     *
     * @return array<NodeContract>
     */
    public function getChildren()
    {
        return $this->children;
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
        return isset($this->children[$index]) ? $this->children[$index] : null;
    }

    /**
     * Count children.
     *
     * @return int
     */
    public function countChildren()
    {
        return count($this->children);
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
        foreach ($this->getChildren() as $child) {
            if ($child instanceof $class) {
                return $child;
            }
        }
    }

    /**
     * Retrieve children between indexes.
     *
     * @param int $start
     * @param int $end
     *
     * @return array
     */
    public function getChildrenBetweenIndexes(int $start, int $end)
    {
        return array_slice($this->children, $start, $end - $start + 1);
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
        $first = array_slice($this->children, 0, $index);
        $second = $index + 1 >= count($this->children) ? [] : array_slice($this->children, $index + 1, count($this->children) - ($index + 1));

        $this->children = [];

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
        unset($this->children[$index]);
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
     * Remove children.
     *
     * @param array $children
     *
     * @return $this
     */
    public function removeChildren($children)
    {
        array_splice($this->children, $children[0]->getIndex(), count($children));
        $this->flush();

        return $this;
    }

    /**
     * Add children before node.
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
     * Add children after node.
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
     * Remove all children.
     *
     * @return $this
     */
    public function removeAllChildren()
    {
        foreach ($this->children as $child) {
            unset($child);
        }

        $this->children = [];

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
        array_splice($this->children, $index, 1);

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
        $children = [];
        foreach ($this->children as $k => $child) {
            if ($child !== null) {
                $child->setIndex($n);
                $child->setParent($this);
                $children[] = $child;
                $n++;
            }
        }

        $this->children = $children;

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
            'children' => array_map(function ($node) {
                return $node->jsonSerialize();
            }, $this->getChildren()),
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
        if ($this->countChildren() === 0) {
            return $this->getValue();
        }

        return implode(' ', array_map(function ($node) use ($recursive) {
            return $recursive ? $node->valueToString() : $node->getValue();
        }, $this->getChildren()));
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
