<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\ApiMethod\Practice;

use TonicForHealth\AthenaHealth\ApiMethod\AbstractApiMethod;
use TonicForHealth\AthenaHealth\ApiMethod\HttpGetMethodTrait;
use TonicForHealth\AthenaHealth\ApiMethod\CollectionMethodInterface;
use TonicForHealth\AthenaHealth\ApiMethod\LimitAndOffsetTrait;

/**
 * Class DepartmentsMethod
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class DepartmentsMethod extends AbstractApiMethod implements CollectionMethodInterface
{
    use HttpGetMethodTrait;
    use LimitAndOffsetTrait;

    /**
     * {@inheritdoc}
     */
    public function getRequestUri()
    {
        $requestUri = sprintf('/%d/departments', $this->practiceId);

        return $this->applyLimitAndOffset($requestUri);
    }
}
