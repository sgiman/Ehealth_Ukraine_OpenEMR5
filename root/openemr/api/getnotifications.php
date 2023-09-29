<?php

/**
 * api/getnotifications.php patient notifications.
 *
 * API returns patients notifications.
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
$xml_string = "<notifications>";

$token = $_GET['token'];
$primary_business_entity = 0;

if ($userId = validateToken($token)) {

    if ($GLOBALS['push_notification']) {
        $strQuery = "SELECT * FROM `api_tokens` WHERE token = ?";
        $result = sqlQuery($strQuery, array($token));

        if ($result) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>Notifications fetching.</reason>";
            $xml_string .= "<message_badge>{$result['message_badge']}</message_badge>";
            $xml_string .= "<appointment_badge>{$result['appointment_badge']}</appointment_badge>";
            $xml_string .= "<labreports_badge>{$result['labreports_badge']}</labreports_badge>";
            $xml_string .= "<prescription_badge>{$result['prescription_badge']}</prescription_badge>";
            $xml_string .= "<total_badge>" . ($result['message_badge'] + $result['appointment_badge'] + $result['labreports_badge'] + $result['prescription_badge']) . "</total_badge>";
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>ERROR: Sorry, there was an error processing your data. Please re-submit the information again.</reason>";
        }
    } else {
        $xml_string .= "<status>0</status>";
        $xml_string .= "<reason>Notifications fetching.</reason>";
        $xml_string .= "<message_badge>0</message_badge>";
        $xml_string .= "<appointment_badge>0</appointment_badge>";
        $xml_string .= "<labreports_badge>0</labreports_badge>";
        $xml_string .= "<prescription_badge>0</prescription_badge>";
        $xml_string .= "<total_badge>0</total_badge>";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</notifications>";
echo $xml_string;
?>