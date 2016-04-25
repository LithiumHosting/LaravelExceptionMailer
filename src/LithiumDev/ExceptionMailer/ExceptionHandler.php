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

        $reportableEnvironments = config('laravel-exception-mailer.config.notify_environment');
        if (is_array($reportableEnvironments) && in_array(app()->environment(), $reportableEnvironments))
        {
            $nonReportableExceptions  = config('laravel-exception-mailer.config.prevent_exception');
            $shouldReport = true;

            foreach ($nonReportableExceptions as $class)
            {
                if ($e instanceof $class)
                {
                    $shouldReport = false;
                }
            }

            if ($shouldReport)
            {
                $eMailer = App::make('ExceptionMailer');
                $eMailer->notifyException($e);
            }
        }
    }
}