<?php
require_once("../../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/sql.inc");
require_once("$srcdir/encounter.inc");
require_once("$srcdir/api.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/formdata.inc.php");


$mode =   (isset($_GET['mode']) && $_GET['mode'] != '')  ? $_GET['mode'] : '';
$id = (isset($_POST['id']) ? $_POST['id'] : '');
$user =  (isset($_SESSION['authUserID']))  ? $_SESSION['authUserID'] : '';
$prescription = $_POST['prescription'];
$prescription_id = $_POST['prescription_id'];
$prescription_delete = $_POST['prescription_delete'];
$prescription1 = $_POST['prescription1'];
$prescription1_id = $_POST['prescription1_id'];
$prescription1_delete = $_POST['prescription1_delete'];

unset($_POST['prescription']);
unset($_POST['prescription_id']);
unset($_POST['prescription_delete']);
unset($_POST['prescription1']);
unset($_POST['prescription1_id']);
unset($_POST['prescription1_delete']);

function prescription($form_id,$user,$pid,$encounter){
        $d_id = sqlQuery("delete from formato_medicamento1 where form_id=?",$form_id);
	$d_id = sqlQuery("delete from formato_medicamento2 where form_id=?",$form_id);
	global $prescription;
	global $prescription1;
        foreach($prescription1 as $k => $value){
          if($value['medicamento'] != ''){
		  $arg = array($user,$pid, $encounter,$form_id,$value['medicamento'],$value['dosis'],$value['size'],$value['unit'],$value['amount'],$value['form'],$value['route'],$value['interval'],$value['refills'],$value['freq_other']);
		  $p_id = sqlInsert("insert into formato_medicamento1 (user,pid,encounter,form_id,medicamento,dosis,size,unit,amount,form,route,`interval`,refills, freq_other) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $arg);
          }
	}
	foreach($prescription as $k => $value){
          if($value['medicamento'] != ''){
                         $arg = array($user,$pid, $encounter,$form_id,$value['medicamento'],$value['dosis'],$value['size'],$value['unit'],$value['amount'],$value['form'],$value['route'],$value['interval'],$value['refills'],$value['freq_other']);
                         $p_id = sqlInsert("insert into formato_medicamento2 (user,pid,encounter,form_id,medicamento,dosis,size,unit,amount,form,route,`interval`,refills, freq_other) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $arg);
          }
        }

}

if($mode == 'new')
{
    $newid = formSubmit('form_formato_psiquiatrico', $_POST, $_GET["id"], $userauthorized);	
    prescription($newid,$user,$pid,$encounter);
    addForm($encounter, "Formato Psiquiatrico", $newid, "formato_psiquiatrico", $pid, $userauthorized);
                formJump();
 }
else if($mode == 'edit')
{
formUpdate('form_formato_psiquiatrico', $_POST, $_GET["id"], $userauthorized);
    prescription($_GET['id'],$user,$pid,$encounter);
                formJump();
 }
?>
