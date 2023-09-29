<?php

/**
 * api/sendmessage.php Send message.
 *
 * API is allowed to send message.
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

$xml_string = "<Message>";



$token = $_GET['token'];

$patientId = $_GET['patientId'];

$authorized = $_GET['authorized'] ? $_GET['authorized'] : 1;

$activity = $_GET['activity'] ? $_GET['activity'] : 1;

$title = $_GET['title'];

$newtext = $_GET['newtext'];

$assigned_to = $_GET['assigned_to'];

$message_status = $_GET['message_status'];



$message_id = isset($_GET['message_id']) && !empty($_GET['message_id']) ? $_GET['message_id'] : '';





if ($userId = validateToken($token)) {

    $user = getUsername($userId);

    $acl_allow = acl_check('patients', 'notes', $user);



    if ($acl_allow) {

        $provider_id = $userId;



        $assigned_to_array = explode(',', $assigned_to);


        // Session variable used in addBilling() function
   
        $provider = getAuthGroup($user);
        if ($authGroup = sqlQuery("select * from groups where user='$user' and name='$provider'")) {
            $_SESSION['authProvider'] = $provider;
            $_SESSION['authId'] = $userId;
            $_SESSION['authUser'] = $user;
        }

        foreach ($assigned_to_array as $assignee) {

            if ($message_status == 'Done' && !empty($message_id)) {

                updatePnoteMessageStatus($message_id, $message_status);

                $result = 1;

                break;
            } else {

                $result = addPnote($patientId, $newtext, $authorized, $activity, $title, $assignee, $datetime = '', $message_status);

                $device_token_badge = getDeviceTokenBadge($assignee, 'message');

                $badge = $device_token_badge ['badge'];

                $deviceToken = $device_token_badge ['device_token'];

                if ($deviceToken) {

                    $notification_res = notification($deviceToken, $badge, $msg_count = 0, $apt_count = 0, $message = 'New Message Notification!');
                }
            }
        }

        if ($result) {

            $xml_string .= "<status>0</status>";

            $xml_string .= "<reason>Message send successfully</reason>";

            if ($notification_res) {

                $xml_string .= "<notification>Notification({$notification_res}) Sent.</notification>";
            } else {

                $xml_string .= "<notification>Notification Failed.</notification>";
            }
        } else {

            $xml_string .= "<status>-1</status>";

            $xml_string .= "<reason>Could not send message</reason>";
        }
    } else {

        $xml_string .= "<status>-2</status>\n";

        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {

    $xml_string .= "<status>-2</status>";

    $xml_string .= "<reason>Invalid Token</reason>";
}



$xml_string .= "</Message>";

echo $xml_string;
?>