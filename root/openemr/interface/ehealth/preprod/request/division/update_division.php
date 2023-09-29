<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Update division</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>
<body>
<div class="WarpMain">
<h1 class="tred">Update division</h1>
<hr>

<!-- INPUT JSON FILE -->
<form method="post" action="" enctype="multipart/form-data">
    <p><label>Division JSON file:</label></p>
    <p><input type="file" name="file"></p>
    <p><button type="submit" name="send" value="btn_send"><span class="tblue tbold">Send</span></button></p>
</form>

<?php
// --- (1) Upload json ---
if(!empty($_FILES)) {
	echo '<hr>';
    echo '<h3>UPLOAD JSON (DIVISION) : </h3> ' . '<pre>';
    print_r($_FILES);
    echo '</pre>';
    $fname = 'UPLOAD/'  .  $_FILES['file']['name'] ;
	move_uploaded_file( $_FILES['file']['tmp_name'], $fname ); 		// переместить загруженный файл
	echo '<hr>';
	
	// --- (2) Create Division    ---
	$json_division = file_get_contents($fname);
	create_division ($json_division);
}

//----------------------- 
//     create_division 
//-----------------------
function create_division($data) 
{
	require_once '../../../api/Common/config.php';

	echo  '<br>' . '******* db_value = ' . $db_value . '<br>';	
	echo  '******* client_secret_mis = ' .  $client_secret_mis . '<br>';	

	echo '<pre>'; 
	print_r($data);
	echo '</pre>'; 

/*******************************************************************/   
	$id_division = 'a941ac8e-4be7-47be-968d-e904963d7a1c';	
/*******************************************************************/

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/divisions/{$id_division}");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{$data}");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"Authorization: Bearer {$db_value}",
		"API-key: {$client_secret_mis}"
	));

	$response = curl_exec($ch);
	curl_close($ch);
	//var_dump($response);

	// PRINT RESPONSE
	$res = json_decode($response, true);
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
