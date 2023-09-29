<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Get client connection details</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>

<?php
// https://ehealthmisapi1.docs.apiary.io/#reference/public.-medical-service-provider-integration-layer/manage-client-configuration/get-client-connection-details
// Manage client configuration  
// "Get client connection details"
/*---------------------------------------------------------------------------------- 
Scopes required: connection:read
-----------------------------------------------------------------------------------*/
	require_once '../../../api/Common/config.php';

	echo '<h1 class="tblue">Get client connection details</h1>';
	echo '<hr>';

	if (isset ($db_connection_id)) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "{$api}/api/clients/{$db_client_id}/connections/{$db_connection_id}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"Authorization: Bearer {$db_value}",
		"API-key: {$client_secret_mis}"
		));
		$response = curl_exec($ch);
		curl_close($ch);

		$res = json_decode($response, true);
		$connection_id = $res['data']['id'];
		$update = "UPDATE `z_ehealth_connect` SET `connection_id`= '{$connection_id}'   WHERE `id`= {$db_id}"; 
		mysqli_query($db, $update) or die (mysqli_error($db));  
		echo  'connection_id = ' . $connection_id;

	}	
	else 
	{
		echo '<h1 class="tred">First use Get Client Connections!!!</h1>';
		exit;
	}

	// Print RESULT
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 

?>

<hr>
</body>
</html>

