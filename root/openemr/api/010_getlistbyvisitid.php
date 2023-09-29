<?php
/**
 * api/getlistbyvisitid.php retrieve all list element by visit id.
 * (010) get list by visitid
 *
 * API returns all list items related to any particular visit id.
 * 
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */
header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;
require_once('classes.php');
require_once("$srcdir/lists.inc");

$xml_string = "";
$xml_string .= "<Listitems>\n";

$token = $_GET['token'];     //  login token
$pid = $_GET['patientId'];   //  pid - patient_data (OEMR)  
$visit_id = isset($_GET['visit_id']) && !empty($_GET['visit_id']) ? $_GET['visit_id'] : '';

if ($userId = validateToken($token)) {

    $username = getUsername($userId);

    $acl_allow = acl_check('encounters', 'auth_a', $username);
    if ($acl_allow) {
        if ($visit_id) {
            $strQuery = "select l.* from lists as l
                        LEFT JOIN `issue_encounter` as i ON i.list_id = l.id
                        where l.pid= ? AND i.encounter = ?  order by l.date DESC";

            $result = sqlStatement($strQuery, array($pid, $visit_id));

            if ($result->_numOfRows > 0) {
                $xml_string .= "<status>0</status>\n";
                $xml_string .= "<reason>The Patient Record has been fetched</reason>\n";
                $count = 0;
                while ($res = sqlFetchArray($result)) {
                    $xml_string .= "<listitem>\n";

                    foreach ($res as $fieldName => $fieldValue) {
                        $rowValue = xmlsafestring($fieldValue);
                        $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                    }

                    $diagnosis_title = getDrugTitle($res['diagnosis'], $db);
                    $xml_string .= "<diagnosistitle>{$diagnosis_title}</diagnosistitle>";
                    $xml_string .= "</listitem>\n";
                }
            } else {
                $xml_string .= "<status>-1</status>\n";
                $xml_string .= "<reason>ERROR: Sorry, there was an error processing your data. Please re-submit the information again.</reason>\n";
            }
        } else {


            $strQuery = "select l.* from lists as l
                        LEFT JOIN `issue_encounter` as i ON i.list_id = l.id
                        where l.pid=?  order by l.date DESC";

            $result = sqlStatement($strQuery, array($pid));

            if ($result->_numOfRows > 0) {
                $xml_string .= "<status>0</status>\n";
                $xml_string .= "<reason>The Patient Record has been fetched</reason>\n";
                $count = 0;
                while ($res = sqlFetchArray($result)) {
                    $xml_string .= "<listitem>\n";

                    foreach ($res as $fieldName => $fieldValue) {
                        $rowValue = xmlsafestring($fieldValue);
                        $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                    }

                    $diagnosis_title = getDrugTitle($res['diagnosis'], $db);
                    $xml_string .= "<diagnosistitle>{$diagnosis_title}</diagnosistitle>";
                    $xml_string .= "</listitem>\n";
                }
            } else {
                $xml_string .= "<status>-1</status>\n";
                $xml_string .= "<reason>ERROR: Sorry, there was an error processing your data. Please re-submit the information again.</reason>\n";
            }
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>\n";
    $xml_string .= "<reason>Invalid Token</reason>\n";
}
$xml_string .= "</Listitems>\n";
echo $xml_string;
?>