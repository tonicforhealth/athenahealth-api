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

use Http\Message\Authentication;

/**
 * Class AbstractAuthenticator
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
abstract class AbstractAuthenticator implements AuthenticatorInterface
{
    /**
     * @var Authentication
     */
    protected $authentication;

    /**
     * {@inheritdoc}
     */
    public function getAuthentication()
    {
        if (!$this->isAuthenticated()) {
            $this->authentication = $this->authenticate();
        }

        return $this->authentication;
    }

    /**
     * @return bool
     */
    protected function isAuthenticated()
    {
        return null !== $this->authentication;
    }

    /**
     * @return Authentication
     *
     * @throws \Http\Client\Exception
     */
    abstract protected function authenticate();
}
