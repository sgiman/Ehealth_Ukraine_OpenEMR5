<?php
/*****************************************************************
 *  api/LegalEntityData/GetDivisionList.php
 *  Get Division List (POST)
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

	//$legal_entity_type = $_POST["legalEntityType"];
	//$email = $_POST["email"];
	//$legal_entity_id =  $_POST["orgId"];

	$ch = curl_init();
	//curl_setopt($ch, CURLOPT_URL, ".../api/divisions?ids=&name=&legal_entity_id=&type=&status=&page=&page_size=");
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/divisions?legal_entity_id={$legal_entity_id}&status=ACTIVE&page=1&page_size=100");
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
	
	//print_result($res);
	
	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);   
	echo $json;
  	
?>
