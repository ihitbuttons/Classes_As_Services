<?php

namespace Platform\Traits\Static_Traits;

use \Platform\Exceptions\Generic_Exception;

trait StaticLogError_Trait {

    public static $loggingDisabled = false;

    private static function logError ($method, $message)
    {
        //developer initiated error
        //any pre-handling should be done here

        //determine if we want to log anything
        self::logTheInformation(6, __CLASS__, $method, $message);

        //throw the exception
        throw new Generic_Exception($method . "::" . $message);
    }

    private static function logWarning ($method, $message)
    {
        //developer initiated warning
        //any pre-handling should be done here

        //determine if we want to log anything
        self::logTheInformation(4, __CLASS__, $method, $message);
    }

    private static function logInfo ($method, $message)
    {
        //developer initiated debugging message
        //any pre-handling should be done here

        //determine if we want to log anything
        self::logTheInformation(2, __CLASS__, $method, $message);
    }

    private static function logTheInformation ($level, $class, $method, $message)
    {
        //check the current logging level
        if (CURRENT_LOGGING_LEVEL > $level) {
            if (is_object($message)) {
                $message = \serialize($message);
            }

            if (is_array($message)) {
                $message = \json_encode($message);
            } else {
                $message = \var_export($message, true);
            }

            if (is_bool($message)) {
                $message = ($message) ? 'true' : 'false';
            }

            $argumentArray["className"] = $class;
            $argumentArray["methodName"] = $method;
            $argumentArray["message"] = $message;
            $argumentArray["level"] = $level;

            $defaultLoggingClass = constant('DEFAULT_LOGGING_CLASS');
            $defaultLoggingClass::logMessage($argumentArray);
        }
    }
}