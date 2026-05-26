<?php

namespace Ades4827\Sprintflow\Helpers;

class ColorHelper
{
    /**
     * Convert hex color (#ff0000) to RGB integer representation
     *
     * @param string $htmlCode Hex color code
     * @return int RGB integer value
     */
    public static function HTMLToRGB(string $htmlCode): int
    {
        if ($htmlCode[0] === '#') {
            $htmlCode = substr($htmlCode, 1);
        }

        if (strlen($htmlCode) == 3) {
            $htmlCode = $htmlCode[0].$htmlCode[0].$htmlCode[1].$htmlCode[1].$htmlCode[2].$htmlCode[2];
        }

        $r = hexdec($htmlCode[0].$htmlCode[1]);
        $g = hexdec($htmlCode[2].$htmlCode[3]);
        $b = hexdec($htmlCode[4].$htmlCode[5]);

        return $b + ($g << 0x8) + ($r << 0x10);
    }

    /**
     * Convert RGB integer to HSL object
     *
     * @param int $RGB RGB integer value
     * @return object Object with hue, saturation, lightness properties
     */
    public static function RGBToHSL(int $RGB): object
    {
        $r = 0xFF & ($RGB >> 0x10);
        $g = 0xFF & ($RGB >> 0x8);
        $b = 0xFF & $RGB;

        $r = ((float) $r) / 255.0;
        $g = ((float) $g) / 255.0;
        $b = ((float) $b) / 255.0;

        $maxC = max($r, $g, $b);
        $minC = min($r, $g, $b);

        $l = ($maxC + $minC) / 2.0;

        if ($maxC == $minC) {
            $s = 0;
            $h = 0;
        } else {
            if ($l < .5) {
                $s = ($maxC - $minC) / ($maxC + $minC);
            } else {
                $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
            }
            if ($r == $maxC) {
                $h = ($g - $b) / ($maxC - $minC);
            }
            if ($g == $maxC) {
                $h = 2.0 + ($b - $r) / ($maxC - $minC);
            }
            if ($b == $maxC) {
                $h = 4.0 + ($r - $g) / ($maxC - $minC);
            }

            $h = $h / 6.0;
        }

        $h = (int) round(255.0 * $h);
        $s = (int) round(255.0 * $s);
        $l = (int) round(255.0 * $l);

        return (object) ['hue' => $h, 'saturation' => $s, 'lightness' => $l];
    }

    /**
     * Check if hex color is dark or light
     *
     * Usage: Helper::colorIsDarker($bg_color) ? '#ffffff' : '#000000'
     *
     * @param string $htmlCode Hex color code
     * @return bool True if color is dark, false if light
     */
    public static function colorIsDarker(string $htmlCode): bool
    {
        if ($htmlCode == '') {
            return true;
        }
        $rgb = self::HTMLToRGB($htmlCode);
        $hsl = self::RGBToHSL($rgb);

        return $hsl->lightness < 200;
    }

    /**
     * Generate all shade variations (50-900) from a base hex color
     * The provided color will be EXACTLY the specified base shade
     *
     * @param string $hex Base color in hex format (e.g., #3b82f6)
     * @param int $baseShade Which shade number the color represents (default: 500)
     * @return array Associative array with keys 50, 100, 200, ..., 900
     */
    public static function generateShades(string $hex, int $baseShade = 500): array
    {
        // Remove # if present
        $hex = ltrim($hex, '#');

        // Convert HEX to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // The base shade is EXACTLY the provided color
        $shades = [];
        $shades[$baseShade] = "$r $g $b";

        // Convert RGB to HSL to get base values
        $rgb = self::HTMLToRGB($hex);
        $hsl = self::RGBToHSL($rgb);

        // Convert from 0-255 scale to 0-1 scale
        $h = $hsl->hue / 255.0;
        $s = $hsl->saturation / 255.0;
        $baseLightness = $hsl->lightness / 255.0;

        // Define all possible shades
        $allShades = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900];

        // Lightness mapping for standard shades
        $lightnessMap = [
            50 => 0.95,
            100 => 0.90,
            200 => 0.80,
            300 => 0.70,
            400 => 0.60,
            500 => 0.50,
            600 => 0.40,
            700 => 0.30,
            800 => 0.20,
            900 => 0.10,
        ];

        // Calculate lightness adjustment based on base shade
        $baseLightnessTarget = $lightnessMap[$baseShade];
        $lightnessOffset = $baseLightness - $baseLightnessTarget;

        // Generate all other shades
        foreach ($allShades as $shade) {
            if ($shade === $baseShade) {
                continue; // Skip base shade, already set
            }

            // Apply lightness offset to maintain relative differences
            $targetLightness = $lightnessMap[$shade] + $lightnessOffset;

            // Clamp lightness between 0 and 1
            $targetLightness = max(0, min(1, $targetLightness));

            $rgb = self::hslToRgb($h, $s, $targetLightness);
            $shades[$shade] = implode(' ', $rgb);
        }

        // Sort by key to maintain order
        ksort($shades);

        return $shades;
    }

    /**
     * Convert hex color to RGB string format for CSS (e.g., "59 130 246")
     *
     * @param string $hex Hex color code
     * @return string RGB values separated by spaces
     */
    public static function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "$r $g $b";
    }

    /**
     * Convert HSL values to RGB array
     *
     * @param float $h Hue (0-1)
     * @param float $s Saturation (0-1)
     * @param float $l Lightness (0-1)
     * @return array RGB values [r, g, b] (0-255 range)
     */
    private static function hslToRgb(float $h, float $s, float $l): array
    {
        if ($s == 0) {
            $r = $g = $b = $l; // achromatic
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;

            $r = self::hueToRgb($p, $q, $h + 1/3);
            $g = self::hueToRgb($p, $q, $h);
            $b = self::hueToRgb($p, $q, $h - 1/3);
        }

        return [
            round($r * 255),
            round($g * 255),
            round($b * 255)
        ];
    }

    /**
     * Helper for HSL to RGB conversion
     *
     * @param float $p
     * @param float $q
     * @param float $t
     * @return float RGB component value (0-1)
     */
    private static function hueToRgb(float $p, float $q, float $t): float
    {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1/2) return $q;
        if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;

        return $p;
    }
}
