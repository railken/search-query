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
        'and' => 2,
        'or' => 1
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
        $current_operator = null;
        $current_key = null;
        $current_value = null;
 
        $subs = [];

        $node = new QueryNode();


        foreach ($support_node->parts as $part) {
            if ($part instanceof QuerySupportNode) {
                $subs[] = $this->newBySupportNode($part);
            } else {
                if (in_array($part, [Token::TOKEN_OPERATOR_OR, Token::TOKEN_OPERATOR_AND])) {
                    $current_key = null;
                    $current_value = null;
                    $current_operator = null;
                    $subs[] = $part;
                } elseif (in_array($part, [Token::TOKEN_OPERATOR_EQ, Token::TOKEN_OPERATOR_GT, Token::TOKEN_OPERATOR_LT, Token::TOKEN_OPERATOR_IN, Token::TOKEN_OPERATOR_CONTAINS])) {
                    if ($current_key !== null) {
                        $current_operator = $part;
                    }
                } else {
                    if ($current_key !== null && $current_value == null) {
                        $current_value = $part;
                    }

                    if ($current_key == null) {
                        $current_key = $part;
                    }
                }

                if ($current_key !== null && $current_operator !== null && $current_value !== null) {

                    # Remove '"' if present
                    if ($current_value[0] == "\"") {
                        $current_value = substr($current_value, 1, -1);
                    }

                    # Explode into array if operator "in"
                    if ($current_operator == Token::TOKEN_OPERATOR_IN) {
                        $current_value = explode(",", $current_value);
                    }

                    $subs[] = (new QueryNode())->setKey($current_key)->setOperator($current_operator)->setValue($current_value);
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
