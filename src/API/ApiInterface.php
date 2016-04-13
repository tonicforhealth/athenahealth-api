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
 * Interface ApiInterface
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
interface ApiInterface
{
    /**
     * ApiInterface constructor.
     *
     * @param Client $client
     * @param int    $practiceId
     */
    public function __construct(Client $client, $practiceId);
}
