<?php

namespace Services\Override\Implementors;

use \Platform\Traits\ArgumentList_Trait;
use \Platform\Traits\LogError_Trait;
use \Platform\Traits\PublicResponse_Trait;
use \Services\Override\Interfaces\Override_Interface;

class Override_Class implements Override_Interface
{
    use ArgumentList_Trait;
    use LogError_Trait;
    use PublicResponse_Trait;

    public function __construct ($argumentArray)
    {
        $this->loggingDisabled = $argumentArray["loggingDisabled"];
        $this->logInfo(__METHOD__, "Called.");

        $this->intService["exampleDependencyMethod"]["required"] = ["number" => "int"];
    }

    public function exampleDependencyMethod ($argumentArray)
    {
        $this->logInfo(__METHOD__, "Called.");
        $this->clearReturnArray(__FUNCTION__);

        //get arguments
        $number = $argumentArray["number"];
        unset($argumentArray);

        $returnValue = ["numberAsString" => 'overRidden'];

        $this->setReturnProperty(__FUNCTION__, "return", $returnValue);
        return $this->generateReturn(__FUNCTION__);
    }
}