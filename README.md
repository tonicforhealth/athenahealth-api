# PHP athenahealth API

A simple wrapper for [athenahealth API](http://www.athenahealth.com/developer-portal) services, written with PHP5.

## Installation using [Composer](http://getcomposer.org/)

Choose and install the [HTTPlug](http://httplug.io/) dependencies for
[php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) and
[psr/http-message-implementation](https://packagist.org/providers/psr/http-message-implementation)
virtual packages, e.g.:

```bash
$ composer require php-http/guzzle6-adapter
```

and then run the following command:

```bash
$ composer require tonicforhealth/athenahealth-api
```

## Usage

```php
<?php

use TonicForHealth\AthenaHealth\Authenticator\BearerAuthenticator;
use TonicForHealth\AthenaHealth\Client;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

$httpClient = new HttpClient();

$authenticator = new BearerAuthenticator();
$authenticator->setAuthUri('https://api.athenahealth.com/oauthpreview/token')
    ->setClientId('YOUR-CLIENT-ID')
    ->setClientSecret('YOUR-CLIENT-SECRET');

$apiClient = new Client($httpClient, $authenticator);
$apiClient->setBaseUri('https://api.athenahealth.com/preview1');

$response = $practiceInfo = $apiClient->practiceInfo();
$content = (string) $response->getBody();
```
