<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Get Employee Request by ID (V2)</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">
<h1 class="tred">Get Employee Request by ID (V2)</h1>
<hr>

<?php
	require_once '../../../api/Common/config.php';
	
	echo  '<br>' . '******* db_value = ' . $db_value . '<br>';	
	echo  '******* client_secret_mis = ' .  $client_secret_mis . '<br>';	

	//****************************** TEST *******************************
	// for "Get Employee Requests List" (V2)
	//$employee_id = '56f58afe-f65f-45b5-a1e0-cf876be4a7a9';
		$employee_id = 'ef96043f-f34d-4497-adac-dda6c2226f0b';
	//********************************************************************

	echo  '******* $employee_id = '  .  $employee_id  .  '<br>';	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests/{$employee_id}");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
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
	
?>

<hr class="tred">
<h3 class="tblue">END</h3>
<!-- ============================================================= -->
</div>
</body>
</html>
