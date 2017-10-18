<?php

namespace Railken\SQ\Exceptions;

use Exception;

class QuerySyntaxException extends Exception
{
    public function __construct($query)
    {
        $this->message = sprintf("Syntax error in %s", $query);
    }
}
