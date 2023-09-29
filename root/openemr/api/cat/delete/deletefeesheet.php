<?php
/**
 * api/deletefeesheet.php Delete fee sheet items.
 *
 * API is allowed to delete fee sheet items (price and units) for billing.
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
$xml_string = "<feesheet>";

$token = $_GET['token'];
$id = $_GET['id'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    
    $acl_allow = acl_check('admin', 'super', $user);
    if ($acl_allow) {
        deleteBilling($id);
        $xml_string .= "<status>0</status>";
        $xml_string .= "<reason>The Feesheet Item has been deleted</reason>";
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</feesheet>";
echo $xml_string;
?>