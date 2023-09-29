<?php
/********************************************************************
 * api/report_appointments.php Appointments report.
 * (006) report appointments
 *
 * API is allowed to get patient appointments report.
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 ********************************************************************/
header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;

require_once ('includes/pdflibrary/config/lang/eng.php');
require_once ('includes/pdflibrary/tcpdf.php');
require_once 'classes.php';

$xml_string = "";
$xml_string = "<list>";

$token = $_GET['token'];
$facility = $_GET['facility'];
$provider = $_GET['provider'];
$show_available_times = $_GET['show_available_times'];
$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];


if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('encounters', 'auth_a', $user);
    if ($acl_allow) {


        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("Haroon");
        $pdf->SetTitle("My Report");
        $pdf->SetSubject("My Report");
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $appointments = fetchAppointments($from_date, $to_date, $patientId = '', $provider, $facility);

        if ($show_available_times) {
            $availableSlots = getAvailableSlots($from_date, $to_date, $provider, $facility);
            $appointments = array_merge($appointments, $availableSlots);
        }
        
        $form_orderby = 'date';
        //$form_orderby = 'doctor';
        $appointments = sortAppointments($appointments, $form_orderby);

        $single_record_header = "";
        $single_record = '';

        if ($appointments) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>Appointments Processed successfully</reason>";

            for ($i = 0; $i < count($appointments); $i++) {
                $xml_string .= "<appointment>\n";

                foreach ($appointments[$i] as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);
                    $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                }
                $provider_name = $appointments[$i]['ufname'] == $last_provider ? '' : $appointments[$i]['ufname'] . " " . $appointments[$i]['umname'] . " " . $appointments[$i]['ulname'];
                
                
                $last_provider = $appointments[$i]['ufname'];

                $xml_string .= "</appointment>\n";

            }

        
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>Could not find results</reason>";
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




//----------------------------------
//  1   fetchAppointments_SG (?)
//----------------------------------
function fetchAppointments_SG( $from_date, $to_date, $pc_StartTime, $with_out_StartTime = null, $patient_id = null, $provider_id = null, $facility_id = null, $pc_appstatus = null, $with_out_provider = null, $with_out_facility = null, $pc_catid = null)
{
	
	$where = "";
	
	if ( $provider_id ) $where .= " AND e.pc_aid = '$provider_id'";
	if ( $patient_id ) {
		$where .= " AND e.pc_pid = '$patient_id'";
	} else {
		$where .= " AND e.pc_pid != ''";
	}		


	$facility_filter = '';
	if ( $facility_id ) {
		$event_facility_filter = " AND e.pc_facility = '" . add_escape_custom($facility_id) . "'";    // escape $facility_id
		$provider_facility_filter = " AND u.facility_id = '" . add_escape_custom($facility_id) . "'"; // escape $facility_id
		$facility_filter = $event_facility_filter . $provider_facility_filter;
	}
	
	$where .= $facility_filter;

//------------------------------------------------------------------------------------------------------------//

	//Appointment StartTime checking
	$StartTime_filter = '';
  if ($pc_startTime) {
    $StartTime_filter = " AND e.pc_startTime = '{$pc_startTime}'" ; // Start-Time
  }
           
   $where .= $StartTime_filter;

//------------------------------------------------------------------------------------------------------------//
	
	//Appointment Status Checking
	$filter_appstatus = '';
	if($pc_appstatus != ''){
		$filter_appstatus = " AND e.pc_apptstatus = '" . $pc_appstatus."'";
	}
	$where .= $filter_appstatus;

  if($pc_catid !=null)
  {
     $where .= " AND e.pc_catid=".intval($pc_catid); // using intval to escape this parameter
  }
        
	//Without Provider checking
	$filter_woprovider = '';
	if($with_out_provider != ''){
		$filter_woprovider = " AND e.pc_aid = ''";
	}
	$where .= $filter_woprovider;
	
	//Without Facility checking
	$filter_wofacility = '';
	if($with_out_facility != ''){
		$filter_wofacility = " AND e.pc_facility = 0";
	}
	$where .= $filter_wofacility;
	

	//Without StartTime checking
	$filter_woStartTime = '';
	if($with_out_StartTime != ''){
		$filter_woStartTime = " AND e.pc_startTime = ''";
	}
	$where .= $filter_woStartTime;

	$appointments = fetchEvents_SG( $from_date, $to_date, $where );
	return $appointments;
}


//----------------------------------
//  2       fetchEvents_SG (?)
//----------------------------------
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


