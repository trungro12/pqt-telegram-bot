<?php

namespace PQT_TELEGRAM_BOT\Lib\Service;

class InitService
{

    private static $isInit = false;
    private static $isAdminInit = false;
    private static $arrServiceClass = [
        OptionService::class,
        APIService::class
    ];

    public static function init()
    {

        // check for is init or not
        if (self::$isInit) return;
        self::$isInit = true;

        self::initAll("init");
    }

    public static function adminInit()
    {
        // check for is init or not
        if (self::$isAdminInit) return;
        self::$isAdminInit = true;

        self::initAll("adminInit");
    }

    /**
     * run all init function 
     */
    private static function initAll($initFunctionName = "init")
    {
        foreach (self::$arrServiceClass as $class) {
            if ($class === __CLASS__) continue;

            if (!method_exists($class, $initFunctionName)) continue;

            call_user_func($class . "::" . $initFunctionName);
        }
    }
}
