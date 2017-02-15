<?php

namespace Services\Example\Implementors;

use \Services\Example\Interfaces\Example_Interface;
use \Platform\Traits\ArgumentList_Trait;
use \Platform\Traits\LogError_Trait;
use \Platform\Traits\PublicResponse_Trait;
use \Platform\Traits\ServiceHandler_Trait;
use \Platform\Traits\ServiceOverride_Trait;


class Example_Alt_Class implements Example_Interface
{
    use ArgumentList_Trait;
    use LogError_Trait;
    use PublicResponse_Trait;
    use ServiceHandler_Trait;
    use ServiceOverride_Trait;

    public function __construct ($argumentArray)
    {
        $this->loggingDisabled = $argumentArray["loggingDisabled"];
        $this->logInfo(__METHOD__, "Called.");
        $this->defaultServiceOverride($argumentArray);

        $this->extService["exampleExternalMethod"]["required"] = ["number" => "int", "string" => "string"];

        $this->extService["exampleInternalMethod"]["required"] = ["string" => "string", "number" => "decimal"];

        $this->extService["exampleOptionalParams"]["required"] = ["number" => "int"];
        $this->extService["exampleOptionalParams"]["optional"] = ["shortString" => "shortString"];
    }

    public function exampleExternalMethod ($argumentArray)
    {
        $this->logInfo(__METHOD__, "Called.");
        $this->clearReturnArray(__FUNCTION__);

        //get arguments
        $number = $argumentArray["number"];
        $string = $argumentArray["string"];
        unset($argumentArray);

        $serviceReturn = $this->callService("Dependency", ["internal" => true], "exampleDependencyMethod", ["number" => $number]);
        $numberAsString = $serviceReturn["numberAsString"];

        $returnValue = ["number" => $numberAsString, "string" => $string];

        $this->setReturnProperty(__FUNCTION__, "return", $returnValue);
        return $this->generateReturn(__FUNCTION__);
    }

    public function exampleInternalMethod ($argumentArray)
    {
        $this->logInfo(__METHOD__, "Called.");
        $this->clearReturnArray(__FUNCTION__);

        $number = $argumentArray["number"];
        $string = $argumentArray["string"];
        unset($argumentArray);

        $returnValue = ["number" => $number, "string" => $string];

        $this->setReturnProperty(__FUNCTION__, "return", $returnValue);
        return $this->generateReturn(__FUNCTION__);
    }

    public function exampleOptionalParams ($argumentArray)
    {
        $this->logInfo(__METHOD__, "Called.");
        $this->clearReturnArray(__FUNCTION__);

        $number = $argumentArray["number"];
        if (@array_key_exists("shortString", $argumentArray)) {
            $shortString = $argumentArray["shortString"];
        } else {
            $shortString = "None Provided!";
        }
        unset($argumentArray);

        $returnValue = ["number" => $number, "shortString" => $shortString];

        $this->setReturnProperty(__FUNCTION__, "return", $returnValue);
        return $this->generateReturn(__FUNCTION__);
    }

}