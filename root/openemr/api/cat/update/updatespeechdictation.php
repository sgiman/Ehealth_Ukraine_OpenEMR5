<?php
/**
 * api/updatespeechdictation.php update patient's Speech Dictation.
 *
 * Api update patient Speech Dictation against particular visit/encounter.
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
$id = $_GET['id'];
$dictation = $_GET['dictation'];
$additional_notes = $_GET['additional_notes'];


if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('encounters', 'auth_a', $user);

    if ($acl_allow) {
        $strQuery = 'UPDATE form_dictation SET ';
        $strQuery .= ' dictation = "' . add_escape_custom($dictation) . '",';
        $strQuery .= ' additional_notes = "' . add_escape_custom($additional_notes) . '"';
        $strQuery .= ' WHERE id = ?';

        $result = sqlStatement($strQuery, array($id));

        if ($result) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Speech Dictation has been updated</reason>";
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

$xml_string .= "</speechdictation>";
echo $xml_string;
?>