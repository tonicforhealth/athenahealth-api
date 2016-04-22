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
 * Interface ApiEndpointInterface
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
interface ApiEndpointInterface
{
    /**
     * ApiEndpointInterface constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client);

    /**
     * @param int $practiceId
     *
     * @return ApiEndpointInterface
     */
    public function setPracticeId($practiceId);
}
