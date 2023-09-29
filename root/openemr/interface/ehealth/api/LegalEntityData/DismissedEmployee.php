<?php
/*****************************************************************
 *  api/LegalEntityData/DismissedEmployee.php
 *  Dismissed (Deactivate) Employee  (POST)
 *  
 * -------------------------------------------------
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
******************************************************************/
	header('Access-Control-Allow-Origin: *');
	//header('Content-type: application/json');

	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$id_employee = $_POST["emp_id"];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employees/{$id_employee}/actions/deactivate");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",
	"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

	$json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	echo $json;
	   
?>
