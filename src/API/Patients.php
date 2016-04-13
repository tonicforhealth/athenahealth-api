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
 * Class Patients
 *
 * @author Vitalii Ekert <vitalii.ekert@tonicforhealth.com>
 */
class Patients extends AbstractApi
{
    /**
     * Accepts a payment from a patient.
     *
     * @param int   $patientId The patient ID.
     * @param array $params    The payment params such as amount and/or trackdata.
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function collectPayment($patientId, array $params)
    {
        $uri = sprintf('/%d/patients/%d/collectpayment', $this->practiceId, $patientId);
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

        return $this->client->post($uri, $headers, http_build_query($params));
    }
}
