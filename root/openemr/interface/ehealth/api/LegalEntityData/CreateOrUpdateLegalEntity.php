<?php
/*****************************************************************
 * api/LegalEntityData/CreateOrUpdateLegalEntity.php
 * Create or Update Legal Entity (POST)
 *
 * -------------------------------------------------
 * @package OpenEMR
 * @link    http://www.open-emr.org
 *
 * API EHEALTH version 1.0
 * Writing by sgiman, 2020
******************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
	
	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$sign_json =  $_POST["signedData"];
	$data_sign = $sign_json[1]; 									// signed sata (1)
	$data_nosign = $sign_json[0];								// nosign data (0)
 	 	 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "{$api}/api/v2/legal_entities");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{
	\"signed_legal_entity_request\":  \"{$data_sign}\",
	\"signed_content_encoding\": \"base64\"
	}");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Content-Type: application/json",
	"Authorization: Bearer {$db_value}",		
	"API-key: {$client_secret_mis}"									
	));
	$response = curl_exec($ch);
	curl_close($ch);

	$data = [
		'error' => null,
		'appData' =>$response,
		'hasError' =>false, 
		'hasData' =>true
	];
 
	$res = json_decode($response, true);
	$error_code = $res['meta']['code'];

	if ($error_code == 200) {
		$new_client_id = $res['urgent']['security']['client_id'];
		$new_client_secret  = $res['urgent']['security']['client_secret'];
		//$new_redirect_uri  =  htmlspecialchars($res['urgent']['security']['redirect_uri']);
		//$new_redirect_uri  = $res['urgent']['security']['redirect_uri'];
		$new_email = $res['data']['email'];
		$new_edrpou = $res['data']['edrpou'];
	
		$update = "UPDATE `z_ehealth_connect` SET `client_secret`= '{$new_client_secret}'   WHERE `id`= 2"; 
		mysqli_query($db, $update) or die (mysqli_error($db));  
	
		$update = "UPDATE `z_ehealth_connect` SET `client_id`= '{$new_client_id}'   WHERE `id`= 2"; 
		mysqli_query($db, $update) or die (mysqli_error($db));  

		$update = "UPDATE `z_ehealth_connect` SET `edrpou`= '{$new_edrpou}'   WHERE `id`= 2"; 
		mysqli_query($db, $update) or die (mysqli_error($db));  

		//$update = "UPDATE `z_ehealth_connect` SET `redirect_uri`= '{$new_redirect_uri}'   WHERE `id`= 2"; 
		//mysqli_query($db, $update) or die (mysqli_error($db));  
		
		token_exchange ('');
		//token_refresh ();			

	}

	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);   
	echo $json;

?>
