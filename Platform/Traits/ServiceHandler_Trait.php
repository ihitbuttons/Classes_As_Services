<?php

namespace Platform\Traits;

use \Platform\Exceptions\ServiceHandler_Exception;
use \Platform\Exceptions\ValidateType_Exception;

trait ServiceHandler_Trait
{
    //include the type validator
    use ValidateType_Trait;

    //set default arguments for all services
    private $defaultServiceArguments = [
        "location" => "local",
        "internal" => false
    ];

    private $serviceOverrideArray = [];

    private function callService ($service, $serviceArgumentsArray, $method, $argumentArray)
    {
        $this->logInfo(__METHOD__, "Called.");

        //merge the supplied arguments (the supplied arguments will overwrite the defaults if provided)
        $serviceArgumentsArray = array_merge($this->defaultServiceArguments, $serviceArgumentsArray);

        if (@array_key_exists($service, $this->serviceOverrideArray)) {
            $service = $this->serviceOverrideArray[$service];
        }

        $serviceArgumentsArray["loggingDisabled"] = $this->loggingDisabled;

        //get the service FQN
        $serviceFQN = '\Services\\' . $service . '\Service\\' . $service . '_Service';
        //get it's instance
        $serviceInstance = $serviceFQN::getInstance($serviceArgumentsArray);
        //get the service object
        $serviceObject = $serviceInstance->returnServiceObject($serviceArgumentsArray);

        //lets see if an object was returned
        $responseReturn = false;
        if (is_object($serviceObject)) {
            //check for auto-discovery
            if ($method == "argumentList") {
                $finalArgumentArray["argumentArray"]["method"] = "argumentList";
                $finalArgumentArray["argumentArray"]["internal"] = false;
                $response = call_user_func_array([$serviceObject, $method], $finalArgumentArray);
                $responseReturn = $response["return"];
            } else {
                //verify the contract
                try {
                    $contractVerified = $this->verifyServiceContract($serviceObject, $method, $serviceArgumentsArray["internal"], $argumentArray);
                } catch (ServiceHandler_Exception $she) {
                    $this->logError(__METHOD__, $she);
                }
                //the contract was verified
                $finalArgumentArray["argumentArray"] = $contractVerified["argumentArray"];
                //call the method using the supplied values
                $response = call_user_func_array([$serviceObject, $method], $finalArgumentArray);

                $serviceObject->loggingDisabled = false;

                //set the standard return values
                $this->setReturnProperty(__FUNCTION__, "error", $response["error"]);
                $this->setReturnProperty(__FUNCTION__, "errorMessage", $response["errorMessage"]);
                $this->setReturnProperty(__FUNCTION__, "returnName", $response["returnName"]);
                $this->setReturnProperty(__FUNCTION__, "success", $response["success"]);

                $responseReturn = $response["return"];
            }
        } else {
            //we failed to find a service
            $this->logError(__METHOD__, 'Failed to find service!');
        }

        if ($responseReturn === false) {
            $this->logError(__METHOD__, 'Response was false!');

        }

        return $responseReturn;
    }

    private function verifyServiceContract ($serviceInstance, $methodRequested, $internal, $suppliedValues)
    {
        $this->logInfo(__METHOD__, "Called.");

        //prepare the return
        $resultArray = ["contract" => true, "argumentArray" => []];

        //get the list of arguments
        $argumentListReturn = call_user_func_array([$serviceInstance, "argumentList"], ["argumentArray" => ["method" => $methodRequested, "internal" => $internal]]);
        $argumentList = $argumentListReturn["return"];

        if ($argumentList !== false) {
            //The list was returned
            //lets go through the array, see if all the elements are present
            $argument_array = [];

            //lets check the required elements
            if (@array_key_exists("required", $argumentList)) {
                $requiredElements = $argumentList["required"];

                //ensure required elements are present and of the right type
                foreach ($requiredElements as $rElementName => $rElementValue) {
                    //Check if the element exists
                    if (@array_key_exists($rElementName, $suppliedValues)) {
                        //The element exists, lets check if the type is correct
                        if (!is_array($suppliedValues[$rElementName])) {
                            try {
                                $this->validateType($rElementValue, $suppliedValues[$rElementName]);
                            } catch (ValidateType_Exception $vte) {
                                throw new ServiceHandler_Exception("Required: $rElementName is of the wrong type. It should be of $rElementValue");
                            }

                            $argument_array[$rElementName] = $suppliedValues[$rElementName];
                        } else {
                            $argument_array[$rElementName] = $suppliedValues[$rElementName];
                        }
                    } else {
                        //the element is not present, stop and return an error
                        throw new ServiceHandler_Exception("Required: $rElementName is not present");
                    }
                }
            } else {
                throw new ServiceHandler_Exception('List of required elements is missing!');
            }

            if (@array_key_exists("optional", $argumentList)) {
                $optionalElements = $argumentList["optional"];

                //ensure optional elements are of the right type.
                foreach ($optionalElements as $oElementName => $oElementValue) {
                    //Check if the element exists
                    if (@array_key_exists($oElementName, $suppliedValues)) {
                        //The element exists, lets check if the type is correct
                        if (!is_array($suppliedValues["$oElementName"])) {
                            try {
                                $this->validateType($oElementValue, $suppliedValues["$oElementName"]);
                            } catch (ValidateType_Exception $vte) {
                                throw new ServiceHandler_Exception("Optional: $oElementName is of the wrong type. It should be of $oElementValue");
                            }

                            $argument_array["$oElementName"] = $suppliedValues["$oElementName"];
                        } else {
                            $argument_array["$oElementName"] = $suppliedValues["$oElementName"];
                        }
                    }
                }
            }

            if (count($argumentList["required"]) < 1) {
                $argument_array = $suppliedValues;
            }

            $resultArray["argumentArray"] = $argument_array;
        } else {
            //the list was not returned
            throw new ServiceHandler_Exception('This is not a valid call!');
        }

        $this->logInfo(__METHOD__, $resultArray);

        return $resultArray;
    }
}