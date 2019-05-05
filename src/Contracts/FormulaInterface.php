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
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface FormulaInterface
{
    /**
     *
     * @param string $formula MUST be string representation of formula
     * @param array $variables MUST be an array of variables with the variable name as
     * the key and value as value. Variable values can be scalar or callable.
     * @return float|null
     */
    public function solveFormula(string $formula, array $variables = []): ?float;
}
