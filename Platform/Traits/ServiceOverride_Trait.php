<?php

namespace Platform\Traits;

trait ServiceOverride_Trait
{
    private function defaultServiceOverride ($argumentArray)
    {
        if (@array_key_exists("serviceOverrideArray", $argumentArray)) {
            foreach ($argumentArray["serviceOverrideArray"] as $service => $override) {
                $this->serviceOverrideArray[$service] = $override;
            }
        }
    }
}