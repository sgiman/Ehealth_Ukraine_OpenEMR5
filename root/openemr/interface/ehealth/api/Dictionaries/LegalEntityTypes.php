<?php
/*****************************************************************************************
 *  api/Dictionaries/LegalEntityTypes.php
 *  (001) LEGAL_ENTITY_TYPE (GET)
 *  https://api-preprod.ehealth.gov.ua/api/dictionaries?name=LEGAL_ENTITY_TYPE
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
	
	token_refresh();
	
	$url = "{$api}/api/dictionaries?name=LEGAL_ENTITY_TYPE";

	echo request_ehealth($url);

?>