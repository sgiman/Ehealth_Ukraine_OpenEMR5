<?php
/*******************************************************************************
 *  api/Dictionaries/SpecialityTypes.php
 *  Speciality Types (GET)
 *  https://api-preprod.ehealth.gov.ua/api/dictionaries?name=
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
	
	$url = "{$api}/api/dictionaries?name=SPECIALITY_TYPE";
	echo request_ehealth($url);
	
?>