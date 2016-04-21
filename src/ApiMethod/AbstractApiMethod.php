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
 * Class AbstractApiMethod
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
abstract class AbstractApiMethod implements ApiMethodInterface
{
    /**
     * @var int
     */
    protected $practiceId;

    /**
     * {@inheritdoc}
     *
     * @return $this
     */
    public function setPracticeId($practiceId)
    {
        $this->practiceId = (int) $practiceId;

        return $this;
    }
}
