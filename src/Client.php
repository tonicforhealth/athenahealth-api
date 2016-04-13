<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth;

use Http\Client\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use TonicForHealth\AthenaHealth\API\ApiInterface;
use TonicForHealth\AthenaHealth\Authenticator\AuthenticatorInterface;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class Client
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 *
 * @method API\Appointments appointments()
 * @method API\Patients patients()
 * @method API\Practice practice()
 */
class Client
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var AuthenticatorInterface
     */
    protected $authenticator;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var int
     */
    protected $practiceId;

    /**
     * Client constructor.
     *
     * @param HttpClient             $httpClient
     * @param AuthenticatorInterface $authenticator
     */
    public function __construct(HttpClient $httpClient, AuthenticatorInterface $authenticator)
    {
        $this->httpClient = $httpClient;
        $this->authenticator = $authenticator;
    }

    /**
     * @param string|UriInterface $baseUri
     *
     * @return $this
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = rtrim($baseUri, '/');

        return $this;
    }

    /**
     * @param int $practiceId
     *
     * @return $this
     */
    public function setPracticeId($practiceId)
    {
        $this->practiceId = (int) $practiceId;

        return $this;
    }

    /**
     * Sends an authenticated GET request.
     *
     * @param string|UriInterface $uri
     * @param array               $headers
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function get($uri, array $headers = [])
    {
        $uri = sprintf('%s%s', $this->baseUri, $uri);

        return $this->authenticator->authenticate($this->httpClient)->get($uri, $headers);
    }

    /**
     * Sends an authenticated POST request.
     *
     * @param string|UriInterface         $uri
     * @param array                       $headers
     * @param string|StreamInterface|null $body
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function post($uri, array $headers = [], $body = null)
    {
        $uri = sprintf('%s%s', $this->baseUri, $uri);

        return $this->authenticator->authenticate($this->httpClient)->post($uri, $headers, $body);
    }

    /**
     * List of all practice IDs that an API user has access to.
     *
     * @param int|null $limit  (Optional) Number of entries to return (default 1500, max 5000)
     * @param int|null $offset (Optional) Starting point of entries; 0-indexed
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function practiceInfo($limit = null, $offset = null)
    {
        $query = http_build_query(['limit' => $limit, 'offset' => $offset]);
        $uri = rtrim(sprintf('/1/practiceinfo?%s', $query), '?');

        return $this->get($uri);
    }

    /**
     * {@inheritdoc}
     *
     * @return ApiInterface
     *
     * @throws \BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        $className = sprintf('%s\\API\\%s', __NAMESPACE__, ucfirst($name));

        if (!class_exists($className) || !is_subclass_of($className, ApiInterface::class)) {
            throw new \BadMethodCallException(sprintf('Undefined API instance called: "%s".', $name));
        }

        if (null === $this->practiceId) {
            throw new \BadMethodCallException('Practice ID is empty.');
        }

        return new $className($this, $this->practiceId);
    }
}
