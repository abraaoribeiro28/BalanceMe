<?php

namespace App\Support;

final class MoneyParser
{
    public static function toDecimal(mixed $value): ?string
    {
        $normalized = self::normalize($value);

        if ($normalized === null) {
            return null;
        }

        return number_format((float) $normalized, 2, '.', '');
    }

    public static function toFloat(mixed $value): ?float
    {
        $normalized = self::normalize($value);

        if ($normalized === null) {
            return null;
        }

        return (float) $normalized;
    }

    private static function normalize(mixed $value): ?string
    {
        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if (!is_string($value)) {
            return null;
        }

        $input = trim($value);

        if ($input === '') {
            return null;
        }

        $input = str_replace(["\xc2\xa0", ' '], '', $input);
        $input = preg_replace('/[^\d,.\-]/', '', $input) ?? '';

        if ($input === '' || $input === '-' || $input === ',' || $input === '.') {
            return null;
        }

        $hasComma = str_contains($input, ',');
        $hasDot = str_contains($input, '.');

        if ($hasComma && $hasDot) {
            $lastComma = strrpos($input, ',');
            $lastDot = strrpos($input, '.');
            $decimalSeparator = $lastComma > $lastDot ? ',' : '.';
            $thousandsSeparator = $decimalSeparator === ',' ? '.' : ',';

            $input = str_replace($thousandsSeparator, '', $input);
            $input = str_replace($decimalSeparator, '.', $input);
        } elseif ($hasComma) {
            $input = self::normalizeSingleSeparator($input, ',');
        } elseif ($hasDot) {
            $input = self::normalizeSingleSeparator($input, '.');
        }

        if (!is_numeric($input)) {
            return null;
        }

        return $input;
    }

    private static function normalizeSingleSeparator(string $value, string $separator): string
    {
        $count = substr_count($value, $separator);

        if ($count > 1) {
            $lastPos = strrpos($value, $separator);
            $fractionLength = strlen($value) - $lastPos - 1;

            if ($fractionLength === 2) {
                $integerPart = str_replace($separator, '', substr($value, 0, $lastPos));
                $fractionPart = substr($value, $lastPos + 1);

                return $integerPart . '.' . $fractionPart;
            }

            return str_replace($separator, '', $value);
        }

        $pos = strrpos($value, $separator);
        $fractionLength = strlen($value) - $pos - 1;

        if ($separator === '.' && $fractionLength === 3) {
            return str_replace($separator, '', $value);
        }

        return str_replace($separator, '.', $value);
    }
}
