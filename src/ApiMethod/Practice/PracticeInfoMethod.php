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
 * Class PracticeInfoMethod
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class PracticeInfoMethod extends AbstractCollectionMethod
{
    const SPECIAL_PRACTICE_ID = 1;

    use HttpGetMethodTrait;

    /**
     * {@inheritdoc}
     */
    public function getCollectionUri()
    {
        return sprintf('/%d/practiceinfo', $this->practiceId ?: self::SPECIAL_PRACTICE_ID);
    }
}
