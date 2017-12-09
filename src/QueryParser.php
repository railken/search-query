<?php

namespace Railken\SQ;

use Railken\SQ\Exceptions as Exceptions;

class QueryParser
{

    /**
     * Construct
     */
    public function __construct()
    {
        $this->tokens = [];
    }
    
    /**
     * Add tokens to resolve query
     *
     * @param array $tokens
     *
     * @return $this
     */
    public function addTokens($tokens)
    {
        $this->tokens = array_merge($this->tokens, $tokens);

        return $this;
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

        foreach ($this->tokens as $token) {
            $token->resolve($node);
        }

        if (count($node->getChilds()) === 1) {
            return $node->getChild(0);
        }

        return $node;
    }
}
