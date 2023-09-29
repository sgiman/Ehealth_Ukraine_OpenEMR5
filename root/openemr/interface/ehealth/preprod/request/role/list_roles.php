<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>List Roles</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>

<?php
//  https://ehealthmisapi1.docs.apiary.io/#reference/public.-medical-service-provider-integration-layer/roles
//  Roles 
// "List Roles"
/*---------------------------------------------------------------------------------- 
		Роли используются для упрощения управления доступом пользователей. 
		Области ролей ограничивают список областей, которые может иметь Пользователь. 
		При изменении области ролей это изменение немедленно распространяется 
		на всех пользователей в роли.

		Роли устанавливаются отдельно для каждого Клиента.
-----------------------------------------------------------------------------------*/
	require_once '../../../api/Common/config.php';

	echo 'value = ' .  $db_value  . '<br>';
	echo  'client_secret = '  .  $db_client_secret;
	echo '<hr>';

	echo '<h1 class="tblue">List Roles</h1>';
	echo '<hr>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/admin/roles?name=&page=&page_size=");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"Authorization: Bearer {$db_value}",
		"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);
	//var_dump($response);

	// Print RESULT
	$res = json_decode($response, true);
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 

?>

<hr>
</body>
</html>


