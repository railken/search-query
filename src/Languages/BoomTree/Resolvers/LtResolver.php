<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class LtResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved.
     *
     * @var string
     */
    public $node = Nodes\LtNode::class;

    /**
     * Regex
     *
     * @var array
     */
    public $regex = [
        '/lt/i',
        '/</i',
    ];
}
