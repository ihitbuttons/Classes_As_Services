<?php

namespace Platform\Traits\Class_Type;

trait Singleton_Trait
{
    public static function getInstance ($argumentArray)
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new static($argumentArray);
        }

        return $instance;
    }

    private function __clone ()
    {
        $this->logInfo(__METHOD__, "Called.");
    }

    private function __wakeup ()
    {
        $this->logInfo(__METHOD__, "Called.");
    }
}