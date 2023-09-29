<?php
/*****************************************************************
 * api/LegalEntityData/CreateOrUpdateEmployeeRequest.php
 * Create or Update Employee Request V2 (POST)
 *
 * -------------------------------------------------
 * @package OpenEMR
 * @link    http://www.open-emr.org
 *
 * API EHEALTH version 1.0
 * Writing by sgiman, 2020
******************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
	
	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$employee_json =  $_POST["emplData"];
	$data = $employee_json;
	//echo '******$employee_json[0] = ' . $data;
	
/************************************** NO SPECIALIST ***************************************
	$data = 
	'{
   "employee_request":{
      "division_id":"3f28f0a9-8d88-42cf-948f-a32ab38415c4",
      "position":"P31",
      "start_date":"2003-11-09",
      "employee_type":"ADMIN",
      "party":{
         "first_name":"Олена",
         "last_name":"Петрова",
         "second_name":"Миколаївна",
         "birth_date":"1983-07-07",
         "gender":"FEMALE",
         "no_tax_id":false,
         "tax_id":"1988206582",
         "email":"nefele1088@testbnk.com",
         "documents":[
            {
               "type":"PASSPORT",
               "number":"PС190665",
               "issued_by":"Дніпровським+РУГу+МВС+України",
               "issued_at":"1999-02-01"
            }
         ],
         "phones":[
            {
               "type":"MOBILE",
               "number":"+380661234111"
            }
         ]
      },
      "status":"NEW",
      "legal_entity_id":"30131cf9-836a-48a0-98dc-741ecb1d3cf7"
   }
}';
*******************************************************************************/

/********************************** SPECIALIST *******************************
{
   "employee_request":{
      "division_id":"ac78b141-9844-4049-b115-4093380a4dea",
      "legal_entity_id":"30131cf9-836a-48a0-98dc-741ecb1d3cf7",
      "position":"P93",
      "start_date":"2003-11-09",
      "end_date":"2020-11-16",
      "status":"NEW",
      "employee_type":"SPECIALIST",
      "party":{
         "first_name":"Карен",
         "last_name":"Аванесов",
         "second_name":"Аванесович",
         "birth_date":"1965-05-10",
         "gender":"MALE",
         "no_tax_id":false,
         "tax_id": "2084518214",
         "email":"kiyixi4763@rvemold.com",
         "documents":[
            {
               "type":"PASSPORT",
               "number":"МК190523",
               "issued_by":"Рокитнянським РВ ГУ МВС Київської області",
               "issued_at":"2000-01-20"
            }
         ],
         "phones":[
            {
               "type":"MOBILE",
               "number":"+380503410123"
            }
         ],
         "working_experience": 20,
         "about_myself":"Закінчив всі можливі курси"
      },
      "specialist":{
         "educations":[
            {
               "country":"UA",
               "city":"Київ",
               "institution_name":"Академія Богомольця",
               "issued_date": "2020-02-28",
               "diploma_number":"DC123555",
               "degree":"MASTER",
               "speciality":"Ортопед"
            }
         ],
         "qualifications":[
            {
               "type":"SPECIALIZATION",
               "institution_name":"Академія Богомольця",
               "speciality":"ортопедія і травматологія",
               "issued_date":"1992-10-10",
               "certificate_number":"DK123512",
               "valid_to":"2024-01-01",
               "additional_info":"додаткова інофрмація"
            }
         ],
         "specialities":[
            {
               "speciality":"THERAPIST",
               "speciality_officio":true,
               "level":"FIRST",
               "qualification_type":"AWARDING",
               "attestation_name":"Академія Богомольця",
               "attestation_date":"2017-02-28",
               "valid_to_date":"2020-02-28",
               "certificate_number":"AB/211234"
            }
         ],
         "science_degree":{
            "country":"UA",
            "city":"Київ",
            "degree":"CANDIDATE_OF_SCIENCE",
            "institution_name":"Академія Богомольця",
            "diploma_number":"DD123567",
            "speciality":"ортопедія і травматологія",
            "issued_date":"1993-09-25"
         }
      }
   }
}
*******************************************************************************/
	$data = $employee_json;
    $data_res = json_decode($data, true);
	$data_json = json_encode($data_res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);   
     //echo $data_json;
	 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",
	"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

    $res = json_decode($response, true);
	
	$data = [
		'error' => null,
		'appData' =>$response,
		'hasError' =>false, 
		'hasData' =>true
	];

	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);   
	echo $json;

?>
