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

use LogicException;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_TestResult;
use ReflectionObject;

abstract class ParameterizedTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @return integer
     */
    public function count()
    {
        return count($this->getTestCaseParameters());
    }

    /**
     * @param PHPUnit_Framework_TestResult|null $result
     *
     * @return PHPUnit_Framework_TestResult
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
     * @return array<integer,array<integer,mixed>>
     */
    abstract public function getTestCaseParameters();

    /**
     * @return ReflectionObject
     */
    protected function getReflector()
    {
        if (null === $this->reflector) {
            $this->reflector = new ReflectionObject($this);
        }

        return $this->reflector;
    }

    protected $reflector;
}
