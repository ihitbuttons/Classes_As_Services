<?php

namespace Services\Example\Implementors;

use \Platform\Traits\ArgumentList_Trait;
use \Platform\Traits\LogError_Trait;
use \Platform\Traits\PublicResponse_Trait;
use \Services\Example\Interfaces\Example_Interface;

class Example_Sender implements Example_Interface
{
    use ArgumentList_Trait;
    use LogError_Trait;
    use PublicResponse_Trait;

    private $location;
    public function __construct ($argumentArray)
    {
        $this->loggingDisabled = $argumentArray["loggingDisabled"];
        $this->location = $argumentArray["location"];

        $this->logInfo(__METHOD__, "Called.");

        $this->extService["exampleExternalMethod"]["required"] = ["number" => "int", "string" => "string"];

        $this->intService["exampleInternalMethod"]["required"] = ["string" => "string", "number" => "decimal"];

        $this->extService["exampleOptionalParams"]["required"] = ["number" => "int"];
        $this->extService["exampleOptionalParams"]["optional"] = ["shortString" => "shortString"];
    }

    public function exampleExternalMethod ($argumentArray)
    {

    }

    public function exampleInternalMethod ($argumentArray)
    {

    }

    public function exampleOptionalParams ($argumentArray)
    {

    }
}