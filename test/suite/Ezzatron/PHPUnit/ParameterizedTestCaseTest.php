<?php

/*
 * This file is part of the PHPUnit extensions package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\PHPUnit;

use Eloquent\Liberator\Liberator;
use Phake;
use PHPUnit_Framework_TestCase;
use ReflectionObject;

class ParameterizedTestCaseTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->_testCase = Phake::partialMock(__NAMESPACE__.'\ParameterizedTestCase');
        $this->_reflector = Phake::mock('ReflectionObject');
        Phake::when($this->_testCase)->getReflector()->thenReturn($this->_reflector);
        $this->_setUpMethod = Phake::mock('ReflectionMethod');
        Phake::when($this->_reflector)->hasMethod('setUpParameterized')->thenReturn(true);
        Phake::when($this->_reflector)->getMethod('setUpParameterized')->thenReturn($this->_setUpMethod);
        $this->_tearDownMethod = Phake::mock('ReflectionMethod');
        Phake::when($this->_reflector)->hasMethod('tearDownParameterized')->thenReturn(true);
        Phake::when($this->_reflector)->getMethod('tearDownParameterized')->thenReturn($this->_tearDownMethod);
    }

    public function testCountOne()
    {
        Phake::when($this->_testCase)->getTestCaseParameters()->thenReturn(array(
            array(),
        ));

        $this->assertSame(1, $this->_testCase->count());
    }

    public function testCountTwo()
    {
        Phake::when($this->_testCase)->getTestCaseParameters()->thenReturn(array(
            array(),
            array(),
        ));

        $this->assertSame(2, $this->_testCase->count());
    }

    public function testRun()
    {
        Phake::when($this->_testCase)->getTestCaseParameters()->thenReturn(array(
            array('foo', 'bar'),
            array('baz', 'qux'),
            array('doom', 'splat'),
        ));
        $result = Phake::mock('PHPUnit_Framework_TestResult');

        $this->assertSame($result, $this->_testCase->run($result));
        Phake::inOrder(
            Phake::verify($this->_testCase)->getTestCaseParameters(),
            Phake::verify($this->_setUpMethod)->invokeArgs($this->_testCase, array('foo', 'bar')),
            Phake::verify($this->_tearDownMethod)->invokeArgs($this->_testCase, array('foo', 'bar')),
            Phake::verify($this->_setUpMethod)->invokeArgs($this->_testCase, array('baz', 'qux')),
            Phake::verify($this->_tearDownMethod)->invokeArgs($this->_testCase, array('baz', 'qux')),
            Phake::verify($this->_setUpMethod)->invokeArgs($this->_testCase, array('doom', 'splat')),
            Phake::verify($this->_tearDownMethod)->invokeArgs($this->_testCase, array('doom', 'splat'))
        );
    }

    public function testRunCreateResult()
    {
        Phake::when($this->_testCase)->getTestCaseParameters()->thenReturn(array());

        $this->assertInstanceOf('PHPUnit_Framework_TestResult', $this->_testCase->run());
    }

    public function testRunInvalidDataFailure()
    {
        Phake::when($this->_testCase)->getTestCaseParameters()->thenReturn(array('foo'));

        $this->setExpectedException('LogicException', 'Invalid test case parameters.');
        $this->_testCase->run();
    }

    public function testGetReflector()
    {
        $testCase = Phake::partialMock(__NAMESPACE__.'\ParameterizedTestCase');
        $testCaseReflector = new ReflectionObject($testCase);
        $testCaseClass = $testCaseReflector->getName();
        $actual = Liberator::liberate($testCase)->getReflector();

        $this->assertInstanceOf('ReflectionObject', $actual);
        $this->assertSame($testCaseClass, $actual->getName());
        $this->assertSame($actual, Liberator::liberate($testCase)->getReflector());
    }
}
