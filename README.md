# Mailxpert Provider for OAuth 2.0 Client


![Packagist](https://img.shields.io/packagist/v/mailXpert/oauth2-mailxpert.svg?style=flat-square)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![CI](https://github.com/mailxpert/oauth2-mailxpert/actions/workflows/ci.yaml/badge.svg)](https://github.com/mailxpert/oauth2-mailxpert/actions/workflows/ci.yaml)
[![Total Downloads](https://img.shields.io/packagist/dt/mailxpert/oauth2-mailxpert.svg?style=flat-square)](https://packagist.org/packages/mailxpert/oauth2-mailxpert)

This package provides Mailxpert OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Requirements

* PHP 8.2, 8.3, 8.4 or 8.5
* [league/oauth2-client](https://github.com/thephpleague/oauth2-client) `^2.7`

## Installation

To install, use composer:

```
composer require mailxpert/oauth2-mailxpert
```

## Usage

Usage is the same as The League's OAuth client, using `\Mailxpert\OAuth2\Client\Provider\Mailxpert` as the provider.

### Authorization Code Flow

```php
$provider = new Mailxpert\OAuth2\Client\Provider\Mailxpert([
    'clientId'          => '{mailxpert-client-id}',
    'clientSecret'      => '{mailxpert-client-secret}',
    'redirectUri'       => 'https://example.com/redirect-url'
]);
```

## Testing

Run the test suite:

```bash
composer phpunit
```

Run the full check (php-cs-fixer + PHPUnit):

```bash
composer test
```

Or run PHPUnit against a specific PHP version with Docker:

```bash
docker run --rm --tty -v "$PWD":/app -w /app php:8.2-cli php vendor/bin/phpunit
docker run --rm --tty -v "$PWD":/app -w /app php:8.5-cli php vendor/bin/phpunit
```

## Misc
Check code style with php-cs-fixer:
```bash
composer phpcs
```

Apply the fixes:
```bash
composer phpcs:fix
```

composer update:
```bash
docker run --rm --interactive --tty --volume $PWD:/app composer update
```

## License

The MIT License (MIT). Please see [License File](https://github.com/mailxpert/oauth2-mailxpert/blob/main/LICENSE) for more information.