<?php
/**
 * api/resetpassword.php Reset user password.
 *
 * API is allowed to reset user password and send informations by email.
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */
header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;
require_once('classes.php');

$token = $_GET['token'];
$password = isset($_GET['password']) && !empty($_GET['password']) ? $_GET['password'] : '';
$pin = isset($_GET['pin']) && !empty($_GET['pin']) ? $_GET['pin'] : '';


$xml_string = "<reset>";

if ($userId = validateToken($token)) {
    if (empty($password) && empty($pin)) {
        $xml_string .= "<status>-1</status>";
        $xml_string .= "<reason>Please provide password/pin values.</reason>";
    } else {
    
        
        $query1 = "UPDATE `users` SET ";

     
        if (!empty($password)) {
            $new_password = sha1($password);
            $query1 .= "`password`='".add_escape_custom($new_password)."' ";

        }
        if (!empty($pin)) {
            $new_pin = sha1($pin);
            if (!empty($password)) {
                $query1 .= ",";
            }
            $query1 .= "`app_pin`='".add_escape_custom($new_pin)."' ";
        }
        $query1 .= "WHERE id = ".add_escape_custom($userId);

      
        $result1 = sqlStatement($query1);
        
        if ($result1) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>Successfully reset Password/Pin</reason>";
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>ERROR: Sorry, there was an error processing your data. Please re-submit the information again.</reason>";
        }
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}
$xml_string .= "</reset>";
echo $xml_string;
?>
