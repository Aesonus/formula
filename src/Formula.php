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
 * Description of Formula
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class Formula implements Contracts\FormulaInterface
{
    protected $variables = [];
    protected $originalFormula;
    private $pregFlag;

    public function __construct(array $variables = [])
    {
        $this->variables = [];
        $this->pregFlag = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
    }

    public function solveFormula(string $formula, array $variables = array()): ?float
    {
        $this->variables = array_merge($variables, $this->variables);
        $this->originalFormula = $formula;
        $clean_formula = $this->cleanFormula();
        //Get lowest level first

        while (count($parenthesis = $this->parseParenthesis($clean_formula)) > 0) {
            $parenthesis = $this->parseParenthesis($clean_formula);
            $clean_formula = $this->solveParenthesis($clean_formula, $parenthesis);
        }

        $adds = $this->parseAdd($clean_formula);
        return $this->solveAdds($adds);
    }

    private function solveParenthesis(
        string $original_formula,
        array $parenthesis
    ) {
        $new_formula = $original_formula;
        foreach ($parenthesis as $formula) {
            $adds = $this->parseAdd($formula);
            $result = $this->solveAdds($adds);
            $new_formula = str_ireplace("($formula)", $result, $new_formula);
        }
        return $new_formula;
    }

    private function parseParenthesis(string $formula)
    {
        $open = '(';
        $close = ')';
        $parenthesis = [];
        $offset = 0;

        while (($pos = strpos($formula, $open, $offset)) !== false) {
            $nextOpen = strpos($formula, $open, $pos + 1);
            $nextClose = strpos($formula, $close, $pos + 1);
            if ($nextClose === false) {
                throw new \InvalidArgumentException(__METHOD__ . 'Formula is missing closing parenthesis');
            }
            $offset = $pos + 1;
            if (($nextOpen === false ? 99999 : $nextOpen)  > $nextClose) {
                $parenthesis[] = substr($formula, $pos + 1, $nextClose - $offset);
            }
        }
        return $parenthesis;
    }

    private function solveAdds(array $adds)
    {
        $solved = array_shift($adds);
        while ($operator = array_shift($adds)) {
            if (!empty($adds)) {
                $operand = array_shift($adds);
            }
            if ($this->containsOperations($solved)) {
                $mults = $this->parseMult($solved);
                $result = $this->solveMults($mults);
                array_unshift($adds,$operator, $operand);
                $solved = $result;
            } elseif ($this->containsOperations($operand)) {
                $mults = $this->parseMult($operand);
                $result = $this->solveMults($mults);
                array_push($adds, $operator, $result);
            } elseif ($operator === '+') {
                $op = new AddOperation();
                $op->setOperands($solved, $operand);
                $solved = $op->solve($this->variables);
            } elseif ($operator === '-') {
                $op = new SubtractOperation();
                $op->setOperands($solved, $operand);
                $solved = $op->solve($this->variables);
            }
        }
        if ($this->containsOperations($solved)) {
            $mults = $this->parseMult($solved);
            return $this->solveMults($mults);
        } else {
            return $solved;
        }
    }

    private function solveMults(array $mults)
    {
        $solved = array_shift($mults);
        while ($operator = array_shift($mults)) {
            if (!empty($mults)) {
                $operand = array_shift($mults);
            }
            if ($this->containsOperations($solved)) {
//                $mults = $this->parseMult($solved);
//                $result = $this->solveMults($mults);
//                array_unshift($adds,$operator, $operand);
//                $solved = $result;
            } elseif ($this->containsOperations($operand)) {
//                $mults = $this->parseMult($operand);
//                $result = $this->solveMults($mults);
//                array_push($adds, $operator, $result);
            } elseif ($operator === '*') {
                $op = new MultiplyOperation();
                $op->setOperands($solved, $operand);
                $solved = $op->solve($this->variables);
            } elseif ($operator === '/') {
                $op = new DivideOperation();
                $op->setOperands($solved, $operand);
                $solved = $op->solve($this->variables);
            }
        }
        if ($this->containsOperations($solved)) {
            $mults = $this->parseMult($solved);
            return $this->solveMults($mults);
        } else {
            return $solved;
        }
    }

    private function parseAdd(string $formula)
    {
        //Get lowest level first
        $pattern = '/([\+\-]{1})/';
        $parsed = preg_split($pattern, $formula, -1, $this->pregFlag);
        return $parsed;
    }

    private function parseMult(string $formula)
    {
        //Get lowest level first
        $pattern = '/([\*\/]{1})/';
        $parsed = preg_split($pattern, $formula, -1, $this->pregFlag);
        return $parsed;
    }

    private function containsOperations(string $formula)
    {
        foreach (['+', '-', '*', '/'] as $operator) {
            if (strpos($formula, $operator) !== false) {
                return true;
            }
        }
        return false;
    }

    private function cleanFormula(): string
    {

        $cleaned = str_replace(' ', '', $this->originalFormula);
        return $cleaned;

    }
}
