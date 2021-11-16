<?php

include_once '../../globals.php';
include_once "$srcdir/options.inc.php";
use OpenEMR\Core\Header;


$form_title = '';
$result = getPatientData($_SESSION['pid'], "sex,DOB,DATE_FORMAT(DOB,'%Y%m%d') as DOB, fname, lname");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Formato Psiquiátrico</title>
  <link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/public/assets/bootstrap/dist/css/bootstrap.min.css?v=41">
  <link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/public/assets/font-awesome/css/font-awesome.css">

  <script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/bootstrap/dist/js/bootstrap.min.js"></script>
<?php Header::setupHeader(['datetime-picker', 'common', 'jquery-ui', 'jquery-ui-darkness']);?>
<script>
function get_pre_value(){
var url = new URL(window.location.href);
url.searchParams.set('get_pre_value','1');
window.location.href = url.href;
}
</script>
<style>
.freqother-hidden {
display:none;
}
.nav>a {
            border-bottom: 1px solid #fff !important;
            color: #fff;
	}
.nav>a.active {
background-color : white !important;
color: black;
}

        .nav>a:hover {
            background-color: none !important;
        }

        .in-content {
            border: 1px solid #ccc;
            padding: 40px;
            height: auto;
        }

        .form-check-inline {
            display: inline-flex;
            align-items: center;
        }

        .ml-2 {
            margin-left: 10px;
        }

        .lh-25 {
            line-height: 25px;
        }

        .mt-4 {
            margin-top: 20px;
        }
</style>
</head>
<body>
<?php
if (!isset($_REQUEST['id']) || $_REQUEST['id'] == '') {?>

	<div class="row col-md-offset-3" onclick="get_pre_value()"><button class="btn btn-primary">Get Most recent Value</button></div>
<?php }
?>
<div id="tabs" class="row">
        <div class="col-md-3" style="padding-left:0px;width:300px;min-height:1200px;">
            <nav class="nav flex-column" style="min-height:762px;background-color:#337ab7;">
               <a data-toggle="tab" class="nav-link active" href="#menu1"><i class="fa fa-certificate"></i> ID/Queja Principal</a>
                <a data-toggle="tab" href="#menu2"  class="nav-link" ><i class="fa fa-certificate"></i> Historial de condiciones medicas</a>
                <a data-toggle="tab" href="#menu3" class="nav-link"><i class="fa fa-certificate"></i> Cirugias o Procedimientos</a>
                <a data-toggle="tab" href="#menu4" class="nav-link"><i class="fa fa-certificate"></i> Alergias & Medicamentos</a>
                <a data-toggle="tab" href="#menu5" class="nav-link"><i class="fa fa-certificate"></i> Historial Psiquiatrico</a>
                <a data-toggle="tab" href="#menu6" class="nav-link"><i class="fa fa-certificate"></i> Examen Mental</a>
                <a data-toggle="tab" href="#menu7" class="nav-link"><i class="fa fa-certificate"></i> Impresion Diagnostica</a>
            </nav>
        </div>
<!--<h3>Formato Psiquiátrico</h3>-->
  <div class="col-md-9 enc-form">
  <form class="" name="seguimiento_form" role="form" method="post" action="<?php echo $rootdir; ?>/forms/formato_psiquiatrico/save.php?<?php echo (isset($_REQUEST['id']) && $_REQUEST['id'] != '') ? ('mode=edit&id='.$_REQUEST['id']): 'mode=new';?>" enctype="multipart/form-data">
<?php
if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
	$data = sqlQuery("select * from form_formato_psiquiatrico where id=?",$_REQUEST['id']);
	$data1 = sqlStatement("select * from formato_medicamento1 where form_id=?",array($_REQUEST['id']));
	$drugs_code1 = [];
	while($row=sqlFetchArray($data1)){
array_push($drugs_code1,$row);
	}
$data2 = sqlStatement("select * from formato_medicamento2 where form_id=?",$_REQUEST['id']);
        $drugs_code = [];
        while($row=sqlFetchArray($data2)){
array_push($drugs_code,$row);
        }
} else if(isset($_REQUEST['get_pre_value']) && $_REQUEST['get_pre_value'] != ''){

	$data = sqlQuery("select fp.* from form_formato_psiquiatrico fp left join forms f on f.form_id=fp.id and f.formdir='formato_psiquiatrico' and fp.pid=f.pid where fp.pid=".$pid." and f.deleted=0 order by fp.date desc,fp.id desc limit 1");
        $data1 = sqlStatement("select * from formato_medicamento1 where form_id=?",$data['id']);
        $drugs_code1 = [];
        while($row=sqlFetchArray($data1)){
array_push($drugs_code1,$row);
        }
$data2 = sqlStatement("select * from formato_medicamento2 where form_id=?",$data['id']);
        $drugs_code = [];
        while($row=sqlFetchArray($data2)){
array_push($drugs_code,$row);
        }
}
?>
<!--<input type="hidden" name="mode" value="edit"/>
<input type="hidden" name="id" value="<?php //echo $_REQUEST['id']; ?>"/>-->
<div class="tab-content">
                    <div id="menu1" class="tab-pane active">
                        <div class="col-md-12 in-content">
<div class="form-group col-md-12"></div>
<div class="form-group col-md-12">
      <label class="form-check-label" for="gridCheck">
       Razon para solicitar servicios
      </label>
      <textarea class="col-md-12" rows="3" name="razon_para"><?php echo $data['razon_para']; ?></textarea>
  </div>
<!--<div class="form-group col-md-12">
      <label class="form-check-label" for="gridCheck">
       Evaluacion Psiquiatrica
      </label>
      <textarea class="col-md-12" rows="3" name="eval_psiquiatrica"><?php //echo $data['eval_psiquiatrica']; ?></textarea>
  </div>-->
      <input class="col-md-12" rows="3" type="hidden" name="eval_psiquiatrica" value="<?php echo $data['eval_psiquiatrica']; ?>">
<div class="form-group col-md-12">
      <label class="form-check-label" for="gridCheck">
       Cuestionario sobre enfermedad presente
      </label>
      <textarea class="col-md-12" rows="3" name="cues_sobre"><?php echo $data['cues_sobre']; ?></textarea>
  </div>
<div class="form-group col-md-12">
      <label class="form-check-label" for="gridCheck">
       Nombre de Informante principal
      </label>
      <textarea class="col-md-12" rows="3" name="nombre_de"><?php echo $data['nombre_de']; ?></textarea>
  </div>
<div class="form-group col-md-12"></div>
<div class="form-group col-md-12">
      <label class="form-check-label col-md-3" for="gridCheck">
       Relacion con el paciente
      </label><div class="col-md-9" style="display:flex">
      <select class="form-control" name="relacion">
<option value="" <?php echo (($data['relacion'] == "") ? "selected" : "")?>>--Select--</option>
      <option value="parte del personal del centro donde reside la paciente" <?php echo (($data['relacion'] == "parte del personal del centro donde reside la paciente") ? "selected" : "")?>>parte del personal del centro donde reside la paciente</option>
      <option value="dueño o gerente del centro donde reside la paciente" <?php echo (($data['relacion'] == "dueño o gerente del centro donde reside la paciente") ? "selected" : "")?>>dueño o gerente del centro donde reside la paciente</option>
      <option value="familiar de la paciente" <?php echo (($data['relacion'] == "familiar de la paciente") ? "selected" : "")?>>familiar de la paciente</option>
      <option value="cuidador(a) de la paciente" <?php echo (($data['relacion'] == "cuidador(a) de la paciente") ? "selected" : "")?>>cuidador(a) de la paciente</option>
      <option value="tutor(a) de la paciente" <?php echo (($data['relacion'] == "tutor(a) de la paciente") ? "selected" : "")?>>tutor(a) de la paciente</option>
      <option value="pareja o cónyuge de la paciente" <?php echo (($data['relacion'] == "pareja o cónyuge de la paciente") ? "selected" : "")?>>pareja o cónyuge de la paciente</option>
      <option value="other" <?php echo (($data['relacion'] == "other") ? "selected" : "")?>>Other</option>
<input name="relacion_other" class="form-control relacion_other" Placeholder="Other" value="<?php echo $data['relacion_other']; ?>">
      
      </select>
</div>
  </div>
<div class="form-group col-md-12">
      <label class="form-check-label" for="gridCheck">
       Fecha de Ingreso a la institucion
      </label>
      <textarea class="col-md-12" rows="3" name="fecha_de"><?php echo $data['fecha_de']; ?></textarea>
  </div>
<div class="form-group col-md-12">
      <label class="form-check-label col-md-3" for="gridCheck">
       Cual fue la razon del ingeso de paciente?
      </label>
<div class="col-md-9" style="display:flex">
<select name="cual_fue" class="form-control">
<option value="" <?php echo (($data['cual_fue'] == "") ? "selected" : "")?>>--Select--</option>
      <option value="para cuido o residencia a largo plazo" <?php echo (($data['cual_fue'] == "para cuido o residencia a largo plazo") ? "selected" : "")?>>para cuido o residencia a largo plazo</option>
      <option value="como parte de su tratamiento de rehabilitación" <?php echo (($data['cual_fue'] == "como parte de su tratamiento de rehabilitación") ? "selected" : "")?>>como parte de su tratamiento de rehabilitación</option>
      <option value="por orden de un tribunal de justicia" <?php echo (($data['cual_fue'] == "por orden de un tribunal de justicia") ? "selected" : "")?>>por orden de un tribunal de justicia</option>
      <option value="other" <?php echo (($data['cual_fue'] == "other") ? "selected" : "")?>>Other</option>
<input name="cual_fue_other" class="form-control cual_fue_other" Placeholder="Other" value="<?php echo $data['cual_fue_other']; ?>">

      </select>
	  </div>
  </div>
<div class="form-group col-md-12">
      <label class="form-check-label col-md-3" for="gridCheck">
       Padece la paciente de alguna condicion mental diagnosticada
oficialmente por un medico o psicologo?
      </label>
<div class="col-md-9" style="display:flex">
<select name="padece_la" class="form-control">
<option value="" <?php echo (($data['padece_la'] == "") ? "selected" : "")?>>--Select--</option>
      <option value="trastorno neurocognitivo mayor" <?php echo (($data['padece_la'] == "trastorno neurocognitivo mayor") ? "selected" : "")?>>trastorno neurocognitivo mayor</option>
      <option value="esquizofrenia" <?php echo (($data['padece_la'] == "esquizofrenia") ? "selected" : "")?>>esquizofrenia</option>
      <option value="trastorno bipolar" <?php echo (($data['padece_la'] == "trastorno bipolar") ? "selected" : "trastorno bipolar")?>>trastorno bipolar</option>
      <option value="trastorno esquizoafectivo" <?php echo (($data['padece_la'] == "trastorno esquizoafectivo") ? "selected" : "")?>>trastorno esquizoafectivo</option>
      <option value="trastono de estrés post traumático" <?php echo (($data['padece_la'] == "trastono de estrés post traumático") ? "selected" : "")?>>trastono de estrés post traumático</option>
      <option value="trastorno de pánico" <?php echo (($data['padece_la'] == "trastorno de pánico") ? "selected" : "")?>>trastorno de pánico</option>
      <option value="trastorno de pánico" <?php echo (($data['padece_la'] == "trastorno de pánico") ? "selected" : "")?>>trastorno de pánico</option>
      <option value="trastorno de ansiedad generalizada" <?php echo (($data['padece_la'] == "trastorno de ansiedad generalizada") ? "selected" : "")?>>trastorno de ansiedad generalizada</option>
      <option value="trastorno por consumo de alcohol o sustancias" <?php echo (($data['padece_la'] == "trastorno por consumo de alcohol o sustancias") ? "selected" : "")?>>trastorno por consumo de alcohol o sustancias</option>
      <option value="other" <?php echo (($data['padece_la'] == "other") ? "selected" : "")?>>Other</option>

      </select>
<input name="padece_la_other" class="form-control padece_la_other" Placeholder="Other" value="<?php echo $data['padece_la_other']; ?>">
</div>
  </div>
<div class="form-group col-md-12">
      <label class="form-check-label col-md-3" for="gridCheck">
       Por cuanto tiempo?
      </label>
<div class="col-md-9" style="display:flex;">
<input type="number" name="por_cuanto_days" class="form-control" Placeholder="Quantity" value="<?php echo $data['por_cuanto_days']; ?>">
<select name="por_cuanto" class="form-control">
<option value="" <?php echo (($data['por_cuanto'] == "") ? "selected" : "")?>>--Select--</option>
      <option value="día(s)" <?php echo (($data['por_cuanto'] == "día(s)") ? "selected" : "")?>>día(s)</option>
      <option value="semana(s)" <?php echo (($data['por_cuanto'] == "semana(s)") ? "selected" : "")?>>semana(s)</option>
      <option value="mes(es)" <?php echo (($data['por_cuanto'] == "mes(es)") ? "selected" : "")?>>mes(es)</option>
      <option value="año(s)" <?php echo (($data['por_cuanto'] == "año(s)") ? "selected" : "")?>>año(s)</option>
      <option value="other" <?php echo (($data['por_cuanto'] == "other") ? "selected" : "")?>>Other</option>
<input name="por_cuanto_other" class="form-control por_cuanto_other" Placeholder="Other" value="<?php echo $data['por_cuanto_other']; ?>">

      </select>
	  </div>
  </div>
<div class="form-group col-md-12">
      <label class="form-check-label col-md-3" for="gridCheck">
       Diagnostico 2
      </label>
