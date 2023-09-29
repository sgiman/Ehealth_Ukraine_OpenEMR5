<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Get Legal Entity by ID V2</title>
<link rel="stylesheet" type="text/css" href="../../css/style.css">	
</head>

<body>
<div class="WarpMain">
<h1 class="tred">Get Legal Entity by ID V2</h1>

<?php
require_once '../../../api/Common/config.php';

global $api;
global $db_value;

echo  '****** $db_client_id = ' . $db_client_id;

echo '<hr>';
echo 'db_client_id = ' .  "{$db_client_id}" .  '<br>';
echo 'db_redirect_uri = ' .  "{$db_redirect_uri}" .  '<br>';
echo 'db_email = ' .  "{$db_email}" .  '<br>';
echo 'db_value = ' .  "{$db_value}" .  '<br>';
echo 'api = ' .  "{$api}" .  '<br>';
echo '<hr>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$api}/api/v2/legal_entities/{$db_client_id}");
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

<hr class="tred">
<h3 class="tblue">END</h3>

</div>

</body>
</html>
