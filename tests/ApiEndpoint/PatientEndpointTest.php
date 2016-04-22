<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\ApiEndpoint;

use TonicForHealth\AthenaHealth\ApiEndpoint\PatientEndpoint;
use TonicForHealth\AthenaHealth\Client;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;
use TonicForHealth\AthenaHealth\Tests\ApiTestCase;

/**
 * Class PatientEndpointTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class PatientEndpointTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldCollectPayment()
    {
        $paymentParams = [
            'field1' => 'value1',
            'field2' => 'value2',
        ];

        $expectedUri = sprintf('/%d/patients/%d/collectpayment', $this->practiceId, $this->patientId);
        $expectedBody = 'field1=value1&field2=value2';
        $expectedHeaders = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $httpClient = $this->getHttpClient('POST', $expectedUri, $expectedHeaders, $expectedBody);
        $patient = $this->getPatient($httpClient);

        static::assertEquals($this->getExpectedApiResponse(), $patient->collectPayment($paymentParams));
    }

    /**
     * @param HttpClient $httpClient
     *
     * @return PatientEndpoint
     */
    protected function getPatient(HttpClient $httpClient)
    {
        $practice = new PatientEndpoint(new Client($httpClient));
        $practice->setPracticeId($this->practiceId)->setPatientId($this->patientId);

        return $practice;
    }
}
