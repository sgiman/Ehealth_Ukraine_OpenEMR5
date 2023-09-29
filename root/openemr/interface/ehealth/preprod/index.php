<?php
	//$type_connect = 1;  // MIS (1) / OWNER (2)

	// --  Type Connect  --- 
	require_once "../api/Common/config.php";
	require_once "../api/Common/functions.php";

	$update = "UPDATE `z_ehealth_type_connect` SET `type_connect`= {$type_connect}  WHERE 1"; 
	mysqli_query($db, $update) or die (mysqli_error($db));  

	if( $type_connect == 1) 
	{
		$update = "UPDATE `z_ehealth_type_connect` SET `type_name_connect`= 'MIS'  WHERE 1 ";  		//  type_name_connect for MIS
		mysqli_query($db, $update) or die (mysqli_error($db));  
	}
	else 
	{	
		$update = "UPDATE `z_ehealth_type_connect` SET `type_name_connect`= 'OWNER'  WHERE 1 ";		// type_name_connect for OWNER
		mysqli_query($db, $update) or die (mysqli_error($db));  
	}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>EHEALTH PREPROD TEST</title>

<link rel="stylesheet" type="text/css" href="css/style.css">	
<link rel="stylesheet" type="text/css" href="css/toogle_switch.css">	

<!-- <script src="js/jquery.min.js"></script> -->

</head>
<body>

<div class="WarpMain">
<center>
<h1 class="tred">EHEALTH PREPROD TEST</h1>

</center>

<?php
	require_once '../api/Common/config.php';
	
	echo '<hr>';
	echo 'db_id = ' . $db_id . '<br>';
	echo 'db_type= ' . $db_type . '<br>';
	echo 'db_client_id = ' . $db_client_id . '<br>';
	echo 'db_client_secret = ' . $db_client_secret . '<br>';
	echo 'db_email = ' . $db_email . '<br>';
	echo 'db_redirect_uri = ' . $db_redirect_uri . '<br>';
	//echo 'db_code = ' . $db_code . '<br>';
	//echo 'db_value = ' . $db_value . '<br>';
	//echo 'db_access_token = ' . $db_access_token . '<br>';
	//echo 'db_refresh_token = ' . $db_refresh_token . '<br>';
	//echo 'db_access = ' . $db_access . '<br>';
	//echo 'db_edrpou = ' . $db_edrpou . '<br>';
	//echo '<hr>';

//echo '**** SCOPE = ' . $scope;
echo '<hr>';

token_refresh ();
//token_exchange ($scope); 
$email = htmlspecialchars($db_email);

/*
print<<<HERE
<div align="center">
<a class="ehealth_red"  href="{$auth}/sign-in?client_id={$db_client_id}&email={$email}&redirect_uri={$db_redirect_uri}&response_type=code&scope={$scope}" target="_blank" rel="nofollow">
<span>1. EHEALTH CONNECT</span></a> 
HERE;
*/

print<<<HERE
<div align="center">
<a class="ehealth_red"  href="{$auth}/sign-in?client_id={$db_client_id}&email={$email}&redirect_uri={$db_redirect_uri}&response_type=code&" target="_blank" rel="nofollow">
<span>1. EHEALTH CONNECT</span></a> 
HERE;


?>

<!-- ====================== BUTTONS ===================== -->
<a class="ehealth_blue" 
href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/legal_entity/get_legal_entity_v2.php" 
target="_blank" rel="nofollow">
<span>2.Get Legal Entities V2</span></a>

<!-- 
<a class="ehealth_blue" 
href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/manage_client_configuration/get_clients.php" 
target="_blank" rel="nofollow">
<span>3. Get clients</span></a>

<a class="ehealth_green" 
href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/manage_client_configuration/get_client_connections.php" 
target="_blank" rel="nofollow">
<span>4. Get client connections</span></a>

<a class="ehealth_green" 
href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/manage_client_configuration/update_client_connection.php" 
target="_blank" rel="nofollow">
<span>5. Update client connection</span></a>
-->

<a class="ehealth_grey" 
href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/oauth/exchange_token.php" 
target="_blank" rel="nofollow">
<span>3. Exchange Token</span></a>

<a class="ehealth_grey" 
href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/oauth/refresh_token.php" 
target="_blank" rel="nofollow">
<span>4. Refresh Token</span></a>

</div>
<hr>

<!-- FORM CODE (INPUT)-->
<!--
<center>
<form action= <? echo $_SERVER['PHP_SELF'] ?> method="post">
    <label for="name"><b>C O D E:</b></label><br>
    <input size="50" type="text" name="code" id="code">
    <button type="submit">submit</button>
</form>
</center>
-->

<!-- FORM MODE -->
<center>
<form action= <? echo $_SERVER['PHP_SELF'] ?> method="post">
	<h3>MIS MODE:</h3>
	<label  class="switch">
			<input id="mode" type="checkbox" onclick="ChangeMode()">
			<span class="slider round"></span>
	</label>
</form>
</center>

<br>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
function ChangeMode() {
	var mode = document.getElementById("mode").checked
	//document.cookie="mode=" + mode
	//alert(mode)		

	var server = "/openemr/interface/ehealth";    
	$.ajax ( {
		type: "POST",
		url: server + "/preprod/request/test/mode.php",
		data: {
			"mode" : mode						// REQUEST VAR
		},
		success: function (data) {
			console.log (data)					// RESPONSIVE!!!
		}	
	} );
}
</script>


