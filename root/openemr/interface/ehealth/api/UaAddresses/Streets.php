<?php
/***********************************************************************************************
 *  api/UaAddresses/Regions.php
 *  Streets (GET)
 *  Улицы
 *
 *   https://api-preprod.ehealth.gov.ua/uaddresses/streets?
 *   settlement_id=&name=&type=STREET&page=1&page_size=500
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

	$settlementId =  urlencode($_GET['settlementId']);
	$streetType =  urlencode($_GET['streetType']);
	
	$url = "{$api}/api/uaddresses/streets?settlement_id="  .  $settlementId  .  '&type='  .  $streetType  .  '&page=1&page_size=500';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$responsive = curl_exec($ch);
	curl_close($ch);

	$page_one = json_decode($responsive, true);	
	$pages = $page_one ["paging"] ["total_pages"];
	$pdata = $page_one['data'];

	$page_all = [];
	if ($pages > 1)
	for ($i = 1; $i <= $pages; $i++) {
		$url = 'https://api.ehealth.gov.ua/api/uaddresses/streets?settlement_id='  .  $settlementId  .  '&type='  .  $streetType  .  
		'&page='  .  $i  .  '&page_size=500';				
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$responsive = curl_exec($ch);
		curl_close($ch);
		$page = json_decode($responsive, true);	
		$page_data = $page['data'];
		$page_all = array_merge($page_all, $page_data);
	};

	$res = array_merge($pdata + $page_all);
	$json_new = json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

	echo $json_new;	

?>