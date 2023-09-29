<?php 
/*****************************************************************
 *  auth.php
 *  Redirect URI (Location)
 *  -----------------------------------------------
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
******************************************************************/
require_once  'api/Common/config.php';
require_once  'api/Common/functions.php';

global $db;
global $eh_users;

//token_exchange ($scope);
token_refresh() ;

// ---------------------------
//           Access CODE  (NEW)
//            z_ehealth_connect
// ---------------------------
// --- CODE ---
$code = $_GET['code'];
if (isset($code)) 	
{
	$update = "UPDATE `z_ehealth_connect` SET `code`= '{$code}'  WHERE `id`= {$db_id}"; 
	mysqli_query($db, $update) or die (mysqli_error($db));  
}

// --  Type Connect  --- 
$update = "UPDATE `z_ehealth_type_connect` SET `type_connect`= {$type_connect}  WHERE 1"; 
mysqli_query($db, $update) or die (mysqli_error($db));  

if( $type_connect == 1) 
{
	$update = "UPDATE `z_ehealth_type_connect` SET `type_name_connect`= 'MIS'  WHERE 1 ";  		//  type_name_connect for MIS
	mysqli_query($db, $update) or die (mysqli_error($db));  
}
else 
{	
	$update = "UPDATE `z_ehealth_type_connect` SET `type_name_connect`= 'OWNER'  WHERE 1 ";		// type_name_connect for OWNER
	mysqli_query($db, $update) or die (mysqli_error($db));  
}

// ------------------------------
//  SCOPE OWNER & TOKEN EXCHANGE
// ------------------------------
$client_scope = 'employee:read employee:write employee_request:read division:read division:write secret:refresh legal_entity:read related_legal_entities:read role:read';
token_exchange ($client_scope);

////////////////////////////////////////////////////// NEW /////////////////////////////////////////////////
//******************************************
//   Get Employees Requests List (V2)
//   "APPROVED"
//******************************************
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests?edrpou={$db_edrpou}&status=APPROVED&page=1&page_size=500");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",
	"API-key: {$client_secret_mis}"
));
$response = curl_exec($ch);
curl_close($ch);

//****************************************
//   Copy Employee Requests List (V2)
//   z_ehealth_user_list
//   "APPROVED"
//****************************************
$res = json_decode($response, true);
$count_res = trim ($res['paging'] ['total_entries']); 	

mysqli_query($db, "TRUNCATE TABLE z_ehealth_employees_list") or die (mysqli_error($db));   // Clear Table 		

for ($i=0; $i < $count_res; ++$i) {
	$edrpou = $res['data'][$i]['edrpou'];
	$legal_entity_name = $res['data'][$i]['legal_entity_name'];
	$first_name = $res['data'][$i]['first_name'];
	$last_name = $res['data'][$i]['last_name'];
	$second_name = $res['data'][$i]['second_name'];
	$status = $res['data'][$i]['status'];
	$employee_id = $res['data'][$i]['id'];
	$inserted_at = $res['data'][$i]['inserted_at'];

	$insert = "INSERT INTO `z_ehealth_employees_list`
	(
	`edrpou`, 
	`full_name`,
	`first_name`, 
	`last_name`, 
	`second_name`, 
	`status`, 
	`employee_id`, 
	`inserted_at`) 
	VALUES (
	'$edrpou',
	'$legal_entity_name',
	'$first_name',
	'$last_name',
	'$second_name ',
	'$status',
	'$employee_id',
	'$inserted_at')";

	mysqli_query($db, $insert) or die (mysqli_error($db));  		
}

$update = "UPDATE  `z_ehealth_employees_list`
LEFT OUTER JOIN 
	(SELECT MIN(`id`) AS `id`, 	`first_name`, `last_name`, `second_name` 
	FROM `z_ehealth_employees_list` 
	GROUP BY `first_name`, `last_name`, `second_name`) AS `tmp` 
ON 
	`z_ehealth_employees_list`.`id` = `tmp`.`id`
SET
	`z_ehealth_employees_list`.`double` = 1    
WHERE
	`tmp`.`id` IS NULL";

mysqli_query($db, $update) or die (mysqli_error($db));  		

//-------------------------
//  Employees IDS
//  z_ehealth_employees
//-------------------------
$query = "SELECT * FROM `z_ehealth_employees_list` WHERE `double`= 0"; 
$res = mysqli_query($db, $query); 

mysqli_query($db, "TRUNCATE TABLE z_ehealth_employees") or die (mysqli_error($db));   // Clear Table 		
while ($row = mysqli_fetch_assoc($res)) {
	 $id = $row['employee_id'] ;
	 $edrpou = $row['edrpou'] ; 
	 $full_name = $row['full_name'] ; 
	 copy_employee_data($id, $edrpou, $full_name);
}

