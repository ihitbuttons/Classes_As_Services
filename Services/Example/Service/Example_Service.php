<?php

namespace Services\Example\Service;

use \Platform\Traits\Class_Type\Singleton_Trait;
use \Platform\Traits\LogError_Trait;

class Example_Service
{
    use Singleton_Trait;
    use LogError_Trait;

    private $service = false;
    private $serviceOverride = false;

    protected function __construct($argumentArray)
    {
        $this->loggingDisabled = $argumentArray["loggingDisabled"];
        $this->logInfo(__METHOD__, "Called.");
    }

    public function returnServiceObject ($argumentArray)
    {
        $this->logInfo(__METHOD__, "Called.");

        if (@array_key_exists("currentVersion", $argumentArray)) {
            $currentVersion = $argumentArray["currentVersion"];
        } else {
            $currentVersion = CURRENT_VERSION;
        }

        //check the service override
        if (@array_key_exists("serviceOverride", $argumentArray)) {
            $this->serviceOverride = true;
        } else {
            //we aren't overriding the default service
            //check if the service is already set
            if ($this->service !== false) {
                //it does, return it
                return $this->service;
            }
        }

        //check if this was the orginating service. If so, override the default behavior and treat as local
        if (($_SESSION["service"] == "Example")) {
            $location = "local";
        } else {
            if (defined('EXAMPLE_SERVICE_LOCATION')) {
                $location = EXAMPLE_SERVICE_LOCATION;
            } else {
                $location = "local";
            }
        }

        if ($location == "local") {
            //check if it needs to be a reciever
            if (@array_key_exists("reciever", $argumentArray)) {
                $service = new \Services\Example\Implementors\Example_Reciever($argumentArray);
            } else {
                //treat as local
                switch ($currentVersion) {
                    case 1:
                        $service = new \Services\Example\Implementors\Example_Class($argumentArray);
                        break;

                    case 2:
                        $service = new \Services\Example\Implementors\Example_Alt_Class($argumentArray);
                        break;

                    default:
                        $service = new \Services\Example\Implementors\Example_Class($argumentArray);
                        break;
                }
            }
        } else {
            //treat as remote
            $service = new \Services\Example\Implementors\Example_Sender($argumentArray);
        }

        if ($this->serviceOverride === false) {
            $this->serviceOverride = false;
            return $service;
        } else {
            $this->service = $service;
            return $this->service;
        }
    }
}
