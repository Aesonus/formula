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

use Aesonus\Formula\AddOperation;
use Aesonus\Formula\DivideOperation;
use Aesonus\Formula\FormulaParser;
use Aesonus\Formula\MultiplyOperation;
use Aesonus\Formula\SubtractOperation;
use Aesonus\TestLib\BaseTestCase;

class FormulaParserTest extends BaseTestCase
{
    public $testObj;

    protected function setUp() : void
    {
        $this->testObj = new FormulaParser;
    }

    /**
     * @test
     * @dataProvider parseReturnsArrayOfOperationsDataProvider
     */
    public function parseReturnsArrayOfOperations($formula, $expected)
    {
        $actual = $this->testObj->parse($formula);
        var_dump($actual);
        foreach ($expected as $i => $classname) {
            $this->assertInstanceOf($classname, $actual[$i]);
        }
    }

    /**
     * Data Provider
     */
    public function parseReturnsArrayOfOperationsDataProvider()
    {
        return [
            [
                '6 - 3 + 10 * 4 / 2',
                [
                    MultiplyOperation::class,
                    DivideOperation::class,
                    SubtractOperation::class,
                    AddOperation::class,
                ]
            ],
            'with ()' => [
                '(3 + 10) * 2',
                [
                    AddOperation::class,
                    MultiplyOperation::class,
                ]
            ]
        ];
    }
}
