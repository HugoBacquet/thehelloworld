<?php

namespace App\Constant;

/**
 * Class Sector
 * @package App\Constant
 */
abstract class Sector
{
    const SECTOR_1 = "Tarifs de base fixés par l'Assurance maladie";
    const SECTOR_2 = "Tarifs fixés librement par le professionnel de santé";
    const SECTOR_3 = "Hors convention";

    /** @var array user friendly named type */
    protected static $sectorsType = [
        self::SECTOR_1 => 1,
        self::SECTOR_2 => 2,
        self::SECTOR_3 => 3,
    ];

    /** @var array user friendly named type */
    protected static $sectorsHalfType = [
        self::SECTOR_1 => self::SECTOR_1,
        self::SECTOR_2 => self::SECTOR_2,
        self::SECTOR_3 => self::SECTOR_3,
    ];

    /**
     * @param  string $sector
     * @return string
     */
    public static function getSectorName($sector)
    {
        if (!isset(static::$sectorsType[$sector])) {
            return "Unknown type ($sector)";
        }

        return static::$sectorsType[$sector];
    }

    /**
     * @param $number
     * @return mixed|string
     */
    public static function getNameByNumber($number)
    {
        switch ($number) {
            case 3 : return static::SECTOR_3;
            case 2 : return static::SECTOR_2;
            default : return static::SECTOR_1;
        }
    }

    /**
     * @param $name
     * @return mixed|string
     */
    public static function getNumberByName($name)
    {
        switch ($name) {
            case static::SECTOR_3 : return 3;
            case static::SECTOR_2 : return 2;
            default : return 1;
        }
    }

    /**
     * @return array<string>
     */
    public static function getSectors()
    {
        return self::$sectorsType;
    }

    /**
     * @return array<string>
     */
    public static function getHalfSectors()
    {
        return self::$sectorsHalfType;
    }
}