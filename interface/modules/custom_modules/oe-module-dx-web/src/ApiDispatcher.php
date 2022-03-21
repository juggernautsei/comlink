<?php

/**
 * package   OpenEMR
 *  link      http://www.open-emr.org
 *  author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  copyright Copyright (c )2021. Sherwin Gaddis <sherwingaddis@gmail.com>
 *  license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
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

    public function registration($clinic)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.affordablecustomehr.com/register.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array("name"=>$clinic['name'],"phone"=>$clinic['phone'],"email"=>$clinic['email']),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
