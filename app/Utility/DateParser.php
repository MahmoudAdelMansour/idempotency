<?php

namespace App\Utility;

use Carbon\Carbon;
use InvalidArgumentException;

class DateParser
{
    public static function parse(string $dateString): Carbon
    {



        $dateString = trim($dateString);
        echo $dateString;
        return match(strlen($dateString)) {
            10 => self::parseTimestamp($dateString),
            11 => self::parseDateIgnoreDigits($dateString),
            14 => self::parseDateTime($dateString),
            8 => self::parseDate($dateString),
            default => throw new InvalidArgumentException(
                "Unknown date format length: {$dateString} it's ".strlen($dateString)
            )
        };
    }

    private static function parseTimestamp(string $dateString): Carbon
    {
        if (!is_numeric($dateString)) {
            throw new InvalidArgumentException("Invalid timestamp: {$dateString}");
        }

        return Carbon::createFromTimestamp((int) $dateString);
    }

    private static function parseDateTime(string $dateString): Carbon
    {
        $date = Carbon::createFromFormat('YmdHis', $dateString);

        if (!$date) {
            throw new InvalidArgumentException("Invalid datetime format: {$dateString}");
        }

        return $date;
    }

    private static function parseDate(string $dateString): Carbon
    {

        $date = Carbon::createFromFormat('Ymd', $dateString);

        if (!$date) {
            throw new InvalidArgumentException("Invalid date format: {$dateString}");
        }

        return $date->startOfDay();
    }
    private static function parseDateIgnoreDigits(string $dateString): Carbon
    {
        $date = Carbon::createFromFormat('Ymd', substr($dateString, 0, 8));
        return $date->startOfDay();
    }
}
