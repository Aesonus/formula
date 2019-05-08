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

use Aesonus\Formula\Contracts\FormulaParserInterface;
use InvalidArgumentException;

/**
 * Description of FormulaParser
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class FormulaParser implements FormulaParserInterface
{
    protected $pregFlag;
    
    public function __construct()
    {
        $this->pregFlag = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
        ;
    }

    public function parseAdd(string $formula): array
    {
        $pattern = '/([\+\-]{1})/';
        $parsed = preg_split($pattern, $formula, -1, $this->pregFlag);
        return $parsed;
    }

    public function parseMult(string $formula): array
    {
        $pattern = '/([\*\/]{1})/';
        $parsed = preg_split($pattern, $formula, -1, $this->pregFlag);
        return $parsed;
    }
    public function parseParenthesis(string $formula): array
    {
        $open = '(';
        $close = ')';
        $parenthesis = [];
        $offset = 0;

        while (($pos = strpos($formula, $open, $offset)) !== false) {
            $nextOpen = strpos($formula, $open, $pos + 1);
            $nextClose = strpos($formula, $close, $pos + 1);
            if ($nextClose === false) {
                throw new InvalidArgumentException(__METHOD__ . 'Formula is missing closing parenthesis');
            }
            $offset = $pos + 1;
            if (($nextOpen === false ? 99999 : $nextOpen)  > $nextClose) {
                $parenthesis[] = substr($formula, $pos + 1, $nextClose - $offset);
            }
        }
        return $parenthesis;
    }

    public function parseExp(string $formula): array
    {
        $pattern = '/([\^_]{1})/';
        $parsed = preg_split($pattern, $formula, -1, $this->pregFlag);
        return $parsed;
    }
}
