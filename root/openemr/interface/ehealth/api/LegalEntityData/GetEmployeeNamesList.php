<?php
/*****************************************************************
 *  api/LegalEntityData/GetEmployeeNamesList.php
 *  Get Employee Names List (POST)
 *
 * -------------------------------------------------
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
******************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
 
 	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$legal_entity_id =  $_POST['orgId'];
	$legal_entity_type = $_POST['legalEntityType'];
	$email = trim($_POST['email']);

	//----------------------- TEST -----------------------	
	//$email = 'sgimancs@gmail.com';
	//$legal_entity_id = '30131cf9-836a-48a0-98dc-741ecb1d3cf7';
	//$legal_entity_type = 'OWNER';
	//---------------------------------------------------	

	mysqli_query($db, "TRUNCATE TABLE `z_ehealth_user_connect`") or die (mysqli_error($db));     // Clear table `z_ehealth_user_connect`		
	
	$query = "SELECT * FROM `z_ehealth_employees` WHERE `email` LIKE  '%{$email}%' "; 
	$res = mysqli_query($db, $query); 

	if  ($res->num_rows != 0) 
	{
		$row = mysqli_fetch_assoc($res);
		
		$email = $row['email'] ;
		$employee_id = $row['employee_id'] ;
		$full_name = $row['full_name'] ;
		$position = $row['position'];
		$employee_type = $row['employee_type'];
		$position_name = $row['position_name'];
		$status = $row['status'];

		$response = "{\"Users\": [{\"ID\":\"{$employee_id}\", \"FullName\":\"{$full_name}\", \"Position\":\"{$position}\", \"PositionName\" :\"{$position_name}\", \"Status\":\"{$status}\",\"EmployeeType\":\"{$employee_type}\"}]}";

		$data = [
			'error' => null,
			'appData' =>$response,
			'hasError' =>false, 
		'	hasData' =>true
		];
		
		// `z_ehealth_user_connect` -  user data connect
		$insert = "INSERT INTO `z_ehealth_user_connect`(`email`,  `legal_entity_id`, `employee_id`, `full_name`, `position`, `position_name`, `employee_type`, `status`)
		VALUES ( '$email',  '$legal_entity_id',  '$employee_id',  '$full_name',  '$position',  '$position_name',  '$employee_type',  '$status') ";
		mysqli_query($db, $insert) or die (mysqli_error($db));
		
		$file = './name.txt';
		$user_name = $full_name . ' (' . $position_name . ')';
		file_put_contents($file, $user_name);

		//$name = file_get_contents($file);
  		//echo '<br>********USER_NAME = ' . $name . '<br>';

	} 
	else {
		$data = [
			'error' => null,
			'appData' =>"{}",
			'hasError' =>false, 
			'hasData' =>true
		];
	}
		
	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);   
	echo $json;

?>
