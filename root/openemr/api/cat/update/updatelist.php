<?php
/**
 * api/updatelist.php Update list.
 *
 * API is allowed to update list of medical_problem, medication, Allergy, 
 * Surgery and Dental.
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

$token = $_GET['token'];

$id = $_GET['id'];

$title = isset($_GET['title']) ? $_GET['title'] : '';
$begdate = isset($_GET['begdate']) ? $_GET['begdate'] : '';
$enddate = isset($_GET['enddate']) ? $_GET['enddate'] : '';
$returndate = isset($_GET['returndate']) ? $_GET['returndate'] : '';
$occurrence = isset($_GET['occurrence']) ? $_GET['occurrence'] : '';
$classification = isset($_GET['classification']) ? $_GET['classification'] : '0';
$referredby = isset($_GET['referredby']) ? $_GET['referredby'] : '';
$extrainfo = isset($_GET['extrainfo']) ? $_GET['extrainfo'] : '';
$diagnosis = isset($_GET['diagnosis']) ? $_GET['diagnosis'] : '';
$activity = isset($_GET['activity']) ? $_GET['activity'] : '1';
$comments = isset($_GET['comments']) ? $_GET['comments'] : '';
$pid = add_escape_custom($_GET['pid']);
$user = '';
$groupname = isset($_GET['groupname']) ? $_GET['groupname'] : '';

$outcome = $_GET['outcome'];
$destination = $_GET['destination'];

$reinjury_id = isset($_GET['reinjury_id']) ? $_GET['reinjury_id'] : '0';
$injury_part = isset($_GET['injury_part']) ? $_GET['injury_part'] : '';
$injury_type = isset($_GET['injury_type']) ? $_GET['injury_type'] : '';
$injury_grade = isset($_GET['injury_grade']) ? $_GET['injury_grade'] : '';
$reaction = isset($_GET['reaction']) ? $_GET['reaction'] : '';
$external_allergyid = isset($_GET['external_allergyid']) ? $_GET['external_allergyid'] : '';
$erx_source = isset($_GET['erx_source']) ? $_GET['erx_source'] : 0;
$erx_uploaded = isset($_GET['erx_uploaded']) ? $_GET['erx_uploaded'] : 0;

$xml_string = "";
$xml_string = "<list>";

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('patients', 'med', $user);

    $_SESSION['authUser'] = $user;
    $_SESSION['authGroup'] = $site;
    $_SESSION['pid'] = $pid;

    if ($acl_allow) {
        $strQuery = "UPDATE `lists` SET 
                                `title`='" . add_escape_custom($title) . "',
                                `begdate`='" . add_escape_custom($begdate) . "',
                                `enddate`='" . add_escape_custom($enddate) . "',
                                `returndate`='" . add_escape_custom($returndate) . "',
                                `occurrence`='" . add_escape_custom($occurrence) . "',
                                `classification`='" . add_escape_custom($classification) . "',
                                `referredby`='" . add_escape_custom($classification) . "',
                                `extrainfo`='" . add_escape_custom($extrainfo) . "',
                                `diagnosis`='" . add_escape_custom($diagnosis) . "',
                                `activity`='" . add_escape_custom($activity) . "',
                                `comments`='" . add_escape_custom($comments) . "',
                                `user`='" . add_escape_custom($user) . "',
                                `groupname`='" . add_escape_custom($groupname) . "',
                                `outcome`='" . add_escape_custom($outcome) . "',
                                `destination`='" . add_escape_custom($destination) . "',
                                `reinjury_id`='" . add_escape_custom($reinjury_id) . "',
                                `injury_part`='" . add_escape_custom($injury_part) . "',
                                `injury_type`='" . add_escape_custom($injury_type) . "',
                                `injury_grade`='" . add_escape_custom($injury_grade) . "',
                                `reaction`='" . add_escape_custom($reaction) . "',
                                `external_allergyid`='" . add_escape_custom($external_allergyid) . "',
                                `erx_source`='" . add_escape_custom($erx_source) . "',
                                `erx_uploaded`='" . add_escape_custom($error_string) . "' 
                                 WHERE id = ? ";

        $result = sqlStatement($strQuery, array($id));

        if ($result !== FALSE) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The {$type} has been update</reason>";
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

$xml_string .= "</list>";
echo $xml_string;
?>