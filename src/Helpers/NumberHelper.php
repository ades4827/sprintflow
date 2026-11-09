<?php

namespace Ades4827\Sprintflow\Helpers;

class NumberHelper
{
    /**
     * Get percentage of partial value from total
     *
     * Usage: NumberHelper::getPercentage(1100, 123)
     *
     * @param float $total Total value
     * @param float $partial Partial value
     * @param bool $limit_100 Limit result to 100 max
     * @return float Percentage value
     */
    public static function getPercentage(float $total, float $partial, bool $limit_100 = true): float
    {
        $percentage = 0;
        if ($total) {
            $percentage = round($partial / $total * 100, 2);
        }
        if ($limit_100 && $percentage > 100) {
            $percentage = 100;
        }

        return $percentage;
    }

    /**
     * Add VAT to a value, or remove it from a gross value.
     *
     * Usage: NumberHelper::applyVat(1100, 22)
     *
     * @param float $value     Starting amount
     * @param int   $vat       VAT rate as a percentage (e.g. 22 = 22%)
     * @param bool  $reverse   true = extract the VAT from the gross value
     * @param int   $precision Decimal places for rounding
     */
    public static function applyVat(float $value, int $vat, bool $reverse = false, int $precision = 2): float
    {
        if ($vat < 0 || $vat > 100) {
            throw new \InvalidArgumentException(
                "Invalid VAT rate: {$vat}. It must be between 0 and 100."
            );
        }

        if ($vat === 0) {
            return round($value, $precision);
        }

        $multiplier = 1 + ($vat / 100);

        $result = $reverse
            ? $value / $multiplier
            : $value * $multiplier;

        return round($result, $precision);
    }

    /**
     * Get effective discount amount on partial value
     * Note: discount is only positive
     *
     * Usage: NumberHelper::getDiscountOf(110, 10) returns 11
     *
     * @param float $partial Value to calculate discount on
     * @param float $discount Discount percentage
     * @return float Discount amount
     */
    public static function getDiscountOf(float $partial, float $discount): float
    {
        if ($discount < 0) {
            return 0;
        }

        return ($partial * $discount) / 100;
    }

    /**
     * Apply percentage discount to partial value
     * Note: discount is only positive
     *
     * Usage: NumberHelper::applyDiscountPercentage(100, 20) returns 80
     *
     * @param float $partial Original value
     * @param float $discount Discount percentage
     * @return float Value after discount
     */
    public static function applyDiscountPercentage(float $partial, float $discount): float
    {
        if ($discount < 0) {
            return $partial;
        }

        $discountAmount = ($partial * $discount) / 100;

        return $partial - $discountAmount;
    }

    /**
     * Check whether a value is zero or close to zero.
     *
     * @param mixed $value
     * @param float $epsilon  threshold below which the number is considered zero
     * @return bool           true = zero (or nearly zero), false = a valid non-zero number
     */
    public static function isEffectivelyZero(mixed $value, float $epsilon = 1e-10): bool
    {
        // Non-numeric values (null, empty string, bool, etc.)
        if (!is_numeric($value)) {
            return true;
        }

        return abs((float) $value) <= $epsilon;
    }

    /**
     * Check whether a number is a multiple of another.
     *
     * Supports both integers and decimals (float).
     * For floats a tolerance based on PHP_FLOAT_EPSILON is used
     * to avoid precision errors in the binary representation.
     *
     * @param int|float $number The number to check.
     * @param int|float $divisor The divisor to check the multiple against.
     * @param float $epsilon Tolerance for the float comparison (default: PHP_FLOAT_EPSILON).
     *
     * @return bool  True if $number is a multiple of $divisor, false otherwise.
     *
     * @throws \InvalidArgumentException  If $divisor is zero.
     *
     * @example
     *   NumberHelper::isMultiple(10, 5);        // true
     *   NumberHelper::isMultiple(10, 3);        // false
     *   NumberHelper::isMultiple(7.5, 2.5);     // true
     *   NumberHelper::isMultiple(25.2, 4.2);     // true
     *   NumberHelper::isMultiple(0.3, 0.1);     // true  (handled via epsilon)
     *   NumberHelper::isMultiple(10, 0);        // throws InvalidArgumentException
     *
     * The legacy Italian named arguments `numero:` and `divisore:` are still accepted
     * through $legacyArgs, but they trigger an E_USER_DEPRECATED notice and will be
     * removed in the next major release. Use `number:` and `divisor:` instead.
     */
    public static function isMultiple(
        int|float|null $number = null,
        int|float|null $divisor = null,
        float $epsilon = PHP_FLOAT_EPSILON,
        mixed ...$legacyArgs,
    ): bool {
        // Backward compatibility for the old Italian parameter names (named arguments only).
        foreach (['numero' => 'number', 'divisore' => 'divisor'] as $old => $new) {
            if (! array_key_exists($old, $legacyArgs)) {
                continue;
            }
            if ($$new !== null) {
                throw new \ArgumentCountError(__METHOD__."(): named arguments \${$old} and \${$new} cannot be used together");
            }
            trigger_error(
                __METHOD__."(): the named argument \"{$old}\" is deprecated, use \"{$new}\" instead.",
                E_USER_DEPRECATED
            );
            // mimic PHP's coercion of numeric strings for int|float parameters
            $value = $legacyArgs[$old];
            $$new = is_string($value) && is_numeric($value) ? $value + 0 : $value;
            unset($legacyArgs[$old]);
        }

        // Keep PHP's native behaviour for any other unknown named argument.
        foreach (array_keys($legacyArgs) as $key) {
            if (is_string($key)) {
                throw new \Error(__METHOD__."(): Unknown named parameter \${$key}");
            }
        }

        if ($number === null || $divisor === null) {
            throw new \ArgumentCountError(__METHOD__.'(): both $number and $divisor are required');
        }
        if (! is_int($number) && ! is_float($number)) {
            throw new \TypeError(__METHOD__.'(): $number must be of type int|float, '.get_debug_type($number).' given');
        }
        if (! is_int($divisor) && ! is_float($divisor)) {
            throw new \TypeError(__METHOD__.'(): $divisor must be of type int|float, '.get_debug_type($divisor).' given');
        }

        if ($divisor == 0) {
            throw new \InvalidArgumentException('The divisor cannot be zero.');
        }

        if (is_int($number) && is_int($divisor)) {
            return $number % $divisor === 0;
        }

        $remainder = fmod((float)$number, (float)$divisor);

        // fmod may return a value close to $divisor instead of 0,
        // so normalize the remainder into the range [0, |divisor|)
        $remainderAbs = abs($remainder);
        $divAbs   = abs((float)$divisor);

        // Scale epsilon to the magnitude of the numbers involved
        $tolerance = $epsilon * max(abs((float)$number), $divAbs);

        return $remainderAbs < $tolerance || abs($remainderAbs - $divAbs) < $tolerance;
    }
}
