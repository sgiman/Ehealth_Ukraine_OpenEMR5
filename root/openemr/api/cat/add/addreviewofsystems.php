<?php
/**
 * api/addreviewofsystems.php add patient's review of systems.
 *
 * Api add's patient review of systems.
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */
header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;
require_once 'classes.php';

$xml_string = "";
$xml_string = "<reviewofsystems>";

$token = $_GET['token'];
$visit_id = $_GET['visit_id'];

$patientId = $_GET['pid'];
$activity = isset($_GET['activity']) ? $_GET['activity'] : 1;
$weight_change = $_GET['weight_change'];
$weakness = $_GET['weakness'];
$fatigue = $_GET['fatigue'];
$anorexia = $_GET['anorexia'];
$fever = $_GET['fever'];
$chills = $_GET['chills'];
$night_sweats = $_GET['night_sweats'];
$insomnia = $_GET['insomnia'];
$irritability = $_GET['irritability'];
$heat_or_cold = $_GET['heat_or_cold'];
$intolerance = $_GET['intolerance'];
$change_in_vision = $_GET['change_in_vision'];
$glaucoma_history = $_GET['glaucoma_history'];
$eye_pain = $_GET['eye_pain'];
$irritation = $_GET['irritation'];
$redness = $_GET['redness'];
$excessive_tearing = $_GET['excessive_tearing'];
$double_vision = $_GET['double_vision'];
$blind_spots = $_GET['blind_spots'];
$photophobia = $_GET['photophobia'];
$hearing_loss = $_GET['hearing_loss'];
$discharge = $_GET['discharge'];
$pain = $_GET['pain'];
$vertigo = $_GET['vertigo'];
$tinnitus = $_GET['tinnitus'];
$frequent_colds = $_GET['frequent_colds'];
$sore_throat = $_GET['sore_throat'];
$sinus_problems = $_GET['sinus_problems'];
$post_nasal_drip = $_GET['post_nasal_drip'];
$nosebleed = $_GET['nosebleed'];
$snoring = $_GET['snoring'];
$apnea = $_GET['apnea'];
$breast_mass = $_GET['breast_mass'];
$breast_discharge = $_GET['breast_discharge'];
$biopsy = $_GET['biopsy'];
$abnormal_mammogram = $_GET['abnormal_mammogram'];
$cough = $_GET['cough'];
$sputum = $_GET['sputum'];
$shortness_of_breath = $_GET['shortness_of_breath'];
$wheezing = $_GET['wheezing'];
$hemoptsyis = $_GET['hemoptsyis'];
$asthma = $_GET['asthma'];
$copd = $_GET['copd'];
$chest_pain = $_GET['chest_pain'];
$palpitation = $_GET['palpitation'];
$syncope = $_GET['syncope'];
$pnd = $_GET['pnd'];
$doe = $_GET['doe'];
$orthopnea = $_GET['orthopnea'];
$peripheal = $_GET['peripheal'];
$edema = $_GET['edema'];
$legpain_cramping = $_GET['legpain_cramping'];
$history_murmur = $_GET['history_murmur'];
$arrythmia = $_GET['arrythmia'];
$heart_problem = $_GET['heart_problem'];
$dysphagia = $_GET['dysphagia'];
$heartburn = $_GET['heartburn'];
$bloating = $_GET['bloating'];
$belching = $_GET['belching'];
$flatulence = $_GET['flatulence'];
$nausea = $_GET['nausea'];
$vomiting = $_GET['vomiting'];
$hematemesis = $_GET['hematemesis'];
$gastro_pain = $_GET['gastro_pain'];
$food_intolerance = $_GET['food_intolerance'];
$hepatitis = $_GET['hepatitis'];
$jaundice = $_GET['jaundice'];
$hematochezia = $_GET['hematochezia'];
$changed_bowel = $_GET['changed_bowel'];
$diarrhea = $_GET['diarrhea'];
$constipation = $_GET['constipation'];
$polyuria = $_GET['polyuria'];
$polydypsia = $_GET['polydypsia'];
$dysuria = $_GET['dysuria'];
$hematuria = $_GET['hematuria'];
$frequency = $_GET['frequency'];
$urgency = $_GET['urgency'];
$incontinence = $_GET['incontinence'];
$renal_stones = $_GET['renal_stones'];
$utis = $_GET['utis'];
$hesitancy = $_GET['hesitancy'];
$dribbling = $_GET['dribbling'];
$stream = $_GET['stream'];
$nocturia = $_GET['nocturia'];
$erections = $_GET['erections'];
$ejaculations = $_GET['ejaculations'];
$g = $_GET['g'];
$p = $_GET['p'];
$ap = $_GET['ap'];
$lc = $_GET['lc'];
$mearche = $_GET['mearche'];
$menopause = $_GET['menopause'];
$lmp = $_GET['lmp'];
$f_frequency = $_GET['f_frequency'];
$f_flow = $_GET['f_flow'];
$f_symptoms = $_GET['f_symptoms'];
$abnormal_hair_growth = $_GET['abnormal_hair_growth'];
$f_hirsutism = $_GET['f_hirsutism'];
$joint_pain = $_GET['joint_pain'];
$swelling = $_GET['swelling'];
$m_redness = $_GET['m_redness'];
$m_warm = $_GET['m_warm'];
$m_stiffness = $_GET['m_stiffness'];
$muscle = $_GET['muscle'];
$m_aches = $_GET['m_aches'];
$fms = $_GET['fms'];
$arthritis = $_GET['arthritis'];
$loc = $_GET['loc'];
$seizures = $_GET['seizures'];
$stroke = $_GET['stroke'];
$tia = $_GET['tia'];
$n_numbness = $_GET['n_numbness'];
$n_weakness = $_GET['n_weakness'];
$paralysis = $_GET['paralysis'];
$intellectual_decline = $_GET['intellectual_decline'];
$memory_problems = $_GET['memory_problems'];
$dementia = $_GET['dementia'];
$n_headache = $_GET['n_headache'];
$s_cancer = $_GET['s_cancer'];
$psoriasis = $_GET['psoriasis'];
$s_acne = $_GET['s_acne'];
$s_other = $_GET['s_other'];
$s_disease = $_GET['s_disease'];
$p_diagnosis = $_GET['p_diagnosis'];
$p_medication = $_GET['p_medication'];
$depression = $_GET['depression'];
$anxiety = $_GET['anxiety'];
$social_difficulties = $_GET['social_difficulties'];
$thyroid_problems = $_GET['thyroid_problems'];
$diabetes = $_GET['diabetes'];
$abnormal_blood = $_GET['abnormal_blood'];
$anemia = $_GET['anemia'];
$fh_blood_problems = $_GET['fh_blood_problems'];
$bleeding_problems = $_GET['bleeding_problems'];
$allergies = $_GET['allergies'];
$frequent_illness = $_GET['frequent_illness'];
$hiv = $_GET['hiv'];
$hai_status = $_GET['hai_status'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('encounters', 'auth_a', $user);
    
    if ($acl_allow) {
        $strQuery = "INSERT INTO form_ros (pid, activity, date, weight_change, weakness, fatigue, anorexia, fever, chills, night_sweats, insomnia, irritability, heat_or_cold, intolerance, change_in_vision, glaucoma_history, eye_pain, irritation, redness, excessive_tearing, double_vision, blind_spots, photophobia, hearing_loss, discharge, pain, vertigo, tinnitus, frequent_colds, sore_throat, sinus_problems, post_nasal_drip, nosebleed, snoring, apnea, breast_mass, breast_discharge, biopsy, abnormal_mammogram, cough, sputum, shortness_of_breath, wheezing, hemoptsyis, asthma, copd, chest_pain, palpitation, syncope, pnd, doe, orthopnea, peripheal, edema, legpain_cramping, history_murmur, arrythmia, heart_problem, dysphagia, heartburn, bloating, belching, flatulence, nausea, vomiting, hematemesis, gastro_pain, food_intolerance, hepatitis, jaundice, hematochezia, changed_bowel, diarrhea, constipation, polyuria, polydypsia, dysuria, hematuria, frequency, urgency, incontinence, renal_stones, utis, hesitancy, dribbling, stream, nocturia, erections, ejaculations, g, p, ap, lc, mearche, menopause, lmp, f_frequency, f_flow, f_symptoms, abnormal_hair_growth, f_hirsutism, joint_pain, swelling, m_redness, m_warm, m_stiffness, muscle, m_aches, fms, arthritis, loc, seizures, stroke, tia, n_numbness, n_weakness, paralysis, intellectual_decline, memory_problems, dementia, n_headache, s_cancer, psoriasis, s_acne, s_other, s_disease, p_diagnosis, p_medication, depression, anxiety, social_difficulties, thyroid_problems, diabetes, abnormal_blood, anemia, fh_blood_problems, bleeding_problems, allergies, frequent_illness, hiv, hai_status) 
                                    VALUES ('" . add_escape_custom($patientId) . "', '" . add_escape_custom($activity) . "', '" . date('Y-m-d H:i:s') . "', '" . add_escape_custom($weight_change) . "', '" . add_escape_custom($weakness) . "', '" . add_escape_custom($fatigue) . "', '" . add_escape_custom($anorexia) . "', '" . add_escape_custom($fever) . "', '" . add_escape_custom($chills) . "', '" . add_escape_custom($night_sweats) . "', '" . add_escape_custom($insomnia) . "', '" . add_escape_custom($irritability) . "', '" . add_escape_custom($heat_or_cold) . "', '" . add_escape_custom($intolerance) . "', '" . add_escape_custom($change_in_vision) . "', '" . add_escape_custom($glaucoma_history) . "', '" . add_escape_custom($eye_pain) . "', '" . add_escape_custom($irritation) . "', '" . add_escape_custom($redness) . "', '" . add_escape_custom($excessive_tearing) . "', '" . add_escape_custom($double_vision) . "', '" . add_escape_custom($blind_spots) . "', '" . add_escape_custom($photophobia) . "', '" . add_escape_custom($hearing_loss) . "', '" . add_escape_custom($discharge) . "', '" . add_escape_custom($pain) . "', '" . add_escape_custom($vertigo) . "', '" . add_escape_custom($tinnitus) . "', '" . add_escape_custom($frequent_colds) . "', '" . add_escape_custom($sore_throat) . "', '" . add_escape_custom($sinus_problems) . "', '" . add_escape_custom($post_nasal_drip) . "', '" . add_escape_custom($nosebleed) . "', '" . add_escape_custom($snoring) . "', '" . add_escape_custom($apnea) . "', '" . add_escape_custom($breast_mass) . "', '" . add_escape_custom($breast_discharge) . "', '" . add_escape_custom($biopsy) . "', '" . add_escape_custom($abnormal_mammogram) . "', '" . add_escape_custom($cough) . "', '" . add_escape_custom($sputum) . "', '" . add_escape_custom($shortness_of_breath) . "', '" . add_escape_custom($wheezing) . "', '" . add_escape_custom($hemoptsyis) . "', '" . add_escape_custom($asthma) . "', '" . add_escape_custom($copd) . "', '" . add_escape_custom($chest_pain) . "', '" . add_escape_custom($palpitation) . "', '" . add_escape_custom($syncope) . "', '" . add_escape_custom($pnd) . "', '" . add_escape_custom($doe) . "', '" . add_escape_custom($orthopnea) . "', '" . add_escape_custom($peripheal) . "', '" . add_escape_custom($edema) . "', '" . add_escape_custom($legpain_cramping) . "', '" . add_escape_custom($history_murmur) . "', '" . add_escape_custom($arrythmia) . "', '" . add_escape_custom($heart_problem) . "', '" . add_escape_custom($dysphagia) . "', '" . add_escape_custom($heartburn) . "', '" . add_escape_custom($bloating) . "', '" . add_escape_custom($belching) . "', '" . add_escape_custom($flatulence) . "', '" . add_escape_custom($nausea) . "', '" . add_escape_custom($vomiting) . "', '" . add_escape_custom($hematemesis) . "', '" . add_escape_custom($gastro_pain) . "', '" . add_escape_custom($food_intolerance) . "', '" . add_escape_custom($hepatitis) . "', '" . add_escape_custom($jaundice) . "', '" . add_escape_custom($hematochezia) . "', '" . add_escape_custom($changed_bowel) . "', '" . add_escape_custom($diarrhea) . "', '" . add_escape_custom($constipation) . "', '" . add_escape_custom($polyuria) . "', '" . add_escape_custom($polydypsia) . "', '" . add_escape_custom($dysuria) . "', '" . add_escape_custom($hematuria) . "', '" . add_escape_custom($frequency) . "', '" . add_escape_custom($urgency) . "', '" . add_escape_custom($incontinence) . "', '" . add_escape_custom($renal_stones) . "', '" . add_escape_custom($utis) . "', '" . add_escape_custom($hesitancy) . "', '" . add_escape_custom($dribbling) . "', '" . add_escape_custom($stream) . "', '" . add_escape_custom($nocturia) . "', '" . add_escape_custom($erections) . "', '" . add_escape_custom($ejaculations) . "', '" . add_escape_custom($g) . "', '" . add_escape_custom($p) . "', '" . add_escape_custom($ap) . "', '" . add_escape_custom($lc) . "', '" . add_escape_custom($mearche) . "', '" . add_escape_custom($menopause) . "', '" . add_escape_custom($lmp) . "', '" . add_escape_custom($f_frequency) . "', '" . add_escape_custom($f_flow) . "', '" . add_escape_custom($f_symptoms) . "', '" . add_escape_custom($abnormal_hair_growth) . "', '" . add_escape_custom($f_hirsutism) . "', '" . add_escape_custom($joint_pain) . "', '" . add_escape_custom($swelling) . "', '" . add_escape_custom($m_redness) . "', '" . add_escape_custom($m_warm) . "', '" . add_escape_custom($m_stiffness) . "', '" . add_escape_custom($muscle) . "', '" . add_escape_custom($m_aches) . "', '" . add_escape_custom($fms) . "', '" . add_escape_custom($arthritis) . "', '" . add_escape_custom($loc) . "', '" . add_escape_custom($seizures) . "', '" . add_escape_custom($stroke) . "', '" . add_escape_custom($tia) . "', '" . add_escape_custom($n_numbness) . "', '" . add_escape_custom($n_weakness) . "', '" . add_escape_custom($paralysis) . "', '" . add_escape_custom($intellectual_decline) . "', '" . add_escape_custom($memory_problems) . "', '" . add_escape_custom($dementia) . "', '" . add_escape_custom($n_headache) . "', '" . add_escape_custom($s_cancer) . "', '" . add_escape_custom($psoriasis) . "', '" . add_escape_custom($s_acne) . "', '" . add_escape_custom($s_other) . "', '" . add_escape_custom($s_disease) . "', '" . add_escape_custom($p_diagnosis) . "', '" . add_escape_custom($p_medication) . "', '" . add_escape_custom($depression) . "', '" . add_escape_custom($anxiety) . "', '" . add_escape_custom($social_difficulties) . "', '" . add_escape_custom($thyroid_problems) . "', '" . add_escape_custom($diabetes) . "', '" . add_escape_custom($abnormal_blood) . "', '" . add_escape_custom($anemia) . "', '" . add_escape_custom($fh_blood_problems) . "', '" . add_escape_custom($bleeding_problems) . "', '" . add_escape_custom($allergies) . "', '" . add_escape_custom($frequent_illness) . "', '" . add_escape_custom($hiv) . "', '" . add_escape_custom($hai_status) . "')";

        $result = sqlInsert($strQuery);

        $last_inserted_id = $result;

        if ($result) {
            addForm($visit_id, $form_name = 'Review Of Systems', $last_inserted_id, $formdir = 'ros ', $patientId, $authorized = "1", $date = "NOW()", $user, $group = "Default");

            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>Review of Systems added successfully</reason>";
            $xml_string .= "<rosid>" . $last_inserted_id . "</rosid>";
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>Could not add Review of Systems</reason>";
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</reviewofsystems>";
echo $xml_string;
?>