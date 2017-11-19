<?php

namespace Railken\SQ;

class Token
{

    const TOKEN_WHITESPACE = ' ';
    const TOKEN_PHRASE_DELIMETER = '"';
    const TOKEN_ESCAPE = '\\';
    const TOKEN_FILTER_DELIMETER = '|';
    const TOKEN_OPENING_PARENTHESIS = '(';
    const TOKEN_CLOSING_PARENTHESIS = ')';

    const TOKEN_OPERATOR_AND = ['and', '&&'];
    const TOKEN_OPERATOR_OR = ['or', '||'];
    const TOKEN_OPERATOR_EQ = ['eq', '='];
    const TOKEN_OPERATOR_GT = ['gt', '>'];
    const TOKEN_OPERATOR_GTE = ['gte', '>='];
    const TOKEN_OPERATOR_LT = ['lt', '<'];
    const TOKEN_OPERATOR_LTE = ['lte', '<='];
    const TOKEN_OPERATOR_IN = ['in', '[]='];
    const TOKEN_OPERATOR_CONTAINS = ['ct', '*='];
    const TOKEN_OPERATOR_START_WITH = ['sw', '^='];
    const TOKEN_OPERATOR_END_WITH = ['ew', '$='];


}
