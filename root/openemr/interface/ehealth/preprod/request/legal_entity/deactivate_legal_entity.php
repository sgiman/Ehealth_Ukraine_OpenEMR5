<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Deactivate Legal Entity</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">
<h1 class="tred">Deactivate Legal Entity</h1>
<hr>
<h1>PREPROD (Deactivate Legal Entity):</h1>
<hr>

<?php
	require_once '../../../api/Common/config.php';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/{$db_client_id}/actions/deactivate");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",
	"API-key: {$db_client_secret}"
	));

	$response = curl_exec($ch);
	curl_close($ch);
	$res = json_decode($response, true);

	// PRINT RESPONSE
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
