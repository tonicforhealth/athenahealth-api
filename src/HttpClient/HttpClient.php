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
use Http\Client\HttpClient as BaseHttpClient;
use Http\Client\Plugin\ErrorPlugin;
use Http\Client\Plugin\Exception\ClientErrorException;
use Http\Client\Plugin\Exception\ServerErrorException;
use Http\Client\Plugin\PluginClient;
use Http\Message\MessageFactory;
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
     * {@inheritdoc}
     */
    public function __construct(BaseHttpClient $httpClient, MessageFactory $messageFactory)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $pluginClient = new PluginClient($httpClient, [new ErrorPlugin()]);

        parent::__construct($pluginClient, $messageFactory);
    }

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
     *
     * @throws ClientErrorException If response status code is a 4xx
     * @throws ServerErrorException If response status code is a 5xx
     */
    public function send($method, $uri, array $headers = [], $body = null)
    {
        return parent::send($method, sprintf('%s%s', $this->baseUri, $uri), $headers, $body);
    }

    /**
     * {@inheritdoc}
     *
     * @throws ClientErrorException If response status code is a 4xx
     * @throws ServerErrorException If response status code is a 5xx
     */
    public function sendRequest(RequestInterface $request)
    {
        $authentication = $this->authenticator->getAuthentication();

        return parent::sendRequest($authentication->authenticate($request));
    }
}
