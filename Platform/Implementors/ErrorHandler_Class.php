<?php

namespace Platform\Implementors;

use \Platform\Traits\Class_Type\Singleton_Trait;

class ErrorHandler_Class
{
    use Singleton_Trait;

    public function customErrorHandler ($error, $message, $file, $line)
    {
        $_SESSION["error"]["error"] = "TRUE";
        $_SESSION["error"]["message"] = $message;
        $_SESSION["error"]["file"] = $file;
        $_SESSION["error"]["line"] = $line;
    }


}