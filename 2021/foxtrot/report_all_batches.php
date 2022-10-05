<?php
require_once "include/config.php";
require_once DIR_FS . "islogin.php";
$instance            = new transaction();
$instance2           = new batches();
$get_logo            = $instance->get_system_logo();
$system_logo         = isset($get_logo['logo']) ? $instance->re_db_input($get_logo['logo']) : '';
$get_company_name    = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name']) ? $instance->re_db_input($get_company_name['company_name']) : '';

$pdf = new RRPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->AddPage('L');
$img = '<img src="upload/logo/' . $system_logo . '" height="25px" />';

$pdf->SetFont('times', 'B', 12);
$pdf->SetFont('times', '', 10);

$return = $instance2->select();

$html = '<table border="0" width="100%">
                <tr>';
$html .= '<td width="20%" align="left">' . $img . '</td>';
$html .= '<td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">ALL BATCHES</td>';

if (isset($system_company_name) && $system_company_name != '') {
    $html .= '<td width="20%" style="font-size:10px;font-weight:bold;text-align:right;">' . $system_company_name . '</td>';
}

$html .= '</tr>
        </table>';

$pdf->writeHTML($html, false, 0, false, 0);
$pdf->Ln(2);

$batchTable = '<table border="0" cellpadding="1" width="100%">
                <tr style="background-color: #f1f1f1;">
                    <td style="text-align:center;"><h5>BATCH#</h5></td>
                    <td style="text-align:center;"><h5>BATCH DATE</h5></td>
                    <td style="text-align:center;"><h5>DESCRIPTION</h5></td>
                    <td style="text-align:center;"><h5>SPONSOR</h5></td>
                    <td style="text-align:center;"><h5>AMT RECIEVED</h5></td>
                    <td style="text-align:center;"><h5>AMT POSTED</h5></td>
                </tr>
                <br/>';

foreach ($return as $batch) {
    $batch['sponser_name'] = $instance2->get_name_by_id($batch['sponsor']);
    $batchTable .= '<tr>
                            <td style="font-size:8px;font-weight:normal;text-align:center;">' . $batch["id"] . '</td>
                            <td style="font-size:8px;font-weight:normal;text-align:center;">' . $batch["batch_date"] . '</td>
                            <td style="font-size:8px;font-weight:normal;text-align:center;">' . $batch["batch_desc"] . '</td>
                            <td style="font-size:8px;font-weight:normal;text-align:center;">' . $batch["sponser_name"] . '</td>
                            <td style="font-size:8px;font-weight:normal;text-align:center;">$' . number_format($batch["check_amount"], 2) . '</td>
                            <td style="font-size:8px;font-weight:normal;text-align:center;">$' . number_format($batch["commission_amount"], 2) . '</td>
                        </tr>';
}

$batchTable .= '</table>';

$pdf->writeHTML($batchTable, false, 0, false, 0);
$pdf->Ln(5);
$pdf->lastPage();
$pdf->Output('report_all_batches.pdf', 'I');

exit;
