<?php

namespace Railken\SQ;

use Railken\SQ\Exceptions;
use Railken\SQ\Nodes as Nodes;

class QueryNodeBridge
{
    protected $operators = [
        Token::TOKEN_OPERATOR_OR[0] => Nodes\OrNode::class,
        Token::TOKEN_OPERATOR_OR[1] => Nodes\OrNode::class,

        Token::TOKEN_OPERATOR_AND[0] => Nodes\AndNode::class,
        Token::TOKEN_OPERATOR_AND[1] => Nodes\AndNode::class,

        Token::TOKEN_OPERATOR_EQ[0] => Nodes\EqNode::class,
        Token::TOKEN_OPERATOR_EQ[1] => Nodes\EqNode::class,
        Token::TOKEN_OPERATOR_NOT_EQ[0] => Nodes\NotEqNode::class,
        Token::TOKEN_OPERATOR_NOT_EQ[1] => Nodes\NotEqNode::class,
        Token::TOKEN_OPERATOR_NOT_EQ[2] => Nodes\NotEqNode::class,
        Token::TOKEN_OPERATOR_NOT_EQ[3] => Nodes\NotEqNode::class,
        Token::TOKEN_OPERATOR_NOT_EQ[4] => Nodes\NotEqNode::class,
        Token::TOKEN_OPERATOR_GT[0] => Nodes\GtNode::class,
        Token::TOKEN_OPERATOR_GT[1] => Nodes\GtNode::class,
        Token::TOKEN_OPERATOR_GTE[0] => Nodes\GteNode::class,
        Token::TOKEN_OPERATOR_GTE[1] => Nodes\GteNode::class,
        Token::TOKEN_OPERATOR_LT[0] => Nodes\LtNode::class,
        Token::TOKEN_OPERATOR_LT[1] => Nodes\LtNode::class,
        Token::TOKEN_OPERATOR_LTE[0] => Nodes\LteNode::class,
        Token::TOKEN_OPERATOR_LTE[1] => Nodes\LteNode::class,
        Token::TOKEN_OPERATOR_CONTAINS[0] => Nodes\ContainsNode::class,
        Token::TOKEN_OPERATOR_CONTAINS[1] => Nodes\ContainsNode::class,
        Token::TOKEN_OPERATOR_START_WITH[0] => Nodes\StartWithNode::class,
        Token::TOKEN_OPERATOR_START_WITH[1] => Nodes\StartWithNode::class,
        Token::TOKEN_OPERATOR_END_WITH[0] => Nodes\EndWithNode::class,
        Token::TOKEN_OPERATOR_END_WITH[1] => Nodes\EndWithNode::class,
        Token::TOKEN_OPERATOR_IN[0] => Nodes\InNode::class,
        Token::TOKEN_OPERATOR_IN[1] => Nodes\InNode::class,
        Token::TOKEN_OPERATOR_NOT_IN[0] => Nodes\NotInNode::class,
        Token::TOKEN_OPERATOR_NOT_IN[1] => Nodes\NotInNode::class,
        Token::TOKEN_OPERATOR_NOT_IN[2] => Nodes\NotInNode::class,
        Token::TOKEN_OPERATOR_NOT_IN[3] => Nodes\NotInNode::class,

    ];

    /**
     * List of weights
     *
     * @var array
     */
    protected $weights = [
        Token::TOKEN_OPERATOR_AND[0] => 2,
        Token::TOKEN_OPERATOR_OR[0] => 1
    ];
        
    /**
     * Return if token is logic operator
     *
     * @param string $token
     *
     * @return boolean
     */
    public function isTokenLogic($token)
    {
        return in_array($token, array_merge(
            Token::TOKEN_OPERATOR_OR,
            Token::TOKEN_OPERATOR_AND
        ));
    }

    /**
     * Parse logic operator
     *
     * @param string $token
     *
     * @return boolean
     */
    public function parseTokenLogic($token)
    {
        if (in_array($token, Token::TOKEN_OPERATOR_OR)) {
            return Token::TOKEN_OPERATOR_OR[0];
        }

        if (in_array($token, Token::TOKEN_OPERATOR_AND)) {
            return Token::TOKEN_OPERATOR_AND[0];
        }
    }

    /**
     * Return if token is operator
     *
     * @param string $token
     *
     * @return boolean
     */
    public function isTokenOperator($token)
    {
        return in_array($token, array_merge(
            Token::TOKEN_OPERATOR_EQ,
            Token::TOKEN_OPERATOR_NOT_EQ,
            Token::TOKEN_OPERATOR_GT,
            Token::TOKEN_OPERATOR_GTE,
            Token::TOKEN_OPERATOR_LT,
            Token::TOKEN_OPERATOR_LTE,
            Token::TOKEN_OPERATOR_CONTAINS,
            Token::TOKEN_OPERATOR_START_WITH,
            Token::TOKEN_OPERATOR_END_WITH,
            Token::TOKEN_OPERATOR_IN,
            Token::TOKEN_OPERATOR_NOT_IN
        ));
    }

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

        $node = new QueryLogicNode();
        $sub = (new QueryKeyNode());
        
        foreach ($support_node->parts as $part) {
            if ($part instanceof QuerySupportNode) {
                $subs[] = $this->newBySupportNode($part);
            } else {
                if ($this->isTokenLogic($part)) {
                    $sub = new $this->operators[$part];

                    $subs[] = $this->parseTokenLogic($part);
                } elseif ($this->isTokenOperator($part)) {
                    if ($sub->getKey() !== null) {
                        $old_generic_sub = $sub;

                        $sub = new $this->operators[$part];
                        $sub->setFilters($old_generic_sub->getFilters());
                        $sub->setKey($old_generic_sub->getKey());
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
                    $subs[] = $sub;
                }
            }
        }

        # No Subs? Throw exception.
        if (count($subs) == 0) {
            throw new Exceptions\QuerySyntaxException("Parts ".json_encode($support_node));
        }


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
        if (count($subs) == 1) {
            return $subs;
        }

        $last_operator = Token::TOKEN_OPERATOR_AND[0];

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
                            if ($subs[$i] instanceof QueryBaseNode) {
                                $group[] = $subs[$i];
                            }

                            $subs[$i] = null;
                        }

                        $subs[$position[0]-1] = (new $this->operators[$operator])->setValue($group);
                    };
                }
            }
        }


        foreach ($subs as $k => $v) {
            if (is_string($v) || $v === null) {
                unset($subs[$k]);
            }
        }

        if (count($subs) == 1) {
            return $subs[0];
        }

        $node = (new $this->operators[$last_operator]);
        $node->value = array_values($subs);



        return $node;
    }
}
