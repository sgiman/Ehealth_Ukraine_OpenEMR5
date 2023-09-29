<?php

/**
 * api/getstatsoptions.php Get stats options.
 *
 * API is allowed to get stats option (language, race and ethnicity).
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

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('lists', 'default', $user);
    if ($acl_allow) {

        $strQuery = "SELECT option_id, title FROM list_options WHERE list_id  = 'drug_units'";
        $result = sqlStatement($strQuery);
        $numRows = sqlNumRows($result);

        $strQuery1 = "SELECT option_id, title FROM list_options WHERE list_id  = 'drug_form'";
        $result1 = sqlStatement($strQuery1);
        $numRows1 = sqlNumRows($result1);

        $strQuery2 = "SELECT option_id, title FROM list_options WHERE list_id  = 'drug_route'";
        $result2 = sqlStatement($strQuery2);
        $numRows2 = sqlNumRows($result2);

        $strQuery3 = "SELECT option_id, title FROM list_options WHERE list_id  = 'drug_interval'";
        $result3 = sqlStatement($strQuery3);
        $numRows3 = sqlNumRows($result3);


        if ($numRows > 0 || $numRows1 > 0 || $numRows2 > 0 || $numRows3 > 0) {

            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Options has been fetched</reason>";

            $xml_string .= "<drug_units>\n";
            while ($res = sqlFetchArray($result)) {
                $xml_string .= "<item>";
                foreach ($res as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);

                    $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                }
                $xml_string .= "</item>";
            }
            $xml_string .= "</drug_units>";

            $xml_string .= "<drug_form>\n";
            while ($res1 = sqlFetchArray($result1)) {
                $xml_string .= "<item>";
                foreach ($res1 as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);

                    $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                }
                $xml_string .= "</item>";
            }
            $xml_string .= "</drug_form>";

            $xml_string .= "<drug_route>\n";
            while ($res2 = sqlFetchArray($result2)) {
                $xml_string .= "<item>";
                foreach ($res2 as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);

                    $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                }
                $xml_string .= "</item>";
            }
            $xml_string .= "</drug_route>";


            $xml_string .= "<drug_interval>\n";
            while ($res3 = sqlFetchArray($result3)) {
                $xml_string .= "<item>";
                foreach ($res3 as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);
                    $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                }
                $xml_string .= "</item>";
            }
            $xml_string .= "</drug_interval>";
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
