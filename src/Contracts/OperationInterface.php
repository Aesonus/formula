<?php
/*
 * This file is part of the aesonus/formula package
 *
 *  (c) Cory Laughlin <corylcomposinger@gmail.com>
 *
 * For full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Aesonus\Formula\Contracts;

/**
 * Defines methods for solving a single operation in a formula
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface OperationInterface
{
    /**
     * MUST solve the operation replacing any variables in the formula with
     * values from $variables
     * @param array $variables MUST be an array of variables with the variable name as
     * the key and value as value. Variable values can be scalar or callable.
     * @return float|null
     */
    public function solve(array $variables = []): ?float;

    /**
     *
     * @param ...$operands Variable length argument list. Each argument SHOULD be a float EXPECT for grouping operations like ()
     */
    public function setOperands(...$operands);

    /**
     * MUST call and return solve method
     * @param array $variables
     */
    public function __invoke(array $variables = []);
}
