<?php
/**
 * api/addsoap.php add patient's Subjective Objective Assessment and Plan.
 *
 * Api add's patient SOAP against particular visit.
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
$xml_string = "<soap>";

$token = $_GET['token'];
$patientId = $_GET['patientId'];
$visit_id = $_GET['visit_id'];

$groupname = isset($_GET['groupname']) ? $_GET['groupname'] : NULL;
$subjective = $_GET['subjective'];
$objective = $_GET['objective'];
$assessment = $_GET ['assessment'];
$plan = $_GET['plan'];
$authorized = isset($_GET['authorized']) ? $_GET['authorized'] : 0;
$activity = isset($_GET['activity']) ? $_GET['activity'] : 1;

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('encounters', 'auth_a', $user);

    if ($acl_allow) {
        $strQuery = "INSERT INTO form_soap 
            (pid, user, date, groupname, authorized, activity, subjective, objective, assessment,  plan) 
            VALUES (" . $patientId . ", '" . add_escape_custom($user) . "', '" . date('Y-m-d H:i:s') . "','" . add_escape_custom($groupname) . "', '" . add_escape_custom($authorized) . "','" . add_escape_custom($activity) . "',  '" . add_escape_custom($subjective) . "' , '" . add_escape_custom($objective) . "' , '" . add_escape_custom($assessment) . "', '" . add_escape_custom($plan) . "')";

        $result = sqlInsert($strQuery);
        $last_inserted_id = $result;

        if ($result) {
            addForm($visit_id, $form_name = 'SOAP', $last_inserted_id, $formdir = 'soap', $patientId, $authorized = "1", $date = "NOW()", $user, $group = "Default");

            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Soap has been added</reason>";
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

$xml_string .= "</soap>";
echo $xml_string;
?>