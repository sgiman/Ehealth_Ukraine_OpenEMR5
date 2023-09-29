<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Refresh client secret</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>

<?php
// https://ehealthmisapi1.docs.apiary.io/#reference/public.-medical-service-provider-integration-layer/manage-client-configuration/refresh-client-secret
// Manage client configuration  
// "Refresh client secret"
/*---------------------------------------------------------------------------------- 
	Этот метод используется для обновления секрета клиента (secret / prerpod)
	для указанного клиентского подключения (Get client connections [id]).
	Только владелец юридического лица может запросить новый секрет клиента 
	для своего юридического лица.

Scopes required: connection:refresh_secret
----------------------------------------------------------------------------------*/
	require_once '../../../api/Common/config.php';

	//$id = 'c545e324-54c2-4125-9540-037cbdea96b6';			  			// Get client connections [client_id], [consumer_id], $client_id
	//$connection_id  = '4592b877-ad55-4568-8c17-e146d0bd29ae'; 	// Get client connections [id]

	echo '<h1 class="tblue">Refresh client secret</h1>';
	echo '<hr>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$api}/api/clients/{$db_client_id}/connections/{$db_connection_id}/actions/refresh_secret");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "Authorization: Bearer {$db_value]}",
  "API-key: {$client_secret_mis}"
));

$response = curl_exec($ch);
curl_close($ch);
//var_dump($response);
echo '<hr>'; 
$res = json_decode($response, true);

// Print RESULT
echo '<pre>'; 
print_r($res);
echo '</pre>'; 

?>

<hr>
</body>
</html>

