<?php

namespace Platform\Implementors;

use \Platform\Traits\Class_Type\Singleton_Trait;

class Shutdown_Class
{
    use Singleton_Trait;

    private $normalShutdown = false;

    public function preShutdown()
    {
        //normal shutdown occurred, indicate it
        $normalShutdown = true;

        //handle any pre-shut down conditions under normal circumstances here
        die();
    }

    public function shutdownFunction()
    {
        if ($this->normalShutdown === false) {
            //handle any abnormal shutdown conditions here
        } else {
            //handle any normal shutdown conditions here
        }

        //check for any fatal errors
        $lastError = error_get_last();
        if ($lastError['type'] === E_ERROR) {
            //fatal error
            $errorHandler = constant('DEFAULT_ERROR_HANDLER');
            $errorHandler::getInstance(["loggingDisabled" => true])->customErrorHandler(E_ERROR, $lastError['message'], $lastError['file'], $lastError['line']);
            $this->standardizedReturn();
        } else {
            $this->standardizedReturn();
        }
    }

    private function standardizedReturn()
    {
        //prep the error section
        if (!array_key_exists("error", $_SESSION)) {
            $_SESSION["error"]["error"] = "TRUE";
        }

        if (!array_key_exists("message", $_SESSION["error"])) {
            $_SESSION["error"]["message"] = "None";
        } else {
            $_SESSION["error"]["message"] .= " in file: " . $_SESSION["error"]["file"] . " on line: " . $_SESSION["error"]["line"];
        }

        //prep the API section
        if (!array_key_exists("response", $_SESSION)) {
            $_SESSION["response"]["success"] = SUCCESS_FALSE;
            $_SESSION["response"]["response"] = "MISSING";
        }

        //normalize the message
        $apiResponseArray = [
            "error" => [
                "errorPresent" => $_SESSION["error"]["error"],
                "errorMessage" => $_SESSION["error"]["message"],
            ],
            "response" => [
                "success" => $_SESSION["response"]["success"],
                "response" => $_SESSION["response"]["response"],
            ],
            "server" => [
                "serverTime" => date('Y-m-d H:i:s'),
            ]
        ];

        $jsonResponse = json_encode($apiResponseArray);

        if ($jsonResponse !== false) {
            $apiResponse = $jsonResponse;
        } else {
            $apiResponse = '{}';
        }

        echo $apiResponse . PHP_EOL;
    }
}