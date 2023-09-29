<?php
/***********************************************************************************
 *  api/Dictionaries/DivisionTypes.php
 *  Division Types (GET)
 *
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
***********************************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
	
	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$data = 	'{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"DIVISION_TYPE","values":{"AMBULANT_CLINIC":"Амбулаторія","CLINIC":"Філія (інший відокремлений підрозділ)","DRUGSTORE":"Аптека","DRUGSTORE_POINT":"Аптечний пункт","FAP":"ФАП","LICENSED_UNIT":"Місце провадження діяльності"}}],"meta":{"code":200,"type":"list"}}';

	echo request_dictionaries($data);

	//$url = "{$api}/api/dictionaries?name=DIVISION_TYPE";
	//echo request_ehealth($url);

?>