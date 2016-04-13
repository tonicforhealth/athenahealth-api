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
use TonicForHealth\AthenaHealth\API\Patients;

/**
 * Class PatientsTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class PatientsTest extends ApiTestCase
{
    /**
     * @test
     * @small
     */
    public function shouldCollectPayment()
    {
        $patientId = mt_rand(1000, 9999);
        $params = ['param' => 'value'];

        $response = new Response();
        $history = [];

        $apiClient = $this->createApiClient([$response], $history);
        $patients = new Patients($apiClient, static::API_PRACTICE_ID);

        static::assertSame($response, $patients->collectPayment($patientId, $params));
        static::assertCount(1, $history);

        /** @var Request $request */
        $request = $history[0]['request'];
        $requestUri = sprintf('http://localhost/195900/patients/%d/collectpayment', $patientId);

        static::assertEquals('POST', $request->getMethod());
        static::assertEquals($requestUri, (string) $request->getUri());
        static::assertEquals('application/x-www-form-urlencoded', (string) $request->getHeaderLine('Content-Type'));
        static::assertEquals('param=value', (string) $request->getBody());
    }
}
