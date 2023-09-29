<?php

/**
 * api/addcheckout.php Add patient checkout.
 *
 * API is allowed to add patient billed ammount, discount and payment 
 * method(cash, check).
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
$xml_string = "<checkout>";

$token = $_GET['token'];
$patientId = $_GET['patientId'];
$visit_id = $_GET['visit_id'];
$payment_method = $_GET['payment_method'];
$check_ref_number = $_GET['check_ref_number'];
$fee = $_GET['fee'];
$discountAmount = !empty($_GET['discountAmount']) ? $_GET['discountAmount'] : 0;
$billing_id = $_GET['billing_id'];

$charges = $fee - $discountAmount;

if ($userId = validateToken($token)) {
    $user = getUsername($userId);

    $acl_allow = acl_check('acct', 'bill', $user);

    // Session variable used in addBilling() function
    $_SESSION['authUser'] = $user;
    
    if ($acl_allow) {

        if ($code_type == 'TAX') {
            // In the SL case taxes show up on the invoice as line items.
            // Otherwise we gotta save them somewhere, and in the billing
            // table with a code type of TAX seems easiest.
            // They will have to be stripped back out when building this
            // script's input form.

            addBilling($visit_id, 'TAX', 'TAX', 'Taxes', $patientId, 0, 0, '', '', $charges, '', '', 1);
        } else {
            // Because there is no insurance here, there is no need for a claims
            // table entry and so we do not call updateClaim().  Note we should not
            // eliminate billed and bill_date from the billing table!
            $query = "UPDATE billing SET fee = ?, billed = 1, " .
                    "bill_date = NOW() WHERE id = ?";
            sqlQuery($query, array($charges, $billing_id));
        }
        if (!empty($discountAmount)) {
            $time = date('Y-m-d H:i:s');
            $memo = 'Discount';
            $query = "INSERT INTO ar_activity ( " .
                    "pid, encounter, code, modifier, payer_type, post_user, post_time, " .
                    "session_id, memo, adj_amount " .
                    ") VALUES ( " .
                    "?, " .
                    "?, " .
                    "'', " .
                    "'', " .
                    "'0', " .
                    "?, " .
                    "?, " .
                    "'0', " .
                    "?, " .
                    "? " .
                    ")";
            sqlStatement($query, array($patientId, $visit_id, $userId, $time, $memo, $amount));
        }

        if (!empty($charges)) {
            $amount = sprintf('%01.2f', trim($charges));

            $ResultSearchNew = sqlStatement("SELECT * FROM billing LEFT JOIN code_types ON billing.code_type=code_types.ct_key " .
                    "WHERE code_types.ct_fee=1 AND billing.activity!=0 AND billing.pid =? AND encounter=? ORDER BY billing.code,billing.modifier", array($patientId, $visit_id));
            if ($RowSearch = sqlFetchArray($ResultSearchNew)) {
                $Codetype = $RowSearch['code_type'];
                $Code = $RowSearch['code'];
                $Modifier = $RowSearch['modifier'];
            } else {
                $Codetype = '';
                $Code = '';
                $Modifier = '';
            }
            $session_id = idSqlStatement("INSERT INTO ar_session (payer_id,user_id,reference,check_date,deposit_date,pay_total," .
                    " global_amount,payment_type,description,patient_id,payment_method,adjustment_code,post_to_date) " .
                    " VALUES ('0',?,?,now(),?,?,'','patient','COPAY',?,?,'patient_payment',now())", array($user_id, $check_ref_number, $dosdate, $amount, $patientId, $paydesc));
            $insrt_id = idSqlStatement("INSERT INTO ar_activity (pid,encounter,code_type,code,modifier,payer_type,post_time,post_user,session_id,pay_amount,account_code)" .
                    " VALUES (?,?,?,?,?,0,?,?,?,?,'PCP')", array($patientId, $visit_id, $Codetype, $Code, $Modifier, $dosdate, $userId, $session_id, $amount));
        }


        if ($insrt_id) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Checkout has been added.</reason>";
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>ERROR: Sorry, there was an error processing your data. Please re-submit the information again.</reason>";
        }
    } else {
        $xml_array['status'] = -2;
        $xml_array['reason'] = 'You are not Authorized to perform this action';
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</checkout>";
echo $xml_string;
?>