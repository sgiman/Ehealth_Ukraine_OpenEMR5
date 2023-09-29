<?php
/*******************************************************************************
 * Home/Login.php
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 *
 * API EHEALTH version 1.0
 * Writing by sgiman, 2020
********************************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');

	$_POST['emplID_input'];

	$data = [
	'error' => null,
	'appData' =>"{\"meta\": {\"code\": 200}, \"data\": \"https://sgiman.com.ua/openemr/interface/ehealth/auth.php'\", \"id\": \"0\"}",
	'hasError'=>false, 
	'hasData'=>true
	];
	
	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	echo $json;
?>