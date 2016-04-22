<?php
/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\Tests\Api;

/**
 * Class PracticeInfoTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 *
 * @group functional
 */
class PracticeInfoTest extends FunctionalTestCase
{
    /**
     * @test
     * @dataProvider providerPracticeInfo
     *
     * @param int|null $limit
     * @param int|null $offset
     * @param string   $expectedUri
     */
    public function shouldGetPracticeInfo($limit, $offset, $expectedUri)
    {
        $client = $this->getClient([$this->getHttpResponse('{}')]);
        $response = $client->practiceInfo($limit, $offset);

        static::assertEquals([], $response);
        $this->assertGetRequest($expectedUri);
    }

    /**
     * @see shouldGetPracticeInfo
     *
     * @return array
     */
    public function providerPracticeInfo()
    {
        return [
            [null, null, 'https://api.athenahealth.com/preview1/1/practiceinfo'],
            [1, null, 'https://api.athenahealth.com/preview1/1/practiceinfo?limit=1'],
            [null, 2, 'https://api.athenahealth.com/preview1/1/practiceinfo?offset=2'],
            [1, 2, 'https://api.athenahealth.com/preview1/1/practiceinfo?limit=1&offset=2'],
        ];
    }
}
