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
 * Class DepartmentsTest
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 *
 * @group functional
 */
class DepartmentsTest extends FunctionalTestCase
{
    /**
     * @test
     * @dataProvider providerDepartments
     *
     * @param int      $practiceId
     * @param int|null $limit
     * @param int|null $offset
     * @param string   $expectedUri
     */
    public function shouldGetDepartments($practiceId, $limit, $offset, $expectedUri)
    {
        $client = $this->getClient([$this->getHttpResponse('{}')]);
        $response = $client->practice($practiceId)->departments($limit, $offset);

        static::assertEquals([], $response);
        $this->assertGetRequest($expectedUri);
    }

    /**
     * @see shouldGetDepartments
     *
     * @return array
     */
    public function providerDepartments()
    {
        return [
            [195900, null, null, 'https://api.athenahealth.com/preview1/195900/departments'],
            [195900, 1, null, 'https://api.athenahealth.com/preview1/195900/departments?limit=1'],
            [195900, null, 2, 'https://api.athenahealth.com/preview1/195900/departments?offset=2'],
            [195900, 1, 2, 'https://api.athenahealth.com/preview1/195900/departments?limit=1&offset=2'],
        ];
    }
}
