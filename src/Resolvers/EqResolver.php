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
        '/eq/i',
        '/=/i'
    ];
}


// sum(x, sum(4, 5))|number_format(8, 2) = 1