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

use TonicForHealth\AthenaHealth\ApiMethod\HttpPostMethodTrait;

/**
 * Class CollectPaymentMethod
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class CollectPaymentMethod extends AbstractPatientMethod
{
    use HttpPostMethodTrait;

    /**
     * {@inheritdoc}
     */
    public function getRequestUri()
    {
        return sprintf('/%d/patients/%d/collectpayment', $this->practiceId, $this->patientId);
    }
}
