<?php
require_once "../../../globals.php";

$query = "SELECT * FROM patient_monitoring_form";
$dataarray = array();
$i = 0;
$res = sqlStatement($query);

while ($row = sqlFetchArray($res)) {
    

    $form_vitals = "SELECT bps,bpd,height,weight,temperature,respiration,oxygen_saturation FROM form_vitals WHERE pid=". $row['pid'];
    $form_vitalsres = sqlStatement($form_vitals);
    $form_vitalsrow = sqlFetchArray($form_vitalsres);

    $facility = "SELECT facility.name FROM facility
    INNER JOIN openemr_postcalendar_events ON facility.id = openemr_postcalendar_events.pc_eid WHERE openemr_postcalendar_events.pc_pid=". $row['pid'];

    $facilityres = sqlStatement($facility);
    $facilityrow = sqlFetchArray($facilityres);
   

    $query2 = "SELECT * FROM patient_data  WHERE patient_data.pid=". $row['pid'];
    $res2 = sqlStatement($query2);

    while ($row2 = sqlFetchArray($res2)) {


        if( $row['alert'] == "Need Attention"){
            $alert='<div class="alert alert-danger" role="alert">'.$row['alert'].'</div>';
        }elseif( $row['alert'] == "Monitored"){
            $alert='<div class="alert alert-info" role="alert">'.$row['alert'].'</div>';
        }else{
            $alert='';
        }
        $dataarray['data'][$i] =  [
            '<a href=form/edit_patient.php?pid=' . $row['pid'] . '>' . $row2['fname'] . $row2['lname'] . $row2['mname'] . '</a>',
            $row2['DOB'],
            $row['pid'],
            $facilityrow['name']?$facilityrow['name']:0,
            $form_vitalsrow['bps'].'/'.$form_vitalsrow['bpd'],
            $form_vitalsrow['temperature'],
            $row['bs_upper'],
            $form_vitalsrow['respiration'],
            '',
            $form_vitalsrow['oxygen_saturation'],
            $form_vitalsrow['weight'],
            $form_vitalsrow['height'],
            $row['pain_upper'],
            $alert,
            

        ];
        $i++;
    }
}

echo json_encode($dataarray);