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

use Aesonus\Formula\Contracts\FormulaInterface;
use Aesonus\Formula\Contracts\FormulaParserInterface;

/**
 * Description of Formula
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class Formula implements FormulaInterface
{
    protected $variables = [];
    protected $originalFormula;
    protected $parser;
    protected $operations = [];

    public function __construct(
        FormulaParserInterface $parser,
        array $variables = []
    ) {
        $this->variables = [];
        $this->parser = $parser;
        $this->operations = [
            '+' => AddOperation::class,
            '-' => SubtractOperation::class,
            '*' => MultiplyOperation::class,
            '/' => DivideOperation::class,
        ];
    }

    public function solveFormula(string $formula, array $variables = array()): ?float
    {
        $this->variables = array_merge($this->variables, $variables);
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
        return $this->parser->parseParenthesis($formula);
    }

    protected function solveAdds(array $adds)
    {
        $solved = array_shift($adds);
        while ($operator = array_shift($adds)) {
            if (!empty($adds)) {
                $operand = array_shift($adds);
            }
            if ($this->containsOperations($solved)) {
                $mults = $this->parseMult($solved);
                $result = $this->solveMults($mults);
                array_unshift($adds, $operator, $operand);
                $solved = $result;
            } elseif ($this->containsOperations($operand)) {
                $mults = $this->parseMult($operand);
                $result = $this->solveMults($mults);
                array_push($adds, $operator, $result);
            } else {
                $solved = $this->solveOperation($operator, $solved, $operand);
            }
        }
        if ($this->containsOperations($solved)) {
            $mults = $this->parseMult($solved);
            return $this->solveMults($mults);
        } else {
            return $solved;
        }
    }

    protected function solveMults(array $mults)
    {
        $solved = array_shift($mults);
        while ($operator = array_shift($mults)) {
            if (!empty($mults)) {
                $operand = array_shift($mults);
            }
            if ($this->containsOperations($solved)) {
                $exps = $this->parseExp($solved);
                $result = $this->solveExps($exps);
                array_unshift($mults, $operator, $operand);
                $solved = $result;
            } elseif ($this->containsOperations($operand)) {
                $exps = $this->parseMult($operand);
                $result = $this->solveMults($exps);
                array_push($mults, $operator, $result);
            } else {
                $solved = $this->solveOperation($operator, $solved, $operand);
            }
        }
        if ($this->containsOperations($solved)) {
            $mults = $this->parseMult($solved);
            return $this->solveMults($mults);
        } else {
            return $solved;
        }
    }

    protected function solveOperation($operator, ...$operands)
    {
        $op = new $this->operations[$operator];
        $op->setOperands(...$operands);
        return $op->solve($this->variables);
    }

    private function parseAdd($formula)
    {
        return $this->parser->parseAdd($formula);
    }

    private function parseMult($formula)
    {
        return $this->parser->parseMult($formula);
    }

    private function parseExp($formula)
    {
        return $this->parser->parseExp($formula);
    }

    private function containsOperations(string $formula)
    {
        foreach (array_keys($this->operations) as $operator) {
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
