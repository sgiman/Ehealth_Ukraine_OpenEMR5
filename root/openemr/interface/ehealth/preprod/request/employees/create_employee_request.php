<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Create Employee Request (V1)</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">
<h1 class="tred">Create Employee Request (V1)</h1>
<hr>

<form method="post" action="" enctype="multipart/form-data">
    <p><label>JSON FILE EMPLOYEE (V1):</label></p>
    <p><input type="file" name="file"></p>
    <p><button type="submit" name="send" value="btn_send"><span class="tblue tbold">Send</span></button></p>
</form>

<?php
// --- (2) UPLOAD SIGNED JSON ---
if(!empty($_FILES)) {
	echo '<hr>';
    echo '<h3>UPLOAD JSON EMPLOYEE: </h3> ' . '<pre>';
    print_r($_FILES);
    echo '</pre>';
    $fname = 'UPLOAD/'  .  $_FILES['file']['name'] ;
	move_uploaded_file( $_FILES['file']['tmp_name'], $fname ); 		// переместить загруженный файл
	
	echo '<hr>';
	$employee_json = file_get_contents($fname);
	create_employee($employee_json);
}

//----------------------- 
//       create_employee 
//-----------------------
function create_employee($data) 
{
	require_once '../../../api/Common/config.php';
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",
	"API-key: {$client_secret_mis}"
	));

	$response = curl_exec($ch);
	curl_close($ch);
	//var_dump($response);
	
	// PRINT RESPONSE
	$res = json_decode($response, true);
	echo '<pre>'; 
	echo '<h1>RESPONSE:</h1>'; 
	print_r($res);
	echo '</pre>'; 
	
	///////////////////// COPY EMPLPOYEES TO MYSQL (TEST) ////////////////////
	//copy_employees($res);
	//////////////////////////////////////////////////////////////////////////////
}

	//---------------------------- 
	//    Copy Employee to MySQL 
	//----------------------------
function copy_employees($res) {
		global $db;
		
		// Print Employees List
		echo '<pre>'; 
		print_r($res);
		echo '</pre>'; 
	
		$count_res = trim ($res['paging'] ['total_entries']); 	
/*	
		// ---  Copy to MySQL ---
		mysqli_query($db, "TRUNCATE TABLE z_ehealth_empoyees") or die (mysqli_error($db));   // Clear Table 		
		for ($i=0; $i < $count_res; ++$i) {
			$id = $res['data'][$i]['id'];
			$full_name = $res['data'][$i]['legal_entity']['name'];
			$employee_type = $res['data'][$i]['employee_type'];
			$reg_status = $res['data'][$i]['status'];
			$position = $res['data'][$i]['position'];					// Postion Code
			$position_name = position_name ($position); 	// Postion Name
			$insert = "INSERT INTO `z_ehealth_empoyees`(`id_employee`, `full_name`, `position`, `position_name`, `employee_type`, `reg_status`)
			VALUES ( '$id', '$full_name', '$position', '$position_name', '$employee_type', '$reg_status') ";
			mysqli_query($db, $insert) or die (mysqli_error($db));  		
		}
*/
	
}

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

?>

<hr class="tred">
<h3 class="tblue">END</h3>
<!-- ============================================================= -->
</div>
</body>
</html>
