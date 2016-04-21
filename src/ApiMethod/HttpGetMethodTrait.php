<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\ApiMethod;

/**
 * Class HttpGetMethodTrait
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
trait HttpGetMethodTrait
{
    /**
     * @see ApiMethodInterface::getRequestMethod()
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return ApiMethodInterface::REQUEST_METHOD_GET;
    }

    /**
     * @see ApiMethodInterface::getRequestHeaders()
     *
     * @return array
     */
    public function getRequestHeaders()
    {
        return [];
    }

    /**
     * @see ApiMethodInterface::getRequestBody()
     *
     * @return null
     */
    public function getRequestBody()
    {
        return null;
    }
}
