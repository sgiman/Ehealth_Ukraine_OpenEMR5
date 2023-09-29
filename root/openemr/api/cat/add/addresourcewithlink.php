<?php
/**
 * api/addresourcewithlink.php add new user's resources.
 *
 * Api add's users resources with url of the file 
 * such as images, url, videos, pdf.
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
$title = $_GET['title'];
$option_id = $_GET['option_id'];
$type = $_GET['type'];
$link = isset($_GET['link']) ? $_GET['link'] : '';
$ext = $_GET['ext'];

$list_id = 'ExternalResources';
$seq = 0;
$is_default = 0;
$notes = '';
$mapping = '';

if ($userId = validateToken($token)) {
    $username = getUsername($userId);
    $acl_allow = acl_check('admin', 'users', $username);

    if ($acl_allow) {

        $provider_id = $userId;

        $path = $sitesDir . "{$site}/documents/userdata";


        if (!file_exists($path)) {
            mkdir($path);
            mkdir($path . "/images");
            mkdir($path . "/images/thumb/");
            mkdir($path . "/pdf");
            mkdir($path . "/videos");
        } elseif (!file_exists($path . "/images") || !file_exists($path . "/images/thumb/") || !file_exists($path . "/pdf") || !file_exists($path . "/videos")) {
            mkdir($path . "/images");
            mkdir($path . "/images/thumb/");
            mkdir($path . "/pdf");
            mkdir($path . "/videos");
        }

        $data = file_get_contents($link);

        if ($data) {
            switch ($type) {
                case 'link':
                    $notes = $link;
                    break;
                case 'image':
                    $image_date_name = date('Y-m-d_H-i-s');
                    $image_name = $image_date_name . "." . $ext;
                    $image_path = $path . "/images/" . $image_name;
                    file_put_contents($image_path, $data);
                    $thumb_path = $path . "/images/thumb/";
                    createThumbnail($image_path, $image_date_name, 250, $thumb_path);
                    $notes = $sitesUrl . "{$site}/documents/userdata/images/" . $image_name;
                    break;
                case 'pdf':
                    $pdf_name = date('Y-m-d_H-i-s') . "." . $ext;
                    file_put_contents($path . "/pdf/" . $pdf_name, $data);
                    $notes = $sitesUrl . "{$site}/documents/userdata/pdf/" . $pdf_name;
                    break;
                case 'video':
                    $video_name = date('Y-m-d_H-i-s') . "." . $ext;
                    file_put_contents($path . "/videos/" . $video_name, $data);
                    $notes = $sitesUrl . "{$site}/documents/userdata/videos/" . $video_name;
                    break;
            }


            $select_query = "SELECT *  FROM `list_options` 
        WHERE `list_id` LIKE 'lists' AND `option_id` LIKE '" . add_escape_custom($list_id) . "' AND `title` LIKE '" . add_escape_custom($list_id) . "'";

            $result_select = sqlQuery($select_query);
            $result1 = true;
            if (!$result_select) {
                $insert_list = "INSERT INTO list_options ( list_id, option_id, title, seq, is_default, option_value ) 
                            VALUES ( 'lists','" . add_escape_custom($list_id) . "','" . add_escape_custom($list_id) . "', '0','1', '0')";
                $result1 = sqlInsert($insert_list);
            }

            $strQuery = "INSERT INTO `list_options`(`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`) 
                        VALUES (
                        '" . add_escape_custom($list_id) . "',
                        '" . add_escape_custom($option_id) . "',
                        '" . add_escape_custom($title) . "',
                        '" . add_escape_custom($seq) . "',
                        '" . add_escape_custom($is_default) . "',
                        '" . add_escape_custom($provider_id) . "',
                        '" . add_escape_custom($mapping) . "',
                        '" . add_escape_custom($notes) . "')";
                  
            
            $result = sqlInsert($strQuery);

            if ($result && $result1) {
                $xml_array['status'] = "0";
                $xml_array['reason'] = "The Resource has been added";
            } else {
                $xml_array['status'] = "-1";
                $xml_array['reason'] = "ERROR: Sorry, there was an error processing your data. Please re-submit the information again.";
            }
        } else {
            $xml_array['status'] = "-1";
            $xml_array['reason'] = "Invalid Url (Resource not found)";
        }
    } else {
        $xml_array['status'] = -2;
        $xml_array['reason'] = 'You are not Authorized to perform this action';
    }
} else {
    $xml_array['status'] = "-2";
    $xml_array['reason'] = 'Invalid Token';
}


$xml = ArrayToXML::toXml($xml_array, 'Resource');
echo $xml;
?>