<?php

/*
 *  package   Comlink OpenEMR
 *  link      http://www.open-emr.org
 *  author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  copyright Copyright (c )2022. Sherwin Gaddis <sherwingaddis@gmail.com>
 *  license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

echo $_GET['encounter'];


$ssql = "DELETE FROM form_encounter WHERE `encounter` = ? ";

sqlStatement($ssql, [$_GET['encounter']]);
