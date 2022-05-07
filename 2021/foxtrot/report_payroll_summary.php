<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
// $instance = new transaction();
// $get_reconci_data = array();
$reportTitle = 'PAYROLL SUMMARY REPORT';
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';

$instance_payroll = new payroll();
$filter_array = $reportData = $summaryData = array();
$html = '';

//filter payroll reconciliation report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);
    $payroll_id = isset($filter_array['payroll_id'])?$filter_array['payroll_id']:'';
    $get_payroll_upload = $instance_payroll->get_payroll_uploads($payroll_id);
    $payroll_date = date('m/d/Y', strtotime($get_payroll_upload['payroll_date']));
    $output_type = isset($filter_array['output_type'])?$filter_array['output_type']:'';
    // 5/6/22 Company Filter
    $instance_company = new manage_company();
    $company = isset($filter_array['company'])?$filter_array['company']:0;
    $companyData = ($company==0) ? ['company_name'=>'All Companies']: $instance_company->select_company_by_id($company);

    $summaryData = $instance_payroll->select_current_payroll($payroll_id, 1, $company);
    $reportData = $instance_payroll->get_payroll_summary_report_data($payroll_id, $company);
}
?>
<?php

    // create new PDF document
    $pdf = new RRPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // add a page
    $pdf->AddPage('L');
    // Title
    $img = '<img src="'.SITE_URL."upload/logo/".$system_logo.'" height="25px" />';
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html=
        '<table border="0" width="100%">
            <tr>';
            if(isset($system_logo) && $system_logo != '')
            {
                $html .='<td width="20%" align="left">'.$img.'</td>';
            }
                $html .='<td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">'.$reportTitle.'</td>';
            if(isset($system_company_name) && $system_company_name != '')
            {
                $html.='<td width="20%" style="font-size:10px;font-weight:bold;text-align:right;">'.$system_company_name.'</td>';
            }
            $html .= '</tr>';
            $html .=
                '<tr>'
                    .'<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">'.$companyData['company_name'].'</td>'
                .'</tr>'
                .'<tr>'
                    .'<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">'.$payroll_date.'</td>'
                .'</tr>'
        .'</table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html=
        '<table border="0" cellpadding="1" width="100%">'
            .'<tr style="background-color: #f1f1f1;">'
                .'<td width="25%" style="font-size:12px;text-align:center;"><h5>PRODUCT CATEGORY</h5></td>'
                .'<td width="15%" style="font-size:12px;text-align:right; "><h5>TRADE COUNT</h5></td>'
                .'<td width="20%" style="font-size:12px;text-align:right;"><h5>GROSS COMMISSION</h5></td>'
                .'<td width="20%" style="font-size:12px;text-align:right;"><h5>% of GROSS</h5></td>'
                .'<td width="20%" style="font-size:12px;text-align:right;"><h5>NET COMMISSION</h5></td>'
            .'</tr><br/>';
            
    if($reportData != array()) {
        $report_trade_count_total = 0;
        $report_gross_commission_total = 0;
        $report_net_commission_total = 0;
        
        foreach($reportData AS $dataKey=>$dataRow) {   
            $pctOfGross = ($summaryData[0]['GROSS_COMMISSION']==0 ? 0 : round($dataRow['GROSS_COMMISSION']*100 /$summaryData[0]['GROSS_COMMISSION'], 0));

            $html.=
                '<tr>'
                    .'<td style="font-size:10px;font-weight:normal;text-align:left;">'.$dataRow['PRODUCT_CATEGORY'].'</td>'
                    .'<td style="font-size:10px;font-weight:normal;text-align:right;">'.number_format($dataRow['TRADE_COUNT'],0).'</td>'
                    .'<td style="font-size:10px;font-weight:normal;text-align:right;">'.$instance_payroll->payroll_accounting_format($dataRow['GROSS_COMMISSION'],2).'</td>'
                    .'<td style="font-size:10px;font-weight:normal;text-align:right;">'.(string)$pctOfGross."%".'</td>'
                    .'<td style="font-size:10px;font-weight:normal;text-align:right;">'.$instance_payroll->payroll_accounting_format($dataRow['NET_COMMISSION'],2).'</td>'
                .'</tr><br/>';
                
            $report_trade_count_total += $dataRow['TRADE_COUNT'];
            $report_gross_commission_total += $dataRow['GROSS_COMMISSION'];
            $report_net_commission_total += $dataRow['NET_COMMISSION'];
        }

        $html.=
            '<tr style="background-color: #f1f1f1;">'
                .'<td style="font-size:10px;font-weight:bold;text-align:right;">* TRANSACTION TOTALS *</td>'
                .'<td style="font-size:10px;font-weight:bold;text-align:right;">'.number_format($report_trade_count_total,0).'</td>'
                .'<td style="font-size:10px;font-weight:bold;text-align:right;">'.$instance_payroll->payroll_accounting_format($report_gross_commission_total,2).'</td>'
                .'<td style="font-size:10px;font-weight:bold;text-align:right;">100%</td>'
                .'<td style="font-size:10px;font-weight:bold;text-align:right;">'.$instance_payroll->payroll_accounting_format($report_net_commission_total,2).'</td>'
            .'</tr>'
            // .'<tr style="background-color: #f1f1f1;">'
            //     .'<td style="font-size:12px;font-weight:bold;text-align:center;" colspan="5"></td>'
            // .'</tr>'
            // .'<tr style="background-color: #f1f1f1;">'
            //     .'<td style="font-size:10px;font-weight:bold;text-align:center;" colspan="3"></td>'
            //     .'<td style="font-size:10px;font-weight:bold;text-align:center;" colspan="2">* PAYOUT SUMMARY *</td>'
            // .'</tr>'
                .'<tr style="background-color: #f1f1f1;">'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:left;" colspan="3"></td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;">FINRA</td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;">'.$instance_payroll->payroll_accounting_format(-$summaryData[0]['FINRA_TOTAL'],2).'</td>'
            .'</tr>'
            .'<tr style="background-color: #f1f1f1;">'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:left;" colspan="3"></td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;">SIPC</td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;">'.$instance_payroll->payroll_accounting_format(-$summaryData[0]['SIPC_TOTAL'],2).'</td>'
            .'</tr>'
            .'<tr style="background-color: #f1f1f1;">'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:left;" colspan="2"></td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;" colspan="2">TAXABLE ADJUSTMENTS</td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;">'.$instance_payroll->payroll_accounting_format($summaryData[0]['TAXABLE_ADJUSTMENTS_TOTAL'],2).'</td>'
            .'</tr>'
            .'<tr style="background-color: #f1f1f1;">'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:left;" colspan="2"></td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;" colspan="2">NON-TAX ADJUSTMENTS</td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;">'.$instance_payroll->payroll_accounting_format($summaryData[0]['NON-TAXABLE_ADJUSTMENTS_TOTAL'],2).'</td>'
            .'</tr>'
            .'<tr style="background-color: #f1f1f1;">'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:left;" colspan="2"></td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;" colspan="2">OVERRIDES PAID</td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;">'.$instance_payroll->payroll_accounting_format($summaryData[0]['OVERRIDES_PAID_TOTAL'],2).'</td>'
            .'</tr>'
            .'<tr style="background-color: #f1f1f1;">'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:left;" colspan="2"></td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;text-decoration: underline" colspan="2">PRIOR BALANCES</td>'
                .'<td style="font-size:10px;font-weight:normal;font-style:italic;text-align:right;text-decoration: underline">'.$instance_payroll->payroll_accounting_format($summaryData[0]['PRIOR_BALANCE_TOTAL'],2).'</td>'
            .'</tr>'
            .'<tr style="background-color: #f1f1f1;">'
                .'<td style="font-size:10px;font-weight:bold;text-align:left;" colspan="2"></td>'
                .'<td style="font-size:10px;font-weight:bold;text-align:right;" colspan="2">TOTAL PAYROLL</td>'
                .'<td style="font-size:10px;font-weight:bold;text-align:right;">'.$instance_payroll->payroll_accounting_format($summaryData[0]['TOTAL_PAYROLL'],2).'</td>'
            .'</tr>'
            .'<tr style="background-color: #f1f1f1;">'
                .'<td style="font-size:10px;font-weight:bold;text-align:center;" colspan="5"></td>'
            .'</tr>'
            .'<tr style="background-color: #f1f1f1;">'
                .'<td style="font-size:10px;font-weight:bold;text-align:left;" colspan="2"></td>'
                .'<td style="font-size:10px;font-weight:bold;text-align:right;" colspan="2">TOTAL CHECKS</td>'
                .'<td style="font-size:10px;font-weight:bold;text-align:right;">'.$instance_payroll->payroll_accounting_format($summaryData[0]['TOTAL_CHECKS'],2).'</td>'
            .'</tr>'
            .'<tr style="background-color: #f1f1f1;">'
                .'<td style="font-size:10px;font-weight:bold;text-align:left;" colspan="2"></td>'
                .'<td style="font-size:10px;font-weight:bold;text-align:right;" colspan="2">BALANCES CARRIED FORWARD</td>'
                .'<td style="font-size:10px;font-weight:bold;text-align:right;">'.$instance_payroll->payroll_accounting_format($summaryData[0]['TOTAL_CARRIED_FORWARD'],2).'</td>'
            .'</tr><br/>';
    }
    else
    {
        $html .=
            '<tr>'
                .'<td style="font-size:11px;font-weight:cold;text-align:center;" colspan="9">No Records Found.</td>'
            .'</tr>';
    }           
    $html.='</table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    if(isset($_GET['open']) && $_GET['open'] == 'output_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->lastPage();
    $pdf->Output('report_payroll_summary.pdf', 'I');
    
    exit;
?>