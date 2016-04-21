<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\ApiMethod\Patient;

use TonicForHealth\AthenaHealth\ApiMethod\Patient\CollectPaymentMethod;
use TonicForHealth\AthenaHealth\Tests\ApiMethod\AbstractHttpPostMethodTest;

/**
 * Class CollectPaymentMethodTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class CollectPaymentMethodTest extends AbstractHttpPostMethodTest
{
    /**
     * {@inheritdoc}
     */
    protected function getApiMethod()
    {
        $requestFields = [
            'field1' => 'value1',
            'field2' => 'value2',
        ];

        $collectPayment = new CollectPaymentMethod();
        $collectPayment->setPatientId(static::FIXTURES_PATIENT_ID)->setRequestFields($requestFields);

        return $collectPayment;
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedRequestUri()
    {
        return '/195900/patients/1234/collectpayment';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedRequestBody()
    {
        return 'field1=value1&field2=value2';
    }
}
