# OneSignal notifications channel for Laravel 5.3+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/onesignal.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/onesignal)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/onesignal/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/onesignal)
[![StyleCI](https://styleci.io/repos/65379321/shield)](https://styleci.io/repos/65379321)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/9015691f-130d-4fca-8710-72a010abc684.svg?style=flat-square)](https://insight.sensiolabs.com/projects/9015691f-130d-4fca-8710-72a010abc684)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/onesignal.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/onesignal)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/onesignal/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/onesignal/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/onesignal.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/onesignal)

This package makes it easy to send [OneSignal notifications](https://documentation.onesignal.com/docs) with Laravel 5.3+.

## Contents

- [Installation](#installation)
	- [Setting up your OneSignal account](#setting-up-your-onesignal-account)
- [Usage](#usage)
	- [Available Message methods](#all-available-methods)
	- [Button usage](#button-usage)
	- [WebButton usage](#webbutton-usage)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

```bash
$ composer require laravel-notification-channels/onesignal
```

If you're installing the package in Laravel 5.4 or lower, you must import the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\OneSignal\OneSignalServiceProvider::class,
],
```

### Setting up your OneSignal account

Add your OneSignal App ID and REST API Key to your `config/services.php`:

```php
// config/services.php
...
'onesignal' => [
    'app_id' => env('ONESIGNAL_APP_ID'),
    'rest_api_key' => env('ONESIGNAL_REST_API_KEY')
],
...
```


## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [OneSignalChannel::class];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject("Your {$notifiable->service} account was approved!")
            ->setBody("Click here to see details.")
            ->setUrl('http://onesignal.com')
            ->webButton(
                OneSignalWebButton::create('link-1')
                    ->text('Click here')
                    ->icon('https://upload.wikimedia.org/wikipedia/commons/4/4f/Laravel_logo.png')
                    ->url('http://laravel.com')
            );
    }
}
```

In order to let your Notification know which OneSignal user(s) you are targeting, add the `routeNotificationForOneSignal` method to your Notifiable model.

You can either return a single player-id, or if you want to notify multiple player IDs just return an array containing all IDs.

```php
public function routeNotificationForOneSignal()
{
    return 'ONE_SIGNAL_PLAYER_ID';
}
```

If you want to send the notification based on the OneSignal "syncHashedEmail" feature just return an array with the index "email". **It isn't possible to use multiple E-Mails on one filter because of a limitation of the OneSignal API.**

```php
public function routeNotificationForOneSignal()
{
    return ['email' => 'example@example.com'];
}
```

If you want to send the notification based on the OneSignal "Tags" feature just return an array with the index "tags".

```php
public function routeNotificationForOneSignal()
{
    return ['tags' => ['key' => 'device_uuid', 'relation' => '=', 'value' => '1234567890-abcdefgh-1234567']];
}
```

If you want to send the notification based on an external user id you set using the `setExternalUserId` feature. This makes it really easy to target users based on their Laravel User Ids.

**Note:** `include_external_user_ids` must be an array of non empty strings

```php
public function routeNotificationForOneSignal()
{
    return ['include_external_user_ids' => $this->id];
}
```

### All available methods

- `setSubject('')`: Accepts a string value for the title.
- `setBody('')`: Accepts a string value for the notification body.
- `setIcon('')`: Accepts an url for the icon.
- `setUrl('')`: Accepts an url for the notification click event.
- `webButton(OneSignalWebButton $button)`: Allows you to add action buttons to the notification (Chrome 48+ (web push) only).
- `button(OneSignalButton $button)`: Allows you to add buttons to the notification (Supported by iOS 8.0 and Android 4.1+ devices. Icon only works for Android).
- `setData($key, $value)`: Allows you to set additional data for the message payload. For more information check the [OneSignal documentation](https://documentation.onesignal.com/reference).
- `setParameter($key, $value)`: Allows you to set additional parameters for the message payload that are available for the REST API. For more information check the [OneSignal documentation](https://documentation.onesignal.com/reference).
- `setImageAttachments($imageUrl)`: Allows you to set one Image to all possible Attachments [OneSignal Attachment documentation](https://documentation.onesignal.com/reference#section-attachments).

### Button usage

```php
OneSignalMessage::create()
    ->button(
        OneSignalButton::create('id')
            ->text('button text')
            ->icon('button icon')
    );
```

### WebButton usage

```php
OneSignalMessage::create()
    ->webButton(
        OneSignalWebButton::create('id')
            ->text('button text')
            ->icon('button icon')
            ->url('button url')
    );
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ composer test
```

## Security

If you discover any security related issues, please email m.pociot@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Marcel Pociot](https://github.com/mpociot)
- [Freek Van der Herten](https://github.com/freekmurze)
- [Lukas Kämmerling](https://github.com/LKDevelopment)
- [David Llop](https://github.com/Lloople)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
