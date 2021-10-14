<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new transaction();

$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';

$instance_payroll = new payroll();
$filter_array = array();
$get_adjustments_data = array();
$company = 0;
$payroll_date = '';
$sort_by = '';
$output_type = '';

//filter payroll adjustments log report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);//echo '<pre>';print_r($filter_array);exit;
    $company = isset($filter_array['company'])?$filter_array['company']:0;
    $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
    $payroll_date = isset($filter_array['payroll_date'])?$filter_array['payroll_date']:'';
    $output_type = isset($filter_array['output_type'])?$filter_array['output_type']:'';
    
    $get_adjustments_data = $instance_payroll->get_adjustments_report_data($company,$payroll_date,$sort_by,$output_type);
}
if($company>0)
{
    $company_name = isset($get_adjustments_data['company_name'])?$get_adjustments_data['company_name']:'';
}
else
{
    $company_name = 'All Company';
}
if(isset($sort_by) && $sort_by == 1)
{
    $sorted_by = 'Sorted by Rep Name';
}
else if(isset($sort_by) && $sort_by == 2)
{
    $sorted_by = 'Sorted by Rep Number';
}
else if(isset($sort_by) && $sort_by == 3)
{
    $sorted_by = 'Sorted by Category';
}
else if(isset($sort_by) && $sort_by == 4)
{
    $sorted_by = 'Sorted by G/L Account';
}
else
{
    $sorted_by='';
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
                $html .='<td width="60%" style="font-size:10px;font-weight:normal;text-align:center;">'.$company_name.'</td>';
                if(isset($system_company_name) && $system_company_name != '')
                {
                    $html.='<td width="20%" style="font-size:10px;font-weight:bold;text-align:right;">'.$system_company_name.'</td>';
                }
                $html.='</tr>';
                $html.='<tr>
                        <td width="100%" style="font-size:14px;font-weight:bold;text-align:center;">COMMISSION ADJUSTMENT LOG</td>
                        </tr>';
                $html.='<tr>';
                    $html.='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">'.$sorted_by.'</td>';
                $html .='</tr>
        </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="1" width="100%">
                <tr style="background-color: #f1f1f1;">';
                if(isset($output_type) && $output_type != '2')
                {
                    $html.='<td width="10%" style="text-align:center;"><h5>ADJUST#</h5></td>';
                }
                if(isset($output_type) && $output_type == '2')
                {
                    $html.='<td width="73%" style="text-align:center;" colspan="5"><h5>REP#</h5></td>';
                }
                else
                {
                    $html.='<td width="10%" style="text-align:center;"><h5>REP#</h5></td>';
                }
                if(isset($output_type) && $output_type != '2')
                {
                    $html.='<td width="10%" style="text-align:center;"><h5>CLEAR NUMBER</h5></td>
                    <td width="20%" style="text-align:center;"><h5>DESCRIPTION</h5></td>
                    <td width="20%" style="text-align:center;"><h5>CATEGORY</h5></td>';
                }
                    $html.='<td width="9%" style="text-align:center;"><h5>TAXABLE AMOUNT</h5></td>
                    <td width="9%" style="text-align:center;"><h5>NON TAXABLE AMOUNT</h5></td>
                    <td width="9%" style="text-align:center;"><h5>ADVANCE</h5></td>
                </tr>
                <br/>';
    //$pdf->Line(10, 81, 290, 81);
    if(isset($get_adjustments_data['data']) && $get_adjustments_data['data'] != array())
    {
        $report_taxable_adjustments = 0;
        $report_non_taxable_adjustments = 0;
        $report_advance = 0;
        
        if(isset($output_type) && ($output_type == '1' || $output_type == '3'))
        {
            foreach($get_adjustments_data['data'] as $adj_key=>$adj_data)
            {
                
                $html.='<tr>
                            <td colspan="11" style="font-size:8px;font-weight:bold;text-align:left;">BROKER #'.strtoupper($adj_key).'</td>
                        </tr>
                        <br/>'; 
                        $broker_taxable_adjustments = 0;
                        $broker_non_taxable_adjustments = 0;
                        $broker_advance = 0;
                        foreach($adj_data as $adj_sub_key=>$adj_sub_data)
                        {
                            if(isset($adj_sub_data['payroll_category']) && strtolower($adj_sub_data['payroll_category']) == strtolower('ADVANCE'))
                            {
                                $taxable_adjustments = 0;
                                $non_taxable_adjustments = 0;
                                $advance = isset($adj_sub_data['adjustment_amount']) && $adj_sub_data['adjustment_amount'] != '' ?$adj_sub_data['adjustment_amount']:0;
                            }
                            else
                            {
                                $taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 1 ?$adj_sub_data['adjustment_amount']:0;
                                $non_taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 0 ?$adj_sub_data['adjustment_amount']:0;
                                $advance = 0;
                            }
                            $broker_taxable_adjustments = $broker_taxable_adjustments+$taxable_adjustments;
                            $broker_non_taxable_adjustments = $broker_non_taxable_adjustments+$non_taxable_adjustments;
                            $broker_advance = $broker_advance+$advance;
                        $html.='<tr>
                                   <td style="font-size:8px;font-weight:normal;text-align:center;">'.$adj_sub_data['id'].'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:center;">'.$adj_sub_data['broker_id'].'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:center;">'.$adj_sub_data['fund'].'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:center;">'.$adj_sub_data['description'].'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:center;">'.$adj_sub_data['payroll_category'].'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:right;">'.number_format($taxable_adjustments,2).'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:right;">'.number_format($non_taxable_adjustments,2).'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:right;">'.number_format($advance,2).'</td>
                                </tr>';
                        }
                        $report_taxable_adjustments = $report_taxable_adjustments+$broker_taxable_adjustments;
                        $report_non_taxable_adjustments = $report_non_taxable_adjustments+$broker_non_taxable_adjustments;
                        $report_advance = $report_advance+$broker_advance;
                        $html.='<br/>
                                <tr style="background-color: #f1f1f1;">
                                   <td style="font-size:8px;font-weight:bold;text-align:right;" colspan="5">* #'.strtoupper($adj_key).' TOTAL *</td>
                                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($broker_taxable_adjustments,2).'</td>
                                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($broker_non_taxable_adjustments,2).'</td>
                                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($broker_advance,2).'</td>
                                </tr>';
                        
             
             }
         }
         else if(isset($output_type) && $output_type == '2')
         {
            foreach($get_adjustments_data['data'] as $adj_key=>$adj_data)
            {
                    $broker_taxable_adjustments = 0;
                    $broker_non_taxable_adjustments = 0;
                    $broker_advance = 0;
                    foreach($adj_data as $adj_sub_key=>$adj_sub_data)
                    {
                        if(isset($adj_sub_data['payroll_category']) && strtolower($adj_sub_data['payroll_category']) == strtolower('ADVANCE'))
                        {
                            $taxable_adjustments = 0;
                            $non_taxable_adjustments = 0;
                            $advance = isset($adj_sub_data['adjustment_amount']) && $adj_sub_data['adjustment_amount'] != '' ?$adj_sub_data['adjustment_amount']:0;
                        }
                        else
                        {
                            $taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 1 ?$adj_sub_data['adjustment_amount']:0;
                            $non_taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 0 ?$adj_sub_data['adjustment_amount']:0;
                            $advance = 0;
                        }
                        $broker_taxable_adjustments = $broker_taxable_adjustments+$taxable_adjustments;
                        $broker_non_taxable_adjustments = $broker_non_taxable_adjustments+$non_taxable_adjustments;
                        $broker_advance = $broker_advance+$advance;
                    }
                    $report_taxable_adjustments = $report_taxable_adjustments+$broker_taxable_adjustments;
                    $report_non_taxable_adjustments = $report_non_taxable_adjustments+$broker_non_taxable_adjustments;
                    $report_advance = $report_advance+$broker_advance;
                    $html.='<tr>
                               <td style="font-size:8px;font-weight:normal;text-align:left;" colspan="5">BROKER #'.strtoupper($adj_key).'</td>
                               <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($broker_taxable_adjustments,2).'</td>
                               <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($broker_non_taxable_adjustments,2).'</td>
                               <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($broker_advance,2).'</td>
                            </tr>';
                    
             
             }
         }
         
         $html.='<br/>
                <tr style="background-color: #f1f1f1;">
                   <td style="font-size:8px;font-weight:bold;text-align:right;" colspan="5">*** REPORT TOTALS **** </td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_taxable_adjustments,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_non_taxable_adjustments,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($report_advance,2).'</td>
                </tr>
                <br/>';
    }
    else
    {
        $html.='<tr>
                    <td style="font-size:13px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
                </tr>';
    }         
    $html.='</table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    if(isset($_GET['open']) && $_GET['open'] == 'output_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->lastPage();
    $pdf->Output('report_payroll_adjustment.pdf', 'I');
    
    exit;
?>