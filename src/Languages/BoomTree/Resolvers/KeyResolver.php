<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Railken\SQ\Traits\SplitNodeTrait;

class KeyResolver extends BaseResolver implements ResolverContract
{

    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\KeyNode::class;

    /**
     * Regex
     *
     * @var array
     */
    public $regex = [
        '/([a-z_][a-z0-9_]*)/i',
    ];
}
