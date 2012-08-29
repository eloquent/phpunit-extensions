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
    public function __construct(
        $name = null,
        array $data = array(),
        $dataName = ''
    ) {
        $this->reflector = new ReflectionObject($this);

        parent::__construct($name, $data, $dataName);
    }

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

        foreach ($this->getTestCaseParameters() as $arguments) {
            if (!is_array($arguments)) {
                throw new LogicException('Invalid test case parameters.');
            }

            $this->reflector->getMethod('setUpParameterized')
                ->invokeArgs($this, $arguments)
            ;

            parent::run($result);

            $this->reflector->getMethod('tearDownParameterized')
                ->invokeArgs($this, $arguments)
            ;
        }

        return $result;
    }

    /**
     * @return array<integer,array<integer,mixed>>
     */
    abstract public function getTestCaseParameters();

    public function setUpParameterized()
    {
    }

    public function tearDownParameterized()
    {
    }

    protected $reflector;
}
