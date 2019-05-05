<?php

/*
 * This file is part of the Tests project.
 *
 * (c) Cory Laughlin <corylcomposinger@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aesonus\Tests;

use Aesonus\TestLib\BaseTestCase;

class MultiplyOperationTest extends BaseTestCase
{
    public $testObj;

    protected function setUp() : void
    {
        $this->testObj = new \Aesonus\Formula\MultiplyOperation();
    }

    /**
     * @test
     * @dataProvider solveMultipliesOperandsDataProvider
     */
    public function solveMultipliesOperands($operands, $expected)
    {
        $this->testObj->setOperands(...$operands);
        $actual = $this->testObj->solve();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function solveMultipliesOperandsDataProvider()
    {
        return [
            'two operands' => [
                [
                    3,4
                ],
                12
            ],
            'three operands' => [
                [
                    10, 5, 2
                ],
                100
            ],
            '4 operands' => [
                [
                    20, 2, 5, 2
                ],
                400
            ]
        ];
    }

    /**
     * @test
     * @dataProvider solveMultipliesVariableOperandsDataProvider
     */
    public function solveMultipliesVariableOperands($variables, $expected)
    {
        $this->testObj->setOperands('testvar', 'othervar');
        $actual = $this->testObj->solve($variables);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function solveMultipliesVariableOperandsDataProvider()
    {
        return [
            'two variables' => [
                [
                    'testvar' => 3,
                    'othervar' => 4
                ],
                12
            ],
            'two variables with callable' => [
                [
                    'testvar' => 3,
                    'othervar' => function () {
                        return 4;
                    }
                ],
                12
            ]
        ];
    }
}
