<?php
/**
 * api/deleteresource.php delete user resources.
 *
 * API is allowed to delete resources for user.
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
$xml_string = "<Resource>";

$token = $_GET['token'];
$option_id = $_GET['option_id'];
$list_id = 'ExternalResources';

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    
    $acl_allow = acl_check('admin', 'super', $user);
    if ($acl_allow) {
        $strQuery1 = "SELECT notes
                    FROM `list_options`
                    WHERE `list_id` LIKE ? AND 
                    `option_id` LIKE ? ";
        
        $result1= sqlQuery($strQuery1, array($list_id, $option_id));
        $file_path = $result1['notes'];

        $temp_path = explode("userdata", $file_path);
        $relative_path = $sitesDir . "{$site}/documents/userdata" . $temp_path[1];


        if (file_exists($relative_path)) {
            unlink($relative_path);
        }

        $thumb_name = end(explode("/", $temp_path[1]));
        $relative_path_thumb = $sitesDir . "{$site}/documents/userdata/images/thumb/" . $thumb_name;

        if (file_exists($relative_path_thumb)) {
            unlink($relative_path_thumb);
        }

        $strQuery = "DELETE FROM list_options WHERE list_id LIKE '{$list_id}' AND 
                    `option_id` LIKE ?";
		$result = sqlStatement($strQuery, array($option_id));



        if ($result){
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Resource has been deleted</reason>";
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

$xml_string .= "</Resource>";
echo $xml_string;
?>