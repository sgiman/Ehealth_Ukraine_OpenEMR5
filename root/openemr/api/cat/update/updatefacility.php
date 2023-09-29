<?php

/**
 * api/updatefacility.php Update facility.
 *
 * API is allowed to update facility informations.
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
$xml_string = "<facility>";

$token = $_GET['token'];

$facilityId = $_GET['id'];
$name = $_GET['name'];
$phone = $_GET['phone'];
$fax = $_GET['fax'];
$street = $_GET['street'];
$city = $_GET['city'];
$state = $_GET['state'];
$postal_code = $_GET['postal_code'];
$country_code = $_GET['country_code'];
$federal_ein = $_GET['federal_ein'];
$service_location = $_GET['service_location'];
$billing_location = $_GET['billing_location'];
$accepts_assignment = $_GET['accepts_assignment'];
$pos_code = $_GET['pos_code'];
$x12_sender_id = $_GET['x12_sender_id'];
$attn = $_GET['attn'];
$domain_identifier = $_GET['domain_identifier'];
$facility_npi = $_GET['facility_npi'];
$tax_id_type = $_GET['tax_id_type'];
$color = $_GET['color'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('admin', 'super', $user);

    if ($acl_allow) {

        $strQuery = 'UPDATE facility SET ';
        $strQuery .= 'name  = "' . add_escape_custom($name) . '",';
        $strQuery .= 'phone = "' . add_escape_custom($phone) . '",';
        $strQuery .= 'fax = "' . add_escape_custom($fax) . '",';
        $strQuery .= 'street = "' . add_escape_custom($street) . '",';
        $strQuery .= 'city = "' . add_escape_custom($city) . '",';
        $strQuery .= 'state = "' . add_escape_custom($state) . '",';
        $strQuery .= 'postal_code = "' . add_escape_custom($postal_code) . '",';
        $strQuery .= 'country_code = "' . add_escape_custom($country_code) . '",';
        $strQuery .= 'federal_ein = "' . add_escape_custom($federal_ein) . '",';
        $strQuery .= 'service_location = "' . add_escape_custom($service_location) . '",';
        $strQuery .= 'billing_location = "' . add_escape_custom($billing_location) . '",';
        $strQuery .= 'accepts_assignment = "' . add_escape_custom($accepts_assignment) . '",';
        $strQuery .= 'pos_code = "' . add_escape_custom($pos_code) . '",';
        $strQuery .= 'x12_sender_id = "' . add_escape_custom($x12_sender_id) . '",';
        $strQuery .= 'attn = "' . add_escape_custom($attn) . '",';
        $strQuery .= 'domain_identifier = "' . add_escape_custom($domain_identifier) . '",';
        $strQuery .= 'facility_npi = "' . add_escape_custom($facility_npi) . '",';
        $strQuery .= 'tax_id_type = "' . add_escape_custom($tax_id_type) . '",';
        $strQuery .= 'color = "' . add_escape_custom($color) . '",';
        $strQuery .= ' WHERE id = ?';

        $result = sqlStatement($strQuery, array($facilityId));

        if ($result !== FALSE) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Facility has been updated</reason>";
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

$xml_string .= "</facility>";
echo $xml_string;
?>