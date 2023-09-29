<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Create Employee Request V2</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">
<h1 class="tred">Create Employee Request V2</h1>
<hr>

<form method="post" action="" enctype="multipart/form-data">
    <p><label>SIGNED JSON FILE (EMPLOYEE V2):</label></p>
    <p><input type="file" name="file"></p>
    <p><button type="submit" name="send" value="btn_send"><span class="tblue tbold">Send</span></button></p>
</form>

<?php
// --- (2) UPLOAD SIGNED JSON ---
if(!empty($_FILES)) {
	echo '<hr>';
    echo '<h3>UPLOAD SIGNED JSON (EMPLOYEE V2) : </h3> ' . '<pre>';
    print_r($_FILES);
    echo '</pre>';
    $fname = 'UPLOAD/'  .  $_FILES['file']['name'] ;
	move_uploaded_file( $_FILES['file']['tmp_name'], $fname ); 		// переместить загруженный файл
	
	// --- (1) BASE64 ---
	echo '<hr>';
	$sign_json = file_get_contents($fname);
	$sign_json_base64 = base64_encode($sign_json);
	create_employee_v2 ($sign_json_base64);									// main request 
}

//----------------------- 
//     create_employee_v2 
//-----------------------
function create_employee_v2($data) 
{
	require_once '../../../api/Common/config.php';

	echo  '<br>'  .  '******* db_value = ' . $db_value  .  '<br>';	
	echo  '******* client_secret_mis = ' .  $client_secret_mis  .  '<br>';	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/employee_requests");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{
	\"signed_content\":  \"{$data}\",,
	}");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",
	"API-key: {$client_secret_mis}"
	));
	$response = curl_exec($ch);
	curl_close($ch);

	// PRINT RESPONSE
	$res = json_decode($response, true);
	echo '<pre>'; 
	print_r($res);
	echo '</pre>'; 
}

?>

<hr class="tred">
<h3 class="tblue">END</h3>
<!-- ============================================================= -->
</div>
</body>
</html>
