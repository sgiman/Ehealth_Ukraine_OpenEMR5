<?php
/*****************************************************************
 *  api/Dictionaries/DocumentTypes.php
 *  DOCUMENT_TYPE (GET)
 *  https://api-preprod.ehealth.gov.ua/api/dictionaries?name=DOCUMENT_TYPE
 *
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

	$data = 	'{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"DOCUMENT_TYPE","values":{"BIRTH_CERTIFICATE":"Свідоцтво про народження (для осіб, які не досягли 14-річного віку)","BIRTH_CERTIFICATE_FOREIGN":"Свідоцтво про народження іноземного зразку","COMPLEMENTARY_PROTECTION_CERTIFICATE":"Посвідчення особи, яка потребує додаткового захисту","NATIONAL_ID":"Біометричний паспорт громадянина України","PASSPORT":"Паспорт громадянина України","PERMANENT_RESIDENCE_PERMIT":"Посвідка на постійне проживання в Україні","REFUGEE_CERTIFICATE":"Посвідка біженця","TEMPORARY_CERTIFICATE":"Посвідка на проживання","TEMPORARY_PASSPORT":"Тимчасове посвідчення громадянина України"}}],"meta":{"code":200,"type":"list"}}';

	echo request_dictionaries($data);

	// $url = "{$api}/api/dictionaries?name=DOCUMENT_TYPE";
	// echo request_ehealth($url);

?>
