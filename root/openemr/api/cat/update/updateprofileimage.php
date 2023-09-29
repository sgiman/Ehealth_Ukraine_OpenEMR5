<?php
/**
 * api/updateprofileimage.php Update profile image.
 *
 * API is allowed to update patient profile image.
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
$patientId = $_GET['patientId'];
$image_data = isset($_GET['image_data']) ? $_GET['image_data'] : '';

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('patients', 'docs', $user);

    if ($acl_allow) {

        if ($image_data) {

            $id = 1;
            $type = "file_url";
            $size = '';
            $date = date('Y-m-d H:i:s');
            $url = '';
            $mimetype = 'image/png';
            $hash = '';
            $patient_id = $patientId;
            $ext = 'png';
//            $cat_title = 'Patient Profile Image';
            $cat_title = 'Patient Photograph';

            $strQuery2 = "SELECT id from `categories` WHERE name LIKE '{$cat_title}'";
            $result3 = sqlQuery($strQuery2);

            if ($result3) {
                $cat_id = $result3['id'];
            } else {
                sqlStatement("lock tables categories read");

                $result4 = sqlQuery("select max(id)+1 as id from categories");

                $cat_id = $result4['id'];

                sqlStatement("unlock tables");

                $cat_insert_query = "INSERT INTO `categories`(`id`, `name`, `value`, `parent`, `lft`, `rght`) 
                VALUES ('" . add_escape_custom($cat_id) . "','" . add_escape_custom($cat_title) . "','',1,0,0)";

                sqlStatement($cat_insert_query);
            }

            $image_path = $sitesDir . "{$site}/documents/{$patient_id}";


            if (!file_exists($image_path)) {
                mkdir($image_path);
            }

            $image_date = date('Y-m-d_H-i-s');

            file_put_contents($image_path . "/" . $image_date . "." . $ext, base64_decode($image_data));

            $hash = sha1_file($image_path . "/" . $image_date . "." . $ext);

            $url = "file://" . $image_path . "/" . $image_date . "." . $ext;

            $size = filesize($url);

            $strQuery4 = "SELECT d.url,d.id
                                FROM `documents` AS d
                                INNER JOIN `categories_to_documents` AS c2d ON d.id = c2d.document_id
                                WHERE d.foreign_id = " . add_escape_custom($patient_id) . "
                                AND c2d.category_id =" . add_escape_custom($cat_id) . "
                                ORDER BY category_id, d.date DESC";

            $result4 = sqlQuery($strQuery4);

            if ($result4) {

                $file_path = $result4['url'];
                $document_id = $result4['id'];
                unlink($file_path);

                $strQuery = "UPDATE `documents` SET 
                                        `size`='" . add_escape_custom($size) . "',
                                        `url`='" . add_escape_custom($url) . "',
                                        `mimetype`='" . add_escape_custom($mimetype) . "',
                                        `hash`='" . add_escape_custom($hash) . "'
                                        WHERE id = " . add_escape_custom($document_id);

                $result = sqlStatement($strQuery);
            } else {


                sqlStatement("lock tables documents read");

                $result = sqlQuery("select max(id)+1 as did from documents");

                sqlStatement("unlock tables");

                if ($result['did'] > 1) {
                    $id = $result['did'];
                }



           $strQuery = "INSERT INTO `documents`( `id`, `type`, `size`, `date`, `url`, `mimetype`, `foreign_id`, `docdate`, `hash`, `list_id`) 
             VALUES (" . add_escape_custom($id) . ",'" . add_escape_custom($type) . "','" . add_escape_custom($size) . "','" . add_escape_custom($date) . "','" . add_escape_custom($url) . "','" . add_escape_custom($mimetype) . "'," . add_escape_custom($patient_id) . ",'" . add_escape_custom($docdate) . "','" . add_escape_custom($hash) . "','" . add_escape_custom($list_id) . "')";

                $result = sqlStatement($strQuery);

                $strQuery1 = "INSERT INTO `categories_to_documents`(`category_id`, `document_id`) VALUES (" . add_escape_custom($cat_id) . "," . add_escape_custom($id) . ")";

                $result1 = sqlStatement($strQuery1);
            }

            if ($result) {
                $xml_array['status'] = 0;
                $xml_array['reason'] = 'The Patient has been updated';
            } else {
                $xml_array['status'] = -2;
                $xml_array['reason'] = 'ERROR: Sorry, there was an error processing your data. Please re-submit the information again.';
            }
        } else {
            $xml_array['status'] = -2;
            $xml_array['reason'] = 'Please select the image';
        }
    } else {
        $xml_array['status'] = -2;
        $xml_array['reason'] = 'You are not Authorized to perform this action';
    }
} else {
    $xml_array['status'] = -2;
    $xml_array['reason'] = 'Invalid Token';
}

$xml = ArrayToXML::toXml($xml_array, 'Patient');
echo $xml;
?>
