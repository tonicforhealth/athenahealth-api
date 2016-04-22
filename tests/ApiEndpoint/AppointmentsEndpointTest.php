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

use TonicForHealth\AthenaHealth\ApiEndpoint\AppointmentsEndpoint;
use TonicForHealth\AthenaHealth\Client;
use TonicForHealth\AthenaHealth\HttpClient\HttpClient;

/**
 * Class AppointmentsEndpointTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class AppointmentsEndpointTest extends ApiEndpointTestCase
{
    /**
     * @test
     */
    public function shouldGetAppointment()
    {
        $expectedUri = sprintf('/%d/appointments/%d', $this->practiceId, $this->appointmentId);
        $httpClient = $this->getHttpClient('GET', $expectedUri);
        $appointments = $this->getAppointments($httpClient);

        static::assertEquals($this->getExpectedApiResponse(), $appointments->get($this->appointmentId));
    }

    /**
     * @param HttpClient $httpClient
     *
     * @return AppointmentsEndpoint
     */
    protected function getAppointments(HttpClient $httpClient)
    {
        $practice = new AppointmentsEndpoint(new Client($httpClient));
        $practice->setPracticeId($this->practiceId);

        return $practice;
    }
}
