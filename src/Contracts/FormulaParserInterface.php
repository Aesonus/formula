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
interface FormulaParserInterface
{
    public function parseParenthesis(string $formula): array;

    public function parseAdd(string $formula): array;

    public function parseMult(string $formula): array;

    public function parseExp(string $formula): array;
}
