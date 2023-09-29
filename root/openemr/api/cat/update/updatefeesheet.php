<?php

/**
 * api/updatefeesheet.php Update fee sheet.
 *
 * API is allowed to update feehsheet items details for billing. 
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
$id = $_GET['id'];

$patientId = $_GET['patientId'];
$visit_id = $_GET['visit_id'];
$provider_id = $_GET['provider_id'];
$supervisor_id = $_GET['supervisor_id'];
$auth = $_GET['auth'];
$code_type = $_GET['code_type'];
$code = $_GET['code'];
$modifier = $_GET['modifier'];
$units = max(1, intval(trim($_GET['units'])));
$price = $_GET['price'];
$priceLevel = $_GET['priceLevel'];
$justify = $_GET['justify'];

$code_text = !empty($_GET['code_text']) ? $_GET['code_text']: '';
$ct0 = ''; //takes the code type of the first fee type code type entry from the fee sheet, against which the copay is posted
$cod0 = ''; //takes the code of the first fee type code type entry from the fee sheet, against which the copay is posted
$mod0 = ''; //takes the modifier of the first fee type code type entry from the fee sheet, against which the copay is posted
$ndc_info = !empty($_GET['ndc_info']) ? $_GET['ndc_info'] : '';
$noteCodes = !empty($_GET['noteCodes']) ? $_GET['noteCodes'] : '';

$fee = sprintf('%01.2f', (0 + trim($price)) * $units);

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('acct', 'bill', $user);
    
    $provider = getAuthGroup($user);
    if ($authGroup = sqlQuery("select * from groups where user='$user' and name='$provider'")) {
        $_SESSION['authProvider'] = $provider;
        $_SESSION['authId'] = $userId;
        $_SESSION['pid'] = $patientId;
    }
    
    if ($acl_allow) {

        if ($code_type == 'COPAY') {

            $strQuery3 = "SELECT pay_amount FROM ar_activity " .
                    "WHERE pid=? AND encounter=? AND session_id=?";

            $res_amount = sqlQuery($strQuery3, array($patientId, $visit_id, $id));

            $getCode = "SELECT * FROM `billing` WHERE  pid = ? AND encounter = ? ORDER BY `billing`.`encounter` ASC LIMIT 1";

            $res = sqlQuery($getCode, array($patientId, $visit_id));
            
            if ($fee != $res_amount['pay_amount']) {

                $cod0 = $res['code'];
                $ct0 = $res['code_type'];
                $mod0 = $res['modifier'];

                $strQuery4 = "UPDATE ar_session SET user_id=?,pay_total=?," .
                        "modified_time=now(),post_to_date=now() WHERE session_id=?";

                $result4 = sqlStatement($strQuery4, array($auth, $fee, $id));

                $strQuery5 = "UPDATE ar_activity SET code_type=?, code=?, modifier=?, post_user=?, post_time=now()," .
                        "pay_amount=?, modified_time=now() WHERE pid=? AND encounter=? AND account_code='PCP'" .
                        "AND session_id=?";
                $result4 = sqlStatement($strQuery5, array($ct0, $cod0, $mod0, $auth, $fee, $patientId, $visit_id, $id));
            }
        } else {

            $strQuery = 'UPDATE billing SET ';
            $strQuery .= ' code_type = "' . add_escape_custom($code_type) . '",';
            $strQuery .= ' code = "' . add_escape_custom($code) . '",';
            $strQuery .= ' modifier = "' . add_escape_custom($modifier) . '",';
            $strQuery .= ' justify = "' . add_escape_custom($justify) . '",';
            $strQuery .= ' authorized = "' . add_escape_custom($auth) . '",';
            $strQuery .= ' provider_id = "' . add_escape_custom($provider_id) . '",';
            $strQuery .= ' units = "' . add_escape_custom($units) . '",';
            $strQuery .= ' bill_process = 0,';
            $strQuery .= ' notecodes = "' . add_escape_custom($notesCodes) . '",';
            $strQuery .= ' fee = "' . add_escape_custom($fee) . '"';
            $strQuery .= ' WHERE id = ?';

            $result = sqlStatement($strQuery, array($id));
        }

        $strQuery1 = 'UPDATE `patient_data` SET';
        $strQuery1 .= ' pricelevel  = "' . $priceLevel . '"';
        $strQuery1 .= ' WHERE pid = ?';

        $result1 = sqlStatement($strQuery1, array($patientId));

        $strQuery2 = 'UPDATE `form_encounter` SET';
        $strQuery2 .= ' provider_id  = "' . $provider_id . '",';
        $strQuery2 .= ' supervisor_id  = "' . $supervisor_id . '"';
        $strQuery2 .= ' WHERE pid = ?' . ' AND encounter = ?';

        $result2 = sqlStatement($strQuery2, array($patientId, $visit_id));


        if ($result1 && $result2) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Feesheet has been updated</reason>";
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>ERROR: Sorry, there was an error processing your data. Please re-submit the information again.</reason>";
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