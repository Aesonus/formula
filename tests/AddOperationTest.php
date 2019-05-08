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

class AddOperationTest extends BaseTestCase
{
    public $testObj;

    protected function setUp(): void
    {
        $this->testObj = new \Aesonus\Formula\AddOperation();
    }

    /**
     * @test
     * @dataProvider solveAddsOperandsDataProvider
     */
    public function solveAddsOperands($operands, $expected)
    {
        $this->testObj->setOperands(...$operands);
        $actual = $this->testObj->solve();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function solveAddsOperandsDataProvider()
    {
        return [
            'two operands' => [
                [
                    3, 4
                ],
                7
            ],
            'three operands' => [
                [
                    10, 5, 2
                ],
                17
            ],
            '4 operands' => [
                [
                    20, 2, 5, 2
                ],
                29
            ]
        ];
    }

    /**
     * @test
     * @dataProvider solveAddsVariableOperandsDataProvider
     */
    public function solveAddsVariableOperands($variables, $expected)
    {
        $this->testObj->setOperands('testvar', 'othervar');
        $actual = $this->testObj->solve($variables);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function solveAddsVariableOperandsDataProvider()
    {
        return [
            'two variables' => [
                [
                    'testvar' => 3,
                    'othervar' => 4
                ],
                7
            ],
            'two variables with callable' => [
                [
                    'testvar' => 3,
                    'othervar' => function () {
                        return 4;
                    }
                ],
                7
            ]
        ];
    }
}
