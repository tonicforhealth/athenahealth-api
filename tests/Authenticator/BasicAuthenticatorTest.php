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

use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication\BasicAuth;
use TonicForHealth\AthenaHealth\Authenticator\BasicAuthenticator;

/**
 * Class BasicAuthenticatorTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class BasicAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldAuthenticate()
    {
        $request = MessageFactoryDiscovery::find()->createRequest('GET', '/');

        $authenticator = new BasicAuthenticator();
        $authenticator->setClientId('YOUR-CLIENT-ID')
            ->setClientSecret('YOUR-CLIENT-SECRET');

        $authentication = $authenticator->getAuthentication();
        static::assertInstanceOf(BasicAuth::class, $authentication);

        $headerLine = $authentication->authenticate($request)->getHeaderLine('Authorization');
        static::assertEquals('Basic WU9VUi1DTElFTlQtSUQ6WU9VUi1DTElFTlQtU0VDUkVU', $headerLine);
    }
}
