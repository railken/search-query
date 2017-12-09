<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;
use Railken\SQ\Exceptions as Exceptions;

class TextResolver implements ResolverContract
{


    /**
     * Resolve node
     *
     * @param Node
     *
     * @return $this
     */
    public function resolve(NodeContract $node)
    {
        // $node->content = "";

        $childs = $node->getChilds();
        
        if (count($childs) > 0) {
            foreach ($node->getChilds() as $child) {
                $this->resolve($child);
            }

            return;
        }
        
        if ($node instanceof Nodes\TextNode) {
            $this->resolveTextNode($node);
        }
    }

    /**
     * Resolve text nodes
     *
     * @param Node
     *
     * @return $this
     */
    public function resolveTextNode($node)
    {
        if (empty(trim($node->getValue()))) {
            $node->getParent()->replaceChild($node->getPos(), []);
        } else {
            throw new Exceptions\QuerySyntaxException($node->getValue());
        }
    }
}
