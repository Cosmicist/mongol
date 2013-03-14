<?php namespace Flatline\Mongol\Facades;

use Illuminate\Support\Facades\Facade;

class Mongol extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'mongol'; }

}