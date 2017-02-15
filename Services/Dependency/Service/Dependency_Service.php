<?php

namespace Services\Dependency\Service;

use \Platform\Traits\Class_Type\Singleton_Trait;
use \Platform\Traits\LogError_Trait;

class Dependency_Service
{
    use Singleton_Trait;
    use LogError_Trait;

    private $service = false;

    protected function __construct($argumentArray)
    {
        $this->loggingDisabled = $argumentArray["loggingDisabled"];
        $this->logInfo(__METHOD__, "Called.");
    }

    public function returnServiceObject ($argumentArray)
    {
        $this->logInfo(__METHOD__, "Called.");

        if ($this->service !== false) {
            //it does, return it
            return $this->service;
        } else {
            //it does not, create it
            $this->service = new \Services\Dependency\Implementors\Dependency_Class($argumentArray);
            return $this->service;
        }
    }
}
