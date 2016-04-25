<?php

return [
    'subject'       => 'Laravel Exception',
    'notify_emails'      => [
        [
            'address' => 'your@email.address',
            'name'    => 'Your Name Here',
        ],
    ],
    'email_template'     => "laravel-exception-mailer::email.exception",
    'notify_environment' => ['local'],
    'prevent_exception'  => ['Symfony\Component\HttpKernel\Exception\NotFoundHttpException'],
];
