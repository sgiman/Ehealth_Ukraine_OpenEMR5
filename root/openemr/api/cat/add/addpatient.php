<?php

/**
 * api/addpatient.php add new Patient.
 *
 * Api add new patient
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
require_once("$srcdir/documents.php");
$xml_array = array();

$token = $_GET['token'];

$title = $_GET['title'];
$language = $_GET['language'];
$firstname = $_GET['firstname'];
$lastname = $_GET['lastname'];
$middlename = $_GET['middlename'];
$dob = $_GET['dob'];
$street = $_GET['street'];
$postal_code = $_GET['postal_code'];
$city = $_GET['city'];
$state = $_GET['state'];
$country_code = $_GET['country_code'];
$ss = $_GET['ss'];
$occupation = $_GET['occupation'];

$phone_home = $_GET['phone_home'];
$phone_biz = $_GET['phone_biz'];
$phone_contact = $_GET['phone_contact'];
$phone_cell = $_GET['phone_cell'];

$status = $_GET['status'];
$drivers_lincense = $_GET['drivers_license'];

$contact_relationship = $_GET['contact_relationship'];
$mothersname = $_GET['mothersname'];
$guardiansname = $_GET['guardiansname'];

$sex = $_GET['sex'];
$email = $_GET['email'];
$race = $_GET['race'];
$ethnicity = $_GET['ethnicity'];
$usertext1 = $_GET['notes'];
$nickname = $_GET['nickname'];

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

        $provider_id = $userId;
        $patientId = 1;
        $pid = 1;
        sqlStatement("lock tables patient_data read");
        $result = sqlQuery("select max(pid)+1 as pid from patient_data");
        sqlStatement("unlock tables");

        if ($result['pid'] > 1) {
            $patientId = $result['pid'];
            $pid = $result['pid'];
        }

        $postData = array(
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
            'pubpid' => $pid,
            'usertext1' => $usertext1,
            'genericname1' => $nickname,
            'mothersname' => $mothersname,
            'guardiansname' => $guardiansname,
            'providerID' => $provider_id,
            'ref_providerID' => 0,
            'financial_review' => '0000-00-00 00:00:00',
            'hipaa_allowsms' => '',
            'hipaa_allowemail' => '',
            'deceased_date' => '0000-00-00 00:0'
        );


        $p_id = updatePatientData($patientId, $postData, $create = true);

        if ($p_id) {

            $primary_insurace_data = getInsuranceData($p_id);

            $secondary_insurace_data = getInsuranceData($p_id, 'secondary');

            $other_insurace_data = getInsuranceData($p_id, 'tertiary');

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
                        $p_id, $type = "secondary", $s_insurance_company, $policy_number = $s_insurance_id, $group_number = $s_group_number, $plan_name = $s_plan_name, $subscriber_lname = "", $subscriber_mname = "", $subscriber_fname = "", $subscriber_relationship = $s_subscriber_relationship, $subscriber_ss = "", $subscriber_DOB = "", $subscriber_street = "", $subscriber_postal_code = "", $subscriber_city = "", $subscriber_state = "", $subscriber_country = "", $subscriber_phone = "", $subscriber_employer = $s_subscriber_employer_status, $subscriber_employer_street = "", $subscriber_employer_city = "", $subscriber_employer_postal_code = "", $subscriber_employer_state = "", $subscriber_employer_country = "", $copay = "", $subscriber_sex = "", $effective_date = "0000-00-00", $accept_assignment = "TRUE");
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

            $xml_array['status'] = 0;
            $xml_array['patientId'] = $patientId;
            $xml_array['reason'] = 'The Patient has been added';
        } else {
            $xml_array['status'] = -1;
            $xml_array['reason'] = 'ERROR: Sorry, there was an error processing your data. Please re-submit the information again.';
        }

        if ($image_data) {
            $id = 1;
            $type = "file_url";
            $size = '';
            $date = date('Y-m-d H:i:s');
            $url = '';
            $mimetype = 'image/png';
            $hash = '';
            $patient_id = $patientId;
            $ext = 'png';
            $cat_title = 'Patient Photograph';


            $cat_id = document_category_to_id($cat_title);

            $image_path = $sitesDir . "{$site}/documents/{$patient_id}";


            if (!file_exists($image_path)) {
                mkdir($image_path);
            }

            $image_date = date('YmdHis');
            $image_root_path = $image_path . "/" . $image_date . "." . $ext;
            
            file_put_contents($image_root_path, base64_decode($image_data));

            $res = addNewDocument($image_date . "." . $ext, $mimetype, $image_root_path, 0, filesize($image_root_path), $userId, $patient_id, $cat_id, $higher_level_path = '', $path_depth = '1');

        }
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