<div class="col-md-9" style="display:flex">
<select name="diagnostico_2" class="form-control">
<option value="" <?php echo (($data['diagnostico_2'] == "") ? "selected" : "")?>>--Select--</option>
      <option value="trastorno depresivo mayor" <?php echo (($data['diagnostico_2'] == "trastorno depresivo mayor") ? "selected" : "")?>>trastorno depresivo mayor</option>
      <option value="trastorno neurocognitivo mayor" <?php echo (($data['diagnostico_2'] == "trastorno neurocognitivo mayor") ? "selected" : "")?>>trastorno neurocognitivo mayor</option>
      <option value="esquizofrenia" <?php echo (($data['diagnostico_2'] == "esquizofrenia") ? "selected" : "")?>>esquizofrenia</option>
      <option value="trastorno bipolar" <?php echo (($data['diagnostico_2'] == "trastorno bipolar") ? "selected" : "")?>>trastorno bipolar</option>
      <option value="trastorno esquizoafectivo" <?php echo (($data['diagnostico_2'] == "trastorno esquizoafectivo") ? "selected" : "")?>>trastorno esquizoafectivo</option>
      <option value="trastono de estrés post traumático" <?php echo (($data['diagnostico_2'] == "trastono de estrés post traumático") ? "selected" : "")?>>trastono de estrés post traumático</option>
      <option value="trastorno de pánico" <?php echo (($data['diagnostico_2'] == "trastorno de pánico") ? "selected" : "")?>>trastorno de pánico</option>
      <option value="trastorno de ansiedad generalizada" <?php echo (($data['diagnostico_2'] == "trastorno de ansiedad generalizada") ? "selected" : "")?>>trastorno de ansiedad generalizada</option>
      <option value="trastorno por consumo de alcohol o sustancias" <?php echo (($data['diagnostico_2'] == "trastorno por consumo de alcohol o sustancias") ? "selected" : "")?>>trastorno por consumo de alcohol o sustancias</option>
      <option value="other" <?php echo (($data['diagnostico_2'] == "other") ? "selected" : "")?>>Other</option>
<input name="diagnostico_2_other" class="form-control diagnostico_2_other" Placeholder="Other" value="<?php echo $data['diagnostico_2_other']; ?>">
      </select>
	  </div>
      <!--<input class="col-md-12" name="diagnostico_2" onclick="sel_diagnosis('diagnostico_2')" value="<?php echo $data['diagnostico_2']; ?>" />-->
  </div>
<div class="form-group col-md-12">
      <label class="form-check-label col-md-3" for="gridCheck">
       Diagnostico 3
      </label>
<div class="col-md-9" style="display:flex">
<select name="diagnostico_3" class="form-control">
<option value="" <?php echo (($data['diagnostico_3'] == "") ? "selected" : "")?>>--Select--</option>
      <option value="trastorno depresivo mayor" <?php echo (($data['diagnostico_3'] == "trastorno depresivo mayor") ? "selected" : "")?>>trastorno depresivo mayor</option>
      <option value="trastorno neurocognitivo mayor" <?php echo (($data['diagnostico_3'] == "trastorno neurocognitivo mayor") ? "selected" : "")?>>trastorno neurocognitivo mayor</option>
      <option value="esquizofrenia" <?php echo (($data['diagnostico_3'] == "esquizofrenia") ? "selected" : "")?>>esquizofrenia</option>
      <option value="trastorno bipolar" <?php echo (($data['diagnostico_3'] == "trastorno bipolar") ? "selected" : "")?>>trastorno bipolar</option>
      <option value="trastorno esquizoafectivo" <?php echo (($data['diagnostico_3'] == "trastorno esquizoafectivo") ? "selected" : "")?>>trastorno esquizoafectivo</option>
      <option value="trastono de estrés post traumático" <?php echo (($data['diagnostico_3'] == "trastono de estrés post traumático") ? "selected" : "")?>>trastono de estrés post traumático</option>
      <option value="trastorno de pánico" <?php echo (($data['diagnostico_3'] == "trastorno de pánico") ? "selected" : "")?>>trastorno de pánico</option>
      <option value="trastorno de ansiedad generalizada" <?php echo (($data['diagnostico_3'] == "trastorno de ansiedad generalizada") ? "selected" : "")?>>trastorno de ansiedad generalizada</option>
      <option value="trastorno por consumo de alcohol o sustancias" <?php echo (($data['diagnostico_3'] == "trastorno por consumo de alcohol o sustancias") ? "selected" : "")?>>trastorno por consumo de alcohol o sustancias</option>
      <option value="other" <?php echo (($data['diagnostico_2'] == "other") ? "selected" : "")?>>Other</option>
<input name="diagnostico_3_other" class="form-control diagnostico_3_other" Placeholder="Other" value="<?php echo $data['diagnostico_3_other']; ?>">
      </select>
          </div>
      <!--<input class="col-md-12" name="diagnostico_3" onclick="sel_diagnosis('diagnostico_3')" value="<?php echo $data['diagnostico_3']; ?>"/>-->
  </div>
<div class="form-group col-md-12">
      <label class="form-check-label col-md-3" for="gridCheck">
       Diagnostico 4
      </label>
<div class="col-md-9" style="display:flex">
<select name="diagnostico_4" class="form-control">
<option value="" <?php echo (($data['diagnostico_4'] == "") ? "selected" : "")?>>--Select--</option>
      <option value="trastorno depresivo mayor" <?php echo (($data['diagnostico_4'] == "trastorno depresivo mayor") ? "selected" : "")?>>trastorno depresivo mayor</option>
      <option value="trastorno neurocognitivo mayor" <?php echo (($data['diagnostico_4'] == "trastorno neurocognitivo mayor") ? "selected" : "")?>>trastorno neurocognitivo mayor</option>
      <option value="esquizofrenia" <?php echo (($data['diagnostico_4'] == "esquizofrenia") ? "selected" : "")?>>esquizofrenia</option>
      <option value="trastorno bipolar" <?php echo (($data['diagnostico_4'] == "trastorno bipolar") ? "selected" : "")?>>trastorno bipolar</option>
      <option value="trastorno esquizoafectivo" <?php echo (($data['diagnostico_4'] == "trastorno esquizoafectivo") ? "selected" : "")?>>trastorno esquizoafectivo</option>
      <option value="trastono de estrés post traumático" <?php echo (($data['diagnostico_4'] == "trastono de estrés post traumático") ? "selected" : "")?>>trastono de estrés post traumático</option>
      <option value="trastorno de pánico" <?php echo (($data['diagnostico_4'] == "trastorno de pánico") ? "selected" : "")?>>trastorno de pánico</option>
      <option value="trastorno de ansiedad generalizada" <?php echo (($data['diagnostico_4'] == "trastorno de ansiedad generalizada") ? "selected" : "")?>>trastorno de ansiedad generalizada</option>
      <option value="trastorno por consumo de alcohol o sustancias" <?php echo (($data['diagnostico_4'] == "trastorno por consumo de alcohol o sustancias") ? "selected" : "")?>>trastorno por consumo de alcohol o sustancias</option>
      <option value="other" <?php echo (($data['diagnostico_4'] == "other") ? "selected" : "")?>>Other</option>
<input name="diagnostico_4_other" class="form-control diagnostico_4_other" Placeholder="Other" value="<?php echo $data['diagnostico_4_other']; ?>">
      </select>
          </div>
      <!--<input class="col-md-12" name="diagnostico_4" onclick="sel_diagnosis('diagnostico_4')" value="<?php echo $data['diagnostico']; ?>" />-->
  </div>
<div class="form-group col-md-12">
      <label class="form-check-label col-md-3" for="gridCheck">
       Que cambios recientes ha habido en la conducta o en los sintomas del paciente? (No incluir conductas o sintomas que han sido la norma en la paciente desde siempre o que estan bien controlados con medicamentos psiquiatricos)?
      </label>
<div class="col-md-9" style="display:flex">
<select name="que_cambios" class="form-control">
<option value="" <?php echo (($data['que_cambios'] == "") ? "selected" : "")?>>--Select--</option>
      <option value="parece más confundida" <?php echo (($data['que_cambios'] == "parece más confundida") ? "selected" : "")?>>parece más confundida</option>
      <option value="parece más olvidadiza" <?php echo (($data['que_cambios'] == "parece más olvidadiza") ? "selected" : "")?>>parece más olvidadiza</option>
      <option value="está más desorientada" <?php echo (($data['que_cambios'] == "está más desorientada") ? "selected" : "")?>>está más desorientada</option>
      <option value="ha perdido autonomía para realizar actividades de la vida diaria" <?php echo (($data['que_cambios'] == "ha perdido autonomía para realizar actividades de la vida diaria") ? "selected" : "")?>>ha perdido autonomía para realizar actividades de la vida diaria</option>
      <option value="deambula de un lado a otro" <?php echo (($data['que_cambios'] == "deambula de un lado a otro") ? "selected" : "")?>>deambula de un lado a otro</option>
      <option value="se observa más desanimada o retraída" <?php echo (($data['que_cambios'] == "se observa más desanimada o retraída") ? "selected" : "")?>>se observa más desanimada o retraída</option>
      <option value="se le observa más malhumorada de lo normal" <?php echo (($data['que_cambios'] == "se le observa más malhumorada de lo normal") ? "selected" : "")?>>se le observa más malhumorada de lo normal</option>
      <option value="other" <?php echo (($data['que_cambios'] == "other") ? "selected" : "")?>>Other</option>
<input name="que_cambios_other" class="form-control que_cambios_other" Placeholder="Other" value="<?php echo $data['que_cambios_other']; ?>">

      </select>
	  </div>

  </div>
<div class="form-group col-md-12">
      <label class="form-check-label col-md-3" for="gridCheck">
       Por cuanto tiempo?
      </label>
<div class="col-md-9" style="display:flex;">
<input type="number" name="por_cuanto_que_days" class="form-control" Placeholder="Quantity" value="<?php echo $data['por_cuanto_que_days']; ?>">
<select name="por_cuanto_que" class="form-control">
<option value="" <?php echo (($data['por_cuanto_que'] == "") ? "selected" : "")?>>--Select--</option>
      <option value="día(s)" <?php echo (($data['por_cuanto_que'] == "día(s)") ? "selected" : "")?>>día(s)</option>
      <option value="semana(s)" <?php echo (($data['por_cuanto_que'] == "semana(s)") ? "selected" : "")?>>semana(s)</option>
      <option value="mes(es)" <?php echo (($data['por_cuanto_que'] == "mes(es)") ? "selected" : "")?>>mes(es)</option>
      <option value="año(s)" <?php echo (($data['por_cuanto_que'] == "año(s)") ? "selected" : "")?>>año(s)</option>
      <option value="other" <?php echo (($data['por_cuanto_que'] == "other") ? "selected" : "")?>>Other</option>
<input name="por_cuanto_que_days_other" class="form-control por_cuanto_que_days_other" Placeholder="Other" value="<?php echo $data['por_cuanto_que_days_other']; ?>">

      </select>
          </div>
  </div>
</div>
</div>
<div id="menu2" class="tab-pane">
                        <div class="col-md-12 in-content">
<div class="form-row">
<h5>Historial de condiciones medicas Generales:</h5>
<div class="form-check col-md-12">
      <input type="checkbox" name="his_ninguna" value="ninguna" <?php echo (($data['his_ninguna'] =="ninguna") ? "checked" : "");?>>
      <label for="inputPassword4">Ninguna </label><br>
      <input type="checkbox" name="his_derrames" value="derrames" <?php echo (($data['his_derrames'] == "derrames") ? "checked" :"");?>>
      <label for="inputEmail4">Derrames Cerebraies</label><br>
      <input type="checkbox" name="his_epilepsia" value="epilepsia" <?php echo (($data['his_epilepsia'] == "epilepsia") ?"checked":"");?>>
      <label for="inputPassword4">Epilepsia </label><br>
      <input type="checkbox" name="his_enfermedad" value="enfermedad" <?php echo (($data['his_enfermedad']=="enfermedad")?"checked":"");?>>
      <label for="inputPassword4">Enfermedad de parkinson </label><br>
<input type="checkbox" name="his_senil" value="senil" <?php echo (($data['his_senil']=="senil")?"checked":"");?>>
      <label for="inputPassword4">Demencia Senil</label><br>
<input type="checkbox" name="his_alzheimor" value="alzheimor" <?php echo (($data['his_alzheimor']=="alzheimor")?"checked":"");?>>
      <label for="inputPassword4">Enfermedad de Alzheimer</label><br>
<input type="checkbox" name="his_vascular" value="vascular" <?php echo (($data['his_vascular']=="vascular")?"checked":"");?>>
      <label for="inputPassword4">Demencia Vascular</label><br>
<input type="checkbox" name="his_hipotiro" value="hipotiro" <?php echo (($data['his_hipotiro']=="hipotiro")?"checked":"");?>>
      <label for="inputPassword4">Hipotiroidismo</label><br>
<input type="checkbox" name="his_diabetes" value="diabetes" <?php echo (($data['his_diabetes']=="diabetes")?"checked":"");?>>
      <label for="inputPassword4">Diabetes</label>
<br>
<input type="checkbox" name="his_cardiaca" value="cardiaca" <?php echo (($data['his_cardiaca']=="cardiaca")?"checked":"");?>>
      <label for="inputPassword4">Enfermedad Cardiaca</label><br>
<input type="checkbox" name="his_arterial" value="arterial" <?php echo (($data['his_arterial']=="arterial")?"checked":"");?>>
      <label for="inputPassword4">Hipertension Arterial</label><br>
<input type="checkbox" name="his_altos" value="altos" <?php echo (($data['his_altos']=="altos")?"checked":"");?>>
      <label for="inputPassword4">Colesterol o Trigliceridos Altos</label><br>
<input type="checkbox" name="his_asma" value="asma" <?php echo (($data['his_asma']=="asma")?"checked":"");?>>
      <label for="inputPassword4">Asma Bronquial</label><br>
<input type="checkbox" name="his_enfisema" value="enfisema" <?php echo (($data['his_enfisema']=="enfisema")?"checked":"");?>>
      <label for="inputPassword4">Enfisema</label><br>
<input type="checkbox" name="his_copd" value="copd" <?php echo (($data['his_copd']=="copd")?"checked":"");?>>
      <label for="inputPassword4">COPD</label><br>
<input type="checkbox" name="his_reflujo" value="reflujo" <?php echo (($data['his_reflujo']=="reflujo")?"checked":"");?>>
      <label for="inputPassword4">Reflujo o Gastritis</label><br>
<input type="checkbox" name="his_artritis" value="artritis" <?php echo (($data['his_artritis']=="artritis")?"checked":"");?>>
      <label for="inputPassword4">Artritis</label><br>
<input type="checkbox" name="his_osteo" value="osteo" <?php echo (($data['his_osteo']=="osteo")?"checked":"");?>>
      <label for="inputPassword4">Osteoporosis</label><br>
