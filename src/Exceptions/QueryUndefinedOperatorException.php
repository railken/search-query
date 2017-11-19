<?php

namespace Railken\SQ\Exceptions;

use Exception;

class QueryUndefinedOperatorException extends QuerySyntaxException
{
    public function __construct($operator)
    {
        $this->message = sprintf("Operator %s not valid ", $operator);
    }
}
