<?php
/**
 * api/updateinsurancecompany.php Update insurance company .
 *
 * API is allowed to update insurance company details.
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
require_once("$srcdir/classes/InsuranceCompany.class.php");

$xml_string = "";
$xml_string .= "<insurancecompany>";

$token = $_GET['token'];
$id = $_GET['id'];
$name = $_GET['name'];
$attn = $_GET['attn'];
$address_line1 = $_GET['address_line1'];
$address_line2 = $_GET['address_line1'];
$phone = $_GET['phone'];
$city = $_GET['city'];
$state = $_GET['state'];
$zip = $_GET['zip'];
$cms_id = $_GET['cms_id'];
$freeb_type = $_GET['freeb_type'];
$x12_receiver_id = $_GET['x12_receiver_id'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('admin', 'super', $user);

    if ($acl_allow) {
        $insuranceCom = new InsuranceCompany($id);

        $insuranceCom->set_name($name);
        $insuranceCom->set_attn($attn);
        $insuranceCom->set_address_line1($address_line1);
        $insuranceCom->set_address_line2($address_line1);
        $insuranceCom->set_phone($phone);
        $insuranceCom->set_city($city);
        $insuranceCom->set_state($state);
        $insuranceCom->set_zip($zip);
        $insuranceCom->set_cms_id($cms_id);
        $insuranceCom->set_freeb_type($freeb_type);
        $insuranceCom->set_x12_receiver_id($x12_receiver_id);

        $insuranceCom->persist();

        $xml_string .= "<status>0</status>\n";
        $xml_string .= "<reason>The Insurance Company hasbeen Updated</reason>\n";
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</insurancecompany>";
echo $xml_string;
?>