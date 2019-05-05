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
class DivideOperation extends MultiplyOperation
{
    protected function operation(?float $carry, float $operand)
    {
        if (!isset($carry)) {
            return $operand;
        }
        return $carry /= $operand;
    }
}
