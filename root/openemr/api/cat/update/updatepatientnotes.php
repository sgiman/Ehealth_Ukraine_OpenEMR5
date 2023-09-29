<?php
/**
 * api/updatepatientnotes.php Update patient notes.
 *
 * API is allowed to update patient notes. 
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

$xml_array = array();

$token = $_GET['token'];
$noteId = $_GET['noteId'];
$notes = $_GET['notes'];
$title = $_GET['title'];
$assigned_to = $_GET['assigned_to'];

if ($userId = validateToken($token)) {
    
    $username = getUsername($userId);
    $acl_allow = acl_check('patients', 'notes', $username);

    if ($acl_allow) {
            $result = updatePnote($noteId, $notes, $title, $assigned_to);
            $xml_array['status'] = 0;
            $xml_array['reason'] = 'The Patient notes has been updated';
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_array['status'] = -2;
    $xml_array['reason'] = 'Invalid Token';
}
$xml = ArrayToXML::toXml($xml_array, 'PatientNotes');
echo $xml;
?>

