<?php
/**
 * api/addcontactgeneral.php delete user's contact.
 *
 * Api delets's contacts for user.
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
$xml_string = "<contact>";

$token = $_GET['token'];
$id = $_GET['userId'];


if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    
    $acl_allow = acl_check('admin', 'super', $user);
    if ($acl_allow) {
        $strQuery = 'UPDATE users SET ';
        $strQuery .= ' active = 0';
        $strQuery .= ' WHERE  username = \'\' AND password = \'\' AND id = ?';
		$result = sqlStatement($strQuery, array($id));

        if ($result) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Contact has been deleted</reason>";
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

$xml_string .= "</contact>";
echo $xml_string;
?>