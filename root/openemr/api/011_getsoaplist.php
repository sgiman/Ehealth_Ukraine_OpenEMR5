<?php
/**
 * api/getsoaplist.php Get SOAP list.
 * (011) get soap list
 *
 * API is allowed to get list of patient SOAP (Subjective, Objective, Assessment and Plan) 
 * details.
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
$patientId = $_GET['patientId'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('encounters', 'auth_a', $user);
    if ($acl_allow) {
        $strQuery = "SELECT f.encounter as visit_id, fsoap. id,fsoap. date, subjective, objective, assessment, plan, fsoap.user
				FROM forms AS f
				INNER JOIN `form_soap` AS fsoap ON f.form_id = fsoap.id
				WHERE fsoap.pid = ?
				AND `form_name` = 'SOAP'
                                ORDER BY fsoap. date DESC";
    $result = sqlStatement($strQuery, array($patientId));
if ($result->_numOfRows > 0) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Soap Record has been fetched</reason>";

            while ($res = sqlFetchArray($result)) {
                $xml_string .= "<soap>\n";

                foreach ($res as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);
                    $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                }
                $userName = $res['user'];
                $user_query = "SELECT  `fname` ,  `lname` 
                                                    FROM  `users` 
                                                    WHERE username LIKE ? ";
                $user_result = sqlQuery($user_query, array($userName));
                $xml_string .= "<firstname>{$user_result['fname']}</firstname>\n";
                $xml_string .= "<lastname>{$user_result['lname']}</lastname>\n";

                $xml_string .= "</soap>\n";
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