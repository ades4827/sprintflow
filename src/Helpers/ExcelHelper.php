<?php

namespace Ades4827\Sprintflow\Helpers;

use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelHelper
{
    private static function checkDependency() {
        if (!class_exists('PhpOffice\\PhpSpreadsheet\\Shared\\Date')) {
            throw new \RuntimeException(
                'The package "phpoffice/phpspreadsheet" is not installed. ' .
                'Install it as a requirement with: composer require maatwebsite/excel'
            );
        }
    }

    // example: 46268.604166667 -> Carbon::parse('2026-09-03 14:30:00')
    public static function toDateTime($value)
    {
        self::checkDependency();

        if (is_numeric($value)) {
            return Carbon::parse(Date::excelToDateTimeObject($value));
        }

        // fallback se arriva già come stringa data
        return Carbon::parse($value);
    }
}
