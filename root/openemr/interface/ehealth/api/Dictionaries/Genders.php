<?php
/*******************************************************************************
 *  api/Dictionaries/Genders.php
 *  GENDER (GET)
 *  https://api-preprod.ehealth.gov.ua/api/dictionaries?name=GENDER
 *
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
********************************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
	
	require_once '../Common/config.php';
	require_once '../Common/functions.php';
	
	$data = 	'{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"GENDER","values":{"FEMALE":"жіноча","MALE":"чоловіча"}}],"meta":{"code":200,"type":"list"}}';

	echo request_dictionaries($data);

	//$url = "{$api}/api/dictionaries?name=GENDER";
	//echo request_ehealth($url);
	
?>