<?php
/**
 * api/updatevisit.php Update Patient visit.
 *
 * API is allowed to update patient visit details.
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

$xml_string = "";
$xml_string .= "<PatientVisit>";

$token = $_GET['token'];

$patientId = $_GET['patientId'];
$reason = $_GET['reason'];
$facility = $_GET['facility'];
$facility_id = $_GET['facility_id'];
$encounter = $_GET['encounter'];
$dateService = $_GET['dateService'];
$sensitivity = $_GET['sensitivity'];
$pc_catid = $_GET['pc_catid'];
$billing_facility = $_GET['billing_facility'];
$list = $_GET['list'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('encounters', 'auth_a', $user);

    if ($acl_allow) {
        $strQuery = "UPDATE form_encounter 
                    SET date = '" . date('Y-m-d H:i:s') . "', 
                        reason = '" . add_escape_custom($reason) . "', 
                        facility = '" . add_escape_custom($facility) . "', 
                        facility_id = " . add_escape_custom($facility_id) . ", 
                        onset_date = '" . add_escape_custom($dateService) . "', 
                        sensitivity = '" . add_escape_custom($sensitivity) . "', 
                        billing_facility  = " . add_escape_custom($billing_facility) . ",
                        pc_catid = '" . add_escape_custom($pc_catid) . "'    
                    WHERE pid = ? " . " AND encounter = ?";
        $result = sqlStatement($strQuery, array($patientId, $encounter));

        $list_res = 1;
        if (!empty($list)) {

            $del_list_query = "DELETE FROM `issue_encounter` WHERE `pid` = ? AND `encounter` = ?";
            $list_res = sqlStatement($del_list_query, array($patientId, $encounter));
            $list_array = explode(',', $list);


            foreach ($list_array as $list_item) {
                $sql_list_query = "INSERT INTO `issue_encounter`(`pid`, `list_id`, `encounter`, `resolved`) 
                            VALUES (".add_escape_custom($patientId).",".add_escape_custom($list_item).",".add_escape_custom($encounter).",0)";
                $result1 = sqlStatement($sql_list_query);
                if (!$list_res)
                    $list_res = 0;
            }
        }
        if ($result !== FALSE || $list_res !== FALSE) {

            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>Patient visit updated successfully</reason>";
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>Couldn't update record</reason>";
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</PatientVisit>";
echo $xml_string;
?>
