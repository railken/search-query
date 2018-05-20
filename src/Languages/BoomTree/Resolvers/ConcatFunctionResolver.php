<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class ConcatFunctionResolver extends FunctionResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\ConcatFunctionNode::class;

    /**
     * Regex.
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])concat(?![^\s])/i',
    ];
}
