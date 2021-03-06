<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class ValueResolver extends BaseResolver implements ResolverContract
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\ValueNode::class;

    /**
     * Regex.
     *
     * @var array
     */
    public $regex = [
        "/'[^'\\\\]*(?:\\\\.[^'\\\\]*)*'/s",
        '/"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"/s',
        '/(?<![a-z])(-?(0|[1-9]\d*)?(\.\d+)?(?<=\d)(e-?(0|[1-9]\d*))?|0x[0-9a-f]+)/',
    ];

    /**
     * Parse the value before adding to the node.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function parseValue($value)
    {
        if (strlen($value) === 0) {
            return $value;
        }

        if ($value[0] === '"' || $value[0] === "'") {
            $value = substr($value, 1, -1);
        }

        $value = stripslashes($value);

        return $value;
    }
}
