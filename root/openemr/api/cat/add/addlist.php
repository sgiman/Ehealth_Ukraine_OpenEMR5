<?php
/**
 * api/addlist.php add patient's problem.
 *
 * Api add's patient problem such as allergies,medical problem, Medicaions,
 * Dental, Surgery
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
$date = 'NOW()';
$type = $_GET['type']; // medical_problem
$visit_id = $_GET['visit_id'];

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
$modifydate = 'NOW()';
$pid = $_GET['pid'];
$user = '';
$groupname = isset($_GET['groupname']) ? $_GET['groupname'] : 'Default';

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

    if ($acl_allow) {
        setListTouch($pid, $type);

        $strQuery = "INSERT INTO `lists`(`date`, `type`, `title`, `begdate`, `enddate`, `returndate`, `occurrence`, `classification`, `referredby`, `extrainfo`, `diagnosis`, `activity`, `comments`, `pid`, `user`, `groupname`, `outcome`, `destination`, `reinjury_id`, `injury_part`, `injury_type`, `injury_grade`, `reaction`, `external_allergyid`, `erx_source`, `modifydate`, `erx_uploaded`) 
                                        VALUES (
                                                '" . add_escape_custom($date) . "',
                                                '" . add_escape_custom($type) . "',
                                                '" . add_escape_custom($title) . "',
                                                '" . add_escape_custom($begdate) . "',
                                                '" . add_escape_custom($enddate) . "',
                                                '" . add_escape_custom($returndate) . "',
                                                '" . add_escape_custom($occurrence) . "',
                                                '" . add_escape_custom($classification) . "',
                                                '" . add_escape_custom($referredby) . "',
                                                '" . add_escape_custom($extrainfo) . "',
                                                '" . add_escape_custom($diagnosis) . "',
                                                '" . add_escape_custom($activity) . "',
                                                '" . add_escape_custom($comments) . "',
                                                '" . add_escape_custom($pid) . "',
                                                '" . add_escape_custom($user) . "',
                                                '" . add_escape_custom($groupname) . "',
                                                '" . add_escape_custom($outcome) . "',
                                                '" . add_escape_custom($destination) . "',
                                                '" . add_escape_custom($reinjury_id) . "',
                                                '" . add_escape_custom($injury_part) . "',
                                                '" . add_escape_custom($injury_type) . "',
                                                '" . add_escape_custom($injury_grade) . "',
                                                '" . add_escape_custom($reaction) . "',
                                                '" . add_escape_custom($external_allergyid) . "',
                                                '" . add_escape_custom($erx_source) . "',
                                                '" . $modifydate . "',
                                                '" . add_escape_custom($erx_uploaded) . "')";
        $result = sqlInsert($strQuery);

        $last_inseted_id = $result;

        $result1 = 1;
        if ($visit_id) {
            $sql_list_query = "INSERT INTO `issue_encounter`(`pid`, `list_id`, `encounter`, `resolved`) 
                            VALUES (
                                '".add_escape_custom($pid)."',
                                '".add_escape_custom($last_inseted_id)."',
                                '".add_escape_custom($visit_id)."',
                                0)";
            $result1 = sqlInsert($sql_list_query);
        }
        if ($result && $result1) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The {$type} has been added</reason>";
            $xml_string .= "<id>{$last_inseted_id}</id>";
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