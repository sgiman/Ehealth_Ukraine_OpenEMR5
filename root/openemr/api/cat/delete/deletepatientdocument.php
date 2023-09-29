<?php
/**
 * api/deletepatientdocument.php delete patient document.
 *
 * API is allowed to delete patient documents. documents can be
 * labreports, id card pic etc.
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
$xml_string = "<PatientImage>";

$token = $_GET['token'];
$document_id = $_GET['documentId'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    
    $acl_allow = acl_check('admin', 'super', $user);
    if ($acl_allow) {
        $strQuery1 = "SELECT `url`
                    FROM `documents`
                    WHERE `id` = ? ";
        $result1 = sqlQuery($strQuery1, array($document_id));
        $file_path = $result1['url'];
        
        unlink($file_path);

        $strQuery = "DELETE FROM documents WHERE id = ? ";
		$result = sqlStatement($strQuery, array($document_id));

        $strQuery2 = "DELETE FROM categories_to_documents WHERE document_id = ?";
		$result = sqlStatement($strQuery2, array($document_id));

        if ($result) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Pateient document has been deleted</reason>";
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

$xml_string .= "</PatientImage>";
echo $xml_string;
?>