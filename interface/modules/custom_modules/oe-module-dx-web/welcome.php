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
                <p style="color: green;"><?php echo xlt('Your installation is now registered'); ?></p>
            </div>
            <div class="header-content">

                <h1 class="et_pb_module_header">Trust DxScript for your ePrescribing needs.</h1>
                <span class="et_pb_fullwidth_header_subhead">Securely prescribe controlled substances electronically in a fully compliant fashion</span>
                <div class="et_pb_header_content_wrapper"><p>&nbsp;</p>
                    <p><span>DxScript® is a Surescripts™ and EPCS certified, HIPAA compliant, cloud-based ePrescribing solution that puts insurance, pharmacy benefits plan eligibility and formulary information at the provider’s fingertips at the point of prescribing. We maintain a database of the latest FDA and DEA released medications as well as all pharmacies that are EDI or eFax capable. Our electronic PDR provides our users real time drug-to-drug, drug to allergies and drug to food contraindication alerts at the point of care. DxScript® even suggests alternative drugs in the event there is a contraindication or allergy alert for a specific drug. No proprietary hardware or software required. All training and set-up is performed by professional Customer Care Engineers at NO COST to the provider(s).</span></p></div>

            </div>
            <div class="footer">
                <p><?php echo xlt("This module was developed by") ?> <a href="https://affordablecustomehr.com" target="_blank" ><?php echo xlt("Affordable Custom EHR") ?></a></p>
                <p><?php echo xlt("Please contact them for technical support of this module") ?></p>
                <p>&copy; <?php echo date('Y')?> <?php echo xlt("Juggernaut Systems Express"); ?></p>
            </div>

        </div>
    </body>
</html>
