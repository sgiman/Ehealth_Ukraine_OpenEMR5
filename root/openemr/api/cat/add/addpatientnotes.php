<?php

/**
 * api/addpatientnotes.php add patient's notes.
 *
 * Api add's patient notes.
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
$notes = $_GET['notes'];
$title = isset($_GET['title']) ? $_GET['title'] : 'Unassigned';
$authorized = isset($_GET['authorized']) ? $_GET['title'] : '0';
$activity = isset($_GET['activity']) ? $_GET['activity'] : '1';
$assigned_to = isset($_GET['assigned_to']) ? $_GET['assigned_to'] : '';
$datetime = isset($_GET['datetime']) ? $_GET['datetime'] : '';
$message_status = isset($_GET['message_status']) ? $_GET['message_status'] : 'New';

if ($userId = validateToken($token)) {

    $username = getUsername($userId);
    $acl_allow = acl_check('patients', 'notes', $username);

    if ($acl_allow) {
        $_SESSION['authProvider'] = getAuthGroup($username);
        $_SESSION['authUser'] = $username;
        $result = addPnote($patientId, $notes, $authorized, $activity, $title, $assigned_to, $datetime, $message_status, $username);

        if ($result) {
            $xml_array['status'] = 0;
            $xml_array['result'] = $result;
            $xml_array['reason'] = 'The Patient notes has been added successfully';
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