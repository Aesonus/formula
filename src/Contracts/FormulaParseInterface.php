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
interface FormulaParseInterface
{
    /**
     * Returns an array of operations to be carried out
     * @param string $formula
     * @return OperationInterface[]
     */
    public function parse(string $formula): array;
}
