<?php
/**
 * api/forgetpassword.php to Retrieve user password.
 *
 * API send an email to user which containing his username, password and pin.
 * 
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */
eader("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;
require_once 'classes.php';

$xml_string = "";
$xml_string = "<forgetpassword>";

$email = $_GET['email'];


$strQuery = "SELECT id,username, password, fname, lname FROM users WHERE email= ?";
$result = sqlQuery($strQuery,array($email));

if ($result) {
    $xml_string .= "<status>0</status>";
    
    $newPwd = rand_string(10);    
    $pin = substr(uniqid(rand()), 0, 4);
    $pin1 = sha1($pin);
        
    if (getVersion()) {
        require_once("$srcdir/authentication/password_hashing.php");        
        
        $salt = password_salt();
        $password = password_hash($newPwd,$salt);
        $result1 = sqlStatement("UPDATE users_secure SET password='".$password."', salt='".$salt."' WHERE id = {$result["id"]}");
        
        $strQuery1 = "UPDATE `users` SET `app_pin`='" . add_escape_custom($pin1) . "' WHERE email = ?";
        $result1 = sqlStatement($strQuery1,array($email));
    } else {        
        $password1 = sha1($newPwd);
        
        $strQuery1 = "UPDATE `users` SET `password`='" . add_escape_custom($password1) . "', `app_pin`='" . add_escape_custom($pin1) . "' WHERE email = ?";
        $result1 = sqlStatement($strQuery1,array($email));
    }
    
    if ($result1 !== FALSE) {
        
        $mail = new PHPMailer();
        $mail->IsSendmail();
        $body = "<html><body>
						<table>
							<tr>
								<td>Your Password has been changed your new Username and Password are</td>
							</tr>
							<tr>
								<td>Here are the details of your account: </td>
							</tr>
							<tr>
								<td>Username: " . $result['username'] . "</td>
							</tr>
							<tr>
								<td>Password: " . $newPwd . "</td>
							</tr>
							<tr>
								<td>Pin: " . $pin . "</td>
							</tr>
							<tr>
								<td>Thanks, <br />MedMaster Team</td>
							</tr>
						</table>
					</body></html>";
        $body = eregi_replace("[\]", '', $body);
        $mail->AddReplyTo("no-reply@mastermobileproducts.com", "MedMasterPro");
        $mail->SetFrom('no-reply@mastermobileproducts.com', 'MedMasterPro');
        $mail->AddAddress($email, $email);
        $mail->Subject = "Forgot Password Request at MedMaster";
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->MsgHTML($body);

        if (!$mail->Send()) {
            $xml_string .= "<error>" . $mail->ErrorInfo . "</error>";
        } else {
            $xml_string .= "<reason>Email containing your username and password has been sent to your email address!</reason>";
        }
    }
} else {
    $xml_string .= "<status>-1</status>";
    $xml_string .= "<reason>Email Address not found in our records. Please contact support.</reason>";
}


$xml_string .= "</forgetpassword>";
echo $xml_string;
?>