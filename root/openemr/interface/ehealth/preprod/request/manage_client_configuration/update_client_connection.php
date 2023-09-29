<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<html>
<head>
<meta charset="utf-8">
<title>Update client connection</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>

<?php
// https://ehealthmisapi1.docs.apiary.io/#reference/public.-medical-service-provider-integration-layer/manage-client-configuration/update-client-connection
// Manage client configuration  
// "Update client connection"
/*---------------------------------------------------------------------------------- 
(!!!)
C помощью этого метода можно обновить только rediгect URI  

Scopes required: connection:write
----------------------------------------------------------------------------------*/
	require_once '../../../api/Common/config.php';

	// $id = 'c545e324-54c2-4125-9540-037cbdea96b6';			  	            	// Get client connections [client_id], [consumer_id], $client_id
	// $connection_id  = '4592b877-ad55-4568-8c17-e146d0bd29ae'; 		// Get client connections [id]
	// $db_redirect_uri = 'https://test114.com';
	// $db_redirect_uri = 'https://sgiman.com.ua/openemr/interface/ehealth/auth.php';
	
	if (isset ($db_connection_id)) {
		echo '<h1 class="tblue">Update client connection</h1>';
	echo '<hr>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/clients/{$db_client_id}/connections/{$db_connection_id}");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{
	\"redirect_uri\": \"{$db_redirect_uri}\"
	}");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"Authorization: Bearer {$db_value}",
		"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

	$res = json_decode($response, true);

	// Print RESULT
	echo '<h3>Log Update Client Connection </h3>';
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 
}
else {
	echo '<h1 class="tred">First use Get Client Connections!!!</h1>';
	exit;
}
	
?>

<div class="tred"> <hr> </div>
</body>
</html>
