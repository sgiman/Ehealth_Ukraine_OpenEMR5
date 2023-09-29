<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Deactivate division</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">
<h1 class="tred">Deactivate division</h1>
<hr>

<?php 
	require_once '../../../api/Common/config.php';

	//***********************************************************************
		$id_division = '622d1ba4-3852-470d-aa6b-8dd7ca2e7b39';
	//***********************************************************************

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/divisions/{$id_division}/actions/deactivate");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
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

</div>

</body>
</html>
