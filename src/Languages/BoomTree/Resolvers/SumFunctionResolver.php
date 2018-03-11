<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class SumFunctionResolver extends FunctionResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\SumFunctionNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/(?<![^\s])sum(?![^\s])/i'
    ];
}
