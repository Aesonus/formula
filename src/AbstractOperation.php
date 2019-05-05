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
 *
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
abstract class AbstractOperation implements Contracts\OperationInterface
{
    protected $operands = [];

    public function setOperands(...$operands)
    {
        foreach ($operands as $value) {
            $this->operands[] = $value;
        }
    }

    public function solve(array $variables = array()): ?float
    {
        return array_reduce($this->operands, function ($carry, $operand) use ($variables) {
            $operand = (string) $operand;
            if (key_exists($operand, $variables)) {
                $operand = is_callable($variables[$operand]) ? $variables[$operand]() : $variables[$operand];
            }
            return $this->operation($carry, $operand);
        });
    }

    public function __invoke(array $variables = array())
    {
        return $this->solve($variables);
    }

    /**
     * MUST carry out array reduction to apply the class's operation
     * @param ?float $carry
     * @param float $operand
     */
    abstract protected function operation(?float $carry, float $operand);
}
