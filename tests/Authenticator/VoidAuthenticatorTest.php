<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\Authenticator;

use TonicForHealth\AthenaHealth\Authenticator\VoidAuthenticator;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class VoidAuthenticatorTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class VoidAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @small
     */
    public function shouldAuthenticate()
    {
        $httpClient = new HttpClient();
        $authenticator = new VoidAuthenticator();

        static::assertSame($httpClient, $authenticator->authenticate($httpClient));
    }
}
