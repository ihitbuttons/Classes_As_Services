<?php

namespace Platform\Traits\Class_Type;

trait StaticClass_Trait
{
    private function __clone()
    {
        self::logInfo(__METHOD__, "Called", true);
    }

    private function __wakeup()
    {
        self::logInfo(__METHOD__, "Called", true);
    }
}