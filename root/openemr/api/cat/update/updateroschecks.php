<?php
/**
 * api/updateroschecks.php Update ROSChecks.
 *
 * API is allowed to update patient review of systems checks details.
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
$xml_string = "<roschecks>";

$token = $_GET['token'];

$id = $_GET['id'];

$groupname = isset($_GET['groupname']) ? $_GET['groupname'] : 'Default';
$authorized = isset($_GET['authorized']) ? $_GET['authorized'] : 1;
$activity = isset($_GET['activity']) ? $_GET['activity'] : 1;

$fever = $_GET['fever'];
$chills = $_GET['chills'];
$night_sweats = $_GET['night_sweats'];
$weight_loss = $_GET['weight_loss'];
$poor_appetite = $_GET['poor_appetite'];
$insomnia = $_GET['insomnia'];
$fatigued = $_GET['fatigued'];
$depressed = $_GET['depressed'];
$hyperactive = $_GET['hyperactive'];
$exposure_to_foreign_countries = $_GET['exposure_to_foreign_countries'];
$cataracts = $_GET['cataracts'];
$cataract_surgery = $_GET['cataract_surgery'];
$glaucoma = $_GET['glaucoma'];
$double_vision = $_GET['double_vision'];
$blurred_vision = $_GET['blurred_vision'];
$poor_hearing = $_GET['poor_hearing'];
$headaches = $_GET['headaches'];
$ringing_in_ears = $_GET['ringing_in_ears'];
$bloody_nose = $_GET['bloody_nose'];
$sinusitis = $_GET['sinusitis'];
$sinus_surgery = $_GET['sinus_surgery'];
$dry_mouth = $_GET['dry_mouth'];
$strep_throat = $_GET['strep_throat'];
$tonsillectomy = $_GET['tonsillectomy'];
$swollen_lymph_nodes = $_GET['swollen_lymph_nodes'];
$throat_cancer = $_GET['throat_cancer'];
$throat_cancer_surgery = $_GET['throat_cancer_surgery'];
$heart_attack = $_GET['heart_attack'];
$irregular_heart_beat = $_GET['irregular_heart_beat'];
$chest_pains = $_GET['chest_pains'];
$shortness_of_breath = $_GET['shortness_of_breath'];
$high_blood_pressure = $_GET['high_blood_pressure'];
$heart_failure = $_GET['heart_failure'];
$poor_circulation = $_GET['poor_circulation'];
$vascular_surgery = $_GET['vascular_surgery'];
$cardiac_catheterization = $_GET['cardiac_catheterization'];
$coronary_artery_bypass = $_GET['coronary_artery_bypass'];
$heart_transplant = $_GET['heart_transplant'];
$stress_test = $_GET['stress_test'];
$emphysema = $_GET['emphysema'];
$chronic_bronchitis = $_GET['chronic_bronchitis'];
$interstitial_lung_disease = $_GET['interstitial_lung_disease'];
$shortness_of_breath_2 = $_GET['shortness_of_breath_2'];
$lung_cancer = $_GET['lung_cancer'];
$lung_cancer_surgery = $_GET['lung_cancer_surgery'];
$pheumothorax = $_GET['pheumothorax'];
$stomach_pains = $_GET['stomach_pains'];
$peptic_ulcer_disease = $_GET['peptic_ulcer_disease'];
$gastritis = $_GET['gastritis'];
$endoscopy = $_GET['endoscopy'];
$polyps = $_GET['polyps'];
$colonoscopy = $_GET['colonoscopy'];
$colon_cancer = $_GET['colon_cancer'];
$colon_cancer_surgery = $_GET['colon_cancer_surgery'];
$ulcerative_colitis = $_GET['ulcerative_colitis'];
$crohns_disease = $_GET['crohns_disease'];
$appendectomy = $_GET['appendectomy'];
$divirticulitis = $_GET['divirticulitis'];
$divirticulitis_surgery = $_GET['divirticulitis_surgery'];
$gall_stones = $_GET['gall_stones'];
$cholecystectomy = $_GET['cholecystectomy'];
$hepatitis = $_GET['hepatitis'];
$cirrhosis_of_the_liver = $_GET['cirrhosis_of_the_liver'];
$splenectomy = $_GET['splenectomy'];
$kidney_failure = $_GET['kidney_failure'];
$kidney_stones = $_GET['kidney_stones'];
$kidney_cancer = $_GET['kidney_cancer'];
$kidney_infections = $_GET['kidney_infections'];
$bladder_infections = $_GET['bladder_infections'];
$bladder_cancer = $_GET['bladder_cancer'];
$prostate_problems = $_GET['prostate_problems'];
$prostate_cancer = $_GET['prostate_cancer'];
$kidney_transplant = $_GET['kidney_transplant'];
$sexually_transmitted_disease = $_GET['sexually_transmitted_disease'];
$burning_with_urination = $_GET['burning_with_urination'];
$discharge_from_urethra = $_GET['discharge_from_urethra'];
$rashes = $_GET['rashes'];
$infections = $_GET['infections'];
$ulcerations = $_GET['ulcerations'];
$pemphigus = $_GET['pemphigus'];
$herpes = $_GET['herpes'];
$osetoarthritis = $_GET['osetoarthritis'];
$rheumotoid_arthritis = $_GET['rheumotoid_arthritis'];
$lupus = $_GET['lupus'];
$ankylosing_sondlilitis = $_GET['ankylosing_sondlilitis'];
$swollen_joints = $_GET['swollen_joints'];
$stiff_joints = $_GET['stiff_joints'];
$broken_bones = $_GET['broken_bones'];
$neck_problems = $_GET['neck_problems'];
$back_problems = $_GET['back_problems'];
$back_surgery = $_GET['back_surgery'];
$scoliosis = $_GET['scoliosis'];
$herniated_disc = $_GET['herniated_disc'];
$shoulder_problems = $_GET['shoulder_problems'];
$elbow_problems = $_GET['elbow_problems'];
$wrist_problems = $_GET['wrist_problems'];
$hand_problems = $_GET['hand_problems'];
$hip_problems = $_GET['hip_problems'];
$knee_problems = $_GET['knee_problems'];
$ankle_problems = $_GET['ankle_problems'];
$foot_problems = $_GET['foot_problems'];
$insulin_dependent_diabetes = $_GET['insulin_dependent_diabetes'];
$noninsulin_dependent_diabetes = $_GET['noninsulin_dependent_diabetes'];
$hypothyroidism = $_GET['hypothyroidism'];
$hyperthyroidism = $_GET['hyperthyroidism'];
$cushing_syndrom = $_GET['cushing_syndrom'];
$addison_syndrom = $_GET['addison_syndrom'];
$additional_notes = $_GET['additional_notes'];


if ($userId = validateToken($token)) {

    $user = getUsername($userId);
    $acl_allow = acl_check('encounters', 'auth_a', $user);

    if ($acl_allow) {
        $strQuery = "UPDATE `form_reviewofs` SET 
                    `user`='".add_escape_custom($user)."',`groupname`='".add_escape_custom($groupname)."',
                    `authorized`='".add_escape_custom($authorized)."',`activity`='".add_escape_custom($activity)."',`fever`='".add_escape_custom($fever)."',`chills`='".add_escape_custom($chills)."',
                    `night_sweats`='".add_escape_custom($night_sweats)."',`weight_loss`='".add_escape_custom($weight_loss)."',`poor_appetite`='".add_escape_custom($poor_appetite)."',`insomnia`='".add_escape_custom($insomnia)."',
                    `fatigued`='".add_escape_custom($fatigued)."',`depressed`='".add_escape_custom($depressed)."',`hyperactive`='".add_escape_custom($hyperactive)."',
                    `exposure_to_foreign_countries`='".add_escape_custom($exposure_to_foreign_countries)."',`cataracts`='".add_escape_custom($cataracts)."',`cataract_surgery`='".add_escape_custom($cataract_surgery)."',
                    `glaucoma`='".add_escape_custom($glaucoma)."',`double_vision`='".add_escape_custom($double_vision)."',`blurred_vision`='".add_escape_custom($blurred_vision)."',`poor_hearing`='".add_escape_custom($poor_hearing)."',
                    `headaches`='".add_escape_custom($headaches)."',`ringing_in_ears`='".add_escape_custom($ringing_in_ears)."',`bloody_nose`='".add_escape_custom($bloody_nose)."',`sinusitis`='".add_escape_custom($sinusitis)."',
                    `sinus_surgery`='".add_escape_custom($sinus_surgery)."',`dry_mouth`='".add_escape_custom($dry_mouth)."',`strep_throat`='".add_escape_custom($strep_throat)."',`tonsillectomy`='".add_escape_custom($tonsillectomy)."',
                    `swollen_lymph_nodes`='".add_escape_custom($swollen_lymph_nodes)."',`throat_cancer`='".add_escape_custom($throat_cancer)."',`throat_cancer_surgery`='".add_escape_custom($throat_cancer_surgery)."',
                    `heart_attack`='".add_escape_custom($heart_attack)."',`irregular_heart_beat`='".add_escape_custom($irregular_heart_beat)."',`chest_pains`='".add_escape_custom($chest_pains)."',
                    `shortness_of_breath`='".add_escape_custom($shortness_of_breath)."',`high_blood_pressure`='".add_escape_custom($high_blood_pressure)."',`heart_failure`='".add_escape_custom($heart_failure)."',
                    `poor_circulation`='".add_escape_custom($poor_circulation)."',`vascular_surgery`='".add_escape_custom($vascular_surgery)."',`cardiac_catheterization`='".add_escape_custom($cardiac_catheterization)."',
                    `coronary_artery_bypass`='".add_escape_custom($coronary_artery_bypass)."',`heart_transplant`='".add_escape_custom($heart_transplant)."',`stress_test`='".add_escape_custom($stress_test)."',
                    `emphysema`='".add_escape_custom($emphysema)."',`chronic_bronchitis`='".add_escape_custom($chronic_bronchitis)."',`interstitial_lung_disease`='".add_escape_custom($interstitial_lung_disease)."',
                    `shortness_of_breath_2`='".add_escape_custom($shortness_of_breath_2)."',`lung_cancer`='".add_escape_custom($lung_cancer)."',`lung_cancer_surgery`='".add_escape_custom($lung_cancer_surgery)."',
                    `pheumothorax`='".add_escape_custom($pheumothorax)."',`stomach_pains`='".add_escape_custom($stomach_pains)."',`peptic_ulcer_disease`='".add_escape_custom($peptic_ulcer_disease)."',`gastritis`='".add_escape_custom($gastritis)."',
                    `endoscopy`='".add_escape_custom($endoscopy)."',`polyps`='".add_escape_custom($polyps)."',`colonoscopy`='".add_escape_custom($colonoscopy)."',`colon_cancer`='".add_escape_custom($colon_cancer)."',
                    `colon_cancer_surgery`='".add_escape_custom($colon_cancer_surgery)."',`ulcerative_colitis`='".add_escape_custom($ulcerative_colitis)."',`crohns_disease`='".add_escape_custom($crohns_disease)."',
                    `appendectomy`='".add_escape_custom($appendectomy)."',`divirticulitis`='".add_escape_custom($divirticulitis)."',`divirticulitis_surgery`='".add_escape_custom($divirticulitis_surgery)."',
                    `gall_stones`='".add_escape_custom($gall_stones)."',`cholecystectomy`='".add_escape_custom($cholecystectomy)."',`hepatitis`='".add_escape_custom($hepatitis)."',`cirrhosis_of_the_liver`='".add_escape_custom($cirrhosis_of_the_liver)."',
                    `splenectomy`='".add_escape_custom($splenectomy)."',`kidney_failure`='".add_escape_custom($kidney_failure)."',`kidney_stones`='".add_escape_custom($kidney_stones)."',`kidney_cancer`='".add_escape_custom($kidney_cancer)."',
                    `kidney_infections`='".add_escape_custom($kidney_infections)."',`bladder_infections`='".add_escape_custom($bladder_infections)."',`bladder_cancer`='".add_escape_custom($bladder_cancer)."',
                    `prostate_problems`='".add_escape_custom($prostate_problems)."',`prostate_cancer`='".add_escape_custom($prostate_cancer)."',`kidney_transplant`='".add_escape_custom($kidney_transplant)."',
                    `sexually_transmitted_disease`='".add_escape_custom($sexually_transmitted_disease)."',`burning_with_urination`='".add_escape_custom($burning_with_urination)."',`discharge_from_urethra`='".add_escape_custom($discharge_from_urethra)."',
                    `rashes`='".add_escape_custom($rashes)."',`infections`='".add_escape_custom($infections)."',`ulcerations`='".add_escape_custom($ulcerations)."',`pemphigus`='".add_escape_custom($pemphigus)."',`herpes`='".add_escape_custom($herpes)."',
                    `osetoarthritis`='".add_escape_custom($osetoarthritis)."',`rheumotoid_arthritis`='".add_escape_custom($rheumotoid_arthritis)."',`lupus`='".add_escape_custom($lupus)."',`ankylosing_sondlilitis`='".add_escape_custom($ankylosing_sondlilitis)."'
                    ,`swollen_joints`='".add_escape_custom($swollen_joints)."',`stiff_joints`='".add_escape_custom($stiff_joints)."',`broken_bones`='".add_escape_custom($broken_bones)."',`neck_problems`='".add_escape_custom($neck_problems)."',
                    `back_problems`='".add_escape_custom($back_problems)."',`back_surgery`='".add_escape_custom($back_surgery)."',`scoliosis`='".add_escape_custom($scoliosis)."',`herniated_disc`='".add_escape_custom($herniated_disc)."',
                    `shoulder_problems`='".add_escape_custom($shoulder_problems)."',`elbow_problems`='".add_escape_custom($elbow_problems)."',`wrist_problems`='".add_escape_custom($wrist_problems)."',`hand_problems`='".add_escape_custom($hand_problems)."',
                    `hip_problems`='".add_escape_custom($hip_problems)."',`knee_problems`='".add_escape_custom($knee_problems)."',`ankle_problems`='".add_escape_custom($ankle_problems)."',`foot_problems`='".add_escape_custom($foot_problems)."',
                    `insulin_dependent_diabetes`='".add_escape_custom($insulin_dependent_diabetes)."',`noninsulin_dependent_diabetes`='".add_escape_custom($noninsulin_dependent_diabetes)."',`hypothyroidism`='".add_escape_custom($hypothyroidism)."',
                    `hyperthyroidism`='".add_escape_custom($hyperthyroidism)."',`cushing_syndrom`='".add_escape_custom($cushing_syndrom)."',`addison_syndrom`='".add_escape_custom($addison_syndrom)."',
                    `additional_notes`='".add_escape_custom($additional_notes)."' 
                    WHERE id = ?";

        $result = sqlStatement($strQuery,array($id));


        if ($result !== FALSE) {

            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Review of System Checks has been updated</reason>";
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>ERROR: Sorry, there was an error processing your data. Please re-submit the information again.</reason>";
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</roschecks>";
echo $xml_string;
?>