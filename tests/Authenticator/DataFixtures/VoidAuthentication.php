<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\Authenticator\DataFixtures;

use Http\Message\Authentication;
use Psr\Http\Message\RequestInterface;

/**
 * Class VoidAuthentication
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class VoidAuthentication implements Authentication
{
    /**
     * {@inheritdoc}
     */
    public function authenticate(RequestInterface $request)
    {
        return $request;
    }
}
