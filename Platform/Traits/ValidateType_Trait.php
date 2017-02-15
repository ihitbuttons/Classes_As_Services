<?php

namespace Platform\Traits;

use \Platform\Exceptions\ValidateType_Exception;

trait ValidateType_Trait
{
    private function validateType ($suppliedType, $value)
    {
        $this->logInfo(__METHOD__, "Called.");

        $commaPosition = strpos($suppliedType, ",");

        if ($commaPosition !== false) {
            $type = substr($suppliedType, 0, $commaPosition);
            $maxLength = substr($suppliedType, $commaPosition + 1);
        } else {
            $type = $suppliedType;
            $maxLength = false;
        }

        unset($suppliedType);
        $type = ucfirst($type);

        $className = '\\Platform\\TypeDefinitions\\ValidateType' . $type . '_Class';
        if (class_exists($className)) {
            if ($maxLength === false) {
                if (defined(strtoupper ($type) . '_DEFAULT_MAX_LENGTH')) {
                    $maxLength = constant(strtoupper ($type) . '_DEFAULT_MAX_LENGTH');
                } else {
                    $maxLength = MAX_LENGTH_DEFINE;
                }
            }
            $returnValueArray = $className::validateType(["value" => $value, "maxLength" => $maxLength]);
            $returnValue = $returnValueArray["return"];
        } else {
            //I do not check the type if it does nto exist
            throw new ValidateType_Exception('Type does not exist!');
        }

        return $returnValue;
    }
}