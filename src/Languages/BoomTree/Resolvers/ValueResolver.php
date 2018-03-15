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
        '/\'([^\']+)\'/i',
        '/"([^"]+)"/i',
        '/(0|[1-9][\.\d]*)/',
    ];
}
