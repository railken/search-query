<?php

namespace Railken\SQ;

use Railken\SQ\Exceptions as Exceptions;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Resolvers as Resolvers;

class QueryParser
{

    /**
     * @var ResolverContract[]
     */
    protected $resolvers;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->resolvers = [];
    }
    
    /**
     * Add resolvers to resolve query
     *
     * @param array $resolvers
     *
     * @return $this
     */
    public function addResolvers($resolvers)
    {
        foreach ($resolvers as $resolver) {
            $this->addResolver($resolver);
        }

        return $this;
    }

    public function addResolver(ResolverContract $resolver)
    {
        $this->resolvers[] = $resolver;
    }

    /**
     * Convert the string query into an object (e.g.)
     *
     * @param string $query (e.g.) title eq 'something'
     *
     * @return Object
     */
    public function parse($query)
    {
        $node = new Nodes\RootNode();

        $l = new Nodes\UndefinedLogicNode();
        $node->addChild($l);

        $t = new Nodes\TextNode();
        $t->setValue($query);
        $l->addChild($t);

        $this->addResolver(new Resolvers\TextResolver());

        foreach ($this->resolvers as $token) {
            $token->resolve($node);
        }

        // From Root to Logic
        $node = $node->getChild(0);
        $node->setParent(null);

        // If logic has only one child, skip to first key node
        if (count($node->getChilds()) === 1) {
            $node = $node->getChild(0);
            $node->setParent(null);
        }

        return $node;
    }
}
