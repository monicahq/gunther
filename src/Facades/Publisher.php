<?php

namespace Gunther\Facades;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class Publisher extends IlluminateFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'gunther.publisher';
    }
}