<input type="checkbox" name="his_fibro" value="fibro" <?php echo (($data['his_fibro']=="fibro")?"checked":"");?>>
      <label for="inputPassword4">Fibromialgia</label><br>
<input type="checkbox" name="his_neuro" value="neuro" <?php echo (($data['his_neuro']=="neuro")?"checked":"");?>>
      <label for="inputPassword4">Neuropatia</label><br>
<input type="checkbox" name="his_discos" value="discos" <?php echo (($data['his_discos']=="discos")?"checked":"");?>>
      <label for="inputPassword4">Discos Herniados</label><br>
<input type="checkbox" name="his_discos" value="other" <?php echo (($data['his_discos']=="other")?"checked":"");?>>
      <label for="inputPassword4">Other</label>
    </div>
</div>

<div class="form-group col-md-12"></div>
<div class="form-group col-md-12">
      <label class="form-check-label" for="gridCheck">
<!--       Favor de no escribir debajo de la linea roja -->
      Other
      </label>
      <textarea class="col-md-12" rows="3" name="favor_de"><?php echo $data['favor_de']; ?></textarea>
  </div>
</div>
</div>
<div id="menu3" class="tab-pane">
                        <div class="col-md-12 in-content">
<div class="form-row">
<h5>Cirugias o Procedimientos:</h5>
<div class="form-check col-md-12">
      <input type="checkbox" name="c_ninguna" value="ninguna" <?php echo (($data['c_ninguna'] =="ninguna") ? "checked" : "");?>>
      <label for="inputPassword4">Ninguna </label><br>
      <input type="checkbox" name="c_cabg" value="cabg" <?php echo (($data['c_cabg'] == "cabg") ? "checked" :"");?>>
      <label for="inputEmail4">Revascularizacion Coronaria (CABG)</label><br>
      <input type="checkbox" name="c_impde" value="impde" <?php echo (($data['c_impde'] == "impde") ?"checked":"");?>>
      <label for="inputPassword4">Implantacion de Marcapaso</label><br>
 <input type="checkbox" name="c_imppro" value="imppro" <?php echo (($data['c_imppro'] == "imppro") ?"checked":"");?>>
      <label for="inputPassword4">Implantacion de protesis</label><br>
 <input type="checkbox" name="c_apende" value="apende" <?php echo (($data['c_apende'] == "apende") ?"checked":"");?>>
      <label for="inputPassword4">Apendectomia</label><br>
 <input type="checkbox" name="c_coli" value="coli" <?php echo (($data['c_coli'] == "coli") ?"checked":"");?>>
      <label for="inputPassword4">Colicistectomia</label><br>
 <input type="checkbox" name="c_herni" value="herni" <?php echo (($data['c_herni'] == "herni") ?"checked":"");?>>
      <label for="inputPassword4">Herniorrafia</label><br>
 <input type="checkbox" name="c_amp" value="amp" <?php echo (($data['c_amp'] == "amp") ?"checked":"");?>>
      <label for="inputPassword4">Amputacion de Extremidad</label><br>
 <input type="checkbox" name="c_cir" value="cir" <?php echo (($data['c_cir'] == "cir") ?"checked":"");?>>
      <label for="inputPassword4">Cirugia de Cadera</label><br>
 <input type="checkbox" name="c_cir" value="other" <?php echo (($data['c_cir'] == "other") ?"checked":"");?>>
      <label for="inputPassword4">Other</label>
</div>

<div class="form-group col-md-12"></div>
<div class="form-group col-md-12">
      <label class="form-check-label" for="gridCheck">
       <!--Favor de no escribir debajo de la linea roja-->
     Other
      </label>
      <textarea class="col-md-12" rows="3" name="favor_de_no"><?php echo $data['favor_de_no']; ?></textarea>
  </div>
</div>
</div></div>
<div id="menu4" class="tab-pane">
			<div class="col-md-12 in-content">
<div>
<div class="form-group col-md-12"></div>
<div class="form-group col-md-12">
      <label class="form-check-label" for="gridCheck">
       Alergias
      </label>
      <textarea class="col-md-12 form-control" rows="3" name="alergias"><?php echo $data['alergias']; ?></textarea>
  </div>
