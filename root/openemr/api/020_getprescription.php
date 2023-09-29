<?php

/**
 * api/getprescription.php Get prescription.
 *
 * (20) API is allowed to get patient prescription.
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
$xml_string .= "<PrescriptionList>\n";

$token = $_GET['token'];
$patientId = $_GET['patientID'];
$visit_id = isset($_GET['visit_id']) && !empty($_GET['visit_id']) ? $_GET['visit_id'] : '';

if ($userId = validateToken($token)) {

    $username = getUsername($userId);

    $acl_allow = acl_check('patients', 'med', $username);

    if ($acl_allow) {

        if ($visit_id) {
            $strQuery = "SELECT p.*,u.id AS provider_id,u.fname AS provider_fname,u.lname AS provider_lname,u.mname AS provider_mname,form, size,  per_refill,unit, route, `interval`, substitute 
                            FROM prescriptions as p
                            LEFT JOIN `users` as u ON u.id = p.provider_id
                            WHERE patient_id = ? AND encounter = ?";

            $result = sqlStatement($strQuery, array($patientId, $visit_id));

            if ($result->_numOfRows > 0) {

                $xml_string .= "<status>0</status>\n";
                $xml_string .= "<reason>The Patient Employer Record has been fetched</reason>\n";
                $data = "";

                while ($res = sqlFetchArray($result)) {
                    $data .= "<prescription>\n";

                    foreach ($res as $fieldName => $fieldValue) {
                        $rowValue = xmlsafestring($fieldValue);
                        $data .= "<$fieldName>$rowValue</$fieldName>\n";

                        if ($fieldName == 'form' && !empty($fieldValue)) {

                            $strQueryForm = "SELECT option_id, title FROM list_options WHERE list_id  = 'drug_form' AND option_id = ?";
                            $resultForm = sqlQuery($strQueryForm, array($fieldValue));
                            $data .= "<form_title>" . xmlsafestring($resultForm['title']) . "</form_title>";
                        } 
                        if ($fieldName == 'unit' && !empty($fieldValue)) {
                            $strQueryForm = "SELECT option_id, title FROM list_options WHERE list_id  = 'drug_units' AND option_id = ?";
                            $resultForm = sqlQuery($strQueryForm, array($fieldValue));
                            $data .= "<unit_title>" . xmlsafestring($resultForm['title']) . "</unit_title>";
                        }
                        if ($fieldName == 'route' && !empty($fieldValue)) {
                            $strQueryForm = "SELECT option_id, title FROM list_options WHERE list_id  = 'drug_route' AND option_id = ?";
                            $resultForm = sqlQuery($strQueryForm, array($fieldValue));
                            $data .= "<route_title>" . xmlsafestring($resultForm['title']) . "</route_title>";
                        }
                        if ($fieldName == 'interval' && !empty($fieldValue)) {
                            $strQueryForm = "SELECT option_id, title FROM list_options WHERE list_id  = 'drug_interval' AND option_id = ?";
                            $resultForm = sqlQuery($strQueryForm, array($fieldValue));
                            $data .= "<interval_title>" . xmlsafestring($resultForm['title']) . "</interval_title>";
                        }
                    }
                    $data .= "</prescription>\n";
                }
                $xml_string .= "<data>" . $data . "</data>";
            } else {
                $xml_string .= "<status>-1</status>\n";
                $xml_string .= "<reason>ERROR: Sorry, there was an error processing your data. Please re-submit the information again.</reason>\n";
            }
        } else {

            $strQuery = "SELECT p.*,u.id AS provider_id,u.fname AS provider_fname,u.lname AS provider_lname,u.mname AS provider_mname 
                            FROM prescriptions as p
                            LEFT JOIN `users` as u ON u.id = p.provider_id
                            WHERE patient_id =?";

            $result = sqlStatement($strQuery, array($patientId));
            if ($result->_numOfRows > 0) {
                $xml_string .= "<status>0</status>\n";
                $xml_string .= "<reason>The Patient Employer Record has been fetched</reason>\n";
                $data = "";
                while ($res = sqlFetchArray($result)) {
                    $data .= "<prescription>\n";
                    foreach ($res as $fieldName => $fieldValue) {
                        $rowValue = xmlsafestring($fieldValue);
                        $data .= "<$fieldName>$rowValue</$fieldName>\n";
                    }
                    $data .= "</prescription>\n";
                }
                $xml_string .= "<data>" . $data . "</data>";
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

$xml_string .= "</PrescriptionList>\n";
echo $xml_string;
?>