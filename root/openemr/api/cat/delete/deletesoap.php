<?php
/**
 * api/deletesoap.php delete patient Subjective Objective Assessment and Plan.
 *
 * API is allowed to delete SOAP of patient's visit.
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
$xml_string = "<soap>";

$token = $_GET['token'];
$soap_id = $_GET['id'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    
    $acl_allow = acl_check('admin', 'super', $user);
    if ($acl_allow) {     
        $strQuery = "DELETE FROM form_soap WHERE id = ?";
	$result = sqlStatement($strQuery, array($soap_id));

        if ($result) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>Soap Deleted successfully</reason>";
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>Could not delete soap</reason>";
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</soap>";
echo $xml_string;
?>