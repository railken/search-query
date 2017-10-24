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
        $this->assertEquals(json_decode('[{"key":"x","operator":"eq","value":"1","filters":["date_modify(\"+1  days\",false)","date(\"d\")"]}]'), json_decode(json_encode($query->parse('x|date_modify("+1  days", false)|date("d") eq 1'))));

        $this->assertEquals(json_decode('[{"key":"x","operator":"eq","value":"1","filters":[]}]'), json_decode(json_encode($query->parse('x eq 1'))));

        $this->assertEquals(json_decode('{"key":null,"operator":"or","value":[{"key":"x","operator":"eq","value":"1","filters":[]},{"key":null,"operator":"and","value":[{"key":"y","operator":"eq","value":"1","filters":[]},{"key":"x","operator":"eq","value":"2","filters":[]},{"key":"x","operator":"eq","value":"4","filters":[]}],"filters":[]},{"key":null,"operator":"and","value":[{"key":"x","operator":"eq","value":"1","filters":[]},{"key":"x","operator":"eq","value":"1","filters":[]}],"filters":[]}],"filters":[]}'), json_decode(json_encode($query->parse('x eq 1 or y eq 1 and x eq 2 and x eq 4 or x eq 1 and x eq 1'))));

        $this->assertEquals(json_decode('{"key":null,"operator":"or","value":[{"key":null,"operator":"or","value":[{"key":"title","operator":"eq","value":"something","filters":[]},{"key":"rating","operator":"gt","value":"3","filters":[]}],"filters":[]},{"key":null,"operator":"or","value":[{"key":"title","operator":"eq","value":"something else","filters":[]},{"key":"rating","operator":"lt","value":"3","filters":[]}],"filters":[]},{"key":"title","operator":"eq","value":"another","filters":[]}],"filters":[]}'), json_decode(json_encode($query->parse('(title eq "something" or rating gt 3) or (title eq "something else" or rating lt 3) or title eq "another"'))));



    }
}


