<?php

/*
 *  package   Comlink OpenEMR
 *  link      http://www.open-emr.org
 *  author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  copyright Copyright (c )2022. Sherwin Gaddis <sherwingaddis@gmail.com>
 *  license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once(__DIR__ . "/../../globals.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/forms.inc");

use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Core\Header;

if (isset($_REQUEST['id']) && ($_REQUEST['id'])) {
    $viewmode = 1;
    $data1 = sqlQuery("select * from formato_psiquiatrico_2_form where id= ?", array($_REQUEST['id']));
    $encuentra = explode(", ", $data1['encuentra']);
    $proposito = explode(", ", $data1['proposito']);
    $fuentes = explode(", ", $data1['fuentes']);
    $si_aplica = explode(", ", $data1['si_aplica']);
    $estresores = explode(", ",$data1['estresores']);
    $medica = explode(", ", $data1['medica']);
    $enferemedades = explode(", ", $data1['enferemedades']);
    $medicamentos = explode(", ", $data1['medicamentos']);
    $apariencia = explode(", ", $data1['apariencia']);
    $alucinaciones = explode(", ", $data1['alucinaciones']);
    $sicomotor = explode(", ",$data1['sicomotor']);
    $orientacion = explode(", ", $data1['orientacion']);
    $proceso_de_pensamiento = explode(", ", $data1['proceso_de_pensamiento']);
    $delirios = explode(", ", $data1['delirios']);
    $data2 = sqlStatement("select * from formato2_medicamento where form_id=?",$_REQUEST['id']);
        $drugs_code = [];
        while($row=sqlFetchArray($data2)){
array_push($drugs_code,$row);
        }
} else if(isset($_REQUEST['get_pre_value']) && $_REQUEST['get_pre_value'] != ''){
	$viewmode = 0;
	$data = sqlQuery("select fp.* from formato_psiquiatrico_2_form fp left join forms f on f.form_id=fp.id and f.formdir='formato_psiquiatrico_2' and fp.pid=f.pid where fp.pid=".$pid." and f.deleted=0 order by f.date desc,fp.id desc limit 1");
$data1 = sqlQuery("select * from formato_psiquiatrico_2_form where id= ?", array($data['id']));
    $encuentra = explode(", ", $data1['encuentra']);
    $proposito = explode(", ", $data1['proposito']);
    $fuentes = explode(", ", $data1['fuentes']);
    $si_aplica = explode(", ", $data1['si_aplica']);
    $estresores = explode(", ",$data1['estresores']);
    $medica = explode(", ", $data1['medica']);
    $enferemedades = explode(", ", $data1['enferemedades']);
    $medicamentos = explode(", ", $data1['medicamentos']);
    $apariencia = explode(", ", $data1['apariencia']);
    $alucinaciones = explode(", ", $data1['alucinaciones']);
    $sicomotor = explode(", ",$data1['sicomotor']);
    $orientacion = explode(", ", $data1['orientacion']);
    $proceso_de_pensamiento = explode(", ", $data1['proceso_de_pensamiento']);
    $delirios = explode(", ", $data1['delirios']);
    $data2 = sqlStatement("select * from formato2_medicamento where form_id=?",array($data['id']));
        $drugs_code = [];
        while($row=sqlFetchArray($data2)){
array_push($drugs_code,$row);
        }
}else {
    $viewmode = 0;
    $encuentra = array();
    $proposito = array();
    $fuentes = array();
    $si_aplica = array();
    $estresores = array();
    $medica = array();
    $enferemedades = array();
    $medicamentos = array();
    $apariencia = array();
    $alucinaciones = array();
    $sicomotor = array();
    $orientacion = array();
    $proceso_de_pensamiento = array();
    $delirios = array();
  }

?>
<html>

<head>
    <?php Header::setupHeader(); ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
function get_pre_value(){
var url = new URL(window.location.href);
url.searchParams.set('get_pre_value','1');
window.location.href = url.href;
}
</script>
</head>
<script>
    $(document).ready(function() {
        $("li").click(function() {
            if ($(this).index() == $("li").length - 1) {
                $("#next").hide();
                $("#save").show();
            } else if ($(this).index() < $("li").length - 1) {
                $("#next").show();
                $("#save").hide();
            }
        });
        $("#next").click(function() {
            var cur_tab = $('ul > li > a.active').parent().next('li').find('a');
            console.log(cur_tab)
            cur_tab.trigger("click");
        });
        $("#nacimiento").datepicker();

        $("#nacimiento").datepicker("option", "dateFormat", "yy-mm-dd");

        var datepick = '<?php echo $viewmode; ?>';
        if(datepick == 0)
            $('#nacimiento').datepicker().datepicker('setDate', new Date("yyyy-mm-dd"));
        else
            $('#nacimiento').datepicker().datepicker('setDate', new Date("<?php echo $data1['nacimiento']?>"));
    });
</script>
<style>
.freqother-hidden {
display:none;
}
    ul {
        justify-content: center;
        background: #e1e1e1;
        padding: 5px;
    }

    li > a {
        padding: 10px 50px !important;
    }
</style>
<body class="body_top">
<?php
if (!isset($_REQUEST['id']) || $_REQUEST['id'] == '') {?>

        <div class="row col-md-offset-3" onclick="get_pre_value()"><button class="btn btn-primary">Get Most recent Value</button></div>
<?php }
?>
    <form method="post" action="<?php echo $rootdir ?>/forms/formato_psiquiatrico_2/save.php" onsubmit="return top.restoreSession()">
        <?php
            $id = ($viewmode == 1) ? $_REQUEST['id'] : '';
        ?>
        <input type="hidden" name="mode" value="<?php echo ($viewmode == 1) ? 'edit' : 'new'; ?>" />
        <input type="hidden" name="form_id" value="<?php echo $id; ?>" />
        <input type="hidden" name="csrf_token_form" value="<?php echo attr(CsrfUtils::collectCsrfToken()); ?>" />
        <div class="col-md-12 p-3 border">
            <div class="row">
                <!--<div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><b>Fetcha</b></label>
                            <input type="text" class="form-control" id="fetcha" aria-describedby="emailHelp" name="fetcha" value=<?php echo $data1['fetcha'];?>>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><b>Fecha de Nacimiento</b></label>
                            <input type="text" class="form-control datepicker" id="nacimiento" name="nacimiento" value=<?php echo $data1['nacimiento'];?>>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><b>Edad</b></label>
                            <input type="text" class="form-control" id="edad" name="edad" value=<?php echo $data1['edad'];?>>
                        </div>
                    </div>
		</div>-->
		<div class="col-md-3">
                <ul class="nav flex-column" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab1" data-toggle="pill" href="#detail-tab1" role="tab" aria-controls="pills-home" aria-selected="true"><b>Page 1</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab2" data-toggle="pill" href="#detail-tab2" role="tab" aria-controls="pills-profile" aria-selected="false"><b>Page 2</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab3" data-toggle="pill" href="#detail-tab3" role="tab" aria-controls="pills-contact" aria-selected="false"><b>Page 3</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab4" data-toggle="pill" href="#detail-tab4" role="tab" aria-controls="pills-contact" aria-selected="false"><b>Page 4</b></a>
                    </li>
		</ul>
		</div>
                <div class="col-md-9 border p-4">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="detail-tab1" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="row">
                               <!-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Nombre</b></label>
                                        <input type="text" class="form-control" id="name" aria-describedby="emailHelp" name="nombre" value=<?php echo $data1['nombre'];?>>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Genero</b></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="genero" id="male" value="male" <?php echo ($data1['genero'] == 'male') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="exampleRadios1">
                                                Masculine
                                            </label>
                                            <span class="ml-4">
                                                <input class="form-check-input" type="radio" name="genero" id="femal" value="female" <?php echo ($data1['genero'] == 'female') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="exampleRadios2">
                                                    Feminine
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Fecha de la Evaluaction</b></label>
                                        <input type="text" class="form-control" id="evaluaction" aria-describedby="emailHelp" name="evaluaction" value=<?php echo $data1['nombre'];?>>
                                    </div>
                                </div>-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Lugar donde se encuentra</b></label>
                                        <div class="form-check">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="encuentra_hogar" name="encuentra[]" value="hogar" <?php echo (in_array("hoger", $encuentra)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1">Hogar</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="encuentra_ambula" name="encuentra[]" value="ambulatorio" <?php echo (in_array("ambulatorio", $encuentra)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Ambulatorio</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="encuentra_hospital" name="encuentra[]" value="hospital" <?php echo (in_array("hospital", $encuentra)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox3">Hospital</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="encuentra_teleconsulta" name="encuentra[]" value="teleconsulta" <?php echo (in_array("teleconsulta", $encuentra)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox3">Teleconsulta</label>
                                            </div>
                                        </div>
                                    </div>
				</div>
<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Proposito de le Evaluacion</b></label>
                                        <div class="form-check">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="proposito_inicial" name="proposito[]" value="inicial" <?php echo (in_array("inicial", $proposito)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1">Inicial</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="proposito_seduimiento" name="proposito[]" value="seduimiento" <?php echo (in_array("seduimiento", $proposito)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Seduimiento</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!--<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Proposito de le Evaluacion</b></label>
                                        <div class="form-check">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="proposito_inicial" name="proposito[]" value="inicial" <?php echo (in_array("inicial", $proposito)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1">Inicial</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="proposito_seduimiento" name="proposito[]" value="seduimiento" <?php echo (in_array("seduimiento", $proposito)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Seduimiento</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>HIPAA / Ley 408 / Consentimiento</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" name="hipaa_ley" id="hipaa_ley"><?php echo $data1['hipaa_ley'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Fuentes de Informacion</b></label>
                                        <div class="form-check">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="paciente" name="fuentes[]" value="paciente" <?php echo (in_array("paciente", $fuentes)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1">Paciente</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="clinico" name="fuentes[]" value="clinico" <?php echo (in_array("clinico", $fuentes)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Clinico</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="familiar" name="fuentes[]" value="familiar" <?php echo (in_array("familiar", $fuentes)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Familiar</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="acompanante" name="fuentes[]" value="acompanante" <?php echo (in_array("acompanante", $fuentes)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Acompanante</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Si aplica, contacte a los siguientes individuos</b></label>
                                        <div class="form-check d-flex">
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" id="si_aplica1" name="si_aplica[]" value=<?php echo (isset($si_aplica[0])) ? $si_aplica[0] : '';?>>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" id="si_aplica2" name="si_aplica[]" value=<?php echo (isset($si_aplica[1])) ? $si_aplica[1] : '';?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Estresores</b></label>
                                        <div class="form-check d-flex">
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" id="estresores1" name="estresores[]" value=<?php echo (isset($estresores[0])) ? $estresores[0] : '';?>>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" id="estresores2" name="estresores[]" value=<?php echo (isset($estresores[1])) ? $estresores[1] : '';?>>
                                            </div>
                                        </div>
                                        <div class="form-check d-flex mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" id="estresores3" name="estresores[]" value=<?php echo (isset($estresores[2])) ? $estresores[2] : '';?>>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" id="estresores4" name="estresores[]" value=<?php echo (isset($estresores[3])) ? $estresores[3] : '';?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Estado Civil</b></label>
                                        <div class="form-check">
                                            <div>
                                                <input class="form-control" type="text" id="estado_civil" name="estao_civil" value=<?php echo $data1['estao_civil'];?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Ocupacion</b></label>
                                        <div class="form-check">
                                            <div>
                                                <input class="form-control" type="text" id="ocupacion" name="ocupacion" value=<?php echo $data1['ocupacion'];?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Lugar Donde Reside (Dir. Fisica)</b></label>
                                        <div class="form-check">
                                            <div>
                                                <input class="form-control" type="text" id="lugar_donde" name="lugar_donde" value=<?php echo $data1['lugar_donde'];?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Historial / Apoyo Social</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" id="historial_social" name="historial_social"><?php echo $data1['historial_social'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="detail-tab2" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Historial Psiquiatrico Presente</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" id="historial_presente" name="historial_presente"><?php echo $data1['historial_presente'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Medicamentos Psicotropicos</b></label>
                                        <div class="form-check d-flex">
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" id="medica1" name="medica[]" value=<?php echo (isset($medica[0])) ? $medica[0] : '';?>>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" id="medica2" name="medica[]" value=<?php echo (isset($medica[1])) ? $medica[1] : '';?>>
                                            </div>
                                        </div>
                                        <div class="form-check d-flex mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" id="medica3" name="medica[]" value=<?php echo (isset($medica[2])) ? $medica[2] : '';?>>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" id="medica4" name="medica[]" value=<?php echo (isset($medica[3])) ? $medica[3] : '';?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="exampleInputEmail1"><b>Historial de Uso de Substancias</b></label>
                                    <div class="form-group">
                                        <table class="table table-responsive table-bordered d-table">
                                            <thead>
                                                <th>Sustancia</th>
                                                <th>Cantidad</th>
                                                <th>Ultimo Consumo</th>
                                                <th>Disfuncion</th>
                                                <th>Comentarios</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <b>Alcohol</b>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_alcohol_canti" value="yes" <?php echo ($data1['hist_alcohol_canti'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_alcohol_canti" value="no" <?php echo ($data1['hist_alcohol_canti'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_alcohol_ultimo" value="yes" <?php echo ($data1['hist_alcohol_ultimo'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_alcohol_ultimo" value="no" <?php echo ($data1['hist_alcohol_ultimo'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_alcohol_disfuncion" value="yes" <?php echo ($data1['hist_alcohol_disfuncion'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_alcohol_disfuncion" value="no" <?php echo ($data1['hist_alcohol_disfuncion'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_alcohol_comantarios" value="yes" <?php echo ($data1['hist_alcohol_comantarios'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_alcohol_comantarios" value="no" <?php echo ($data1['hist_alcohol_comantarios'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Cannabis</b>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_cannabis_canti" value="yes" <?php echo ($data1['hist_cannabis_canti'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_cannabis_canti" value="no" <?php echo ($data1['hist_cannabis_canti'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_cannabis_ultimo" value="yes" <?php echo ($data1['hist_cannabis_ultimo'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_cannabis_ultimo" value="no" <?php echo ($data1['hist_cannabis_ultimo'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_cannabis_disfuncion" value="yes" <?php echo ($data1['hist_cannabis_disfuncion'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_cannabis_disfuncion" value="no" <?php echo ($data1['hist_cannabis_disfuncion'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_cannabis_comentarios" value="yes" <?php echo ($data1['hist_cannabis_comentarios'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_cannabis_comentarios" value="no" <?php echo ($data1['hist_cannabis_comentarios'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Cocaina</b>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_cocaina_canti" value="yes" <?php echo ($data1['hist_cocaina_canti'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_cocaina_canti" value="no" <?php echo ($data1['hist_cocaina_canti'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_cocaina_ultimo" value="yes" <?php echo ($data1['hist_cocaina_ultimo'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_cocaina_ultimo" value="no" <?php echo ($data1['hist_cocaina_ultimo'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_cocaina_disfuncion" value="yes" <?php echo ($data1['hist_cocaina_disfuncion'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_cocaina_disfuncion" value="no" <?php echo ($data1['hist_cocaina_disfuncion'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_cocaina_comentarios" value="yes" <?php echo ($data1['hist_cocaina_comentarios'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_cocaina_comentarios" value="no" <?php echo ($data1['hist_cocaina_comentarios'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Opioides</b>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_opioides_canti" value="yes" <?php echo ($data1['hist_opioides_canti'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_opioides_canti" value="no" <?php echo ($data1['hist_opioides_canti'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_opioides_ultimo" value="yes" <?php echo ($data1['hist_opioides_ultimo'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_opioides_ultimo" value="no" <?php echo ($data1['hist_opioides_ultimo'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_opioides_disfuncion" value="yes" <?php echo ($data1['hist_opioides_disfuncion'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_opioides_disfuncion" value="no" <?php echo ($data1['hist_opioides_disfuncion'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_opioides_comentarios" value="yes" <?php echo ($data1['hist_opioides_comentarios'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_opioides_comentarios" value="no" <?php echo ($data1['hist_opioides_comentarios'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Nicotina</b>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_nicotina_canti" value="yes" <?php echo ($data1['hist_nicotina_canti'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_nicotina_canti" value="no" <?php echo ($data1['hist_nicotina_canti'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_nicotina_ultimo" value="yes" <?php echo ($data1['hist_nicotina_ultimo'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_nicotina_ultimo" value="no" <?php echo ($data1['hist_nicotina_ultimo'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_nicotina_disfuncion" value="yes" <?php echo ($data1['hist_nicotina_disfuncion'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_nicotina_disfuncion" value="no" <?php echo ($data1['hist_nicotina_disfuncion'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_nicotina_comentarios" value="yes" <?php echo ($data1['hist_nicotina_comentarios'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_nicotina_comentarios" value="no" <?php echo ($data1['hist_nicotina_comentarios'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Otros</b>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_otros_canti" value="yes" <?php echo ($data1['hist_otros_canti'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_otros_canti" value="no" <?php echo ($data1['hist_otros_canti'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_otros_ultimo" value="yes" <?php echo ($data1['hist_otros_ultimo'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_otros_ultimo" value="no" <?php echo ($data1['hist_otros_ultimo'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_otros_disfuncion" value="yes" <?php echo ($data1['hist_otros_disfuncion'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_otros_disfuncion" value="no" <?php echo ($data1['hist_otros_disfuncion'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="hist_otros_comentarios" value="yes" <?php echo ($data1['hist_otros_comentarios'] == 'yes') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                Yes
                                                            </label>
                                                            <span class="ml-4">
                                                                <input class="form-check-input" type="radio" name="hist_otros_comentarios" value="no" <?php echo ($data1['hist_otros_comentarios'] == 'no') ? 'checked' : ''; ?>>
                                                                <label class="form-check-label" for="exampleRadios2">
                                                                    No
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Historial Psiquiatrico Previo</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" id="historial_previo" name="historial_previo"><?php echo $data1['historial_previo'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Historial Medico</b></label>
                                        <div class="form-check">
                                            <input class="form-control" type="text" id="historial_medico" name="historial_medico" value=<?php echo $data1['historial_medico'];?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Enfermedades</b></label>
                                        <div class="form-check">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="enferemedades[]" value="diabetes" <?php echo (in_array("diabetes", $enferemedades)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1">Diabetes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="enferemedades[]" value="enfermedad_cardio" <?php echo (in_array("enfermedad_cardio", $enferemedades)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Enfermedad Cardiovascular</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="enferemedades[]" value="otros" <?php echo (in_array("otros", $enferemedades)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Otros</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="enferemedades[]" value="hipertension" <?php echo (in_array("hipertension", $enferemedades)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Hipertension</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="enferemedades[]" value="enfermedad_renal" <?php echo (in_array("enfermedad_renal", $enferemedades)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Enfermedad Renal</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="enferemedades[]" value="epilepsia" <?php echo (in_array("epilepsia", $enferemedades)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Epilepsia</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="enferemedades[]" value="enfermedad_hepatica" <?php echo (in_array("enfermedad_hepatica", $enferemedades)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Enfermedad Hepatica</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" name="enfermedad_free_text" value=<?php echo $data1['enfermedad_free_text'];?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Medicamentos</b></label>
                                        <div class="form-check d-flex">
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" name="medicamentos[]" value=<?php echo (isset($medicamentos[0])) ? $medicamentos[0] : '';?>>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" name="medicamentos[]" value=<?php echo (isset($medicamentos[1])) ? $medicamentos[1] : '';?>>
                                            </div>
                                        </div>
                                        <div class="form-check d-flex mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" name="medicamentos[]" value=<?php echo (isset($medicamentos[2])) ? $medicamentos[2] : '';?>>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" name="medicamentos[]" value=<?php echo (isset($medicamentos[3])) ? $medicamentos[3] : '';?>>
                                            </div>
                                        </div>
                                        <div class="form-check d-flex mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" name="medicamentos[]" value=<?php echo (isset($medicamentos[4])) ? $medicamentos[4] : '';?>>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-control" type="text" name="medicamentos[]" value=<?php echo (isset($medicamentos[5])) ? $medicamentos[5] : '';?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
			    </div>-->
                            <input type="hidden" name="prescription_delete[]" id="prescription_delete" value="">
                      <table class='table'>
                        <thead>
                            <tr>
                              <th>Medicamento</th>
                              <th>Dosis</th>
                              <th>Unidad</th>
                              <th>Cantidad</th>
                              <th>Via</th>
                              <th>Route</th>
                              <th>Frecuencia</th>
                              <th>Refill</th>
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
<td style="display:flex;">
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
</select></td><td>
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
<td class="row">
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
</td>
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
<option label="mg/5cc" value="6">mg/5cc</option>-->
<option label="mcg" value="7">mcg</option>
<option label="grams" value="8">grams</option>
<option label="mL" value="9">mL</option>
</select></td>
                                <td>
    <input class="form-control" name="prescription[0][amount]" size="6">
</td>
<td style="display:flex;">
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
</select></td><td>
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
<td class="row">
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
</td>
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
                        </div>
                        </div>

                        <div class="tab-pane fade" id="detail-tab3" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Historial Psiquiatrico Familiar</b></label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hist_familiar" value="yes" <?php echo ($data1['hist_familiar'] == 'yes') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="exampleRadios1">
                                                Yes
                                            </label>
                                            <span class="ml-4">
                                                <input class="form-check-input" type="radio" name="hist_familiar" value="no" <?php echo ($data1['hist_familiar'] == 'no') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="exampleRadios2">
                                                    No
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Historial Educacion</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" id="historial_educacion" name="historial_educacion"><?php echo $data1['historial_educacion'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Historial Legal</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" id="historial_legal" name="historial_legal"><?php echo $data1['historial_legal'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Evaluacion de Riesgo</b></label>
                                        <div class="form-check">
                                            <input class="form-control" type="text" name="evaluacion_riesgo" value=<?php echo $data1['evaluacion_riesgo'];?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Acceso a Armas de Fuego</b></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="acceso_armas" value="yes" <?php echo ($data1['acceso_armas'] == 'yes') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="exampleRadios1">
                                                Yes
                                            </label>
                                            <span class="ml-4">
                                                <input class="form-check-input" type="radio" name="acceso_armas" value="no" <?php echo ($data1['acceso_armas'] == 'no') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="exampleRadios2">
                                                    No
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Risk Evaluation</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" id="risk_evaluation" name="risk_evaluation"><?php echo $data1['risk_evaluation'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Examen Mental (al momento de la evaluacion)</b></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Apariencia / Higiene</b></label>
                                        <div class="form-check">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="apariencia[]" value="adecuada" <?php echo (in_array("adecuada", $apariencia)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1">Adacuada</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="apariencia[]" value="tatuajes" <?php echo (in_array("tatuajes", $apariencia)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Tatuajes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="apariencia[]" value="desalinado" <?php echo (in_array("desalinado", $apariencia)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Desalinado</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="apariencia[]" value="vestimental_inusual" <?php echo (in_array("vestimental_inusual", $apariencia)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Vestimental Inusual</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="apariencia[]" value="mal_olor" <?php echo (in_array("mal_olor", $apariencia)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Mal Olar</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Alucinaciones</b></label>
                                        <div class="form-check">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="alucinaciones[]" value="visuales"  <?php echo (in_array("visuales", $alucinaciones)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1">Visuales</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="alucinaciones[]" value="olafatorias" <?php echo (in_array("olafatorias", $alucinaciones)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Olafatorias</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="alucinaciones[]" value="auditivas"  <?php echo (in_array("auditivas", $alucinaciones)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Auditivas</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="alucinaciones[]" value="gustatorias"  <?php echo (in_array("gustatorias", $alucinaciones)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Gustatorias</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="alucinaciones[]" value="tactiles"  <?php echo (in_array("tactiles", $alucinaciones)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Tactiles</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Actitud Hacia Evaluador</b></label>
                                        <div class="form-check">
                                            <input class="form-control" type="text" name="actitud_evaluador" value=<?php echo $data1['actitud_evaluador'];?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Comportamiento Sicomotor</b></label>
                                        <div class="form-check">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="sicomotor[]" value="promedio" <?php echo (in_array("promedio", $sicomotor)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1">Promedio</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="sicomotor[]" value="agitacion" <?php echo (in_array("agitacion", $sicomotor)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Agitacion</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="sicomotor[]" value="retardacion" <?php echo (in_array("retardacion", $sicomotor)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Retardacion</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Expresion Verbal</b></label>
                                        <div class="form-check">
                                            <input class="form-control" type="text" name="expresion_verbal" value=<?php echo $data1['expresion_verbal'];?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Orientacion</b></label>
                                        <div class="form-check">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="orientacion[]" value="persona" <?php echo (in_array("persona", $orientacion)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1">Persona</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="orientacion[]" value="tiempo" <?php echo (in_array("tiempo", $orientacion)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Tiempo</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="orientacion[]" value="lugar" <?php echo (in_array("lugar", $orientacion)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Lugar</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-control" type="text" name="orientacion_text" value=<?php echo $data1['orientacion_text'];?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Memoria Inmediata</b></label>
                                        <div class="form-check">
                                            <input class="form-control" type="text" name="memoria_inmediata" value=<?php echo $data1['memoria_inmediata'];?>>
                                        </div>
                                        <label for="exampleInputEmail1"><b>Memoria Reciente</b></label>
                                        <div class="form-check">
                                            <input class="form-control" type="text" name="memoria_reciente" value=<?php echo $data1['memoria_reciente'];?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Atencion y Concentracion</b></label>
                                        <div class="form-check">
                                            <input class="form-control" type="text" name="atencion_y_concentracion" value=<?php echo $data1['atencion_y_concentracion'];?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Juicio</b></label>
                                        <div class="form-check">
                                            <input class="form-control" type="text" name="juicio" value=<?php echo $data1['juicio'];?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Afecto</b></label>
                                        <div class="form-check">
                                            <input class="form-control" type="text" name="afecto" value=<?php echo $data1['afecto'];?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Proceso de Pensamiento</b></label>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><b>Logico</b></label>
                                            <div class="form-check">
                                                <input class="form-control" type="text" name="logico" value=<?php echo $data1['logico'];?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><b>Irrelevante</b></label>
                                            <div class="form-check">
                                                <input class="form-control" type="text" name="irrelevante" value=<?php echo $data1['irrelevante'];?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><b>Coherente</b></label>
                                            <div class="form-check">
                                                <input class="form-control" type="text" name="coherente" value=<?php echo $data1['coherente'];?>>
                                            </div>
                                        </div>
                                        <div class="form-check">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="proceso_de_pensamiento[]" value="fuga_de_ideas" <?php echo (in_array("fuga_de_ideas", $proceso_de_pensamiento)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1">Fuga de Ideas</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="proceso_de_pensamiento[]" value="circumstancial" <?php echo (in_array("circumstancial", $proceso_de_pensamiento)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Circumstancial</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="proceso_de_pensamiento[]" value="tangencial" <?php echo (in_array("tangencial", $proceso_de_pensamiento)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Tangencial</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="proceso_de_pensamiento[]" value="asociaciones_laxes" <?php echo (in_array("asociaciones_laxes", $proceso_de_pensamiento)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Asociaciones Laxes</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Delirios</b></label>
                                        <div class="form-check">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="delirios[]" value="paranoide" <?php echo (in_array("paranoide", $delirios)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox1">Paranoide</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="delirios[]" value="grandiosidad" <?php echo (in_array("grandiosidad", $delirios)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Grandiosidad</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="delirios[]" value="hiper_religiosidad" <?php echo (in_array("hiper_religiosidad", $delirios)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Hiper-Religiosidad</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="delirios[]" value="celotipicos" <?php echo (in_array("celotipicos", $delirios)) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="inlineCheckbox2">Celotipicos</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Ideas Suicidas</b></label>
                                        <div class="form-check">
                                            <input class="form-control" type="text" name="ideas_suicidas" value=<?php echo $data1['ideas_suicidas'];?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Ideas Homicidas</b></label>
                                        <div class="form-check">
                                            <input class="form-control" type="text" name="ideas_homicidas" value=<?php echo $data1['ideas_homicidas'];?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <textarea class="form-control" id="ideas_free_text" name="ideas_free_text"><?php echo $data1['ideas_free_text'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Diagnostico / Apreciacion / Educacion</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" id="apreciacion_education" name="apreciacion_education"><?php echo $data1['apreciacion_education'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="detail-tab4" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Diagnostico / Apreciacion / Educacion</b></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Signs and Symptoms</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" id="signs_symptoms" name="signs_symptoms"><?php echo $data1['signs_symptoms'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Diagnoses</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" id="diagnoses" name="diagnoses"><?php echo $data1['diagnoses'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Rational of Recommended Treatment</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" id="rational_recommended" name="rational_recommended"><?php echo $data1['rational_recommended'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Education / Orientation</b></label>
                                        <div class="form-check">
                                            <textarea class="form-control" id="eduction_orientation" name="education_orientation"><?php echo $data1['education_orientation'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><b>Plan:</b></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><b>Pharmacotherapy</b></label>
                                            <div class="form-check">
                                                <textarea class="form-control" id="pharmacotherapy" name="pharmacotherapy"><?php echo $data1['pharmacotherapy'];?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><b>Psychotherapy</b></label>
                                            <div class="form-check">
                                                <textarea class="form-control" id="psychitherapy" name="psychitherapy"><?php echo $data1['psychitherapy'];?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><b>Laboratories</b></label>
                                            <div class="form-check">
                                                <textarea class="form-control" id="laboratories" name="laboratories"><?php echo $data1['laboratories'];?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><b>Referrals</b></label>
                                            <div class="form-check">
                                                <textarea class="form-control" id="referrals" name="referrals"><?php echo $data1['referrals'];?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><b>Follow-up</b></label>
                                            <div class="form-check">
                                                <textarea class="form-control" id="folloup" name="followup"><?php echo $data1['followup'];?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3 d-flex justify-content-center">
                    <div class="btn-toolbar" role="group" aria-label="Basic example">
                        <button type="button" id="next" class="btn btn-primary">Next</button>
                        <button type="submit" id="save" class="ml-2 btn btn-success" style="display:none">Save</button>
                        <button type="button" id="cancel" class="ml-2 btn btn-danger" onclick="top.restoreSession();parent.closeTab(window.name, true);">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php
    // TBD: If $alertmsg, display it with a JavaScript alert().
?>
<script>
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
$('select.interval').on('change', function() {
        if(this.value == '19'){
                $(this).siblings('input').removeClass('freqother-hidden');
        } else if(this.value != '19' && !$(this).siblings('input').hasClass('freqother-hidden')){
                $(this).siblings('input').addClass('freqother-hidden').val('');
        }
});
</script>
</body>

</html>
