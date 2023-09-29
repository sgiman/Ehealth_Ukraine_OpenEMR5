<?php
/*****************************************************************
 *  api/LegalEntityData/GetEmployeeDetails.php
 *  Get Employee Details (POST)
 *
 * -------------------------------------------------
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
******************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
 
  	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$employee_id = $_POST['emp_id'];
	
	//*****************************************************************************************
	//$employee_id = '89106b5b-fd20-4148-b7b6-b42fddb590ad'; 		// OWNER
	//$employee_id = 'a808d1da-dbdb-4286-b21e-063293cdb305';		// SPECIALIST
	//$email = 'sgiman@ukr.net';
	//*****************************************************************************************

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employees/{$employee_id}");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",
	"API-key: {$client_secret_mis}"
	));

	$response = curl_exec($ch);
	curl_close($ch);

	$res = json_decode($response, true);							// JSON to ARRAY	
    $id =  $res['data']['id'];
	
	//--- FIND EMAIL ---
	$query = "SELECT * FROM `z_ehealth_employees` WHERE `employee_user_id`= '{$id}'"; 
	$sel = mysqli_query($db, $query); 
	$row = mysqli_fetch_assoc($sel);
	if (isset($row['email'])) 	
		$email = $row['email'];
	else
		$email = 'none@none.non';
		
	 
	// SPECIALIST
	if ($res['data']['employee_type'] == 'SPECIALIST') {
		$dt = [
            'id' => $res['data']['id'],
            'division' => $res['data']['division'],
            'employee_type'=> $res['data']['employee_type'],
            'end_date' => $res['data']['end_date'],
            'is_active' => $res['data']['is_active'],
            'legal_entity' => $res['data']['legal_entity'],
            'party' => $res['data']['party'],
			'position' => $res['data']['position'],
            'start_date' => $res['data']['start_date'],
            'status' => $res['data']['status'],
			'specialist'=>$res['data']['specialist'],
			'email' => $email
		];		
	}	
	// OTHERS
	else {
		$dt = [
            'id' => $res['data']['id'],
            'division' => $res['data']['division'],
            'employee_type'=> $res['data']['employee_type'],
            'end_date' => $res['data']['end_date'],
            'is_active' => $res['data']['is_active'],
            'legal_entity' => $res['data']['legal_entity'],
            'party' => $res['data']['party'],
            'position' => $res['data']['position'],
            'start_date' => $res['data']['start_date'],
            'status' => $res['data']['status'],
			'email' => $email
		];		
	}	
			
	$dt_json = json_encode($dt, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);   

	$error = null;
	$hasError = false;
	$hasData = true;
	$data = [
		'error' => null,
		'appData' =>$dt_json,
		'hasError' =>false, 
		'hasData' =>true
	];
	
	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);   
	echo $json;

?>
