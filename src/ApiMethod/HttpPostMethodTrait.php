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
 * Class HttpPostMethodTrait
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
trait HttpPostMethodTrait
{
    /**
     * @var array
     */
    protected $requestFields = [];

    /**
     * @param array $requestFields
     *
     * @return $this
     */
    public function setRequestFields(array $requestFields)
    {
        $this->requestFields = $requestFields;

        return $this;
    }

    /**
     * @see ApiMethodInterface::getRequestMethod()
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return ApiMethodInterface::REQUEST_METHOD_POST;
    }

    /**
     * @see ApiMethodInterface::getRequestHeaders()
     *
     * @return array
     */
    public function getRequestHeaders()
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    /**
     * @see ApiMethodInterface::getRequestBody()
     *
     * @return string
     */
    public function getRequestBody()
    {
        return http_build_query($this->requestFields);
    }
}
