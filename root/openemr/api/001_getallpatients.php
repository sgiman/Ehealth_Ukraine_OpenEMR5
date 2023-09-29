<?php

/**
 * api/getallpatients.php retrieve all patients.
 *
 * API returns all patients with the profile image url.
 * 
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API OEMR version 1.4
 * Modified by sgiman, 2016
 */
header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;
require_once 'classes.php';

//$token = $_POST['token'];
$token = $_GET['token'];

$xml_string = "";
$xml_string .= "<PatientList>\n";

if ($userId = validateToken($token)) {

    $username = getUsername($userId);

    $acl_allow = acl_check('patients', 'demo', $username);
    if ($acl_allow) {

        $strQuery = "SELECT p.id, p.pid, p.fname as firstname, p.lname as lastname, p.mname as middlename, p.phone_contact as phone, phone_home, p.phone_biz, p.street, p.city, p.state, p.country_code as country, p.postal_code, p.email, p.status, p.dob, p.sex as gender, p.language, p.race, p.family_size, p.ss, p.providerID, p.pharmacy_id, u.fname, u.lname, ph.name
        FROM patient_data p, users u, pharmacies ph WHERE p.providerID = u.id AND p.pharmacy_id = ph.id
        ";
        
        $result = sqlStatement($strQuery);
        $numRows = sqlNumRows($result);
        
        if ($numRows > 0) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Patient list Record has been fetched</reason>";
            $counter = 0;

            $cat_title = 'Patient Photograph';
            $strQuery2 = "SELECT id from `categories` WHERE name LIKE '" . add_escape_custom($cat_title) . "'";
            $result3 = sqlQuery($strQuery2);
            $cat_id = $result3['id'];

            while ($res = sqlFetchArray($result)) {
                $xml_string .= "<Patient>\n";

                $p_id = 0;
                foreach ($res as $fieldname => $fieldvalue) {
                    $rowvalue = xmlsafestring($fieldvalue);
                    if ($fieldname == "pid")
                        $p_id = $fieldvalue;
                    $xml_string .= "<$fieldname>$rowvalue</$fieldname>\n";
                }


                $strQuery1 = "SELECT d.date,d.size,d.url,d.docdate,d.mimetype,c2d.category_id
                                FROM `documents` AS d
                                INNER JOIN `categories_to_documents` AS c2d ON d.id = c2d.document_id
                                WHERE foreign_id = ?
                                AND category_id = ?
                                ORDER BY category_id, d.date DESC 
                                LIMIT 1";

                //$result1 = sqlQuery($strQuery1, array($p_id, $cat_id));
                $result1 = sqlQuery($strQuery1, array($p_id, 5));

                if ($result1) {
                    $xml_string .= "<profileimage>" . getUrl($result1['url']) . "</profileimage>\n";
                } else {
                    $xml_string .= "<profileimage></profileimage>\n";
                }

                $xml_string .= "</Patient>\n";
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


$xml_string .= "</PatientList>\n";
echo $xml_string;
?>