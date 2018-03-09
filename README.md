# Search Query

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
$query = new Railken\SQ\QueryParser();
$search = $query->parse('x eq 1 or y > 1');
```
The result formatted in json of `$search`:

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
created_id
```


### Value node
All numbers and strings are converted as ```ValueNode```. The delimiter of strings can be either ```'``` or ```"```

Examples:

```
"An apple"
30.20
```


### Group node
All round parenthesis create a group node ```( ... )```. This group is used also for logic operators, operators in/not in

Examples:

```
(x or y) and z
```

### AND, OR
This operators requires a ```Node``` before and after. If a parent group is defined and the LogicNode is the only child, the LogicNode will take the place of the GroupNode.
Can be expressed as a literals (and, or) and as a symbols (&&, ||)

Examples:

```
x or y || z
x and y && z
```


### EQ, NOT_EQ, GT, GTE, LT, LTE
All these operators requires ```KeyNode``` or a ```ValueNode``` before and afters. 
Can be expressed as a literals (eq, not eq, gt, gte, lt, lte) and as a symbols (=, !=, >, >=, < <=)

Examples:

```
x eq 1
x = 1
x gt 1
x > 1
x < 1
x <= 1
```


### CT, SW, EW
these operators will handle strings: contains, start with and end with.


Examples:

```
description ct "a random phrase"
description sw "the text must start with this"
description ew "the text must end with this"
```


### NULL, NOT NULL

### IN, NOT IN

## License

Open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
