<?php
/*****************************************************************************************
 *  api/Dictionaries/SettlementTypes.php
 *  SETTLEMENT_TYPE (GET)
 *  https://api-preprod.ehealth.gov.ua/api/dictionaries?name=SETTLEMENT_TYPE
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

	$data = 	'{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"SETTLEMENT_TYPE","values":{"CITY":"місто","SETTLEMENT":"селище","TOWNSHIP":"селище міського типу","VILLAGE":"село"}}],"meta":{"code":200,"type":"list"}}';

	echo request_dictionaries($data);

	//$url = "{$api}/api/dictionaries?name=SETTLEMENT_TYPE";
	//echo request_ehealth($url);

?>