<div class="form-group col-md-12"></div>
<input type="hidden" name="prescription1_delete[]" id="prescription1_delete" value="">
                      <table class='table'>
                        <thead>
                            <tr>
                              <th>Medicamento</th>
                              <th>Dosis</th>
                              <th>Unidad</th>
                              <th>Cantidad</th>
                              <!--<th>Via</th>-->
                              <th>Via</th>
                              <th>Frecuencia</th>
                              <!--<th>Refill</th>-->
                            </tr>
                      </tr>
                      </thead>

                      <?php if (!empty($drugs_code1)) { ?>
                        <tbody class="table mainrow_drug1">
 <?php foreach ($drugs_code1 as $key => $dc) { ?>
                            <!-- <div class='row'>
                              <div class='col-md-12' style='padding-bottom:10px;'> -->
                                <tr class="rowdrug_1 first_primary">
                                  <td><input class="drug_align protoautosuggest ui-autocomplete-input drug_code_search" name="prescription1[<?php echo $key; ?>][medicamento]" placeholder="Medicamento" autocomplete="off" type="text" value='<?php echo $dc['medicamento'] ?>'>
                                    <input class="drug_code" name="prescription1[<?php echo $key; ?>][drug_code]" type="hidden" value="<?php echo $dc['drug_code'] ?>">
                                  <input class="id" type="hidden" name="prescription1[<?php echo $key; ?>][id]" value="<?php echo $dc['id'] ?>">
                                    <input class="prescription1_id" name="prescription1_id" type="hidden" value="<?php echo $dc['id'] ?>"></td>
                                  <td><input class="drug_concentration drug_align" name="prescription1[<?php echo $key; ?>][dosis]" value="<?php echo $dc['dosis']; ?>"></td>
                                  <td style="display:flex"><input class="drug_concentration drug_align form-control" type="hidden" name="prescription1[<?php echo $key; ?>][size]" value="<?php echo $dc['size']; ?>">
				  
<select class="form-control" name="prescription1[<?php echo $key; ?>][unit]" id="unit">
<option label=" " value="0"> </option>
<option label="mg" value="1" <?php echo (($dc['unit'] == '1') ? "selected" : "") ?> >mg</option>
<!--<option label="mg/1cc" value="2"  <?php //echo (($dc['unit'] == '2') ? "selected" : "") ?>>mg/1cc</option>
<option label="mg/2cc" value="3"  <?php //echo (($dc['unit'] == '3') ? "selected" : "") ?>>mg/2cc</option>
<option label="mg/3cc" value="4"  <?php //echo (($dc['unit'] == '4') ? "selected" : "") ?>>mg/3cc</option>
<option label="mg/4cc" value="5"  <?php //echo (($dc['unit'] == '5') ? "selected" : "") ?>>mg/4cc</option>
<option label="mg/5cc" value="6"  <?php //echo (($dc['unit'] == '6') ? "selected" : "") ?>>mg/5cc</option>-->
<option label="mcg" value="7" <?php echo (($dc['unit'] == '7') ? "selected" : "") ?>>mcg</option>
<option label="grams" value="8"  <?php echo (($dc['unit'] == '8') ? "selected" : "") ?>>grams</option>
<option label="mL" value="9"  <?php echo (($dc['unit'] == '9') ? "selected" : "") ?>>mL</option>
</select>
</td>
<td>
    <input class="form-control" name="prescription1[<?php echo $key; ?>][amount]" size="6" value="<?php echo $dc['amount']; ?>">
</td>
<!--<td style="display:flex;">
    <select class="form-control" name="prescription1[<?php echo $key; ?>][form]">
<option label=" " value="0" <?php echo (($dc['form'] == '0') ? "selected" : "") ?>> </option>
<option label="suspension" value="1" <?php echo (($dc['form'] == '1') ? "selected" : "") ?>>suspension</option>
<option label="tablet" value="2" <?php echo (($dc['form'] == '2') ? "selected" : "") ?>>tablet</option>
<option label="capsule" value="3" <?php echo (($dc['form'] == '3') ? "selected" : "") ?>>capsule</option>
<option label="solution" value="4" <?php echo (($dc['form'] == '4') ? "selected" : "") ?>>solution</option>
<option label="tsp" value="5" <?php echo (($dc['form'] == '5') ? "selected" : "") ?>>tsp</option>
<option label="ml" value="6" <?php echo (($dc['form'] == '6') ? "selected" : "") ?>>ml</option>
<option label="units" value="7" <?php echo (($dc['form'] == '7') ? "selected" : "") ?>>units</option>
<option label="inhalations" value="8" <?php echo (($dc['form'] == '8') ? "selected" : "") ?>>inhalations</option>
<option label="gtts(drops)" value="9" <?php echo (($dc['form'] == '9') ? "selected" : "") ?>>gtts(drops)</option>
<option label="cream" value="10" <?php echo (($dc['form'] == '10') ? "selected" : "") ?>>cream</option>
<option label="ointment" value="11" <?php echo (($dc['form'] == '11') ? "selected" : "") ?>>ointment</option>
<option label="puff" value="12" <?php echo (($dc['form'] == '12') ? "selected" : "") ?>>puff</option>
</select></td>--><td>
    <select class="form-control" name="prescription1[<?php echo $key; ?>][route]">
<option label=" " value="0" <?php echo (($dc['route'] == '0') ? "selected" : "") ?>> </option>
<option label="Per Oris" value="1" <?php echo (($dc['route'] == '1') ? "selected" : "") ?>>Per Oris</option>
<option label="Per Rectum" value="2" <?php echo (($dc['route'] == '2') ? "selected" : "") ?>>Per Rectum</option>
<option label="To Skin" value="3" <?php echo (($dc['route'] == '3') ? "selected" : "") ?>>To Skin</option>
<option label="To Affected Area" value="4" <?php echo (($dc['route'] == '4') ? "selected" : "") ?>>To Affected Area</option>
<option label="Sublingual" value="5" <?php echo (($dc['route'] == '5') ? "selected" : "") ?>>Sublingual</option>
<option label="OS" value="6" <?php echo (($dc['route'] == '6') ? "selected" : "") ?>>OS</option>
<option label="OD" value="7" <?php echo (($dc['route'] == '7') ? "selected" : "") ?>>OD</option>
<option label="OU" value="8" <?php echo (($dc['route'] == '8') ? "selected" : "") ?>>OU</option>
<option label="SQ" value="9" <?php echo (($dc['route'] == '9') ? "selected" : "") ?>>SQ</option>
<option label="IM" value="10" <?php echo (($dc['route'] == '10') ? "selected" : "") ?>>IM</option>
<option label="IV" value="11" <?php echo (($dc['route'] == '11') ? "selected" : "") ?>>IV</option>
<option label="Per Nostril" value="12" <?php echo (($dc['route'] == '12') ? "selected" : "") ?>>Per Nostril</option>
<option label="Both Ears" value="13" <?php echo (($dc['route'] == '13') ? "selected" : "") ?>>Both Ears</option>
<option label="Left Ear" value="14" <?php echo (($dc['route'] == '14') ? "selected" : "") ?>>Left Ear</option>
<option label="Right Ear" value="15" <?php echo (($dc['route'] == '15') ? "selected" : "") ?>>Right Ear</option>
<option label="Inhale" value="inhale" <?php echo (($dc['route'] == 'inhale') ? "selected" : "") ?>>Inhale</option>
<option label="Intradermal" value="intradermal" <?php echo (($dc['route'] == 'intradermal') ? "selected" : "") ?>>Intradermal</option>
<option label="Other/Miscellaneous" value="other" <?php echo (($dc['route'] == 'other') ? "selected" : "") ?>>Other/Miscellaneous</option>
<option label="Transdermal" value="transdermal" <?php echo (($dc['route'] == 'transdermal') ? "selected" : "") ?>>Transdermal</option>
<option label="Intramuscular" value="intramuscular" <?php echo (($dc['route'] == 'intramuscular') ? "selected" : "") ?>>Intramuscular</option>
</select>
</td>
<td>
    <select class="form-control interval" name="prescription1[<?php echo $key; ?>][interval]">
<option label=" " value="0" <?php echo (($dc['interval'] == '0') ? "selected" : "") ?>> </option>
<option label="b.i.d." value="1" <?php echo (($dc['interval'] == '1') ? "selected" : "") ?>>b.i.d.</option>
<option label="t.i.d." value="2" <?php echo (($dc['interval'] == '2') ? "selected" : "") ?>>t.i.d.</option>
<option label="q.i.d." value="3" <?php echo (($dc['interval'] == '3') ? "selected" : "") ?>>q.i.d.</option>
<option label="q.3h" value="4" <?php echo (($dc['interval'] == '4') ? "selected" : "") ?>>q.3h</option>
<option label="q.4h" value="5" <?php echo (($dc['interval'] == '5') ? "selected" : "") ?>>q.4h</option>
<option label="q.5h" value="6" <?php echo (($dc['interval'] == '6') ? "selected" : "") ?>>q.5h</option>
<option label="q.6h" value="7" <?php echo (($dc['interval'] == '7') ? "selected" : "") ?>>q.6h</option>
<option label="q.8h" value="8" <?php echo (($dc['interval'] == '8') ? "selected" : "") ?>>q.8h</option>
<option label="q.d." value="9" <?php echo (($dc['interval'] == '9') ? "selected" : "") ?>>q.d.</option>
<option label="a.c." value="10" <?php echo (($dc['interval'] == '10') ? "selected" : "") ?>>a.c.</option>
<option label="p.c." value="11" <?php echo (($dc['interval'] == '11') ? "selected" : "") ?>>p.c.</option>
<option label="a.m." value="12" <?php echo (($dc['interval'] == '12') ? "selected" : "") ?>>a.m.</option>
<option label="p.m." value="13" <?php echo (($dc['interval'] == '13') ? "selected" : "") ?>>p.m.</option>
<option label="ante" value="14" <?php echo (($dc['interval'] == '14') ? "selected" : "") ?>>ante</option>
<option label="h" value="15" <?php echo (($dc['interval'] == '15') ? "selected" : "") ?> >h</option>
<option label="h.s." value="16" <?php echo (($dc['interval'] == '16') ? "selected" : "") ?>>h.s.</option>
<option label="p.r.n." value="17" <?php echo (($dc['interval'] == '17') ? "selected" : "") ?>>p.r.n.</option>
<option label="stat" value="18" <?php echo (($dc['interval'] == '18') ? "selected" : "") ?>>stat</option>
<option label="other" value="19" <?php echo (($dc['interval'] == '19') ? "selected" : "") ?>>Other</option>
<input type="text" class="form-control <?php echo (($dc['interval'] == '19') ? '' :'freqother-hidden'); ?>" name="prescription1[<?php echo $key; ?>][freq_other]" value="<?php echo $dc['freq_other']; ?>"/>
</select>
</td>
<!--<td class="row">
<select class="form-control" name="prescription1[<?php echo $key; ?>][refills]">
<option label="00" value="0" <?php echo (($dc['refills'] == '0')? "selected":"")?>>00</option>
<option label="01" value="1" <?php echo (($dc['refills'] == '1')? "selected":"")?>>01</option>
<option label="02" value="2"  <?php echo (($dc['refills'] == '2')? "selected":"")?>>02</option>
<option label="03" value="3"  <?php echo (($dc['refills'] == '3')? "selected":"")?>>03</option>
<option label="04" value="4"  <?php echo (($dc['refills'] == '4')? "selected":"")?>>04</option>
<option label="05" value="5"  <?php echo (($dc['refills'] == '5')? "selected":"")?>>05</option>
<option label="06" value="6"  <?php echo (($dc['refills'] == '6')? "selected":"")?>>06</option>
<option label="07" value="7"  <?php echo (($dc['refills'] == '7')? "selected":"")?>>07</option>
<option label="08" value="8"  <?php echo (($dc['refills'] == '8')? "selected":"")?>>08</option>
<option label="09" value="9"  <?php echo (($dc['refills'] == '9')? "selected":"")?>>09</option>
<option label="10" value="10"  <?php echo (($dc['refills'] == '10')? "selected":"")?>>10</option>
<option label="11" value="11"  <?php echo (($dc['refills'] == '11')? "selected":"")?>>11</option>
<option label="12" value="12"  <?php echo (($dc['refills'] == '12')? "selected":"")?>>12</option>
<option label="13" value="13"  <?php echo (($dc['refills'] == '13')? "selected":"")?>>13</option>
<option label="14" value="14"  <?php echo (($dc['refills'] == '14')? "selected":"")?>>14</option>
<option label="15" value="15"  <?php echo (($dc['refills'] == '15')? "selected":"")?>>15</option>
<option label="16" value="16"  <?php echo (($dc['refills'] == '16')? "selected":"")?>>16</option>
<option label="17" value="17"  <?php echo (($dc['refills'] == '17')? "selected":"")?>>17</option>
<option label="18" value="18"  <?php echo (($dc['refills'] == '18')? "selected":"")?>>18</option>
<option label="19" value="19"  <?php echo (($dc['refills'] == '19')? "selected":"")?>>19</option>
<option label="20" value="20"  <?php echo (($dc['refills'] == '20')? "selected":"")?>>20</option>
</select>
</td> -->

<!--                                  <td><select class="drugs_form drug_align" style="text-align:center;" name="prescription[<?php echo $key; ?>][drug_form]">-->
                                      <?php
/*                                      foreach ($drug_interval_list as $k => $value) {
                                        $selected = ($k == $dc['drug_form']) ? 'selected' : '';
                                        echo "<option value='$k' $selected>" . $value . "</option>";
}*/
                                      ?>
<!--</select></td>
                                  <td><textarea class="special_instructions" style="margin-right:10px; height:60px;margin-top:5px;" name="prescription[<?php echo $key; ?>][special_instructions]"><?php echo $dc['special_instructions']; ?></textarea></td>-->
                                  <td>
                                  <div class="form-group" style="display:flex">
                                      <a href="javascript:void(0);" class="btn btn-primary adddrug1"><span class="fa fa-plus text-white"></span></a>
                                      <a href="javascript:void(0);" class="btn btn-danger rmdrug1"><span class="fa fa-minus text-white"></span></a>
                                    </div>
                                  </td>
                                </tr>
                              <!-- </div>
                            </div> -->
                        <?php } ?>
                        </tbody>
<?php } else { ?>
                        <tbody class="table mainrow_drug1">
						<!-- <div class='row'>
                            <div class="col-md-12" style="padding-bottom:10px;"> -->
                              <tr class="rowdrug_1 first_primary">
                                <td><input class="drug_align protoautosuggest ui-autocomplete-input drug_code_search form-control" name="prescription1[0][medicamento]" placeholder="Medicamento" autocomplete="off" type="text" value=''>
                                <input class="drug_code" name="prescription1[0][drug_code]" type="hidden" value="">
                                <input class="id" type="hidden" name="prescription1[0][id]" value=""></td>
                                <input class="prescription1_id" name="prescription1_id" type="hidden" value=""></td>
                                <td><input class="drug_concentration drug_align form-control" name="prescription1[0][dosis]" value="" placeholder="Dosis"></td>
                                <td style="display:flex;"><input class="form-control" type="hidden" name="prescription1[0][size]" value="" placeholder="Talla">
                                <select class="form-control" name="prescription1[0][unit]" id="unit">
<option label=" " value="0"> </option>
<option label="mg" value="1" selected="selected">mg</option>
<!--<option label="mg/1cc" value="2">mg/1cc</option>
<option label="mg/2cc" value="3">mg/2cc</option>
<option label="mg/3cc" value="4">mg/3cc</option>
<option label="mg/4cc" value="5">mg/4cc</option>
<option label="mg/5cc" value="6">mg/5cc</option>-->
<option label="mcg" value="7">mcg</option>
<option label="grams" value="8">grams</option>
<option label="mL" value="9">mL</option>
</select></td>
				<td>
    <input class="form-control" name="prescription1[0][amount]" size="6">
</td>
<!--<td style="display:flex;">
    <select class="form-control" name="prescription1[0][form]" id="form"><option label=" " value="0"> </option>
<option label="suspension" value="1">suspension</option>
<option label="tablet" value="2">tablet</option>
<option label="capsule" value="3">capsule</option>
<option label="solution" value="4">solution</option>
<option label="tsp" value="5">tsp</option>
<option label="ml" value="6">ml</option>
<option label="units" value="7">units</option>
<option label="inhalations" value="8">inhalations</option>
<option label="gtts(drops)" value="9">gtts(drops)</option>
<option label="cream" value="10">cream</option>
<option label="ointment" value="11">ointment</option>
<option label="puff" value="12">puff</option>
</select></td>--><td>
    <select class="form-control" name="prescription1[0][route]" id="route"><option label=" " value="0"> </option>
<option label="Per Oris" value="1">Per Oris</option>
<option label="Per Rectum" value="2">Per Rectum</option>
<option label="To Skin" value="3">To Skin</option>
<option label="To Affected Area" value="4">To Affected Area</option>
<option label="Sublingual" value="5">Sublingual</option>
<option label="OS" value="6">OS</option>
<option label="OD" value="7">OD</option>
<option label="OU" value="8">OU</option>
<option label="SQ" value="9">SQ</option>
<option label="IM" value="10">IM</option>
<option label="IV" value="11">IV</option>
<option label="Per Nostril" value="12">Per Nostril</option>
<option label="Both Ears" value="13">Both Ears</option>
<option label="Left Ear" value="14">Left Ear</option>
<option label="Right Ear" value="15">Right Ear</option>
<option label="Inhale" value="inhale">Inhale</option>
<option label="Intradermal" value="intradermal">Intradermal</option>
<option label="Other/Miscellaneous" value="other">Other/Miscellaneous</option>
<option label="Transdermal" value="transdermal">Transdermal</option>
<option label="Intramuscular" value="intramuscular">Intramuscular</option>
</select>
</td>
<td>
    <select class="form-control interval" name="prescription1[0][interval]" id="interval"><option label=" " value="0"> </option>
<option label="b.i.d." value="1">b.i.d.</option>
<option label="t.i.d." value="2">t.i.d.</option>
<option label="q.i.d." value="3">q.i.d.</option>
<option label="q.3h" value="4">q.3h</option>
<option label="q.4h" value="5">q.4h</option>
<option label="q.5h" value="6">q.5h</option>
<option label="q.6h" value="7">q.6h</option>
<option label="q.8h" value="8">q.8h</option>
<option label="q.d." value="9">q.d.</option>
<option label="a.c." value="10">a.c.</option>
<option label="p.c." value="11">p.c.</option>
<option label="a.m." value="12">a.m.</option>
<option label="p.m." value="13">p.m.</option>
<option label="ante" value="14">ante</option>
<option label="h" value="15">h</option>
<option label="h.s." value="16">h.s.</option>
<option label="p.r.n." value="17">p.r.n.</option>
<option label="stat" value="18">stat</option>
<option label="other" value="19">Other</option>
<input type="text" class="form-control freqother-hidden" name="prescription1[0][freq_other]" value=""/>
</select>
</td>
<!--<td class="row">
<select class="form-control" name="prescription1[0][refills]">
<option label="00" value="0" selected="selected">00</option>
<option label="01" value="1">01</option>
<option label="02" value="2">02</option>
<option label="03" value="3">03</option>
<option label="04" value="4">04</option>
<option label="05" value="5">05</option>
<option label="06" value="6">06</option>
<option label="07" value="7">07</option>
<option label="08" value="8">08</option>
<option label="09" value="9">09</option>
<option label="10" value="10">10</option>
<option label="11" value="11">11</option>
<option label="12" value="12">12</option>
<option label="13" value="13">13</option>
<option label="14" value="14">14</option>
<option label="15" value="15">15</option>
<option label="16" value="16">16</option>
<option label="17" value="17">17</option>
<option label="18" value="18">18</option>
<option label="19" value="19">19</option>
<option label="20" value="20">20</option>
</select>
</td>-->
                                <!--<td>
                                  <input class='frecuencia' placeholder='Frecuencia' autocomplete="off" name="prescription[0][frecuencia]" value=""></td>
                                  <td><input class="to_date datepicker drug_align" placeholder='M/D/Y' autocomplete="off" name="prescription[0][date_to]" value=""></td>
                                <td><select class="drugs_form drug_align" style="text-align:center;width:170px !important;" name="prescription[0][drug_form]">
                                    <?php
                                    foreach ($drug_interval_list as $kt => $value) {
                                      echo "<option value='$kt'>" . $value . "</option>";
                                    }
                                    ?>
                                  </select></td>
<td><textarea class="special_instructions" style="margin-right:10px; height:60px;width:190px !important;margin-top:5px;" name="prescription[0][special_instructions]"></textarea></td>
                                <td><input class="add_to_medications drug_align" name="prescription[0][add_to_medications]" type="checkbox" value="1"></td>-->
                                <td>
                                  <div class='form-group' style="display:flex">
                                  <a href="javascript:void(0);" class="btn btn-primary adddrug1"><span class="fa fa-plus text-white"></span></a>
                                    <a href="javascript:void(0);" class="btn btn-danger rmdrug1"><span class="fa fa-minus text-white"></span></a>
                                  </div>
                                </td>
                              </tr>
                            <!-- </div>
                          </div> -->
                        </tbody>
                      <?php } ?>
					  </table>
<div class="form-group col-md-12"></div>
</div>
</div>
</div>
<div id="menu5" class="tab-pane">
                        <div class="col-md-12 in-content">
<div>
<h4>Historial Psiquiatrico</h4>
<div class="form-group row">
<div class="col-md-3">
<label for="inputPassword4">Hospitalizaciones Psiquiatricas</label><br>
 <input type="checkbox" name="hos_si" value="si" <?php echo (($data['hos_si'] == "si") ?"checked":"");?>>
      <label for="inputPassword4">Si</label><br>
<input type="checkbox" name="hos_no" value="no" <?php echo (($data['hos_no'] == "no") ?"checked":"");?>>
      <label for="inputPassword4">No &nbsp;&nbsp;</label><br>
<input type="checkbox" name="hos_se" value="se" <?php echo (($data['hos_se'] == "se") ?"checked":"");?>>
      <label for="inputPassword4">SE DESCONOCE &nbsp;&nbsp;</label>
</div><div class="col-md-4">
      <textarea class="form-control" rows="3" name="hos_comm"><?php echo $data['hos_comm']; ?></textarea>
</div>
</div>
<div class="form-group row">
<div class="col-md-3">
<label for="inputPassword4">Intentos Suicidas</label><br>
 <input type="checkbox" name="int_si" value="si" <?php echo (($data['int_si'] == "si") ?"checked":"");?>>
      <label for="inputPassword4">Si</label><br>
<input type="checkbox" name="int_no" value="no" <?php echo (($data['int_no'] == "no") ?"checked":"");?>>
      <label for="inputPassword4">No &nbsp;&nbsp;</label><br>
<input type="checkbox" name="int_se" value="se" <?php echo (($data['int_se'] == "se") ?"checked":"");?>>
      <label for="inputPassword4">SE DESCONOCE &nbsp;&nbsp;</label>
</div><div class="col-md-4">
      <textarea class="form-control" rows="3" name="int_comm"><?php echo $data['int_comm']; ?></textarea>
</div>
</div>
<div class="form-group row">
<div class="col-md-3">
<label for="inputPassword4">Abuso de Sustancias o Alcohol</label><br>
 <input type="checkbox" name="abu_si" value="si" <?php echo (($data['abu_si'] == "si") ?"checked":"");?>>
      <label for="inputPassword4">Si</label><br>
<input type="checkbox" name="abu_no" value="no" <?php echo (($data['abu_no'] == "no") ?"checked":"");?>>
      <label for="inputPassword4">No &nbsp;&nbsp;</label><br>
<input type="checkbox" name="abu_se" value="se" <?php echo (($data['abu_se'] == "se") ?"checked":"");?>>
      <label for="inputPassword4">SE DESCONOCE &nbsp;&nbsp;</label>
</div><div class="col-md-4">
      <textarea class="form-control" rows="3" name="abu_comm"><?php echo $data['abu_comm']; ?></textarea>
</div>
</div>
<div class="form-group row">
<div class="col-md-3">
<label for="inputPassword4">Historial Familiar Psiquiatrico</label><br>
 <input type="checkbox" name="his_si" value="si" <?php echo (($data['his_si'] == "si") ?"checked":"");?>>
      <label for="inputPassword4">Si</label><br>
