<?php

namespace Railken\SQ;

use Railken\SQ\Exceptions as Exceptions;

class QueryParser
{

    /**
     * Construct
     */
    public function __construct()
    {
    }

    /**
     * Convert the string query into an object (e.g.)
     *
     * @param string $query (e.g.) title eq 'something'
     *
     * @return Object
     */
    public function parse($query)
    {
        return (new QueryConverter($query))->convert();
    }
}
