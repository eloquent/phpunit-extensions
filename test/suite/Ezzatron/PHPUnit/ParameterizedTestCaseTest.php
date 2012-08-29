<?php

/*
 * This file is part of the PHPUnit extensions package.
 *
 * Copyright © 2012 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ezzatron\PHPUnit;

use Eloquent\Liberator\Liberator;
use Phake;
use PHPUnit_Framework_TestCase;

class ParameterizedTestCaseTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $testCase = Phake::partialMock(
            __NAMESPACE__.'\ParameterizedTestCase',
            'foo',
            array('bar', 'baz'),
            'qux'
        );

        $this->assertSame('foo with data set "qux"', $testCase->getName());
        $this->assertSame(array('bar', 'baz'), Liberator::liberate($testCase)->data);
    }

    public function testCount()
    {
        $testCase = Phake::partialMock(__NAMESPACE__.'\ParameterizedTestCase');
        Phake::when($testCase)->getTestCaseParameters()->thenReturn(array(
            array(),
        ));

        $this->assertSame(1, $testCase->count());


        $testCase = Phake::partialMock(__NAMESPACE__.'\ParameterizedTestCase');
        Phake::when($testCase)->getTestCaseParameters()->thenReturn(array(
            array(),
            array(),
        ));

        $this->assertSame(2, $testCase->count());
    }

    public function testRun()
    {
        $testCase = Phake::partialMock(__NAMESPACE__.'\ParameterizedTestCase');
        Phake::when($testCase)->getTestCaseParameters()->thenReturn(array(
            array('foo', 'bar'),
            array('baz', 'qux'),
            array('doom', 'splat'),
        ));
        $result = Phake::mock('PHPUnit_Framework_TestResult');

        $this->assertSame($result, $testCase->run($result));
        Phake::inOrder(
            Phake::verify($testCase)->getTestCaseParameters(),
            Phake::verify($testCase)->setUpParameterized('foo', 'bar'),
            Phake::verify($testCase)->tearDownParameterized('foo', 'bar'),
            Phake::verify($testCase)->setUpParameterized('baz', 'qux'),
            Phake::verify($testCase)->tearDownParameterized('baz', 'qux'),
            Phake::verify($testCase)->setUpParameterized('doom', 'splat'),
            Phake::verify($testCase)->tearDownParameterized('doom', 'splat')
        );
    }

    public function testRunCreateResult()
    {
        $testCase = Phake::partialMock(__NAMESPACE__.'\ParameterizedTestCase');
        Phake::when($testCase)->getTestCaseParameters()->thenReturn(array());

        $this->assertInstanceOf('PHPUnit_Framework_TestResult', $testCase->run());
    }

    public function testRunInvalidDataFailure()
    {
        $testCase = Phake::partialMock(__NAMESPACE__.'\ParameterizedTestCase');
        Phake::when($testCase)->getTestCaseParameters()->thenReturn(array('foo'));

        $this->setExpectedException('LogicException', 'Invalid test case parameters.');
        $testCase->run();
    }
}
