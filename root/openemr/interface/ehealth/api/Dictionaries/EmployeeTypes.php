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

/*	
	$data = 	'{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"EMPLOYEE_TYPE","values":{"ADMIN":"адміністратор","ASSISTANT":"асистент","DOCTOR":"лікар","HR":"відділ кадрів","NHS ADMIN":"Адміністратор адмін панелі НСЗУ","NHS ADMIN MONITORING":"Виконуючий функції моніторингу","NHS ADMIN PROGRAM MEDICATION":"Виконуючий функції адміністрування медичних програм","NHS ADMIN REIMBURSEMENT":"Виконуючий функції адміністрування електронних рецептів","NHS ADMIN SIGNER":"Виконуючий функції контрактування","NHS ADMIN VERIFIER":"Виконуючий функції верифікації","NHS LE TERMINATOR":"Виконуючий функцію деактивації закладу","NHS LE VERIFIER":"Виконуючий функції верифікації юридичних осіб","OWNER":"керівник закладу ОЗ","PHARMACIST":"фармацевт","PHARMACY_OWNER":"керівник аптеки","RECEPTIONIST":"працівник реєстратури","SPECIALIST":"спеціаліст"}}],"meta":{"code":200,"type":"list"}}';

	echo request_dictionaries($data);
*/

	
	$data = 	'{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"EMPLOYEE_TYPE","values":{"ADMIN":"адміністратор","ASSISTANT":"асистент","HR":"відділ кадрів","OWNER":"керівник закладу ОЗ","RECEPTIONIST":"працівник реєстратури","SPECIALIST":"спеціаліст"}}],"meta":{"code":200,"type":"list"}}';

	echo request_dictionaries($data);



	//$url = "{$api}/api/dictionaries?name=EMPLOYEE_TYPE";
	//echo request_ehealth($url);



?>