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

    public function testEq()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"eq","value":"1"}]'), json_decode(json_encode($query->parse('x eq 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"eq","value":"1"}]'), json_decode(json_encode($query->parse('x = 1'))));
    } 

    public function testGt()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"gt","value":"1"}]'), json_decode(json_encode($query->parse('x gt 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"gt","value":"1"}]'), json_decode(json_encode($query->parse('x > 1'))));
    }

    public function testGte()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"gte","value":"1"}]'), json_decode(json_encode($query->parse('x gte 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"gte","value":"1"}]'), json_decode(json_encode($query->parse('x >= 1'))));
    }

    public function testLt()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"lt","value":"1"}]'), json_decode(json_encode($query->parse('x lt 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"lt","value":"1"}]'), json_decode(json_encode($query->parse('x < 1'))));
    }

    public function testLte()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"lte","value":"1"}]'), json_decode(json_encode($query->parse('x lte 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"lte","value":"1"}]'), json_decode(json_encode($query->parse('x <= 1'))));
    }

    public function testCt()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"ct","value":"1"}]'), json_decode(json_encode($query->parse('x ct 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"ct","value":"1"}]'), json_decode(json_encode($query->parse('x *= 1'))));
    }

    public function testSw()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"sw","value":"1"}]'), json_decode(json_encode($query->parse('x sw 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"sw","value":"1"}]'), json_decode(json_encode($query->parse('x ^= 1'))));
    }

    public function testEw()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"ew","value":"1"}]'), json_decode(json_encode($query->parse('x ew 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"ew","value":"1"}]'), json_decode(json_encode($query->parse('x $= 1'))));
    }

    public function testIn()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"in","value":["1"]}]'), json_decode(json_encode($query->parse('x in 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"in","value":["1"]}]'), json_decode(json_encode($query->parse('x []= 1'))));
    }


    public function testAnd()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('{"operator":"and","key":null,"filters":[],"value":[{"operator":"eq","key":"x","filters":[],"value":"1"},{"operator":"eq","key":"x","filters":[],"value":"2"}]}'), json_decode(json_encode($query->parse('x = 1 and x = 2'))));
        
        $this->assertEquals(json_decode('{"operator":"and","key":null,"filters":[],"value":[{"operator":"eq","key":"x","filters":[],"value":"1"},{"operator":"eq","key":"x","filters":[],"value":"2"}]}'), json_decode(json_encode($query->parse('x = 1 && x = 2'))));

    }



    public function testBasic()
    {   
        $query = new QueryParser();

        $this->assertEquals(json_decode('[{"key":"x","filters":[{"name":"date_modify","parameters":["\"+1  days\"","false"]},{"name":"date","parameters":["\"d\""]}],"operator":"eq","value":"1"}]'), json_decode(json_encode($query->parse('x|date_modify("+1  days", false)|date("d") eq 1'))));

        $this->assertEquals(json_decode('{"key":null,"filters":[],"operator":"or","value":[{"key":"x","filters":[],"operator":"eq","value":"1"},{"key":null,"filters":[],"operator":"and","value":[{"key":"y","filters":[],"operator":"eq","value":"1"},{"key":"x","filters":[],"operator":"eq","value":"2"},{"key":"x","filters":[],"operator":"eq","value":"4"}]},{"key":null,"filters":[],"operator":"and","value":[{"key":"x","filters":[],"operator":"eq","value":"1"},{"key":"x","filters":[],"operator":"eq","value":"1"}]}]}
'), json_decode(json_encode($query->parse('x eq 1 or y eq 1 and x eq 2 and x eq 4 or x eq 1 and x eq 1'))));

        $this->assertEquals(json_decode('{"key":null,"filters":[],"operator":"or","value":[{"key":null,"filters":[],"operator":"or","value":[{"key":"title","filters":[],"operator":"eq","value":"something"},{"key":"rating","filters":[],"operator":"gt","value":"3"}]},{"key":null,"filters":[],"operator":"or","value":[{"key":"title","filters":[],"operator":"eq","value":"something else"},{"key":"rating","filters":[],"operator":"lt","value":"3"}]},{"key":"title","filters":[],"operator":"eq","value":"another"}]}
'), json_decode(json_encode($query->parse('(title eq "something" or rating gt 3) or (title eq "something else" or rating lt 3) or title eq "another"'))));



    }
}


