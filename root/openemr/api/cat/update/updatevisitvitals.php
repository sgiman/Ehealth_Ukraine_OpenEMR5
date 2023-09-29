<?php
/**
 * api/updatevisitvitals.php Update vitals against visit.
 *
 * API is allowed to update vitals for patient visit.
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
$xml_array = array();

$token = $_GET['token'];
$patientId = $_GET['patientId'];
$vital_id = $_GET['vital_id'];

$date = date('Y-m-d H:i:s');
$groupname = $_GET['groupname'];
$authorized = $_GET['authorized'];
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
        $strQuery = "UPDATE `form_vitals` SET 
                                        `date`='" . add_escape_custom($date) . "',
                                        `pid`='" . add_escape_custom($patientId) . "',
                                        `user`='" . add_escape_custom($user) . "',
                                        `groupname`='" . add_escape_custom($groupname) . "',
                                        `authorized`='" . add_escape_custom($authorized) . "',
                                        `activity`='" . add_escape_custom($activity) . "',
                                        `bps`='" . add_escape_custom($bps) . "',
                                        `bpd`='" . add_escape_custom($bpd) . "',
                                        `weight`='" . add_escape_custom($weight) . "',
                                        `height`='" . add_escape_custom($height) . "',
                                        `temperature`='" . add_escape_custom($temperature) . "',
                                        `temp_method`='" . add_escape_custom($temp_method) . "',
                                        `pulse`='" . add_escape_custom($pulse) . "',
                                        `respiration`='" . add_escape_custom($respiration) . "',
                                        `note`='" . add_escape_custom($note) . "',
                                        `BMI`='" . add_escape_custom($BMI) . "',
                                        `BMI_status`='" . add_escape_custom($BMI_status) . "',
                                        `waist_circ`='" . add_escape_custom($waist_circ) . "',
                                        `head_circ`='" . add_escape_custom($head_circ) . "',
                                        `oxygen_saturation`='" . add_escape_custom($oxygen_saturation) . "' 
                                         WHERE id = ?";

        $result = sqlStatement($strQuery, array($vital_id));

        if ($result !== FALSE) {
            $xml_array['status'] = 0;
            $xml_array['reason'] = 'Visit vital update successfully';
        } else {
            $xml_array['status'] = -1;
            $xml_array['reason'] = 'Could not update isit vital';
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_array['status'] = -2;
    $xml_array['reason'] = 'Invalid Token';
}


$xml = ArrayToXML::toXml($xml_array, 'visitvitals');
echo $xml;
?>