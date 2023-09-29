<?php

/**
 * api/updatelocation.php Update location.
 *
 * API is allowed to update location.
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
$xml_string = "<location>";

$token = $_GET['token'];
$name = $_GET['name'];
$locationId = $_GET['id'];

if ($userId = validateToken($token)) {

    $user = getUsername($userId);

    $acl_allow = acl_check('admin', 'super', $user);
    
    if ($acl_allow) {
        
        $strQuery = 'UPDATE facility SET ';
        $strQuery .= ' name = "' .add_escape_custom($name) . '"';
        $strQuery .= ' WHERE id = ?' ;
        $result = sqlStatement($strQuery,array($locationId));

        if ($result !== FALSE) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Location has been updated</reason>";
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

$xml_string .= "</location>";
echo $xml_string;
?>