<?php
/**
 * api/getuserlist.php Get user list.
 *
 * API is allowed to get list of users with details.
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

//$token = $_POST['token'];
$token = $_GET['token'];

$xml_string = "";
$xml_string .= "<users>\n";

if (validateToken($token)) {
    $user = getUsername($userId);
    //$acl_allow = acl_check('admin', 'users', $user);
    $acl_allow = true;
    if ($acl_allow) {
        $strQuery = "SELECT id,fname,lname,mname, username
                                FROM `users`
                                WHERE username != '' AND password != '' AND active = 1";


        $result = sqlStatement($strQuery);
        $numRows = sqlNumRows($result);
        if ($numRows > 0) {
            $xml_string .= "<status>0</status>\n";
            $xml_string .= "<reason>The User list Record has been fetched</reason>\n";
            $counter = 0;

            while ($res = sqlFetchArray($result)) {
                $xml_string .= "<user>\n";

                foreach ($res as $fieldname => $fieldvalue) {
                    $rowvalue = xmlsafestring($fieldvalue);
                    $xml_string .= "<$fieldname>$rowvalue</$fieldname>\n";
                }

                $xml_string .= "</user>\n";
                $counter++;
            }
        } else {
            $xml_string .= "<status>-1</status>\n";
            $xml_string .= "<reason>ERROR: Sorry, there was an error processing your data. Please re-submit the information again.</reason>\n";
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>\n";
    $xml_string .= "<reason>Invalid Token</reason>\n";
}


$xml_string .= "</users>\n";
echo $xml_string;
?>