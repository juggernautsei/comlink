<?php
require_once "../../../globals.php";

$query = "SELECT * FROM patient_monitoring_form";
$dataarray = array();
$i = 0;
$res = sqlStatement($query);

while ($row = sqlFetchArray($res)) {
    $query2 = "SELECT * FROM patient_data WHERE pid="
        . $row['pid'];
    $res2 = sqlStatement($query2);
    while ($row2 = sqlFetchArray($res2)) {
        $dataarray['data'][$i] =  [
            '<a href=form/edit_patient.php?pid=' . $row['pid'] . '>' . $row2['fname'] . $row2['lname'] . $row2['mname'] . '</a>',
            $row2['DOB'],
            $row['pid'],
            '',
            $row['bp_upper'],
            $row['temp_upper'],
            $row['bs_upper'],
            $row['resp_upper'],
            // $row['weight'],
            '',
            $row['oxy_upper'],
            $row['weight'],
            $row['height'],
            $row['pain_upper'],

        ];
        $i++;
    }
}

echo json_encode($dataarray);