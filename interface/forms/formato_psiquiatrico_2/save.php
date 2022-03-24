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

if (!CsrfUtils::verifyCsrfToken($_POST["csrf_token_form"])) {
    CsrfUtils::csrfNotVerified();
}

if (!$encounter) { // comes from globals.php
    die(xlt("Internal error: we do not seem to be in an encounter!"));
}

$pat_id = $pid;
$enc_id = $encounter;
$pro_id = $_SESSION['authUserID'];
$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';
$form_id = (isset($_POST['form_id'])) ? $_POST['form_id'] : '';
$fetcha = (isset($_POST['fetcha'])) ? $_POST['fetcha'] : '';
$nacimiento = (isset($_POST['nacimiento'])) ? $_POST['nacimiento'] : '';
$edad = (isset($_POST['edad'])) ? $_POST['edad'] : '';
$nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : '';
$genero = (isset($_POST['genero'])) ? $_POST['genero'] : '';
$evaluaction = (isset($_POST['evaluaction'])) ? $_POST['evaluaction'] : '';
$encuentra = (isset($_POST['encuentra'])) ? implode(", ", $_POST['encuentra']) : '';
$proposito = (isset($_POST['proposito'])) ? implode(", ", $_POST['proposito']) : '';
$hipaa_ley = (isset($_POST['hipaa_ley'])) ? $_POST['hipaa_ley'] : '';
$fuentes = (isset($_POST['fuentes'])) ? implode(", ", $_POST['fuentes']) : '';
$si_aplica = (isset($_POST['si_aplica'])) ? implode(", ", $_POST['si_aplica']) : '';
$estresores = (isset($_POST['estresores'])) ? implode(", ", $_POST['estresores']) : '';
$estao_civil = (isset($_POST['estao_civil'])) ? $_POST['estao_civil'] : '';
$ocupacion = (isset($_POST['ocupacion'])) ? $_POST['ocupacion'] : '';
$lugar_donde = (isset($_POST['lugar_donde'])) ? $_POST['lugar_donde'] : '';
$historial_social = (isset($_POST['historial_social'])) ? $_POST['historial_social'] : '';
$historial_presente = (isset($_POST['historial_presente'])) ? $_POST['historial_presente'] : '';
$medica = (isset($_POST['medica'])) ? implode(", ", $_POST['medica']) : '';
$hist_alcohol_canti = (isset($_POST['hist_alcohol_canti'])) ? $_POST['hist_alcohol_canti'] : '';
$hist_alcohol_ultimo = (isset($_POST['hist_alcohol_ultimo'])) ? $_POST['hist_alcohol_ultimo'] : '';
$hist_alcohol_disfuncion = (isset($_POST['hist_alcohol_disfuncion'])) ? $_POST['hist_alcohol_disfuncion'] : '';
$hist_alcohol_comantarios = (isset($_POST['hist_alcohol_comantarios'])) ? $_POST['hist_alcohol_comantarios'] : '';
$hist_cannabis_canti = (isset($_POST['hist_cannabis_canti'])) ? $_POST['hist_cannabis_canti'] : '';
$hist_cannabis_ultimo = (isset($_POST['hist_cannabis_ultimo'])) ? $_POST['hist_cannabis_ultimo'] : '';
$hist_cannabis_disfuncion = (isset($_POST['hist_cannabis_disfuncion'])) ? $_POST['hist_cannabis_disfuncion'] : '';
$hist_cannabis_comentarios = (isset($_POST['hist_cannabis_comentarios'])) ? $_POST['hist_cannabis_comentarios'] : '';
$hist_cocaina_canti = (isset($_POST['hist_cocaina_canti'])) ? $_POST['hist_cocaina_canti'] : '';
$hist_cocaina_ultimo = (isset($_POST['hist_cocaina_ultimo'])) ? $_POST['hist_cocaina_ultimo'] : '';
$hist_cocaina_disfuncion = (isset($_POST['hist_cocaina_disfuncion'])) ? $_POST['hist_cocaina_disfuncion'] : '';
$hist_cocaina_comentarios = (isset($_POST['hist_cocaina_comentarios'])) ? $_POST['hist_cocaina_comentarios'] : '';
$hist_opioides_canti = (isset($_POST['hist_opioides_canti'])) ? $_POST['hist_opioides_canti'] : '';
$hist_opioides_ultimo = (isset($_POST['hist_opioides_ultimo'])) ? $_POST['hist_opioides_ultimo'] : '';
$hist_opioides_disfuncion = (isset($_POST['hist_opioides_disfuncion'])) ? $_POST['hist_opioides_disfuncion'] : '';
$hist_opioides_comentarios = (isset($_POST['hist_opioides_comentarios'])) ? $_POST['hist_opioides_comentarios'] : '';
$hist_nicotina_canti = (isset($_POST['hist_nicotina_canti'])) ? $_POST['hist_nicotina_canti'] : '';
$hist_nicotina_ultimo = (isset($_POST['hist_nicotina_ultimo'])) ? $_POST['hist_nicotina_ultimo'] : '';
$hist_nicotina_disfuncion = (isset($_POST['hist_nicotina_disfuncion'])) ? $_POST['hist_nicotina_disfuncion'] : '';
$hist_nicotina_comentarios = (isset($_POST['hist_nicotina_comentarios'])) ? $_POST['hist_nicotina_comentarios'] : '';
$hist_otros_canti = (isset($_POST['hist_otros_canti'])) ? $_POST['hist_otros_canti'] : '';
$hist_otros_ultimo = (isset($_POST['hist_otros_ultimo'])) ? $_POST['hist_otros_ultimo'] : '';
$hist_otros_disfuncion = (isset($_POST['hist_otros_disfuncion'])) ? $_POST['hist_otros_disfuncion'] : '';
$hist_otros_comentarios = (isset($_POST['hist_otros_comentarios'])) ? $_POST['hist_otros_comentarios'] : '';
$historial_previo = (isset($_POST['historial_previo'])) ? $_POST['historial_previo'] : '';
$historial_medico = (isset($_POST['historial_medico'])) ? $_POST['historial_medico'] : '';
$enferemedades = (isset($_POST['enferemedades'])) ? implode(", ", $_POST['enferemedades']) : '';
$enfermedad_free_text = (isset($_POST['enfermedad_free_text'])) ? $_POST['enfermedad_free_text'] : '';
$medicamentos = (isset($_POST['medicamentos'])) ? implode(", ", $_POST['medicamentos']) : '';
$hist_familiar = (isset($_POST['hist_familiar'])) ? $_POST['hist_familiar'] : '';
$historial_educacion = (isset($_POST['historial_educacion'])) ? $_POST['historial_educacion'] : '';
$historial_legal = (isset($_POST['historial_legal'])) ? $_POST['historial_legal'] : '';
$evaluacion_riesgo = (isset($_POST['evaluacion_riesgo'])) ? $_POST['evaluacion_riesgo'] : '';
$acceso_armas = (isset($_POST['acceso_armas'])) ? $_POST['acceso_armas'] : '';
$risk_evaluation = (isset($_POST['risk_evaluation'])) ? $_POST['risk_evaluation'] : '';
$apariencia = (isset($_POST['apariencia'])) ? implode(", ", $_POST['apariencia']) : '';
$alucinaciones = (isset($_POST['alucinaciones'])) ? implode(", ", $_POST['alucinaciones']) : '';
$actitud_evaluador = (isset($_POST['actitud_evaluador'])) ? $_POST['actitud_evaluador'] : '';
$sicomotor = (isset($_POST['sicomotor'])) ? implode(", ", $_POST['sicomotor']) : '';
$expresion_verbal = (isset($_POST['expresion_verbal'])) ? $_POST['expresion_verbal'] : '';
$orientacion = (isset($_POST['orientacion'])) ? implode(", ", $_POST['orientacion']) : '';
$orientacion_text = (isset($_POST['orientacion_text'])) ? $_POST['orientacion_text'] : '';
$memoria_inmediata = (isset($_POST['memoria_inmediata'])) ? $_POST['memoria_inmediata'] : '';
$memoria_reciente = (isset($_POST['memoria_reciente'])) ? $_POST['memoria_reciente'] : '';
$atencion_y_concentracion = (isset($_POST['atencion_y_concentracion'])) ? $_POST['atencion_y_concentracion'] : '';
$juicio = (isset($_POST['juicio'])) ? $_POST['juicio'] : '';
$afecto = (isset($_POST['afecto'])) ? $_POST['afecto'] : '';
$logico = (isset($_POST['logico'])) ? $_POST['logico'] : '';
$irrelevante = (isset($_POST['irrelevante'])) ? $_POST['irrelevante'] : '';
$coherente = (isset($_POST['coherente'])) ? $_POST['coherente'] : '';
$proceso_de_pensamiento = (isset($_POST['proceso_de_pensamiento'])) ? implode(", ", $_POST['proceso_de_pensamiento']) : '';
$delirios = (isset($_POST['delirios'])) ? implode(", ", $_POST['delirios']) : '';
$ideas_suicidas = (isset($_POST['ideas_suicidas'])) ? $_POST['ideas_suicidas'] : '';
$ideas_homicidas = (isset($_POST['ideas_homicidas'])) ? $_POST['ideas_homicidas'] : '';
$ideas_free_text = (isset($_POST['ideas_free_text'])) ? $_POST['ideas_free_text'] : '';
$apreciacion_education = (isset($_POST['apreciacion_education'])) ? $_POST['apreciacion_education'] : '';
$signs_symptoms = (isset($_POST['signs_symptoms'])) ? $_POST['signs_symptoms'] : '';
$diagnoses = (isset($_POST['diagnoses'])) ? $_POST['diagnoses'] : '';
$rational_recommended = (isset($_POST['rational_recommended'])) ? $_POST['rational_recommended'] : '';
$education_orientation = (isset($_POST['education_orientation'])) ? $_POST['education_orientation'] : '';
$pharmacotherapy = (isset($_POST['pharmacotherapy'])) ? $_POST['pharmacotherapy'] : '';
$psychitherapy = (isset($_POST['psychitherapy'])) ? $_POST['psychitherapy'] : '';
$laboratories = (isset($_POST['laboratories'])) ? $_POST['laboratories'] : '';
$referrals = (isset($_POST['referrals'])) ? $_POST['referrals'] : '';
$followup = (isset($_POST['followup'])) ? $_POST['followup'] : '';
$prescription = $_POST['prescription'];
$prescription_id = $_POST['prescription_id'];
$prescription_delete = $_POST['prescription_delete'];

 function prescription($form_id,$user,$pid,$encounter){
        $d_id = sqlQuery("delete from formato2_medicamento where form_id=?",$form_id);
        global $prescription;
        foreach($prescription as $k => $value){
          if($value['medicamento'] != ''){
                         $arg = array($user,$pid, $encounter,$form_id,$value['medicamento'],$value['dosis'],$value['size'],$value['unit'],$value['amount'],$value['form'],$value['route'],$value['interval'],$value['refills'],$value['freq_other']);
                         $p_id = sqlInsert("insert into formato2_medicamento (user,pid,encounter,form_id,medicamento,dosis,size,unit,amount,form,route,`interval`,refills,freq_other) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $arg);
          }
        }

}


if($mode == 'new') {
    $args = array($pid, $pro_id, $enc_id, $fetcha, $nacimiento, $edad, $nombre, $genero, $evaluaction, $encuentra, $proposito, $hipaa_ley, $fuentes, $si_aplica, $estresores, $estao_civil, $ocupacion, $lugar_donde, $historial_social, $historial_presente, $medica, $hist_alcohol_canti, $hist_alcohol_ultimo, $hist_cannabis_disfuncion, $hist_cannabis_comentarios, $hist_cannabis_canti, $hist_cannabis_ultimo, $hist_cannabis_disfuncion, $hist_cannabis_comentarios, $hist_cocaina_canti, $hist_cocaina_ultimo, $hist_cocaina_disfuncion, $hist_cocaina_comentarios, $hist_opioides_canti, $hist_opioides_ultimo, $hist_opioides_disfuncion, $hist_opioides_comentarios, $hist_nicotina_canti, $hist_nicotina_ultimo, $hist_nicotina_disfuncion, $hist_nicotina_comentarios, $hist_otros_canti, $hist_otros_ultimo, $hist_otros_disfuncion, $hist_otros_comentarios, $historial_previo, $historial_medico, $enferemedades, $enfermedad_free_text, $medicamentos, $hist_familiar, $historial_educacion, $historial_legal, $evaluacion_riesgo, $acceso_armas, $risk_evaluation, $apariencia, $alucinaciones, $actitud_evaluador, $sicomotor, $expresion_verbal, $orientacion, $orientacion_text,$memoria_inmediata ,$memoria_reciente, $atencion_y_concentracion, $juicio, $afecto, $logico, $irrelevante, $coherente, $proceso_de_pensamiento, $delirios, $ideas_suicidas, $ideas_homicidas, $ideas_free_text, $apreciacion_education, $signs_symptoms, $diagnoses, $rational_recommended, $education_orientation, $pharmacotherapy, $psychitherapy, $laboratories, $referrals, $followup);
    $gf_id = sqlInsert("INSERT INTO formato_psiquiatrico_2_form (pid, uid, enc_id, fetcha, nacimiento, edad, nombre, genero, evaluaction, encuentra, proposito, hipaa_ley, fuentes, si_aplica, estresores, estao_civil, ocupacion, lugar_donde, historial_social, historial_presente, medica, hist_alcohol_canti, hist_alcohol_ultimo, hist_alcohol_disfuncion, hist_alcohol_comantarios, hist_cannabis_canti, hist_cannabis_ultimo, hist_cannabis_disfuncion, hist_cannabis_comentarios, hist_cocaina_canti, hist_cocaina_ultimo, hist_cocaina_disfuncion, hist_cocaina_comentarios, hist_opioides_canti, hist_opioides_ultimo, hist_opioides_disfuncion, hist_opioides_comentarios, hist_nicotina_canti, hist_nicotina_ultimo, hist_nicotina_disfuncion, hist_nicotina_comentarios, hist_otros_canti, hist_otros_ultimo, hist_otros_disfuncion, hist_otros_comentarios, historial_previo, historial_medico, enferemedades, enfermedad_free_text, medicamentos, hist_familiar, historial_educacion, historial_legal, evaluacion_riesgo, acceso_armas, risk_evaluation, apariencia, alucinaciones, actitud_evaluador, sicomotor, expresion_verbal, orientacion, orientacion_text, memoria_inmediata, memoria_reciente, atencion_y_concentracion, juicio, afecto, logico, irrelevante, coherente, proceso_de_pensamiento, delirios, ideas_suicidas, ideas_homicidas, ideas_free_text, apreciacion_education, signs_symptoms, diagnoses, rational_recommended, education_orientation, pharmacotherapy, psychitherapy, laboratories, referrals, followup) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $args);
    addForm($encounter, "Formato Psiquiatrico 2",$gf_id,"formato_psiquiatrico_2", $pid, $userauthorized);
    prescription($gf_id,$user,$pid,$encounter);
}
else {
    $args = array($pid, $pro_id, $enc_id, $fetcha, $nacimiento, $edad, $nombre, $genero, $evaluaction, $encuentra, $proposito, $hipaa_ley, $fuentes, $si_aplica, $estresores, $estao_civil, $ocupacion, $lugar_donde, $historial_social, $historial_presente, $medica, $hist_alcohol_canti, $hist_alcohol_ultimo, $hist_cannabis_disfuncion, $hist_cannabis_comentarios, $hist_cannabis_canti, $hist_cannabis_ultimo, $hist_cannabis_disfuncion, $hist_cannabis_comentarios, $hist_cocaina_canti, $hist_cocaina_ultimo, $hist_cocaina_disfuncion, $hist_cocaina_comentarios, $hist_opioides_canti, $hist_opioides_ultimo, $hist_opioides_disfuncion, $hist_opioides_comentarios, $hist_nicotina_canti, $hist_nicotina_ultimo, $hist_nicotina_disfuncion, $hist_nicotina_comentarios, $hist_otros_canti, $hist_otros_ultimo, $hist_otros_disfuncion, $hist_otros_comentarios, $historial_previo, $historial_medico, $enferemedades, $enfermedad_free_text, $medicamentos, $hist_familiar, $historial_educacion, $historial_legal, $evaluacion_riesgo, $acceso_armas, $risk_evaluation, $apariencia, $alucinaciones, $actitud_evaluador, $sicomotor, $expresion_verbal, $orientacion, $orientacion_text,$memoria_inmediata ,$memoria_reciente, $atencion_y_concentracion, $juicio, $afecto, $logico, $irrelevante, $coherente, $proceso_de_pensamiento, $delirios, $ideas_suicidas, $ideas_homicidas, $ideas_free_text, $apreciacion_education, $signs_symptoms, $diagnoses, $rational_recommended, $education_orientation, $pharmacotherapy, $psychitherapy, $laboratories, $referrals, $followup, $form_id);
    $gf_id = sqlInsert("UPDATE formato_psiquiatrico_2_form SET pid = ?, uid = ?, enc_id = ?, fetcha = ?, nacimiento = ?, edad = ?, nombre = ?, genero = ?, evaluaction = ?, encuentra = ?, proposito = ?, hipaa_ley = ?, fuentes = ?, si_aplica = ?, estresores = ?, estao_civil = ?, ocupacion = ?, lugar_donde = ?, historial_social = ?, historial_presente = ?, medica = ?, hist_alcohol_canti = ?, hist_alcohol_ultimo = ?, hist_alcohol_disfuncion = ?, hist_alcohol_comantarios = ?, hist_cannabis_canti = ?, hist_cannabis_ultimo = ?, hist_cannabis_disfuncion = ?, hist_cannabis_comentarios = ?, hist_cocaina_canti = ?, hist_cocaina_ultimo = ?, hist_cocaina_disfuncion = ?, hist_cocaina_comentarios = ?, hist_opioides_canti = ?, hist_opioides_ultimo = ?, hist_opioides_disfuncion = ?, hist_opioides_comentarios = ?, hist_nicotina_canti = ?, hist_nicotina_ultimo = ?, hist_nicotina_disfuncion = ?, hist_nicotina_comentarios = ?, hist_otros_canti = ?, hist_otros_ultimo = ?, hist_otros_disfuncion = ?, hist_otros_comentarios = ?, historial_previo = ?, historial_medico = ?, enferemedades = ?, enfermedad_free_text = ?, medicamentos = ?, hist_familiar = ?, historial_educacion = ?, historial_legal = ?, evaluacion_riesgo = ?, acceso_armas = ?, risk_evaluation = ?, apariencia = ?, alucinaciones = ?, actitud_evaluador = ?, sicomotor = ?, expresion_verbal = ?, orientacion = ?, orientacion_text = ?, memoria_inmediata = ?, memoria_reciente = ?, atencion_y_concentracion = ?, juicio = ?, afecto = ?, logico = ?, irrelevante = ?, coherente = ?, proceso_de_pensamiento = ?, delirios = ?, ideas_suicidas = ?, ideas_homicidas = ?, ideas_free_text = ?, apreciacion_education = ?, signs_symptoms = ?, diagnoses = ?, rational_recommended = ?, education_orientation = ?, pharmacotherapy = ?, psychitherapy = ?, laboratories = ?, referrals = ?, followup = ? WHERE id = ?", $args);
    prescription($form_id,$user,$pid,$encounter);
}
formJump();


