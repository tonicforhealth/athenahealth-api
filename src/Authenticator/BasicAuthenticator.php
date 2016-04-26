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

use Http\Message\Authentication\BasicAuth;

/**
 * Class BasicAuthenticator
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class BasicAuthenticator extends AbstractAuthenticator
{
    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @param string $clientId
     *
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = (string) $clientId;

        return $this;
    }

    /**
     * @param string $clientSecret
     *
     * @return $this
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = (string) $clientSecret;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function authenticate()
    {
        return new BasicAuth($this->clientId, $this->clientSecret);
    }
}
