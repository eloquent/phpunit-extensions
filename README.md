# Ezzatron's PHPUnit extensions

*Extensions for PHPUnit to provide additional functionality.*

[![Build Status]](http://travis-ci.org/ezzatron/phpunit-extensions)
[![Test Coverage]](http://ezzatron-software.com/phpunit-extensions/artifacts/tests/coverage/)

## Installation

Available as [Composer](http://getcomposer.org/) package
[ezzatron/phpunit-extensions](https://packagist.org/packages/ezzatron/phpunit-extensions).

## Parameterized test cases

Parameterized test cases allow entire PHPUnit test cases to be run in multiple
different configurations. They operate similar to PHPUnit's own
[data providers](http://www.phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.data-providers),
but on a test case level rather than a test method level.

To create a parameterized test case, extend the `ParameterizedTestCase` class
instead of `PHPUnit_Framework_TestCase`, and implement the required methods:

```php
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

<!-- references -->
[Build Status]: https://raw.github.com/ezzatron/phpunit-extensions/gh-pages/artifacts/images/icecave/regular/build-status.png
[Test Coverage]: https://raw.github.com/ezzatron/phpunit-extensions/gh-pages/artifacts/images/icecave/regular/coverage.png
