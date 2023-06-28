![Auth0 Laravel SDK](https://cdn.auth0.com/website/sdks/banners/laravel-auth0-banner.png)

<div aria-label="Laravel SDK for Auth0 Authentication and Management APIs">
    <p aria-hidden="true" align="right">
        <a href="https://github.com/auth0/laravel-auth0/actions"><img src="https://github.com/auth0/laravel-auth0/actions/workflows/php_pest.yml/badge.svg" alt="Build Status"></a>
        <a href="https://codecov.io/gh/auth0/laravel-auth0"><img src="https://codecov.io/gh/auth0/laravel-auth0/branch/main/graph/badge.svg?token=vEwn6TPADf" alt="Code Coverage"></a>
        <a href="https://packagist.org/packages/auth0/laravel-auth0"><img src="https://img.shields.io/packagist/dt/auth0/login" alt="Total Downloads"></a>
        <a href="https://packagist.org/packages/auth0/login"><img src="https://img.shields.io/packagist/l/auth0/login" alt="License"></a>
    </p>
</div>

- [Requirements](#requirements)
- [Getting Started](#getting-started)
  - [1. Install the SDK](#1-install-the-sdk)
  - [2. Install the CLI](#2-install-the-cli)
  - [3. Configure the SDK](#3-configure-the-sdk)
  - [4. Run the Application](#4-run-the-application)
- [Documentation](#documentation)
- [QuickStarts](#quickstarts)
- [Contributing](#contributing)
- [Code of Conduct](#code-of-conduct)
- [Security](#security)
- [License](#license)

## Requirements

Your application must use a [supported Laravel version](https://laravelversions.com/en), and your host environment must be running a [supported PHP version](https://www.php.net/supported-versions.php). Please review [our support policy](./docs/Support.md) for more information.

| SDK  | Laravel | PHP  | Supported Until |
| ---- | ------- | ---- | --------------- |
| 7.5+ | 10      | 8.2+ | Feb 2025        |
|      |         | 8.1+ | Nov 2024        |
| 7.0+ | 9       | 8.2+ | Feb 2024        |
|      |         | 8.1+ | Feb 2024        |
|      |         | 8.0+ | Nov 2023        |

You will also need [Composer](https://getcomposer.org/) and an [Auth0 account](https://auth0.com/signup).

## Getting Started

The following is our recommended approach to getting started with the SDK. Alternatives are available in [our expanded installation guide](./docs/Installation.md).

### 1. Install the SDK

- For **new applications**, we offer a quickstart template — a version of the default Laravel 9 starter project pre-configured for use with the Auth0 SDK:

    ```shell
    composer create-project auth0-samples/laravel auth0-laravel-app && cd auth0-laravel-app
    ```

- For **existing applications**, you can install the SDK using Composer:

    ```shell
    composer require auth0/login:^7.9 --update-with-all-dependencies
    ```

    In this case, you will also need to generate an SDK configuration file for your application:

    ```shell
    php artisan vendor:publish --tag auth0
    ```

</details>

### 2. Install the CLI

1. Install the [Auth0 CLI](https://github.com/auth0/auth0-cli) to manage your account from the command line:

    ```shell
    curl -sSfL https://raw.githubusercontent.com/auth0/auth0-cli/main/install.sh | sh -s -- -b .
    sudo mv ./auth0 /usr/local/bin
    ```

    <details>
    <summary>Using <a href="https://brew.sh/">Homebrew</a> (macOS)</summary>
     

    ```shell
    brew tap auth0/auth0-cli && brew install auth0
    ```

    </details>

    <details>
    <summary>Using <a href="https://scoop.sh/">Scoop</a> (Windows)</summary>
     

    ```cmd
    scoop bucket add auth0 https://github.com/auth0/scoop-auth0-cli.git
    scoop install auth0
    ```

    </details>

2. Authenticate the CLI with your Auth0 account. Choose "as a user," and follow the prompts.

    ```shell
    auth0 login
    ```

### 3. Configure the SDK

1. Register a new application with Auth0:

    ```shell
    auth0 apps create \
      --name "My Laravel Application" \
      --type "regular" \
      --auth-method "post" \
      --callbacks "http://localhost:8000/callback" \
      --logout-urls "http://localhost:8000" \
      --reveal-secrets \
      --no-input \
      --json > .auth0.app.json
    ```

2. Register a new API with Auth0:

    ```shell
    auth0 apis create \
      --name "My Laravel Application API" \
      --identifier "https://github.com/auth0/laravel-auth0" \
      --offline-access \
      --no-input \
      --json > .auth0.api.json
    ```

3. Add the new files to `.gitignore`:

    ```bash
    echo ".auth0.*.json" >> .gitignore
    ```

    <details>
    <summary>Using Windows PowerShell</summary>
     

    ```powershell
    Add-Content .gitignore "`n.auth0.*.json"
    ```

    </details>

    <details>
    <summary>Using Windows Command Prompt</summary>
     

    ```cmd
    echo .auth0.*.json >> .gitignore
    ```

    </details>

### 4. Run the Application

You can now run the application using the built-in PHP web server:

```shell
php artisan serve
```

Your application should now be accessible from your browser at [http://localhost:8000](http://localhost:8000).

- **Testing Authentication**  
  You can log in or out by visiting the [`/login`](http://localhost:8000/login) or [`/logout`](http://localhost:8000/logout) routes, respectively.

- **Testing API Authorization**  
  To test `/api` routes, you can generate a test token using the CLI. In the following example, substitute `%IDENTIFIER%` with the identifier of the API you created in step 3 above.

    ```shell
    auth0 test token \
      --audience %IDENTIFIER% \
      --scopes "read:messages"
    ```

  You can now make requests to your application's API routes by providing the test token as a header in the request. Substitute `%TOKEN%` with the token returned by the previous step.

    ```shell
    curl --request GET \
      --url http://localhost:8000/api/example \
      --header 'Accept: application/json' \
      --header 'Authorization: Bearer %TOKEN%'
    ```

    <details>
    <summary>Using Windows PowerShell</summary>
     

    ```powershell
    Invoke-WebRequest http://localhost:8000/api/example `
      -Headers @{'Accept' = 'application/json'; 'Authorization' = 'Bearer %TOKEN%'}
    ```

    </details>

- **Deploying to Production**  
For guidance on moving your application to production, see [our deployment guide](./docs/Deployment.md).

## Integration Examples

<details>
<summary><b>User Authentication</b></summary>
 

The SDK automatically registers all the necessary authentication services within the `web` middleware group for your application. Once you have [configured the SDK](./docs/Management.md#api-application-authorization) your users will be able to authenticate with your application using Auth0.

The SDK automatically registers the following routes to facilitate authentication:

| Route       | Purpose                            |
| ----------- | ---------------------------------- |
| `/login`    | Initiates the authentication flow. |
| `/logout`   | Logs the user out.                 |
| `/callback` | Handles the callback from Auth0.   |

> **Note**  
> See [the configuration guide](./docs/Configuration.md#authentication-routes) for information on customizing this behavior.

---

</details>

<details>
<summary><b>Route Authorization (Access Control)</b></summary>
 

The SDK automatically registers its authentication and authorization guards within the `web` and `api` middleware groups for your Laravel application, respectively.

For `web` routes, you can use Laravel's `auth` middleware to require that a user be authenticated to access a route:

```php
Route::get('/private', function () {
  return response('Welcome! You are logged in.');
})->middleware('auth');
```

For `api` routes, you can use Laravel's `auth` middleware to require that a request be authenticated with a valid bearer token to access a route:

```php
Route::get('/api/private', function () {
  return response()->json(['message' => 'Hello! You included a valid token with your request.']);
})->middleware('auth');
```

In addition to requiring that a user be authenticated, you can also require that the user have specific permissions to access a route, using Laravel's `can` middleware:

```php
Route::get('/scope', function () {
    return response('You have the `read:messages` permission, and can therefore access this resource.');
})->middleware('auth')->can('read:messages');
```

> **Note**  
> Permissions require that [RBAC](https://auth0.com/docs/manage-users/access-control/rbac) be enabled within [your API settings](https://manage.auth0.com/#/apis).

---

</details>

<details>
<summary><b>Users and Tokens</b></summary>
 

Laravel's `Auth` Facade (or the `auth()` global helper) can be used to retrieve information about the authenticated user, or the access token used to authorize the request.

For example, for routes using the `web` middleware group in `routes/web.php`:

```php
Route::get('/', function () {
  if (! auth()->check()) {
    return response('You are not logged in.');
  }

  $user = auth()->user();
  $name = $user->name ?? 'User';
  $email = $user->email ?? '';

  return response("Hello {$name}! Your email address is {$email}.");
});
```

Alternatively, for routes using the `api` middleware group in `routes/api.php`:

```php
Route::get('/', function () {
  if (! auth()->check()) {
    return response()->json([
      'message' => 'You did not provide a token.',
    ]);
  }

  return response()->json([
    'message' => 'Your token is valid; you are authorized.',
    'id' => auth()->id(),
    'token' => auth()?->user()?->getAttributes(),
  ]);
});
```

---

</details>

<details>
<summary><b>Management API Calls</b></summary>
 

Once you've [authorized your application to make Management API calls](./docs/Management.md#api-application-authorization), you'll be able to engage nearly any of the [Auth0 Management API endpoints](https://auth0.com/docs/api/management/v2) using the SDK.

Each endpoint has its own Management API class, all of which can be accessed through the Facade's `management()` method. API responses are returned as [PSR-7 messages](https://www.php-fig.org/psr/psr-7/), and can be converted into native arrays using the SDK's `json()` method.

For example, to update a user's metadata, you can call the `management()->users()->update()` method:

```php
use Auth0\Laravel\Facade\Auth0;

Route::get('/colors', function () {
  $colors = ['red', 'blue', 'green', 'black', 'white', 'yellow', 'purple', 'orange', 'pink', 'brown'];

  // Update the authenticated user with a randomly assigned favorite color.
  Auth0::management()->users()->update(
    id: auth()->id(),
    body: [
      'user_metadata' => [
        'color' => $colors[random_int(0, count($colors) - 1)]
      ]
    ]
  );

  // Retrieve the user's updated profile.
  $profile = Auth0::management()->users()->get(auth()->id());

  // Convert the PSR-7 response into a native array.
  $profile = Auth0::json($profile);

  // Extract some values from the user's profile.
  $color = $profile['user_metadata']['color'] ?? 'unknown';
  $name = auth()->user()->name;

  return response("Hello {$name}! Your favorite color is {$color}.");
})->middleware('auth');
```

All the SDK's Management API methods are [documented here](./docs/Management.md).

</details>

## Documentation

- [Installation](./docs/Installation.md) — Installing the SDK and generating configuration files.
- [Configuration](./docs/Configuration.md) — Configuring the SDK using JSON files or environment variables.
- [Management](./docs/Management.md) — Using the SDK to call the [Management API](https://auth0.com/docs/api/management/v2).
- [Users](./docs/Users.md) — Extending the SDK to support persistent storage and [Eloquent](https://laravel.com/docs/eloquent).
- [Events](./docs/Events.md) — Hooking into SDK [events](https://laravel.com/docs/events) to respond to specific actions.
- [Octane](./docs/Octane.md) — We do not support using the SDK with [Octane](https://laravel.com/docs/octane) at this time.

You may also find the following resources helpful:

- [Auth0 Documentation Hub](https://www.auth0.com/docs)
- [Auth0 Management API Explorer](https://auth0.com/docs/api/management/v2)
- [Auth0 Authentication API Explorer](https://auth0.com/docs/api/authentication)

Contributions to improve our documentation [are welcomed](https://github.com/auth0/laravel-auth0/pull).

## QuickStarts

- [Session-based Authentication](https://auth0.com/docs/quickstart/webapp/laravel) ([GitHub](https://github.com/auth0-samples/laravel))
- [Token-based Authorization](https://auth0.com/docs/quickstart/backend/laravel) ([GitHub](https://github.com/auth0-samples/laravel))

## Community

The [Auth0 Community](https://community.auth0.com) is where you can get support, ask questions, and share your projects.

## Contributing

We appreciate feedback and contributions to this library. Before you get started, please review Auth0's [General Contribution guidelines](https://github.com/auth0/open-source-template/blob/master/GENERAL-CONTRIBUTING.md).

The [Contribution Guide](./.github/CONTRIBUTING.md) contains information about our development process and expectations, insight into how to propose bug fixes and improvements, and instructions on how to build and test changes to the library.

To provide feedback or report a bug, [please raise an issue](https://github.com/auth0/laravel-auth0/issues).

## Code of Conduct

Participants are expected to adhere to Auth0's [Code of Conduct](https://github.com/auth0/open-source-template/blob/master/CODE-OF-CONDUCT.md) when interacting with this project.

## Security

If you believe you have found a security vulnerability, we encourage you to responsibly disclose this and not open a public issue. We will investigate all reports. The [Responsible Disclosure Program](https://auth0.com/whitehat) details the procedure for disclosing security issues.

## License

This library is open-sourced software licensed under the [MIT license](./LICENSE.md).

---

<p align="center">
  <picture>
    <source media="(prefers-color-scheme: light)" srcset="https://cdn.auth0.com/website/sdks/logos/auth0_light_mode.png" width="150">
    <source media="(prefers-color-scheme: dark)" srcset="https://cdn.auth0.com/website/sdks/logos/auth0_dark_mode.png" width="150">
    <img alt="Auth0 Logo" src="https://cdn.auth0.com/website/sdks/logos/auth0_light_mode.png" width="150">
  </picture>
</p>

<p align="center">Auth0 is an easy-to-implement, adaptable authentication and authorization platform.<br />To learn more, check out <a href="https://auth0.com/why-auth0">"Why Auth0?"</a></p>