<input type="checkbox" name="his_no" value="no" <?php echo (($data['his_no'] == "no") ?"checked":"");?>>
      <label for="inputPassword4">No &nbsp;&nbsp;</label><br>
<input type="checkbox" name="his_se" value="se" <?php echo (($data['his_se'] == "se") ?"checked":"");?>>
      <label for="inputPassword4">SE DESCONOCE &nbsp;&nbsp;</label>
</div><div class="col-md-4">
      <textarea class="form-control" rows="3" name="his_comm"><?php echo $data['his_comm']; ?></textarea>
</div>
</div>
<div class="form-group row">
<div class="col-md-3">
<label for="inputPassword4">Historial de Efectos Adversos a Medicamentos</label><br>
 <input type="checkbox" name="hise_si" value="se" <?php echo (($data['hise_si'] == "si") ?"checked":"");?>>
      <label for="inputPassword4">Si</label><br>
<input type="checkbox" name="hise_no" value="no" <?php echo (($data['hise_no'] == "no") ?"checked":"");?>>
      <label for="inputPassword4">No &nbsp;&nbsp;</label><br>
<input type="checkbox" name="hise_se" value="se" <?php echo (($data['hise_se'] == "se") ?"checked":"");?>>
      <label for="inputPassword4">SE DESCONOCE &nbsp;&nbsp;</label>
</div><div class="col-md-4">
      <textarea class="form-control" rows="3" name="hise_comm"><?php echo $data['hise_comm']; ?></textarea>
</div>
</div>
<!--<div class="form-group col-md-12">
<label class="form-check-label" for="gridCheck">
       Comentarios
      </label>
      <textarea class="col-md-12" rows="3" name="hos_comm"><?php //echo $data['hos_comm']; ?></textarea>
</div>-->
</div>
</div>
</div>
<div id="menu6" class="tab-pane">
                        <div class="col-md-12 in-content">
<div>
<h4>Examen Mental</h4>

<div class="form-group col-md-10">
<table class="table">
<tr>
    <td>  <label for="inputEmail4">Apariencia: &nbsp;&nbsp;</label></td>
<td>
<select name="apariencia" class="form-control">
      <option value="" <?php echo (($data['apariencia'] == "") ? "selected" : "")?>>--Select--</option>
      <option value="Buena higiene" <?php echo (($data['apariencia'] == "Buena higiene") ? "selected" : "")?>>Buena higiene</option>
      <option value="Desaliñada" <?php echo (($data['apariencia'] == "Desaliñada") ? "selected" : "")?>>Desaliñada</option>
      <option value="Nítida" <?php echo (($data['apariencia'] == "Nítida") ? "selected" : "")?>>Nítida</option>
      <option value="Normal para la edad" <?php echo (($data['apariencia'] == "Normal para la edad") ? "selected" : "")?>>Normal para la edad</option>
      <option value="other" <?php echo (($data['apariencia'] == "other") ? "selected" : "")?>>Other</option>
      </select>
</td>
<td><textarea class="col-md-12" rows="3" name="apariencia_cmnt"><?php echo $data['apariencia_cmnt']; ?></textarea></td>

</tr>
<tr>
    <td>  <label for="inputEmail4">Actividad Motora: &nbsp;&nbsp;</label></td>
<td><select name="actividadm" class="form-control">
      <option value="" <?php echo (($data['actividadm'] == "") ? "selected" : "")?>>--Select--</option>
      <option value="Retraso Psicomotor" <?php echo (($data['actividadm'] == "Agitada") ? "selected" : "")?>>Agitada</option>
      <option value="Temblorosa" <?php echo (($data['actividadm'] == "Temblorosa") ? "selected" : "")?>>Temblorosa</option>
      <option value="Paciente encamada" <?php echo (($data['actividadm'] == "Paciente encamada") ? "selected" : "")?>>Paciente encamada</option>
      <option value="Paciente en silla de ruedas" <?php echo (($data['actividadm'] == "Paciente en silla de ruedas") ? "selected" : "")?>>Paciente en silla de ruedas</option>
      <option value="Ambula por sí sola" <?php echo (($data['actividadm'] == "Ambula por sí sola") ? "selected" : "")?>>Ambula por sí sola</option>
      <option value="Ambula con andador o bastón" <?php echo (($data['actividadm'] == "Ambula con andador o bastón") ? "selected" : "")?>>Ambula con andador o bastón</option>
      <option value="Ambula con ayuda" <?php echo (($data['actividadm'] == "Ambula con ayuda") ? "selected" : "")?>>Ambula con ayuda</option>
      <option value="other" <?php echo (($data['actividadm'] == "other") ? "selected" : "")?>>Other</option>

      </select>
</td>
<td><textarea class="col-md-12" rows="3" name="actividadm_cmnt"><?php echo $data['actividadm_cmnt']; ?></textarea></td>

</tr>
<tr>
    <td>  <label for="inputEmail4">Actitud: &nbsp;&nbsp;</label></td>
