<?php

namespace Railken\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Nodes as Nodes;

class GroupingResolver implements ResolverContract
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = Nodes\LogicOperatorNode::class;

    /**
     * Regex token 
     *
     * @var string
     */
    public $regex = ['/\([^()"]*(?:"[^"]*"[^()"]*)*\)/'];

    /**
     * Resolve token eq node
     *
     * @param Node
     *
     * @return $this
     */
    public function resolve(NodeContract $node)
    {

        $nk = 0;
        $offset = 0;

        $key = 0;
        while($child = $node->getChild($key)) {
            if ($child instanceof Nodes\TextNode) {
                foreach ($this->regex as $regex) {
                    preg_match($regex, $child->getValue(), $match, PREG_OFFSET_CAPTURE);

                    if ($match) {
                        $push = [];
                        $new_node = new $this->node;
                        $new_node->addChild(new Nodes\TextNode(substr($match[0][0], 1, -1)));

                        $start =  $match[0][1];
                        $length = strlen($match[0][0]);


                        $child = $node->getChilds()[$key];

                        $first = new Nodes\TextNode(substr($child->getValue(), 0, $start));

                        if ($first->getValue())
                            $push[] = $first;

                        $push[] = $new_node;

                        $second = new Nodes\TextNode(substr($child->getValue(), $start+$length));

                        if ($second->getValue())
                            $push[] = $second;

                        $node->replaceChild($key, $push);
                    }    
                }
            }

            $key++;
        }



    }
}
