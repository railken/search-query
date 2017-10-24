<?php

namespace Railken\SQ;

use Railken\SQ\Exceptions;

class QueryNodeBridge
{

    /**
     * List of weights
     *
     * @var array
     */
    protected $weights = [
        Token::TOKEN_OPERATOR_AND => 2,
        Token::TOKEN_OPERATOR_OR => 1
    ];
    
    /**
     * Create a new node from support node
     *
     * @param FilterSupportNode $support_node
     *
     * @return Node
     */
    public function newBySupportNode($support_node)
    {
 
        $subs = [];

        $node = new QueryNode();
        $sub = (new QueryNode());


        foreach ($support_node->parts as $part) {
            if ($part instanceof QuerySupportNode) {
                $subs[] = $this->newBySupportNode($part);
            } else {
                if (in_array($part, [Token::TOKEN_OPERATOR_OR, Token::TOKEN_OPERATOR_AND])) {
                    $sub = (new QueryNode());
                    $subs[] = $part;
                } elseif (in_array($part, [Token::TOKEN_OPERATOR_EQ, Token::TOKEN_OPERATOR_GT, Token::TOKEN_OPERATOR_LT, Token::TOKEN_OPERATOR_IN, Token::TOKEN_OPERATOR_CONTAINS])) {
                    if ($sub->getKey() !== null) {
                        $sub->setOperator($part);
                    }
                } elseif ($part[0] === Token::TOKEN_FILTER_DELIMETER) {
                    $sub->addFilter(substr($part, 1));
                } else {
                    if ($sub->getKey() !== null && $sub->getValue() == null) {
                        $sub->setValue($part);
                    }

                    if ($sub->getKey() == null) {
                        $sub->setKey($part);
                    }
                }



                if ($sub->getKey() !== null && $sub->getValue() !== null && $sub->getOperator() !== null) {

                    # Remove '"' if present
                    if ($sub->getValue()[0] == "\"") {
                        $sub->setValue(substr($sub->getValue(), 1, -1));
                    }

                    # Explode into array if operator "in"
                    if ($sub->getOperator() == Token::TOKEN_OPERATOR_IN) {
                        $sub->setValue(explode(",", $sub->getValue()));
                    }

                    $subs[] = $sub;
                }
            }
        }
        # No Subs? Throw exception.
        if (count($subs) == 0)
            throw new Exceptions\QuerySyntaxException("Parts ".implode($support_node));

        $node = $this->groupNodes($node, $subs);

        return $node;
    }

    /**
     * Group node based on operator weight
     *
     * @param FilterNode $node
     * @param array $subs
     *
     * @return FilterNode
     */
    public function groupNodes($node, $subs)
    {
        if (count($subs) == 1) 
            return $subs;

        $last_operator = Token::TOKEN_OPERATOR_AND;

        foreach ($this->weights as $operator => $weight) {
            $positions = array_keys($subs, $operator, true);

            $last_position = null;
            $i = -1;
            $group_positions = [];

            if (count($positions) !== 0) {
                $last_operator = $operator;

                if ($weight > 1) {
                    foreach ($positions as $k => $position) {
                        if ($last_position != $position-2) {
                            $i++;
                        }

                        $group_positions[$i][] = $position;

                        $last_position = $position;
                    }

                    foreach ($group_positions as $position) {
                        $group = [];

                        for ($i = $position[0]-1; $i <= end($position)+1; $i++) {
                            if ($subs[$i] instanceof QueryNode) {
                                $group[] = $subs[$i];
                            }

                            $subs[$i] = null;
                        }

                        $subs[$position[0]-1] = (new QueryNode())->setValue($group)->setOperator($operator);
                    };
                }
            }
        }


        foreach ($subs as $k => $v) {
            if (is_string($v) || $v === null) {
                unset($subs[$k]);
            }
        }
                    
        $node->value = array_values($subs);
        $node->operator = $last_operator;

        return $node;
    }
}
