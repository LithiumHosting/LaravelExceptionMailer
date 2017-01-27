<?php
namespace LithiumDev\ExceptionMailer\Facades;


use Illuminate\Support\Facades\Facade;

class ExceptionMailerFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ExceptionMailer';
    }

}
