<?php

/**
 * package   OpenEMR
 *  link      http://www.open-emr.org
 *  author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  copyright Copyright (c )2021. Sherwin Gaddis <sherwingaddis@gmail.com>
 *  license   All rights reserved
 *
 */

namespace OpenEMR\Module\Documo;

use OpenEMR\Module\Documo\Database;

class ApiDispatcher
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZjJkN2I2NC1kZGQxLTQwOTEtYmZmMi03ODA5ODQ0MmE2MWYiLCJhY2NvdW50SWQiOiIxODJjNTIyNC1lMzNmLTQ3ODMtOWI5Mi04YjJlODgxNWU0OTkiLCJpYXQiOjE2MzU0NDMyMDN9.WY3X-HMpWqO7JJ7EoQPxDEMZPujrSNjgubLzFiSDJCU";
    }

    public function createUser()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.documo.com/v1/users',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'firstName=example&lastName=example&password=example&email=example&userRole=example&phone=example&accountId=d1077489-5ea1-4db1-9760-853f175e8288&jobPosition=example&drive=false&sign=false&fax=false&cf=%7B%22example%22%3A%20%22value%22%7D',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $this->apiKey,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function createAccount()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.documo.com/v1/accounts',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'accountName=example&faxCallerId=example&faxCsid=example&emailNotifySendOption=None&emailNotifyReceiveOption=None&emailNotifySendIncAttachment=false&emailNotifyReceiveIncAttachment=false&timezone=pacific&allowEmailToFax=false&usersTokenLife=1&cf=%7B%22example%22%3A%20%22value%22%7D',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $this->apiKey,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
