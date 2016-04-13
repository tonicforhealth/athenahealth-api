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

use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Interface AuthenticatorInterface
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
interface AuthenticatorInterface
{
    /**
     * @param HttpClient $httpClient
     *
     * @return HttpClient
     *
     * @throws \Http\Client\Exception
     */
    public function authenticate(HttpClient $httpClient);
}
