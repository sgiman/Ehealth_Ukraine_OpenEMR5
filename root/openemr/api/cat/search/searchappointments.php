<?php
/**
 * api/searchappointments.php Search appointments.
 *
 * API is allowed to get list of appointments for search appointment.
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
$appointmentDate = $_GET['appointmentDate'];

$xml_string = "";
$xml_string .= "<Appointments>\n";


if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('admin', 'super', $user);
    if ($acl_allow) {

        if (!empty($appointmentDate)) {

            $strQuery = "SELECT pd.id as pid,pd.fname, pd.lname, pd.sex as gender, ope.pc_apptstatus, ope.pc_eid, ope.pc_pid, ope.pc_title, ope.pc_hometext, ope.pc_eventDate, ope.pc_startTime, ope.pc_endTime 
                    FROM openemr_postcalendar_events as ope, patient_data as pd 
                    WHERE pd.pid=ope.pc_pid AND ope.pc_eventDate = ?";

            $result = sqlStatement($strQuery, array($appointmentDate));
            if ($result->_numOfRows > 0) {
                $xml_string .= "<status>0</status>\n";
                $xml_string .= "<reason>Success processing patient appointments records</reason>\n";
                $counter = 0;

                while ($res = sqlFetchArray($result)) {
                    $xml_string .= "<Appointment>\n";
                    foreach ($res as $fieldname => $fieldvalue) {
                        $rowvalue = xmlsafestring($fieldvalue);
                        $xml_string .= "<$fieldname>$rowvalue</$fieldname>\n";
                    }

                    $xml_string .= "</Appointment>\n";
                    $counter++;
                }
            } else {
                $xml_string .= "<status>-1</status>\n";
                $xml_string .= "<reason>Could not find results</reason>\n";
            }
        } else {
            $strQuery = "SELECT pd.id as pid,pd.fname, pd.lname, pd.sex as gender, ope.pc_apptstatus, ope.pc_eid, ope.pc_pid, ope.pc_title, ope.pc_hometext, ope.pc_eventDate, ope.pc_startTime, ope.pc_endTime 
                    FROM openemr_postcalendar_events as ope, patient_data as pd 
                    WHERE pd.pid=ope.pc_pid";
            $result = sqlStatement($strQuery);
            $numRows = sqlNumRows($result);
            if ($numRows > 0) {
                $xml_string .= "<status>0</status>\n";
                $xml_string .= "<reason>Success processing patient appointments records</reason>\n";
                $counter = 0;

                while ($res = sqlFetchArray($result)) {
                    $xml_string .= "<Appointment>\n";

                    foreach ($res as $fieldname => $fieldvalue) {
                        $rowvalue = xmlsafestring($fieldvalue);
                        $xml_string .= "<$fieldname>$rowvalue</$fieldname>\n";
                    }

                    $xml_string .= "</Appointment>\n";
                    $counter++;
                }
            } else {
                $xml_string .= "<status>-1</status>\n";
                $xml_string .= "<reason>Could not find results</reason>\n";
            }
        }
    } else {
        $xml_string .= "<status>-2</status>";
        $xml_string .= "<reason>Invalid Token</reason>";
    }
} else {
    $xml_string .= "<status>-2</status>\n";
    $xml_string .= "<reason>Invalid Token</reason>\n";
}


$xml_string .= "</Appointments>\n";
echo $xml_string;
?>