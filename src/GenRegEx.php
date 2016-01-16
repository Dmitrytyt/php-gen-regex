<?php
/**
 * Created by PhpStorm.
 * User: EC_l
 * Date: 04.02.14
 * Time: 16:55
 * Email: bpteam22@gmail.com
 */

namespace bpteam\GenRegEx;

use ReverseRegex\Lexer;
use ReverseRegex\Random\SimpleRandom;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;

class GenRegEx
{

    /**
     * decorate html tag for find it in other implementation
     * @param $tag string|array html tag like <div class="abc"> or [<a href="/">, <p class="123">]
     * @return string|null|array universal regex for this tag
     */
    public static function htmlTag($tag)
    {
        $replaceData = [
            '~(\S+\s*=)~' => '[^>]*$1',
            '~>$~ms' => '[^>]*>',
            '~^<(\w+)~ms' => '<$1[^>]*',
            '~=~ms' => '\s*=\s*',
            '~["\']~ms' => '["\']?',
            '~\s+~ms' => '\s*',
        ];

        return preg_replace(array_keys($replaceData), array_values($replaceData), $tag);
    }

    /**
     * @param array $data
     * @return string
     */
    public static function implodeOr(array $data)
    {
        $result = [];
        foreach ($data as $value) {
            if (is_array($value)) {
                $subResult = self::implodeOr($value);
                if ($subResult) {
                    $result[] = $subResult;
                }
            } else {
                $result[] = $value;
            }
        }
        $or = '(' . implode('|', array_unique($result)) . ')';

        return strlen($or) > 2 ? $or : '';
    }

    /**
     * @param string $data
     * @param string $separator
     * @return string
     */
    public static function separateString($data = '', $separator = '')
    {
        if (preg_match_all('~(?<symbol>.)~mus', $data, $match)) {
            $data = implode($separator, $match['symbol']);
        }

        return $data;
    }

    /**
     * @param array $data
     * @param string $separator
     * @return string
     */
    public static function separateArray(array $data, $separator = '')
    {
        $result = [];
        foreach ($data as $value) {
            if (is_array($value)) {
                $value = self::separateArray($value, $separator);
            }
            $result[] = $value;
        }

        return implode($separator, $result);
    }

    public static function textByPattern($pattern, $seed = 1)
    {
        $result = '';

        (new Parser(new Lexer($pattern), new Scope(), new Scope()))
            ->parse()
            ->getResult()
            ->generate($result, new SimpleRandom($seed));

        return $result;
    }
}