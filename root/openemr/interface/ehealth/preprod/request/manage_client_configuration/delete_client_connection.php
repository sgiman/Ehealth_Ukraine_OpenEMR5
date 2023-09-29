<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Get client connections</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>

<?php
// https://ehealthmisapi1.docs.apiary.io/#reference/public.-medical-service-provider-integration-layer/manage-client-configuration/get-client-connections
// Manage client configuration
// "Get client connections"
/*---------------------------------------------------------------------------------- 
	Сервис возвращает список подключений для указанного клиента
	Сервис вернет ошибку авторизации (403)
	если запрашиваемый клиент не соответствует контексту

 Scopes required: connection:read
-----------------------------------------------------------------------------------*/
	require_once '../../../api/Common/config.php';

	echo '<h1 class="tblue">Get client connections</h1>';
	echo '<hr>';

if ($id == 1) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/clients/{$db_client_id}/connections");
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
	$connection_id = $res['data'][0]['id'];

	$update = "UPDATE `z_ehealth_connect` SET `connection_id`= '{$connection_id}'   WHERE `id`= 1"; 
	mysqli_query($db, $update) or die (mysqli_error($db));  
	echo  'connection_id = ' . $connection_id;

	$update = "UPDATE `z_ehealth_connect` SET `connection_id`= '{$connection_id}'   WHERE `id`= 2"; 
	mysqli_query($db, $update) or die (mysqli_error($db));  
	echo  'connection_id = ' . $connection_id;

} 
else {
	$query = "SELECT `connection_id`  FROM `z_ehealth_connect` WHERE id = 2"; 
	$res = mysqli_query($db, $query); 
	echo '<div class="tgreen">';
	$row = mysqli_fetch_assoc($res) 
	if (isset ($row['connection_id'])) {
		$db_connection_id = $row['connection_id'];
		echo '**** db_connection_id = ' . $db_connection_id;
	}
 };
 
echo '<h1>MYSQL:</h1>';
echo 'db_id = '  .  $db_id  .  '<br>'; 

}
	

// Print RESULT
echo '<hr>';
echo '<pre>'; 
print_r($res);
echo '</pre>'; 
echo '<hr>';

?>

</body>
</html>
