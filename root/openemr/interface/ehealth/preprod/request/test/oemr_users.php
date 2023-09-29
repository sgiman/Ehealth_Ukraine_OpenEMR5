<?php
error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');

require_once "../../../api/Common/config.php";
require_once "../../../api/Common/functions.php";

$query = "SELECT * FROM `users` WHERE  1"; 
$res = mysqli_query($db, $query); 
$i = 0;
while ($row = mysqli_fetch_assoc($res)) {
$fname = $row['fname']; 
$mname = $row['mname']; 
$lname = $row['lname'];

$username = $row['username'];
$password = $row['password']; 
$authorized = $row['authorized']; 
$info = $row['info'];
$source = $row['source']; 
$suffix = $row['suffix']; 
$federaltaxid = $row['federaltaxid'];
$federaldrugid = $row['federaldrugid']; 
$upin = $row['upin'];
$facility = $row['facility'];
$facility_id = $row['facility_id'];
$see_auth = $row['see_auth'];
$active = $row['active'];
$npi = $row['npi'];
$title = $row['title'];
$specialty = $row['specialty'];
$billname = $row['billname'];
$email = $row['email'];
$email_direct = $row['email_direct'];
$url = $row['url'];
$assistant = $row['assistant'];
$organization = $row['organization'];
$valedictory = $row['valedictory'];
$street = $row['street'];
$streetb = $row['streetb'];
$city = $row['city'];
$state = $row['state'];
$zip = $row['zip'];
$street2 = $row['street2'];
$streetb2 = $row['streetb2'];
$city2 = $row['city2'];
$state2 = $row['state2'];
$zip2 = $row['zip2'];
$phone = $row['phone'];
$fax = $row['fax'];
$phonew = $row['phonew1'];
$phonew2 = $row['phonew2'];
$phonecell = $row['phonecell'];
$notes = $row['notes'];
$cal_ui = $row['cal_ui'];
$taxonomy = $row['taxonomy'];
$calendar = $row['calendar'];
$abook_type = $row['abook_type'];
$pwd_expiration_date = $row['pwd_expiration_date'];
$pwd_history1 = $row['pwd_history1'];
$pwd_history2 = $row['pwd_history2'];
$default_warehouse = $row['default_warehouse'];
$irnpool = $row['irnpool'];
$state_license_number = $row['state_license_number'];
$weno_prov_id = $row['weno_prov_id'];
$newcrop_user_role = $row['newcrop_user_role'];
$cpoe = $row['cpoe'];
$physician_type = $row['physician_type'];
$main_menu_role = $row['main_menu_role'];
$patient_menu_role = $row['patient_menu_role'];
 
if ($password == 'NoLongerUsed') {
$i++;
echo '<br><hr>';
echo $i; 
echo '<hr>';
echo "fname =  "  .  $row['fname'] . '<br>'; 
echo "mname =  "  .  $row['mname'] . '<br>'; 
echo "lname =  "  .  $row['lname'] . '<br>';

echo "username =  "  .  $row['username'] . '<br>';
echo "password =  "  .  $row['password'] . '<br>'; 
echo "authorized =  "  .  $row['authorized'] . '<br>'; 
echo "info =  "  .  $row['info'] . '<br>';
//echo "source =  "  .  $row['source'] . '<br>'; 
//echo "suffix =  "  .  $row['suffix'] . '<br>'; 
echo "federaltaxid =  "  .  $row['federaltaxid'] . '<br>';
echo "federaldrugid =  "  .  $row['federaldrugid'] . '<br>'; 
echo "upin =  "  .  $row['upin'] . '<br>';
echo "facility =  "  .  $row['facility'] . '<br>';
//echo "facility_id =  "  .  $row['facility_id'] . '<br>';
//echo "see_auth =  "  .  $row['see_auth'] . '<br>';
echo "active =  "  .  $row['active'] . '<br>';
echo "npi =  "  .  $row['npi'] . '<br>';
//echo "title =  "  .  $row['title'] . '<br>';
echo "specialty =  "  .  $row['specialty'] . '<br>';
//echo "billname =  "  .  $row['billname'] . '<br>';
echo "email =  "  .  $row['email'] . '<br>';
//echo "email_direct =  "  .  $row['email_direct'] . '<br>';
echo "url =  "  .  $row['url'] . '<br>';
//echo "assistant =  "  .  $row['assistant'] . '<br>';
echo "organization =  "  .  $row['organization'] . '<br>';
//echo "valedictory =  "  .  $row['valedictory'] . '<br>';
echo "street =  "  .  $row['street'] . '<br>';
//echo "streetb =  "  .  $row['streetb'] . '<br>';
echo "city =  "  .  $row['city'] . '<br>';
echo "state =  "  .  $row['state'] . '<br>';
echo "zip =  "  .  $row['zip'] . '<br>';
echo "street2 =  "  .  $row['street2'] . '<br>';
//echo "streetb2 =  "  .  $row['streetb2'] . '<br>';
//echo "city2 =  "  .  $row['city2'] . '<br>';
//echo "state2 =  "  .  $row['state2'] . '<br>';
//echo "zip2 =  "  .  $row['zip2'] . '<br>';
echo "phone =  "  .  $row['phone'] . '<br>';
//echo "fax =  "  .  $row['fax'] . '<br>';
//echo "phonew =  "  .  $row['phonew1'] . '<br>';
//echo "phonew2 =  "  .  $row['phonew2'] . '<br>';
echo "phonecell =  "  .  $row['phonecell'] . '<br>';
echo "notes =  "  .  $row['notes'] . '<br>';
//echo "cal_ui =  "  .  $row['cal_ui'] . '<br>';
echo "taxonomy =  "  .  $row['taxonomy'] . '<br>';
//echo "calendar =  "  .  $row['calendar'] . '<br>';
//echo "abook_type =  "  .  $row['abook_type'] . '<br>';
//echo "pwd_expiration_date =  "  .  $row['pwd_expiration_date'] . '<br>';
//echo "pwd_history1 =  "  .  $row['pwd_history1'] . '<br>';
//echo "pwd_history2 =  "  .  $row['pwd_history2'] . '<br>';
//echo "default_warehouse =  "  .  $row['default_warehouse'] . '<br>';
//echo "irnpool =  "  .  $row['irnpool'] . '<br>';
echo "state_license_number =  "  .  $row['state_license_number'] . '<br>';
echo "weno_prov_id =  "  .  $row['weno_prov_id'] . '<br>';
echo "newcrop_user_role =  "  .  $row['newcrop_user_role'] . '<br>';
echo "cpoe =  "  .  $row['cpoe'] . '<br>';
echo "physician_type =  "  .  $row['physician_type'] . '<br>';
//echo "main_menu_role =  "  .  $row['main_menu_role'] . '<br>';
//echo "patient_menu_role =  "  .  $row['patient_menu_role'] . '<br>';
}
}

$res->close();

?>

