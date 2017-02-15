<?php

namespace Examples\Implementors;

use \Examples\Interfaces\ServiceExample_Interface;
use \Platform\Traits\ServiceHandler_Trait;
use \Platform\Traits\LogError_Trait;
use \Platform\Traits\Class_Type\Singleton_Trait;
use \Platform\Traits\PublicResponse_Trait;

class ServiceExample_Class implements ServiceExample_Interface
{
    use Singleton_Trait;
    use ServiceHandler_Trait;
    use LogError_Trait;
    use PublicResponse_Trait;

    protected function __construct ($argumentArray)
    {
        $this->loggingDisabled = $argumentArray["loggingDisabled"];
        $this->logInfo(__METHOD__, "Called.");
    }

    public function makeServiceCall ($argumentsArray)
    {
        $this->logInfo(__METHOD__, "Called.");
        $this->clearReturnArray(__FUNCTION__);

        //get the arguments
        $method = $argumentsArray["method"];
        $service = $argumentsArray["service"];
        $serviceArgumentsArray = $argumentsArray["serviceArguments"];
        $methodArgumentsArray = $argumentsArray["methodArguments"];
        unset($argumentsArray);

        //Set the arguments for the service
        $returnedResponse = $this->callService($service, $serviceArgumentsArray, $method, $methodArgumentsArray);

        $returnedResponse = [$this->returnArray[__FUNCTION__]["returnName"] => $returnedResponse];

        //Set the information in the session superglobal for handling of the shutdown function
        if ($this->returnArray[__FUNCTION__]["error"] !== false) {
            $_SESSION["error"]["error"] = "TRUE";
            $_SESSION["error"]["response"] = $this->returnArray[__FUNCTION__]["errorMessage"];
        } else {
            $_SESSION["error"]["error"] = "FALSE";
            $_SESSION["error"]["response"] = "None";
        }

        //I should normalize the message
        $_SESSION["response"]["response"] = $returnedResponse;
        $_SESSION["response"]["success"] = $this->returnArray[__FUNCTION__]["success"];

        $shutdownClass = constant('DEFAULT_SHUTDOWN_FUNCTION');
        $shutdownClass::getInstance([])->preShutdown();
    }

    /****** BEGIN PRIVATE FUNCTIONS ******/
}