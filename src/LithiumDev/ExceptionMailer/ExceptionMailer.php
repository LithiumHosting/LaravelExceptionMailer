<?php
namespace LithiumDev\ExceptionMailer;

/**
 * Description of ExceptionMailer
 *
 * @author Troy Siedsma <tsiedsma@lithiumhosting.com>
 */
class ExceptionMailer {

    public $env    = '';
    public $config = array();

    public function __construct($config = array())
    {
        $this->config = $config;
    }

    public function setEnvironment($env)
    {
        $this->env = $env;
    }

    public function notifyException($exception)
    {
        if (! empty($this->env))
        {
            $request                   = array();
            $request['fullUrl']        = (! app()->runningInConsole()) ? \Request::fullUrl() : null;
            $request['input_get']      = (! app()->runningInConsole()) ? $_GET : [];
            $request['input_post']     = (! app()->runningInConsole()) ? $_POST : [];
            $request['input_old']      = [];
            $request['session']        = [];
            $request['cookie']         = (! app()->runningInConsole()) ? \Request::cookie() : [];
            $request['file']           = (! app()->runningInConsole()) ? \Request::file() : [];
            $request['header']         = (! app()->runningInConsole()) ? \Request::header() : [];
            $request['server']         = (! app()->runningInConsole()) ? \Request::server() : [];
            $request['json']           = (! app()->runningInConsole()) ? \Request::json() : [];
            $request['request_format'] = (! app()->runningInConsole()) ? \Request::format() : null;
            $request['error']          = $exception->getTraceAsString();
            $request['subject_line']   = $exception->getMessage();
            $request['class_name']     = get_class($exception);
            if (! in_array($request['class_name'], $this->config['prevent_exception']))
            {
                \Mail::send("{$this->config['email_template']}", $request, function ($message) use ($request)
                {
                    foreach ($this->config['notify_emails'] as $recipient)
                    {
                        $message->to($recipient['address'], $recipient['name']);
                    }

                    $subject = (! app()->runningInConsole()) ? "URL: " . $request['fullUrl'] : " CLI Command Failure";
                    $message->subject("{$this->config['subject']} - " . $subject);
                });
            }
        }

        return $exception;
    }
}
