<?php

/**
 * package   OpenEMR
 *  link      http://www.open-emr.org
 *  author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  copyright Copyright (c )2021. Sherwin Gaddis <sherwingaddis@gmail.com>
 *  All rights reserved
 *
 */


require_once dirname(__FILE__, 4) . "/globals.php";
require_once "vendor/autoload.php";

use Juggernaut\Modules\DxWeb\Database;
use OpenEMR\Core\Header;

$send = new Database();


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo xlt('Initial Setup for DxScript'); ?></title>
    <?php echo Header::setupHeader() ?>
</head>
<body>
<div class="container">
    <div>
        <h1><?php echo "DxScript Setup" ?></h1>
        <p><?php
            if (empty($send->checkUuid())) {
                echo xlt("Your patient table does not have UUIDs for the patients. Please check your version must be greater than 5.1.0");
                die;
            } else {
                echo xlt('Patients need to be uploaded to DxScript');
            }
            ?></p>
    </div>
    <div>
        <?php echo $send->initialSendPatients(); ?>
    </div>
    <div>
        &copy; <?php echo date('Y') . " Juggernaut Systems Express" ?>
    </div>
</div>
</body>
</html>




