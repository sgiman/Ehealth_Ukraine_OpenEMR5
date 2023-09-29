<?php
/**
 * api/getappointmentcategories.php to retrieve appointment categories.
 *
 * API retrieve all appointment categories.
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

$token = $_GET['token'];
$catType = isset($_GET['catType']) ? $_GET['catType'] : "0";

$xml_string = "";
$xml_string .= "<Appointmentscategories>\n";

if ($userId = validateToken($token)) {

    $username = getUsername($userId);

    $acl_allow = acl_check('patients', 'appt', $username);
    if ($acl_allow) {
        $strQuery = "SELECT pc_catid,pc_catname
                                FROM `openemr_postcalendar_categories`
                                WHERE pc_cattype = ? ";
        $result = sqlStatement($strQuery,array($catType));
        
        if ($result->_numOfRows > 0) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Appointment categories records has been fetched</reason>\n";
            $counter = 0;

            while($res = sqlFetchArray($result)){
                $xml_string .= "<Appointmentcategory>\n";

                foreach ($res as $fieldname => $fieldvalue) {
                    $rowvalue = xmlsafestring($fieldvalue);
                    $xml_string .= "<$fieldname>$rowvalue</$fieldname>\n";
                }

                $xml_string .= "</Appointmentcategory>\n";
                $counter++;
            }
        } else {
            $xml_string .= "<status>-1</status>\n";
            $xml_string .= "<reason>ERROR: Sorry, there was an error processing your data. Please re-submit the information again.</reason>\n";
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>\n";
    $xml_string .= "<reason>Invalid Token</reason>\n";
}

$xml_string .= "</Appointmentscategories>\n";
echo $xml_string;
?>