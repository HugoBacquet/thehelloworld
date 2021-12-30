<?php

namespace App\Constant;

/**
 * Class ConsultationTypes
 * @package App\Constant
 */
abstract class ConsultationTypes
{
    const OTHERS = "Peu importe";
    const CABINET = "En cabinet";
    const DOMICILE = "A domicile";
    const TELECONSULTATION = "En téléconsultation";

    /** @var array user friendly named type */
    protected static $consultationTypesType = [
        self::OTHERS => 1,
        self::CABINET => 2,
        self::DOMICILE => 3,
        self::TELECONSULTATION => 4,
    ];

    /** @var array user friendly named type */
    protected static $consultationTypesHalfType = [
        self::CABINET => self::CABINET,
        self::DOMICILE => self::DOMICILE,
        self::TELECONSULTATION => self::TELECONSULTATION,
    ];

    /**
     * @param  string $consultationType
     * @return string
     */
    public static function getConsultationTypeName($consultationType)
    {
        if (!isset(static::$consultationTypesType[$consultationType])) {
            return "Unknown type ($consultationType)";
        }

        return static::$consultationTypesType[$consultationType];
    }

    /**
     * @param $number
     * @return mixed|string
     */
    public static function getNameByNumber($number)
    {
        switch ($number) {
            case 4 : return static::TELECONSULTATION;
            case 3 : return static::DOMICILE;
            case 2 : return static::CABINET;
            default : return null;
        }
    }

    /**
     * @return array<string>
     */
    public static function getConsultationTypes()
    {
        return self::$consultationTypesType;
    }

    /**
     * @return array<string>
     */
    public static function getHalfConsultationTypes()
    {
        return self::$consultationTypesHalfType;
    }
}