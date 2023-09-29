<?php
/**
 * api/updatepatientdocument.php Update patient documents.
 *
 * API is allowed to update patient documents like Lab Report,
 * Patient Photograph, and Medical Record.  
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

$xml_array = array();

$token = $_GET['token'];

$image_content = $_GET['data'];
$patient_id = $_GET['patientId'];
$document_id = $_GET['document_id'];
$url = $_GET['url'];
$categoryId = $_GET['categoryId'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('patients', 'docs', $user);

    if ($acl_allow) {

        $provider_id = getPatientsProvider($patient_id);
        $provider_username = getProviderUsername($provider_id);

        $image_name = basename($url);

        $image_path = $sitesDir . "{$site}/documents/{$patient_id}/{$image_name}";

        if (unlink($image_path)) {
            file_put_contents($image_path, base64_decode($image_content));
        }

        $hash = sha1_file($image_path);

        $size = filesize($url);

        $strQuery = "UPDATE `documents` SET `size`='" . add_escape_custom($size) . "',`hash`='" . add_escape_custom($hash) . "' WHERE id = ?";
        $result = sqlStatement($strQuery, array($document_id));

        if ($categoryId == 2) {
            $device_token_badge = getDeviceTokenBadge($provider_username, 'labreport');
            $badge = $device_token_badge ['badge'];
            $deviceToken = $device_token_badge ['device_token'];
            if ($deviceToken) {
                $notification_res = notification($deviceToken, $badge, $msg_count = 0, $apt_count = 0, $message = 'Labreport Updated, Notification!');
            }
        }

        if ($result !== FALSE) {
            $xml_array['status'] = "0";
            $xml_array['reason'] = "The Image has been Updated";
            if ($notification_res) {
                $xml_array['notification'] = 'Add Patient document Notification(' . $notification_res . ')';
            } else {
                $xml_array['notification'] = 'Notificaiotn Failed.';
            }
        } else {
            $xml_array['status'] = "-1";
            $xml_array['reason'] = "ERROR: Sorry, there was an error processing your data. Please re-submit the information again.";
        }
    } else {
        $xml_array['status'] = -2;
        $xml_array['reason'] = 'You are not Authorized to perform this action';
    }
} else {
    $xml_array['status'] = "-2";
    $xml_array['reason'] = 'Invalid Token';
}


$xml = ArrayToXML::toXml($xml_array, 'PatientImage');
echo $xml;
?>
