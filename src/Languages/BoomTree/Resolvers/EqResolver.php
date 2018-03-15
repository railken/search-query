<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class EqResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\EqNode::class;

    /**
     * Regex.
     *
     * @var array
     */
    public $regex = [
        '/(?<![^\s])eq(?![^\s])/i',
        '/(?<![^\s])=(?![^\s])/i',
    ];
}
