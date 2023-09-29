<?php
/***********************************************************************************************
 *  api/UaAddresses/Regions.php
 *  Regions (GET)
 *  Области
 *
 *  https://api-preprod.ehealth.gov.ua/api/uaddresses/regions?page=1&page_size=100
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

	$url = "{$api}/api/uaddresses/regions?page=1&page_size=100";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	curl_close($ch);

	//$arr = json_decode($response, true);
	//$new_arr = ['regions' => $arr['data']];
	//$res = json_encode($new_arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	
	echo $response;	

?>
