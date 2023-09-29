<?php
/**
 * api/searchappointments.new.php Search appointments.
 *
 * API is allowed to get list of appointments for search appointment .
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


//$appointment_type = $_GET['appointmentType']; // 1: All, 2:person specefic
$token = $_GET['token'];
$from_date = !empty($_GET['fromDate']) ? $_GET['fromDate'] : '1900-00-00';
$to_date = !empty($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d');
$time = !empty($_GET['time']) ? $_GET['time'] : '';
$facilities = $_GET['facilities'];
$provider = $_GET['provider'];
$appstatus = stripslashes($_GET['status']);

$xml_string = "";
$xml_string .= "<Appointments>\n";


if ($userId = validateToken($token)) {
    $username = getUsername($userId);

    $acl_allow = acl_check('patients', 'appt', $username);
    if ($acl_allow) {
        $where = null;
        $provider_id = $userId;
        
        if ($facilities) {
            $where = " AND pc_facility IN ($facilities)";
        }
        
        //if ($provider) {
        //    $where = " AND pc_provider IN ($provider)";
        //}
        
        if ($appstatus) {
            $where .= ' AND pc_apptstatus IN (' . $appstatus . ')';
        }
        
        //if ($appointment_type == 2) {
        //    $where .= " AND pc_aid = {$provider_id}";
        //}
        
        if ($time) {
            $where .= " AND pc_startTime = '{$time}'";
        }

        $events = fetchEvents_SG($from_date, $to_date, $where, $orderby_param = null);


        if ($events) {
            $xml_string .= "<status>0</status>\n";
            $xml_string .= "<reason>Success processing patient appointments records</reason>\n";
            $counter = 0;

            foreach ($events as $event) {
                $xml_string .= "<Appointment>\n";

                foreach ($event as $fieldname => $fieldvalue) {
                    $rowvalue = xmlsafestring($fieldvalue);
                    $xml_string .= "<$fieldname>$rowvalue</$fieldname>\n";
                }

                $strQuery = 'SELECT pc_apptstatus,p.sex as gender,p.pid as p_id, pce.pc_facility,pce.pc_billing_location,f1.name as facility_name,f2.name as billing_location_name FROM openemr_postcalendar_events as pce
                                            LEFT JOIN `facility` as f1 ON pce.pc_facility = f1.id
                                            LEFT JOIN `facility` as f2 ON pce.pc_billing_location = f2.id
                                            LEFT JOIN patient_data AS p ON p.pid = pce.pc_pid 
                                        WHERE pc_eid = ?';
                
                $result = sqlQuery($strQuery, array($event['pc_eid']));
             
                $status = xmlsafestring($result['pc_apptstatus']);
                $xml_string .= "<gender>{$result['gender']}</gender>\n";
                $xml_string .= "<pc_apptstatus>{$status}</pc_apptstatus>\n";
                $xml_string .= "<pc_facility>{$result['pc_facility']}</pc_facility>\n";
                $xml_string .= "<facility_name>{$result['facility_name']}</facility_name>\n";
                $xml_string .= "<pc_billing_location>{$result['pc_billing_location']}</pc_billing_location>\n";
                $xml_string .= "<billing_location_name>{$result['billing_location_name']}</billing_location_name>\n";
                $patient_id = $result['p_id'];
                $strQuery2 = "SELECT d.url
                                FROM `documents` AS d
                                INNER JOIN `categories_to_documents` AS c2d ON d.id = c2d.document_id
                                WHERE d.foreign_id = ?
                                AND c2d.category_id = 13";

                 $result2 = sqlQuery($strQuery2, array($patient_id));
           
                if ($result2) {
                    $url = getUrl($result2['url']);
                    $xml_string .= "<patient_profile_image>{$url}</patient_profile_image>\n";
                } else {
                    $xml_string .= "<patient_profile_image></patient_profile_image>\n";
                }

                $xml_string .= "</Appointment>\n";
                $counter++;
            }
        } else {
            $xml_string .= "<status>-1</status>\n";
            $xml_string .= "<reason>Could not find results</reason>\n";
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>\n";
    $xml_string .= "<reason>Invalid Token</reason>\n";
}


$xml_string .= "</Appointments>\n";
echo $xml_string;


//------------------------------------
//          fetchEvents_SG
//
// $from_date
// $to_date
// $where_param
// $orderby_param
//------------------------------------
function fetchEvents_SG( $from_date, $to_date, $where_param = null, $orderby_param = null ) 
{
	$where =
		"( (e.pc_endDate >= '$from_date' AND e.pc_eventDate <= '$to_date' AND e.pc_recurrtype = '1') OR " .
  		  "(e.pc_eventDate >= '$from_date' AND e.pc_eventDate <= '$to_date') )";
	if ( $where_param ) $where .= $where_param;
	
	$order_by = "e.pc_eventDate, e.pc_startTime";
	if ( $orderby_param ) {
		$order_by = $orderby_param;
	}
	
	$query = "SELECT " .
  	"e.pc_eventDate, e.pc_endDate, e.pc_startTime, e.pc_endTime, e.pc_duration, e.pc_recurrtype, e.pc_recurrspec, e.pc_recurrfreq, e.pc_catid, e.pc_eid, " .
  	"e.pc_title, e.pc_hometext, e.pc_apptstatus, " .
  	"p.fname, p.mname, p.lname, p.pid, p.pubpid, p.phone_home, p.phone_cell, " .
  	"u.fname AS ufname, u.mname AS umname, u.lname AS ulname, u.id AS uprovider_id, " .
  	"c.pc_catname, c.pc_catid " .
  	"FROM openemr_postcalendar_events AS e " .
  	"LEFT OUTER JOIN patient_data AS p ON p.pid = e.pc_pid " .
  	"LEFT OUTER JOIN users AS u ON u.id = e.pc_aid " .
	"LEFT OUTER JOIN openemr_postcalendar_categories AS c ON c.pc_catid = e.pc_catid " .
	"WHERE $where " . 
	"ORDER BY $order_by";

	$res = sqlStatement( $query );
	$events = array();
	if ( $res )
	{
		while ( $row = sqlFetchArray($res) ) 
		{
			// if it's a repeating appointment, fetch all occurances in date range
			if ( $row['pc_recurrtype'] ) {
				$reccuringEvents = getRecurringEvents( $row, $from_date, $to_date );
				$events = array_merge( $events, $reccuringEvents );
			} else {
				$events []= $row;
			}
		}
	}
	
	return $events;
}




?>