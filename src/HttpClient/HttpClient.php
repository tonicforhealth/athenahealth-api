<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\HttpClient;

use Http\Client\Common\HttpMethodsClient;
use Psr\Http\Message\RequestInterface;
use TonicForHealth\AthenaHealth\Authenticator\AuthenticatorInterface;

/**
 * Class HttpClient
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class HttpClient extends HttpMethodsClient
{
    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var AuthenticatorInterface
     */
    protected $authenticator;

    /**
     * @param string $baseUri
     *
     * @return $this
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = rtrim($baseUri, '/');

        return $this;
    }

    /**
     * @param AuthenticatorInterface $authenticator
     *
     * @return $this
     */
    public function setAuthenticator(AuthenticatorInterface $authenticator)
    {
        $this->authenticator = $authenticator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function send($method, $uri, array $headers = [], $body = null)
    {
        return parent::send($method, sprintf('%s%s', $this->baseUri, $uri), $headers, $body);
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request)
    {
        $authentication = $this->authenticator->getAuthentication();

        return parent::sendRequest($authentication->authenticate($request));
    }
}
