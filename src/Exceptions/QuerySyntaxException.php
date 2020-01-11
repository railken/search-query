<?php

namespace Railken\SQ\Exceptions;

use Exception;

class QuerySyntaxException extends Exception
{
    public function __construct(string $error)
    {
        parent::__construct($error);
    }
}
