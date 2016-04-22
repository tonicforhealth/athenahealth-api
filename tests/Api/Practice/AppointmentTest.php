<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\Api\Practice;

use TonicForHealth\AthenaHealth\Tests\Api\FunctionalTestCase;

/**
 * Class AppointmentTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 *
 * @group functional
 */
class AppointmentTest extends FunctionalTestCase
{
    /**
     * @test
     * @dataProvider providerGetAppointment
     *
     * @param int    $practiceId
     * @param int    $appointmentId
     * @param string $expectedUri
     */
    public function shouldGetAppointment($practiceId, $appointmentId, $expectedUri)
    {
        $client = $this->getClient([$this->getHttpResponse('{}')]);
        $response = $client->practice($practiceId)->appointments()->get($appointmentId);

        static::assertEquals([], $response);
        $this->assertGetRequest($expectedUri);
    }

    /**
     * @see shouldGetAppointment
     *
     * @return array
     */
    public function providerGetAppointment()
    {
        return [
            [195900, 123456, 'https://api.athenahealth.com/preview1/195900/appointments/123456'],
        ];
    }
}
