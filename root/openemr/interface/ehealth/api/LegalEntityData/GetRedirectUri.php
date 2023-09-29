<?php
/*****************************************************************
 *  api/LegalEntityData/GetRedirectUri.php
 *  Get Redirect URI (POST)
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
  
	$data = [
	'error' => null,
	'appData' =>"{\"meta\": {\"code\": 200}, \"data\": \"https://sgiman.com.ua/openemr/interface/ehealth/auth.php'\", \"id\": \"0\"}",
	'hasError'=>false, 
	'hasData'=>true
	];
	
	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	echo $json;
	
?>
