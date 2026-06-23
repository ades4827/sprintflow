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

    /**
     * Verifica se un numero è multiplo di un altro.
     *
     * Supporta sia interi che numeri decimali (float).
     * Per i float viene usata una tolleranza basata su PHP_FLOAT_EPSILON
     * per evitare errori di precisione nella rappresentazione binaria.
     *
     * @param int|float $numero Il numero da verificare.
     * @param int|float $divisore Il divisore rispetto al quale verificare il multiplo.
     * @param float $epsilon Tolleranza per il confronto float (default: PHP_FLOAT_EPSILON).
     *
     * @return bool  True se $numero è multiplo di $divisore, false altrimenti.
     *
     * @throws \InvalidArgumentException  Se $divisore è zero.
     *
     * @example
     *   NumberHelper::isMultiple(10, 5);        // true
     *   NumberHelper::isMultiple(10, 3);        // false
     *   NumberHelper::isMultiple(7.5, 2.5);     // true
     *   NumberHelper::isMultiple(25.2, 4.2);     // true
     *   NumberHelper::isMultiple(0.3, 0.1);     // true  (gestito con epsilon)
     *   NumberHelper::isMultiple(10, 0);        // throws InvalidArgumentException
     */
    public static function isMultiple(int|float $numero, int|float $divisore, float $epsilon = PHP_FLOAT_EPSILON): bool
    {
        if ($divisore == 0) {
            throw new \InvalidArgumentException('Il divisore non può essere zero.');
        }

        if (is_int($numero) && is_int($divisore)) {
            return $numero % $divisore === 0;
        }

        $resto = fmod((float)$numero, (float)$divisore);

        // fmod può restituire un valore prossimo a $divisore invece di 0
        // quindi normalizziamo il resto nel range [0, |divisore|)
        $restNorm = abs($resto);
        $divAbs   = abs((float)$divisore);

        // Scala epsilon rispetto alla grandezza dei numeri in gioco
        $tolerance = $epsilon * max(abs((float)$numero), $divAbs);

        return $restNorm < $tolerance || abs($restNorm - $divAbs) < $tolerance;
    }
}
