<?php

/**
 * api/updatepatientnotes.php Update patient notes status.
 *
 * API is allowed to update patient notes status. 
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
$noteIds = $_GET['noteIds'];
$active = $_GET['active'];

if ($userId = validateToken($token)) {
    
    $username = getUsername($userId);
    $acl_allow = acl_check('patients', 'notes', $username);

    if ($acl_allow) {
        $noteIds_array = explode(',', $noteIds);

        foreach ($noteIds_array as $noteId) {
            switch ($active) {
                case 1:
                    reappearPnote($noteId);
                    break;
                case 0:
                    disappearPnote($noteId);
                    break;
            }
        }

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

