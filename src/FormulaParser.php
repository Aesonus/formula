<?php
/*
 * This file is part of the aesonus/formula package
 *
 *  (c) Cory Laughlin <corylcomposinger@gmail.com>
 *
 * For full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Aesonus\Formula;

/**
 * Description of FormulaParser
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class FormulaParser implements Contracts\FormulaParseInterface
{
    protected $originalFormula;
    private $pregFlag;

    public function __construct()
    {
        $this->pregFlag = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
    }

    public function parse(string $formula): array
    {
        $this->originalFormula = $formula;
        $clean_formula = $this->cleanFormula();
        //Get lowest level first
        $pattern = '/([\+\-]{1})/';
        $additions = preg_split($pattern, $clean_formula, -1, $this->pregFlag);
        echo "Additions: ", var_dump($additions), "\n";
        while ($operands[0] = array_shift($additions)) {
            if (!empty($additions)) {
                $operator = array_shift($additions);
            }
            if (!empty($additions)) {
                $operands[1] = array_shift($additions);
            }
            if ($operator === '+') {
                $op = new AddOperation();
                $op->setOperands(...$operands);
                $operations[] = $op;
            } elseif ($operator === '-') {
                $op = new SubtractOperation();
                $op->setOperands(...$operands);
                $operations[] = $op;
            }
        }
        return $operations;
    }

    private function cleanFormula(): string
    {

        $cleaned = str_replace(' ', '', $this->originalFormula);
        return $cleaned;

    }
}
