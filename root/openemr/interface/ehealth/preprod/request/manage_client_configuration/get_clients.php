<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Get clients</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
<!--<link rel=“canonical” href=“https://sgiman.com.ua/ehealth/preprod/request/manage_client_configuration/get_clients.php” />-->
</head>
<body>
<?php
// https://ehealthmisapi1.docs.apiary.io/#reference/public.-medical-service-provider-integration-layer/manage-client-configuration/get-clients
// Manage client configuration  
// "Get clients"
/*---------------------------------------------------------------------------------- 
	Эта служба возвращает список клиентов, отфильтрованных по контексту,
	в зависимости от типа клиента:
		- Mithril Admin - получает все записи клиента без контекста
		- MSP - получает только свой клиент
		- MIS - получает только свой клиент
	
	Scopes required: client:read
-----------------------------------------------------------------------------------*/
	require_once '../../../api/Common/config.php';

	echo 'value = ' .  $db_value  . '<br>';
	echo  'client_id = ' . $db_client_id . '<br>';
	echo  'client_secret = ' . $db_client_secret  . '<br>';
	echo  'redirect_uri = ' . $db_redirect_uri. '<br>';
	echo  'edrou = ' . $db_edrpou;

	echo '<hr>';
	echo '<h1 class="tblue">Get client connections</h1>';
	echo '<hr>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/clients");
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

	// Print RESULT
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 

?>

<hr>
</body>
</html>


