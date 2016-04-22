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
 * Class PingTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 *
 * @group functional
 */
class PingTest extends FunctionalTestCase
{
    /**
     * @test
     * @dataProvider providerPing
     *
     * @param int    $practiceId
     * @param string $expectedUri
     */
    public function shouldPing($practiceId, $expectedUri)
    {
        $client = $this->getClient([$this->getHttpResponse('{}')]);
        $response = $client->practice($practiceId)->ping();

        static::assertEquals([], $response);
        $this->assertGetRequest($expectedUri);
    }

    /**
     * @see shouldPing
     *
     * @return array
     */
    public function providerPing()
    {
        return [
            [195900, 'https://api.athenahealth.com/preview1/195900/ping'],
        ];
    }
}
