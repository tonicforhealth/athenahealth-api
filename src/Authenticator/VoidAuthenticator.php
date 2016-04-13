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
 * Class VoidAuthenticator
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class VoidAuthenticator implements AuthenticatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function authenticate(HttpClient $httpClient)
    {
        return $httpClient;
    }
}
