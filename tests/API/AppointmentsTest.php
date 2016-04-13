<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\API;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use TonicForHealth\AthenaHealth\API\Appointments;

/**
 * Class AppointmentsTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class AppointmentsTest extends ApiTestCase
{
    /**
     * @test
     * @small
     */
    public function shouldGetById()
    {
        $appointmentId = mt_rand(100000, 999999);

        $response = new Response();
        $history = [];

        $apiClient = $this->createApiClient([$response], $history);
        $appointments = new Appointments($apiClient, static::API_PRACTICE_ID);

        static::assertSame($response, $appointments->get($appointmentId));
        static::assertCount(1, $history);

        /** @var Request $request */
        $request = $history[0]['request'];
        $requestUri = sprintf('http://localhost/195900/appointments/%d', $appointmentId);

        static::assertEquals('GET', $request->getMethod());
        static::assertEquals($requestUri, (string) $request->getUri());
    }
}
