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
use TonicForHealth\AthenaHealth\API\Practice;

/**
 * Class PracticeTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class PracticeTest extends ApiTestCase
{
    /**
     * @test
     * @small
     */
    public function shouldPing()
    {
        $response = new Response();
        $history = [];

        $apiClient = $this->createApiClient([$response], $history);
        $practice = new Practice($apiClient, static::API_PRACTICE_ID);

        static::assertSame($response, $practice->ping());
        static::assertCount(1, $history);

        /** @var Request $request */
        $request = $history[0]['request'];

        static::assertEquals('GET', $request->getMethod());
        static::assertEquals('http://localhost/195900/ping', (string) $request->getUri());
    }

    /**
     * @test
     * @small
     * @dataProvider providerGetDepartments
     *
     * @param int    $limit
     * @param int    $offset
     * @param string $uri
     */
    public function shouldGetDepartments($limit, $offset, $uri)
    {
        $response = new Response();
        $history = [];

        $apiClient = $this->createApiClient([$response], $history);
        $practice = new Practice($apiClient, static::API_PRACTICE_ID);

        static::assertSame($response, $practice->departments($limit, $offset));
        static::assertCount(1, $history);

        /** @var Request $request */
        $request = $history[0]['request'];

        static::assertEquals('GET', $request->getMethod());
        static::assertEquals($uri, (string) $request->getUri());
    }

    /**
     * @see shouldGetDepartments
     *
     * @return array
     */
    public function providerGetDepartments()
    {
        return [
            [null, null, 'http://localhost/195900/departments'],
            [1, null, 'http://localhost/195900/departments?limit=1'],
            [null, 2, 'http://localhost/195900/departments?offset=2'],
            [1, 2, 'http://localhost/195900/departments?limit=1&offset=2'],
        ];
    }
}
