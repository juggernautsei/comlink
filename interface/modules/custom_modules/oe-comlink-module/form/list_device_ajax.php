<?php
require_once "../../../../globals.php";
$pid= $_GET['pid'];
$query = "SELECT * FROM patient_devices_list WHERE pid=$pid";
$dataarray = array();
$i = 0;
$x = 1;
$res = sqlStatement($query);

while ($row = sqlFetchArray($res)) {
    $id=$row['id'];
    $dataarray['data'][$i] =  [
        $x,
        $row['subehremrid'],
        $row['deviceid'],
        $row['devicemodal'],
        $row['devicemaker'],
        $row['deviceos'],
        '<button class="btn btn-success edit-btn" id="edit" data-id="'.$id.'">edit</button><button id="delete" data-id="'.$id.'" class="btn btn-danger ml-2">Delete</button>',
        

    ];
    $i++;
    $x++;
    
}

echo json_encode($dataarray);die;