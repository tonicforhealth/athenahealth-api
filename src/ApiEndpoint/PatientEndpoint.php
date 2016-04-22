<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\ApiEndpoint;

use TonicForHealth\AthenaHealth\Api\PatientInterface;
use TonicForHealth\AthenaHealth\ApiMethod\Patient\CollectPaymentMethod;

/**
 * Class PatientEndpoint
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class PatientEndpoint extends AbstractApiEndpoint implements PatientInterface
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

    /**
     * @return int
     */
    public function getPatientId()
    {
        return $this->patientId;
    }

    /**
     * {@inheritdoc}
     */
    public function collectPayment(array $params)
    {
        $collectPayment = new CollectPaymentMethod();
        $collectPayment->setPracticeId($this->practiceId)
            ->setPatientId($this->patientId)
            ->setRequestFields($params);

        return $this->client->sendRequest($collectPayment);
    }
}
