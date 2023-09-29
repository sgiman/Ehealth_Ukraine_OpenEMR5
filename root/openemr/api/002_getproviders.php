<?php

/**
 * api/getproviders.php Get providers.
 * (002) get providers
 * API is allowed to get list of providers.
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

//$token = $_POST['token'];
$token = $_GET['token'];

$xml_string = "";
$xml_string .= "<Providers>\n";


if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    //$acl_allow = acl_check('admin', 'super', $user);
    $acl_allow = true;
    if ($acl_allow) {
        $strQuery = "SELECT u.id, u.fname, u.lname, u.mname, u.username, u.info, u.federaltaxid, u.upin, u.facility, u.npi, u.title, u.specialty, u.email, u.email_direct, u.url, u.assistant, u.organization, u.street, u.streetb, u.city, u.state, u.zip, u.phone, u.fax, u.taxonomy, u.state_license_number, u.notes     
                                                            FROM  `users` AS u
                                                            WHERE authorized =1
                                                            AND active =1";

        $result = sqlStatement($strQuery);
        $numRows = sqlNumRows($result);
        if ($numRows > 0) {
            $xml_string .= "<status>0</status>\n";
            $xml_string .= "<reason>The Appointment Categories Record has been fetched</reason>\n";
            $counter = 0;

            while ($res = sqlFetchArray($result)) {
                $xml_string .= "<Provider>\n";

                foreach ($res as $fieldname => $fieldvalue) {
                    $rowvalue = xmlsafestring($fieldvalue);
                    $xml_string .= "<$fieldname>$rowvalue</$fieldname>\n";
                }

                $xml_string .= "</Provider>\n";
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
$xml_string .= "</Providers>\n";
echo $xml_string;
?>