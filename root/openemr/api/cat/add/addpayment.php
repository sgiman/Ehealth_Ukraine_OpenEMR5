<?php

/**
 * api/addpayment.php Add patient payment.
 *
 * API is allowed to add patient payment.
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
require_once("$srcdir/payment.inc.php");

$xml_string = "";
$xml_string = "<payment>";

$token = $_GET['token'];

$payment_type = $_GET['payment_type'];

$patient_id = $_GET['patient_id'];
$visit_id = $_GET['visit_id'];
$amount = $_GET['amount'];
$modifier = $_GET['modifier'];
$check_ref_number = $_GET['check_ref_number'];
$payment_method = $_GET['payment_method'];

$prepayment = $_GET['prepayment'];
$NameNew = $_GET['fname'] . " " . $_GET['lname'] . " " . $_GET['mname'];

$now = time();
$today = date('Y-m-d', $now);
$timestamp = date('Y-m-d H:i:s', $now);

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('acct', 'bill', $user);


    if ($acl_allow) {

        if ($payment_type == 'pre_payment') {

            $strQuery = "insert into ar_session set " .
                    "payer_id = ?" .
                    ", patient_id = ?" .
                    ", user_id = ?" .
                    ", closed = ?" .
                    ", reference = ?" .
                    ", check_date =  now() , deposit_date = now() " .
                    ",  pay_total = ?" .
                    ", payment_type = 'patient'" .
                    ", description = ?" .
                    ", adjustment_code = 'pre_payment'" .
                    ", post_to_date = now() " .
                    ", payment_method = ?";
            $payment_id = idSqlStatement($strQuery, array(0, $patient_id, $userId, 0, $check_ref_number, $prepayment, $NameNew, $payment_type));

            $result = frontPayment($patient_id, 0, $payment_type, $payment_method, $prepayment, 0, $timestamp, $user);
        }             
        if ($payment_type == 'COPAY') {
            $ResultSearchNew = sqlStatement("SELECT * FROM billing LEFT JOIN code_types ON billing.code_type=code_types.ct_key " .
                    "WHERE code_types.ct_fee=1 AND billing.activity!=0 AND billing.pid =? AND encounter=? ORDER BY billing.code,billing.modifier", array($patient_id, $visit_id));

            if ($RowSearch = sqlFetchArray($ResultSearchNew)) {
                $Codetype = $RowSearch['code_type'];
                $Code = $RowSearch['code'];
                $Modifier = $RowSearch['modifier'];
            } else {
                $Codetype = '';
                $Code = '';
                $Modifier = '';
            }

            $strQuery1 = "INSERT INTO ar_session (payer_id,user_id,reference,check_date,deposit_date,pay_total," .
                    " global_amount,payment_type,description,patient_id,payment_method,adjustment_code,post_to_date) " .
                    " VALUES ('0',?,?,now(),now(),?,'','patient','COPAY',?,?,'patient_payment',now())";
            $session_id = idSqlStatement($strQuery1, array($userId, $check_ref_number, $amount, $patient_id, $payment_type));


            $insert_id = idSqlStatement("INSERT INTO ar_activity (pid,encounter,code_type,code,modifier,payer_type,post_time,post_user,session_id,pay_amount,account_code)" .
                    " VALUES (?,?,?,?,?,0,now(),?,?,?,'PCP')", array($patient_id, $visit_id, $Codetype, $Code, $Modifier, $userId, $session_id, $amount));

            $result = frontPayment($patient_id, $visit_id, $payment_type, $payment_method, $amount, 0, $timestamp, $user);
        } 
        if ($payment_type == 'invoice_balance' || $payment_type == 'cash') {

            if ($payment_type == 'cash') {
                sqlStatement("update form_encounter set last_level_closed=? where encounter=? and pid=? ", array(4, $visit_id, $patient_id));
                sqlStatement("update billing set billed=? where encounter=? and pid=?", array(1, $visit_id, $patient_id));
            }

            $adjustment_code = 'patient_payment';
            $strQuery2 = "insert into ar_session set " .
                    "payer_id = ?" .
                    ", patient_id = ?" .
                    ", user_id = ?" .
                    ", closed = ?" .
                    ", reference = ?" .
                    ", check_date =  now() , deposit_date = now() " .
                    ", pay_total = ?" .
                    ", payment_type = 'patient'" .
                    ", description = ?" .
                    ", adjustment_code = ?" .
                    ", post_to_date = now() " .
                    ", payment_method = ?";
            $payment_id = idSqlStatement($strQuery2, array(0, $patient_id, $userId, 0, $check_ref_number, $amount, $NameNew, $adjustment_code, $payment_type));

            $result = frontPayment($patient_id, $visit_id, $payment_type, $payment_method, 0, $amount, $timestamp, $user); //insertion to 'payments' table.

            $resMoneyGot = sqlStatement("SELECT sum(pay_amount) as PatientPay FROM ar_activity where pid =? and " .
                    "encounter =? and payer_type=0 and account_code='PCP'", array($patient_id, $visit_id)); //new fees screen copay gives account_code='PCP'
            $rowMoneyGot = sqlFetchArray($resMoneyGot);
            $Copay = $rowMoneyGot['PatientPay'];

            $ResultSearchNew = sqlStatement("SELECT * FROM billing LEFT JOIN code_types ON billing.code_type=code_types.ct_key WHERE code_types.ct_fee=1 " .
                    "AND billing.activity!=0 AND billing.pid =? AND encounter=? ORDER BY billing.code,billing.modifier", array($patient_id, $visit_id));
            while ($RowSearch = sqlFetchArray($ResultSearchNew)) {
                $Codetype = $RowSearch['code_type'];
                $Code = $RowSearch['code'];
                $Modifier = $RowSearch['modifier'];
                $Fee = $RowSearch['fee'];

                $resMoneyGot = sqlStatement("SELECT sum(pay_amount) as MoneyGot FROM ar_activity where pid =? " .
                        "and code_type=? and code=? and modifier=? and encounter =? and !(payer_type=0 and account_code='PCP')", array($patient_id, $Codetype, $Code, $Modifier, $visit_id));

                $rowMoneyGot = sqlFetchArray($resMoneyGot);
                $MoneyGot = $rowMoneyGot['MoneyGot'];

                $resMoneyAdjusted = sqlStatement("SELECT sum(adj_amount) as MoneyAdjusted FROM ar_activity where " .
                        "pid =? and code_type=? and code=? and modifier=? and encounter =?", array($patient_id, $Codetype, $Code, $Modifier, $visit_id));
                $rowMoneyAdjusted = sqlFetchArray($resMoneyAdjusted);
                $MoneyAdjusted = $rowMoneyAdjusted['MoneyAdjusted'];

                $Remainder = $Fee - $Copay - $MoneyGot - $MoneyAdjusted;
                $Copay = 0;

                if (round($Remainder, 2) != 0 && $amount != 0) {
                    if ($amount - $Remainder >= 0) {
                        $insert_value = $Remainder;
                        $amount = $amount - $Remainder;
                    } else {
                        $insert_value = $amount;
                        $amount = 0;
                    }
                    sqlStatement("insert into ar_activity set " .
                            "pid = ?" .
                            ", encounter = ?" .
                            ", code_type = ?" .
                            ", code = ?" .
                            ", modifier = ?" .
                            ", payer_type = ?" .
                            ", post_time = now() " .
                            ", post_user = ?" .
                            ", session_id = ?" .
                            ", pay_amount = ?" .
                            ", adj_amount = ?" .
                            ", account_code = 'PP'", array($patient_id, $visit_id, $Codetype, $Code, $Modifier, 0, $userId, $payment_id, $insert_value, 0));
                }
            }
        }
        if ($result) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Payment has been added</reason>";
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

$xml_string .= "</payment>";
echo $xml_string;
?>