<?php

namespace Ades4827\Sprintflow\Helpers;

class NumberHelper
{
    /**
     * Get percentage of partial value from total
     *
     * Usage: Helper::getPercentage(1100, 123)
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
     * Get effective discount amount on partial value
     * Note: discount is only positive
     *
     * Usage: Helper::getDiscountOf(110, 10) returns 11
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
     * Usage: Helper::applyDiscountPercentage(100, 20) returns 80
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
     * Verifica se un valore è zero o prossimo a zero.
     *
     * @param mixed $value
     * @param float $epsilon  soglia sotto cui il numero è considerato zero
     * @return bool           true = è zero (o quasi), false = è un numero valido
     */
    public static function isEffectivelyZero(mixed $value, float $epsilon = 1e-10): bool
    {
        // Valori non numerici (null, stringa vuota, bool, ecc.)
        if (!is_numeric($value)) {
            return true;
        }

        return abs((float) $value) <= $epsilon;
    }
}
