<?php

namespace Railken\SQ\Contracts;

interface ResolverContract
{
    /**
     * Resolve node.
     *
     * @param NodeContract $node
     *
     * @return NodeContract|null
     */
    public function resolve(NodeContract $node);
}
