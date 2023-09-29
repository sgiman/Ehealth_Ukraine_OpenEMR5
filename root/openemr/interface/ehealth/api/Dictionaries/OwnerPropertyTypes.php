<?php
/***********************************************************************************************
 *  api/Dictionaries/OwnerPropertyTypes.php
 *  OWNER_PROPERTY_TYPE (GET)
 *  https://api-preprod.ehealth.gov.ua/api/dictionaries?name=OWNER_PROPERTY_TYPE

 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
************************************************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');

	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$data = '{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"OWNER_PROPERTY_TYPE","values":{"PRIVATE":"приватна форма власності","STATE":"бюджетна форма власності"}}],"meta":{"code":200,"type":"list"}}';

	echo request_dictionaries($data);

	//$url = "{$api}/api/dictionaries?name=OWNER_PROPERTY_TYPE";
	// echo request_ehealth($url);

?>