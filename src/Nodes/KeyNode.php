<?php
namespace Railken\SQ\Nodes;

use Railken\SQ\QueryKeyNode;

class KeyNode extends QueryKeyNode
{
	
	/**
	 * set value
	 *
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setValue($value)
	{
		# Remove '"' if present
		if ($value[0] == "\"") {
		    $value = substr($value, 1, -1);
		}

		$this->value = $value;

		return $this;
	}
}