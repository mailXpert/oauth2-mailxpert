# Mailxpert Provider for OAuth 2.0 Client

[![Latest Version](https://img.shields.io/github/release/mailxpert/oauth2-mailxpert.svg?style=flat-square)](https://github.com/mailxpert/oauth2-mailxpert/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/mailXpert/oauth2-mailxpert/master.svg?style=flat-square)](https://travis-ci.org/mailXpert/oauth2-mailxpert)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/mailxpert/oauth2-mailxpert.svg?style=flat-square)](https://scrutinizer-ci.com/g/mailxpert/oauth2-mailxpert/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/mailxpert/oauth2-mailxpert.svg?style=flat-square)](https://scrutinizer-ci.com/g/mailxpert/oauth2-mailxpert)
[![Total Downloads](https://img.shields.io/packagist/dt/mailxpert/oauth2-mailxpert.svg?style=flat-square)](https://packagist.org/packages/mailxpert/oauth2-mailxpert)

This package provides Mailxpert OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

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

``` bash
$ ./vendor/bin/phpunit
```

## Credits

- [mailXpert GmbH](https://github.com/mailxpert)
- [ARTACK WebLab GmbH](https://github.com/artack)
- [All Contributors](https://github.com/mailxpert/oauth2-mailxpert/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/mailxpert/oauth2-mailxpert/blob/master/LICENSE) for more information.