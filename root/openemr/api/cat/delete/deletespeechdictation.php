<?php
/**
 * api/deletespeechdictation.php delete patient Speech Dictation against a visit/encounter.
 *
 * API is allowed to delete Speech Dictation of patient's visit.
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
$xml_string = "<speechdictation>";

$token = $_GET['token'];
$dictation_id = $_GET['id'];

if ($userId = validateToken($token)) {
    
    $user = getUsername($userId);
    $acl_allow = acl_check('admin', 'super', $user);
    
    if ($acl_allow) {     
        $strQuery = "DELETE FROM form_dictation WHERE id = ?";
	$result = sqlStatement($strQuery, array($dictation_id));

        if ($result) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>Speech Dictation deleted successfully</reason>";
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>Could not delete speech dictation</reason>";
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</speechdictation>";
echo $xml_string;
?>