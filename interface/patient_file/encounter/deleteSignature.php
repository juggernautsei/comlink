<?php

/*
 *  package   Comlink OpenEMR
 *  link      http://www.open-emr.org
 *  author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  copyright Copyright (c )2022. Sherwin Gaddis <sherwingaddis@gmail.com>
 *  license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once dirname(__FILE__, 3) . "/globals.php";

echo $_GET['encounter'];


$ssql = "DELETE FROM form_encounter WHERE `encounter` = ? ";

sqlQuery($ssql, [$_GET['encounter']]);
