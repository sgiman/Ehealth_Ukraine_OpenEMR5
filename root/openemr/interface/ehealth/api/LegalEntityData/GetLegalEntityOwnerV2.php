<?php
/*****************************************************************
 *  api/LegalEntityData/GetLegalEntityOwnerV2.php
 *  Get Legal Entity Owner V2 (POST)
 *  Get Employee Details (for OWNER)
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
	
	$query = "SELECT * FROM `z_ehealth_user_connect` WHERE 1"; 
	$res = mysqli_query($db, $query); 
	$row = mysqli_fetch_assoc($res);
	$employee_id = $row['employee_id'] ;

	//  Get Employee Request by ID
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests/{$employee_id}");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"Authorization: Bearer {$db_value}",
		"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

	$res = json_decode($response, true);
	//print_result($res);  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	
	$data = [		
			'meta' => [
			'code' => $res['meta']['code'],	
			'type' =>$res['meta']['type']				
		],
		
		'data' => [
			'employee_id' => $res['data']['id'],
			'first_name' => $res['data']['party']['first_name'],
			'last_name' => $res['data']['party']['last_name'],
			'second_name' => $res['data']['party']['second_name'],
			'tax_id' => $res['data']['party']['tax_id'],
			'birth_date' => $res['data']['party']['birth_date'],
			'gender' => $res['data']['party']['gender'],
			'documents' => $res['data']['party']['documents'],
			'phones' => $res['data']['party']['phones'],
			'position' => $res['data']['position'],
			'email' => $res['data']['party']['email']
		]			
	];

	$dt = json_encode($data, JSON_UNESCAPED_UNICODE);   

/*-----------------------------------------------------------------------------------------------------------------   
   	// SAMPLE:
	$dt = "{ \"meta\":{\"code\": 200, \"type\":\"object\"}, \"data\":{\"employee_id\":\"6f28badc-281f-4347-b8fe-0bd21a8d1494\",\"first_name\":\"ОЛЕНА\",\"last_name\":\"ФЕДОРОВА\",\"second_name\":\"ЄВГЕНІЇВНА\",\"tax_id\":\"1988206582\",\"birth_date\":\"1963-10-01\",\"gender\":\"FEMALE\",\"email\":\"sgimancs@gmail.com\",\"documents\":[{\"type\":\"PASSPORT\",\"number\":\"СН780664\",\"issued_by\":\"Дніпровським РУГу МВС України\",\"issued_at\":\"1998-04-21\"},{\"type\":\"NATIONAL_ID\",\"number\":\"KN38782678\",\"issued_by\":\"Дніпровським РУГу МВС України\",\"issued_at\":\"2019-10-01\"}],\"phones\":[{\"type\":\"MOBILE\",\"number\":\"+380669090789\"},{\"type\":\"LAND_LINE\",\"number\":\"+380444502787\"}],\"position\":\"P2\"} }";
-------------------------------------------------------------------------------------------------------------------*/   	
	
	$owner = [
		'error' => null,
		'appData' =>$dt,
		'hasError' =>false, 
		'hasData' =>true
	];
	
	$json = json_encode($owner, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);   
    echo $json;

?>
