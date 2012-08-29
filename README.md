# Ezzatron's PHPUnit extensions

*Extensions for PHPUnit to provide additional functionality.*

## Installation

### With [Composer](http://getcomposer.org/)

* Add 'ezzatron/phpunit-extensions' to the project's composer.json dev dependencies
* Run `composer update --dev`

### Bare installation

* Clone from GitHub: `git clone git://github.com/ezzatron/phpunit-extensions.git`
* Use a [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
  compatible autoloader (namespace 'Ezzatron\PHPUnit' in the 'src' directory)

## Parameterized test cases

Parameterized test cases allow entire PHPUnit test cases to be run in multiple
different configurations. They operate similar to PHPUnit's own
[data providers](http://www.phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.data-providers),
but on a test case level rather than a test method level.

To create a parameterized test case, extend the `ParameterizedTestCase` class
instead of `PHPUnit_Framework_TestCase`, and implement the required methods:

```php
<?php

use Ezzatron\PHPUnit\ParameterizedTestCase;

class ExampleTest extends ParameterizedTestCase
{
    public function getTestCaseParameters()
    {
        return array(
            array('Ocelot', 'Lapis lazuli', 'Dandelion'),
            array('Sloth', 'Carbon', 'Conifer'),
        );
    }

    public function setUpParameterized($animal, $mineral, $vegetable)
    {
        // set up...
    }

    public function tearDownParameterized($animal, $mineral, $vegetable)
    {
        // tear down...
    }

    public function testSomething()
    {
        // test...
    }
}
```

Every test in the testcase will now be run once for each entry in the
`getTestCaseParameters()` method.

## Code quality

PHPUnit extensions strives to attain a high level of quality. A full test suite
is available, and code coverage is closely monitored.

### Latest revision test suite results
[![Build Status](https://secure.travis-ci.org/ezzatron/phpunit-extensions.png)](http://travis-ci.org/ezzatron/phpunit-extensions)

### Latest revision test suite coverage
<http://ci.ezzatron.com/report/phpunit-extensions/coverage/>
