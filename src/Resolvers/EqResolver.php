<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Nodes as Nodes;

class EqResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\EqNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/(?<![^\s])eq(?![^\s])/i',
        '/(?<![^\s])=(?![^\s])/i'
    ];
}