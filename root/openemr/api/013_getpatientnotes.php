<?php
/**
 * api/getpatientnotes.php get patient's notes.
 * (13) get patient notes
 *
 * Api to get patient notes.
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
$patient_id = $_GET['patientId'];
$active = isset($_GET['active']) ? $_GET['active'] : 1;

if ($userId = validateToken($token)) {

    $username = getUsername($userId);
    $acl_allow = acl_check('patients', 'notes', $username);

    if ($acl_allow) {

        $patient_data = getPnotesByDate("", $active, 'id,date,body,user,activity,title,assigned_to,message_status', $patient_id);

        if ($patient_data) {

            $xml_array['status'] = 0;
            $xml_array['reason'] = 'The Patient notes has been fetched successfully';
            foreach ($patient_data as $key => $patientnote) {
                $xml_array['patientnote' . $key]['id'] = $patientnote['id'];
                $xml_array['patientnote' . $key]['date'] = $patientnote['date'];
                $xml_array['patientnote' . $key]['body'] = $patientnote['body'];
                $xml_array['patientnote' . $key]['user'] = $patientnote['user'];
                $xml_array['patientnote' . $key]['activity'] = $patientnote['activity'];
                $xml_array['patientnote' . $key]['title'] = $patientnote['title'];
                $xml_array['patientnote' . $key]['assigned_to'] = $patientnote['assigned_to'];
                $xml_array['patientnote' . $key]['message_status'] = $patientnote['message_status'];
            }
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


$xml = ArrayToXML::toXml($xml_array, 'PatientNotes');
echo $xml;
?>