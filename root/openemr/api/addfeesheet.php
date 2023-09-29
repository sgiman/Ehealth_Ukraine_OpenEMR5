<?php

/**
 * api/addfeesheet.php Add fee sheet items.
 *
 * API is allowed to add fee sheet items (price and units) for billing.
 * 
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */
header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;
require_once 'classes.php';

$xml_string = "";
$xml_string = "<feesheet>";

$token = $_GET['token'];

$patientId = $_GET['patientId'];
$visit_id = $_GET['visit_id'];
$provider_id = $_GET['provider_id'];
$supervisor_id = $_GET['supervisor_id'];
$auth = $_GET['auth'];
$code_type = $_GET['code_type'];
$code = $_GET['code'];
$modifier = $_GET['modifier'];
$units = max(1, intval(trim($_POST['units'])));
$price = $_GET['price'];
$priceLevel = $_GET['priceLevel'];
$justify = $_GET['justify'];
$ndc_info = !empty($_GET['ndc_info']) ? $_GET['ndc_info'] : '';
$noteCodes = !empty($_GET['noteCodes']) ? $_GET['noteCodes'] : '';
$code_text = !empty($_GET['code_text']) ? $_GET['code_text']: '';
$ct0 = ''; //takes the code type of the first fee type code type entry from the fee sheet, against which the copay is posted
$cod0 = ''; //takes the code of the first fee type code type entry from the fee sheet, against which the copay is posted
$mod0 = ''; //takes the modifier of the first fee type code type entry from the fee sheet, against which the copay is posted

$fee = sprintf('%01.2f', (0 + trim($price)) * $units);
if ($fee < 0) {
    $fee = $fee * -1;
}

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('acct', 'bill', $user);

    $_SESSION['authProvider'] = getAuthGroup($user);
    $_SESSION['authId'] = $userId;
    
    if ($acl_allow) {

        if ($code_type == 'COPAY') {

            $strQuery3 = "INSERT INTO ar_session(payer_id,user_id,pay_total,payment_type,description," .
                    "patient_id,payment_method,adjustment_code,post_to_date)" .
                    "VALUES('0',?,?,'patient','COPAY',?,'','patient_payment',now())";

            $session_id = idSqlStatement($strQuery3, array($auth, $fee, $patientId));

            $getCode = "SELECT * FROM `billing` WHERE  pid = ? AND encounter = ? ORDER BY `billing`.`encounter` ASC LIMIT 1";

            $res = sqlQuery($getCode, array($patientId, $visit_id));

            if ($res) {
                $cod0 = $res['code'];
                $ct0 = $res['code_type'];
                $mod0 = $res['modifier'];

                $strQuery4 = "INSERT INTO ar_activity (pid,encounter,code_type,code,modifier,payer_type," .
                        "post_time,post_user,session_id,pay_amount,account_code) " .
                        "VALUES (?,?,?,?,?,0,now(),?,?,?,'PCP')";

                $result3 = SqlStatement($strQuery4, array($patientId, $visit_id, $ct0, $cod0, $mod0, $auth, $session_id, $fee));
            }
        } else {

            addBilling($visit_id, $code_type, $code, $code_text, $patientId, $auth, $provider_id, $modifier, $units, $fee, $ndc_info, $justify, 0, $noteCodes);
        }
        $strQuery1 = 'UPDATE `patient_data` SET';
        $strQuery1 .= ' pricelevel  = "' . add_escape_custom($priceLevel) . '"';
        $strQuery1 .= ' WHERE pid = ?';

        $result1 = sqlStatement($strQuery1, array($patientId));

        $strQuery2 = 'UPDATE `form_encounter` SET';
        $strQuery2 .= ' provider_id  = "' . add_escape_custom($provider_id) . '",';
        $strQuery2 .= ' supervisor_id  = "' . add_escape_custom($supervisor_id) . '"';
        $strQuery2 .= ' WHERE pid = ?' . ' AND encounter = ?';

        $result2 = sqlStatement($strQuery2, array($patientId, $visit_id));

        if ($result1 && $result2) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>Fee Sheet added successfully</reason>";
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</feesheet>";
echo $xml_string;
?>