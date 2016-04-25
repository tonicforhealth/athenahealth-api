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

use TonicForHealth\AthenaHealth\ApiMethod\AbstractCollectionMethod;
use TonicForHealth\AthenaHealth\ApiMethod\HttpGetMethodTrait;

/**
 * Class DepartmentsMethod
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class DepartmentsMethod extends AbstractCollectionMethod
{
    use HttpGetMethodTrait;

    /**
     * {@inheritdoc}
     */
    public function getCollectionUri()
    {
        return sprintf('/%d/departments', $this->practiceId);
    }
}