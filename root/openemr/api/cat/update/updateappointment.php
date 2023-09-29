<?php
/**
 * api/updateappointment.php Update appointment.
 *
 * API is allowed to update patient appointment information.
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
$appointmentId = $_GET['appointmentId'];
$pc_catid = $_GET['pc_catid'];
$pc_hometext = $_GET['pc_hometext'];
$appointmentDate = $_GET['appointmentDate'];
$appointmentTime = date("H:i:s", strtotime($_GET['appointmentTime']));
$app_status = $_GET['pc_apptstatus'];
$pc_title = $_GET['pc_title'];
$patientId = $_GET['patientId'];
$admin_id = $_GET['uprovider_id'];
$facility = $_GET['pc_facility'];
$pc_billing_location = $_GET['pc_billing_location'];
$pc_duration = $_GET['pc_duration'];

$app_status = $app_status == 'p' ? '+' : $app_status;

$endTime = date('H:i:s', strtotime($_GET['appointmentTime']) + $pc_duration);

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $username = $user;
  
    $provider_username = getProviderUsername($admin_id);
    $acl_allow = acl_check('patients', 'appt', $username);

    if ($acl_allow) {

        $strQuery = "UPDATE openemr_postcalendar_events SET 
                        pc_title = '" . add_escape_custom($pc_title) . "', 
                        pc_hometext = '" . add_escape_custom($pc_hometext) . "' , 
                        pc_catid = '" . add_escape_custom($pc_catid) . "' , 
                        pc_eventDate = '" . add_escape_custom($appointmentDate) . "', 
                        pc_startTime = '" . add_escape_custom($appointmentTime) . "', 
                        pc_endTime = '" . add_escape_custom($endTime) . "', 
                        pc_aid = '" . add_escape_custom($admin_id) . "', 
                        pc_facility = '" . add_escape_custom($facility) . "',
                        pc_billing_location = '" . add_escape_custom($pc_billing_location) . "',
                        pc_duration = '" . add_escape_custom($pc_duration) . "',
                        pc_pid = '" . add_escape_custom($patientId) . "',
                        pc_apptstatus = '" . add_escape_custom($app_status) . "' 
                    WHERE pc_eid=?";

        $result = sqlStatement($strQuery,array($appointmentId));

        $device_token_badge = getDeviceTokenBadge($provider_username, 'appointment');
        $badge = $device_token_badge ['badge'];
        $deviceToken = $device_token_badge ['device_token'];
        if ($deviceToken) {
            $notification_res = notification($deviceToken, $badge, $msg_count = 0, $apt_count = 0, $message = 'Appointment Updated!');
        }

        if ($result !== FALSE) {
            $xml_array['status'] = 0;
            $xml_array['reason'] = 'The Appointment has been updated.';
            if ($notification_res) {
                $xml_array['notification'] = 'Update Appointment Notification(' . $notification_res . ')';
            } else {
                $xml_array['notification'] = 'Notificaiotn Failed.';
            }
        } else {
            $xml_array['status'] = -1;
            $xml_array['reason'] = 'ERROR: Sorry, there was an error processing your request. Please re-submit the information again.';
        }
    } else {
        $xml_array['status'] = -2;
        $xml_array['reason'] = 'You are not Authorized to perform this action';
    }
} else {
    $xml_array['status'] = -2;
    $xml_array['reason'] = 'Invalid Token';
}


$xml = ArrayToXML::toXml($xml_array, 'Appointment');
echo $xml;
?>