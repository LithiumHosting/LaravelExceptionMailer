![](https://lithiumhosting.com/images/logo_new_black.png)

## Laravel Exception Mailer
**from Lithium Hosting**  
We're always open to pull requests, feel free to make this your own or help us make it better.

### Copyright
(c) Lithium Hosting, llc

### License
This library is licensed under the MIT license; you can find a full copy of the license itself in the file /LICENSE  

### Requirements
* PHP 5.5.9 or newer
* Laravel 5.2.x

### Description
This package enables you to receive emails when Laravel throws an Exception. 
This enables you to react to issues in your application as they happen instead of waiting for user feedback.

## Installation

Begin by installing this package through Composer.
Edit your project's `composer.json` file to require `lithiumdev/laravel-exception-mailer`.

    "require": {
		"laravel/framework": "5.*",
		"lithiumdev/laravel-exception-mailer": "~1.0"
	}

Next, update Composer from the Terminal:

    composer update

Once this operation completes, the next step is to add the service provider. Open `app/config/app.php`, and add a new item to the providers array.

* Add `LithiumDev\ExceptionMailer\ExceptionMailerServiceProvider::class,` to your providers array in `app/config/app.php` 
* Run `php artisan vendor:publish --provider="LithiumDev\ExceptionMailer\ExceptionMailerServiceProvider"` to publish required resources. 

Now change Config file: `config/laravel-exception-mailer/config.php`
```php
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
```
Be sure to set your email address and your name.  You can add multiple arrays of users to receive notifications.

Manually Call

    ExceptionMailer::notifyException($exception)

Manually set environment

    ExceptionMailer::setEnvironment("local")

