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
</head>
<body>

<?php
// https://ehealthmisapi1.docs.apiary.io/#reference/public.-medical-service-provider-integration-layer/roles/get-role-by-id
// Roles 
// "Get Role by ID"
/*--------------------------------------------------------------------------------------------------- 
Роли используются для упрощения управления доступом пользователей. 
Области ролей ограничивают список областей, которые может иметь Пользователь. 
При изменении области ролей это изменение немедленно распространяется на всех пользователей в роли.

Роли устанавливаются отдельно для каждого Клиента.
----------------------------------------------------------------------------------------------------*/
	require_once '../../../api/Common/config.php';

	echo 'value = ' . $db_value  . '<br>';
	echo  'client_secret = ' . $db_client_secret;
	echo '<hr>';

	echo '<h1 class="tblue">Get Role by ID</h1>';
	echo '<hr>';

//****************************** TEST *******************************
//$employee_id = 'da763dd6-b0a0-4925-bf89-cd38800551a1';

$employee_id = 'ac518af4-81ac-4d82-98fa-6e07afbea2cb';
//$employee_id = 'ef96043f-f34d-4497-adac-dda6c2226f0b';
//$employee_id = '19eb415c-ac31-4bd2-8385-e20623febc20';
//$employee_id = '06b24497-0894-4dd3-8b84-2daa8a63bb41';
//$employee_id = 'b67c06ba-f489-46cb-95aa-4e1a34590d70';
//$employee_id = 'f5ede152-e457-4278-8713-f7028fa3ab1c';
//$employee_id = '2d0bebd6-b681-4d0a-9ef4-c5738eea1d59';
//********************************************************************

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/admin/roles/{$employee_id}");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"Authorization: Bearer {$db_value}",
		"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

	// Print RESULT
	$res = json_decode($response, true);
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 

?>

<hr>
</body>
</html>


