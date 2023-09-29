<?php
/*************************************************************************************************
 *  api/Dictionaries/AccreditationCategories.php 
 *  ACCREDITATION_CATEGORY (GET)
 *  
 *  https://api-preprod.ehealth.gov.ua/api/dictionaries?name=ACCREDITATION_CATEGORY
 *
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
*************************************************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');

	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$data = '{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"ACCREDITATION_CATEGORY","values":{"FIRST":"Перша категорія","HIGHEST":"Вища категорія","NO_ACCREDITATION":"Без аккредитації","SECOND":"Друга категорія"}}],"meta":{"code":200,"type":"list"}}';
	echo request_dictionaries($data);

	//$url = "{$api}/api/dictionaries?name=ACCREDITATION_CATEGORY";
 	//echo request_ehealth($url);
	
?>
