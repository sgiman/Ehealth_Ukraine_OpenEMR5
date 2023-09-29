<?php
/**
 * api/deleteappointment.php Cancel appointment.
 *
 * Api allows to cancel appointment for a patient.
 * 
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */
header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;
require_once('classes.php');

$xml_string = "";
$xml_string = "<appointment>";

$token = $_GET['token'];
$id = $_GET['id'];

if ($userId = validateToken($token)) {

    $username = getUsername($userId);
    
    $acl_allow = acl_check('admin', 'super', $username);
    if ($acl_allow) {
        $strQuery = "DELETE FROM openemr_postcalendar_events WHERE pc_eid = ?";
	$result = sqlStatement($strQuery, array($id));

        if ($result) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Appointment has been deleted/removed.</reason>";
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>ERROR: Sorry, there was an error processing your request. Please re-submit the information again.</reason>";
        }

    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</appointment>";
echo $xml_string;
?>