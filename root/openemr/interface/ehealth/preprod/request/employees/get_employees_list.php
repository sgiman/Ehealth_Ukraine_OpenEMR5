<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Get Employees List (V1)</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">
<h1 class="tred">Get Employees List (V1)</h1>
<hr>

<?php 
	require_once '../../../api/Common/config.php';
	require_once '../../../api/Common/functions.php';
	//api/employees?no_tax_id=&tax_id=&party_id=&edrpou=&legal_entity_id=&division_id=&status=&employee_type=&page=&page_size=&page=1&page_size=100
	//$legal_entity_id = '30131cf9-836a-48a0-98dc-741ecb1d3cf7';
	//$edrpou = '1988206582';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employees?legal_entity_id={$db_client_id}&status=APPROVED&page=&page_size=&page=1&page_size=100");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",
	"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);
	
	///////////////////// COPY EMPLPOYEES TO MYSQL (TEST) ////////////////////
	$res = json_decode($response, true);
	copy_employees($res);
	//////////////////////////////////////////////////////////////////////////////

	//---------------------------- 
	//    Copy Employees to MySQL 
	//----------------------------
	function copy_employees($res) {
		global $db;
		
		// Print Employees List
		echo '<pre>'; 
		print_r($res);
		echo '</pre>'; 
	
/*------------------------------------------------------------------------------------------------ 
		// ---  Copy to MySQL ---
		$count_res = trim ($res['paging'] ['total_entries']); 	
		mysqli_query($db, "TRUNCATE TABLE z_ehealth_empoyees") or die (mysqli_error($db));   // Clear Table 		
		for ($i=0; $i < $count_res; ++$i) {
			$id_employee = $res['data'][$i]['id'];
			$full_name = $res['data'][$i]['legal_entity']['name'];
			$employee_type = $res['data'][$i]['employee_type'];
			$reg_status = $res['data'][$i]['status'];
			$position = $res['data'][$i]['position'];					
			$position_name = position_name ($position); 		
			
			$fisrt_name = $res['data'][$i]['party']['first_name'];
			$second_name = $res['data'][$i]['party']['second_name'];
			$last_name = $res['data'][$i]['party']['last_name'];			
			
			$insert = "INSERT INTO `z_ehealth_empoyees`(`id_employee`,  `full_name`, `first_name`, `second_name`, `last_name`, `position`, `position_name`, `employee_type`, `reg_status`)
			VALUES ( '$id_employee', '$full_name',  '$fisrt_name',  '$second_name',  '$last_name',  '$position',  '$position_name',  '$employee_type',  '$reg_status') ";
			mysqli_query($db, $insert) or die (mysqli_error($db));  		
		}
-------------------------------------------------------------------------------------------------*/			
	
	}

/*
	//------------------ 
	//     Postion Name
	//------------------
	function position_name ($code_position) {
		global $api;
		
		$url = "{$api}/api/dictionaries?name=POSITION";
		$dic_position = request_ehealth($url); 	// REQUEST DICTIONARIES

		// Search name position
		$arr_position = json_decode($dic_position, true);			// Positions Array 
		$count_position  = count($arr_position['values']);				// Length Positions Array
	
		for ($i=0; $i < $count_position; ++$i) {
			$dic_pos_code = $arr_position ['values'] [$i] ['code'];		// Position Code
			$dic_pos_name = $arr_position ['values'] [$i] ['name'];	// Position Name		
			if ($dic_pos_code == $code_position) break;
		}

		return $dic_pos_name;    // position name  
	}
	*/
	
?>

<hr class="tred">
<h3 class="tblue">END</h3>

</div>
</body>
</html>
