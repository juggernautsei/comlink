<?php
require_once(dirname(__file__)."/../../globals.php");
/*
function med_unit($val){
	$units = ['1'=>'mg', '2'=>'mg/1cc','3'=>'mg/2cc','4'=>'mg/3cc','5'=>'mg/4cc','6'=>'mg/5cc','7'=>'mcg','8'=>'grams','9'=>'mL'];
	return $units[$val];
}

function med_form($val) {
	$forms = ['1'=>'suspension','2'=>'tablet','3'=>'capsule','4'=>'solution','5'=>'tsp','6'=>'ml','7'=>'units','8'=>'inhalations','9'=>'gtts(drops)','10'=>'cream','11'=>'ointment','12'=>'puff'];
	return $forms[$val];
}

function med_route($val) {
	$routes = ['1'=>'Per Oris','2'=>'Per Rectum','3'=>'To Skin','4'=>'To Affected Area','5'=>'Sublingual','6'=>'OS','7'=>'OD','8'=>'OU','9'=>'SQ','10'=>'IM','11'=>'IV','12'=>'Per Nostril','13'=>'Both Ears','14'=>'Left Ear','15'=>'Right Ear','inhale'=>'Inhale','intradermal'=>'Intradermal','other'=>'Other/Miscellaneous','transdermal'=>'Transdermal','intramuscular'=>'Intramuscular'];
	return $routes[$val];
}

function med_interval($val) {
	$interval = ['1'=>'b.i.d','2'=>'t.i.d','3'=>'q.i.d','4'=>'q.3h','5'=>'q.4h','6'=>'q.5h','7'=>'q.6h','8'=>'q.8h','9'=>'q.d','10'=>'a.c','11'=>'p.c','12'=>'a.m','13'=>'p.m','14'=>'ante','15'=>'h','16'=>'h.s','17'=>'p.r.n','18'=>'stat'];
	return $interval[$val];
}
 
 */
