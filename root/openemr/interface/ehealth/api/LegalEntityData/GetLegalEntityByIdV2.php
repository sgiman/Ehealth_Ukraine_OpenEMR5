<?php
/*****************************************************************
 *  api/LegalEntityData/GetLegalEntityByIdV2
 *  Get Legal Entity By ID V2 (POST)
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
	require_once '../Common/mysql.php';
	require_once '../Common/functions.php';
	
	global $server;
	global $db_value;
	global $db_client_secret;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/v2/legal_entities/{$db_client_id_owner}");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"Authorization: Bearer {$db_value}",
		"API-key: {$client_secret_mis}"				
	));
	$response = curl_exec($ch);
	curl_close($ch);
	//var_dump($response);
	
	$res = json_decode($response, true);
	$error = null;
	$hasError = false;
	$hasData = true;
	
	$data = [
		'error' => null,
		'appData' =>$response,
		'hasError' =>false, 
		'hasData' =>true
	];
	
	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);   
	echo $json;
  	
?>
