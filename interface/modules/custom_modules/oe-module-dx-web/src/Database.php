<?php

/**
 * package   OpenEMR
 *  link      http//www.open-emr.org
 *  author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  copyright Copyright (c )2021. Sherwin Gaddis <sherwingaddis@gmail.com>
 *  license   https//github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 *
 */

namespace OpenEMR\Module\Documo;

class Database
{
    public function __construct()
    {
    }

    public function registerFacility()
    {
        $sql = "SELECT * FROM `facility` WHERE id = 3";
        return sqlQuery($sql);
    }
}
