<?php

/**
 * package   OpenEMR
 *  link      http//www.open-emr.org
 *  author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  copyright Copyright (c )2021. Sherwin Gaddis <sherwingaddis@gmail.com>
 *  license   https//github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 *
 */

namespace Juggernaut\Modules\DxWeb;

class Database
{
    public function __construct()
    {
        //do epic stuff
    }

    public function registerFacility()
    {
        $sql = "SELECT * FROM `facility` WHERE id = 3";
        return sqlQuery($sql);
    }

    public function initialSendPatients(): string
    {
        $sql = "select
            pd.uuid,
            pd.pid,
            pd.DOB,
            pd.sex,
            pd.ss,
            pd.fname,
            pd.lname,
            pd.mname,
            pd.street,
            pd.city,
            pd.state,
            pd.email,
            pd.postal_code,
            pd.phone_home,
            pd.phone_cell,
            pd.status,
            ic.name,
            in.plan_name,
            in.group_number,
            in.policy_number,
            in.subscriber_relationship
            FROM patient_data pd
            LEFT JOIN insurance_data AS `in` ON pd.pid = in.pid
            LEFT JOIN insurance_companies AS `ic` ON in.provider = ic.id
            ORDER BY pd.lname, pd.fname
        ";

        $patientList = sqlStatementNoLog($sql);
        $csv = '';
        while ($row = sqlFetchArray($patientList)) {
            $csv .= $row['uuid'] . ", " .
                $row['pid'] . ", " .
                $row['DOB'] . ", " ;
            if ($row['sex'] == 'Male') {
                    $csv .= 'M' . ", ";
                } else {
                    $csv .= 'F' . ", ";
                }
            $csv .=   $row['ss'] . ", "  .
                $row['fname'] . ", " .
                $row['lname'] . ", " .
                $row['mname'] . ", " .
                $row['street'] . ", " .
                $row['city'] . ", " .
                $row['state'] . ", " .
                $row['postal_code'] . ", " .
                $row['phone_home'] . ", " .
                $row['phone_cell'] . ", ";
            switch ($row['subscriber_relationship']) {
                case "self":
                    $csv .= "S, " ;
                    break;
                case "spouse":
                    $csv .= "M, ";
                    break;
                case "child":
                    $csv .= "C, ";
                    break;
                case "parent":
                    $csv .= "P, ";
                default:
                    $csv .= "'', ";
            }
            $csv .=
                $row['status'] . ", " .
                $row['name'] . ", " .
                $row['plan_name'] . ", " .
                $row['group_number'] . ", " .
                $row['policy_number'] . ", ";
                if (isset($row['name'])) {
                    $csv .= 'Yes' . ", ";
                } else {
                    $csv .= 'No' . ", ";
                }
                $csv .= $GLOBALS['unique_installation_id'] . ", ";
                $csv .= $row['email'] . PHP_EOL;
        }
        return $csv;
    }
}
