matchmaker
==========

PHP functions that help you validate structure of complex nested PHP arrays.

```php
$books = [
    [
        'type' => 'book',
        'title' => 'Geography book',
        'chapters' => [
            'eu' => ['title' => 'Europe', 'interesting' => true],
            'as' => ['title' => 'America', 'interesting' => false]
        ]
    ],
    [
        'type' => 'book',
        'title' => 'Foreign languages book',
        'chapters' => [
            'de' => ['title' => 'Deutsch']
        ]
    ]
];

$pattern = [
    '*' => [
        'type' => 'book',
        'title' => ':string contains(book)',
        'chapters' => [
            ':string length(2) {1,3}' => [
                'title' => ':string',
                'interesting?' => ':bool',
            ]
        ]
    ]
];

matchmaker\matches($books, $pattern); // true
```

It could be used to check scalar values, objects or arrays from different sources (JSON, XML, Post Data).

## Matching rules

Matching rules are strings that start with ':'. You can use multiple matchers joined with space.
Matcher could be any callable (name of function or closure). You can add your own rules or replace standard ones.

* **General**

 * empty
 * nonempty
 * required
 * in(a, b, ...)
 * mixed
 * any

* **Types**

 * array
 * bool
 * boolean
 * callable
 * double
 * float
 * int
 * integer
 * long
 * numeric
 * number
 * object
 * real
 * resource
 * scalar
 * string

* **Numbers**

 * gt(n)
 * gte(n)
 * lt(n)
 * lte(n)
 * negative
 * positive
 * between(a, b)

* **Strings**

 * alnum
 * alpha
 * cntrl
 * digit
 * graph
 * lower
 * print
 * punct
 * space
 * upper
 * xdigit
 * regexp(pattern)
 * email
 * url
 * ip
 * length(n)
 * min(n)
 * max(n)
 * contains(needle)
 * starts(s)
 * ends(s)
 * json
 * date

* **Arrays**

 * count(n)
 * keys(key1, key2, ...)

* **Objects**

 * instance(class)
 * property(name, value)
 * method(name, value)

More details you can find [here](https://github.com/ptrofimov/matchmaker/blob/master/src/rules.php)

## Quantifiers for keys

* ! - one key required (default)
* ? - optional key
* * - any count of keys
* {3} - strict count of keys
* {1,5} - range

For matchers (i.e. ':string') default quantifier is *

## Installation

* Install matchmaker via composer for your project:
```
composer require ptrofimov/matchmaker:*
```

## License

Copyright (c) 2014 Petr Trofimov

MIT License

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
