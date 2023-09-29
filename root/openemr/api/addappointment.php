<?php

/**
 * api/addappointment.php Schedule new appointment.
 *
 * Api allows to schedule new appointment for a patient.
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
require_once("$srcdir/encounter_events.inc.php");

$xml_array = array();

$token = $_GET['token'];
$pc_catid = $_GET['pc_catid'];
$patientId = $_GET['patientId'];
$pc_title = $_GET['pc_title'];
$appointmentDate = $_GET['appointmentDate'];
$appointmentTime = date("H:i:s", strtotime($_GET['appointmentTime']));
$app_status = $_GET['pc_apptstatus'];
$admin_id = $_GET['uprovider_id'];
$facility = $_GET['pc_facility'];
$pc_billing_location = $_GET['pc_billing_location'];
$comments = $_GET['pc_hometext'];
$pc_duration = $_GET['pc_duration'];


$endTime = date('H:i:s', strtotime($_GET['appointmentTime']) + $pc_duration);


$recurrspecs = array("event_repeat_freq" => "",
    "event_repeat_freq_type" => "",
    "event_repeat_on_num" => "1",
    "event_repeat_on_day" => "0",
    "event_repeat_on_freq" => "0",
    "exdate" => ""
);
$recurrspec = serialize($recurrspecs);

$locationspecs = array("event_location" => "",
    "event_street1" => "",
    "event_street2" => "",
    "event_city" => "",
    "event_state" => "",
    "event_postal" => ""
);
$locationspec = serialize($locationspecs);

if ($userId = validateToken($token)) {

    $user = getUsername($userId);
    
    $provider_username = getProviderUsername($admin_id);

    $acl_allow = acl_check('patients', 'appt', $user);
    if ($acl_allow) {
        $args = array('form_category'=>$pc_catid,'form_provider'=>$admin_id,'form_pid'=>$patientId,
                       'form_title'=>$pc_title,'form_comments'=>$comments,'event_date'=>$appointmentDate,
                        'form_enddate'=>'','duration'=>$pc_duration,'recurrspec'=>$recurrspecs,
                        'starttime'=>$appointmentTime,'endtime'=>$endTime,'form_allday'=>0,
                        'form_apptstatus'=>$app_status,'form_prefcat'=>0,'locationspec'=>$locationspec,
                        'facility'=>$facility,'billing_facility'=>$pc_billing_location);
        $result = InsertEvent($args);

        $device_token_badge = getDeviceTokenBadge($provider_username, 'appointment');
        $badge = $device_token_badge ['badge'];
        $deviceToken = $device_token_badge ['device_token'];
        if ($deviceToken) {
            $notification_res = notification($deviceToken, $badge, $msg_count = 0, $apt_count = 0, $message = 'New Appointment Notification!');
        }

        if ($result) {

            $xml_array['status'] = 0;
            $xml_array['reason'] = 'The Appointment has been added.';
            if ($notification_res) {
                $xml_array['notification'] = 'Add Appointment Notification(' . $notification_res . ')';
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