<?php

namespace OpenEMR\Modules\Comlink;

class Database
{
    public function __construct()
    {
    }

    private function createComlinkTable()
    {

    }

    /**
     * @return string
     */
    public function doesTableExist()
    {
        $db = $GLOBALS['dbase'];
        $exist = sqlQuery("SHOW TABLES FROM `$db` LIKE 'form_vital_limits'");
        if (empty($exist)) {
            self::createComlinkTable();
            return "created";
        } else {
            return "exist";
        }
    }
}
