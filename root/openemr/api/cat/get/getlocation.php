<?php
/**
 * api/getlocation.php retrieve all locations.
 *
 * API returns all locations.
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
$xml_string = "<locations>";

$token = $_GET['token'];
$facilityId = $_GET['facility_id'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);

    $acl_allow = acl_check('admin', 'super', $user);
    if ($acl_allow) {
        $strQuery = "SELECT id, name FROM facility WHERE primary_business_entity= ?";
        $result = sqlStatement($strQuery,array($facilityId));

        if ($result->_numOfRows > 0) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Locations Records has been fetched</reason>";

            while($res = sqlFetchArray($result)){
                $xml_string .= "<location>\n";

                foreach ($res as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);
                    $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                }

                $xml_string .= "</location>\n";
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

$xml_string .= "</locations>";
echo $xml_string;
?>