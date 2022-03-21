<?php

/*
 *  package   Comlink OpenEMR
 *  link      http://www.open-emr.org
 *  author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  copyright Copyright (c )2022. Sherwin Gaddis <sherwingaddis@gmail.com>
 *  license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once(dirname(__FILE__) . '/../../globals.php');
require_once($GLOBALS["srcdir"] . "/api.inc");

function formato_psiquiatrico_2_report($pid, $encounter, $cols, $id)
{
	$data = sqlQuery("select * from formato_psiquiatrico_2_form where pid=? and id=?", array($pid,$id));
        $data1 = sqlStatement("select * from formato2_medicamento where form_id=?",$id);
        $drugs_code1 = [];
        while($row=sqlFetchArray($data1)){
array_push($drugs_code1,$row);
        }
        print '<style>
                .formato th, .formato td,.formato tr {
  border:1px solid black;
  border-collapse:collapse;
padding: 10px;
}

.formato table {
  border:2px solid black;
  border-collapse:collapse;
}

.formato td {
  font-family:Arial;
  font-size:11px;
}
</style>';

//print_r($data);
print '
<div class="form-group col-md-12">
<b>Lugar donde se encuentra :</b><br>';
echo str_replace(',','<br>',$data['encuentra']);
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Proposito de le Evaluacion :</b><br>';
echo str_replace(',','<br>',$data['proposito']);
print'
</div>';

print '
<div class="form-group col-md-12">
<b>HIPAA / Ley 408 / Consentimiento :</b><br>';
echo $data['hipaa_ley'];
print'
</div>';

print '
<div class="form-group col-md-12">
<b>Fuentes de Informacion :</b><br>';
echo str_replace(',','<br>',$data['fuentes']);
print'
</div>';

print '
<div class="form-group col-md-12">
<b>Si aplica, contacte a los siguientes individuos :</b><br>';
echo str_replace(',','<br>',$data['si_aplica']);
print'
</div>';

print '
<div class="form-group col-md-12">
<b>Estresores :</b><br>';
echo str_replace(',','<br>',$data['estresores']);
print'
</div>';

print '
<div class="form-group col-md-12">
<b>Estado Civil :</b><br>';
echo $data['estao_civil'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Ocupacion :</b><br>';
echo $data['ocupacion'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Lugar Donde Reside (Dir. Fisica) :</b><br>';
echo $data['lugar_donde'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Historial / Apoyo Social :</b><br>';
echo $data['historial_social'];
print'
</div>';

print '
<div class="form-group col-md-12">
<b>Historial Psiquiatrico Presente :</b><br>';
echo $data['historial_presente'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Medicamentos Psicotropicos :</b><br>';
echo str_replace(',','<br>',$data['medica']);
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Historial de Uso de Substancias :</b><br>';
echo '<table class="table table-bordered"><thead>
	<tr><th>Sustancia</th><th>Cantidad</th><Ultimo Consumo</th><th>Disfunction</th><th>Comentarios</th></tr>
</thead>
<tbody>
<tr>
<td><b>Alcohol</b></td>
<td>';
echo ($data["hist_alcohol_canti"] == "yes" ? "Yes" : "");
echo ($data["hist_alcohol_canti"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_alcohol_ultimo"] == "yes" ? "Yes" : "");
echo ($data["hist_alcohol_ultimo"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_alcohol_disfuncion"] == "yes" ? "Yes" : "");
echo ($data["hist_alcohol_disfuncion"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_alcohol_comantarios"] == "yes" ? "Yes" : "");
echo ($data["hist_alcohol_comantarios"] == "no" ? "No" : "");
echo'</td></tr>';

echo '<tr>
<td><b>Cannabis</b></td>
<td>';
echo ($data["hist_cannabis_canti"] == "yes" ? "Yes" : "");
echo ($data["hist_cannabis_canti"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_cannabis_ultimo"] == "yes" ? "Yes" : "");
echo ($data["hist_cannabis_ultimo"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_cannabis_disfuncion"] == "yes" ? "Yes" : "");
echo ($data["hist_cannabis_disfuncion"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_cannabis_comentarios"] == "yes" ? "Yes" : "");
echo ($data["hist_cannabis_comentarios"] == "no" ? "No" : "");
echo'</td></tr>';

echo '<tr>
<td><b>Cocaina</b></td>
<td>';
echo ($data["hist_cocaina_canti"] == "yes" ? "Yes" : "");
echo ($data["hist_cocaina_canti"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_cocaina_ultimo"] == "yes" ? "Yes" : "");
echo ($data["hist_cocaina_ultimo"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_cocaina_disfuncion"] == "yes" ? "Yes" : "");
echo ($data["hist_cocaina_disfuncion"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_cocaina_comentarios"] == "yes" ? "Yes" : "");
echo ($data["hist_cocaina_comentarios"] == "no" ? "No" : "");
echo'</td></tr>';

echo '<tr>
<td><b>Opioides</b></td>
<td>';
echo ($data["hist_opioides_canti"] == "yes" ? "Yes" : "");
echo ($data["hist_opioides_canti"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_opioides_ultimo"] == "yes" ? "Yes" : "");
echo ($data["hist_opioides_ultimo"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_opioides_disfuncion"] == "yes" ? "Yes" : "");
echo ($data["hist_opioides_disfuncion"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_opioides_comentarios"] == "yes" ? "Yes" : "");
echo ($data["hist_opioides_comentarios"] == "no" ? "No" : "");
echo'</td></tr>';

echo '<tr>
<td><b>Nicotina</b></td>
<td>';
echo ($data["hist_nicotina_canti"] == "yes" ? "Yes" : "");
echo ($data["hist_nicotina_canti"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_nicotina_ultimo"] == "yes" ? "Yes" : "");
echo ($data["hist_nicotina_ultimo"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_nicotina_disfuncion"] == "yes" ? "Yes" : "");
echo ($data["hist_nicotina_disfuncion"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_nicotina_comentarios"] == "yes" ? "Yes" : "");
echo ($data["hist_nicotina_comentarios"] == "no" ? "No" : "");
echo'</td></tr>';

echo '<tr>
<td><b>Otros</b></td>
<td>';
echo ($data["hist_otros_canti"] == "yes" ? "Yes" : "");
echo ($data["hist_otros_canti"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_otros_ultimo"] == "yes" ? "Yes" : "");
echo ($data["hist_otros_ultimo"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_otros_disfuncion"] == "yes" ? "Yes" : "");
echo ($data["hist_otros_disfuncion"] == "no" ? "No" : "");
echo '</td><td>';
echo ($data["hist_otros_comentarios"] == "yes" ? "Yes" : "");
echo ($data["hist_otros_comentarios"] == "no" ? "No" : "");
echo'</td></tr>';

echo '</table>';
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Historial Psiquiatrico Previo :</b><br>';
echo $data['historial_previo'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Historial Medico :</b><br>';
echo $data['historial_medico'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Enfermedades :</b><br>';
echo str_replace(',','<br>',$data['enferemedades']);
print'
</div>';

print '<div class="form-group col-md-12">
<b>Alergias</b><br>
'.$data['alergias'].'
</div>
<div class="form-group col-md-12 formato">
                      <table class="table table-bordered">
                        <thead>
                            <tr>
                              <th>Medicamento</th>
                              <th>Dosis</th>
                              <th>Unidad</th>
                              <th>Cantidad</th>
                              <th>Via</th>
                              <th>Route</th>
                              <th>Frecuencia</th>
                              <th>Recargas</th>
                            </tr>
                      </tr>
                      </thead>


                        <tbody class="table mainrow_drug1">
'; foreach ($drugs_code1 as $key => $dc) { print'<tr class="rowdrug_1 first_primary"><td>'.$dc['medicamento'].'
                                  <td>'.$dc['dosis'].'</td>
                                    <td>'.med_unit($dc['unit']).'</td>
                                    <td>'.$dc['amount'].'</td>
                                    <td>'.med_form($dc['form']).'</td><td>'.med_route($dc['route']).'</td>
                                    <td>'.($dc['interval'] == '19' ? ('Other - '.$dc['freq_other']) :med_interval($dc['interval'])).'</td>
                                    <td>'.$dc['refills'].'</td>
                                </tr>';
                         }
                        print '</tbody>
                                                </table>
</div>';
print '
<div class="form-group col-md-12">
<b>Historial Psiquiatrico Familiar :</b><br>';
echo $data['hist_familiar'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Historial Educacion :</b><br>';
echo $data['historial_educacion'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Historial Legal :</b><br>';
echo $data['historial_legal'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Evaluacion de Riesgo :</b><br>';
echo $data['evaluacion_riesgo'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Acceso a Armas de Fuego :</b><br>';
echo $data['acceso_armas'];
print'
</div>';

print '
<div class="form-group col-md-12">
<b>Risk Evaluation :</b><br>';
echo $data['risk_evaluation'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Examen Mental (al momento de la evaluacion) :</b><br>
<b>Apariencia / Higiene :</b><br>';
echo str_replace(',','<br>',$data['apariencia']);
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Alucinaciones :</b><br>';
echo str_replace(',','<br>',$data['alucinaciones']);
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Actitud Hacia Evaluador :</b><br>';
echo $data['actitud_evaluador'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Comportamiento Sicomotor :</b><br>';
echo str_replace(',','<br>',$data['sicomotor']);
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Expresion Verbal :</b><br>';
echo $data['expresion_verbal'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Orientacion :</b><br>';
echo str_replace(',','<br>',$data['orientacion']);
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Memoria Inmediata :</b><br>';
echo $data['memoria_inmediata'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Memoria Reciente :</b><br>';
echo $data['memoria_reciente'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Atencion y Concentracion :</b><br>';
echo $data['atencion_y_concentracion'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Juicio :</b><br>';
echo $data['juicio'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Afecto :</b><br>';
echo $data['afecto'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Proceso de Pensamiento :</b><br>
<b>Logico :</b><br>';
echo $data['logico'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Irrelevante :</b><br>';
echo $data['irrelevante'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Coherente :</b><br>';
echo $data['coherente'];
print'
<br>';
echo str_replace(',','<br>',$data['proceso_de_pensamiento']);
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Delirios :</b><br>';
echo $data['delirios'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Ideas Suicidas :</b><br>';
echo $data['ideas_suicidas'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Ideas Homicidas :</b><br>';
echo $data['ideas_homicidas'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b> :</b><br>';
echo $data['ideas_free_text'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Diagnostico / Apreciacion / Educacion :</b><br>';
echo $data['apreciacion_education'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Diagnostico / Apreciacion / Educacion :</b><br>
<b>Signs and Symptoms :</b><br>';
echo $data['signs_symptoms'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Diagnoses :</b><br>';
echo $data['diagnoses'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Rational of Recommended Treatment :</b><br>';
echo $data['rational_recommended'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Education / Orientation :</b><br>';
echo $data['education_orientation'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Plan :</b><br>
<b>Pharmacotherapy</b><br>';
echo $data['pharmacotherapy'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Psychotherapy :</b><br>';
echo $data['psychitherapy'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Laboratories :</b><br>';
echo $data['laboratories'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Referrals :</b><br>';
echo $data['referrals'];
print'
</div>';
print '
<div class="form-group col-md-12">
<b>Follow-up :</b><br>';
echo $data['followup'];
print'
</div>';
}

