<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Exchange Token</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	

</head>
<body>

<div class="WarpMain">

<?php
	require_once '../../../api/Common/config.php';
	global $db_code;
	
	/*
	$scope = 'client:read connection:delete connection:read connection:refresh_secret connection:write division:activate division:deactivate division:details division:read division:write employee:deactivate employee:details employee:read employee:write employee_request:approve employee_request:read employee_request:reject employee_request:write employee_role:read employee_role:write legal_entity:read related_legal_entities:read secret:refresh';
	*/
	
	//$scope = 'division:read division:write employee:read employee:write ';
	
	echo '****** ACCESS *****'  . '<br>';
	echo "<b>DB Code = </b>" .  $db_code . '<br>';
	echo "<b>DB Client_ID = </b>" .  $db_client_id . '<br>';
	echo "<b>DB Client_Secret = </b>" .  $db_client_secret . '<br>';
	echo "<b>DB Redirect_URI = </b>" .  $db_redirect_uri  . '<br>';
	echo "<b>Scope = </b>" .  "{$scope}"  . '<br><br>';

	echo '<hr>';
	echo '<h1 class="tblue">Exchange Token</h1>';

	//------------------------------------------------------------------
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/oauth/tokens");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);

	curl_setopt($ch, CURLOPT_POSTFIELDS, "{
		\"token\": {
		\"client_id\": \"{$db_client_id}\",
		\"client_secret\": \"{$db_client_secret}\",
		\"code\": \"{$db_code}\",
		\"grant_type\": \"authorization_code\",
		\"redirect_uri\": \"{$db_redirect_uri}\"
		}
	}");

echo '********client_secret_mis =' . $client_secret_mis . '<br>';
echo '********db_client_secret =' . $db_client_secret . '<br>';
echo '********db_code =' . $db_code . '<br>';
echo '********db_value =' . $db_value;

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"X-CSRF-Token: {$db_value}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

	/*================
				PRINT DATA
	================*/
	$res = json_decode($response, true);
	$code_error = $res['meta']['code'];
	
	if ($code_error == 201) {
		$refresh_token = $res['data']['details']['refresh_token'];
		$value = $res['data']['value']; 
		$user_id = $res['data']['user_id']; 
	}
	
	// -- Update Connect --- 
	if($code_error == 201) {
		$update = "UPDATE `z_ehealth_connect` SET `refresh_token`= '{$refresh_token}'   WHERE `id`= {$db_id}"; 
		mysqli_query($db, $update) or die (mysqli_error($db));  

		$update = "UPDATE `z_ehealth_connect` SET `value`= '{$value}'   WHERE `id`= {$db_id}"; 
		mysqli_query($db, $update) or die (mysqli_error($db));  

		$update = "UPDATE `z_ehealth_connect` SET `user_id`= '{$user_id}'   WHERE `id`= {$db_id}"; 
		mysqli_query($db, $update) or die (mysqli_error($db));  
	}

	echo '<hr>';
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 
	echo '<hr>';

	if ($code_error == 401) 
		echo '<h4>' . 'MESSAGE ERROR = ' . '<span class="tblue">' .  $res['error']['message']  .  '</span>' . '</h4>';	
	
	if ($code_error == 201) 
		echo '<h1>' . 'VALUE = ' . '<span class="tred">' .  $value  .  '</span>' . '</h1>';	

	echo '<hr>';

?>

</div>
</body>
</html>

