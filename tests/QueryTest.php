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
       $query->parse('x');
    } 

    /**
     * @expectedException Railken\SQ\Exceptions\QuerySyntaxException
     */
    public function testQuerySyntaxException2()
    {
       $query = new QueryParser();
       $query->parse('x eq');
    }

    /**
     * @expectedException Railken\SQ\Exceptions\QuerySyntaxException
     */
    public function testQuerySyntaxException3()
    {
       $query = new QueryParser();
       $query->parse('x wrong 1');
    }

    /**
     * @expectedException Railken\SQ\Exceptions\QuerySyntaxException
     */
    public function testQuerySyntaxException5()
    {
       $query = new QueryParser();
       $query->parse('x|date( eq 1');
    }

    public function testBasic()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","operator":"eq","value":"1","filters":[{"name":"date_modify","parameters":["\"+1  days\"","false"]},{"name":"date","parameters":["\"d\""]}]}]'), json_decode(json_encode($query->parse('x|date_modify("+1  days", false)|date("d") eq 1'))));

        $this->assertEquals(json_decode('[{"key":"x","operator":"eq","value":"1","filters":[]}]'), json_decode(json_encode($query->parse('x eq 1'))));

        $this->assertEquals(json_decode('{"operator":"or","value":[{"key":"x","filters":[],"operator":"eq","value":"1"},{"operator":"and","value":[{"key":"y","filters":[],"operator":"eq","value":"1"},{"key":"x","filters":[],"operator":"eq","value":"2"},{"key":"x","filters":[],"operator":"eq","value":"4"}]},{"operator":"and","value":[{"key":"x","filters":[],"operator":"eq","value":"1"},{"key":"x","filters":[],"operator":"eq","value":"1"}]}]}'), json_decode(json_encode($query->parse('x eq 1 or y eq 1 and x eq 2 and x eq 4 or x eq 1 and x eq 1'))));

        $this->assertEquals(json_decode('{"operator":"or","value":[{"operator":"or","value":[{"key":"title","filters":[],"operator":"eq","value":"something"},{"key":"rating","filters":[],"operator":"gt","value":"3"}]},{"operator":"or","value":[{"key":"title","filters":[],"operator":"eq","value":"something else"},{"key":"rating","filters":[],"operator":"lt","value":"3"}]},{"key":"title","filters":[],"operator":"eq","value":"another"}]}'), json_decode(json_encode($query->parse('(title eq "something" or rating gt 3) or (title eq "something else" or rating lt 3) or title eq "another"'))));



    }
}


