<?php

namespace Platform\Traits;

trait ArgumentList_Trait
{
    private $extService = [];
    private $intService = [];

    public function argumentList ($argumentArray)
    {
        $this->logInfo(__METHOD__, "Called.");
        $this->clearReturnArray(__FUNCTION__);

        $method = $argumentArray["method"];

        if ($method == "argumentList") {
            $returnValue = $this->extService;
        } else {
            if ($argumentArray["internal"] === false) {
                $arrayToCheck = $this->extService;
            } else {
                $arrayToCheck = $this->intService;
            }

            if (@array_key_exists($method, $arrayToCheck)) {
                $returnValue = $arrayToCheck[$method];
            } else {
                $this->logError(__METHOD__, 'Method Not Callable');
                $returnValue = false;
            }
        }

        $this->setReturnProperty(__FUNCTION__, "return", $returnValue);
        return $this->generateReturn(__FUNCTION__);
    }
}