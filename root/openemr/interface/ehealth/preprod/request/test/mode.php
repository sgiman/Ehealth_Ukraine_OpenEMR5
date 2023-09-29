<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');

require_once "../../../api/Common/config.php";
require_once "../../../api/Common/functions.php";

$mode = $_POST['mode'];
echo 'mode = ' .  $_POST['mode'] ; 

$type_connect = ($mode === 'true') ? 1 :  2;
echo '  type_connect =  ' . $type_connect;

//$type_connect = 1;  // MIS (1) / OWNER (2)

// ---  Type Connect (MySQL) --- 
$update = "UPDATE `z_ehealth_type_connect` SET `type_connect`= {$type_connect}  WHERE 1"; 
mysqli_query($db, $update) or die (mysqli_error($db));  

if( $type_connect === 1) 
{
	$update = "UPDATE `z_ehealth_type_connect` SET `type_name_connect`= 'MIS'  WHERE 1 ";  		//  type_name_connect for MIS
	mysqli_query($db, $update) or die (mysqli_error($db));  
	echo "  MIS";
}
else 
{	
	$update = "UPDATE `z_ehealth_type_connect` SET `type_name_connect`= 'OWNER'  WHERE 1 ";		// type_name_connect for OWNER
	mysqli_query($db, $update) or die (mysqli_error($db));  
	echo "  OWNER";
}

token_refresh() ;
//exit ($_POST['mode']);

?>
