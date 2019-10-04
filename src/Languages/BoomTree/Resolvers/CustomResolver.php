<?php

namespace Railken\SQ\Languages\BoomTree\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Railken\SQ\Contracts\NodeContract;
use Closure;

class CustomResolver extends BaseResolver implements ResolverContract
{
    /**
     * @var Closure $resolver
     */
    protected $resolver;

    /**
     * Create a new instance of Resolver
     *
     * @param Closure $resolver
     */
    public function __construct(Closure $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Resolve node.
     *
     * @param NodeContract $node
     *
     * @return NodeContract|null
     */
    public function resolve(NodeContract $node)
    {
        $children = $node->getChildren();

        if (count($children) > 0) {
            $this->resolve($node->getChildByIndex(0));
        }

        $resolver = $this->resolver;
        $resolver($node);

        return $node->next() !== null ? $this->resolve($node->next()) : null;
    }
}
