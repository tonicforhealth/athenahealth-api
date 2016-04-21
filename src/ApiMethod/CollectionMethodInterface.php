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
 * Interface CollectionMethodInterface
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
interface CollectionMethodInterface extends ApiMethodInterface
{
    /**
     * Sets a limit of resource entries to return.
     *
     * @see LimitAndOffsetTrait::setLimit()
     *
     * @param int $limit Number of entries to return (default 1500, max 5000)
     *
     * @return CollectionMethodInterface
     */
    public function setLimit($limit);

    /**
     * Sets an offset of resource entries to return.
     *
     * @see LimitAndOffsetTrait::setOffset()
     *
     * @param int $offset Starting point of entries; 0-indexed
     *
     * @return CollectionMethodInterface
     */
    public function setOffset($offset);
}
