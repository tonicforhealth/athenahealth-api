<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Authenticator;

use Http\Message\Authentication\Bearer;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class BearerAuthenticator
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class BearerAuthenticator extends AbstractAuthenticator
{
    const AUTH_TOKEN_URI = '/token';

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var int
     */
    protected $tokenExpiresAt;

    /**
     * BearerAuthenticator constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritdoc}
     */
    protected function isAuthenticated()
    {
        return parent::isAuthenticated() && !$this->isTokenExpired();
    }

    /**
     * @return bool
     */
    protected function isTokenExpired()
    {
        return time() >= $this->tokenExpiresAt;
    }

    /**
     * {@inheritdoc}
     */
    protected function authenticate()
    {
        $this->tokenExpiresAt = time();

        $response = $this->httpClient->post(
            static::AUTH_TOKEN_URI,
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            http_build_query(['grant_type' => 'client_credentials'])
        );

        $content = json_decode($response->getBody(), true);
        $this->tokenExpiresAt += $content['expires_in'];

        return new Bearer($content['access_token']);
    }
}