function formato_psiquiatrico_report($pid, $encounter, $cols, $id)
{
	$data = sqlQuery("select * from form_formato_psiquiatrico where pid=? and id=?", array($pid,$id));
	$data1 = sqlStatement("select * from formato_medicamento1 where form_id=?",$id);
	$data2 = sqlStatement("select * from formato_medicamento2 where form_id=?",$id);
        $drugs_code1 = [];
        while($row=sqlFetchArray($data1)){
array_push($drugs_code1,$row);
	}
	$drugs_code2 = [];
        while($row=sqlFetchArray($data2)){
array_push($drugs_code2,$row);
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
if(trim($data["razon_para"] != '')){
print '<div class="form-group col-md-12">
      <b>
       Razon para solicitar servicios
      </b><br>
      '.$data["razon_para"].'
</div>';
}
if(trim($data['eval_psiquiatrica']) != '')
{
	print '<div class="form-group col-md-12">
<b>Evaluacion Psiquiatrica</b>
<br>
'.$data['eval_psiquiatrica'].'
</div>';
}
if(trim($data['cues_sobre']) != ''){
print '<div class="form-group col-md-12">
<b>Cuestionario sobre enfermedad presente</b>
<br>
'.$data['cues_sobre'].'
</div>';
}
if(trim($data['nombre_de']) != ''){
print '<div class="form-group col-md-12">
<b> Nombre de Informante principal</b><br>
'.$data['nombre_de'].'
</div>';
}
if(trim($data['relacion'] != '')){
print '<div class="form-group col-md-12">
<b>Relacion con el paciente</b>
<br>'.$data['relacion'].' '.(($data['relacion'] == 'other') ? (' - '.$data['relacion_other']) : '').'
</div>';
}
if(trim($data['fecha_de']) != ''){
print '<div class="form-group col-md-12">
<b>Fecha de Ingreso a la institucion</b><br>
'.$data['fecha_de'].'
</div>';
}
if(trim($data['cual_fue']) != ''){
print '<div class="form-group col-md-12">
<b>Cual fue la razon del ingeso de paciente?</b><br>
'.$data['cual_fue'].' '.(($data['cual_fue'] == 'other') ? (' - '.$data['cual_fue_other']) : '').'
</div>';
}
if(trim($data['padece_la'])!=''){
print '<div class="form-group col-md-12">
<b>Padece la paciente de alguna condicion mental diagnosticada oficialmente por un medico o psicologo?</b><br>
'.$data['padece_la'].' '.(($data['padece_la'] == 'other') ? (' - '.$data['padece_la_other']) : '').'
</div>';
}
if(trim($data['por_cuanto_days'])!=''){
print '<div class="form-group col-md-12">
<b>Por cuanto tiempo?</b><br>
'.$data['por_cuanto_days'].' (quantity) &nbsp;'.$data['por_cuanto'].' '.(($data['por_cuanto'] == 'other') ? (' - '.$data['por_cuanto_other']) : '').'
</div>';
}
if($data['diagnostico_2'] != ''){
print '<div class="form-group col-md-12">
<b>Diagnostico 2</b><br>
'.$data['diagnostico_2'].' '.(($data['diagnostico_2'] == 'other') ? (' - '.$data['diagnostico_2_other']) : '').'
</div>';
}
if($data['diagnostico_3'] != ''){
print '<div class="form-group col-md-12">
<b>Diagnostico 3</b><br>
'.$data['diagnostico_3'].' '.(($data['diagnostico_3'] == 'other') ? (' - '.$data['diagnostico_3_other']) : '').'
</div>';
}
if($data['diagnostico_4'] != ''){
print '<div class="form-group col-md-12">
<b>Diagnostico 4</b><br>
'.$data['diagnostico_4'].' '.(($data['diagnostico_4'] == 'other') ? (' - '.$data['diagnostico_4_other']) : '').' <br>
</div>';
}
if($data['que_cambios'] != ''){
print '<div class="form-group col-md-12">
<b>Que cambios recientes ha habido en la conducta o en los sintomas del paciente? (No incluir conductas o sintomas que han sido la norma en la paciente desde siempre o que estan bien controlados con medicamentos psiquiatricos)?</b><br>
'.$data['que_cambios'].' '.(($data['que_cambios'] == 'other') ? (' - '.$data['que_cambios_other']) : '').'<br>
</div>';
}
if(trim($data['por_cuanto_que_days']) != ''){
print '<div class="form-group col-md-12">
<b>Por cuanto tiempo?</b><br>
'.$data['por_cuanto_que_days'].' (quantity) &nbsp;'.$data['por_cuanto_que'].' '.(($data['por_cuanto_que'] == 'other') ? (' - '.$data['por_cuanto_que_days_other']) : '').'<br>
</div>';
} 
if($data['his_ninguna'] != '' || $data['his_derrames'] != '' || $data['his_enfermedad'] !='' || $data['his_senil']!='' || $data['his_alzheimor']!='' || $data['his_vascular'] != '' || $data['his_hipotiro'] != '' || $data['his_diabetes'] !='' || $data['his_cardiaca'] !='' || $data['his_arterial'] != '' || $data['his_altos'] != '' || $data['his_asma']!='' || $data['his_enfisema']!=''||$data['his_copd']!=''||$data['his_reflujo']!=''||$data['his_artritis']!=''||$data['his_osteo']!=''||$data['his_fibro']!=''||$data['his_neuro']!=''||$data['his_discos']!='') {
print '
<div class="form-group col-md-12">
<b>Historial de condiciones medicas Generales:</b><br>
'; echo (($data['his_ninguna'] == "ninguna") ? "Ninguna<br>" : "");
echo (($data['his_derrames'] == "derrames") ? "Derrames Cerebraies<br>" :"");
echo (($data['his_epilepsia'] == "epilepsia") ?"Epilepsia<br>":"");
echo (($data['his_enfermedad']=="enfermedad")?"Enfermedad de parkinson<br>":"");
echo (($data['his_senil']=="senil")?"Demencia Senil<br>":"");
echo (($data['his_alzheimor']=="alzheimor")?"Demencia Alzheimor<br>":"");
echo (($data['his_vascular']=="vascular")?"Demencia Vascular<br>":"");
echo (($data['his_hipotiro']=="hipotiro")?"Hipotiroidismo<br>":"");
echo (($data['his_diabetes']=="diabetes")?"Diabetes<br>":"");
echo (($data['his_cardiaca']=="cardiaca")?"Enfermedad Cardiaca<br>":"");
echo (($data['his_arterial']=="arterial")?"Hipertension Arterial<br>":"");
echo (($data['his_altos']=="altos")?"Colesterol o Trigliceridos Altos<br>":"");
echo (($data['his_asma']=="asma")?"Asma Bronquial<br>":"");
echo (($data['his_enfisema']=="enfisema")?"Enfisema<br>":"");
echo (($data['his_copd']=="copd")?"COPD<br>":"");
echo (($data['his_reflujo']=="reflujo")?"Reflujo o Gastritis<br>":"");
echo (($data['his_artritis']=="artritis")?"Artritis<br>":"");
echo (($data['his_osteo']=="osteo")?"Osteoporosis<br>":"");
echo (($data['his_fibro']=="fibro")?"Fibromialgia<br>":"");
echo (($data['his_neuro']=="neuro")?"Neuropatia<br>":"");
echo (($data['his_discos']=="discos")?"Discos Herniados<br>":"");
echo (($data['his_discos']=="other")?("Other - ".$data['favor_de']."<br>"):"");
print'
</div>';
}
//<div class="form-group col-md-12">
//<b>Other</b><br>
//'.$data['favor_de'].'
//</div>
if($data['c_ninguna'] != '' || $data['c_cabg']!=''||$data['c_impde']!=''||$data['c_imppro']!=''||$data['c_apende']!=''||$data['c_coli']!=''||$data['c_herni']!=''||$data['c_amp']!=''||$data['c_cir']!=''){
print '<div class="form-group col-md-12">
<b>Cirugias o Procedimientos:</b><br>';
echo (($data['c_ninguna'] =="ninguna") ? "Ninguna<br>" : "");
echo (($data['c_cabg'] == "cabg") ? "Revascularizacion Coronaria (CABG)<br>" :"");
echo (($data['c_impde'] == "impde") ?"Implantacion de Marcapaso<br>":"");
echo (($data['c_imppro'] == "imppro") ?"Implantacion de protesis<br>":"");
echo (($data['c_apende'] == "apende") ?"Apendectomia<br>":"");
echo (($data['c_coli'] == "coli") ?"Colicistectomia<br>":"");
echo (($data['c_herni'] == "herni") ?"Herniorrafia<br>":"");
echo (($data['c_amp'] == "amp") ?"Amputacion de Extremidad<br>":"");
echo (($data['c_cir'] == "cir") ?"Cirugia de Cadera<br>":"");
echo (($data['c_cir'] == "other") ?("Other - ".$data['favor_de_no']."<br>"):"");
print'
</div>';
}
//<div class="form-group col-md-12">
//<b>Favor de no escribir debajo de la linea roja</b><br>
//'.$data['favor_de_no'].'
//</div>
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
if($data['hos_si'] != '' || $data['hos_no'] != '' || $data['hos_se']!=''){
print '<div class="form-group col-md-12">
<h4>Historial Psiquiatrico</h4>
<b>Hospitalizaciones Psiquiatricas</b> ';
echo (($data['hos_si'] == "si") ?"Si ":"");
echo (($data['hos_no'] == "no") ?"No ":"");
echo (($data['hos_se'] == "se") ?"SE DESCONOCE ":"");

print'
</div>';
}
if($data['int_si'] != '' || $data['int_no'] != '' || $data['int_se']!=''){
print '<div class="form-group col-md-12">
<b>Intentos Suicidas</b>:';
echo (($data['int_si'] == "si") ?"Si ":"");
echo (($data['int_no'] == "no") ?"No ":"");
echo (($data['int_se'] == "se") ?"SE DESCONOCE":"");

print'
</div>';
}
if($data['abu_si'] != '' || $data['abu_no'] != '' || $data['abu_se'] !=''){
print '<div class="form-group col-md-12">
<b>Abuso de Sustancias o Alcohol</b> : ';
echo (($data['abu_si'] == "si") ?"Si ":"");
echo (($data['abu_no'] == "no") ?"No ":"");
echo (($data['abu_se'] == "se") ?"SE DESCONOCE":"");
print'
</div>';
}

print '<div class="form-group col-md-12">
<b>Historial Familiar Psiquiatrico</b> : ';
echo (($data['his_si'] == "si") ?"Si ":"");
echo (($data['his_se'] == "se") ?"Se desconoce ":"");
echo (($data['his_no'] == "no") ?"No":"");
print'
</div>
<div class="form-group col-md-12">
<b>Historial de Efectos Adversos a Medicamentos</b> : ';
echo (($data['hise_si'] == "si") ?"Si ":"");
echo (($data['hise_se'] == "se") ?"Se desconoce ":"");
echo (($data['hise_no'] == "no") ?"No":"");
print'
</div>
<div class="form-group col-md-12">
<b>Comentarios</b><br>
'.$data['hos_comm'].'
</div>
<div class="form-group col-md-12">
<h4>Examen Mental</h4>
<b>Apariencia:</b><br>
'.$data['apariencia'].'<br>
'.$data['apariencia_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Actividad Motora:</b><br>
'.$data['actividadm'].'<br>
'.$data['actividadm_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Actitud:</b><br>
'.$data['actitud'].'<br>
'.$data['actitud_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Conducta</b><br>
'.$data['conducta'].'<br>
'.$data['conducta_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Habla:</b><br>
'.$data['habia'].'<br>
'.$data['habia_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Afecto:</b><br>
'.$data['afecto'].'<br>
'.$data['afecto_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Talante:</b><br>
'.$data['talante'].'<br>
'.$data['talante_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Percepción</b><br>
'.$data['percepcion'].'<br>
'.$data['percepcion_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Contenido de Pensamiento</b><br>
'.$data['contenido'].'<br>
'.$data['contenido_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Relevancia de sus Respuestas</b><br>
'.$data['relevancia'].'<br>
'.$data['relevancia_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Procesamiento de Pensamiento</b><br>
'.$data['proces_de'].'<br>
'.$data['proces_de_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Nivel de Atención</b><br>
'.$data['nivel'].'<br>
'.$data['nivel_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Concentración</b><br>
'.$data['concentracion'].'<br>
'.$data['concentracion_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Orientación</b><br>
'.$data['orientacion'].'<br>
'.$data['orientacion_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Memoria</b><br>
'.$data['memoria'].'<br>
'.$data['memoria_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Juicio</b><br>
'.$data['juicio'].'<br>
'.$data['juicio_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Introspección</b><br>
'.$data['introspeccion'].'<br>
'.$data['introspeccion'].'
</div>
<div class="form-group col-md-12">
<b>Ideas Suicidas</b><br>
'.$data['ideass'].'<br>
'.$data['ideass_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Ideas Homicidas</b><br>
'.$data['ideash'].'<br>
'.$data['ideash_cmnt'].'
</div>
<div class="form-group col-md-12">
<b>Impresion Diagnostica</b><br>
'.$data['imp_dia'].'
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
'; foreach ($drugs_code2 as $key => $dc) { print'
                                <tr class="rowdrug_1 first_primary">
                                  <td>'.$dc['medicamento'].'
				  <td>'.$dc['dosis'].'</td>
<td>'.med_unit($dc['unit']).'</td>
                                    <td>'.$dc['amount'].'</td>
				    <td>'.med_form($dc['form']).'</td><td>'.med_route($dc['route']).'</td>
				    <td>'.($dc['interval'] == '19' ? ('Other - '.$dc['freq_other']) :med_interval($dc['interval'])).'</td>
                                    <td>'.med_interval($dc['interval']).'</td>
                                    <td>'.$dc['refills'].'</td>
                                </tr>';
                         }
                        print '</tbody>
                                                </table>
</div>

<h4>Orientacion y Recomendaciones</h4>
<p>Se orienta sobre diagnosticos psiquiatricos forma de tomar los medicamentos y posibles efectos secundarios<br>Se indica informa de forma oportuna sobre efectos secundarios<br>
Se recomienda formar precauciones para evitar caidas<br>
Llamar al 9-1-1 o acudir al hospital en caso de cualquier situacion que pueda amenazor la vida de la paciente, la propiedad u otras personas<br>
Tomar medicamentos segun especificados en receta medica y no hacer cambios per cuenta propia</p>


';
/*	print "<style>input[type='checkbox'][readonly] {
  pointer-events: none;
}</style>";
	print "<table><tr><td>\n";
echo '		<div class="form-group col-md-12">
      <label for="inputEmail4">Modalidad : &nbsp;&nbsp;</label>
      <input type="checkbox" name="modalidad" value="individual" '.(($data['modalidad'] =="individual") ? "checked" : "").' readonly>
      <label for="inputPassword4">Individual </label>
      <input type="checkbox" name="individual" value="con_paciente" '. (($data['individual'] == "con_paciente") ? "checked" :"").' readonly>
      <label for="inputEmail4">Fam.Con Paciente </label>
      <input type="checkbox" name="con_paciente" value="sin_paciente" '.(($data['con_paciente'] == "sin_paciente") ?"checked":"").' readonly>
      <label for="inputPassword4">Fam.Sin Paciente </label>
      <input type="checkbox" name="sin_paciente" value="grupal" '.(($data['sin_paciente']=="grupal")?"checked":"").' readonly>
      <label for="inputPassword4">Grupal </label>
    </div>
';
	print '<div class="form-group col-md-12">
      <label class="form-check-label" for="gridCheck">
       Diagnostico :
      </label>
      <span >'.$data['diagnostico'].'</span>
  </div>';
	print '<div class="form-group col-md-12 form-inline">
      <label for="inputEmail4">Actitud : &nbsp;&nbsp;</label>
      <input type="checkbox" name="actitud" value="cooperador" '.(($data['actitud'] == "cooperador")?"checked":"").' readonly>
      <label for="inputPassword4">Cooperador </label>
      <input type="checkbox" name="cooperador" value="no_cooperador" '. (($data['cooperador']=="no_cooperador")?"checked":"").' readonly>
      <label for="inputEmail4">No Cooperador</label>
      <input type="checkbox" name="no_cooperador" value="hostil" '. (($data['no_cooperador']=="hostil")?"checked":"").' readonly>
      <label for="inputPassword4">Hostil</label>
      <input type="checkbox" name="hostil" value="demandante" '. (($data['hostil']=="demandante")?"checked":"").' readonly>
      <label for="inputPassword4">Demandante</label>
      <input type="checkbox" name="demandante" value="reservado" '. (($data['demandante']=="reservado")?"checked":"").' readonly>
      <label for="inputPassword4">Reservado</label>
      <input type="checkbox" name="reservado" value="suspicaz" '. (($data['reservado']=="suspicaz")?"checkbox":"").' readonly>
      <label for="inputPassword4">Suspicaz</label>
    </div>';
		/*
        print "<span class=bold>" . xlt('Estado Civil') . ": </span><span class=text>" . text(ucfirst($result{"civil_casado"})) .(($result['civil_soltero'] !='') ? (', '.ucfirst($result['civil_soltero'])): '' ).(($result['civil_divorciado'] !='') ? (', '.ucfirst($result['civil_divorciado'])): '' ).(($result['civil_viudo'] !='') ? (', '.ucfirst($result['civil_viudo'])): '' ). "</span><br>\n";
            print "<span class=bold>" . xlt('Cuáles son sus preocupaciones con el paciente?') . ": </span><span class=text>" . nl2br(text($result{"caules_comments"})) . "</span><br>\n";
            print "<span class=bold>" . xlt('Padece el paciente de alguna condicion medica?') . ": </span><span class=text>" . nl2br(text(ucfirst($result{"padece"}))) . "</span><br>\n";
            print "<span class=bold>" . xlt('Condiciones medicas') . ": </span><span class=text>" . nl2br(text($result{"padece_comments"})) . "</span><br>\n";
            print "<span class=bold>" . xlt('Se ha realizado el paciente alguna cirugia?') . ": </span><span class=text>" . nl2br(text(ucfirst($result{"se_ha"}))) . "</span><br>\n";
            print "<span class=bold>" . xlt('Cirugias que se ha realizado') . ": </span><span class=text>" . nl2br(text($result{"se_ha_comments"})) . "</span><br>\n";
            print "<span class=bold>" . xlt('Es alergico el paciente a algun medicamento?') . ": </span><span class=text>" . nl2br(text(ucfirst($result{"es"}))) . "</span><br>\n";
	    print "<span class=bold>" . xlt('Alergias del paciente') . ": </span><span class=text>" . nl2br(text($result{"es_comments"})) . "</span><br>\n";
    print "</td></tr></table>\n";
?><table class='table table-responsive'>
                        <thead>
                            <tr>
                              <th>Medicamento</th>
                              <th>Dosis</th>
                              <th>Frecuencia</th>
                            </tr>
                      </tr>
                      </thead>

                      <?php if (!empty($drugs_code)) { ?>

<tbody class="table table-responsive mainrow_drug">
 <?php foreach ($drugs_code as $key => $dc) { ?>
                                <tr class="rowdrug_1 first_primary">
                                  <td><?php echo $dc['medicamento'] ?>
                                    <input class="drug_code" name="prescription[<?php echo $key; ?>][drug_code]" type="hidden" value="<?php echo $dc['drug_code'] ?>">
                                  <input class="id" type="hidden" name="prescription[<?php echo $key; ?>][id]" value="<?php echo $dc['id'] ?>">
                                    <input class="prescription_id" name="prescription_id" type="hidden" value="<?php echo $dc['id'] ?>"></td>
                                  <td><?php echo $dc['dosis']; ?></td>
                                  <td><?php echo $dc['frecuencia']; ?></td>
				</tr>
 <?php } ?>
</tbody>
 <?php } ?>
</table>
<?php
    print "<table><tr><td>\n";
            print "<span class=bold>" . xlt('Hospitalizaciones Psiquitricas') . ": </span><span class=text>" . nl2br(text(ucfirst($result{"hosp"}))) . "</span><br><span class=text>" . nl2br(text($result{"hosp_comments"})) . "</span>\n";
            print "<span class=bold>" . xlt('Intentos Suicidas') . ": </span><span class=text>" . nl2br(text(ucfirst($result{"intentos"}))) . "</span><br><span class=text>" . nl2br(text($result{"intentos_comments"})) . "</span>\n";
            print "<span class=bold>" . xlt('Abuso de Sustancias o Alcohol') . ": </span><span class=text>" . nl2br(text(ucfirst($result{"abuso"}))) . "</span><br><span class=text>" . nl2br(text($result{"abuso_comments"})) . "</span>\n";
            print "<span class=bold>" . xlt('Familiares con condiciones psiquiatricas') . ": </span><span class=text>" . nl2br(text(ucfirst($result{"familiares"}))) . "</span><br><span class=text>" . nl2br(text($result{"familiares_comments"})) . "</span>\n";
    }*/
	/*  print "</td></tr></table>\n";*/
}
?>
