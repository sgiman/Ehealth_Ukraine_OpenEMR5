<?php
/**
 * api/addpatientdocumentwithlink.php add new patient's document.
 *
 * Api add's patient document againt a particular category with file url.
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
require_once("$srcdir/documents.php");
$xml_array = array();

$token = $_GET['token'];

$patient_id = $_GET['patientId'];
$docdate = $_GET['docDate'];
$list_id = !empty($_GET['listId']) ? $_GET['listId'] : 0;
$cat_id = $_GET['categoryId'];
$link = $_GET['link'];
$ext = $_GET['docType'];
$mimetype = $_GET['mimeType'];

$image_content = file_get_contents($link);

if ($userId = validateToken($token)) {
    $provider_id = getPatientsProvider($patient_id);
    $provider_username = getProviderUsername($provider_id);
            
    $user = getUsername($userId);
    $acl_allow = acl_check('patients', 'docs', $user);

   

    if ($acl_allow) {
            $id = 1;
            $type = "file_url";
            $size = '';
            $date = date('Y-m-d H:i:s');
            $url = '';
            $hash = '';
            $image_path = $sitesDir . "{$site}/documents/{$patient_id}";

            if (!file_exists($image_path)) {
                mkdir($image_path);
            }

            $image_date = date('YmdHis');
            $image_root_path = $image_path . "/" . $image_date . "." . $ext;
            file_put_contents($image_root_path , $image_content);
            
            $res = addNewDocument($image_date. "." . $ext,'image/png',$image_root_path,0,filesize($image_root_path),$userId,$patient_id,$cat_id,$higher_level_path='',$path_depth='1');

            

                $lab_report_catid = document_category_to_id("Lab Report");
                
                if ($cat_id == $lab_report_catid) {
                    $device_token_badge = getDeviceTokenBadge($provider_username, 'labreport');
                    $badge = $device_token_badge ['badge'];
                    $deviceToken = $device_token_badge ['device_token'];
                    if ($deviceToken) {
                        $notification_res = notification($deviceToken, $badge, $msg_count = 0, $apt_count = 0, $message = 'New Labreport Notification!');
                    }
                }


                if ($res) {
                    $xml_array['status'] = "0";
                    $xml_array['reason'] = "Document added successfully";
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
