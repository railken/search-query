<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;
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
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/([a-z_][a-z0-9_]*)/i',
    ];
}
