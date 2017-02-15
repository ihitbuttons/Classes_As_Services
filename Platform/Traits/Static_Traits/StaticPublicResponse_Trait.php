<?php

namespace Platform\Traits\Static_Traits;

trait StaticPublicResponse_Trait
{
    private static $returnArray = [];

    private static function clearReturnArray ($method)
    {
        self::$returnArray[$method] = ["error" => false, "errorMessage" => false, "return" => false, "success" => SUCCESS_TRUE, "returnName" => false];
    }

    private static function setReturnProperty ($method, $property, $value)
    {
        self::$returnArray[$method]["$property"] = $value;
    }

    private static function generateReturn ($method)
    {
        if (self::$returnArray[$method]["error"] !== false) {
            self::setReturnProperty($method, "success", SUCCESS_FALSE);
        }

        return self::$returnArray[$method];
    }
}