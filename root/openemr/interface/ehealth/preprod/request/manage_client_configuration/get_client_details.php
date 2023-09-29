<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Get client details</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<?php
// https://ehealthmisapi1.docs.apiary.io/#reference/public.-medical-service-provider-integration-layer/manage-client-configuration/get-client-details
// Manage client configuration  
// "Get client details"
/*---------------------------------------------------------------------------------- 
	Этот сервис возвращает данные клиента.
	Сервис вернет ошибку авторизации (403)
	если запрашиваемый клиент не соответствует контексту

 Scopes required: client:read
-----------------------------------------------------------------------------------*/
	require_once '../../../api/Common/config.php';

	echo '<h2 class="tblue">Get client details</h2>';
	echo '<hr>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$api}/api/clients/{$db_client_id}");
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

