# PHP athenahealth API
[![License](https://img.shields.io/github/license/tonicforhealth/athenahealth-api.svg?maxAge=2592000)](LICENSE.md)
[![Build Status](https://travis-ci.org/tonicforhealth/athenahealth-api.svg?branch=master)](https://travis-ci.org/tonicforhealth/athenahealth-api)
[![Code Coverage](https://scrutinizer-ci.com/g/tonicforhealth/athenahealth-api/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tonicforhealth/athenahealth-api/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tonicforhealth/athenahealth-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tonicforhealth/athenahealth-api/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e7c5255b-43bc-4f0a-a0b9-f3176ea5d2e8/mini.png)](https://insight.sensiolabs.com/projects/e7c5255b-43bc-4f0a-a0b9-f3176ea5d2e8)

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

The first step is to define a `\Http\Client\HttpClient` object that will be used to send HTTP requests and
a `\Http\Message\MessageFactory` object that will be used to generate HTTP messages.

The recommended way to do this is to use the [Discovery](http://docs.php-http.org/en/latest/discovery.html) services:

```php
<?php

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;

$baseHttpClient = HttpClientDiscovery::find();
$messageFactory = MessageFactoryDiscovery::find();
```

After that you must define the following chain of objects with your API credentials to establish
a correct authentication process:

```php
<?php

use TonicForHealth\AthenaHealth\Authenticator\BasicAuthenticator;
use TonicForHealth\AthenaHealth\Authenticator\BearerAuthenticator;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

$basicAuthenticator = new BasicAuthenticator();
$basicAuthenticator->setClientId('YOUR-CLIENT-ID')
    ->setClientSecret('YOUR-CLIENT-SECRET');

$authHttpClient = new HttpClient($baseHttpClient, $messageFactory);
$authHttpClient->setAuthenticator($basicAuthenticator)
    ->setBaseUri('https://api.athenahealth.com/oauthpreview');

$apiHttpClient = new HttpClient($baseHttpClient, $messageFactory);
$apiHttpClient->setAuthenticator(new BearerAuthenticator($authHttpClient))
    ->setBaseUri('https://api.athenahealth.com/preview1');
```

Finally you can construct an API client:

```php
<?php

use TonicForHealth\AthenaHealth\Client;

$apiClient = new Client($apiHttpClient);
$practiceInfo = $apiClient->practiceInfo();
```

A full list of supported API methods with usage examples you can find
[here](https://github.com/tonicforhealth/athenahealth-api/wiki).
