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
            new Resolvers\ValueResolver(),
            new Resolvers\KeyResolver(),
            new Resolvers\GroupingResolver(),
            new Resolvers\NotEqResolver(),
            new Resolvers\EqResolver(),
            new Resolvers\LteResolver(),
            new Resolvers\LtResolver(),
            new Resolvers\GteResolver(),
            new Resolvers\GtResolver(),
            new Resolvers\CtResolver(),
            new Resolvers\SwResolver(),
            new Resolvers\NotInResolver(),
            new Resolvers\InResolver(),
            new Resolvers\EwResolver(),
            new Resolvers\NotNullResolver(),
            new Resolvers\NullResolver(),
            new Resolvers\AndResolver(),
            new Resolvers\OrResolver(),
        ]);
    }

    public function testEq()
    {
        $query = $this->parser;

        $result = $query->parse('x eq 1');
        $this->assertEquals(Nodes\EqNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());

        $result = $query->parse('x = 1');
        $this->assertEquals(Nodes\EqNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
    }

    public function testNotEq()
    {
        $query = $this->parser;

        $result = $query->parse('x not eq 1');
        $this->assertEquals(Nodes\NotEqNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());

        $result = $query->parse('x != 1');
        $this->assertEquals(Nodes\NotEqNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());

        $result = $query->parse('x <> 1');
        $this->assertEquals(Nodes\NotEqNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
    }

    public function testGt()
    {
        $query = $this->parser;

        $result = $query->parse('x gt 1');
        $this->assertEquals(Nodes\GtNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());

        $result = $query->parse('x > 1');
        $this->assertEquals(Nodes\GtNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
    }

    public function testGte()
    {
        $query = $this->parser;

        $result = $query->parse('x gte 1');
        $this->assertEquals(Nodes\GteNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());

        $result = $query->parse('x >= 1');
        $this->assertEquals(Nodes\GteNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
    }


    public function testLt()
    {
        $query = $this->parser;

        $result = $query->parse('x lt 1');
        $this->assertEquals(Nodes\LtNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());

        $result = $query->parse('x < 1');
        $this->assertEquals(Nodes\LtNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
    }

    public function testLte()
    {
        $query = $this->parser;

        $result = $query->parse('x lte 1');
        $this->assertEquals(Nodes\LteNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());

        $result = $query->parse('x <= 1');
        $this->assertEquals(Nodes\LteNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
    }


    public function testNull()
    {
        $query = $this->parser;

        $result = $query->parse('x is null');
        $this->assertEquals(Nodes\NullNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
    }

    public function testNotNull()
    {
        $query = $this->parser;

        $result = $query->parse('x is not null');
        $this->assertEquals(Nodes\NotNullNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
    }

    public function testCt()
    {
        $query = $this->parser;

        $result = $query->parse('x ct 1');
        $this->assertEquals(Nodes\CtNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());

        $result = $query->parse('x *= 1');
        $this->assertEquals(Nodes\CtNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
    }

    public function testSw()
    {
        $query = $this->parser;

        $result = $query->parse('x sw 1');
        $this->assertEquals(Nodes\SwNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());

        $result = $query->parse('x ^= 1');
        $this->assertEquals(Nodes\SwNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
    }

    public function testEw()
    {
        $query = $this->parser;

        $result = $query->parse('x ew 1');
        $this->assertEquals(Nodes\EwNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());

        $result = $query->parse('x $= 1');
        $this->assertEquals(Nodes\EwNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
    }

    public function testNotIn()
    {
        $query = $this->parser;

        $result = $query->parse('x not in (1, 2)');


        $this->assertEquals(Nodes\NotInNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\GroupNode::class)->getChildByIndex(0)->getValue());
        $this->assertEquals('2', $result->getFirstChildByClass(Nodes\GroupNode::class)->getChildByIndex(1)->getValue());

        $result = $query->parse('x !=[] (1, 2)');
        $this->assertEquals(Nodes\NotInNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\GroupNode::class)->getChildByIndex(0)->getValue());
        $this->assertEquals('2', $result->getFirstChildByClass(Nodes\GroupNode::class)->getChildByIndex(1)->getValue());
    }

    public function testIn()
    {
        $query = $this->parser;

        $result = $query->parse('x in (1,2)');
        $this->assertEquals(Nodes\InNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\GroupNode::class)->getChildByIndex(0)->getValue());
        $this->assertEquals('2', $result->getFirstChildByClass(Nodes\GroupNode::class)->getChildByIndex(1)->getValue());

        $result = $query->parse('x =[] (1,2)');
        $this->assertEquals(Nodes\InNode::class, get_class($result));
        $this->assertEquals('x', $result->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getFirstChildByClass(Nodes\GroupNode::class)->getChildByIndex(0)->getValue());
        $this->assertEquals('2', $result->getFirstChildByClass(Nodes\GroupNode::class)->getChildByIndex(1)->getValue());
        ;
    }


    public function testAnd1()
    {
        $query = $this->parser;
        $result = $query->parse('x and y');
        $this->assertEquals(Nodes\AndNode::class, get_class($result));
        $this->assertEquals('x', $result->getChildByIndex(0)->getValue());
        $this->assertEquals('y', $result->getChildByIndex(1)->getValue());


        $result = $query->parse('x && y');
        $this->assertEquals(Nodes\AndNode::class, get_class($result));
        $this->assertEquals('x', $result->getChildByIndex(0)->getValue());
        $this->assertEquals('y', $result->getChildByIndex(1)->getValue());
    }

    public function testAndEq()
    {
        $query = $this->parser;
        $result = $query->parse('x = 1 and y = 1');
        $this->assertEquals(Nodes\AndNode::class, get_class($result));
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChildByIndex(0)));
        $this->assertEquals('x', $result->getChildByIndex(0)->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getChildByIndex(0)->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChildByIndex(1)));
        $this->assertEquals('y', $result->getChildByIndex(1)->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getChildByIndex(1)->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
    }

    public function testAnd2()
    {
        $query = $this->parser;
        $result = $query->parse('x and (y and z)');

        $this->assertEquals(Nodes\AndNode::class, get_class($result));
        $this->assertEquals('x', $result->getChildByIndex(0)->getValue());
        $this->assertEquals('y', $result->getChildByIndex(1)->getValue());
        $this->assertEquals('z', $result->getChildByIndex(2)->getValue());
    }

    public function testOr1()
    {
        $query = $this->parser;
        $result = $query->parse('x or y');
        $this->assertEquals(Nodes\OrNode::class, get_class($result));
        $this->assertEquals('x', $result->getChildByIndex(0)->getValue());
        $this->assertEquals('y', $result->getChildByIndex(1)->getValue());


        $result = $query->parse('x || y');
        $this->assertEquals(Nodes\OrNode::class, get_class($result));
        $this->assertEquals('x', $result->getChildByIndex(0)->getValue());
        $this->assertEquals('y', $result->getChildByIndex(1)->getValue());
    }

    public function testAndOr1()
    {
        $query = $this->parser;
        $result = $query->parse('x || y && z');

        $this->assertEquals(Nodes\OrNode::class, get_class($result));
        $this->assertEquals('x', $result->getChildByIndex(0)->getValue());
        $this->assertEquals(Nodes\AndNode::class, get_class($result->getChildByIndex(1)));
        $this->assertEquals('y', $result->getChildByIndex(1)->getChildByIndex(0)->getValue());
        $this->assertEquals('z', $result->getChildByIndex(1)->getChildByIndex(1)->getValue());
    }


    public function testGrouping1()
    {
        $query = $this->parser;
        $result = $query->parse('((x eq 1))');

        $this->assertEquals(Nodes\GroupNode::class, get_class($result));
        $this->assertEquals(Nodes\GroupNode::class, get_class($result->getChildByIndex(0)));
        $this->assertEquals(Nodes\EqNode::class, get_class($result->getChildByIndex(0)->getChildByIndex(0)));
        $this->assertEquals('x', $result->getChildByIndex(0)->getChildByIndex(0)->getFirstChildByClass(Nodes\KeyNode::class)->getValue());
        $this->assertEquals('1', $result->getChildByIndex(0)->getChildByIndex(0)->getFirstChildByClass(Nodes\ValueNode::class)->getValue());
    }
}
