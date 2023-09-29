<?php

/**
 * api/getoptions.php Get options.
 *
 * API is allowed to get stats option(language, race and ethnicity).
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
$xml_string = "<list>";

$token = $_GET['token'];
$list_id = $_GET['listId'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('lists', 'default', $user);
    if ($acl_allow) {

        $strQuery = "SELECT option_id, title FROM list_options WHERE list_id  = ?";
        $result = sqlStatement($strQuery, array($list_id));
        $numRows = sqlNumRows($result);


        if ($numRows > 0) {

            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Options has been fetched</reason>";

            while ($res = sqlFetchArray($result)) {
                $xml_string .= "<option>";
                foreach ($res as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);

                    $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                }
                $xml_string .= "</option>";
            }
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

$xml_string .= "</list>";
echo $xml_string;
?>
