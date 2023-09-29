<?php
error_reporting (-1);
require_once  'config.php' ;

//---------------------
//          Print Result
//---------------------
function print_result ($rdata) {
	// Print RESULT
	echo '<h2>RESULT</h2>';
	echo '<hr> ';
	echo '<pre>'; 	
	print_r($rdata);
}

//---------------------------
//      Request E-Health (single)
//---------------------------
function request_ehealth ($url)  {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	curl_close($ch);
	
	$res = json_decode($response, true);

	// Modification array "values" array for pair "code / name"
	
	$name = $res['data'][0]['name'];
	$is_active = $res['data'][0]['is_active'];
	$labels = $res['data'][0]['labels'];
	$values = $res['data'][0]['values']; 
	
	$new_array_value = [];
	$i=0;
	foreach ($values as $key => $value) {
		$new_array_value[$i] ['code'] = "{$key}";
		$new_array_value[$i] ['name']  = $value;
		$i++;
    }	

	$new_array = ['name'=>$name, 'is_active'=>$is_active, 'labels'=>$labels, 'values'=>$new_array_value];
	$res = json_encode($new_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return $res;
}


//---------------------------
//      Request E-Health (all)
//---------------------------
function request_ehealth_all ($url)  {

/*...*/

}


//---------------------
//      Request Dictionaries
//---------------------
function request_dictionaries ($data)  {	
	$res = json_decode($data, true);

	// Modification array "values" array for pair "code / name"	
	$name = $res['data'][0]['name'];
	$is_active = $res['data'][0]['is_active'];
	$labels = $res['data'][0]['labels'];
	$values = $res['data'][0]['values']; 
	
	$new_array_value = [];
	$i=0;
	foreach ($values as $key => $value) {
		$new_array_value[$i] ['code'] = "{$key}";
		$new_array_value[$i] ['name']  = $value;
		$i++;
    }	
	$new_array = ['name'=>$name, 'is_active'=>$is_active, 'labels'=>$labels, 'values'=>$new_array_value];
	$res = json_encode($new_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return $res;
}

//-------------------------
//         Token Exchange
//-------------------------
function token_exchange ($client_scope)  {
	require_once  'config.php' ;

	global $db;
	global $api;
    global $scope;

	global $db_id;
	global $db_client_id;
	global $db_client_secret;
	global $db_code;
	global $db_redirect_uri;
	global $db_access_token;
	
	//$client_id = 'c545e324-54c2-4125-9540-037cbdea96b6';
	//$client_secret ='768f58eca7dca31bc5e6b9f866470cbf';
	//echo '$db_client_id = ' . $db_client_id . '<br>'; 
	//echo '$db_client_secret = ' . $db_client_secret . '<br>'; 
	
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
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"X-CSRF-Token: {$db_access_token}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

	// -------------------------------- Value -------------------------------
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

	//print_result($res);

}

//------------------------
//          Token Refresh 
//------------------------
function token_refresh ()  {
	require_once  'config.php' ;

	global $db;
	global $api;
    global $scope;

	global $db_id;
	global $db_client_id;
	global $db_client_secret;
	global $db_access_token;
	global $db_refresh_token;
   
    //------------- Refresh Token - Get Value Code ---------------
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

	// -------------------------------- Value -------------------------------
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
	
	//print_result($res);

}

//------------------ 
//     Postion Name
//------------------
function position_name ($code_position) {
		global $api;
		
		$url = "{$api}/api/dictionaries?name=POSITION";
		$dic_position = request_ehealth($url); 	// REQUEST DICTIONARIES

		// Search name position
		$arr_position = json_decode($dic_position, true);			// Positions Array 
		$count_position  = count($arr_position['values']);				// Length Positions Array
	
		for ($i=0; $i < $count_position; ++$i) {
			$dic_pos_code = $arr_position ['values'] [$i] ['code'];		// Position Code
			$dic_pos_name = $arr_position ['values'] [$i] ['name'];	// Position Name		
			if ($dic_pos_code == $code_position) break;
		}
		return $dic_pos_name;    // position name  
}

?>
