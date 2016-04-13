<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\API;

use TonicForHealth\AthenaHealth\Client;

/**
 * Class AbstractApi
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
abstract class AbstractApi implements ApiInterface
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
    public function __construct(Client $client, $practiceId)
    {
        $this->client = $client;
        $this->practiceId = (int) $practiceId;
    }
}
