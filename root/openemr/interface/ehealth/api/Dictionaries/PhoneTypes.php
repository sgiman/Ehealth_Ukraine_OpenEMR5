<?php
/********************************************************************************
 *  api/Dictionaries/PhoneTypes.php
 *  (008)  PHONE_TYPE (GET)
 *  https://api-preprod.ehealth.gov.ua/api/dictionaries?name=PHONE_TYPE
 *
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
**********************************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');

	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$data = 	'{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"PHONE_TYPE","values":{"LAND_LINE":"стаціонарний","MOBILE":"мобільний"}}],"meta":{"code":200,"type":"list"}}';

	echo request_dictionaries($data);

	//$url = "{$api}/api/dictionaries?name=PHONE_TYPE";
	//echo request_ehealth($url);

?>