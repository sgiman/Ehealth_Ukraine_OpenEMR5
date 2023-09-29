<?php

/**
 * api/report_visits.php Visits reports .
 *
 * API is allowed to get patient visit reports in html and pdf format.
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

require_once("includes/pdflibrary/config/lang/eng.php");
require_once("includes/pdflibrary/tcpdf.php");

require_once("includes/formatting.inc.php");
require_once "includes/options.inc.php";

$xml_string = "";
$xml_string = "<list>";

$single_record_header = "";
$single_record = '';

$html = "<html>
            <head>
                <style>
                        
                              #report_parameters {
                                    background-color: #ececec;
                                        margin-top:10px;
                                }

                                #report_parameters table {
                                    border: solid 1px;
                                        width: 100%;
                                    border-collapse: collapse;
                                }
                                #report_parameters table td {
                                    padding: 5px;
                                }

                                #report_parameters table table {
                                    border: 0px;
                                    border-collapse: collapse;
                                        font-size: 0.8em;
                                }

                                #report_parameters table table td.label {
                                        text-align: right;
                                }

                                #report_results table {
                                border-top: 1px solid black;
                                border-bottom: 1px solid black;
                                border-left: 1px solid black;
                                border-right: 1px solid black;
                                width: 100%;
                                border-collapse: collapse;
                                margin-top: 1px;
                                }
                                #report_results table thead {
                                    padding: 5px;
                                    display: table-header-group;
                                    background-color: #ddd;
                                        text-align:left;
                                        font-weight: bold;
                                        font-size: 0.7em;
                                }
                                #report_results table th {
                                    border-bottom: 1px solid black;
                                        padding: 5px;
                                }
                                #report_results table td {
                                        padding: 5px;
                                    border-bottom: 1px dashed;
                                        font-size: 0.8em;
                                }
                                .report_totals td {
                                    background-color: #77ff77;
                                    font-weight: bold;
                                }


                </style>
            </head>
            <body>
            <div id='report_results' style=\" margin-top:10px;\">
            <h3>Report - Encounters {$from_date} to {$to_date}</h3>
            <table style=\"border-top: 1px solid black;
                                border-bottom: 1px solid black;
                                border-left: 1px solid black;
                                border-right: 1px solid black;
                                width: 100%;
                                border-collapse: collapse;
                                margin-top: 1px;\">
                 <thead style=\"padding: 5px;
                                    display: table-header-group;
                                    background-color: #ddd;
                                        text-align:left;
                                        font-weight: bold;
                                        font-size: 0.7em;\">                
                <tr>
                    <th style=\"border-bottom: 1px solid black; padding: 5px;\">Provider</th>
                    <th style=\"border-bottom: 1px solid black; padding: 5px;\">Date</th>
                    <th style=\"border-bottom: 1px solid black; padding: 5px;\">Patient</th>
                    <th style=\"border-bottom: 1px solid black; padding: 5px;\">ID</th>
                    <th style=\"border-bottom: 1px solid black; padding: 5px;\">Status</th>
                    <th style=\"border-bottom: 1px solid black; padding: 5px;\">Encounter</th>
                    <th style=\"border-bottom: 1px solid black; padding: 5px;\">Form</th>
                    <th style=\"border-bottom: 1px solid black; padding: 5px;\">Coding</th>
                </tr>
                </thead>
            ";
$single_record_header .= $html;

function bucks($amount) {
    if ($amount)
        printf("%.2f", $amount);
}

function show_doc_total($lastdocname, $doc_encounters) {
    if ($lastdocname) {
        $xml_string .= "<doctor>$lastdocname</doctor>";
        $xml_string .= "<visits>$doc_encounters</visits>";
    }
}

$token = $_GET['token'];
$facility = $_GET['facility'];
$provider = $_GET['provider'];
$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];
$details = $_GET['details'] == 'false' ? false : true;
$new_patients = $_GET['new_patients'] == 'false' ? false : true;

//$token = 'df19b7027c8cab07db1e9eef0566e1c9';
//$facility = ''; //6
//$provider = ''; //7
//$from_date = '2016-08-01';
//$to_date = '2016-08-31';
//$details = true;
//$new_patients = false;


if ($userId = validateToken($token)) {
    $user = getUsername($userId);
    $acl_allow = acl_check('encounters', 'auth_a', $user);
    if ($acl_allow) {


        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("Haroon");
        $pdf->SetTitle("My Report");
        $pdf->SetSubject("My Report");
//        $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $query = "SELECT " .
                "fe.encounter, fe.date, fe.reason, " .
                "f.formdir, f.form_name, " .
                "p.fname, p.mname, p.lname, p.pid, p.pubpid, " .
                "u.lname AS ulname, u.fname AS ufname, u.mname AS umname " .
                "FROM ( form_encounter AS fe, forms AS f ) " .
                "LEFT OUTER JOIN patient_data AS p ON p.pid = fe.pid " .
                "LEFT JOIN users AS u ON u.id = fe.provider_id " .
                "WHERE f.encounter = fe.encounter AND f.formdir = 'newpatient' ";

        if ($to_date) {
            $query .= "AND fe.date >= '" . add_escape_custom($from_date) . " 00:00:00' AND fe.date <= '" . add_escape_custom($to_date)." 23:59:59' ";
        } else {
            $query .= "AND fe.date >= '".add_escape_custom($from_date)." 00:00:00' AND fe.date <= '" . add_escape_custom($from_date)." 23:59:59' ";
        }
        if ($provider) {
            $query .= "AND fe.provider_id = '" . add_escape_custom($provider)."' ";
        }
        if ($facility) {
            $query .= "AND fe.facility_id = '" . add_escape_custom($facility)."' ";
        }
        if ($new_patients) {
            $query .= "AND fe.date = (SELECT MIN(fe2.date) FROM form_encounter AS fe2 WHERE fe2.pid = fe.pid) ";
        }


        $res = sqlStatement($query);
        $numRows = sqlNumRows($res);
        
        if ($numRows > 0) {
            $lastdocname = "";
            $doc_encounters = 0;
            while ($row = sqlFetchArray($res)) {
                $patient_id = $row['pid'];

                $docname = '';
                if (!empty($row['ulname']) || !empty($row['ufname'])) {
                    $docname = $row['ulname'];
                    if (!empty($row['ufname']) || !empty($row['umname']))
                        $docname .= ', ' . $row['ufname'] . ' ' . $row['umname'];
                }

                $errmsg = "";
                if ($details) {
                    // Fetch all other forms for this encounter.
                    $encnames = '';
                    $encarr = getFormByEncounter($patient_id, $row['encounter'], "formdir, user, form_name, form_id");
                    if ($encarr != '') {
                        foreach ($encarr as $enc) {
                            if ($enc['formdir'] == 'newpatient')
                                continue;
                            if ($encnames)
                                $encnames .= '<br />';
                            $encnames .= $enc['form_name'];
                        }
                    }

                    // Fetch coding and compute billing status.
                    $coded = "";
                    $billed_count = 0;
                    $unbilled_count = 0;
                    if ($billres = getBillingByEncounter($row['pid'], $row['encounter'], "code_type, code, code_text, billed")) {
                        foreach ($billres as $billrow) {
                            // $title = addslashes($billrow['code_text']);
                            if ($billrow['code_type'] != 'COPAY' && $billrow['code_type'] != 'TAX') {
                                $coded .= $billrow['code'] . ', ';
                                if ($billrow['billed'])
                                    ++$billed_count; else
                                    ++$unbilled_count;
                            }
                        }
                        $coded = substr($coded, 0, strlen($coded) - 2);
                    }

                    // Figure product sales into billing status.
                    $sres = sqlStatement("SELECT billed FROM drug_sales " .
                            "WHERE pid = '{$row['pid']}' AND encounter = '{$row['encounter']}'");
                    while ($srow = sqlFetchArray($sres)) {
                        if ($srow['billed'])
                            ++$billed_count; else
                            ++$unbilled_count;
                    }

                    // Compute billing status.
                    if ($billed_count && $unbilled_count)
                        $status = xl('Mixed');
                    else if ($billed_count)
                        $status = xl('Closed');
                    else if ($unbilled_count)
                        $status = xl('Open');
                    else
                        $status = xl('Empty');

                    $xml_string .= "<visit>";
                    $xml_string .= "<provider>" . $docname . "</provider>";
                    $xml_string .= "<date>" . oeFormatShortDate(substr($row['date'], 0, 10)) . "</date>";
                    $xml_string .= "<patient>" . $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'] . "</patient>";
                    $xml_string .= "<id>" . $row['pubpid'] . "</id>";
                    $xml_string .= "<status>" . $status . "</status>";
                    $xml_string .= "<reason>" . trim($row['reason']) . "</reason>";
                    $xml_string .= "<form>" . str_replace('<br />', '\n', $encnames) . "</form>";
                    $xml_string .= "<coding>" . $coded . "</coding>";

                    $old_docname == '';
                    $display_docname = $docname == $old_docname ? '' : $docname;
                    $single_record = "
                            <tr>
                                <td style=\"padding: 5px; border-bottom: 1px dashed black; font-size: 0.8em;\">{$display_docname}</td>
                                <td style=\"padding: 5px; border-bottom: 1px dashed black; font-size: 0.8em;\">" . oeFormatShortDate(substr($row['date'], 0, 10)) . "</td>
                                <td style=\"padding: 5px; border-bottom: 1px dashed black; font-size: 0.8em;\">" . $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'] . "</td>
                                <td style=\"padding: 5px; border-bottom: 1px dashed black; font-size: 0.8em;\">" . $row['pubpid'] . "</td>
                                <td style=\"padding: 5px; border-bottom: 1px dashed black; font-size: 0.8em;\">" . $status . "</td>
                                    
                                <td style=\"padding: 5px; border-bottom: 1px dashed black; font-size: 0.8em;\">" . trim($row['reason']) . "</td>
                                <td style=\"padding: 5px; border-bottom: 1px dashed black; font-size: 0.8em;\">" . str_replace('<br />', ',', $encnames) . "</td>
                                <td style=\"padding: 5px; border-bottom: 1px dashed black; font-size: 0.8em;\">" . $coded . "</td>
                            </tr>";

                    $html .= $single_record;
                    $old_docname = $docname;

                    $complete_single_record = $single_record_header . $single_record . "<table></div></body></html>";
                    $xml_string .= "<visit_html>" . base64_encode($complete_single_record) . "</visit_html>";
//                echo $single_record_header .$single_record. "<table></div></body></html>";
//                $pdf1 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//                $pdf1->SetCreator(PDF_CREATOR);
//                $pdf1->SetAuthor("Haroon");
//                $pdf1->SetTitle("My Report");
//                $pdf1->SetSubject("My Report");
////        $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
//                $pdf1->setPrintHeader(false);
//                $pdf1->setPrintFooter(false);
//                $pdf1->AliasNbPages();
//                $pdf1->AddPage();
//                $pdf1->writeHTML($complete_single_record, true, false, true, false, '');
//                $pdf_base64 = $pdf1->Output("", "E");
//                $temp = explode('filename=""', $pdf_base64);
//                
//                $xml_string .= "<visit_pdf>" . $temp[1] . "</visit_pdf>";

                    $xml_string .= "</visit>";
                } else {
                    if ($docname != $lastdocname) {
                        //show_doc_total($lastdocname, $doc_encounters);
                        if ($lastdocname) {
                            $xml_string .= "<provider>$lastdocname</provider>";
                            $xml_string .= "<visits>$doc_encounters</visits>";
                        }
                        $doc_encounters = 0;
                    }
                    ++$doc_encounters;
                }
                $lastdocname = $docname;
            }

            $html .= "
                    <table>
                    </div>
                </body>
            </html>";
//        echo $html;exit;

            $xml_string .= "<html_report>" . base64_encode($html) . "</html_report>";

            $pdf->writeHTML($html, true, false, true, false, '');

            $pdf_base64 = $pdf->Output("", "E");
            $temp = explode('filename=""', $pdf_base64);
            $xml_string .= "<pdf_report>" . $temp[1] . "</pdf_report>";

            if (!$details) {
                // show_doc_total($lastdocname, $doc_encounters);
                $xml_string .= "<provider>$lastdocname</provider>";
                $xml_string .= "<visits>$doc_encounters</visits>";
            }
        } else {
            $xml_string .= "<status>-1</status>";
            $xml_string .= "<reason>No Records Found</reason>";
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