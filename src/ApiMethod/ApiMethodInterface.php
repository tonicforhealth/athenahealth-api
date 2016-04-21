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
 * Interface ApiMethodInterface
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
interface ApiMethodInterface
{
    const REQUEST_METHOD_GET = 'GET';
    const REQUEST_METHOD_POST = 'POST';

    /**
     * @param int $practiceId
     *
     * @return ApiMethodInterface
     */
    public function setPracticeId($practiceId);

    /**
     * @see HttpGetMethodTrait
     * @see HttpPostMethodTrait
     *
     * @return string
     */
    public function getRequestMethod();

    /**
     * @return string
     */
    public function getRequestUri();

    /**
     * @see HttpGetMethodTrait
     * @see HttpPostMethodTrait
     *
     * @return array
     */
    public function getRequestHeaders();

    /**
     * @see HttpGetMethodTrait
     * @see HttpPostMethodTrait
     *
     * @return string|null
     */
    public function getRequestBody();
}
