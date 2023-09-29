<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Create/Update Legal Entity V2</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">
<h1 class="tred">Create/Update Legal Entity V2</h1>
<hr>

<form method="post" action="" enctype="multipart/form-data">
    <p><label>SIGNED JSON FILE (LE V2):</label></p>
    <p><input type="file" name="file"></p>
    <p><button type="submit" name="send" value="btn_send"><span class="tblue tbold">Send</span></button></p>
</form>

<?php
// --- (2) UPLOAD SIGNED JSON ---
if(!empty($_FILES)) {
	echo '<hr>';
    echo '<h3>UPLOAD SIGNED JSON (LE V2) : </h3> ' . '<pre>';
    print_r($_FILES);
    echo '</pre>';
    $fname = 'UPLOAD/'  .  $_FILES['file']['name'] ;
	move_uploaded_file( $_FILES['file']['tmp_name'], $fname ); 		// переместить загруженный файл
	
	// --- (1) BASE64 ---
	echo '<hr>';
	$sign_json = file_get_contents($fname);
	$sign_json_base64 = base64_encode($sign_json);
	create_legal_entity_v2 ($sign_json_base64);									// main request 
}

//----------------------- 
//     create_legal_entity_v2 
//-----------------------
function create_legal_entity_v2($data) 
{
	require_once '../../../api/Common/config.php';
	require_once '../../../api/Common/functions.php';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/v2/legal_entities");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{
	\"signed_legal_entity_request\":  \"{$data}\",
	\"signed_content_encoding\": \"base64\"
	}");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",	
	"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

	$res = json_decode($response, true);
	$error_code = $res['meta']['code'];
	
	if ($error_code == 200) {
		$new_client_id = $res['urgent']['security']['client_id'];
		$new_client_secret  = $res['urgent']['security']['client_secret'];
		$new_redirect_uri  = $res['urgent']['security']['redirect_uri'];
		$new_email = $res['data']['email'];
		$new_edrpou = $res['data']['edrpou'];
		
		$update = "UPDATE `z_ehealth_connect` SET `client_id`= '{$new_client_id}'   WHERE `id`= 2"; 
		mysqli_query($db, $update) or die (mysqli_error($db));  

		$update = "UPDATE `z_ehealth_connect` SET `client_secret`= '{$new_client_secret}'   WHERE `id`= 2"; 
		mysqli_query($db, $update) or die (mysqli_error($db));  

		$update = "UPDATE `z_ehealth_connect` SET `redirect_uri`= '{$new_redirect_uri}'   WHERE `id`= 2"; 
		mysqli_query($db, $update) or die (mysqli_error($db));  

		$update = "UPDATE `z_ehealth_connect` SET `edrpou`= '{$new_edrpou}'   WHERE `id`= 2"; 
		mysqli_query($db, $update) or die (mysqli_error($db));  
	}
	
	//token_refresh ();
	//token_exchange ($scope); 
	
	// PRINT RESPONSE
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 

}

?>

<hr class="tred">
<h3 class="tblue">END</h3>

</div>

</body>
</html>
