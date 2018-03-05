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
    public $node = Nodes\GroupNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = '/\([^()"]*(?:"[^"]*"[^()"]*)*\)/i';
    
    /**
     * Resolve token eq node
     *
     * @param Node
     *
     * @return $this
     */
    public function resolve(NodeContract $node)
    {


        if ($node instanceof Nodes\TextNode) {
            return;
        }

        $childs = $node->getChilds();
        
        if (count($childs) > 0) {
            foreach ($node->getChilds() as $child) {
                $this->resolve($child);
            }

        }
        

        $this->resolveTextNodes($node);        
    }

  
    public function resolveTextNodes($node)
    {

        $key = 0;

        $texts = array_map(function($child) { 
            return $child->getValue();
        }, array_filter($node->getChilds(), function($child) {
            return $child instanceof Nodes\TextNode;
        }));

        $positions = [];
        $i = 0;
        foreach ($node->getChilds() as $child) {
            $y = 0;
            if ($child instanceof Nodes\TextNode) {
                foreach (str_split($child->getValue()) as $char) {
                    $positions[$i++] = [
                        'char' => $y++, 
                        'node' => $child->getPos(),
                    ];
                }

                foreach ($positions as $key => $pos) {
                    if ($pos['node'] === $child->getPos()) {
                        $positions[$key]['remaining_char'] = $y-1 - $pos['char'];
                    }
                }

            }
        }

        $text = implode("", $texts);

        if (preg_match($this->regex, $text, $match, PREG_OFFSET_CAPTURE)) {

            $start =  $match[0][1]; 
            $length = strlen($match[0][0]);

            // Key of first char
            $key_first = $positions[$start];

            // Key of last char
            $key_last = $positions[$start+$length-1];

 
            $push = []; 
            $new_node = new $this->node; 
            $result = substr($match[0][0], 1, -1);
            $text_node = new Nodes\TextNode($result);

            if ($key_first['node'] !== $key_last['node']) {
                // print_r($positions);
            }

            if ($key_first['node'] === $key_last['node']) {

                $new_node->addChild($text_node); 
            }

            if ($key_first['node'] !== $key_last['node']) {

                for ($i = $key_first['node']; $i <= $key_last['node']; $i++) {
                    $child = $node->getChild($i);

                    if ($child instanceof Nodes\Textnode) {

                        if ($i === $key_first['node']) {
                            // print_r($texts[$key_first['node']]);
                            $first = new Nodes\TextNode(substr($text, $start+1, $key_first['remaining_char'])); 
                            // print_r($first->getValue());
                            // $first = new Nodes\TextNode(substr($texts[$key_first['node']], 0, strlen($texts[$key_last['node']]) - $key_last['remaining_char'] - 1)); 
                            if (trim($first->getValue()))
                                $new_node->addChild($first);
                        } else if ($i === $key_last['node']) {

                            // print_r($key_last);
                            // print_r($texts);
                            $last = new Nodes\TextNode(substr($texts[$key_last['node']], 0, strlen($texts[$key_last['node']]) - $key_last['remaining_char'] - 1)); 
                            if (trim($last->getValue()))
                                $new_node->addChild($last);
                        } else {
                            $new_node->addChild($child);
                        }

                    } else {
                        $new_node->addChild($child);
                    }

                    $child->setParent($new_node);
                }
            }

            $first = new Nodes\TextNode(substr($text, $start-$key_first['char'], $start)); 

            if (trim($first->getValue()))
                $push[] = $first; 
            

            $push[] = $new_node; 
            $second = new Nodes\TextNode(substr($text, $start+$length, $length+$key_last['remaining_char'])); 
            
            if (trim($second->getValue()))
                $push[] = $second; 


            if (count($push) === 1 && $push[0]->countChilds() === 1 && $push[0]->getChild(0) instanceof Nodes\GroupNode) {
                $push = [$push[0]->getChild(0)];
            }
            
            for ($i = $key_first['node']; $i <= $key_last['node']; $i++) {
                $node->replaceChild($i, []);
            }
            $node->replaceChild($key_first['node'], $push);

            foreach ($new_node->getChilds() as $child) {
                $child->setParent($new_node);
            }
            
            $this->resolveTextNodes($node);



        }

    }

}
