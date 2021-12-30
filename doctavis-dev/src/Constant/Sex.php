<?php

namespace App\Constant;

/**
 * Class Sex
 * @package App\Constant
 */
abstract class Sex
{
    const OTHERS = "Peu importe";
    const MALE = "Homme";
    const FEMALE = "Femme";
    const NON_BINARY = "Non Binaire";

    /** @var array user friendly named type */
    protected static $sexType = [
        self::OTHERS => self::OTHERS,
        self::MALE => self::MALE,
        self::FEMALE => self::FEMALE,
        self::NON_BINARY => self::NON_BINARY
    ];

    /** @var array user friendly named type */
    protected static $sexHalfType = [
        self::MALE => self::MALE,
        self::FEMALE => self::FEMALE,
        self::NON_BINARY => self::NON_BINARY
    ];

    /**
     * @param  string $sex
     * @return string
     */
    public static function getSexName($sex)
    {
        if (!isset(static::$sexType[$sex])) {
            return "Unknown type ($sex)";
        }

        return static::$sexType[$sex];
    }

    /**
     * @return array<string>
     */
    public static function getSexes()
    {
        return self::$sexType;
    }

    /**
     * @return array<string>
     */
    public static function getHalfSexes()
    {
        return self::$sexHalfType;
    }
}