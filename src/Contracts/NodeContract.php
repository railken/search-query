<?php

namespace Railken\SQ\Contracts;

interface NodeContract
{

    /**
     * Set value
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value);

    /**
     * Get value
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set parent
     *
     * @var NodeContract|null $parent
     *
     * @return $this
     */
    public function setParent(NodeContract $parent = null);

    /**
     * Get parent
     *
     * @return NodeContract|null
     */
    public function getParent();

    /**
     * Set index node
     *
     * @param int $index
     *
     * @return $this
     */
    public function setIndex($index);

    /**
     * Retrieve index node
     *
     * @return int
     */
    public function getIndex();

    /**
     * Set childs
     *
     * @var array $childs
     *
     * @return $this
     */
    public function setChilds($childs);

    /**
     * Add a child
     *
     * @param NodeContract $child
     *
     * @return $this
     */
    public function addChild(NodeContract $child);

    /**
     * Add childs
     *
     * @param array $childs
     *
     * @return $this
     */
    public function addChilds($childs);

    /**
     * Get childs
     *
     * @return array
     */
    public function getChilds();

    /**
     * Get a child by index
     *
     * @param integer $index
     *
     * @return NodeContract
     */
    public function getChildByIndex($index);

    /**
     * Count childs
     *
     * @return integer
     */
    public function countChilds();

    /**
     * Retrieve prev node
     *
     * @return NodeContract|null
     */
    public function prev();
    
    /**
     * Retrieve next node
     *
     * @return NodeContract|null
     */
    public function next();

    /**
     * Move the node from the old location to a new one as a child of $this
     *
     * @param NodeContract $child
     *
     * @return $this
     */
    public function moveNodeAsChild(NodeContract $child);

    /**
     * Retrieve first child by class name
     *
     * @param string $class
     *
     * @return NodeContract|null
     */
    public function getFirstChildByClass(string $class);

    /**
     * Retrieve childs between indexes
     *
     * @param int $start
     * @param int $end
     *
     * @return array
     */
    public function getChildsBetweenIndexes(int $start, int $end);


    /**
     * Replace a child by others
     *
     * @param integer $index
     * @param array $subs
     *
     * @return $this
     */
    public function replaceChild($index, $subs);

    /**
     * Remove a child by index
     *
     * @param int $index
     *
     * @return $this
     */
    public function removeChildByIndex($index);

    /**
     * Remove a child
     *
     * @param NodeContract $child
     *
     * @return $this
     */
    public function removeChild(NodeContract $child);

    /**
     * Remove childs
     *
     * @param array $childs
     *
     * @return $this
     */
    public function removeChilds($childs);

    /**
     * Add childs before node
     *
     * @param NodeContract $child
     * @param int $index
     *
     * @return $this
     */
    public function addChildBeforeNodeByIndex(NodeContract $child, int $index);

    /**
     * Add childs after node
     *
     * @param NodeContract $child
     * @param int $index
     *
     * @return $this
     */
    public function addChildAfterNodeByIndex(NodeContract $child, int $index);

    /**
     * Remove all childs
     *
     * @return $this
     */
    public function removeAllChilds();

    /**
     * Remove child by index
     *
     * @param int $index
     * @param bool $resort
     *
     * @return $this
     */
    public function removeChildByKey($index, $resort = true);

    /**
     * Reset parent and index of each node
     *
     * @return $this
     */
    public function flush();

    /**
     * Swap parent and delete
     *
     * @param NodeContract $old_parent
     * @param NodeContract $new_parent
     *
     * @return $this
     */
    public function swapParentAndDelete(NodeContract $old_parent, NodeContract $new_parent);

    /**
     * Array representation of node
     *
     * @return array
     */
    public function toArray();

    /**
     * To string
     *
     * @param boolean $recursive
     *
     * @return string
     */
    public function valueToString($recursive = true);

    /**
     * Get root
     *
     * @return NodeContract
     */
    public function getRoot();
}
