<?php
/*****************************************************************
 *  api/LegalEntityData/GetEmployeeList.php
 *  Get Employee List (POST)
 *  OLD VERSION
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

	$org_id = $_POST['org_id'];
	$division_id  = $_POST['division_id'];
	$status = $_POST['status'];

	$ch = curl_init();
	//api/employees?no_tax_id=&tax_id=&party_id=&edrpou=&legal_entity_id=&division_id=&status=&employee_type=
	//&page=&page_size=&page=1&page_size=100
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employees?legal_entity_id={$db_client_id}&division_id={$division_id}&status={$status}&page=1&page_size=100");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",
	"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

	$error = null;
	$hasError = false;
	$hasData = true;
	$data = [
		'error' => null,
		'appData' =>$response,
		'hasError' =>false, 
		'hasData' =>true
	];
	
	//$res = json_decode($response, true);
	//print_result($res);

	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);   
	echo $json;

?>