<?php 
/*
// SAVE CODE
require_once  '../api/Common/config.php';
if(!empty($_POST['code'])) {
	echo '<center>';
	echo 'CODE = ' . $_POST['code'];
	echo '</center>';
	$code = $_POST['code'];
	$update = "UPDATE `z_ehealth_connect` SET `code`= '{$code}'   WHERE `id`= {$id}"; 
	mysqli_query($db, $update) or die (mysqli_error($db));
} else {
	echo '<center>';
	echo 'NO CODE!!!';	
	echo '</center>';
}
*/
?>

<hr>

<!-- ====================== REQUESTS ===================== -->
<div id="header"><h1 class="tblue">EHEALTH API REQUESTS</h1></div>

<!-- PART-1 -->
<div id="wrapper">

<div id="one">
<h3>Legal Entities</h3>
<ul>
	<li><a target="_blank" rel="nofollow" 
	href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/legal_entity/create_legal_entity_v2.php">Create/Update Legal Entity V2</a></li>
	<li><a target="_blank" rel="nofollow" 
	href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/legal_entity/get_legal_entity_v2.php">Get Legal Entities V2</a></li>
	<li><a target="_blank" rel="nofollow" 
	href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/legal_entity/get_legal_entity_by_id_v2.php">Get Legal Entity by ID V2</a></li>
	<li><a target="_blank" rel="nofollow" 
	href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/legal_entity/deactivate_legal_entity.php">Deactivate Legal Entity (SUPER USER)</a></li>
	<li><a target="_blank" rel="nofollow" 
	href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/legal_entity/get_legators_legal_entities.php">Get legators Legal Entities (SUPER USER)</a></li>
</ul>
</div>
    
<div id="two">
<h3>Manage client configuration</h3>
<ul>
  <li><a target="_blank" rel="nofollow" 
  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/manage_client_configuration/get_clients.php">Get clients</a></li>
  <li><a target="_blank" rel="nofollow" 
  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/manage_client_configuration/get_client_details.php">Get client details</a></li>
  <li><a target="_blank" rel="nofollow" 
  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/manage_client_configuration/get_client_connections.php">Get client connections</a></li>
  <li><a target="_blank" rel="nofollow" 
  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/manage_client_configuration/get_client_connection_details.php">Get client connection details</a></li>
  <li><a target="_blank" rel="nofollow" 
  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/manage_client_configuration/update_client_connection.php">Update client connection</a></li>
  <li><a target="_blank" rel="nofollow" 
  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/manage_client_configuration/refresh_client_secret.php">Refresh client secret (NONE) </a></li>
</ul>
</div>
    
<div id="four">
<h3>Role (MIS)</h3>
<ul>
	<li><a target="_blank" rel="nofollow" 
	href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/role/list_roles.php">List Roles</a></li>
	<li><a target="_blank" rel="nofollow" 
	href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/role/get_role_by_id.php">Get Role by ID</a></li>
</ul>
</div>
   
<div id="three">
<h3>oAuth</h3>
<ul>
	<li><a target="_blank" rel="nofollow" 
	href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/oauth/exchange_token.php">Exchange Token</a></li>
	<li><a target="_blank" rel="nofollow" 
	href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/oauth/refresh_token.php">Refresh Token</a></li>
</ul>
</div>

</div>

<!-- PART-2 -->
<div id="wrapper">

<div id="two">
<h3>Division (OWNER)</h3>
<ul>
	<li><a target="_blank" rel="nofollow" href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/division/сreate_division.php">Create Division</a></li>
	<li><a target="_blank" rel="nofollow" href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/division/update_division.php">Update Division</a></li>
	<li><a target="_blank" rel="nofollow"  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/division/get_division.php">Get Division</a></li>
	<li><a target="_blank" rel="nofollow"  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/division/activate_division.php">Activate Division</a></li>
	<li><a target="_blank" rel="nofollow" href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/division/deactivate_division.php">Deactivate Division</a></li>
</ul>
</div>
	
<div id="one">
<h3>Employees (OWNER)</h3>
<ul>
	<li><a target="_blank" rel="nofollow"  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/employees/create_employee_request_v2.php">Create Employee Request V2</a></li>
	<li><a target="_blank" rel="nofollow"  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/employees/create_employee_request.php">Create Employee Request</a></li>
	<li><a target="_blank" rel="nofollow"  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/employees/get_employees_list.php">Get Employees List</a></li>
	<li><a target="_blank" rel="nofollow"  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/employees/get_employee_requests_list.php">Get Employee Requests List</a></li>
	<li><a target="_blank" rel="nofollow"  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/employees/get_employee_request_by_id.php">Get Employee Request by ID</a></li>
	<li><a target="_blank" rel="nofollow"  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/employees/get_employee_details.php">Get Employee Details</a></li>
	<li><a target="_blank" rel="nofollow"  href="https://sgiman.com.ua/openemr/interface/ehealth/preprod/request/employees/deactivate_employee.php">Deactivate Employee</a></li>
</ul>
</div>
	
<div id="three">
	<h3>Reserv2</h3>
</div>
	
<div id="four">
	<h3>Reserv1</h3>
</div>

</div>

<hr>

<div id="footer">

<center>
<p>Copyright 2021</p>
</center>
</div>
</div>

</body>
</html>
