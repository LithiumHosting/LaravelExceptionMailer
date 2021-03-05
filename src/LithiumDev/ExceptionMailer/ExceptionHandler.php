<?php
namespace LithiumDev\ExceptionMailer;


use Throwable;
use App\Exceptions\Handler;

class ExceptionHandler extends Handler {

    /**
     *
     * @param Throwable $e
     *
     * @return type
     */
    public function report(Throwable $e)
    {
        parent::report($e);

        $reportableEnvironments = config('laravel-exception-mailer.config.notify_environment');
        if (is_array($reportableEnvironments) && in_array(app()->environment(), $reportableEnvironments))
        {
            $nonReportableExceptions = config('laravel-exception-mailer.config.prevent_exception');
            $shouldReport            = true;

            foreach ($nonReportableExceptions as $class)
            {
                if ($e instanceof $class)
                {
                    $shouldReport = false;
                }
            }

            /*
             * If maintenance mode is enabled, a 500 error will be displayed instead of the error page
             * Skip the mailer in this one instance
             */
            if (app()->isDownForMaintenance())
            {
                $shouldReport = false;
            }

            if ($shouldReport)
            {
                $eMailer = app()->make('ExceptionMailer');
                $eMailer->notifyException($e);
            }
        }
    }
}