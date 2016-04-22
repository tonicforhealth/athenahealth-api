<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\Api\Practice\Patient;

use TonicForHealth\AthenaHealth\Tests\Api\FunctionalTestCase;

/**
 * Class CollectPaymentTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 *
 * @group functional
 */
class CollectPaymentTest extends FunctionalTestCase
{
    /**
     * @test
     * @dataProvider providerCollectPayment
     *
     * @param int    $practiceId
     * @param int    $patientId
     * @param array  $params
     * @param string $expectedUri
     * @param string $expectedBody
     */
    public function shouldCollectPayment($practiceId, $patientId, array $params, $expectedUri, $expectedBody)
    {
        $client = $this->getClient([$this->getHttpResponse('{}')]);
        $response = $client->practice($practiceId)->patient($patientId)->collectPayment($params);

        static::assertEquals([], $response);
        $this->assertPostRequest($expectedUri, $expectedBody);
    }

    /**
     * @see shouldCollectPayment
     *
     * @return array
     */
    public function providerCollectPayment()
    {
        return [
            [
                195900,
                1234,
                ['field1' => 'value1', 'field2' => 'value2'],
                'https://api.athenahealth.com/preview1/195900/patients/1234/collectpayment',
                'field1=value1&field2=value2',
            ],
        ];
    }
}
