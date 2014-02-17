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

use LogicException;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_TestResult;
use ReflectionObject;

/**
 * A test case for creating parameterized tests.
 */
abstract class ParameterizedTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Counts the number of test cases executed by run(TestResult result).
     *
     * @return integer The number of test cases to run.
     */
    public function count()
    {
        return count($this->getTestCaseParameters());
    }

    /**
     * Runs the test case and collects the results in a TestResult object. If no
     * TestResult object is passed a new one will be created.
     *
     * @param PHPUnit_Framework_TestResult|null $result The test result to use.
     *
     * @return PHPUnit_Framework_TestResult The test result.
     * @throws PHPUnit_Framework_Exception  If the test case cannot be run.
     * @throws LogicException               If the test case parameters are invalid.
     */
    public function run(PHPUnit_Framework_TestResult $result = null)
    {
        if (null === $result) {
            $result = $this->createResult();
        }
        $reflector = $this->getReflector();

        foreach ($this->getTestCaseParameters() as $arguments) {
            if (!is_array($arguments)) {
                throw new LogicException('Invalid test case parameters.');
            }

            if ($reflector->hasMethod('setUpParameterized')) {
                $reflector->getMethod('setUpParameterized')
                    ->invokeArgs($this, $arguments)
                ;
            }

            parent::run($result);

            if ($reflector->hasMethod('tearDownParameterized')) {
                $reflector->getMethod('tearDownParameterized')
                    ->invokeArgs($this, $arguments)
                ;
            }
        }

        return $result;
    }

    /**
     * Get the test case parameters to use.
     *
     * Each row will cause an extra invocation of each test in the test case.
     *
     * @return array<integer,array<integer,mixed>> The test case parameters.
     */
    abstract public function getTestCaseParameters();

    /**
     * Get the reflector for this test case.
     *
     * @return ReflectionObject The test case reflector.
     */
    protected function getReflector()
    {
        if (null === $this->reflector) {
            $this->reflector = new ReflectionObject($this);
        }

        return $this->reflector;
    }

    private $reflector;
}