<td>
<select class="form-control" name="actitud">
<option value="" <?php echo (($data['actitud']=="") ? "selected" : "")?>>--Select--</option>
<option value="Cooperadora"  <?php echo (($data['actitud']=="Cooperadora") ? "selected" : "")?>>Cooperadora</option>
<option value="Hostil"  <?php echo (($data['actitud']=="Hostil") ? "selected" : "")?>>Hostil</option>
<option value="Agresiva"  <?php echo (($data['actitud']=="Agresiva") ? "selected" : "")?>>Agresiva</option>
<option value="Reservada"  <?php echo (($data['actitud']=="Reservada") ? "selected" : "")?>>Reservada</option>
<option value="Desconfiada"  <?php echo (($data['actitud']=="Desconfiada") ? "selected" : "")?>>Desconfiada</option>
<option value="Desinteresada"  <?php echo (($data['actitud']=="Desinteresada") ? "selected" : "")?>>Desinteresada</option>
<option value="other"  <?php echo (($data['actitud']=="other") ? "selected" : "")?>>Other</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="actitud_cmnt"><?php echo $data['actitud_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Conducta</label></td>
<td>
<select class="form-control" name="conducta">
<option value="" <?php echo (($data['conducta']=="") ? "selected" : "")?>>--Select--</option>
<option value="Lució tranquila durante evaluación"  <?php echo (($data['conducta']=="Lució tranquila durante evaluación") ? "selected" : "")?>>Lució tranquila durante evaluación</option>
<option value="Lució intranquila durante evaluación"  <?php echo (($data['conducta']=="Lució intranquila durante evaluación") ? "selected" : "")?>>Lució intranquila durante evaluación</option>
<option value="Desorganizada"  <?php echo (($data['conducta']=="Desorganizada") ? "selected" : "")?>>Desorganizada</option>
<option value="No contesta preguntas"  <?php echo (($data['conducta']=="No contesta preguntas") ? "selected" : "")?>>No contesta preguntas</option>
<option value="Emplea lenguaje soez o insultos"  <?php echo (($data['conducta']=="Emplea lenguaje soez o insultos") ? "selected" : "")?>>Emplea lenguaje soez o insultos</option>
<option value="Se limita a emitir quejidos no verbales o a gritar"  <?php echo (($data['conducta']=="Se limita a emitir quejidos no verbales o a gritar") ? "selected" : "")?>>Se limita a emitir quejidos no verbales o a gritar</option>
<option value="Se muestra combativa o con dificultad en controlar impulsos"  <?php echo (($data['conducta']=="Se muestra combativa o con dificultad en controlar impulsos") ? "selected" : "")?>>Se muestra combativa o con dificultad en controlar impulsos</option>
<option value="Hace bromas o comentarios inapropiados"  <?php echo (($data['conducta']=="Hace bromas o comentarios inapropiados") ? "selected" : "")?>>Hace bromas o comentarios inapropiados</option>
<option value="other"  <?php echo (($data['conducta']=="other") ? "selected" : "")?>>Other</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="conducta_cmnt"><?php echo $data['conducta_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Habla</label></td>
<td>
<select class="form-control" name="habia">
<option value="" <?php echo (($data['habia']=="") ? "selected" : "")?>>--Select--</option>
<option value="Clara"  <?php echo (($data['habia']=="Clara") ? "selected" : "")?>>Clara</option>
<option value="Disártrica"  <?php echo (($data['habia']=="Disártrica") ? "selected" : "")?>>Disártrica</option>
<option value="Mínima"  <?php echo (($data['habia']=="Mínima") ? "selected" : "")?>>Mínima</option>
<option value="Se mantiene en silencio"  <?php echo (($data['habia']=="Se mantiene en silencio") ? "selected" : "")?>>Se mantiene en silencio</option>
<option value="Pausada"  <?php echo (($data['habia']=="Pausada") ? "selected" : "")?>>Pausada</option>
<option value="Apresurada"  <?php echo (($data['habia']=="Apresurada") ? "selected" : "")?>>Apresurada</option>
<option value="Murmura"  <?php echo (($data['habia']=="Murmura") ? "selected" : "")?>>Murmura</option>
<option value="Ininteligible"  <?php echo (($data['habia']=="Ininteligible") ? "selected" : "")?>>Ininteligible</option>
<option value="Estropajosa"  <?php echo (($data['habia']=="Estropajosa") ? "selected" : "")?>>Estropajosa</option>
<option value="other"  <?php echo (($data['habia']=="other") ? "selected" : "")?>>Other</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="habia_cmnt"><?php echo $data['habia_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Afecto</label></td>
<td>
<select class="form-control" name="afecto">
<option value="" <?php echo (($data['afecto']=="") ? "selected" : "")?>>--Select--</option>
<option value="Amplio"  <?php echo (($data['afecto']=="Amplio") ? "selected" : "")?>>Amplio</option>
<option value="Restringido"  <?php echo (($data['afecto']=="Restringido") ? "selected" : "")?>>Restringido</option>
<option value="Embotado"  <?php echo (($data['afecto']=="Embotado") ? "selected" : "")?>>Embotado</option>
<option value="Lábil"  <?php echo (($data['afecto']=="Lábil") ? "selected" : "")?>>Lábil</option>
<option value="Congruente con estado de ánimo"  <?php echo (($data['afecto']=="Congruente con estado de ánimo") ? "selected" : "")?>>Congruente con estado de ánimo</option>
<option value="Incongruente con estado de ánimo"  <?php echo (($data['afecto']=="Incongruente con estado de ánimo") ? "selected" : "")?>>Incongruente con estado de ánimo</option>
<option value="other"  <?php echo (($data['afecto']=="other") ? "selected" : "")?>>Other</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="afecto_cmnt"><?php echo $data['afecto_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Talante</label></td>
<td>
<select class="form-control" name="talante">
<option value="" <?php echo (($data['talante']=="") ? "selected" : "")?>>--Select--</option>
<option value="Eutímico"  <?php echo (($data['talante']=="Eutímico") ? "selected" : "")?>>Eutímico</option>
<option value="Deprimido"  <?php echo (($data['talante']=="Deprimido") ? "selected" : "")?>>Deprimido</option>
<option value="Expansivo"  <?php echo (($data['talante']=="Expansivo") ? "selected" : "")?>>Expansivo</option>
<option value="Ansioso"  <?php echo (($data['talante']=="Ansioso") ? "selected" : "")?>>Ansioso</option>
<option value="Paciente no contestó o no se le entendió"  <?php echo (($data['talante']=="Paciente no contestó o no se le entendió") ? "selected" : "")?>>Paciente no contestó o no se le entendió</option>
<option value="other"  <?php echo (($data['talante']=="other") ? "selected" : "")?>>Other</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="talante_cmnt"><?php echo $data['talante_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Percepción</label></td>
<td>
<select class="form-control" name="percepcion">
<option value="" <?php echo (($data['percepcion']=="") ? "selected" : "")?>>--Select--</option>
<option value="Sin disturbios perceptuales"  <?php echo (($data['percepcion']=="Sin disturbios perceptuales") ? "selected" : "")?>>Sin disturbios perceptuales</option>
<option value="Alucinaciones auditivas"  <?php echo (($data['percepcion']=="Alucinaciones auditivas") ? "selected" : "")?>>Alucinaciones auditivas</option>
<option value="Alucinaciones visuales"  <?php echo (($data['percepcion']=="Alucinaciones visuales") ? "selected" : "")?>>Alucinaciones visuales</option>
<option value="Parece responder a estímulos internos"  <?php echo (($data['percepcion']=="Parece responder a estímulos internos") ? "selected" : "")?>>Parece responder a estímulos internos</option>
<option value="Paciente no contestó o no se le entendió"  <?php echo (($data['percepcion']=="Paciente no contestó o no se le entendió") ? "selected" : "")?>>Paciente no contestó o no se le entendió</option>
<option value="other"  <?php echo (($data['percepcion']=="other") ? "selected" : "")?>>Other</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="percepcion_cmnt"><?php echo $data['percepcion_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Contenido de Pensamiento</label></td>
<td>
<select class="form-control">
<option value="" <?php echo (($data['contenido']=="") ? "selected" : "")?>>--Select--</option>
<option value="Ideas delirantes"  <?php echo (($data['contenido']=="Ideas delirantes") ? "selected" : "")?>>Ideas delirantes</option>
<option value="Obsesiones"  <?php echo (($data['contenido']=="Obsesiones") ? "selected" : "")?>>Obsesiones</option>
<option value="Miedos irracionales"  <?php echo (($data['contenido']=="Miedos irracionales") ? "selected" : "")?>>Miedos irracionales</option>
<option value="Gira en torno a preocupaciones ordinarias de su diario vivir"  <?php echo (($data['contenido']=="Gira en torno a preocupaciones ordinarias de su diario vivir") ? "selected" : "")?>>Gira en torno a preocupaciones ordinarias de su diario vivir</option>
<option value="No trae preocupaciones durante la entrevista"  <?php echo (($data['contenido']=="No trae preocupaciones durante la entrevista") ? "selected" : "")?>>No trae preocupaciones durante la entrevista</option>
<option value="Paciente no contestó o no se le entendió"  <?php echo (($data['contenido']=="Paciente no contestó o no se le entendió") ? "selected" : "")?>>Paciente no contestó o no se le entendió</option>
<option value="other"  <?php echo (($data['contenido']=="other") ? "selected" : "")?>>Other</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="contenido_cmnt"><?php echo $data['contenido_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Relevancia de sus Respuestas</label></td>
<td>
<select class="form-control" name="relevancia">
<option value="" <?php echo (($data['relevancia']=="") ? "selected" : "")?>>--Select--</option>
<option value="Respuestas guardan relevancia con las preguntas o temas tratados"  <?php echo (($data['relevancia']=="Respuestas guardan relevancia con las preguntas o temas tratados") ? "selected" : "")?>>Respuestas guardan relevancia con las preguntas o temas tratados</option>
<option value="Por momentos sus respuestas no guardan relevancia con pregunatas o temas tratados"  <?php echo (($data['relevancia']=="Por momentos sus respuestas no guardan relevancia con pregunatas o temas tratados") ? "selected" : "")?>>Por momentos sus respuestas no guardan relevancia con pregunatas o temas tratados</option>
<option value="Respuestas no guardan relevancia con preguntas o temas tratados"  <?php echo (($data['relevancia']=="Respuestas no guardan relevancia con preguntas o temas tratados") ? "selected" : "")?>>Respuestas no guardan relevancia con preguntas o temas tratados</option>
<option value="Paciente no contestó o no se le entendió"  <?php echo (($data['relevancia']=="Paciente no contestó o no se le entendió") ? "selected" : "")?>>Paciente no contestó o no se le entendió</option>
<option value="Paciente no contestó o no se le entendió"  <?php echo (($data['relevancia']=="Paciente no contestó o no se le entendió") ? "selected" : "")?>>Paciente no contestó o no se le entendió</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="relevancia_cmnt"><?php echo $data['relevancia_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Procesamiento de Pensamiento</label></td>
<td>
<select class="form-control" name="proces_de">
<option value="" <?php echo (($data['proces_de']=="") ? "selected" : "")?>>--Select--</option>
<option value="Coherente"  <?php echo (($data['proces_de']=="Coherente") ? "selected" : "")?>>Coherente</option>
<option value="Incoherente"  <?php echo (($data['proces_de']=="Incoherente") ? "selected" : "")?>>Incoherente</option>
<option value="Asociaciones laxas"  <?php echo (($data['proces_de']=="Asociaciones laxas") ? "selected" : "")?>>Asociaciones laxas</option>
<option value="Tangencial"  <?php echo (($data['proces_de']=="Tangencial") ? "selected" : "")?>>Tangencial</option>
<option value="Bloqueos"  <?php echo (($data['proces_de']=="Bloqueos") ? "selected" : "")?>>Bloqueos</option>
<option value="Confabula"  <?php echo (($data['proces_de']=="Confabula") ? "selected" : "")?>>Confabula</option>
<option value="Circunstancial"  <?php echo (($data['proces_de']=="Circunstancial") ? "selected" : "")?>>Circunstancial</option>
<option value="Paciente no contestó o no se le entendió"  <?php echo (($data['proces_de']=="Paciente no contestó o no se le entendió") ? "selected" : "")?>>Paciente no contestó o no se le entendió</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="proces_de_cmnt"><?php echo $data['proces_de_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Nivel de Atención</label></td>
<td>
<select class="form-control" name="nivel">
<option value="" <?php echo (($data['nivel']=="") ? "selected" : "")?>>--Select--</option>
<option value="Centrada en los temas tratados"  <?php echo (($data['nivel']=="Centrada en los temas tratados") ? "selected" : "")?>>Centrada en los temas tratados</option>
<option value="Hipervigilante"  <?php echo (($data['nivel']=="Hipervigilante") ? "selected" : "")?>>Hipervigilante</option>
<option value="Se distrae fácilmente con estímulos irrelevantes"  <?php echo (($data['nivel']=="Se distrae fácilmente con estímulos irrelevantes") ? "selected" : "")?>>Se distrae fácilmente con estímulos irrelevantes</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="nivel_cmnt"><?php echo $data['nivel_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Concentración</label></td>
<td>
<select class="form-control" name="concentracion">
<option value="" <?php echo (($data['concentracion']=="") ? "selected" : "")?>>--Select--</option>
<option value="Adecuada"  <?php echo (($data['concentracion']=="Adecuada") ? "selected" : "")?>>Adecuada</option>
<option value="Disminuida"  <?php echo (($data['concentracion']=="Disminuida") ? "selected" : "")?>>Disminuida</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="concentracion_cmnt"><?php echo $data['concentracion_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Orientación</label></td>
<td>
<select class="form-control" name="orientacion">
<option value="" <?php echo (($data['orientacion']=="") ? "selected" : "")?>>--Select--</option>
<option value="Completamente orientada"  <?php echo (($data['orientacion']=="Completamente orientada") ? "selected" : "")?>>Completamente orientada</option>
<option value="Parcialmente orientada"  <?php echo (($data['orientacion']=="Parcialmente orientada") ? "selected" : "")?>>Parcialmente orientada</option>
<option value="Orientada sólo en persona"  <?php echo (($data['orientacion']=="Orientada sólo en persona") ? "selected" : "")?>>Orientada sólo en persona</option>
<option value="Totalmente desorientada"  <?php echo (($data['orientacion']=="Totalmente desorientada") ? "selected" : "")?>>Totalmente desorientada</option>
<option value="Paciente no contestó o no se le entendió"  <?php echo (($data['orientacion']=="Paciente no contestó o no se le entendió") ? "selected" : "")?>>Paciente no contestó o no se le entendió</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="orientacion_cmnt"><?php echo $data['orientacion_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Memoria</label></td>
<td>
<select class="form-control" name="memoria">
<option value="" <?php echo (($data['memoria']=="") ? "selected" : "")?>>--Select--</option>
<option value="Memoria inmediata y remota preservadas"  <?php echo (($data['memoria']=="Memoria inmediata y remota preservadas") ? "selected" : "")?>>Memoria inmediata y remota preservadas</option>
<option value="Paciente no contestó o no se le entendió"  <?php echo (($data['memoria']=="Paciente no contestó o no se le entendió") ? "selected" : "")?>>Paciente no contestó o no se le entendió</option>
<option value="Alteración en memoria"  <?php echo (($data['memoria']=="Alteración en memoria") ? "selected" : "")?>>Alteración en memoria</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="memoria_cmnt"><?php echo $data['memoria_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Juicio</label></td>
<td>
<select class="form-control" name="juicio">
<option value="" <?php echo (($data['juicio']=="") ? "selected" : "")?>>--Select--</option>
<option value="Puede manejar adecuadamente situaciones ordinarias"  <?php echo (($data['juicio']=="Puede manejar adecuadamente situaciones ordinarias") ? "selected" : "")?>>Puede manejar adecuadamente situaciones ordinarias </option>
<option value="Requiere supervisión para manejar adecuadamente situaciones ordinarias"  <?php echo (($data['juicio']=="Requiere supervisión para manejar adecuadamente situaciones ordinarias") ? "selected" : "")?>>Requiere supervisión para manejar adecuadamente situaciones ordinarias</option>
<option value="No puede manejar adecuadamente situaciones ordinarias"  <?php echo (($data['juicio']=="No puede manejar adecuadamente situaciones ordinarias") ? "selected" : "")?>>No puede manejar adecuadamente situaciones ordinarias</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="juicio_cmnt"><?php echo $data['juicio_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Introspección</label></td>
<td>
<select class="form-control" name="introspeccion">
<option value="" <?php echo (($data['introspeccion']=="") ? "selected" : "")?>>--Select--</option>
<option value="Consciente de sus problemas o situaciones"  <?php echo (($data['introspeccion']=="Consciente de sus problemas o situaciones") ? "selected" : "")?>>Consciente de sus problemas o situaciones</option>
<option value="No está consciente de sus problemas o situaciones"  <?php echo (($data['introspeccion']=="No está consciente de sus problemas o situaciones") ? "selected" : "")?>>No está consciente de sus problemas o situaciones</option>
<option value="Tiene alguna consciencia de sus problemas o situaciones"  <?php echo (($data['introspeccion']=="Tiene alguna consciencia de sus problemas o situaciones") ? "selected" : "")?>>Tiene alguna consciencia de sus problemas o situaciones</option>
<option value="Confundida"  <?php echo (($data['introspeccion']=="Confundida") ? "selected" : "")?>>Confundida</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="introspeccion_cmnt"><?php echo $data['introspeccion_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Ideas Suicidas</label></td>
<td>
<select class="form-control" name="ideass">
<option value="" <?php echo (($data['ideass']=="") ? "selected" : "")?>>--Select--</option>
<option value="Sin plan estructurado"  <?php echo (($data['ideass']=="Sin plan estructurado") ? "selected" : "")?>>Sin plan estructurado</option>
<option value="Con plan estructurado"  <?php echo (($data['ideass']=="Con plan estructurado") ? "selected" : "")?>>Con plan estructurado</option>
<option value="Niega ideas suicidas"  <?php echo (($data['ideass']=="Niega ideas suicidas") ? "selected" : "")?>>Niega ideas suicidas</option>
<option value="Paciente no contestó o no se le entendió"  <?php echo (($data['ideass']=="Paciente no contestó o no se le entendió") ? "selected" : "")?>>Paciente no contestó o no se le entendió</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="ideass_cmnt"><?php echo $data['ideass_cmnt']; ?></textarea></td>
</tr>
<tr>
<td><label for="inputEmail4">Ideas Homicidas</label></td>
<td>
<select class="form-control" name="ideash">
<option value="" <?php echo (($data['ideash']=="") ? "selected" : "")?>>--Select--</option>
<option value="Sin plan estructurado"  <?php echo (($data['ideash']=="Sin plan estructurado") ? "selected" : "")?>>Sin plan estructurado</option>
<option value="Con plan estructurado"  <?php echo (($data['ideash']=="Con plan estructurado") ? "selected" : "")?>>Con plan estructurado</option>
<option value="Niega ideas homicidas"  <?php echo (($data['ideash']=="Niega ideas homicidas") ? "selected" : "")?>>Niega ideas homicidas</option>
<option value="Paciente no contestó o no se le entendió"  <?php echo (($data['ideash']=="Paciente no contestó o no se le entendió") ? "selected" : "")?>>Paciente no contestó o no se le entendió</option>
</select>
</td>
<td><textarea class="col-md-12" rows="3" name="ideash_cmnt"><?php echo $data['ideash_cmnt']; ?></textarea></td>
</tr>
</table>
    </div>
<!--<div class="form-group col-md-7">
      <label for="inputPassword4">Actividad Motora</label>
      <input type="text" name="actividadm" value="<?php echo $data['actividadm'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Actitud</label>
      <input type="text" name="actitud" value="<?php echo $data['actitud'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Conducta</label>
      <input type="text" name="conducta" value="<?php echo $data['conducta'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Habia</label>
      <input type="text" name="habia" value="<?php echo $data['habia'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Afecto</label>
      <input type="text" name="afecto" value="<?php echo $data['afecto'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Talante</label>
      <input type="text" name="talante" value="<?php echo $data['talante'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Percepcion</label>
      <input type="text" name="percepcion" value="<?php echo $data['percepcion'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Contenido de Pensamiento</label>
      <input type="text" name="contenido" value="<?php echo $data['contenido'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Relevancia de sus Respuestas</label>
      <input type="text" name="relevancia" value="<?php echo $data['relevancia'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Procesamiento de Pensamiento</label>
      <input type="text" name="proces_de" value="<?php echo $data['proces_de'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Nivel de Atencion</label>
      <input type="text" name="nivel" value="<?php echo $data['nivel'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Concentracion</label>
      <input type="text" name="concentracion" value="<?php echo $data['concentracion'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Orientacion</label>
      <input type="text" name="orientacion" value="<?php echo $data['orientacion'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Memoria</label>
      <input type="text" name="memoria" value="<?php echo $data['memoria'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Juicio</label>
      <input type="text" name="juicio" value="<?php echo $data['juicio'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Introspeccion</label>
      <input type="text" name="introspeccion" value="<?php echo $data['introspeccion'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Ideas Suicidas</label>
      <input type="text" name="ideass" value="<?php echo $data['ideass'];?>">
    </div>
<div class="form-group col-md-7">
      <label for="inputPassword4">Ideas Homicidas</label>
      <input type="text" name="ideash" value="<?php echo $data['ideash'];?>">
    </div>
<label class="form-check-label" for="gridCheck">
       Comentarios:
      </label>
      <textarea class="col-md-12" rows="3" name="examen_comm"><?php echo $data['examen_comm']; ?></textarea>
<div class="form-group col-md-12"></div>-->
</div>
</div>
</div>
<div id="menu7" class="tab-pane">
                        <div class="">
<div>
<div class="form-group col-md-12">
      <label class="form-check-label" for="gridCheck">
       Impresion Diagnostica
      </label>
      <textarea class="col-md-12" name="imp_dia" onclick="sel_diagnosis('imp_dia')" ><?php echo $data['imp_dia']; ?> </textarea>
  </div>
<div class="form-group col-md-12"></div>

<input type="hidden" name="prescription_delete[]" id="prescription_delete" value="">
                      <table class='table'>
                        <thead>
                            <tr>
                              <th>Medicamento</th>
                              <th>Dosis</th>
                              <th>Unidad</th>
                              <th>Cantidad</th>
                              <!--<th>Via</th>-->
                              <th>Via</th>
                              <th>Frecuencia</th>
                              <!--<th>Refill</th>-->
                            </tr>
                      </tr>
                      </thead>

                      <?php if (!empty($drugs_code)) { ?>
                        <tbody class="table mainrow_drug">
 <?php foreach ($drugs_code as $key => $dc) { ?>
                            <!-- <div class='row'>
                              <div class='col-md-12' style='padding-bottom:10px;'> -->
                                <tr class="rowdrug_1 first_primary">
                                  <td><input class="drug_align protoautosuggest ui-autocomplete-input drug_code_search" name="prescription[<?php echo $key; ?>][medicamento]" placeholder="Medicamento" autocomplete="off" type="text" value='<?php echo $dc['medicamento'] ?>'>
                                    <input class="drug_code" name="prescription[<?php echo $key; ?>][drug_code]" type="hidden" value="<?php echo $dc['drug_code'] ?>">
                                  <input class="id" type="hidden" name="prescription[<?php echo $key; ?>][id]" value="<?php echo $dc['id'] ?>">
                                    <input class="prescription_id" name="prescription_id" type="hidden" value="<?php echo $dc['id'] ?>"></td>
				  <td><input class="drug_concentration drug_align" name="prescription[<?php echo $key; ?>][dosis]" value="<?php echo $dc['dosis']; ?>"></td>
<td style="display:flex"><input class="drug_concentration drug_align form-control" type="hidden" name="prescription[<?php echo $key; ?>][size]" value="<?php echo $dc['size']; ?>">

<select class="form-control" name="prescription[<?php echo $key; ?>][unit]" id="unit">
<option label=" " value="0"> </option>
<option label="mg" value="1" <?php echo (($dc['unit'] == '1') ? "selected" : "") ?> >mg</option>
<!--<option label="mg/1cc" value="2"  <?php //echo (($dc['unit'] == '2') ? "selected" : "") ?>>mg/1cc</option>
<option label="mg/2cc" value="3"  <?php //echo (($dc['unit'] == '3') ? "selected" : "") ?>>mg/2cc</option>
<option label="mg/3cc" value="4"  <?php //echo (($dc['unit'] == '4') ? "selected" : "") ?>>mg/3cc</option>
<option label="mg/4cc" value="5"  <?php //echo (($dc['unit'] == '5') ? "selected" : "") ?>>mg/4cc</option>
<option label="mg/5cc" value="6"  <?php //echo (($dc['unit'] == '6') ? "selected" : "") ?>>mg/5cc</option>-->
<option label="mcg" value="7" <?php echo (($dc['unit'] == '7') ? "selected" : "") ?>>mcg</option>
<option label="grams" value="8"  <?php echo (($dc['unit'] == '8') ? "selected" : "") ?>>grams</option>
<option label="mL" value="9"  <?php echo (($dc['unit'] == '9') ? "selected" : "") ?>>mL</option>
</select>
</td>
<td>
    <input class="form-control" name="prescription[<?php echo $key; ?>][amount]" size="6" value="<?php echo $dc['amount']; ?>">
</td>
<!--<td style="display:flex;">
    <select class="form-control" name="prescription[<?php echo $key; ?>][form]">
<option label=" " value="0" <?php echo (($dc['form'] == '0') ? "selected" : "") ?>> </option>
<option label="suspension" value="1" <?php echo (($dc['form'] == '1') ? "selected" : "") ?>>suspension</option>
<option label="tablet" value="2" <?php echo (($dc['form'] == '2') ? "selected" : "") ?>>tablet</option>
<option label="capsule" value="3" <?php echo (($dc['form'] == '3') ? "selected" : "") ?>>capsule</option>
<option label="solution" value="4" <?php echo (($dc['form'] == '4') ? "selected" : "") ?>>solution</option>
<option label="tsp" value="5" <?php echo (($dc['form'] == '5') ? "selected" : "") ?>>tsp</option>
<option label="ml" value="6" <?php echo (($dc['form'] == '6') ? "selected" : "") ?>>ml</option>
<option label="units" value="7" <?php echo (($dc['form'] == '7') ? "selected" : "") ?>>units</option>
<option label="inhalations" value="8" <?php echo (($dc['form'] == '8') ? "selected" : "") ?>>inhalations</option>
<option label="gtts(drops)" value="9" <?php echo (($dc['form'] == '9') ? "selected" : "") ?>>gtts(drops)</option>
<option label="cream" value="10" <?php echo (($dc['form'] == '10') ? "selected" : "") ?>>cream</option>
<option label="ointment" value="11" <?php echo (($dc['form'] == '11') ? "selected" : "") ?>>ointment</option>
<option label="puff" value="12" <?php echo (($dc['form'] == '12') ? "selected" : "") ?>>puff</option>
</select></td>--><td>
    <select class="form-control" name="prescription[<?php echo $key; ?>][route]">
<option label=" " value="0" <?php echo (($dc['route'] == '0') ? "selected" : "") ?>> </option>
<option label="Per Oris" value="1" <?php echo (($dc['route'] == '1') ? "selected" : "") ?>>Per Oris</option>
<option label="Per Rectum" value="2" <?php echo (($dc['route'] == '2') ? "selected" : "") ?>>Per Rectum</option>
<option label="To Skin" value="3" <?php echo (($dc['route'] == '3') ? "selected" : "") ?>>To Skin</option>
<option label="To Affected Area" value="4" <?php echo (($dc['route'] == '4') ? "selected" : "") ?>>To Affected Area</option>
<option label="Sublingual" value="5" <?php echo (($dc['route'] == '5') ? "selected" : "") ?>>Sublingual</option>
<option label="OS" value="6" <?php echo (($dc['route'] == '6') ? "selected" : "") ?>>OS</option>
<option label="OD" value="7" <?php echo (($dc['route'] == '7') ? "selected" : "") ?>>OD</option>
<option label="OU" value="8" <?php echo (($dc['route'] == '8') ? "selected" : "") ?>>OU</option>
<option label="SQ" value="9" <?php echo (($dc['route'] == '9') ? "selected" : "") ?>>SQ</option>
<option label="IM" value="10" <?php echo (($dc['route'] == '10') ? "selected" : "") ?>>IM</option>
<option label="IV" value="11" <?php echo (($dc['route'] == '11') ? "selected" : "") ?>>IV</option>
<option label="Per Nostril" value="12" <?php echo (($dc['route'] == '12') ? "selected" : "") ?>>Per Nostril</option>
<option label="Both Ears" value="13" <?php echo (($dc['route'] == '13') ? "selected" : "") ?>>Both Ears</option>
<option label="Left Ear" value="14" <?php echo (($dc['route'] == '14') ? "selected" : "") ?>>Left Ear</option>
<option label="Right Ear" value="15" <?php echo (($dc['route'] == '15') ? "selected" : "") ?>>Right Ear</option>
<option label="Inhale" value="inhale" <?php echo (($dc['route'] == 'inhale') ? "selected" : "") ?>>Inhale</option>
<option label="Intradermal" value="intradermal" <?php echo (($dc['route'] == 'intradermal') ? "selected" : "") ?>>Intradermal</option>
<option label="Other/Miscellaneous" value="other" <?php echo (($dc['route'] == 'other') ? "selected" : "") ?>>Other/Miscellaneous</option>
<option label="Transdermal" value="transdermal" <?php echo (($dc['route'] == 'transdermal') ? "selected" : "") ?>>Transdermal</option>
<option label="Intramuscular" value="intramuscular" <?php echo (($dc['route'] == 'intramuscular') ? "selected" : "") ?>>Intramuscular</option>
</select>
</td>
<td>
    <select class="form-control interval" name="prescription[<?php echo $key; ?>][interval]">
<option label=" " value="0" <?php echo (($dc['interval'] == '0') ? "selected" : "") ?>> </option>
<option label="b.i.d." value="1" <?php echo (($dc['interval'] == '1') ? "selected" : "") ?>>b.i.d.</option>
<option label="t.i.d." value="2" <?php echo (($dc['interval'] == '2') ? "selected" : "") ?>>t.i.d.</option>
<option label="q.i.d." value="3" <?php echo (($dc['interval'] == '3') ? "selected" : "") ?>>q.i.d.</option>
<option label="q.3h" value="4" <?php echo (($dc['interval'] == '4') ? "selected" : "") ?>>q.3h</option>
<option label="q.4h" value="5" <?php echo (($dc['interval'] == '5') ? "selected" : "") ?>>q.4h</option>
<option label="q.5h" value="6" <?php echo (($dc['interval'] == '6') ? "selected" : "") ?>>q.5h</option>
<option label="q.6h" value="7" <?php echo (($dc['interval'] == '7') ? "selected" : "") ?>>q.6h</option>
<option label="q.8h" value="8" <?php echo (($dc['interval'] == '8') ? "selected" : "") ?>>q.8h</option>
<option label="q.d." value="9" <?php echo (($dc['interval'] == '9') ? "selected" : "") ?>>q.d.</option>
<option label="a.c." value="10" <?php echo (($dc['interval'] == '10') ? "selected" : "") ?>>a.c.</option>
<option label="p.c." value="11" <?php echo (($dc['interval'] == '11') ? "selected" : "") ?>>p.c.</option>
<option label="a.m." value="12" <?php echo (($dc['interval'] == '12') ? "selected" : "") ?>>a.m.</option>
<option label="p.m." value="13" <?php echo (($dc['interval'] == '13') ? "selected" : "") ?>>p.m.</option>
<option label="ante" value="14" <?php echo (($dc['interval'] == '14') ? "selected" : "") ?>>ante</option>
<option label="h" value="15" <?php echo (($dc['interval'] == '15') ? "selected" : "") ?> >h</option>
<option label="h.s." value="16" <?php echo (($dc['interval'] == '16') ? "selected" : "") ?>>h.s.</option>
<option label="p.r.n." value="17" <?php echo (($dc['interval'] == '17') ? "selected" : "") ?>>p.r.n.</option>
<option label="stat" value="18" <?php echo (($dc['interval'] == '18') ? "selected" : "") ?>>stat</option>
<option label="other" value="19" <?php echo (($dc['interval'] == '19') ? "selected" : "") ?>>Other</option>
<input type="text" class="form-control <?php echo (($dc['interval'] == '19') ? '' :'freqother-hidden'); ?>" name="prescription[<?php echo $key; ?>][freq_other]" value="<?php echo $dc['freq_other']; ?>"/>
</select>
</td>
<!--<td class="row">
<select class="form-control" name="prescription[<?php echo $key; ?>][refills]">
<option label="00" value="0" <?php echo (($dc['refills'] == '0')? "selected":"")?>>00</option>
<option label="01" value="1" <?php echo (($dc['refills'] == '1')? "selected":"")?>>01</option>
<option label="02" value="2"  <?php echo (($dc['refills'] == '2')? "selected":"")?>>02</option>
<option label="03" value="3"  <?php echo (($dc['refills'] == '3')? "selected":"")?>>03</option>
<option label="04" value="4"  <?php echo (($dc['refills'] == '4')? "selected":"")?>>04</option>
<option label="05" value="5"  <?php echo (($dc['refills'] == '5')? "selected":"")?>>05</option>
<option label="06" value="6"  <?php echo (($dc['refills'] == '6')? "selected":"")?>>06</option>
<option label="07" value="7"  <?php echo (($dc['refills'] == '7')? "selected":"")?>>07</option>
<option label="08" value="8"  <?php echo (($dc['refills'] == '8')? "selected":"")?>>08</option>
<option label="09" value="9"  <?php echo (($dc['refills'] == '9')? "selected":"")?>>09</option>
<option label="10" value="10"  <?php echo (($dc['refills'] == '10')? "selected":"")?>>10</option>
<option label="11" value="11"  <?php echo (($dc['refills'] == '11')? "selected":"")?>>11</option>
<option label="12" value="12"  <?php echo (($dc['refills'] == '12')? "selected":"")?>>12</option>
<option label="13" value="13"  <?php echo (($dc['refills'] == '13')? "selected":"")?>>13</option>
<option label="14" value="14"  <?php echo (($dc['refills'] == '14')? "selected":"")?>>14</option>
<option label="15" value="15"  <?php echo (($dc['refills'] == '15')? "selected":"")?>>15</option>
<option label="16" value="16"  <?php echo (($dc['refills'] == '16')? "selected":"")?>>16</option>
<option label="17" value="17"  <?php echo (($dc['refills'] == '17')? "selected":"")?>>17</option>
<option label="18" value="18"  <?php echo (($dc['refills'] == '18')? "selected":"")?>>18</option>
<option label="19" value="19"  <?php echo (($dc['refills'] == '19')? "selected":"")?>>19</option>
<option label="20" value="20"  <?php echo (($dc['refills'] == '20')? "selected":"")?>>20</option>
</select>
</td> -->
<!--                                  <td><select class="drugs_form drug_align" style="text-align:center;" name="prescription[<?php echo $key; ?>][drug_form]">-->
                                      <?php
/*foreach ($drug_interval_list as $k => $value) {
                                        $selected = ($k == $dc['drug_form']) ? 'selected' : '';
                                        echo "<option value='$k' $selected>" . $value . "</option>";
}*/
                                      ?>
<!--</select></td>
                                  <td><textarea class="special_instructions" style="margin-right:10px; height:60px;margin-top:5px;" name="prescription[<?php echo $key; ?>][special_instructions]"><?php echo $dc['special_instructions']; ?></textarea></td>-->
                                  <td>
                                  <div class="form-group">
                                      <a href="javascript:void(0);" class="btn btn-primary adddrug"><span class="fa fa-plus text-white"></span></a>
                                      <a href="javascript:void(0);" class="btn btn-danger rmdrug"><span class="fa fa-minus text-white"></span></a>
                                    </div>
                                  </td>
                                </tr>
                              <!-- </div>
                            </div> -->
                        <?php } ?>
                        </tbody>
<?php } else { ?>
                        <tbody class="table mainrow_drug">
                                                <!-- <div class='row'>
                            <div class="col-md-12" style="padding-bottom:10px;"> -->
                              <tr class="rowdrug_1 first_primary">
                                <td><input class="drug_align protoautosuggest ui-autocomplete-input drug_code_search" name="prescription[0][medicamento]" placeholder="Medicamento" autocomplete="off" type="text" value=''>
                                <input class="drug_code" name="prescription[0][drug_code]" type="hidden" value="">
                                <input class="id" type="hidden" name="prescription[0][id]" value=""></td>
                                <input class="prescription_id" name="prescription_id" type="hidden" value=""></td>
				<td><input class="drug_concentration drug_align" name="prescription[0][dosis]" value="" placeholder="Dosis"></td>
<td style="display:flex;"><input class="form-control" type="hidden" name="prescription[0][size]" value="" placeholder="Talla">
                                <select class="form-control" name="prescription[0][unit]" id="unit">
<option label=" " value="0"> </option>
<option label="mg" value="1" selected="selected">mg</option>
<!--<option label="mg/1cc" value="2">mg/1cc</option>
<option label="mg/2cc" value="3">mg/2cc</option>
<option label="mg/3cc" value="4">mg/3cc</option>
<option label="mg/4cc" value="5">mg/4cc</option>
<option label="mg/5cc" value="6">mg/5cc</option> -->
<option label="mcg" value="7">mcg</option>
<option label="grams" value="8">grams</option>
<option label="mL" value="9">mL</option>
</select></td>
                                <td>
    <input class="form-control" name="prescription[0][amount]" size="6">
</td>
<!--<td style="display:flex;">
    <select class="form-control" name="prescription[0][form]" id="form"><option label=" " value="0"> </option>
<option label="suspension" value="1">suspension</option>
<option label="tablet" value="2">tablet</option>
<option label="capsule" value="3">capsule</option>
<option label="solution" value="4">solution</option>
<option label="tsp" value="5">tsp</option>
<option label="ml" value="6">ml</option>
<option label="units" value="7">units</option>
<option label="inhalations" value="8">inhalations</option>
<option label="gtts(drops)" value="9">gtts(drops)</option>
<option label="cream" value="10">cream</option>
<option label="ointment" value="11">ointment</option>
<option label="puff" value="12">puff</option>
</select></td>--><td>
<select class="form-control" name="prescription[0][route]" id="route"><option label=" " value="0"> </option>
<option label="Per Oris" value="1">Per Oris</option>
<option label="Per Rectum" value="2">Per Rectum</option>
<option label="To Skin" value="3">To Skin</option>
<option label="To Affected Area" value="4">To Affected Area</option>
<option label="Sublingual" value="5">Sublingual</option>
<option label="OS" value="6">OS</option>
<option label="OD" value="7">OD</option>
<option label="OU" value="8">OU</option>
<option label="SQ" value="9">SQ</option>
<option label="IM" value="10">IM</option>
<option label="IV" value="11">IV</option>
<option label="Per Nostril" value="12">Per Nostril</option>
<option label="Both Ears" value="13">Both Ears</option>
<option label="Left Ear" value="14">Left Ear</option>
<option label="Right Ear" value="15">Right Ear</option>
<option label="Inhale" value="inhale">Inhale</option>
<option label="Intradermal" value="intradermal">Intradermal</option>
<option label="Other/Miscellaneous" value="other">Other/Miscellaneous</option>
<option label="Transdermal" value="transdermal">Transdermal</option>
<option label="Intramuscular" value="intramuscular">Intramuscular</option>
</select>
</td>
<td>
<select class="form-control interval" name="prescription[0][interval]" id="interval"><option label=" " value="0"> </option>
<option label="b.i.d." value="1">b.i.d.</option>
<option label="t.i.d." value="2">t.i.d.</option>
<option label="q.i.d." value="3">q.i.d.</option>
<option label="q.3h" value="4">q.3h</option>
<option label="q.4h" value="5">q.4h</option>
<option label="q.5h" value="6">q.5h</option>
<option label="q.6h" value="7">q.6h</option>
<option label="q.8h" value="8">q.8h</option>
<option label="q.d." value="9">q.d.</option>
<option label="a.c." value="10">a.c.</option>
<option label="p.c." value="11">p.c.</option>
<option label="a.m." value="12">a.m.</option>
<option label="p.m." value="13">p.m.</option>
<option label="ante" value="14">ante</option>
<option label="h" value="15">h</option>
<option label="h.s." value="16">h.s.</option>
<option label="p.r.n." value="17">p.r.n.</option>
<option label="stat" value="18">stat</option>
<option label="other" value="19">Other</option>
<input type="text" class="form-control freqother-hidden" name="prescription[0][freq_other]" value=""/>
</select>
</td>
<!--<td class="row">
<select class="form-control" name="prescription[0][refills]">
<option label="00" value="0" selected="selected">00</option>
<option label="01" value="1">01</option>
<option label="02" value="2">02</option>
<option label="03" value="3">03</option>
<option label="04" value="4">04</option>
<option label="05" value="5">05</option>
<option label="06" value="6">06</option>
<option label="07" value="7">07</option>
<option label="08" value="8">08</option>
<option label="09" value="9">09</option>
<option label="10" value="10">10</option>
<option label="11" value="11">11</option>
<option label="12" value="12">12</option>
<option label="13" value="13">13</option>
<option label="14" value="14">14</option>
<option label="15" value="15">15</option>
<option label="16" value="16">16</option>
<option label="17" value="17">17</option>
<option label="18" value="18">18</option>
<option label="19" value="19">19</option>
<option label="20" value="20">20</option>
</select>
</td>-->
                                <!--<td>MG</td>
                                <td>1</td>
                                <td>
                                  <input class='frecuencia' placeholder='Via' autocomplete="off" name="prescription[0][via]" value=""></td>
                                <td>
                                  <input class='frecuencia' placeholder='Frecuencia' autocomplete="off" name="prescription[0][frecuencia]" value=""></td>
                                  <td><input class='frecuencia' placeholder='Disp' autocomplete="off" name="prescription[0][disp]" value=""></td>
                                  <td><input class='frecuencia' placeholder='Refills' autocomplete="off" name="prescription[0][refills]" value=""></td>-->
								<!--<td>
                                  <input class='frecuencia' placeholder='Frecuencia' autocomplete="off" name="prescription[0][frecuencia]" value=""></td>
                                  <td><input class="to_date datepicker drug_align" placeholder='M/D/Y' autocomplete="off" name="prescription[0][date_to]" value=""></td>
                                <td><select class="drugs_form drug_align" style="text-align:center;width:170px !important;" name="prescription[0][drug_form]">
                                    <?php
                                    foreach ($drug_interval_list as $kt => $value) {
                                      echo "<option value='$kt'>" . $value . "</option>";
                                    }
                                    ?>
                                  </select></td>
<td><textarea class="special_instructions" style="margin-right:10px; height:60px;width:190px !important;margin-top:5px;" name="prescription[0][special_instructions]"></textarea></td>
                                <td><input class="add_to_medications drug_align" name="prescription[0][add_to_medications]" type="checkbox" value="1"></td>-->
                                <td>
                                  <div class='form-group'>
                                  <a href="javascript:void(0);" class="btn btn-primary adddrug"><span class="fa fa-plus text-white"></span></a>
                                    <a href="javascript:void(0);" class="btn btn-danger rmdrug"><span class="fa fa-minus text-white"></span></a>
                                  </div>
                                </td>
                              </tr>
                            <!-- </div>
                          </div> -->
                        </tbody>
                      <?php } ?>
                                          </table>


<div class="form-group col-md-12"></div>
<div class="form-group col-md-12"></div>
<h4>Orientacion y Recomendaciones</h4>
<p>Se orienta sobre diagnosticos psiquiatricos forma de tomar los medicamentos y posibles efectos secundarios<br>Se indica informa de forma oportuna sobre efectos secundarios<br>
Se recomienda formar precauciones para evitar caidas<br>
Llamar al 9-1-1 o acudir al hospital en caso de cualquier situacion que pueda amenazor la vida de la paciente, la propiedad u otras personas<br>
Tomar medicamentos segun especificados en receta medica y no hacer cambios per cuenta propia

<div class="form-group col-md-12"></div>
</div>
</div>
</div>

<div class="col-md-12" style="display:flex;justify-content:center;">
<button type="submit" class="btn btn-primary"><?php
if($_REQUEST['id'] != '')
	echo 'Update & Close';
else
	echo 'Save & Close';
?>
</button>
  <button class="btn btn-danger" onclick="top.restoreSession();parent.closeTab(window.name, true);">Cancel</button>
</div>
</div>
</form>
  </div>
</div>
  </body>
				    <script>
				    //Adddrug
    $(document).on('click', '.adddrug1', function() {
        //$("#prescription_form .datepicker").datepicker("destroy");
//        $(".drug_code_search").autocomplete("destroy");
        var newelm = $('.mainrow_drug1 tr:last').clone(true);
        var len = $('.mainrow_drug1 tr').length + 1;
        newelm.find(':input').val('');
        newelm.attr('class', newelm.attr('class').replace(/rowdrug_\d/g, 'rowdrug_' + len));
        newelm.appendTo('.mainrow_drug1');
	var prescription_list = ['medicamento', 'dosis', 'frecuencia','drug_code','id','size','unit','amount','form','route','interval','freq_other','refills'];
	prescription_list.forEach(function(i) {
		if(i == 'form' || i=='route'||i=='unit' || i =='interval'||i=='refills') {
			$('.rowdrug_' + len).find('td > select[name="prescription1[' + (len - 2) + '][' + i + ']"]').attr('name', 'prescription1[' + (len - 1) + '][' + i + ']');

		} else {
			$('.rowdrug_' + len).find('td > input[name="prescription1[' + (len - 2) + '][' + i + ']"]').attr('name', 'prescription1[' + (len - 1) + '][' + i + ']');
		}
        });
        //$("#prescription_form .datepicker").datepicker({dateFormat: 'yy-mm-dd'});
    /*    $(".drug_code_search").autocomplete({
          minLength: 2,
          source: "../../search.php?fn=drug_code",
          select: function(event, ui) {
            $(this).val(ui.item.value);
            $(this).next().val(ui.item.code);
          }
    });*/
    });
 $(document).on('click', '.rmdrug1', function() {
              if ($('.rmdrug1').length > 1) {
                      prescription_delete = ($('input:hidden[name="prescription1_delete[]"]').val() != '') ? JSON.parse($('input:hidden[name="prescription1_delete[]"]').val()) : [];
                delete_pres = $(this).closest('tr').find('.prescription1_id').val();
                if(delete_pres != '')
                        prescription_delete.push(delete_pres);
                $('input:hidden[name="prescription1_delete[]"]').val(JSON.stringify(prescription_delete));
                $(this).closest('tr').remove();

        } else
          return false;
 });


//Adddrug
    $(document).on('click', '.adddrug', function() {
        //$("#prescription_form .datepicker").datepicker("destroy");
//        $(".drug_code_search").autocomplete("destroy");
        var newelm = $('.mainrow_drug tr:last').clone(true);
        var len = $('.mainrow_drug tr').length + 1;
        newelm.find(':input').val('');
        newelm.attr('class', newelm.attr('class').replace(/rowdrug_\d/g, 'rowdrug_' + len));
        newelm.appendTo('.mainrow_drug');
	var prescription_list = ['medicamento', 'dosis', 'frecuencia','drug_code','id','size','unit','amount','form','route','interval','freq_other','refills'];
        prescription_list.forEach(function(i) {
          if (i == 'date_from' || i == 'date_to')
                  $('.rowdrug_' + len).find('td > input[name="prescription[' + (len - 2) + '][' + i + ']"]').attr('id', 'prescription[' + (len - 1) + '][' + i + ']');
          if(i == 'drug_form')
                $('.rowdrug_' + len).find('td > select[name="prescription[' + (len - 2) + '][' + i + ']"]').attr('name', 'prescription[' + (len - 1) + '][' + i + ']');
          if(i == 'special_instructions')
		  $('.rowdrug_' + len).find('td > textarea[name="prescription[' + (len - 2) + '][' + i + ']"]').attr('name', 'prescription[' + (len - 1) + '][' + i + ']');
	  if(i == 'form' || i=='route'||i=='unit' || i =='interval'||i=='refills') {
                        $('.rowdrug_' + len).find('td > select[name="prescription[' + (len - 2) + '][' + i + ']"]').attr('name', 'prescription[' + (len - 1) + '][' + i + ']');

                } else {
			$('.rowdrug_' + len).find('td > input[name="prescription[' + (len - 2) + '][' + i + ']"]').attr('name', 'prescription[' + (len - 1) + '][' + i + ']');
		}
        });
        //$("#prescription_form .datepicker").datepicker({dateFormat: 'yy-mm-dd'});
    /*    $(".drug_code_search").autocomplete({
          minLength: 2,
          source: "../../search.php?fn=drug_code",
          select: function(event, ui) {
            $(this).val(ui.item.value);
            $(this).next().val(ui.item.code);
          }
    });*/
    });
 $(document).on('click', '.rmdrug', function() {
              if ($('.rmdrug').length > 1) {
                      prescription_delete = ($('input:hidden[name="prescription_delete[]"]').val() != '') ? JSON.parse($('input:hidden[name="prescription_delete[]"]').val()) : [];
                delete_pres = $(this).closest('tr').find('.prescription_id').val();
                if(delete_pres != '')
                        prescription_delete.push(delete_pres);
                $('input:hidden[name="prescription_delete[]"]').val(JSON.stringify(prescription_delete));
                $(this).closest('tr').remove();

        } else
          return false;
 });
$("form").submit(function () {

    var this_master = $(this);

    this_master.find('input[type="checkbox"]').each( function () {
        var checkbox_this = $(this);


        if(!checkbox_this.is(":checked")) {
            checkbox_this.prop('checked',true);
            //DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA
            checkbox_this.attr('value','');
        }
    })
})
// This is for callback by the find-code popup.
// Appends to or erases the current list of related codes.
function set_related(codetype, code, selector, codedesc) {
	var f = document.forms[0];

    var s = f[rcvarname].value;
    if (code) {
        if (s.length > 0) s += ';';
        s += codetype + ':' + code +' - '+codedesc;
    } else {
        s = '';
    }
    f[rcvarname].value = s;
}
// This invokes the find-code popup.
function sel_diagnosis(varname) {
    rcvarname = varname;
    // codetype is just to make things easier and avoid mistakes.
    // Might be nice to have a lab parameter for acceptable code types.
    // Also note the controlling script here runs from interface/patient_file/encounter/.
    let title = <?php echo xlj("Select Diagnosis Codes"); ?>;
    dlgopen('find_code_dynamic.php?codetype=ICD10', '_blank', 985, 750, '', title);
}

// This is for callback by the find-code popup.
// Returns the array of currently selected codes with each element in codetype:code format.
function get_related() {
    return document.forms[0][rcvarname].value.split(';');
}

// This is for callback by the find-code popup.
// Deletes the specified codetype:code from the currently selected list.
function del_related(s) {
    my_del_related(s, document.forms[0][rcvarname], false);
}

$('select.interval').on('change', function() {
        if(this.value == '19'){
                $(this).siblings('input').removeClass('freqother-hidden');
        } else if(this.value != '19' && !$(this).siblings('input').hasClass('freqother-hidden')){
                $(this).siblings('input').addClass('freqother-hidden').val('');
        }
});
</script>
  </html>
