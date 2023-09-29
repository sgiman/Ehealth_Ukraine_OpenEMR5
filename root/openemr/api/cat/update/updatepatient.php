<?php
/**
 * api/updatepatient.php Update patient information.
 *
 * API is allowed to update patient demographics, insurance details
 * and profile image.
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

$xml_array = array();

$token = $_GET['token'];
$patientId = $_GET['patientId'];

$id = $_GET['id'];

$title = $_GET['title'];
$language = $_GET['language']; //d
$firstname = $_GET['firstname']; // d
$lastname = $_GET['lastname']; //d
$middlename = $_GET['middlename']; //d
$dob = $_GET['dob']; //d
$street = $_GET['street']; // streetAddressLine1, streetAddressLine2
$postal_code = $_GET['postal_code']; // ZipCode d
$city = $_GET['city']; //d
$state = $_GET['state']; //d
$country_code = $_GET['country_code'];
$ss = $_GET['ss']; // if suffix d
$occupation = $_GET['occupation'];
$phone_home = $_GET['phone_home']; //d
$phone_biz = $_GET['phone_biz']; //d
$phone_contact = $_GET['phone_contact']; // d
$phone_cell = $_GET['phone_cell']; //d
$status = $_GET['status'];
$drivers_lincense = $_GET['drivers_license'];
$contact_relationship = $_GET['contact_relationship']; //d
$sex = $_GET['sex']; //d
$email = $_GET['email']; //d
$race = $_GET['race']; //d
$ethnicity = $_GET['ethnicity']; //d
$usertext1 = $_GET['notes']; // note d
$nickname = $_GET['nickname'];
$mothersname = $_GET['mothersname'];
$guardiansname = $_GET['guardiansname'];

$p_insurance_company = $_GET['p_provider'];
$p_subscriber_employer_status = $_GET['p_subscriber_employer'];
$p_group_number = $_GET['p_group_number'];
$p_plan_name = $_GET['p_plan_name'];
$p_subscriber_relationship = $_GET['p_subscriber_relationship'];
$p_insurance_id = $_GET['p_insurance_id'];


$s_insurance_company = $_GET['s_provider'];
$s_subscriber_employer_status = $_GET['s_subscriber_employer'];
$s_group_number = $_GET['s_group_number'];
$s_plan_name = $_GET['s_plan_name'];
$s_subscriber_relationship = $_GET['s_subscriber_relationship'];
$s_insurance_id = $_GET['s_insurance_id'];

$o_insurance_company = $_GET['o_provider'];
$o_subscriber_employer_status = $_GET['o_subscriber_employer'];
$o_group_number = $_GET['o_group_number'];
$o_plan_name = $_GET['o_plan_name'];
$o_subscriber_relationship = $_GET['o_subscriber_relationship'];
$o_insurance_id = $_GET['o_insurance_id'];

$image_data = isset($_GET['image_data']) ? $_GET['image_data'] : '';

if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    
    $acl_allow = acl_check('patients', 'demo', $user);

    if ($acl_allow) {

        $postData = array(
            'id' => $id,
            'title' => $title,
            'fname' => $firstname,
            'lname' => $lastname,
            'mname' => $middlename,
            'sex' => $sex,
            'status' => $status,
            'drivers_license' => $drivers_lincense,
            'contact_relationship' => $contact_relationship,
            'phone_biz' => $phone_biz,
            'phone_cell' => $phone_cell,
            'phone_contact' => $phone_contact,
            'phone_home' => $phone_home,
            'DOB' => $dob,
            'language' => $language,
            'financial' => $financial,
            'street' => $street,
            'postal_code' => $postal_code,
            'city' => $city,
            'state' => $state,
            'country_code' => $country_code,
            'ss' => $ss,
            'occupation' => $occupation,
            'email' => $email,
            'race' => $race,
            'ethnicity' => $ethnicity,
            'usertext1' => $usertext1,
            'genericname1' => $nickname,
            'mothersname' => $mothersname,
            'guardiansname' => $guardiansname,
        );


        updatePatientData($patientId, $postData, $create = false);


        $primary_insurace_data = getInsuranceData($patientId);

        $secondary_insurace_data = getInsuranceData($patientId, 'secondary');

        $other_insurace_data = getInsuranceData($patientId, 'tertiary');

        $p_insurace_data = array(
            'provider' => $p_insurance_company,
            'group_number' => $p_group_number,
            'plan_name' => $p_plan_name,
            'subscriber_employer' => $p_subscriber_employer_status,
            'subscriber_relationship' => $p_subscriber_relationship,
            'policy_number' => $p_insurance_id
        );

        if ($primary_insurace_data) {
            updateInsuranceData($primary_insurace_data['id'], $p_insurace_data);
        } else {
            newInsuranceData(
                    $patientId, $type = "primary", $p_insurance_company, $policy_number = $p_insurance_id, $group_number = $p_group_number, $plan_name = $p_plan_name, $subscriber_lname = "", $subscriber_mname = "", $subscriber_fname = "", $subscriber_relationship =
                    $p_subscriber_relationship, $subscriber_ss = "", $subscriber_DOB = "", $subscriber_street = "", $subscriber_postal_code = "", $subscriber_city = "", $subscriber_state = "", $subscriber_country = "", $subscriber_phone = "", $subscriber_employer =
                    $p_subscriber_employer_status, $subscriber_employer_street = "", $subscriber_employer_city = "", $subscriber_employer_postal_code = "", $subscriber_employer_state = "", $subscriber_employer_country = "", $copay = "", $subscriber_sex = "", $effective_date = "0000-00-00", $accept_assignment = "TRUE"
            );
        }

        $s_insurace_data = array(
            'provider' => $s_insurance_company,
            'group_number' => $s_group_number,
            'plan_name' => $s_plan_name,
            'subscriber_employer' => $s_subscriber_employer_status,
            'subscriber_relationship' => $s_subscriber_relationship,
            'policy_number' => $s_insurance_id
        );

        if ($secondary_insurace_data) {
            updateInsuranceData($secondary_insurace_data['id'], $s_insurace_data);
        } else {
            newInsuranceData(
                    $patientId, $type = "secondary", $s_insurance_company, $policy_number = $s_insurance_id, $group_number = $s_group_number, $plan_name = $s_plan_name, $subscriber_lname = "", $subscriber_mname = "", $subscriber_fname = "", $subscriber_relationship = $s_subscriber_relationship, $subscriber_ss = "", $subscriber_DOB = "", $subscriber_street = "", $subscriber_postal_code = "", $subscriber_city = "", $subscriber_state = "", $subscriber_country = "", $subscriber_phone = "", $subscriber_employer = $s_subscriber_employer_status, $subscriber_employer_street = "", $subscriber_employer_city = "", $subscriber_employer_postal_code = "", $subscriber_employer_state = "", $subscriber_employer_country = "", $copay = "", $subscriber_sex = "", $effective_date = "0000-00-00", $accept_assignment = "TRUE");
        }

        $o_insurace_data = array(
            'provider' => $o_insurance_company,
            'group_number' => $o_group_number,
            'plan_name' => $o_plan_name,
            'subscriber_employer' => $o_subscriber_employer_status,
            'subscriber_relationship' => $o_subscriber_relationship,
            'policy_number' => $o_insurance_id
        );

        if ($other_insurace_data) {
            updateInsuranceData($other_insurace_data['id'], $o_insurace_data);
        } else {
            newInsuranceData(
                    $patientId, $type = "tertiary", $o_insurance_company, $policy_number = $o_insurance_id, $group_number = $o_group_number, $plan_name = $o_plan_name, $subscriber_lname = "", $subscriber_mname = "", $subscriber_fname = "", $subscriber_relationship = $o_subscriber_relationship, $subscriber_ss = "", $subscriber_DOB = "", $subscriber_street = "", $subscriber_postal_code = "", $subscriber_city = "", $subscriber_state = "", $subscriber_country = "", $subscriber_phone = "", $subscriber_employer = $o_subscriber_employer_status, $subscriber_employer_street = "", $subscriber_employer_city = "", $subscriber_employer_postal_code = "", $subscriber_employer_state = "", $subscriber_employer_country = "", $copay = "", $subscriber_sex = "", $effective_date = "0000-00-00", $accept_assignment = "TRUE");
        }


        if ($image_data) {

            $id = 1;
            $type = "file_url";
            $size = '';
            $date = date('Y-m-d H:i:s');
            $url = '';
            $mimetype = 'image/jpeg';
            $hash = '';
            $patient_id = $patientId;
            $ext = 'png';
//            $cat_title = 'Patient Profile Image';
            $cat_title = 'Patient Photograph';
            
            $strQuery2 = "SELECT id from `categories` WHERE name LIKE ?";

            $result3 = sqlQuery($strQuery2, array($cat_title));

            if ($result3) {
                $cat_id = $result3['id'];
            } else {
                sqlStatement("lock tables categories read");

                $result4 = sqlQuery("select max(id)+1 as id from categories");

                $cat_id = $result4['id'];

                sqlStatement("unlock tables");

                $cat_insert_query = "INSERT INTO `categories`(`id`, `name`, `value`, `parent`, `lft`, `rght`) 
                VALUES (" . add_escape_custom($cat_id) . ",'" . add_escape_custom($cat_title) . "','',1,0,0)";

                sqlStatement($cat_insert_query);
            }

            $strQuery4 = "SELECT d.url,d.id
                                FROM `documents` AS d
                                INNER JOIN `categories_to_documents` AS c2d ON d.id = c2d.document_id
                                WHERE d.foreign_id = ?
                                AND c2d.category_id = ?
                                ORDER BY category_id, d.date DESC";

            $result4 = sqlQuery($strQuery4, array($patient_id, $cat_id));

            if ($result4) {
                $file_path = $result4['url'];
                $document_id = $result4['id'];
                unlink($file_path);

                $strQueryD = "DELETE FROM `documents` WHERE id =?";
                $resultD = sqlStatement($strQueryD, array($document_id));

                $strQueryD1 = "DELETE FROM `categories_to_documents` WHERE document_id =?";
                $resultD = sqlStatement($strQueryD1, array($document_id));
            }

            $image_path = $sitesDir . "$site}/documents/{$patient_id}";

            if (!file_exists($image_path)) {
                mkdir($image_path);
            }

            $image_date = date('Y-m-d_H-i-s');

            file_put_contents($image_path . "/" . $image_date . "." . $ext, base64_decode($image_data));


            sqlStatement("lock tables documents read");

            $result = sqlQuery("select max(id)+1 as did from documents");

            sqlStatement("unlock tables");

            if ($result['did'] > 1) {
                $id = $result['did'];
            }

            $hash = sha1_file($image_path . "/" . $image_date . "." . $ext);

            $url = "file://" . $image_path . "/" . $image_date . "." . $ext;

            $size = filesize($url);

            $strQuery = "INSERT INTO `documents`( `id`, `type`, `size`, `date`, `url`, `mimetype`, `foreign_id`, `docdate`, `hash`, `list_id`) 
             VALUES (" . add_escape_custom($id) . ",'" . add_escape_custom($type) . "','" . add_escape_custom($size) . "','" . add_escape_custom($date) . "','" . add_escape_custom($url) . "','" . add_escape_custom($mimetype) . "'," . add_escape_custom($patient_id) . ",'" . add_escape_custom($docdate) . "','" . add_escape_custom($hash) . "','" . add_escape_custom($list_id) . "')";

            $result = sqlStatement($strQuery);

            $strQuery1 = "INSERT INTO `categories_to_documents`(`category_id`, `document_id`) VALUES (" . add_escape_custom($cat_id)."," . add_escape_custom($id).")";

            $result1 = sqlStatement($strQuery1);
        }

        $xml_array['status'] = 0;
        $xml_array['reason'] = 'Patient updated successfully';
    } else {
        $xml_array['status'] = -2;
        $xml_array['reason'] = 'You are not Authorized to perform this action';
    }
} else {
    $xml_array['status'] = -2;
    $xml_array['reason'] = 'Invalid Token';
}

$xml = ArrayToXML::toXml($xml_array, 'Patient');
echo $xml;
?>