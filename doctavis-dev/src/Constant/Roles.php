<?php

namespace App\Constant;

/**
 * Class Roles
 * @package App\Constant
 */
abstract class Roles
{
    const ROLE_USER = "ROLE_USER";
    const ROLE_PRACTITIONER = "ROLE_PRACTITIONER";
    const ROLE_ADMIN = "ROLE_ADMIN";

    protected static $roles = [
        self::ROLE_USER => "ROLE_USER",
        self::ROLE_PRACTITIONER=> "ROLE_PRACTITIONER",
        self::ROLE_ADMIN => "ROLE_ADMIN",
    ];

    /**
     * @param  string $role
     * @return string
     */
    public static function getRoleName($role)
    {
        if (!isset(static::$roles[$role])) {
            return "Unknown type ($role)";
        }
        return static::$roles[$role];
    }

    /**
     * @return array<string>
     */
    public static function getRoles()
    {
        return self::$roles;
    }
}
