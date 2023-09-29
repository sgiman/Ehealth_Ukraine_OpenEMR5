<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Get Division</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">
<h1 class="tred">Get Division</h1>
<hr>

<?php 
	require_once '../../../api/Common/config.php';

	$ch = curl_init();
	//curl_setopt($ch, CURLOPT_URL, "../api/divisions?ids=&name=&legal_entity_id=&type=&status=&page=&page_size=");
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/divisions?page=1&page_size=100");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"Authorization: Bearer {$db_value}",
		"API-key: {$client_secret_mis}"
	));

	$response = curl_exec($ch);
	curl_close($ch);
	//var_dump($response)

	// Print Response
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
