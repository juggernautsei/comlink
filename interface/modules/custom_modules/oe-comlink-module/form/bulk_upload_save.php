<?php
require_once "../../../../globals.php";
require_once "../includes/api.php";


    $file=$_FILES['file'];

    $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    if(in_array($_FILES["file"]["type"],$allowedFileType)){
     if($_FILES["file"]["size"] > 0)
     {
        
        $targetPath = '../uploads/'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        $file = fopen($targetPath, "r");
          while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
           {
               if($getData[0]=="pid") continue;
              
                $pid=$getData[0];
                $sub_ehr=$getData[1];
                $device_id=$getData[2];
                $device_modal=$getData[3];
                $device_maker=$getData[4];
                $watch_os=$getData[5];
                $action=$getData[6];
                

                $query = "SELECT * FROM patient_data WHERE pid=".$pid;
                $res = sqlStatement($query);
                while ($row = sqlFetchArray($res)) {

                    $fname=$row['fname'];
                    $lname=$row['lname'];
                    
                    
                }

                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                $Api_url = 'https://proddevbrdg.comlinktelehealth.io:57483/ctsiDevBridge/bulkProvDev';
                // print_r($Api_url );die;
               
                $payload[] =[
                    "firstName" => $fname, 
                    "lastName" => $lname, 
                    "subEhrEmrID" => $sub_ehr, 
                    "actionCode" => $action, 
                    "deviceData" => [
                        "deviceID" => $device_id, 
                        "deviceModel" => $device_modal, 
                        "deviceMaker" => $device_maker, 
                        "deviceOS" => $watch_os, 
                        "ehrEmrCallBackURL" => $actual_link 
                    ] 
                ];
                // array_push($payload,$load);
                

                // $resp = curl_get_content($Api_url, 'POST', json_encode($payload));
               
                // $reponse=json_decode($resp);
                
                // if($reponse->errorCode=='200' &&$reponse->errorDesc='OK'){

                // if(strtoupper($action)=="ADDSUBDEVICE" || strtoupper($action)=="CHANGESUBDEVICE") {
                //     $check = sqlQuery("SELECT count(*) FROM `patient_devices_list` WHERE pid='$pid' And `subehremrid` = '$sub_ehr' And `deviceid`='$device_id' And `devicemodal`='$device_modal' And `devicemaker`='$device_maker' And `deviceos`='$watch_os'");
                //     // print_r($check['count(*)']);die;
                //     if($check['count(*)'] > 0){
                //         sqlQuery("UPDATE patient_devices_list SET subehremrid = '$sub_ehr',deviceid = '$device_id',devicemodal = '$device_modal', devicemaker = '$device_maker',deviceos = '$watch_os' WHERE id = '$pid' And `subehremrid` = '$sub_ehr'");

                //     }else{
                //         sqlQuery("INSERT INTO `patient_devices_list` (`id`, `pid`, `subehremrid`,`deviceid`,`devicemodal`, `devicemaker`, `deviceos`) VALUES
                //             ('','$pid','$sub_ehr','$device_id','$device_modal','$device_maker','$watch_os')");
                //     }
                // }else if(strtoupper($action)=="DELETESUBDEVICE"){
                //     $sql="DELETE FROM patient_devices_list WHERE pid = '$pid' And `subehremrid` = '$sub_ehr'";
                    
                //     sqlQuery($sql);
                // }

                
                   

            
                // }else{
                //     echo 'Somthing Went Wrong '.$reponse->errorDesc;
                // }
      
        }
        $count= count($payload);
        $post_payload= [
            "numRecords" => $count,
            "bulkData" => $payload
        ];
        $payload=json_encode($post_payload);
        $resp = curl_get_content($Api_url, 'POST', $payload);
        $reponse=json_decode($resp);
             
        if($reponse){
            if($reponse->errorCode=='200' &&$reponse->errorDesc='OK'){
                foreach ($payload->bulkData as $key => $value) {
                    $sub_ehr=$value->subEhrEmrID;
                    $device_id=$value->deviceID;
                    $device_modal=$value->deviceModel;
                    $device_maker=$value->deviceMaker;
                    $watch_os=$value->deviceOS;
            
                    if( strtoupper($value->actionCode) =="ADDSUBDEVICE" || strtoupper($value->actionCode)=="CHANGESUBDEVICE") {
                        $check = sqlQuery("SELECT count(*) FROM `patient_devices_list` WHERE pid='$pid' And `subehremrid` = '$sub_ehr' And `deviceid`='$device_id' And `devicemodal`='$device_modal' And `devicemaker`='$device_maker' And `deviceos`='$watch_os'");

                        if($check['count(*)'] > 0){
                            sqlQuery("UPDATE patient_devices_list SET subehremrid = '$sub_ehr',deviceid = '$device_id',devicemodal = '$device_modal', devicemaker = '$device_maker',deviceos = '$watch_os' WHERE id = '$pid' And `subehremrid` = '$sub_ehr'");

                        }else{
                            sqlQuery("INSERT INTO `patient_devices_list` (`id`, `pid`, `subehremrid`,`deviceid`,`devicemodal`, `devicemaker`, `deviceos`) VALUES
                                ('','$pid','$sub_ehr','$device_id','$device_modal','$device_maker','$watch_os')");
                        }
                    }else if(strtoupper($value->actionCode)=="DELETESUBDEVICE"){
                        $sql="DELETE FROM patient_devices_list WHERE pid = '$pid' And `subehremrid` = '$sub_ehr'";
                        
                        sqlQuery($sql);
                    }
            
                }
                echo $reponse;
                
            }
        }else{
            echo "Somthing Went Wrong on Api !!!..";
        } 
        
        
     }
}