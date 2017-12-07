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
$search = $query->parse('created_at|date("Y") eq 2017');
```
The result formatted in json of `$search`:

```json
[  
   {  
      "key":"created_at",
      "filters":[  
         {  
            "name":"date",
            "parameters":[  
               "\"Y\""
            ]
         }
      ],
      "operator":"eq",
      "value":"2017"
   }
]
```
| Name     | Description            | Example
|:---------|:---------------------- |:----------
| and      | Logic operator AND     | x eq 1 and y eq 1
| or       | Logic operator OR      | x eq 1 or y eq 1
| eq       | Equals                 | x eq 1
| gt       | Greater than           | x gt 1
| lt       | Less than              | x lt 1
| in       | in values              | x in 1,2,3
| contains | in values              | x contains "foo bar"

## License

Open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
