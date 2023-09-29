<?php
/**
 * api/adduser.php Register user.
 *
 * API is allowed to register new user.
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
$xml_array = array();

$firstname = $_GET['firstname'];
$lastname = $_GET['lastname'];
$phone = $_GET['phone'];
$email = $_GET['email'];
$username = $_GET['username'];
$password = $_GET['password'];
// $greetings = isset($_GET['greetings']) ? $_GET['greetings'] : "";
$title = !empty($_GET['title']) ? $_GET['title'] : 'Doctor';
$device_token = isset($_REQUEST['device_token']) ? $_REQUEST['device_token'] : '';

$pin = !empty($_GET['pin']) ? $_GET['pin'] : substr(uniqid(rand()), 0, 4);


$createDate = date('Y-m-d');


sqlStatement("lock tables gacl_aro read");
$result5 = sqlQuery("select max(id)+1 as id from gacl_aro");
$gacl_aro_id = $result5['id'];
sqlStatement("unlock tables");
$secretKey = getUniqueSecretkey();



$strQueryUsers = "SELECT * FROM users WHERE username LIKE '{$username}'";
$resultUsers = sqlQuery($strQueryUsers);

if ($result || $resultUsers) {
    $xml_array['status'] = -2;
    $xml_array['reason'] = 'Username is not available';
} else {

    $ip = $_SERVER['REMOTE_ADDR'];

    $url = "http://api.ipinfodb.com/v3/ip-city/?key=53e1dbadb9c701a660a8914aeacca2bd640b56758659f3b1940de385fa97ca94&ip={$ip}&format=json";
    $responce = file_get_contents($url);
    $responce_array = json_decode($responce);
    
    $pin1 = sha1($pin);
    $password1 = sha1($password);
    $strQuery1 = "INSERT INTO `users`(`username`, `password`, `fname`, `lname`,  `phone`, `email`, `authorized`,`calendar`, `app_pin`, `create_date`, `secret_key`,  `title`, `ip_address`, `country_code`, `country_name`, `state`, `city`, `zip`, `latidute`, `longitude`, `time_zone`)
                            VALUES ('".add_escape_custom($username)."','".add_escape_custom($password1)."','".add_escape_custom($firstname)."','".add_escape_custom($lastname)."','".add_escape_custom($phone)."','".add_escape_custom($email)."',1,1, '" . add_escape_custom($pin1) . "', '" . $createDate . "','" . $secretKey . "','".add_escape_custom($title)."','".add_escape_custom($responce_array->ipAddress)."','".add_escape_custom($responce_array->countryCode)."','".add_escape_custom($responce_array->countryName)."','".add_escape_custom($responce_array->regionName)."','".add_escape_custom($responce_array->cityName)."','".add_escape_custom($responce_array->zipCode)."','".add_escape_custom($responce_array->latitude)."','".add_escape_custom($responce_array->longitude)."','".add_escape_custom($responce_array->timeZone)."')";
   
    $result1 = sqlInsert($strQuery1);
    $last_user_id = $result1;
    
    if (getVersion()) {
        require_once("$srcdir/authentication/common_operations.php");
        initializePassword($username, $last_user_id, $password);
        purgeCompatabilityPassword($username, $last_user_id);
    } 

    $strQuery2 = "INSERT INTO `gacl_aro`(`id`, `section_value`, `value`, `order_value`, `name`) 
                    VALUES ('{$gacl_aro_id}', 'users', '".add_escape_custom($username)."', '10','" . add_escape_custom($firstname . " " . $lastname) . "')";


    $result2 = sqlInsert($strQuery2);


    $strQuery3 = "INSERT INTO `groups`(`name`, `user`) 
                        VALUES ('Default', '" . add_escape_custom($username) . "')";
    $result3 = sqlInsert($strQuery3);


    $strQuery4 = "INSERT INTO `gacl_groups_aro_map`(`group_id`, `aro_id`) 
                    VALUES('11', '" . add_escape_custom($gacl_aro_id) . "')";
    $result4 = sqlInsert($strQuery4);


    $token = createToken($last_user_id, true, $device_token);

    
    if ($result1 && $result2 && $result3 && $result4 && $token) {
        $mail = new PHPMailer();
        $mail->IsSendmail();
        $body = "<html><body>
                            <table>
                                    <tr>
                                            <td>You have signed up for a MedMaster account</td>
                                    </tr>
                                    <tr>
                                            <td>Here are the details of your account: </td>
                                    </tr>
                                    <tr>
                                            <td>Username: " . $username . "</td>
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
        $mail->Subject = "MedMaster Account Signup";
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->MsgHTML($body);

        if (!$mail->Send()) {
            $xml_array['email'] = $mail->ErrorInfo;
        } else {
            $xml_array['email'] = "Email send successfully";
        }

        $xml_array['status'] = 0;
        $xml_array['token'] = $token;
        $xml_array['provider_id'] = $last_user_id;
        $xml_array['firstname'] = $firstname;
        $xml_array['lastname'] = $lastname;
        $xml_array['reason'] = 'User registered successfully';
    } else {
        $xml_array['status'] = -1;
        $xml_array['reason'] = 'Could not register user';
    }
}

$xml = ArrayToXML::toXml($xml_array, 'MedMasterUser');
echo $xml;
?>