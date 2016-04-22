<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\ApiMethod\Patient;

use TonicForHealth\AthenaHealth\ApiMethod\AbstractApiMethod;

/**
 * Class AbstractPatientMethod
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
abstract class AbstractPatientMethod extends AbstractApiMethod
{
    /**
     * @var int
     */
    protected $patientId;

    /**
     * @param int $patientId
     *
     * @return $this
     */
    public function setPatientId($patientId)
    {
        $this->patientId = (int) $patientId;

        return $this;
    }
}
