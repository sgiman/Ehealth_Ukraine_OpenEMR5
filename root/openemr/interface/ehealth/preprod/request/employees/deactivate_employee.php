<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Deactivate Employee (V1)</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">
<h1 class="tred">Deactivate Employee (V1)</h1>
<hr>

<?php 
	require_once '../../../api/Common/config.php';

	//*******************************************************************************************
    //$id = '7572b217-5387-44f4-88e1-c22bfc0f9c55';
    $id = 'ef96043f-f34d-4497-adac-dda6c2226f0b';
	//$id = '397b5566-5bc9-42e6-9a11-c0842d4605f4';
	//$id = '1e1d28a8-1663-494d-b107-fdb2150c6f71';
	//$id = '86878607-4e71-4aa9-9084-b466581d93f2';
	//$id = '721e1b19-597b-44cb-aa67-8fb867bc6240';
	//*******************************************************************************************

	
	/*-------------------------------------------------
		Use this method to deactivate employee. 
		OWNER and PHARMACY_OWNER can't be deactivated using this method. (the 409 error with message 'Owner can't be deactivated'). 
		OWNER and PHARMACY_OWNER deactivates when corresponding legal_entity deactivates. Employee deactivation:
		
		Revoke role from user

		1. Deactivate declarations

		2. Update employee parameters:

		3. change status=DISMISSED and end_date=now
	---------------------------------------------------*/

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employees/{$id}/actions/deactivate");
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

	// Print
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
