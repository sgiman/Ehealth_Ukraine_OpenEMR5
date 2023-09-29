<?php
/*************************************************************************************************
 *  api/Dictionaries/DictionariesM31.php 
 *  Dictionaries for ehaeath module 3.1(GET)
 *  
 *  https://api.ehealth.gov.ua/api/dictionaries?name=
 *  ACCREDITATION_CATEGORY,
 *  ADDRESS_TYPE,
 *  COUNTRY,
 *  DIVISION_TYPE,
 *  DOCUMENT_TYPE,
 *  EDUCATION_DEGREE,
 *  EMPLOYEE_STATUS,
 *  EMPLOYEE_TYPE,
 *  GENDER,
 *  LEGAL_ENTITY_TYPE,
 *  LEGAL_FORM,
 *  LICENSE_TYPE,
 *  OWNER_PROPERTY_TYPE,
 *  PHONE_TYPE,
 *  POSITION,
 *  SPEC_QUALIFICATION_TYPE,
 *  SCIENCE_DEGREE,
 *  SETTLEMENT_TYPE,
 *  SPECIALITY_LEVEL,
 *  SPECIALITY_TYPE,
 *  SPEC_QUALIFICATION_TYPE,
 *  STREET_TYPE
 * ------------------------------------------------------------------------
 *
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
*************************************************************************************************/
	header('Access-Control-Allow-Origin: *');
	//header('Content-type: application/json');

	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$url = "{$api}/api/dictionaries?name=ACCREDITATION_CATEGORY,ADDRESS_TYPE,COUNTRY,DIVISION_TYPE,DOCUMENT_TYPE,EDUCATION_DEGREE,EMPLOYEE_STATUS,EMPLOYEE_TYPE,GENDER,LEGAL_ENTITY_TYPE,LEGAL_FORM,LICENSE_TYPE,OWNER_PROPERTY_TYPE,PHONE_TYPE,POSITION,SPEC_QUALIFICATION_TYPE,SCIENCE_DEGREE,SETTLEMENT_TYPE,SPECIALITY_LEVEL,SPECIALITY_TYPE,SPEC_QUALIFICATION_TYPE,STREET_TYPE";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	curl_close($ch);
	//var_dump ($response);
	
	$file_json = './dictionaries.json';
	file_put_contents($file_json, $response);			// Save JSON to FILE
	
	$file = file_get_contents($file_json); 					// Read JSON form FILE
	$dic_file = json_decode($file,TRUE);        			// Декодировать в массив 
	print_result($dic_file);
		
	$res = json_decode($response, true); 					// Array
	//print_result($res);
 	
?>
