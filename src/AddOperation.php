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
class AddOperation extends AbstractOperation
{
    protected function operation(?float $carry, float $operand)
    {
        $carry = $carry ?? 0;
        return $carry += $operand;
    }
}
