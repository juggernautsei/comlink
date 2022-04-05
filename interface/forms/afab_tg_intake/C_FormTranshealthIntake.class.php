<?php

/*
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * @author  Sherwin Gaddis <sherwingaddis@gmail.com>
 * @copyright Copyright (c)  2022 Sherwin Gaddis <sherwingaddis@gmail.com>
 * @license https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 *
 */

require_once($GLOBALS['fileroot'] . "/library/forms.inc");
require_once("FormTranshealthIntake.class.php");

class C_FormTranshealthIntake extends Controller
{
    public $template_dir;
    /**
     * @var mixed|string
     */
    private $termplate_mod;


    public function __construct($template_mod = "general")
    {
        parent::__construct();
        $this->termplate_mod = $template_mod;
        $this->template_dir = dirname(__FILE__) . "/templates/";
    }

    function default_action()
    {
        $form = new FormTranshealthIntake();
        $this->assign("data", $form);
        return $this->fetch($this->template_dir . $this->template_mod . "_new.html");
    }

    function view_action($form_id)
    {
        if (is_numeric($form_id)) {
            $form = new FormTranshealthIntake($form_id);
        } else {
            $form = new FormTranshealthIntake();
        }

        $this->assign("data", $form);

        return $this->fetch($this->template_dir . $this->template_mod . "_new.html");
    }

    function default_action_process()
    {
        if ($_POST['process'] != "true") {
            return;
        }
        $billing = new AutoBilling();
        $billing->billingEntries($_POST['dxcode'], $_POST['cpt']);

        $this->form = new FormTranshealthIntake($_POST['id']);
        parent::populate_object($this->form);

        $this->form->persist();
        if ($GLOBALS['encounter'] == "") {
            $GLOBALS['encounter'] = date("Ymd");
        }

        if (empty($_POST['id'])) {
            addForm($GLOBALS['encounter'], "SOAP", $this->form->id, "soap", $GLOBALS['pid'], $_SESSION['userauthorized']);
            $_POST['process'] = "";
        }
    }
}
