<?php
/**
 * api/getvisits.php Get visits.
 * (009) get visits *
 * API is allowed to get patient visits list with details.
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

$xml_string = "";
$xml_string .= "<PatientVisit>";

$token = $_GET['token'];
$patientId = $_GET['patientId'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);

    $acl_allow = acl_check('encounters', 'auth_a', $user);
    if ($acl_allow) {

        $strQuery = "SELECT fe.*,opc.pc_catname,fb.name AS billing_facility_name FROM form_encounter as fe
                                LEFT JOIN `openemr_postcalendar_categories` as opc ON opc.pc_catid = fe.pc_catid
                                LEFT JOIN `facility` as fb ON fb.id = fe.billing_facility
                                WHERE pid= ? ORDER BY id DESC";

        $result = sqlStatement($strQuery, array($patientId));

        if ($result->_numOfRows > 0) {
            $xml_string .= "<status>The Patient visit Record has been fetched (status 0)</status>";
            $xml_string .= "<reason>The Patient visit Record has been fetched</reason>";

            while ($res = sqlFetchArray($result)) {

                $xml_string .= "<Visit>\n";

                foreach ($res as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);
                    $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                }

                $sql_visits = "SELECT type,title,begdate,diagnosis
                           FROM `issue_encounter` AS ie
                           INNER JOIN `lists` AS l ON ie.list_id = l.id
                           WHERE ie.encounter = ?";

                $list_result = sqlStatement($sql_visits, array($res['encounter']));

                $xml_string .= "<Issues>";
                if ($list_result->_numOfRows > 0) {

                    while ($list_res = sqlFetchArray($list_result)) {

                        $xml_string .= "<Issue>\n";
                        foreach ($list_res as $fieldName => $fieldValue) {
                            $rowValue = xmlsafestring($fieldValue);
                            $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                        }
                        $xml_string .= "</Issue>\n";
                    }
                }
                $xml_string .= "</Issues>";

                $sql_soap = "SELECT fs.subjective,fs.objective,fs.assessment,fs.plan 
                                    FROM `forms` AS f
                                    INNER JOIN `form_soap` as fs ON fs.`id` = f.`form_id`
                                    WHERE f.encounter = ?
                                    AND f.form_name = 'SOAP'
                                    AND NOT EXISTS (select 1 from `forms` where `form_name` = f.`form_name` and `date` > f.`date` and encounter = ?)";

                $list_result = sqlQuery($sql_soap, array($res['encounter'], $res['encounter']));

                if ($list_result) {
                    foreach ($list_result as $fieldName => $fieldValue) {
                        $rowValue = xmlsafestring($fieldValue);
                        $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                    }
                } else {
                    $xml_string .= "<subjective></subjective>\n
                                <objective></objective>\n
                                <assessment></assessment>\n
                                <plan></plan>\n";
                }

                $xml_string .= "</Visit>\n";
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

$xml_string .= "</PatientVisit>";
echo $xml_string;
?>
