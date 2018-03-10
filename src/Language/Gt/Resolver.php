<?php

namespace Railken\SQ\Language\Gt;

use Railken\SQ\Resolvers\ComparisonOperatorResolver;

class Resolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Node::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/(?<![^\s])gt(?![^\s])/i',
        '/(?<![^\s])\>(?![^\s])/i',
    ];
}
