<?php

/**
 * api/updateprescription.php Update prescription.
 *
 * API is allowed to update patient prescription. 
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
$xml_string = "<prescription>";

$token = $_GET['token'];

$id = $_GET['id'];
$startDate = $_GET['startDate'];
$drug = $_GET['drug'];

$dosage = $_GET['dosage'];
$quantity = $_GET['quantity'];

$per_refill = $_GET['refill'];
$medication = $_GET['medication'];
$note = $_GET['note'];
$provider_id = $_GET['provider_id'];

$patientId = $_GET['patientId'];

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

        $strQuery = "UPDATE `prescriptions` set
                                        provider_id = " . add_escape_custom($provider_id) . ", 
                                        start_date = '" . add_escape_custom($startDate) . "',
                                        form = '" . add_escape_custom($drug_form) . "',
                                        drug = '" . add_escape_custom($drug) . "', 
                                        dosage = '" . add_escape_custom($dosage) . "', 
                                        unit = '" . add_escape_custom($drug_units) . "', 
                                        route = '" . add_escape_custom($drug_route) . "', 
                                        `interval` = '" . add_escape_custom($drug_interval) . "', 
                                        substitute = '" . add_escape_custom($substitute) . "',
                                        quantity = '" . add_escape_custom($quantity) . "',  
                                        refills = '" . add_escape_custom($per_refill) . "', 
                                        medication = '" . add_escape_custom($medication) . "',
                                        date_modified = '" . date('Y-m-d') . "',
                                        size = '" . add_escape_custom($size) . "', 
                                        per_refill = '" . add_escape_custom($p_refill) . "',
                                        note = '" . add_escape_custom($note) . "'
                             WHERE id = ?";

        $result = sqlStatement($strQuery, array($id));

        $list_result = 1;
        if ($medication) {
            $select_medication = "SELECT * FROM  `lists` 
                                    WHERE  `type` LIKE  'medication'
                                            AND  `title` LIKE  ? 
                                            AND  `pid` = ?";
            $result1 = sqlQuery($select_medication, array($drug, $patient_id));
            if (!$result1) {
                $list_query = "insert into lists(date,begdate,type,activity,pid,user,groupname,title) 
                            values (now(),cast(now() as date),'medication',1," . add_escape_custom($patientId) . ",'" . add_escape_custom($user) . "','','" . add_escape_custom($drug) . "')";
                $list_result = sqlStatement($list_query);
            }
        }

        $device_token_badge = getDeviceTokenBadge($provider_username, 'prescription');
        $badge = $device_token_badge ['badge'];
        $deviceToken = $device_token_badge ['device_token'];
        if ($deviceToken) {
            $notification_res = notification($deviceToken, $badge, $msg_count = 0, $apt_count = 0, $message = 'Update Prescription Notification!');
        }
        if ($result !== FALSE && $list_result !== FALSE) {

            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Patient prescription has been updated</reason>";
            if ($notification_res) {
                $xml_array['notification'] = 'Update Appointment Notification(' . $notification_res . ')';
            } else {
                $xml_array['notification'] = 'Notificaiotn Failed.';
            }
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

$xml_string .= "</prescription>";
echo $xml_string;
?>