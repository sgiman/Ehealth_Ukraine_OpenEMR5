<?php

/**
 * api/addprescription.php add new patient's prescription.
 *
 * Api add's patient prescriptions.
 * 
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */
header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;
require('classes.php');

$xml_string = "";
$xml_string = "<prescription>";

$token = $_GET['token'];
$patientId = $_GET['patientId'];
$startDate = $_GET['startDate'];
$drug = $_GET['drug'];
$visit_id = $_GET['visit_id'];
$dosage = $_GET['dosage'];
$quantity = $_GET['quantity'];

$per_refill = $_GET['refill'];
$medication = $_GET['medication'];
$note = $_GET['note'];
$provider_id = $_GET['provider_id'];

$drug_form = $_GET['drug_form'];
$drug_units = $_GET['drug_units'];
$drug_route = $_GET['drug_route'];
$drug_interval = $_GET['drug_interval'];
$substitute = $_GET['substitute'];

$size = $_GET['medicine_units'];
$p_refill = $_GET['per_refill'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('patients', 'med', $user);

    if ($acl_allow) {
        $provider_username = getProviderUsername($provider_id);

        $strQuery = "INSERT INTO prescriptions (patient_id, date_added, date_modified, provider_id, start_date, drug, form ,dosage, quantity, unit, route, `interval`, substitute, refills, medication, note, active, encounter, size, per_refill) 
                                            VALUES (
                                            " . add_escape_custom($patientId) . ",
                                            '" . date('Y-m-d') . "',
                                            '" . date('Y-m-d') . "',
                                             " . add_escape_custom($provider_id) . ",
                                            '" . add_escape_custom($startDate) . "',
                                            '" . add_escape_custom($drug) . "',
                                            '" . add_escape_custom($drug_form) . "',
                                            '" . add_escape_custom($dosage) . "',
                                            '" . add_escape_custom($quantity) . "',
                                            '" . add_escape_custom($drug_units) . "',
                                            '" . add_escape_custom($drug_route) . "',
                                            '" . add_escape_custom($drug_interval) . "',
                                            '" . add_escape_custom($substitute) . "',
                                            '" . add_escape_custom($per_refill) . "',
                                            " . add_escape_custom($medication) . ",
                                            '" . add_escape_custom($note) . "',
                                            1,
                                            " . add_escape_custom($visit_id) . ",
                                            '" . add_escape_custom($size) . "',
                                            '" . add_escape_custom($p_refill) . "'
                                                )";

        if ($medication) {
            $list_query = "insert into lists(date,begdate,type,activity,pid,user,groupname,title) 
                            values (now(),cast(now() as date),'medication',1," . add_escape_custom($patientId) . ",'" . add_escape_custom($user) . "','','" . add_escape_custom($drug) . "')";
            $list_result = sqlStatement($list_query);
        }else{
            $list_result = true;
        }
        $result = sqlStatement($strQuery);

        $device_token_badge = getDeviceTokenBadge($provider_username, 'prescription');
        $badge = $device_token_badge ['badge'];
        $deviceToken = $device_token_badge ['device_token'];
        if ($deviceToken) {
            $notification_res = notification($deviceToken, $badge, $msg_count = 0, $apt_count = 0, $message = 'New Prescription Notification!');
        }

        if ($result && $list_result) {


            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>Patient prescription added successfully</reason>";
            if ($notification_res) {
                $xml_array['notification'] = 'Add Prescription Notification(' . $notification_res . ')';
            } else {
                $xml_array['notification'] = 'Notificaiotn Failed.';
            }
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>Couldn't add record</reason>";
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</prescription>";
echo $xml_string;
?>