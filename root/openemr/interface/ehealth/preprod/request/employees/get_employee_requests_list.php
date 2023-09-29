<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Get Employee Requests List (V2)</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">
<h1 class="tred">Get Employee Requests List (V2)</h1>
<hr>

<?php 
	require_once '../../../api/Common/config.php';

	echo  '******* db_value = ' . $db_value  .  '<br>';	
	echo  '******* client_secret_mis = ' .  $client_secret_mis  .  '<br><br>';	
	echo  '****** legal_entity_id = ' . $db_client_id . '<br>';
	echo '<hr>';

// ***** TEST *****
//curl_setopt($ch, CURLOPT_URL, "{api}/api/employees?no_tax_id=&tax_id=&party_id=&edrpou=&legal_entity_id=&division_id=&status=&employee_type=&page=&page_size=");

//$legal_entity_id = '30131cf9-836a-48a0-98dc-741ecb1d3cf7';
//$edrpou = '1988206582';
	
	$ch = curl_init();
	//curl_setopt($ch, CURLOPT_URL, "../api/employee_requests?id=&edrpou=&legal_entity_name=&no_tax_id=&status=&page=&page_size=");
	//curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests?edrpou={$db_edrpou}&status=REJECTED&page=1&page_size=100");
	//curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests?edrpou={$db_edrpou}&status=NEW&page=1&page_size=100");
	//curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests?edrpou={$db_edrpou}&status=APPROVED&no_tax_id=false&page=1&page_size=100");
	//curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests?edrpou={$db_edrpou}&status=APPROVED&page=1&page_size=100");
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests?edrpou={$db_edrpou}&status=APPROVED&&page=1&page_size=100");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
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
	print_r($res);
	echo '</pre>'; 

?>

<hr class="tred">
<h3 class="tblue">END</h3>
<!-- ============================================================= -->
</div>
</body>
</html>
