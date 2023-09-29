<?php
/**********************************************************************************
 *  api/Dictionaries/LegalForms.php
 *  (003) LEGAL_FORM (GET)
 *  https://api-preprod.ehealth.gov.ua/api/dictionaries?name=LEGAL_FORM
 *
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
************************************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');

	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$data = 	'{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"LEGAL_FORM","values":{"120":"ПРИВАТНЕ ПІДПРИЄМСТВО","140":"ДЕРЖАВНЕ ПІДПРИЄМСТВО","145":"КАЗЕННЕ ПІДПРИЄМСТВО","150":"КОМУНАЛЬНЕ ПІДПРИЄМСТВО","160":"ДОЧІРНЄ ПІДПРИЄМСТВО","193":"СПІЛЬНЕ ПІДПРИЄМСТВО","230":"АКЦІОНЕРНЕ ТОВАРИСТВО","231":"ВІДКРИТЕ АКЦІОНЕРНЕ ТОВАРИСТВО","232":"ЗАКРИТЕ АКЦІОНЕРНЕ ТОВАРИСТВО","235":"ДЕРЖАВНА АКЦІОНЕРНА КОМПАНІЯ (ТОВАРИСТВО)","240":"ТОВАРИСТВО З ОБМЕЖЕНОЮ ВІДПОВІДАЛЬНІСТЮ","250":"ТОВАРИСТВО З ДОДАТКОВОЮ ВІДПОВІДАЛЬНІСТЮ","425":"ДЕРЖАВНА ОРГАНІЗАЦІЯ (УСТАНОВА, ЗАКЛАД)","430":"КОМУНАЛЬНА ОРГАНІЗАЦІЯ (УСТАНОВА, ЗАКЛАД)","910":"ПІДПРИЄМЕЦЬ-ФІЗИЧНА ОСОБА","995":"ІНШІ ОРГАНІЗАЦІЙНО-ПРАВОВІ ФОРМИ"}}],"meta":{"code":200,"type":"list"}}';

	echo request_dictionaries($data);

	//$url = "{$api}/api/dictionaries?name=LEGAL_FORM";
	//echo request_ehealth($url);

?>