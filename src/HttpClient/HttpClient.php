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
use Http\Client\HttpClient as HttpClientInterface;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\MessageFactory as MessageFactoryInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Class HttpClient
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class HttpClient extends HttpMethodsClient
{
    /**
     * @var Authentication
     */
    protected $authentication;

    /**
     * {@inheritdoc}
     */
    public function __construct(HttpClientInterface $httpClient = null, MessageFactoryInterface $messageFactory = null)
    {
        $httpClient = $httpClient ?: HttpClientDiscovery::find();
        $messageFactory = $messageFactory ?: MessageFactoryDiscovery::find();

        parent::__construct($httpClient, $messageFactory);
    }

    /**
     * @param Authentication $authentication
     *
     * @return $this
     */
    public function setAuthentication(Authentication $authentication)
    {
        $this->authentication = $authentication;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request)
    {
        if (null !== $this->authentication) {
            $request = $this->authentication->authenticate($request);
        }

        return parent::sendRequest($request);
    }
}
