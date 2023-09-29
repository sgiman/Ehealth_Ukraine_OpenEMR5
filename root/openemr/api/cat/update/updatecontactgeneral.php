<?php

/**
 * api/updatecontactgeneral.php Update contact.
 *
 * API is allowed to update general contact details.
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
$xml_string = "<contact>";

$token = $_GET['token'];
$id = $_GET['id'];
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
$image_title_old = $_GET['imageTitleOld'];
$image_title_new = $_GET['imageTitleNew'];


if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('admin', 'users', $user);

    if ($acl_allow) {


        if ($firstname == '' || $lastname == '' || $email == '') {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>Some fields are empty</reason>";
        } else {
            
            if ($image_data) {

                $imageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
                if ($_SERVER["SERVER_PORT"] != "80") {
                    $imageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
                } else {
                    $imageURL .= $_SERVER["SERVER_NAME"];
                }


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

                $strQuery2 = "SELECT * FROM `users` 
                            WHERE id = ?";
                $result2 = sqlQuery($strQuery2, array($id));
                if (!empty($result2['contact_image'])) {
                    $old_image_name = $result2['contact_image'];
                    if (file_exists($path . "/contactimages/" . $old_image_name)) {
                        unlink($path . "/contactimages/" . $old_image_name);
                    }
                }
            } 
            
            $strQuery = 'UPDATE users SET ';
            $strQuery .= ' info = "' . add_escape_custom($info) . '",';
            $strQuery .= ' source = "' . add_escape_custom($source) . '",';
            $strQuery .= ' title = "' . add_escape_custom($title) . '",';
            $strQuery .= ' fname = "' . add_escape_custom($firstname) . '",';
            $strQuery .= ' lname = "' . add_escape_custom($lastname) . '",';
            $strQuery .= ' mname = "' . add_escape_custom($middlename) . '",';
            $strQuery .= ' upin = "' . add_escape_custom($upin) . '",';
            $strQuery .= ' see_auth = "' . add_escape_custom($see_auth) . '",';
            $strQuery .= ' npi = "' . add_escape_custom($npi) . '",';
            $strQuery .= ' taxonomy = "' . add_escape_custom($taxonomy) . '",';
            $strQuery .= ' specialty = "' . add_escape_custom($specialty) . '",';
            $strQuery .= ' organization = "' . add_escape_custom($organization) . '",';
            $strQuery .= ' valedictory = "' . add_escape_custom($valedictory) . '",';
            $strQuery .= ' assistant = "' . add_escape_custom($assistant) . '",';
            $strQuery .= ' email = "' . add_escape_custom($email) . '",';
            $strQuery .= ' url = "' . add_escape_custom($url) . '",';
            $strQuery .= ' street = "' . add_escape_custom($street) . '",';
            $strQuery .= ' streetb = "' . add_escape_custom($streetb) . '",';
            $strQuery .= ' city = "' . add_escape_custom($city) . '",';
            $strQuery .= ' state = "' . add_escape_custom($state) . '",';
            $strQuery .= ' zip = "' . add_escape_custom($zip) . '",';
            $strQuery .= ' phone = "' . add_escape_custom($home_phone) . '",';
            $strQuery .= ' phonew1 = "' . add_escape_custom($work_phone1) . '",';
            $strQuery .= ' phonew2 = "' . add_escape_custom($work_phone2) . '",';
            $strQuery .= ' phonecell = "' . add_escape_custom($mobile) . '",';
            $strQuery .= ' fax = "' . add_escape_custom($fax) . '",';
            if (!empty($image_data)){
            $strQuery .= ' contact_image = "' . add_escape_custom($image_name) . '",';
            }
            $strQuery .= ' notes = "' . add_escape_custom($notes) . '"';
            $strQuery .= ' WHERE username = \'\' AND password = \'\' AND id = ?';

            $result = sqlStatement($strQuery, array($id));

            if ($result !== FALSE) {
                $xml_string .= "<status>0</status>";
                $xml_string .= "<reason>The Contact has been updated</reason>";
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