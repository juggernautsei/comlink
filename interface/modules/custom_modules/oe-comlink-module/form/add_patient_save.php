<?php

/**
 * new_patient_save.php
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2018 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once "../../../../globals.php";

use OpenEMR\Common\Csrf\CsrfUtils;

if (!CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
    CsrfUtils::csrfNotVerified();
}

$date=date('y-m-d h:s');
$pid=$_POST['pid'];
$facility=$_POST['facility'];
$provider=$_POST['provider'];
$weight=$_POST['weight'];
$height=$_POST['height'];
$bp_upper=$_POST['bp_upper'];
$bp_lower=$_POST['bp_lower'];
$temp_upper=$_POST['temp_upper'];
$temp_lower=$_POST['temp_lower'];
$bs_upper=$_POST['bs_upper'];
$bs_lower=$_POST['bs_lower'];
$resp_upper=$_POST['resp_upper'];
$resp_lower=$_POST['resp_lower'];
$oxy_upper=$_POST['oxy_upper'];
$oxy_lower=$_POST['oxy_lower'];
$pain_upper=$_POST['pain_upper'];
$pain_lower=$_POST['pain_lower'];
$active=$_POST['alert'];
$count =sqlQuery("SELECT COUNT(*) FROM `patient_monitoring_form` Where pid = '$pid'");


if($count['COUNT(*)'] > 0){
    echo "Patient Already Exist !!!";
    
}else{
    $form_vitals = sqlQuery("SELECT COUNT(*) FROM form_vitals WHERE  pid =".$pid);
    if($form_vitals['COUNT(*)'] > 0){
       sqlQuery("UPDATE form_vitals SET height = $height,weight = $weight,temperature = $temp_upper,bps = $bp_upper,bpd = $bp_lower,oxygen_saturation = $oxy_upper WHERE  pid =".$pid);
    }
    sqlQuery("INSERT INTO `patient_monitoring_form` (`id`, `pid`, `facility`,`provider`,`pm_id`, `weight`, `height`, `bp_upper`, `bp_lower`, `temp_upper`, `temp_lower`, `bs_upper`, `bs_lower`, `resp_upper`, `resp_lower`, `oxy_upper`, `oxy_lower`, `pain_upper`, `pain_lower`, `alert`, `updatedAt`) VALUES
            ('','$pid','$facility','$provider','1234','$weight','$height','$bp_upper', '$bp_lower', '$temp_upper', '$temp_lower', '$bs_upper', '$bs_lower', '$resp_upper', '$resp_lower', '$oxy_upper', '$oxy_lower','$pain_upper', '$pain_lower','$active','$date')");

    echo "Success Insert New  Record !!!";
    
}




?>