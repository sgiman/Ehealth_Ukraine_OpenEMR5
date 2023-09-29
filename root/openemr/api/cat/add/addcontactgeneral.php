<?php

/**
 * api/addcontactgeneral.php Add new contact for user.
 *
 * Api add's new contacts for user.
 * 
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */

header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;
require_once ("classes.php");

$xml_string = "";
$xml_string = "<contact>";

$token = $_GET['token'];
$title = $_GET['title'];
$firstname = $_GET['firstname'];
$lastname = $_GET['lastname'];
$middlename = $_GET['middlename'];
$upin = $_GET['upin'];
$npi = $_GET['npi'];
$taxonomy = $_GET['taxonomy'];
$specialty = $_GET['specialty'];
$organization = $_GET['organization'];
$valedictory = $_GET['valedictory'];
$assistant = $_GET['assistant'];
$email = $_GET['email'];
$url = $_GET['url'];
$street = $_GET['street'];
$streetb = $_GET['streetb'];
$city = $_GET['city'];
$state = $_GET['state'];
$zip = $_GET['zip'];
$home_phone = $_GET['home_phone'];
$work_phone1 = $_GET['work_phone1'];
$work_phone2 = $_GET['work_phone2'];
$mobile = $_GET['mobile'];
$fax = $_GET['fax'];
$notes = $_GET['notes'];
$image_data = $_GET['imageData'];
$image_title = $_GET['imageTitle'];
$image_name = '';

if ($userId = validateToken($token)) {
    
    $user = getUsername($userId);
    $acl_allow = acl_check('admin', 'users', $user);

    if ($acl_allow) {
        
        if ($firstname == '' || $lastname == '' || $email == '') {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>Some fields are empty</reason>";
        } else {
            if ($image_data) {

               
                $path = $sitesDir . "{$site}/documents/userdata";

                if (!file_exists($path)) {
                    mkdir($path);
                    mkdir($path . "/contactimages");
                } elseif (!file_exists($path . "/contactimages")) {
                    mkdir($path . "/contactimages");
                }

                $image_name = date('YmdHis') . ".png";
                file_put_contents($path . "/contactimages/" . $image_name, base64_decode($image_data));

                $notes_url = $sitesUrl . "{$site}/documents/userdata/contactimages/" . $image_name;
            }
            
            $strQuery = "INSERT INTO users (username, password, authorized, info, source, title, fname, lname, mname,  upin, see_auth, active, npi, taxonomy, specialty, organization, valedictory, assistant, email, url, street, streetb, city, state, zip, phone, phonew1, phonew2, phonecell, fax, notes, contact_image ) 
                            VALUES ('',
                                    '',
                                    0,
                                    '',
                                    NULL,
                                    '" . add_escape_custom($title) . "',
                                    '" . add_escape_custom($firstname) . "',
                                    '" . add_escape_custom($lastname) . "',
                                    '" . add_escape_custom($middlename) . "',
                                    '" . add_escape_custom($upin) . "',
                                    0,
                                    1,
                                    '" . add_escape_custom($npi) . "',
                                    '" . add_escape_custom($taxonomy) . "',
                                    '" . add_escape_custom($specialty) . "',
                                    '" . add_escape_custom($organization) . "',
                                    '" . add_escape_custom($valedictory) . "',
                                    '" . add_escape_custom($assistant) . "',
                                    '" . add_escape_custom($email) . "',
                                    '" . add_escape_custom($url) . "',
                                    '" . add_escape_custom($street) . "',
                                    '" . add_escape_custom($streetb) . "',
                                    '" . add_escape_custom($city) . "',
                                    '" . add_escape_custom($state) . "',
                                    '" . add_escape_custom($zip) . "',
                                    '" . add_escape_custom($home_phone) . "',
                                    '" . add_escape_custom($work_phone1) . "',
                                    '" . add_escape_custom($work_phone2) . "',
                                    '" . add_escape_custom($mobile) . "',
                                    '" . add_escape_custom($fax) . "',
                                    '" . add_escape_custom($notes) . "',
                                    '" . add_escape_custom($image_name) . "'
                                    )";
            $result = sqlInsert($strQuery);

            if ($result) {

                $xml_string .= "<status>0</status>";
                $xml_string .= "<reason>The Contact has been added</reason>";
            } else {
                $xml_string .= "<status>-1</status>";
                $xml_string .= "<reason>ERROR: Sorry, there was an error processing your data. Please re-submit the information again.</reason>";
            }
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</contact>";
echo $xml_string;
?>