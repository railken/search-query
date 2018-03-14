# Search Query

[![Build Status](https://img.shields.io/travis/railken/search-query/master.svg)](https://travis-ci.org/railken/search-query)
[![Code Coverage](https://img.shields.io/codecov/c/github/railken/search-query.svg)](https://codecov.io/gh/railken/search-query)
[![Style CI](https://styleci.io/repos/107004461/shield?branch=master)](https://styleci.io/repos/107004461)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Latest stable](https://img.shields.io/packagist/v/railken/search-query.svg?style=flat-square)](https://packagist.org/packages/railken/search-query)
[![PHP](https://img.shields.io/travis/php-v/railken/search-query.svg)](https://secure.php.net/)

Converts a simple expression (e.g. 'x eq 1 or y gt 2') into a tree object.
This can be pretty usefull when building the API endpoint for a search.

## Requirements

PHP 7.0.0 or later.

## Composer

You can install it via [Composer](https://getcomposer.org/) by typing the following command:

```bash
composer require railken/search-query
```

## Demo

![demo](https://raw.githubusercontent.com/railken/search-query/master/demo/demo.gif)

## Usage

A simple usage looks like this: 
```php
   use Railken\SQ\QueryParser;
   use Railken\SQ\Resolvers as Resolvers;

   $parser = new QueryParser();
   $parser->addResolvers([
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
      new Resolvers\TextResolver(),
   ]);
   $result = $query->parse('x eq 1 or y > 1');
```
The result formatted in json of `$result`:

```json
{
  "type": "Railken\\SQ\\Nodes\\OrNode",
  "value": "OR",
  "childs": [
    {
      "type": "Railken\\SQ\\Nodes\\EqNode",
      "value": "EQ",
      "childs": [
        {
          "type": "Railken\\SQ\\Nodes\\KeyNode",
          "value": "x"
        },
        {
          "type": "Railken\\SQ\\Nodes\\ValueNode",
          "value": "1"
        }
      ]
    },
    {
      "type": "Railken\\SQ\\Nodes\\GtNode",
      "value": "GT",
      "childs": [
        {
          "type": "Railken\\SQ\\Nodes\\KeyNode",
          "value": "y"
        },
        {
          "type": "Railken\\SQ\\Nodes\\ValueNode",
          "value": "1"
        }
      ]
    }
  ]
}
```

## Nodes

### Key node
All alphanumeric (starting with alphabetic character) are converted as ```KeyNode```. Other characters allowed: ```_```

Examples:

```
created_at
```


### Value node
All numbers and strings are converted as ```ValueNode```. The delimiter of strings can be either ```'``` or ```"```

Examples:

```
"An apple"
30.20
```


### Group node
All round parenthesis create a group node ```( ... )```. Of course a nested group can be made. If a parenthesis is missing an exception will be thrown

Examples:

```
(x or y) and z
((x > 10 and w) or (y > 5 and f)) and z
```

### AND, OR
This operators requires a ```Node``` before and after, otherwise an exception will be thrown. 

Can be expressed as literals (and, or) or as symbols (&&, ||)

**Important**: If a parent group is defined and the LogicNode is the only child, the LogicNode will take the place of the GroupNode.


Examples:

```
x or y || z
x and y && z
```


### EQ, NOT_EQ, GT, GTE, LT, LTE
All these operators requires ```KeyNode``` or a ```ValueNode``` before and afters. 
Can be expressed as literals (eq, not eq, gt, gte, lt, lte) or as symbols (=, !=, >, >=, < <=)

Examples:

```
x eq 1
x = 1
x gt 1
x > 1
x gte 1
x >= 1
x lt 1
x < 1
x lte 1
x <= 1
```

Comparison can be also made between two keys

Examples:

```
x eq y
```


### CT, SW, EW
These operators will handle comparison with strings: contains, start with and end with.

Examples:

```
description ct "a random phrase"
description sw "the text must start with this"
description ew "the text must end with this"
```


### NULL, NOT NULL
These operators doesn't require a node after the operator.
```
deleted_at is null
deleted_at is not null
```


## Custom resolver

As you already saw, in order to parse the query you have to add the resolvers. 
So are free to add any resolvers you want, but pay attention to the order: KeyValue and NodeValue are the foundation for all the resolvers, so be carefull.

Here's an example of custom resolver and node

CustomResolver.php
```php
<?php
namespace App\SQ\Resolvers;

use Railken\SQ\Resolvers\ComparisonOperatorResolver;
use App\SQ\Nodes\CustomNode;

class CustomResolver extends ComparisonOperatorResolver
{
    /**
     * Node resolved
     *
     * @var string
     */
    public $node = CustomNode::class;

    /**
     * Regex token
     *
     * @var string
     */
    public $regex = [
        '/(?<![^\s])custom(?![^\s])/i',
    ];
}
```


CustomNode.php
```php
<?php
namespace App\SQ\Nodes;

use Railken\SQ\Nodes\ComparisonOperatorNode;

class CustomNode extends ComparisonOperatorNode
{
    /**
     * Value
     *
     * @var string
     */
    public $value = 'CUSTOM';
}
```


Remember to add the resolver when you're creating the instance of the parser

```php
<?php
$parser->addResolvers([
   new \App\SQ\Resolvers\CustomResolver(),
});

```

If you have a more complicated node to resolve simply add the method resolve to the CustomResolver. 

```php
<?php
namespace App\SQ\Resolvers;

use Railken\SQ\Contracts\ResolverContract;
use Railken\SQ\Contracts\NodeContract;

class CustomResolver implements ResolverContract
{

   /**
     * Resolve node
     *
     * @param NodeContract $node
     *
     * @return NodeContract
     */
    public function resolve(NodeContract $node)
    {
        return $node;
    }

}
```

## License

Open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
