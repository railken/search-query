<?php

use PHPUnit\Framework\TestCase;
use Railken\SQ\QueryParser;
use Railken\SQ\Resolvers as Resolvers;
use Railken\SQ\Nodes as Nodes;

class QueryTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->parser = new QueryParser();
        $this->parser->addTokens([
            new Resolvers\GroupingResolver(),
            new Resolvers\EqResolver(),
            new Resolvers\AndResolver(),
        ]);
    }


    /**
     * @expectedException Railken\SQ\Exceptions\QuerySyntaxException
     */
    public function testQuerySyntaxException1()
    {
       $query = $this->parser;
       $query->parse('x');
    }

    /**
     * @expectedException Railken\SQ\Exceptions\QuerySyntaxException
     */
    public function testQuerySyntaxException2()
    {
       $query = $this->parser;
       $query->parse('x eq');
    }

    /**
     * @expectedException Railken\SQ\Exceptions\QuerySyntaxException
     */
   public function testQuerySyntaxException3()
    {

       $query = $this->parser;
       $query->parse('x wrong 1');
    }

    /**
     * @expectedException Railken\SQ\Exceptions\QuerySyntaxException
     */
    public function testQuerySyntaxException5()
    {

       $query = $this->parser;
       $query->parse('x|date( eq 1');
    }

    public function testEq()
    {   
        $query = $this->parser;

        $result = $query->parse('x eq 1');
        $this->assertEquals(Nodes\EqNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

        $result = $query->parse('x = 1');
        $this->assertEquals(Nodes\EqNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

    } 

    public function testNotEq()
    {   
        $query = $this->parser;

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"not_eq","value":"1"}]'), json_decode(json_encode($query->parse('x != 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"not_eq","value":"1"}]'), json_decode(json_encode($query->parse('x <> 1'))));
    } 


    public function testGt()
    {   
        $query = $this->parser;

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"gt","value":"1"}]'), json_decode(json_encode($query->parse('x gt 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"gt","value":"1"}]'), json_decode(json_encode($query->parse('x > 1'))));
    }

    public function testGte()
    {   
        $query = $this->parser;

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"gte","value":"1"}]'), json_decode(json_encode($query->parse('x gte 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"gte","value":"1"}]'), json_decode(json_encode($query->parse('x >= 1'))));
    }

    public function testLt()
    {   
        $query = $this->parser;

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"lt","value":"1"}]'), json_decode(json_encode($query->parse('x lt 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"lt","value":"1"}]'), json_decode(json_encode($query->parse('x < 1'))));
    }

    public function testLte()
    {   
        $query = $this->parser;

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"lte","value":"1"}]'), json_decode(json_encode($query->parse('x lte 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"lte","value":"1"}]'), json_decode(json_encode($query->parse('x <= 1'))));
    }

    public function testCt()
    {   
        $query = $this->parser;

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"ct","value":"1"}]'), json_decode(json_encode($query->parse('x ct 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"ct","value":"1"}]'), json_decode(json_encode($query->parse('x *= 1'))));
    }

    public function testSw()
    {   
        $query = $this->parser;

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"sw","value":"1"}]'), json_decode(json_encode($query->parse('x sw 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"sw","value":"1"}]'), json_decode(json_encode($query->parse('x ^= 1'))));
    }

    public function testEw()
    {   
        $query = $this->parser;

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"ew","value":"1"}]'), json_decode(json_encode($query->parse('x ew 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"ew","value":"1"}]'), json_decode(json_encode($query->parse('x $= 1'))));
    }

    public function testNotIn()
    {   
        $query = $this->parser;

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"not_in","value":["1"]}]'), json_decode(json_encode($query->parse('x not_in 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"not_in","value":["1"]}]'), json_decode(json_encode($query->parse('x !=[] 1'))));
    }

    public function testIn()
    {   
        $query = $this->parser;

        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"in","value":["1"]}]'), json_decode(json_encode($query->parse('x in 1'))));
        $this->assertEquals(json_decode('[{"key":"x","filters":[],"operator":"in","value":["1"]}]'), json_decode(json_encode($query->parse('x =[] 1'))));
    }

    public function testAnd()
    {   
        $query = $this->parser;
        $this->assertEquals(json_decode('{"operator":"and","value":[{"operator":"eq","key":"x","filters":[],"value":"1"},{"operator":"eq","key":"x","filters":[],"value":"2"}]}'), json_decode(json_encode($query->parse('x = 1 and x = 2'))));
        
        $this->assertEquals(json_decode('{"operator":"and","value":[{"operator":"eq","key":"x","filters":[],"value":"1"},{"operator":"eq","key":"x","filters":[],"value":"2"}]}'), json_decode(json_encode($query->parse('x = 1 && x = 2'))));

    }

    public function testOr()
    {   
        $query = $this->parser;
        
        $this->assertEquals(json_decode('{"operator":"or","value":[{"operator":"eq","key":"x","filters":[],"value":"1"},{"operator":"eq","key":"x","filters":[],"value":"2"}]}'), json_decode(json_encode($query->parse('x = 1 or x = 2'))));

        $this->assertEquals(json_decode('{"operator":"or","value":[{"operator":"eq","key":"x","filters":[],"value":"1"},{"operator":"eq","key":"x","filters":[],"value":"2"}]}'), json_decode(json_encode($query->parse('x = 1 || x = 2'))));

    }

    public function testBasic()
    {   
        $query = $this->parser;

        $this->assertEquals(json_decode('[{"key":"x","filters":[{"name":"date_modify","parameters":["\"+1  days\"","false"]},{"name":"date","parameters":["\"d\""]}],"operator":"eq","value":"1"}]'), json_decode(json_encode($query->parse('x|date_modify("+1  days", false)|date("d") eq 1'))));

        $this->assertEquals(json_decode('{"operator":"or","value":[{"operator":"eq","key":"x","filters":[],"value":"1"},{"operator":"and","value":[{"operator":"eq","key":"y","filters":[],"value":"1"},{"operator":"eq","key":"x","filters":[],"value":"2"},{"operator":"eq","key":"x","filters":[],"value":"4"}]},{"operator":"and","value":[{"operator":"eq","key":"x","filters":[],"value":"1"},{"operator":"eq","key":"x","filters":[],"value":"1"}]}]}
'), json_decode(json_encode($query->parse('x eq 1 or y eq 1 and x eq 2 and x eq 4 or x eq 1 and x eq 1'))));

        $this->assertEquals(json_decode('{"operator":"or","value":[{"operator":"or","value":[{"key":"title","filters":[],"operator":"eq","value":"something"},{"key":"rating","filters":[],"operator":"gt","value":"3"}]},{"operator":"or","value":[{"key":"title","filters":[],"operator":"eq","value":"something else"},{"key":"rating","filters":[],"operator":"lt","value":"3"}]},{"key":"title","filters":[],"operator":"eq","value":"another"}]}
'), json_decode(json_encode($query->parse('(title eq "something" or rating gt 3) or (title eq "something else" or rating lt 3) or title eq "another"'))));



    }
}


