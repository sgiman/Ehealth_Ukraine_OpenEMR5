<?php
$type_connect = 2;  // MIS (1) / OWNER (2)

// PREPROD / PROD 
$auth = 'https://auth-preprod.ehealth.gov.ua';
$api =  'https://api-preprod.ehealth.gov.ua';			
//$api_prod = 'https://api.ehealth.gov.ua';

// DEMO
$auth_demo = 'https://auth.demo.edenlab.com.ua';
$api_demo =  'https://auth.demo.edenlab.com.ua/';			

$client_secret_mis  = '768f58eca7dca31bc5e6b9f866470cbf';			// API-KEY MIS	
$access_token = '28e44060571cf1f6e32a8c351c8e7c80';				 	// X-CSRF-Token
$user_redirect_uri = 'https://sgiman.com.ua/openemr/interface/ehealth/auth.php'; // redirect_uri 

//-------------------------------------------
//                                   MySQL 
//-------------------------------------------
require_once 'mysql.php';

// USER ID
$query = "SELECT * FROM `z_ehealth_type_connect` WHERE 1 "; 
$res = mysqli_query($db, $query); 
$row = mysqli_fetch_assoc($res);
$db_id = $row['type_connect'] ;
$res->close();

$i = $db_id - 1; 		// MIS (0) / OWNER (1) / DEMO (2)  - Array Config

/********************************************** EHEALTH ACCESS **********************************************/
$eh_users = [
	// MIS ACCESS - A1234567890114
	[
		'id'=>'1',
		'client_id' => 'c545e324-54c2-4125-9540-037cbdea96b6',	
		'client_secret' => '768f58eca7dca31bc5e6b9f866470cbf',		
		//'redirect_uri' => 'https://test114.com',
		'redirect_uri' => 'https://sgiman.com.ua/openemr/interface/ehealth/auth.php',
		'email' => 'mis114@test.com',	
		'access_token' => '28e44060571cf1f6e32a8c351c8e7c80',
		'refresh_token'=> 'a9a36207a017cc205caeb8ce99ec7557',
		'scope' => "legal_entity:read legal_entity:write legal_entity:mis_verify role:read user:request_factor user:approve_factor event:read client:read connection:read connection:write connection:refresh_secret connection:delete medical_program:read program_service:read",
		'user'=>'MIS',
		'mis' => true
	],
	// OWNER ACCESS - MedTest12345
	[
		'id'=>'2',
		'client_id' => '30131cf9-836a-48a0-98dc-741ecb1d3cf7',
		'client_secret' => 'M1RGb0xXZGJWY25hbGFHMTNBNHBOdz09',		
		'redirect_uri' => 'https://sgiman.com.ua/openemr/interface/ehealth/auth.php',
		'email' => 'sgimancs@gmail.com',
		'access_token' => '28e44060571cf1f6e32a8c351c8e7c80',
		'refresh_token' => 'a9a36207a017cc205caeb8ce99ec7557',

		'scope' => 'employee:read employee:write employee_request:read employee_request:write employee_request:approve employee_request:reject division:read division:write division:details division:activate division:deactivate secret:refresh legal_entity:read related_legal_entities:read',
	
		/*
		'scope' => "employee_role:write employee_role:read healthcare_service:write healthcare_service:read declaration:read declaration_request:approve declaration_request:read declaration_request:reject declaration_request:write division:activate division:deactivate division:details division:read division:write employee:deactivate employee:details employee:read employee:write employee_request:approve employee_request:read employee_request:reject employee_request:write legal_entity:read otp:read otp:write person:read reimbursement_report:read secret:refresh capitation_report:read contract_request:create contract_request:read contract_request:terminate contract_request:approve contract_request:sign client:read connection:read connection:write connection:refresh_secret connection:delete related_legal_entities:read contract:read contract:write medication_request:details medication_dispense:read equipment:write equipment:read",
		*/
		
		'user'=>'OWNER',
		'mis' => false
	],
	// DEMO ACCESS - A1234567890114
	[
		'id'=>'3',
		'client_id' => 'b76d1907-317a-474f-b4f8-e10a5608b4e9',
		'client_secret' => '7b5a09360ea96d64b5b856dd23bdf75e',		
		'redirect_uri' => 'https://test114.com',
        'email' => "mis114@test.com",
		'access_token' => '958e149747d3789cb66d1aa9aec79a6b',
		'refresh_token'=> 'a9a36207a017cc205caeb8ce99ec7557',
        'scope' => "client:read connection:delete connection:read connection:refresh_secret connection:write event:read legal_entity:mis_verify legal_entity:read legal_entity:write role:read user:approve_factor user:request_factor",
		'user'=>'DEMO',
		'mis' => true
	]
];
/********************************************** END EHEALTH ACCESS **********************************************/

// -- CURRENT ACCESS -- 
$client_secret_mis = $eh_users[0]['client_secret'];
$scope = $eh_users[$i]['scope'];

// -- CURRENT DATA ACCESS  -- 
$query = "SELECT * FROM `z_ehealth_connect` WHERE id = {$db_id}"; 
$res = mysqli_query($db, $query); 
$row = mysqli_fetch_assoc($res);

if  ($res->num_rows != 0)  
{	
	$db_id = $row['id'] ;
	$db_email = $row['email'];
	$db_client_id = $row['client_id'];
	$db_client_secret = $row['client_secret'];
	$db_redirect_uri = $row['redirect_uri'];
	$db_value = $row['value'];
	$db_code = $row['code'] ;
	$db_refresh_token = $row['refresh_token'];
	$db_access = $row['access'];
	$db_edrpou = $row['edrpou'];
	$db_type = $row['type'];
	
	if (isset ($row['connection_id'])) 
		$db_connection_id = $row['connection_id'];
 };
$res->close();

// -- OWNER DATA ACCESS  -- 
$query = "SELECT * FROM `z_ehealth_connect` WHERE id = 2"; 
$res = mysqli_query($db, $query); 
$row = mysqli_fetch_assoc($res);
if  ($res->num_rows != 0)  
{	
	$db_id_owner = $row['id'] ;
	$db_email_owner = $row['email'];
	$db_client_id_owner = $row['client_id'];
	$db_client_secret_owner = $row['client_secret'];
	$db_redirect_uri_owner = $row['redirect_uri'];
	$db_value_owner = $row['value'];
	$db_code_owner = $row['code'] ;
	$db_refresh_token_owner = $row['refresh_token'];
	$db_access_owner = $row['access'];
	$db_edrpou_owner = $row['edrpou'];
	$db_type_owner = $row['type'];
	
	if (isset ($row['connection_id'])) 
		$db_connection_id_owner = $row['connection_id'];
 };
$res->close();

?>
