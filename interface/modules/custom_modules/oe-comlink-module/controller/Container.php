<?php

namespace OpenEMR\Modules\Comlink;

require_once "Database.php";

/**
 * Class Container
 * @package OpenEMR\Modules\Comlink
 */

class Container
{
    /**
     * @var
     */
    private $database;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        if ($this->database === null) {
            $this->database = new Database();
        }
        return $this->database;
    }
}
