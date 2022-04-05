<?php

/*
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * @author  Sherwin Gaddis <sherwingaddis@gmail.com>
 * @copyright Copyright (c)  2022 Sherwin Gaddis <sherwingaddis@gmail.com>
 * @license https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 *
 */

use OpenEMR\Common\ORDataObject\ORDataObject;

/**
 * class FormHpTjePrimary
 *
 */
class FormTranshealthIntake
{
    function __construct($id = "", $_prefix = "")
    {
        if (is_numeric($id)) {
            $this->id = $id;
        } else {
            $id = "";
            $this->date = date("Y-m-d H:i:s");
        }

        $this->_table = "form_afab_tg_intake";
        $this->activity = 1;
        $this->pid = $GLOBALS['pid'];
        if ($id != "") {
            $this->populate();
        }
    }
    function populate()
    {
        parent::populate();
        $this->temp_methods = parent::_load_enum("temp_locations",false);
    }
}
