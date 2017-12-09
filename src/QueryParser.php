<?php

namespace Railken\SQ;

use Railken\SQ\Exceptions as Exceptions;

use Railken\SQ\Contracts\ResolverContract;

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
        $node = new Nodes\LogicOperatorNode();

        $t = new Nodes\TextNode();
        $t->setValue($query);
        $node->addChild($t);

        foreach ($this->resolvers as $token) {
            $token->resolve($node);
        }

        if (count($node->getChilds()) === 1) {
            return $node->getChild(0);
        }

        return $node;
    }
}
