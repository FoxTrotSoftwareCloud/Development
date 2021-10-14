<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new transaction();
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';


$instance_payroll = new payroll();
$get_company_data = array();
$filter_array = array();

//filter payroll company statement report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);
    $company = isset($filter_array['company'])?$filter_array['company']:0;
    $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
    $payroll_date = isset($filter_array['payroll_date'])?$filter_array['payroll_date']:'';
    
    $get_company_data = $instance_payroll->get_company_statement_report_data($company,$sort_by,$payroll_date);
    //echo '<pre>';print_r($get_company_data);exit;
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
    $html='<table border="0" width="100%">
                <tr>';
                if(isset($system_logo) && $system_logo != '')
                {
                    $html .='<td width="20%" align="left">'.$img.'</td>';
                }
                    $html .='<td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">COMPANY COMMISSION STATEMENT</td>';
                if(isset($system_company_name) && $system_company_name != '')
                {
                    $html.='<td width="20%" style="font-size:10px;font-weight:bold;text-align:right;">'.$system_company_name.'</td>';
                }
                $html.='</tr>
                <tr>';
                if($company > 0){
                    $company_name = '';
                    foreach($get_company_data as $key=>$val)
                    {
                        $company_name = $key;
                    }
                    $html .='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">'.$company_name.'</td>';
                 
                } else {
                    
                    $html .='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">All Companies</td>';
                }   
            $html .='</tr>
        </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="1" width="100%">
                <tr style="background-color: #f1f1f1;">
                    <td width="5%" style="text-align:center;"><h5>REP#</h5></td>
                    <td width="10%" style="text-align:center;"><h5>NAME</h5></td>
                    <td width="10%" style="text-align:center;"><h5>GROSS COMMISSIONS</h5></td>
                    <td width="10%" style="text-align:center;"><h5>NET COMMISSIONS</h5></td>
                    <td width="10%" style="text-align:center;"><h5>CHARGE</h5></td>
                    <td width="10%" style="text-align:center;"><h5>OVERRIDE COMMISSIONS</h5></td>
                    <td width="10%" style="text-align:center;"><h5>PRIOR BALANCE</h5></td>
                    <td width="10%" style="text-align:center;"><h5>ADVANCES/ ADJUSTMENTS</h5></td>
                    <td width="8%" style="text-align:center;"><h5>FINRA/SIPC</h5></td>
                    <td width="7%" style="text-align:center;"><h5>CHECK AMOUNT</h5></td>
                    <td width="10%" style="text-align:center;"><h5>B/D RETENTION</h5></td>
                </tr>
                <br/>';
    //$pdf->Line(10, 81, 290, 81);
    if($get_company_data != array())
    {
        $report_gross_comm_total = 0;
        $report_net_comm_total = 0;
        $report_charge_total = 0;
        $report_override_comm_total = 0;
        $report_balances_total = 0;
        $report_adjustments_total = 0;
        $report_finra_sipc_total = 0;
        $report_check_amount_total = 0;
        $report_retention_total = 0;
        foreach($get_company_data as $com_key=>$com_data)
        {//echo '<pre>'print_r($get_company_data);exit;
            $html.='<tr>
                   <td colspan="11" style="font-size:8px;font-weight:bold;text-align:left;">'.$com_key.'</td>
            </tr>
            <br/>'; 
            $company_gross_comm_total = 0;
            $company_net_comm_total = 0;
            $company_charge_total = 0;
            $company_override_comm_total = 0;
            $company_balances_total = 0;
            $company_adjustments_total = 0;
            $company_finra_sipc_total = 0;
            $company_check_amount_total = 0;
            $company_retention_total = 0;
            foreach($com_data as $com_sub_key=>$com_sub_data)
            {
                $retention = $com_sub_data['commission_received']-$com_sub_data['check_amount'];
                $finra_sipc = $com_sub_data['finra']+$com_sub_data['sipc'];
                
                $company_gross_comm_total = $company_gross_comm_total+$com_sub_data['commission_received'];
                $company_net_comm_total = $company_net_comm_total+$com_sub_data['commission_paid'];
                $company_charge_total = $company_charge_total+$com_sub_data['charge'];
                $company_override_comm_total = $company_override_comm_total+$com_sub_data['override_rate'];
                $company_balances_total = $company_balances_total+$com_sub_data['balance'];
                $company_adjustments_total = $company_adjustments_total+$com_sub_data['adjustments'];
                $company_finra_sipc_total = $company_finra_sipc_total+$finra_sipc;
                $company_check_amount_total = $company_check_amount_total+$com_sub_data['check_amount'];
                $company_retention_total = $company_retention_total+$retention;
                
                $html.='<tr>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">'.$com_sub_data['fund'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">'.$com_sub_data['broker_firstname'].' '.$com_sub_data['broker_lastname'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:right;">'.$com_sub_data['commission_received'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:right;">'.$com_sub_data['commission_paid'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:right;">'.$com_sub_data['charge'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:right;">'.$com_sub_data['override_rate'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:right;">'.$com_sub_data['balance'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:right;">'.$com_sub_data['adjustments'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:right;">'.number_format($finra_sipc,2).'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:right;">'.$com_sub_data['check_amount'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:right;">'.number_format($retention,2).'</td>
                        </tr>';
            }
            $report_gross_comm_total = $report_gross_comm_total+$company_gross_comm_total;
            $report_net_comm_total = $report_net_comm_total+$company_net_comm_total;
            $report_charge_total = $report_charge_total+$company_charge_total;
            $report_override_comm_total = $report_override_comm_total+$company_override_comm_total;
            $report_balances_total = $report_balances_total+$company_balances_total;
            $report_adjustments_total = $report_adjustments_total+$company_adjustments_total;
            $report_finra_sipc_total = $report_finra_sipc_total+$company_finra_sipc_total;
            $report_check_amount_total = $report_check_amount_total+$company_check_amount_total;
            $report_retention_total = $report_retention_total+$company_retention_total;
            $html.='<tr style="background-color: #f1f1f1;">
                       <td style="font-size:8px;font-weight:bold;text-align:right;" colspan="2">* Company Total *</td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($company_gross_comm_total,2).'</td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($company_net_comm_total,2).'</td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($company_charge_total,2).'</td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($company_override_comm_total,2).'</td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($company_balances_total,2).'</td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($company_adjustments_total,2).'</td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($company_finra_sipc_total,2).'</td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($company_check_amount_total,2).'</td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($company_retention_total,2).'</td>
                    </tr>
                    <br/>';
        }
        $html.='<tr style="background-color: #f1f1f1;">
                   <td style="font-size:8px;font-weight:bold;text-align:right;" colspan="2">*** Report Total ***</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_gross_comm_total,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_net_comm_total,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_charge_total,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_override_comm_total,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_balances_total,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_adjustments_total,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_finra_sipc_total,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_check_amount_total,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_retention_total,2).'</td>
                </tr>';
         
    }
    else
    {
        $html.='<tr>
                    <td style="font-size:11px;font-weight:cold;text-align:center;" colspan="11">No Records Found.</td>
                </tr>';
    }        
    $html.='</table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    if(isset($_GET['open']) && $_GET['open'] == 'output_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->lastPage();
    $pdf->Output('report_payroll_company_statement.pdf', 'I');
    
    exit;
?>