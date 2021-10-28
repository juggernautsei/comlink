<?php

/*
 *
 * @package      Comlink OpenEMR
 * @link               https://www.open-emr.org
 *
 * @author    Sherwin Gaddis <sherwingaddis@gmail.com>
 * @copyright Copyright (c) 2021 Sherwin Gaddis <sherwingaddis@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 *
 */

use OpenEMR\Modules\Comlink\Container;
use OpenEMR\Common\Acl\AclMain;

require_once dirname(__FILE__, 4) . "/globals.php";
require_once dirname(__FILE__) . "/controller/Container.php";

if (!AclMain::aclCheckCore('admin', 'manage_modules')) {
    echo xlt('Not Authorized');
    exit;
}

$installdatatable = new Container();
$loadTable = $installdatatable->getDatabase();
//table creation
$status = $loadTable->doesTableExist();


