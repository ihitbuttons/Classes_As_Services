<?php

namespace Platform\Implementors;

use \Platform\Traits\Class_Type\StaticClass_Trait;

class OutputLogging_Class
{
    use StaticClass_Trait;

    public static function logMessage ($argumentArray)
    {
        //get arguments
        $class = $argumentArray["className"];
        $method = $argumentArray["methodName"];
        $message = $argumentArray["message"];
        $level = $argumentArray["level"];
        unset($argumentArray);

        $messageString = "Level: $level, Class: $class, Method: $method, Message: $message";
        echo var_export($messageString, true) . PHP_EOL . PHP_EOL;
    }
}