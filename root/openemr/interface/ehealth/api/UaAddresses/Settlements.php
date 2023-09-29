<?php
/***********************************************************************************************
 *  api/UaAddresses/Regions.php
 *  Settlements (GET)
 *  Населённые пункты
 *  
 *  OEMR:
 *  https://www.scsmed.com.ua/scsmed/interface/ehealth/api/UaAddresses/Settlements.php?region=area&district=region
 * 
 *  EHELATH:
 *  https://api.ehealth.gov.ua/api/uaddresses/settlements?district=БАХЧИСАРАЙСЬКИЙ&name=БАХЧИСАРАЙ
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
	
	$region =  urlencode($_GET['region']);
	$district =  urlencode($_GET['district']);
	
	//echo  urldecode($region) . '<br>';
	//echo  urldecode($district) . '<br>';
	
	if (urldecode($region) === 'М.КИЇВ') 
		$district = "";

	if (urldecode($region) === 'М.СЕВАСТОПОЛЬ') 
		$district = "";
	
	//echo '$district = ' . $district . '<br>';
	
	$url = "{$api}/api/uaddresses/settlements?region="  .  $region  .  '&district='  .  $district  .  '&page=1&page_size=100';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	curl_close($ch);

	$res = json_decode($response, true);	
	$data = $res['data'];
	$new_array = ['data'=>$data];
	$res = json_encode($new_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

	echo $res;	

?>