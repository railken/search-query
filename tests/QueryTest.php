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
        $this->parser->addResolvers([
            new Resolvers\GroupingResolver(),
            new Resolvers\NotEqResolver(),
            new Resolvers\EqResolver(),
            new Resolvers\LtResolver(),
            new Resolvers\LteResolver(),
            new Resolvers\GtResolver(),
            new Resolvers\GteResolver(),
            new Resolvers\CtResolver(),
            new Resolvers\SwResolver(),
            new Resolvers\NotInResolver(),
            new Resolvers\InResolver(),
            new Resolvers\EwResolver(),
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

        $result = $query->parse('x not eq 1');        
        $this->assertEquals(Nodes\NotEqNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

        $result = $query->parse('x != 1');
        $this->assertEquals(Nodes\NotEqNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

        $result = $query->parse('x <> 1');
        $this->assertEquals(Nodes\NotEqNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

    } 

    public function testGt()
    {   
        $query = $this->parser;

        $result = $query->parse('x gt 1');
        $this->assertEquals(Nodes\GtNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

        $result = $query->parse('x > 1');
        $this->assertEquals(Nodes\GtNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

    }

    public function testGte()
    {   
        $query = $this->parser;

        $result = $query->parse('x gte 1');
        $this->assertEquals(Nodes\GteNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

        $result = $query->parse('x >= 1');
        $this->assertEquals(Nodes\GteNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

    }


    public function testLt()
    {   
        $query = $this->parser;

        $result = $query->parse('x lt 1');
        $this->assertEquals(Nodes\LtNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

        $result = $query->parse('x < 1');
        $this->assertEquals(Nodes\LtNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

    }

    public function testLte()
    {   
        $query = $this->parser;

        $result = $query->parse('x lte 1');
        $this->assertEquals(Nodes\LteNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

        $result = $query->parse('x <= 1');
        $this->assertEquals(Nodes\LteNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

    }

    public function testCt()
    {   
        $query = $this->parser;

        $result = $query->parse('x ct 1');
        $this->assertEquals(Nodes\CtNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

        $result = $query->parse('x *= 1');
        $this->assertEquals(Nodes\CtNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

    }

    public function testSw()
    {   

        $query = $this->parser;

        $result = $query->parse('x sw 1');
        $this->assertEquals(Nodes\SwNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

        $result = $query->parse('x ^= 1');
        $this->assertEquals(Nodes\SwNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());
    }

    public function testEw()
    {   
        $query = $this->parser;

        $result = $query->parse('x ew 1');
        $this->assertEquals(Nodes\EwNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());

        $result = $query->parse('x $= 1');
        $this->assertEquals(Nodes\EwNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals('1', $result->getValue());
    }

    public function testNotIn()
    {   
        $query = $this->parser;

        $result = $query->parse('x not in 1');
        $this->assertEquals(Nodes\NotInNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals(['1'], $result->getValue());

        $result = $query->parse('x !=[] 1');
        $this->assertEquals(Nodes\NotInNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals(['1'], $result->getValue());
    }

    public function testIn()
    {   
        $query = $this->parser;

        $result = $query->parse('x in 1');
        $this->assertEquals(Nodes\InNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals(['1'], $result->getValue());

        $result = $query->parse('x =[] 1');
        $this->assertEquals(Nodes\InNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
        $this->assertEquals(['1'], $result->getValue());
    }

    public function testAnd()
    {   
        $query = $this->parser;
        $result = $query->parse('x = 1 and y = 1');
        $this->assertEquals(Nodes\AndNode::class, get_class($result));
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(0)));
        $this->assertEquals('x', $result->getChild(0)->getKey());
        $this->assertEquals('1', $result->getChild(0)->getValue());
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(1)));
        $this->assertEquals('y', $result->getChild(1)->getKey());
        $this->assertEquals('1', $result->getChild(1)->getValue());


        $result = $query->parse('x = 1 && y = 1');
        $this->assertEquals(Nodes\AndNode::class, get_class($result));
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(0)));
        $this->assertEquals('x', $result->getChild(0)->getKey());
        $this->assertEquals('1', $result->getChild(0)->getValue());
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(1)));
        $this->assertEquals('y', $result->getChild(1)->getKey());
        $this->assertEquals('1', $result->getChild(1)->getValue());

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


