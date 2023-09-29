<?php
/**************************************************************************************************************************************
 *  api/UaAddresses/Districts.php
 *  Districts by Region (GET)
 *  Районы 
 *
 *  /api/UaAddresses/Districts.php?regionName=" + regionName
 *   https://api.ehealth.gov.ua/api/uaddresses/settlements?district=БАХЧИСАРАЙСЬКИЙ&name=БАХЧИСАРАЙ
 *
 *   @package OpenEMR
 *   @link    http://www.open-emr.org
 *
 *   API EHEALTH version 1.0
 *   Writing by sgiman, 2020
***************************************************************************************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
	
	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	//--- Districts by Region ---
	$regionName =  urlencode($_GET['regionName']);
		
	$url = "{$api}/api/uaddresses/districts?region=" .  $regionName  .  '&page=1&page_size=100';
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	curl_close($ch);

	//$res = json_decode($response, true);	
	//print_result($res);
	
	echo $response;

?>
