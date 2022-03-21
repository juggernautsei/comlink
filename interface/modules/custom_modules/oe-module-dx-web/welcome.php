<?php

/**
 * package   OpenEMR
 *  link      https://affordablecustomehr.com
 *  author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  copyright Copyright (c )2021. Sherwin Gaddis <sherwingaddis@gmail.com>
 *  All rights reserved
 *
 */


use OpenEMR\Core\Header;
use Juggernaut\Modules\DxWeb\ApiDispatcher;
use Juggernaut\Modules\DxWeb\Database;

require_once dirname(__FILE__, 4) . "/globals.php";
require_once dirname(__FILE__) . "/vendor/autoload.php";

$clinicData = new Database();
$registration = new ApiDispatcher();
$clinic = $clinicData->registerFacility();
$registration->registration($clinic);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo xlt('Welcome to the module'); ?></title>
        <?php echo Header::setupHeader(); ?>
    </head>
    <body>
        <div class="container-fluid">
            <div>
                <h1><?php echo xlt('Welcome'); ?></h1>
            </div>
            <div>
                <p><?php echo xlt('Your installation is now registered'); ?></p>
            </div>
            <div>
                <h2 class="font-weight-bold pb-4">Enterprise <span class="text-primary">cloud fax</span> for regulated industries</h2>
                <p class="pr-xl-9 font-size-md-font-weight-bold">The easiest way for both large and small businesses to achieve real digital transformation. Save time and money by eliminating hardware and outsourcing fax to the cloud. Easily integrate secure and reliable cloud fax into existing apps and workflows.</p>
            </div>
            <div class="footer">
                <p><?php echo xlt("This module was developed by") ?> <a href="https://affordablecustomehr.com" target="_blank" ><?php echo xlt("Affordable Custom EHR") ?></a></p>
                <p><?php echo xlt("Please contact them for technical support of this module") ?></p>
                <p>&copy; <?php echo date('Y')?> <?php echo xlt("Juggernaut Systems Express"); ?></p>
            </div>

        </div>
    </body>
</html>
