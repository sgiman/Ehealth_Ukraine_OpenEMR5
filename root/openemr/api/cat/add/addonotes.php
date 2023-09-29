<?php

/**
 * api/addonotes.php add notes.
 *
 * Api add onotes
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
include_once("$srcdir/onotes.inc");

$xml_string = "";
$xml_string .= "<officenote>";

$token = $_GET['token'];
$body = $_GET['body'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('admin', 'super', $user);

    // $_SESSION['authUser'] used in addOnote() function.
    
    $_SESSION['authUser'] = $user;
    $_SESSION['authProvider'] = getAuthGroup($user);
    

    if ($acl_allow) {
        addOnote($body);
        $xml_string .= "<status>0</status>\n";
        $xml_string .= "<reason>Office Note Added Successfully</reason>\n";
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</officenote>";
echo $xml_string;
?>