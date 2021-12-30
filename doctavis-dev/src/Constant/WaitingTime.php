<?php

namespace App\Constant;

/**
 * Class WaitingTime
 * @package App\Constant
 */
abstract class WaitingTime
{
    const SHORT = "48h";
    const MEDIUM = "1 semaine";
    const LONG = "1 mois";
    const VERY_LONG = "Plus d'1 mois";

    /** @var array user friendly named type */
    protected static $waitingTimesType = [
        self::SHORT => self::SHORT,
        self::MEDIUM => self::MEDIUM,
        self::LONG => self::LONG,
        self::VERY_LONG => self::VERY_LONG,
    ];

    /**
     * @param  string $waitingTime
     * @return string
     */
    public static function getWaitingTimeName($waitingTime)
    {
        if (!isset(static::$waitingTimesType[$waitingTime])) {
            return "Unknown type ($waitingTime)";
        }

        return static::$waitingTimesType[$waitingTime];
    }

    /**
     * @return array<string>
     */
    public static function getWaitingTimes()
    {
        return self::$waitingTimesType;
    }

}