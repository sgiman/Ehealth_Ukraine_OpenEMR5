<?php
/*****************************************************************************************
 *  api/Dictionaries/AddressTypes.php 
 *  ADDRESS_TYPE (GET)
 *  https://api-preprod.ehealth.gov.ua/api/dictionaries?name=ADDRESS_TYPE
 *
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
******************************************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
	
	require_once '../Common/config.php';
	require_once '../Common/functions.php';
	
	
	$data = '{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"ADDRESS_TYPE","values":{"RECEPTION":"приймальної","REGISTRATION":"реєстрації","RESIDENCE":"проживання/розташування/перебування"}}],"meta":{"code":200,"type":"list"}}';
	
	/*	
	$data = '{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"ADDRESS_TYPE","values":{"RESIDENCE":"проживання/розташування/перебування"}}],"meta":{"code":200,"type":"list"}}';
	*/

	echo request_dictionaries($data);
	
	//$url = "{$api}/api/dictionaries?name=ADDRESS_TYPE";
	// echo request_ehealth($url);

?>
