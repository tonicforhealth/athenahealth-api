<?php

/**
 * This file is part of the AthenaHealth package.
 *
 * Copyright (c) 2016 Tonic Health <info@tonicforhealth.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace TonicForHealth\AthenaHealth\API;

use Http\Client\Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Practice
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class Practice extends AbstractApi
{
    /**
     * Return an acknowledgement that request was received and that this API key has access to the given practice.
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function ping()
    {
        return $this->client->get(sprintf('/%d/ping', $this->practiceId));
    }

    /**
     * Get a list of departments for given practice.
     *
     * @param int|null $limit  (Optional) Number of entries to return (default 1500, max 5000)
     * @param int|null $offset (Optional) Starting point of entries; 0-indexed
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function departments($limit = null, $offset = null)
    {
        $query = http_build_query(['limit' => $limit, 'offset' => $offset]);
        $uri = rtrim(sprintf('/%d/departments?%s', $this->practiceId, $query), '?');

        return $this->client->get($uri);
    }
}
