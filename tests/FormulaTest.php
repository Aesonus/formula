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

use Aesonus\Formula\Formula;
use Aesonus\TestLib\BaseTestCase;

class FormulaTest extends BaseTestCase
{
    public $testObj;

    protected function setUp() : void
    {
        $this->testObj = new Formula;
    }

    /**
     * @test
     * @dataProvider solveFormulaSolvesStringFormulaDataProvider
     */
    public function solveFormulaSolvesStringFormula($formula, $expected)
    {
        $actual = $this->testObj->solveFormula($formula, ['var' => 3]);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data Provider
     */
    public function solveFormulaSolvesStringFormulaDataProvider()
    {
        return [
            'with parenthesis and variable' => ['(var + 3) * (2 + 2) + 7', 31],
            ['(4 * (3 + 2)) / 4', 5],
            ['4 + (4 - 2) + (6 / (14 / 7))', 9],
            ['3 + 10 + 2', 15],
            ['10 - 7 + 2', 5],
            ['10 - 7 + 2 * 2', 7],
            ['4 * 5 - 4 * 2 + 2 * 7', 26],
            ['4 / 2', 2],
            ['2 * 2', 4],
            ['3 + 10', 13],
            ['3 * 2 + 10 * 4', 46],
            ['2 + 10 * 2 * 2', 42],
            ['3 + 10 * 2', 23]
        ];
    }
}
