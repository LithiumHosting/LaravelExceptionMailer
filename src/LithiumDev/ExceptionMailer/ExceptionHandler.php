<?php

namespace LithiumDev\ExceptionMailer;

use App;
use Exception;
use App\Exceptions\Handler;

class ExceptionHandler extends Handler
{

    /**
     *
     * @param Exception $e
     * @return type
     */
    public function report(Exception $e)
    {
        parent::report($e);

        $this->prevent_exception = config('laravel-exception-mailer.config.prevent_exception');
        $shouldReport            = true;

        foreach ($this->prevent_exception as $type) {
            if ($e instanceof $type) $shouldReport = false;
        }        
        if ($shouldReport) {
            $bugonemail = App::make('ExceptionMailer');
            $bugonemail->notifyException($e);
        }
    }
}