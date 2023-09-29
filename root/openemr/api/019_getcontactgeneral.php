<?php

/**
 * api/getcontactgeneral.php retrieve user all contacts.
 * (19) Get Contact General
 *
 * API retrieve user all contacts.
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
$xml_string = "<contacts>";

$token = $_GET['token'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('admin', 'users', $user);

    if ($acl_allow) {

        $strQuery = "SELECT id, username,
                                password , authorized, info, source, u.title, fname, lname, mname, upin, see_auth, active, npi, taxonomy, specialty, organization, valedictory, assistant, email, url, street, streetb, city, state, zip, phone, phonew1, phonew2, phonecell, fax, u.notes, contact_image FROM users AS u WHERE authorized=1";


        $result = sqlStatement($strQuery);
        $numRows = sqlNumRows($result);
        if ($numRows > 0) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Contact Record has been fetched</reason>";

            while ($res = sqlFetchArray($result)) {
                $xml_string .= "<contact>\n";

                foreach ($res as $fieldName => $fieldValue) {
                    if ($fieldName == 'contact_image' && !empty($fieldValue)) {
                        $xml_string .="<image_url>{$sitesUrl}{$site}/documents/userdata/contactimages/{$fieldValue}</image_url>";
                        $xml_string .="<image_title>{$fieldValue}</image_title>";
                        //$xml_string .="<image_title>{$image_data['title']}</image_title>";
                    } else {
                        $rowValue = xmlsafestring($fieldValue);
                        $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                    }
                }
                $xml_string .= "</contact>\n";
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

$xml_string .= "</contacts>";
echo $xml_string;
?>