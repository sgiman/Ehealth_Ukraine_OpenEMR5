<?php
/**
 * api/addvisitvitals.php add patient's vitals.
 *
 * Api add's patient Vitals against particular visit.
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */
header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;

require_once'classes.php';
$xml_array = array();

$token = $_GET['token'];
$patientId = $_GET['patientId'];
$visit_id = $_GET['visit_id'];

$date = date('Y-m-d H:i:s');
$groupname = isset($_GET['groupname']) ? $_GET['groupname'] : 'default';
$authorized = isset($_GET['authorized']) ? $_GET['authorized'] : 1;
$activity = $_GET['activity'];
$bps = $_GET['bps'];
$bpd = $_GET['bpd'];
$weight = $_GET['weight'];
$height = $_GET['height'];
$temperature = $_GET['temperature'];
$temp_method = $_GET['temp_method'];
$pulse = $_GET['pulse'];
$respiration = $_GET['respiration'];
$note = $_GET['note'];
$BMI = $_GET['BMI'];
$BMI_status = $_GET['BMI_status'];
$waist_circ = $_GET['waist_circ'];
$head_circ = $_GET['head_circ'];
$oxygen_saturation = $_GET['oxygen_saturation'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('encounters', 'auth_a', $user);

    if ($acl_allow) {
        $strQuery = "INSERT INTO `form_vitals`(`date`, `pid`, `user`, `groupname`, `authorized`, `activity`, `bps`, `bpd`, `weight`, `height`, `temperature`, `temp_method`, `pulse`, `respiration`, `note`, `BMI`, `BMI_status`, `waist_circ`, `head_circ`, `oxygen_saturation`) 
                    VALUES ('".add_escape_custom($date)."','".add_escape_custom($patientId)."','".add_escape_custom($user)."','".add_escape_custom($groupname)."','".add_escape_custom($authorized)."','".add_escape_custom($activity)."','".add_escape_custom($bps)."','".add_escape_custom($bpd)."','".add_escape_custom($weight)."','".add_escape_custom($height)."','".add_escape_custom($temperature)."','".add_escape_custom($temp_method)."','".add_escape_custom($pulse)."','".add_escape_custom($respiration)."','".add_escape_custom($note)."','".add_escape_custom($BMI)."','".add_escape_custom($BMI_status)."','".add_escape_custom($waist_circ)."','".add_escape_custom($head_circ)."','".add_escape_custom($oxygen_saturation)."')";

        $result = sqlInsert($strQuery);
        $last_inserted_id = $result;

        if ($result) {
            addForm($visit_id, $form_name = 'Vitals', $last_inserted_id, $formdir = 'vitals', $patientId, $authorized = "1", $date = "NOW()", $user, $group = "Default");
            $xml_array['status'] = 0;
            $xml_array['reason'] = 'The Visit vital has been added';
        } else {
            $xml_array['status'] = -1;
            $xml_array['reason'] = 'ERROR: Sorry, there was an error processing your data. Please re-submit the information again.';
        }
    } else {
        $xml_array['status'] = -2;
        $xml_array['reason'] = 'You are not Authorized to perform this action';
    }
} else {
    $xml_array['status'] = -2;
    $xml_array['reason'] = 'Invalid Token';
}


$xml = ArrayToXML::toXml($xml_array, 'visitvitals');
echo $xml;
?>