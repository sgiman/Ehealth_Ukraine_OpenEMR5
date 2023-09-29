<?php
/**
 * api/searchdrug.php Search drug.
 *
 * API is allowed to search drug.
 * 
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */
header("Content-Type:text/xml");
header("Access-Control-Allow-Origin: *");

$ignoreAuth = true;
require_once('classes.php');
require_once("$srcdir/classes/RXList.class.php");

$xml_string = "";
$xml_string = "<list>";

$token = $GET['token'];
$query = $GET['name'];

$drugList = new RxList();
$result = $drugList->get_list($query);

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('admin', 'super', $user);
    if ($acl_allow) {
        if (!empty($result)) {

            $xml_string .= "<status>0</status>\n";
            $xml_string .= "<reason>Success processing drugs list records</reason>\n";

            foreach ($result as $rows) {

                $xml_string .= "<drug>" . $rows . "</drug>";
            }
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

$xml_string .= "</list>";
echo $xml_string;
?>