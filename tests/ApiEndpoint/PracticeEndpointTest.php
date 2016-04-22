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

use TonicForHealth\AthenaHealth\Api\AppointmentsInterface;
use TonicForHealth\AthenaHealth\Api\PatientInterface;
use TonicForHealth\AthenaHealth\ApiEndpoint\AppointmentsEndpoint;
use TonicForHealth\AthenaHealth\ApiEndpoint\PatientEndpoint;
use TonicForHealth\AthenaHealth\ApiEndpoint\PracticeEndpoint;
use TonicForHealth\AthenaHealth\Client;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;
use TonicForHealth\AthenaHealth\Tests\ApiTestCase;

/**
 * Class PracticeEndpointTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class PracticeEndpointTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldPing()
    {
        $httpClient = $this->getHttpClient('GET', sprintf('/%d/ping', $this->practiceId));
        $practice = $this->getPractice($httpClient);

        static::assertEquals($this->getExpectedApiResponse(), $practice->ping());
    }

    /**
     * @test
     */
    public function shouldGetDepartments()
    {
        $httpClient = $this->getHttpClient('GET', sprintf('/%d/departments', $this->practiceId));
        $practice = $this->getPractice($httpClient);

        static::assertEquals($this->getExpectedApiResponse(), $practice->departments());
    }

    /**
     * @test
     */
    public function shouldGetDepartmentsWithLimit()
    {
        $expectedUri = sprintf('/%d/departments?limit=%d', $this->practiceId, $this->requestLimit);
        $httpClient = $this->getHttpClient('GET', $expectedUri);
        $response = $this->getPractice($httpClient)->departments($this->requestLimit);

        static::assertEquals($this->getExpectedApiResponse(), $response);
    }

    /**
     * @test
     */
    public function shouldGetDepartmentsWithOffset()
    {
        $expectedUri = sprintf('/%d/departments?offset=%d', $this->practiceId, $this->requestOffset);
        $httpClient = $this->getHttpClient('GET', $expectedUri);
        $response = $this->getPractice($httpClient)->departments(null, $this->requestOffset);

        static::assertEquals($this->getExpectedApiResponse(), $response);
    }

    /**
     * @test
     */
    public function shouldGetDepartmentsWithLimitAndOffset()
    {
        $expectedUri = sprintf(
            '/%d/departments?limit=%d&offset=%d',
            $this->practiceId,
            $this->requestLimit,
            $this->requestOffset
        );

        $httpClient = $this->getHttpClient('GET', $expectedUri);
        $response = $this->getPractice($httpClient)->departments($this->requestLimit, $this->requestOffset);

        static::assertEquals($this->getExpectedApiResponse(), $response);
    }

    /**
     * @test
     */
    public function shouldGetPatient()
    {
        $httpClient = $this->getHttpClientMock(['send']);
        $httpClient->expects(static::never())->method('send');

        /** @var PatientInterface|PatientEndpoint $patient */
        $patient = $this->getPractice($httpClient)->patient($this->patientId);

        static::assertInstanceOf(PatientInterface::class, $patient);
        static::assertInstanceOf(PatientEndpoint::class, $patient);
        static::assertEquals($this->practiceId, $patient->getPracticeId());
        static::assertEquals($this->patientId, $patient->getPatientId());
    }

    /**
     * @test
     */
    public function shouldGetAppointments()
    {
        $httpClient = $this->getHttpClientMock(['send']);
        $httpClient->expects(static::never())->method('send');

        /** @var AppointmentsInterface|AppointmentsEndpoint $appointments */
        $appointments = $this->getPractice($httpClient)->appointments();

        static::assertInstanceOf(AppointmentsInterface::class, $appointments);
        static::assertInstanceOf(AppointmentsEndpoint::class, $appointments);
        static::assertEquals($this->practiceId, $appointments->getPracticeId());
    }

    /**
     * @param HttpClient $httpClient
     *
     * @return PracticeEndpoint
     */
    protected function getPractice(HttpClient $httpClient)
    {
        $practice = new PracticeEndpoint(new Client($httpClient));
        $practice->setPracticeId($this->practiceId);

        return $practice;
    }
}
