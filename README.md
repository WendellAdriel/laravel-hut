# Laravel Hut 🛖

> A collection of Helpers and Utilities for your Laravel application

## Installation

```
composer require wendelladriel/laravel-hut
```

Run your migrations since this package provides a migration for a table called `change_logs` that will
be explained below.

## Usage

This package provides a lot of classes that can be useful for a lot of projects created with
Laravel. I created this package because I use a lot of these Helpers and Utilities in the projects
I work on, so I hope it can be useful for someone out there as well.

### Exceptions

This package provides the class `WendellAdriel\LaravelHut\Exceptions\ApiHandler` that you can use
and/or extend. This class can be used if you're creating an API. It will render all the errors
in JSON format.

You can extend it in your `App\Exceptions\Handler`:

```php
// CHANGE THIS
<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler

// TO THIS

<?php

namespace App\Exceptions;

use Throwable;
use WendellAdriel\LaravelHut\Exceptions\ApiHandler;

class Handler extends ApiHandler
```

The `ApiHandler` can also send custom error messages using custom exceptions, you just need to implement
the `WendellAdriel\LaravelHut\Exceptions\AppExceptionInterface`. Example:

```php
<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use WendellAdriel\LaravelHut\Exceptions\AppExceptionInterface;

class AccessDeniedException extends Exception implements AppExceptionInterface
{
    public function __construct()
    {
        parent::__construct('Access Denied', Response::HTTP_FORBIDDEN);
    }
}
```

When throwing this exception in the code the `ApiHandler` will return a response with the given message
and HTTP code.

If you have your `APP_DEBUG` set to true, it will also send debug information when throwing an error.

### HTTP

#### API Controller

This package provides the class `WendellAdriel\LaravelHut\Http\ApiController` that you can extend.
Instead of extending from Laravel base controller, extend this Controller to get access to these
methods:

```php
/**
 * Builds and sends a simple success API response
 *
 * @param int $code
 * @return JsonResponse
 */
protected function apiSimpleSuccessResponse(int $code = Response::HTTP_CREATED): JsonResponse
{
    return response()->json(['success' => true], $code);
}

/**
 * Builds and sends a success API response
 *
 * @param mixed $data
 * @param int   $code
 * @param bool  $forceUTF8Convert
 * @return JsonResponse
 */
protected function apiSuccessResponse(
    $data,
    int $code = Response::HTTP_OK,
    bool $forceUTF8Convert = false
): JsonResponse {
    $formattedData = $forceUTF8Convert ? $this->convertToUTF8Recursively($data) : $data;
    return response()->json($formattedData, $code);
}

/**
 * Builds and sends an error API response
 *
 * @param string         $message
 * @param Throwable|null $exception
 * @param int            $code
 * @return JsonResponse
 */
protected function apiErrorResponse(
    string $message,
    Throwable $exception = null,
    int $code = Response::HTTP_INTERNAL_SERVER_ERROR
): JsonResponse {
    $response = ['message' => $message];

    if (!empty($exception) && config('app.debug')) {
        $response['debug'] = [
            'message' => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'trace'   => $exception->getTraceAsString()
        ];
    }

    return response()->json($response, $code);
}
```

#### Requests and DTOs

This package provides the class `WendellAdriel\LaravelHut\Http\BaseRequest` that you can extend.
Instead of extending from `Illuminate\Foundation\Http\FormRequest`. This class is used so you can
use the DTO pattern when sending data from your Controllers to other application layer like Services
or Repositories.

This package also provides the class `WendellAdriel\LaravelHut\Support\DTOs\BaseDTO` that you can extend
to create your own DTOs.

This package also provides a Request + DTO for tables that have basic features
like pagination, search and sort that you can use and/or extend.
Check the classes: `WendellAdriel\LaravelHut\Http\CommonTableRequest` and
`WendellAdriel\LaravelHut\Support\DTOs\CommonTableDTO`.

This package also provides a Request + DTO for tables with the basic features above plus
date range filters that you can use and/or extend.
Check the classes: WendellAdriel\LaravelHut\Http\DateRangeRequest` and
`WendellAdriel\LaravelHut\Support\DTOs\DateRangeDTO`.

### Models

This package provides the class `WendellAdriel\LaravelHut\Models\BaseModel` that you can extend.
Instead of extending from `Illuminate\Database\Eloquent\Model`. This can be used to track changes made
in the DB that will be logged in a table called change_logs. Check the class `WendellAdriel\LaravelHut\Models\ChangeLog`
and the trait `WendellAdriel\LaravelHut\Models\Traits\LogChanges`.

This package also provides the trait `WendellAdriel\LaravelHut\Models\Traits\HasUuid` that can be used to add
a UUID field in your models.

### Support

#### Formatter

This package provides the class `WendellAdriel\LaravelHut\Support\Formatter` that you can use to format data
like integers, floats, monetary values, dates and also provides some generic and useful constants to use in the
code to avoid using hardcoded strings.

#### Paginator

This package provides the class `WendellAdriel\LaravelHut\Support\Paginator` that you can use to manually
paginate Collections.

#### SlackClient

This package provides the class `WendellAdriel\LaravelHut\Support\SlackClient` that you can use to send notifications
to Slack. First you need to add the configuration below into your `config/services` file and set the ENV values
needed in your `.env` file:

```php
'slack' => [
    'bot' => [
        'name' => env('SLACK_NOTIFICATIONS_BOT_NAME', 'APP-BOT'),
        'icon' => env('SLACK_NOTIFICATIONS_BOT_ICON', ':robot_face:'),
    ],
    'channel' => env('SLACK_NOTIFICATIONS_CHANNEL', '#general'),
    'webhook' => env('SLACK_NOTIFICATIONS_WEBHOOK'),
],
```

After that you can already use the `sendNotification` method:

```php
/**
 * Notify the Slack channel, sending the given message and mentioning the given users
 *
 * @param string      $message
 * @param array       $users
 * @param string|null $target - IF CHANNEL: '#channel' IF USER: '@username'
 * @return void
 */
public function sendNotification(string $message, array $users = [], ?string $target = null): void
```

## Credits

- [Wendell Adriel](https://github.com/WendellAdriel)
- [All Contributors](../../contributors)

## Contributing

All PRs are welcome.

For major changes, please open an issue first describing what you want to add/change.
