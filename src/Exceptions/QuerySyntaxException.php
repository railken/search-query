<?php

namespace Railken\SQ\Exceptions;

use Exception;

class QuerySyntaxException extends Exception
{
    public function __construct($query = '')
    {
        parent::__construct($query);
    }
}
