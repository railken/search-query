<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class LteResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\LteNode::class;

    /**
     * Regex
     *
     * @var array
     */
    public $regex = [
        '/lte/i',
        '/<=/i'
    ];
}
