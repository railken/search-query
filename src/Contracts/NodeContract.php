<?php

namespace Railken\SQ\Contracts;

interface NodeContract
{
    /**
     * Set value.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value);

    /**
     * Get value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set parent.
     *
     * @var NodeContract|null
     *
     * @return $this
     */
    public function setParent(self $parent = null);

    /**
     * Get parent.
     *
     * @return NodeContract|null
     */
    public function getParent();

    /**
     * Set index node.
     *
     * @param int $index
     *
     * @return $this
     */
    public function setIndex($index);

    /**
     * Retrieve index node.
     *
     * @return int
     */
    public function getIndex();

    /**
     * Set children.
     *
     * @var array
     *
     * @return $this
     */
    public function setChildren($children);

    /**
     * Add a child.
     *
     * @param NodeContract $child
     *
     * @return $this
     */
    public function addChild(self $child);

    /**
     * Add children.
     *
     * @param array $children
     *
     * @return $this
     */
    public function addChildren($children);

    /**
     * Get children.
     *
     * @return array
     */
    public function getChildren();

    /**
     * Get a child by index.
     *
     * @param int $index
     *
     * @return NodeContract
     */
    public function getChildByIndex($index);

    /**
     * Count children.
     *
     * @return int
     */
    public function countChildren();

    /**
     * Retrieve prev node.
     *
     * @return NodeContract|null
     */
    public function prev();

    /**
     * Retrieve next node.
     *
     * @return NodeContract|null
     */
    public function next();

    /**
     * Move the node from the old location to a new one as a child of $this.
     *
     * @param NodeContract $child
     *
     * @return $this
     */
    public function moveNodeAsChild(self $child);

    /**
     * Retrieve first child by class name.
     *
     * @param string $class
     *
     * @return NodeContract|null
     */
    public function getFirstChildByClass(string $class);

    /**
     * Retrieve children between indexes.
     *
     * @param int $start
     * @param int $end
     *
     * @return array
     */
    public function getChildrenBetweenIndexes(int $start, int $end);

    /**
     * Replace a child by others.
     *
     * @param int   $index
     * @param array $subs
     *
     * @return $this
     */
    public function replaceChild($index, $subs);

    /**
     * Remove a child by index.
     *
     * @param int $index
     *
     * @return $this
     */
    public function removeChildByIndex($index);

    /**
     * Remove a child.
     *
     * @param NodeContract $child
     *
     * @return $this
     */
    public function removeChild(self $child);

    /**
     * Remove children.
     *
     * @param array $children
     *
     * @return $this
     */
    public function removeChildren($children);

    /**
     * Add children before node.
     *
     * @param NodeContract $child
     * @param int          $index
     *
     * @return $this
     */
    public function addChildBeforeNodeByIndex(self $child, int $index);

    /**
     * Add children after node.
     *
     * @param NodeContract $child
     * @param int          $index
     *
     * @return $this
     */
    public function addChildAfterNodeByIndex(self $child, int $index);

    /**
     * Remove all children.
     *
     * @return $this
     */
    public function removeAllChildren();

    /**
     * Remove child by index.
     *
     * @param int  $index
     * @param bool $resort
     *
     * @return $this
     */
    public function removeChildByKey($index, $resort = true);

    /**
     * Reset parent and index of each node.
     *
     * @return $this
     */
    public function flush();

    /**
     * Swap parent and delete.
     *
     * @param NodeContract $old_parent
     * @param NodeContract $new_parent
     *
     * @return $this
     */
    public function swapParentAndDelete(self $old_parent, self $new_parent);

    /**
     * Array representation of node.
     *
     * @return array
     */
    public function toArray();

    /**
     * To string.
     *
     * @param bool $recursive
     *
     * @return string
     */
    public function valueToString($recursive = true);

    /**
     * Get root.
     *
     * @return NodeContract
     */
    public function getRoot();
}
