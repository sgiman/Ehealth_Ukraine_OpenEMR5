<?php
/*****************************************************************
 *  api/LegalEntityData/UpdateDivision.php
 *  Update Division (POST)
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

	$id_division = $_POST["id"];
	$data = $_POST["data"];

	/*******************************************************************/   
	//$id_division = '75e28d64-2fcc-432f-9b80-38c7e59c5c38';	
	/*******************************************************************/

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/divisions/{$id_division}");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{$data}");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"Authorization: Bearer {$db_value}",
		"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

	$res = json_decode($response, true);
		
	$data = [
		'error' => null,
		'appData' =>$response,
		'hasError' =>false, 
		'hasData' =>true
	];
	
	//print_result($res);

	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

	echo $json;
	
?>
