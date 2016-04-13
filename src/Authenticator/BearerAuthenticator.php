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

use Http\Message\Authentication\BasicAuth;
use Http\Message\Authentication\Bearer;
use Psr\Http\Message\UriInterface;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class BearerAuthenticator
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class BearerAuthenticator implements AuthenticatorInterface
{
    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $authUri;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var int
     */
    protected $tokenExpiresAt;

    /**
     * @param string $clientId
     *
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = (string) $clientId;

        return $this;
    }

    /**
     * @param string $clientSecret
     *
     * @return $this
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = (string) $clientSecret;

        return $this;
    }

    /**
     * @param string|UriInterface $authUri
     *
     * @return $this
     */
    public function setAuthUri($authUri)
    {
        $this->authUri = (string) $authUri;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(HttpClient $httpClient)
    {
        if (!$this->isTokenValid()) {
            $this->refreshToken($httpClient);
        }

        return $httpClient->setAuthentication(new Bearer($this->token));
    }

    /**
     * @param HttpClient $httpClient
     *
     * @return $this
     *
     * @throws \Http\Client\Exception
     */
    protected function refreshToken(HttpClient $httpClient)
    {
        $this->tokenExpiresAt = time();

        $basicAuth = new BasicAuth($this->clientId, $this->clientSecret);
        $httpClient->setAuthentication($basicAuth);

        $response = $httpClient->post(
            $this->authUri,
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            http_build_query(['grant_type' => 'client_credentials'])
        );

        $content = json_decode($response->getBody(), true);

        $this->token = $content['access_token'];
        $this->tokenExpiresAt += $content['expires_in'];

        return $this;
    }

    /**
     * @return bool
     */
    protected function isTokenValid()
    {
        return null !== $this->token && time() < $this->tokenExpiresAt;
    }
}