//-------------------------
//       Employee Data by ID
//-------------------------
function copy_employee_data($id, $edr, $le_name) {
	global $api;
	global $db_value;
	global $client_secret_mis;
	global $db;

	$edrpou = $edr;
	$full_name = $le_name;
	
	// Employee Requests by ID 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests/{$id}");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Authorization: Bearer {$db_value}",
		"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

	// PRINT RESPONSE
	$res = json_decode($response, true);
	
	if ($res) {
		$first_name = $res['data']['party']['first_name'];
		$last_name = $res['data']['party']['last_name'];
		$second_name =  $res['data']['party']['second_name'];
		$status = $res['data']['status'];
		$employee_id = $res['data']['id'];
		$email = $res['data']['party']['email'];
		$position = $res['data']['position'];
		$employee_type = $res['data']['employee_type'];
		$legal_entity_id  = $res['data']['legal_entity_id'];
		$inserted_at = $res['data']['inserted_at'];
		$start_date = $res['data']['start_date'];	
		$position_name = position_name ($position);
		
		$insert = "INSERT INTO `z_ehealth_employees`(
		`edrpou`, 
		`full_name`,
		`first_name`, 
		`last_name`, 
		`second_name`, 
		`status`, 
		`employee_id`, 
		`email`, 
		`position`, 
		`position_name`, 
		`employee_type`, 
		`legal_entity_id`, 
		`inserted_at`, 
		`start_date`) 
		VALUES (
		'$edrpou', 
		'$full_name',
		'$first_name', 
		'$last_name', 
		'$second_name', 
		'$status', 
		'$employee_id', 
		'$email', 
		'$position', 
		'$position_name',
		'$employee_type',
		'$legal_entity_id',
		'$inserted_at', 
		'$start_date')";

		mysqli_query($db, $insert) or die (mysqli_error($db));  		
	}
}

//-----------------------------
//              z_employee_all
//-----------------------------
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employees?legal_entity_id={$db_client_id}&status=APPROVED&page=&page_size=&page=1&page_size=100");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",
	"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);
	
	///////////////////// COPY EMPLPOYEES TO MYSQL (TEST) ////////////////////
	$res = json_decode($response, true);
	copy_employees($res);
	//////////////////////////////////////////////////////////////////////////////
	//--- Copy Employees to MySQL --- 
	function copy_employees($res) {
		global $db;
		
		// ---  Copy to MySQL ---
		$count_res = trim ($res['paging'] ['total_entries']); 	
		mysqli_query($db, "TRUNCATE TABLE z_ehealth_employees_all") or die (mysqli_error($db));   // Clear Table 		
		for ($i=0; $i < $count_res; ++$i) {
			$id_employee = $res['data'][$i]['id'];
			$full_name = $res['data'][$i]['legal_entity']['name'];
			$employee_type = $res['data'][$i]['employee_type'];
			$reg_status = $res['data'][$i]['status'];

			$position = $res['data'][$i]['position'];					
			$position_name = position_name ($position); 	
			
			$fisrt_name = $res['data'][$i]['party']['first_name'];
			$second_name = $res['data'][$i]['party']['second_name'];
			$last_name = $res['data'][$i]['party']['last_name'];			
			$start_date = $res['data'][$i]['start_date'];
			
			$insert = "INSERT INTO `z_ehealth_employees_all`(`id_employee`,  `full_name`, `first_name`, `second_name`, `last_name`, `position`, `position_name`, `employee_type`, `reg_status`, `start_date`)
			VALUES ( '$id_employee', '$full_name',  '$fisrt_name',  '$second_name',  '$last_name',  '$position',  '$position_name',  '$employee_type',  '$reg_status',  '$start_date') ";
			mysqli_query($db, $insert) or die (mysqli_error($db));  		
		}
	}

//--------------------------------
//   $client_id
//  `z_ehealth_connect`
//--------------------------------
$query = "SELECT * FROM `z_ehealth_connect` WHERE `type`= 'OWNER' "; 
$res = mysqli_query($db, $query); 
$row = mysqli_fetch_assoc($res);
$client_id = $row['client_id'];
$res->close();


//===================================
//     COPY IMPLOYEE ID (form ALL)
//	z_ehealth_employees_all, z_ehealth_employees 
//===================================
// --- SELECT z_ehealth_employees_all ---
$query = "SELECT * FROM `z_ehealth_employees_all` WHERE  1"; 
$res = mysqli_query($db, $query); 
while ($row = mysqli_fetch_assoc($res)) {
	$first_name =$row['first_name'];
	$second_name = $row['second_name'];
	$last_name = $row['last_name'];
	$position = $row['position'];
	$employee_type =$row['employee_type'];
	$id_employee =$row['id_employee'];
	
	/*
    echo '<hr>';
	echo $first_name . '<br>';
	echo $second_name  . '<br>';
	echo $last_name . '<br>';
	echo $position . '<br>';
	echo $employee_type . '<br>';
	echo $id_employee . '<br>';
	*/
	
	// --- UPDATE z_ehealth_employees ---
	$update = "UPDATE `z_ehealth_employees` SET `employee_user_id`= '{$id_employee}' WHERE 
	`first_name`= '{$first_name}' AND 
	`second_name`= '{$second_name}' AND 
	`last_name`= '{$last_name}'  AND 
	`position`= '{$position} ' AND 
	`employee_type`= '{$employee_type}'";
	mysqli_query($db, $update) or die (mysqli_error($db));  

}
$res->close();

/////////////////////////////
//******************************* 
//       REDIRECT to LOGIN       
//******************************* 
/////////////////////////////
$location = "Location: https://sgiman.com.ua/openemr/interface/ehealth/eh-m31/ProfileOfLegalEntity.php?code="  .  $_GET['code'];
header($location, TRUE, 301);
exit();

?>

<hr>

<center>
	<h2 align=center>PROTOTYPE</h2>
</center>

<hr>

<h1>MODULE 3.1</h1>
<hr>
<a style="text-decoration: none;" href="https://sgiman.com.ua/openemr/interface/ehealth/eh-m31/Login.php?code=<?php echo $_GET['code']?>"><h2>Перейти на https://scsmed.com.ua/scsmed/interface/ehealth/eh-m31/ для продолжения работы</h2></a>
