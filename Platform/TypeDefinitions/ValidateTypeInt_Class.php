<?php

namespace Platform\TypeDefinitions;

use \Platform\Traits\Class_Type\StaticClass_Trait;
use \Platform\Traits\Static_Traits\StaticLogError_Trait;
use \Platform\Traits\Static_Traits\StaticPublicResponse_Trait;
use \Platform\Exceptions\ValidateType_Exception;
use \Platform\Interfaces\ValidateType_Interface;

class ValidateTypeInt_Class implements ValidateType_Interface
{
    use StaticClass_Trait;
    use StaticLogError_Trait;
    use StaticPublicResponse_Trait;

    public static function validateType ($argumentArray)
    {
        self::logInfo(__METHOD__, "Called");
        self::clearReturnArray(__FUNCTION__);

        $originalValue = $argumentArray["value"];
        $maxLength = (int) $argumentArray["maxLength"];

        if (strlen($originalValue) > $maxLength) {
            throw new ValidateType_Exception("Value is too long.");
        }

        $testValue = filter_var($originalValue, FILTER_VALIDATE_INT);
        if ($testValue === false) {
            while( (substr($originalValue, 0, 1) == 0) && (strlen($originalValue) > 1) ) {
                $originalValue = substr($originalValue, 1);
            }

            $testValue = filter_var($originalValue, FILTER_VALIDATE_INT);
            if ($testValue === false) {
                throw new ValidateType_Exception("Value is not a valid integer.");
            }

        }

        self::setReturnProperty(__FUNCTION__, "return", true);
        return self::generateReturn(__FUNCTION__);
    }
}