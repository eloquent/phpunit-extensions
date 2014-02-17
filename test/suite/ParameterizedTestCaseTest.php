<?php

/*
 * This file is part of the PHPUnit extensions package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phpunit;

use Eloquent\Liberator\Liberator;
use Phake;
use PHPUnit_Framework_TestCase;
use ReflectionObject;

class ParameterizedTestCaseTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->testCase = Phake::partialMock(__NAMESPACE__ . '\ParameterizedTestCase');
        $this->reflector = Phake::mock('ReflectionObject');
        $this->setUpMethod = Phake::mock('ReflectionMethod');
        $this->tearDownMethod = Phake::mock('ReflectionMethod');

        Phake::when($this->testCase)->getReflector()->thenReturn($this->reflector);
        Phake::when($this->reflector)->hasMethod('setUpParameterized')->thenReturn(true);
        Phake::when($this->reflector)->getMethod('setUpParameterized')->thenReturn($this->setUpMethod);
        Phake::when($this->reflector)->hasMethod('tearDownParameterized')->thenReturn(true);
        Phake::when($this->reflector)->getMethod('tearDownParameterized')->thenReturn($this->tearDownMethod);
    }

    public function testCountOne()
    {
        Phake::when($this->testCase)->getTestCaseParameters()->thenReturn(array(
            array(),
        ));

        $this->assertSame(1, $this->testCase->count());
    }

    public function testCountTwo()
    {
        Phake::when($this->testCase)->getTestCaseParameters()->thenReturn(array(
            array(),
            array(),
        ));

        $this->assertSame(2, $this->testCase->count());
    }

    public function testRun()
    {
        Phake::when($this->testCase)->getTestCaseParameters()->thenReturn(array(
            array('foo', 'bar'),
            array('baz', 'qux'),
            array('doom', 'splat'),
        ));
        $result = Phake::mock('PHPUnit_Framework_TestResult');

        $this->assertSame($result, $this->testCase->run($result));
        Phake::inOrder(
            Phake::verify($this->testCase)->getTestCaseParameters(),
            Phake::verify($this->setUpMethod)->invokeArgs($this->testCase, array('foo', 'bar')),
            Phake::verify($this->tearDownMethod)->invokeArgs($this->testCase, array('foo', 'bar')),
            Phake::verify($this->setUpMethod)->invokeArgs($this->testCase, array('baz', 'qux')),
            Phake::verify($this->tearDownMethod)->invokeArgs($this->testCase, array('baz', 'qux')),
            Phake::verify($this->setUpMethod)->invokeArgs($this->testCase, array('doom', 'splat')),
            Phake::verify($this->tearDownMethod)->invokeArgs($this->testCase, array('doom', 'splat'))
        );
    }

    public function testRunCreateResult()
    {
        Phake::when($this->testCase)->getTestCaseParameters()->thenReturn(array());

        $this->assertInstanceOf('PHPUnit_Framework_TestResult', $this->testCase->run());
    }

    public function testRunInvalidDataFailure()
    {
        Phake::when($this->testCase)->getTestCaseParameters()->thenReturn(array('foo'));

        $this->setExpectedException('LogicException', 'Invalid test case parameters.');
        $this->testCase->run();
    }

    public function testGetReflector()
    {
        $testCase = Phake::partialMock(__NAMESPACE__ . '\ParameterizedTestCase');
        $testCaseReflector = new ReflectionObject($testCase);
        $testCaseClass = $testCaseReflector->getName();
        $actual = Liberator::liberate($testCase)->getReflector();

        $this->assertInstanceOf('ReflectionObject', $actual);
        $this->assertSame($testCaseClass, $actual->getName());
        $this->assertSame($actual, Liberator::liberate($testCase)->getReflector());
    }
}
