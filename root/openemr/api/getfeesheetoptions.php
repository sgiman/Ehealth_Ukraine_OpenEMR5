<?php
/**
 * api/getfeesheetoptions.php Get fee sheet options.
 *
 * API is allowed to get fee sheet options for new and established patient.
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */
header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;
require 'classes.php';

$xml_string = "";
$xml_string = "<options>";

$token = $_GET['token'];

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    
    $acl_allow = acl_check('acct', 'bill', $user);
    if ($acl_allow) {
        $newpatient = '1New Patient';
        $strQuery = "SELECT * FROM fee_sheet_options WHERE fs_category = ? ORDER BY fs_option";

        $result = sqlStatement($strQuery,array($newpatient));
    
        $established = '2Established Patient';
        
        $strQuery1 = "SELECT * FROM fee_sheet_options WHERE fs_category = ? ORDER BY fs_option";
        $result1 = sqlStatement($strQuery1,array($established));
        
       
        if ($result->_numOfRows > 0 || $result1->_numOfRows > 0){ 
            $xml_string .= "<status>0</status>";
            $xml_string .= "<reason>Options Processed successfully</reason>";

            $xml_string .= "<newpatient>\n";
            
           while($res = sqlFetchArray($result)){        
                $xml_string .= "<option>\n";
                
                foreach ($res as $fieldName => $fieldValue) {
                    $rowValue = xmlsafestring($fieldValue);
                    if ($fieldName != 'fs_category' && $fieldName == 'fs_option') {
                        $xml_string .= "<$fieldName>" . substr($rowValue, 1) . "</$fieldName>\n";
                    }
                    if ($fieldName != 'fs_category' && $fieldName != 'fs_option' && $fieldName == 'fs_codes') {
                        $xml_string .= "<$fieldName>" . $rowValue . "</$fieldName>\n";
                    }
                }

                $xml_string .= "</option>\n";
               
            }
            $xml_string .= "</newpatient>";

            $xml_string .= "<establishedpatient>\n";

           while($res = sqlFetchArray($result1)){        
                $xml_string .= "<option>\n";
                
                foreach ($res as $fieldName => $fieldValue) {
                    $rowValue1 = xmlsafestring($fieldValue);

                    if ($fieldName != 'fs_category' && $fieldName == 'fs_option') {
                        $xml_string .= "<$fieldName>" . substr($rowValue1, 1) . "</$fieldName>\n";
                    }
                    if ($fieldName != 'fs_category' && $fieldName != 'fs_option' && $fieldName == 'fs_codes') {
                        $xml_string .= "<$fieldName>" . $rowValue1 . "</$fieldName>\n";
                    }
                }

                $xml_string .= "</option>\n";
            }
            $xml_string .= "</establishedpatient>";
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>Could not find results</reason>";
        }
    } else {
        $xml_string .= "<status>-2</status>\n";
        $xml_string .= "<reason>You are not Authorized to perform this action</reason>\n";
    }
} else {
    $xml_string .= "<status>-2</status>";
    $xml_string .= "<reason>Invalid Token</reason>";
}

$xml_string .= "</options>";
echo $xml_string;
?>