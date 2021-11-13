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
$pid=$_POST['pid'];
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


sqlQuery("UPDATE patient_monitoring_form SET weight = $weight, height = $height,bp_upper = $bp_upper,bp_lower = $bp_lower,temp_upper = $temp_upper,temp_lower = $temp_lower,bs_upper = $bs_upper,bs_lower = $bs_lower,resp_upper = $resp_upper,resp_lower = $resp_lower,oxy_upper = $oxy_upper,oxy_lower = $oxy_lower,pain_upper = $pain_upper,pain_lower = $pain_lower " ."WHERE pid = $pid");

echo "Success Update Record !!!";



?>