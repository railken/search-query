<?php

use PHPUnit\Framework\TestCase;
use Railken\SQ\QueryParser;

class QueryTest extends TestCase
{

    /**
     * @expectedException Railken\SQ\Exceptions\QuerySyntaxException
     */
    public function testQuerySyntaxException1()
    {
       $query = new QueryParser();
       $query->parse('x eq');
    }

    /**
     * @expectedException Railken\SQ\Exceptions\QuerySyntaxException
     */
    public function testQuerySyntaxException2()
    {
       $query = new QueryParser();
       $query->parse('x wrong 1');
    }
   
    public function testBasic()
    {   
   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","operator":"eq","value":"1"}]'), json_decode(json_encode($query->parse('x eq 1'))));

        $this->assertEquals(json_decode('{"key":null,"operator":"or","value":[{"key":"x","operator":"eq","value":"1"},{"key":null,"operator":"and","value":[{"key":"y","operator":"eq","value":"1"},{"key":"x","operator":"eq","value":"2"},{"key":"x","operator":"eq","value":"4"}]},{"key":null,"operator":"and","value":[{"key":"x","operator":"eq","value":"1"},{"key":"x","operator":"eq","value":"1"}]}]}'), json_decode(json_encode($query->parse('x eq 1 or y eq 1 and x eq 2 and x eq 4 or x eq 1 and x eq 1'))));

        $this->assertEquals(json_decode('{"key":null,"operator":"or","value":[{"key":null,"operator":"or","value":[{"key":"title","operator":"eq","value":"something"},{"key":"rating","operator":"gt","value":"3"}]},{"key":null,"operator":"or","value":[{"key":"title","operator":"eq","value":"something else"},{"key":"rating","operator":"lt","value":"3"}]},{"key":"title","operator":"eq","value":"another"}]}'), json_decode(json_encode($query->parse('(title eq "something" or rating gt 3) or (title eq "something else" or rating lt 3) or title eq "another"'))));



    }
}


