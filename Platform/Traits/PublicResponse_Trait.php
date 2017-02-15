<?php

namespace Platform\Traits;

trait PublicResponse_Trait
{
    private $returnArray = [];

    private function clearReturnArray ($method)
    {
        $this->returnArray[$method] = ["error" => false, "errorMessage" => false, "return" => false, "success" => SUCCESS_TRUE, "returnName" => false];
    }

    private function setReturnProperty ($method, $property, $value)
    {
        $this->returnArray[$method]["$property"] = $value;

    }

    private function generateReturn ($method)
    {
        if ($this->returnArray[$method]["error"] !== false) {
            $this->setReturnProperty($method, "success", SUCCESS_FALSE);
        }

        return $this->returnArray[$method];
    }
}