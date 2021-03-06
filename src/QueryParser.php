<?php

namespace Railken\SQ;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;
use Railken\SQ\Languages\BoomTree\Resolvers as Resolvers;

class QueryParser
{
    /**
     * @var ResolverContract[]
     */
    protected $resolvers;

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->resolvers = [];
    }

    /**
     * Add resolvers to resolve query.
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
     * Convert the string query into an object (e.g.).
     *
     * @param string $query (e.g.) title eq 'something'
     *
     * @return \Railken\SQ\Contracts\NodeContract
     */
    public function parse($query)
    {
        $node = new Nodes\RootNode();
        $node->setValue($query);

        $l = new Nodes\UndefinedLogicNode();
        $node->addChild($l);

        $t = new Nodes\TextNode();
        $t->setValue($query);
        $l->addChild($t);

        // $this->addResolver(new Resolvers\TextResolver());

        foreach ($this->resolvers as $token) {
            $token->resolve($node);
        }

        // From Root to Logic
        $node = $node->getChildByIndex(0);

        if ($node === null) {
            throw new Exceptions\QuerySyntaxException($query);
        }

        $node->setParent(null);

        // If logic has only one child, skip to first key node
        if (count($node->getChildren()) === 1) {
            $node = $node->getChildByIndex(0);
            $node->setParent(null);
        }

        if ($node instanceof Nodes\UndefinedLogicNode) {
            throw new Exceptions\QuerySyntaxException($query);
        }

        return $node;
    }
}
