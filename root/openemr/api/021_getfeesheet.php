<?php
/**
 * api/getfeecheet.php retrieve feesheet.
 *
 * (21)API fetch patient feesheet.
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

function supervisorName($supervisor_id) {
    $strQuery = "SELECT fname, lname FROM users WHERE id =?";
    $result = sqlQuery($strQuery, array($supervisor_id));
    return $result['fname'] . " " . $result['lname'];
}

$xml_string = "";
$xml_string = "<feesheet>";

$token = $_GET['token'];
$visit_id = $_GET['visit_id'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('acct', 'bill', $user);

    if ($acl_allow) {


        $strQuery = "SELECT b.id, b.authorized, b.fee, b.code_type, b.code, b.modifier, b.units, b.justify, b.provider_id, 
				fe.supervisor_id, u.fname, u.lname, pd.pricelevel, c.code_text
          		FROM billing AS b
				LEFT JOIN users AS u ON u.id = b.provider_id
          		LEFT JOIN form_encounter AS fe ON fe.pid = b.pid AND fe.encounter = b.encounter
				LEFT JOIN codes AS c ON c.code = b.code
          		LEFT JOIN patient_data AS pd ON pd.pid = b.pid WHERE b.activity = 1 AND b.encounter = ?";

        $result = sqlStatement($strQuery, array($visit_id));

        $strQuery1 = "SELECT aa.session_id AS id,aa.post_user AS authorized, pay_amount AS fee, aa.code_type, aa.code, aa.modifier, aa.pay_amount AS PatientPay, date(post_time) AS date, fe.supervisor_id,u.id AS provider_id, u.fname, u.lname, pd.pricelevel, c.code_text
FROM ar_activity AS aa 
LEFT JOIN users AS u ON u.id = aa.post_user
LEFT JOIN form_encounter AS fe ON fe.pid = aa.pid AND fe.encounter = aa.encounter
LEFT JOIN codes AS c ON c.code = aa.code
LEFT JOIN patient_data AS pd ON pd.pid = aa.pid WHERE aa.encounter = ? AND payer_type=0 and 
account_code='PCP'";

        $resMoneyGot = sqlStatement($strQuery1, array($visit_id));
        
        if ($result->_numOfRows > 0) {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>The Feesheet records has been fetched.</reason>";
            $count = 0;
            while ($res = sqlFetchArray($result)) {
                $xml_string .= "<item>\n";

                foreach ($res as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);
                    if ($fieldName == 'fname' || $fieldName == 'lname') {
                        
                    } else {
                        $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                    }
                }
                $supervisor_id = $res['supervisor_id'];
                $fname = $res['fname'];
                $lname = $res['lname'];
                $xml_string .= "<provider>" . $fname . " " . $lname . "</provider>\n";
                $xml_string .= "<supervisor>\n" . supervisorName($supervisor_id) . "</supervisor>\n";
                $xml_string .= "</item>\n";
                $count++;
            }
        } else {
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>No records found.</reason>";
        }
        if ($resMoneyGot->_numOfRows > 0) {
            $count = 0;
            while ($res1 = sqlFetchArray($resMoneyGot)) {
                $xml_string .= "<copay>\n";

                foreach ($res1 as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);
                    if ($fieldName == 'fname' || $fieldName == 'lname') {
                        
                    } else {
                        $xml_string .= "<$fieldName>$rowValue</$fieldName>\n";
                    }
                }
                $fname = $res1['fname']; // ???
                $lname = $res1['lname']; // ???
							
                $xml_string .= "<provider>" . $fname . " " . $lname . "</provider>\n";
                //$xml_string .= "<provider>" . "FNAME" . " " . "LNAME" . "</provider>\n";
                $xml_string .= "</copay>\n";
                $count++;
            }
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