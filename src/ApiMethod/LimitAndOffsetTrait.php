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
 * Class LimitAndOffsetTrait
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
trait LimitAndOffsetTrait
{
    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @see CollectionMethodInterface::setLimit()
     *
     * @param int $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = (int) $limit;

        return $this;
    }

    /**
     * @see CollectionMethodInterface::setOffset()
     *
     * @param int $offset
     *
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = (int) $offset;

        return $this;
    }

    /**
     * @param string $requestUri
     *
     * @return string
     */
    protected function applyLimitAndOffset($requestUri)
    {
        $query = http_build_query(['limit' => $this->limit, 'offset' => $this->offset]);
        $format = (false === strpos($requestUri, '?')) ? '%s?%s' : '%s&%s';

        return rtrim(sprintf($format, $requestUri, $query), '?');
    }
}
