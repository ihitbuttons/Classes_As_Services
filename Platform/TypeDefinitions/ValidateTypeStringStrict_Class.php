<?php

namespace Platform\TypeDefinitions;

use \Platform\Traits\Class_Type\StaticClass_Trait;
use \Platform\Traits\Static_Traits\StaticLogError_Trait;
use \Platform\Traits\Static_Traits\StaticPublicResponse_Trait;
use \Platform\Exceptions\ValidateType_Exception;
use \Platform\Interfaces\ValidateType_Interface;

class ValidateTypeStringStrict_Class implements ValidateType_Interface
{
    use StaticClass_Trait;
    use StaticLogError_Trait;
    use StaticPublicResponse_Trait;

    public static function validateType ($argumentArray)
    {
        self::logInfo(__METHOD__, "Called");
        self::clearReturnArray(__FUNCTION__);

        $originalValue = (string) $argumentArray["value"];
        $maxLength = (int) $argumentArray["maxLength"];

        if (strlen($originalValue) > $maxLength) {
            throw new ValidateType_Exception("Value is too long.");
        }

        if ($originalValue === false) {
            throw new ValidateType_Exception("Wrong Type.");
        }

        $testForFloat = filter_var($originalValue, FILTER_VALIDATE_FLOAT);
        if ($testForFloat !== false) {
            throw new ValidateType_Exception("Wrong Type.");
        }

        $testForInt = filter_var($originalValue, FILTER_VALIDATE_INT);
        if ($testForInt !== false) {
            throw new ValidateType_Exception("Wrong Type.");
        }

        if (is_array($originalValue)) {
            throw new ValidateType_Exception("Wrong Type.");
        }

        self::setReturnProperty(__FUNCTION__, "return", true);
        return self::generateReturn(__FUNCTION__);
    }
}