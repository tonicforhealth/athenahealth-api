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
 * Class AbstractCollectionMethod
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
abstract class AbstractCollectionMethod extends AbstractApiMethod implements CollectionMethodInterface
{
    use LimitAndOffsetTrait;

    /**
     * {@inheritdoc}
     */
    public function getRequestUri()
    {
        return $this->applyLimitAndOffset($this->getCollectionUri());
    }

    /**
     * @return string
     */
    abstract protected function getCollectionUri();
}
