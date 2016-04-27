<?php

namespace LithiumDev\ExceptionMailer;


use Illuminate\Support\Facades\Request;

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
            $request['fullUrl']        = (! \App::runningInConsole()) ? Request::fullUrl() : null;
            $request['input_get']      = (! \App::runningInConsole()) ? $_GET : [];
            $request['input_post']     = (! \App::runningInConsole()) ? $_POST : [];
            $request['input_old']      = (! \App::runningInConsole()) ? Request::old() : [];
            $request['session']        = (! \App::runningInConsole()) ? \Session::all() : [];
            $request['cookie']         = (! \App::runningInConsole()) ? Request::cookie() : [];
            $request['file']           = (! \App::runningInConsole()) ? Request::file() : [];
            $request['header']         = (! \App::runningInConsole()) ? Request::header() : [];
            $request['server']         = (! \App::runningInConsole()) ? Request::server() : [];
            $request['json']           = (! \App::runningInConsole()) ? Request::json() : [];
            $request['request_format'] = (! \App::runningInConsole()) ? Request::format() : null;
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

                    $subject = (! \App::runningInConsole()) ? "URL: " . $request['fullUrl'] : " CLI Command Failure";
                    $message->subject("{$this->config['subject']} - " . $subject);
                });
            }
        }

        return $exception;
    }
}
