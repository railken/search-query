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
            new Resolvers\NullResolver(),
            new Resolvers\AndResolver(),
            new Resolvers\OrResolver(),
            new Resolvers\TextResolver(),
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


    public function testNull()
    {
        $query = $this->parser;

        $result = $query->parse('x is null');
        $this->assertEquals(Nodes\NullNode::class, get_class($result));
        $this->assertEquals('x', $result->getKey());
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
        $result = $query->parse('x = 1 or y = 1');
        $this->assertEquals(Nodes\OrNode::class, get_class($result));
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(0)));
        $this->assertEquals('x', $result->getChild(0)->getKey());
        $this->assertEquals('1', $result->getChild(0)->getValue());
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(1)));
        $this->assertEquals('y', $result->getChild(1)->getKey());
        $this->assertEquals('1', $result->getChild(1)->getValue());


        $result = $query->parse('x = 1 || y = 1');
        $this->assertEquals(Nodes\OrNode::class, get_class($result));
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(0)));
        $this->assertEquals('x', $result->getChild(0)->getKey());
        $this->assertEquals('1', $result->getChild(0)->getValue());
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(1)));
        $this->assertEquals('y', $result->getChild(1)->getKey());
        $this->assertEquals('1', $result->getChild(1)->getValue());
    }


    public function testAndOr1()
    {
        $query = $this->parser;
        $result = $query->parse('(x = 1 and (y = 1 or x = 1)) or z = 1');
     
        // {{ x = 1 and (y = 1 or x = 1) or z = 1 }}
        $this->assertEquals(Nodes\OrNode::class, get_class($result));

        // {{ x = 1 and (y = 1 or x = 1) }} or z = 1
        $this->assertEquals(Nodes\AndNode::class, get_class($result->getChild(0)));

        // {{ x = 1 }} and (y = 1 or x = 1) or z = 1
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(0)->getChild(0)));
        $this->assertEquals('x', $result->getChild(0)->getChild(0)->getKey());
        $this->assertEquals('1', $result->getChild(0)->getChild(0)->getValue());

        // x = 1 and {{ (y = 1 or x = 1) }} or z = 1
        $this->assertEquals(Nodes\OrNode::class, get_class($result->getChild(0)->getChild(1)));

        // x = 1 and ({{ y = 1 }} or x = 1) or z = 1
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(0)->getChild(1)->getChild(0)));
        $this->assertEquals('y', $result->getChild(0)->getChild(1)->getChild(0)->getKey());
        $this->assertEquals('1', $result->getChild(0)->getChild(1)->getChild(0)->getValue());

        // x = 1 and ( y = 1 or  {{ x = 1 }}) or z = 1
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(0)->getChild(1)->getChild(1)));
        $this->assertEquals('x', $result->getChild(0)->getChild(1)->getChild(1)->getKey());
        $this->assertEquals('1', $result->getChild(0)->getChild(1)->getChild(1)->getValue());


        // x = 1 and ( y = 1 or  x = 1 ) or {{ z = 1 }}
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(1)));
        $this->assertEquals('z', $result->getChild(1)->getKey());
        $this->assertEquals('1', $result->getChild(1)->getValue());


    }

    public function testAndOr2()
    {
        $query = $this->parser;
        $result = $query->parse('id eq 1 and id eq 2 or id eq 3');

        $this->assertEquals(Nodes\OrNode::class, get_class($result));
        $this->assertEquals(2, $result->countChilds());

        $this->assertEquals(Nodes\AndNode::class, get_class($result->getChild(0)));
        $this->assertEquals(2, $result->getChild(0)->countChilds());

        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(0)->getChild(0)));
        $this->assertEquals('id', $result->getChild(0)->getChild(0)->getKey());
        $this->assertEquals('1', $result->getChild(0)->getChild(0)->getValue());

        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(0)->getChild(1)));
        $this->assertEquals('id', $result->getChild(0)->getChild(1)->getKey());
        $this->assertEquals('2', $result->getChild(0)->getChild(1)->getValue());


        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChild(1)));
        $this->assertEquals('id', $result->getChild(1)->getKey());
        $this->assertEquals('3', $result->getChild(1)->getValue());

    }

    public function testGrouping1()
    {
        $query = $this->parser;
        $result = $query->parse('(((id eq 1)))');

        $this->assertEquals(Nodes\GroupNode::class, get_class($result));
    }


}
