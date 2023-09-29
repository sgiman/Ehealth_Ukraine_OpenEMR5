<?php
/*******************************************************************************
 * home/ehealth_login.php
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 *
 * API EHEALTH version 1.0
 * Writing by sgiman, 2020
********************************************************************************/
	require_once  '../../api/Common/config.php';
	require_once  '../../api/Common/functions.php';

	//------------------------
	//              USER SCOPES
	//------------------------
	$user_scope = 'employee:read employee:write division:read division:write secret:refresh legal_entity:read related_legal_entities:read';
	token_exchange ($user_scope);
	
	//------------------------
	//   USER CONNECT
	//  `z_ehealth_user_connect`
	//------------------------
	$query = "SELECT * FROM `z_ehealth_user_connect` WHERE 1 "; 
	$res = mysqli_query($db, $query); 

	if  ($res->num_rows != 0) 
	{
		$row = mysqli_fetch_assoc($res);
		$user_email = trim($row['email']) ;
		$user_client_id = trim($row['legal_entity_id']) ;
		$employee_type = trim($row['employee_type']) ;
	}
	$res->close();

	//------------------------ 
	//           USER URIDIRECT
	//------------------------ 
	$query = "SELECT * FROM `z_ehealth_connect` WHERE  `client_id` = '{$user_client_id}' "; 
	$res = mysqli_query($db, $query); 
	if  ($res->num_rows != 0) 
	{
		$row = mysqli_fetch_assoc($res);
		$user_redirect_uri = trim($row['redirect_uri']) ;	
	}
	$res->close();

	/***********************************************************
	echo  'email = ' . $user_email . '<br>';
	echo  'client_id = ' . $user_client_id . '<br>';
	echo  'employee_type = ' . $employee_type . '<br>';
	echo  'scope = ' . $user_scope . '<br>';
	echo  'auth = ' . $auth . '<br>';
	***********************************************************/
	
	//--- org_id (client_id) ---
	$file = './org_id.txt';
	$org_id = $user_client_id;
	file_put_contents($file, $user_client_id);

	//----------------------- 
	//       REDIRECT to LOGIN
	//----------------------- 
	if ($employee_type == "OWNER") {
		$url = "{$auth}/sign-in?client_id={$user_client_id}&redirect_uri={$user_redirect_uri}&email={$user_email}";
		$location = "Location: {$url}";
		header($location, TRUE, 301);
	    exit();
	}	
	else {
		echo '<center>';
		echo "<h2>Клієнт з поштовою адресою  {$user_email} не власник.</h2>";
		echo '<h3>Вхід заборонено.</h3>';
		echo '</center>';
	}
		
?>