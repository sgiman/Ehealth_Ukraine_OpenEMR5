<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Refresh Token for Access Token extension</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">

<?php
	require_once '../../../api/Common/config.php';
	global $db_code;

	echo '<pre>';
	echo '****** ACCESS *****'  . '<br>';
	echo "<b>Code = </b>" .  $db_code . '<br>';
	echo "<b>Client_ID = </b>" .  $db_client_id . '<br>';
	echo "<b>Client_Secret = </b>" .  $db_client_secret . '<br>';
	echo "<b>Redirect_URI = </b>" .  $db_redirect_uri  . '<br><br>';
	echo '</pre>';

	echo '<hr>';
	echo '<h1 class="tblue">Refresh Token for Access Token extension</h1>';

	// --- CURL_INIT ---
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/oauth/tokens");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);

	curl_setopt($ch, CURLOPT_POSTFIELDS, "{
		\"token\": {
		\"client_id\": \"{$db_client_id}\",
		\"client_secret\": \"{$db_client_secret}\",
		\"refresh_token\": \"{$db_refresh_token}\",
		\"grant_type\": \"refresh_token\"
		}
	}");

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json"
	));

	$response = curl_exec($ch);
	curl_close($ch);

	/*================
				PRINT DATA
	================*/
	$res = json_decode($response, true);
	$code_error = $res['meta']['code'];
	
	if ($code_error == 201) {
		$value = $res['data']['value']; 
		$user_id = $res['data']['user_id']; 
	}
	
	// -- Update Connect --- 
	if ($code_error == 201) {
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

	if ($code_error == 422) 
		echo '<h4>' . 'MESSAGE ERROR = ' . '<span class="tblue">' .  $res['error']['message']  .  '</span>' . '</h4>';	
	
	if ($code_error == 201) 
		echo '<h1>' . 'VALUE = ' . '<span class="tred">' .  $value  .  '</span>' . '</h1>';	
	
	echo '<hr>';

?>

</div>
</body>
</html>
