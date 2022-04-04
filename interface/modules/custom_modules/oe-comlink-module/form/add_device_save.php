<?php

/*
 *  package   Comlink OpenEMR
 *  link      http://www.open-emr.org
 *  author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  copyright Copyright (c )2022. Sherwin Gaddis <sherwingaddis@gmail.com>
 *
 */
$ignoreAuth = true;
// Set $sessionAllowWrite to true to prevent session concurrency issues during authorization related code
$sessionAllowWrite = true;

require_once "../../../../globals.php";
require_once "../includes/api.php";
$pid=$_POST['pid'];
$sub_ehr=$_POST['sub_ehr'];
$device_id=$_POST['device_id'];
$device_modal=$_POST['device_modal'];
$device_maker=$_POST['device_maker'];
$watch_os=$_POST['watch_os'];


if(empty($sub_ehr)||empty($device_id)||empty($device_modal)||empty($device_maker)||empty($watch_os)){
    echo 'please fill all data..!';
}else{

    $query = "SELECT * FROM patient_data WHERE pid=".$pid;
$res = sqlStatement($query);
while ($row = sqlFetchArray($res)) {

    $fname=$row['fname'];
    $lname=$row['lname'];


}


$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$Api_url = 'https://proddevbrdg.comlinktelehealth.io:57483/ctsiDevBridge/addSubDevice';
$payload =
    [
        "firstName" => $fname,
        "lastName" => $lname,
        "subEhrEmrID" => $sub_ehr,
        "deviceData" => [
            "deviceID" => $device_id,
            "deviceModel" => $device_modal,
            "deviceMaker" => $device_maker,
            "deviceOS" => $watch_os,
            "ehrEmrCallBackURL" => $actual_link
        ]
    ];

$resp = curl_get_content($Api_url, 'POST', json_encode($payload));
$reponse=json_decode($resp);
// print_r($reponse);die;
if($reponse->errorCode==200 &&$reponse->errorDesc=='OK'){
  sqlQuery("INSERT INTO `patient_devices_list` (`id`, `pid`, `subehremrid`,`subehremrid`,`deviceid`,`devicemodal`, `devicemaker`, `deviceos`) VALUES
            ('','$pid','$sub_ehr','$device_id','$device_modal','$device_maker','$watch_os')");
    echo 'successfully saved device..!';
}else{
    echo 'Somthing Went Wrong '.$reponse->errorDesc;
}

}
