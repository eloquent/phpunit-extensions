# Ezzatron's PHPUnit extensions

*Extensions for PHPUnit to provide additional functionality.*

[![The most recent stable version is 1.0.2][version-image]][Semantic versioning]
[![Current build status image][build-image]][Current build status]
[![Current coverage status image][coverage-image]][Current coverage status]

## Installation and documentation

- Available as [Composer] package [eloquent/phpunit-extensions].
- [API documentation] available.

## Parameterized test cases

Parameterized test cases allow entire PHPUnit test cases to be run in multiple
different configurations. They operate similar to PHPUnit's own [data
providers], but on a test case level rather than a test method level.

To create a parameterized test case, extend the `ParameterizedTestCase` class
instead of `PHPUnit_Framework_TestCase`, and implement the required methods:

```php
use Eloquent\Phpunit\ParameterizedTestCase;

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

<!-- References -->

[data providers]: http://www.phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.data-providers

[API documentation]: http://lqnt.co/phpunit-extensions/artifacts/documentation/api/
[Composer]: http://getcomposer.org/
[build-image]: http://img.shields.io/travis/eloquent/phpunit-extensions/develop.svg "Current build status for the develop branch"
[Current build status]: https://travis-ci.org/eloquent/phpunit-extensions
[coverage-image]: http://img.shields.io/coveralls/eloquent/phpunit-extensions/develop.svg "Current test coverage for the develop branch"
[Current coverage status]: https://coveralls.io/r/eloquent/phpunit-extensions
[eloquent/phpunit-extensions]: https://packagist.org/packages/eloquent/phpunit-extensions
[Semantic versioning]: http://semver.org/
[version-image]: http://img.shields.io/:semver-1.0.2-brightgreen.svg "This project uses semantic versioning"
