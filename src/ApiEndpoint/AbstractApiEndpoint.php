<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\ApiEndpoint;

use TonicForHealth\AthenaHealth\Client;

/**
 * Class AbstractApiEndpoint
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
abstract class AbstractApiEndpoint implements ApiEndpointInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var int
     */
    protected $practiceId;

    /**
     * {@inheritdoc}
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function setPracticeId($practiceId)
    {
        $this->practiceId = (int) $practiceId;

        return $this;
    }

    /**
     * @return int
     */
    public function getPracticeId()
    {
        return $this->practiceId;
    }
}
