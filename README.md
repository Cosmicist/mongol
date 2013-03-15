laravel-mongol
==============

MongoDB library and Auth driver for Laravel 4.

It extends PHP's [MongoDB Native Driver](http://php.net/mongo).

Installation
------------

Require `flatline/mongol` in your project's `composer.json`:

```javascript
{
    "require": {
        "flatline/mongol": "0.1.*"
    }
}
```

Update or install your packages with `composer update` or `composer install` respectively.

Now you need to register MongolServiceProvider with Laravel.
Open up `app/config/app.php` and add the following to the `providers` key:

```php
'Flatline\Mongol\MongolServiceProvider'
```

You can also register the facade in the class aliases, look for the `aliases` key and add the following:

```php
'Mongol' => 'Flatline\Mongol\Facades\Mongol'
```

This way you can use `Mongol::connection()` instead of `$app['mongol']->connection()` if you want.

Configuration
-------------

In order to use your own database credentials you can extend the package configuration by
creating `app/config/packages/flatline/mongol/config.php`.

You can do this by running the following Artisan command.

```
$ php artisan config:publish flatline/mongol
```

Here's an example configuration using mongohq
```php
'default' => array(
    'host'     => 'alex.mongohq.com',
    'port'     => 10002,
    'username' => 'your_username',
    'password' => 'your_db_password',
    'database' => 'your_db_name',
),
```

You can also connect as admin
```php
'other_credentials' => array(
    'host'     => 'localhost',
    'username' => 'your_admin_username',
    'password' => 'your_admin_db_password',
    'database' => 'your_db_name',
    'admin'    => true,
),
```

You can create as many database credential groups as you need.

Auth Driver configuration
-------------------------

To use Mongol with the Auth library, just set _'mongol'_ as the driver in `app/config/auth.php`:

```php
'driver' => 'mongol'
```

### Password Reminders & Reset

If you also want to use the Reminder service you will need to replace the native `Illuminate\Auth\Reminders\ReminderServiceProvider` for `Flatline\Mongol\Auth\Reminders\ReminderServiceProvider` on `app/config/app.php` in the `providers` key.

Because of MongoDB's dynamic nature, there's no need for migrations, once you replace the service provider you're ready to go.

Usage
-----

You use it the same way you would use the native driver, but first you need to get the connection and database.

To get your default connection use:

```php
Mongol::connection();
```

To get other connection:

```php
Mongol::connection('group');
```

To get the database just use:

```php
Mongol::connection()->getDB();
```

And to get other db using the same credentials (you must be authenticated as admin), you can use:

```php
Mongol::connection()->getDB('other_db_name')
```
