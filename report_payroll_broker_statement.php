<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new transaction();
$get_trans_data = array();
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
//print_r($system_logo);exit;

$filter_array = array();
$company = 0;
$sort_by='';
//filter payroll company statement report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);//echo '<pre>';print_r($filter_array);exit;
    $company = isset($filter_array['company'])?$filter_array['company']:0;
    $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
    
    $get_trans_data = $instance->select_data_report($company,$sort_by);
    
}
if(isset($_GET['batch_id']))
{
    $batch = $_GET['batch_id'];
    $get_trans_data = $instance->select_data_report('','',$batch,'','','');
}
$batch_desc = isset($get_trans_data[0]['batch_desc'])?$instance->re_db_input($get_trans_data[0]['batch_desc']):'';
$total_amount_invested = 0;
$total_commission_received = 0;
$total_charges = 0;
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
                    $html .='<td width="60%" style="font-size:12px;font-weight:bold;text-align:center;">All Companies</td>';
                if(isset($system_company_name) && $system_company_name != '')
                {
                    $html.='<td width="20%" style="font-size:10px;font-weight:bold;text-align:right;">'.$system_company_name.'</td>';
                }
                $html.='</tr>
                <tr>';
                    $html .='<td width="100%" style="font-size:14px;font-weight:bold;text-align:center;">COMMISSION STATEMENT</td>';
                $html .='</tr>
                <tr>';
                    $html .='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">February 28,2017</td>';
                $html .='</tr>
        </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" width="100%">
                <tr>
                    <td width="70%" align="left" style="font-size:8px;">JONES/BROKER SPLIT BROKER</td>
                    <td width="30%" align="left" style="font-size:8px;">BROKER# : 101</td>
                </tr>
                <tr>
                    <td width="70%" align="left" style="font-size:8px;">2021 E.LONG LAKE ROAD</td>
                    <td width="30%" align="left" style="font-size:8px;">BRANCH# : BOULDER CITY BRANCH</td>
                </tr>
                <tr>
                    <td width="20%" align="left" style="font-size:8px;">SUITE 250</td>
                </tr>
                <tr>
                    <td width="20%" align="left" style="font-size:8px;">Troy, MI 48085-0001</td>
                </tr>
           </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" width="100%">
                <tr>
                    <td style="font-size:12px;font-weight:bold;text-align:center;">COMMISSION STATEMENT for JONES/BROKER SPLIT BROKER</td>
                </tr>
           </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" width="100%">';
                $html.='<tr style="background-color: #f1f1f1;">
                    <td width="10%" style="text-align:center;"><h5>TRADE DATE#</h5></td>
                    <td width="15%" style="text-align:center;"><h5>CLIENT</h5></td>
                    <td width="15%" style="text-align:center;"><h5>INVESTMENT</h5></td>
                    <td width="5%" style="text-align:center;"><h5>B/S</h5></td>
                    <td width="9%" style="text-align:center;"><h5>INVESTMENT AMOUNT</h5></td>
                    <td width="9%" style="text-align:center;"><h5>GROSS COMMISSION</h5></td>
                    <td width="9%" style="text-align:center;"><h5>CLEARING CHARGE</h5></td>
                    <td width="9%" style="text-align:center;"><h5>NET COMMISSION</h5></td>
                    <td width="9%" style="text-align:center;"><h5>RATE</h5></td>
                    <td width="10%" style="text-align:center;"><h5>BROKER COMMISSION</h5></td>
                </tr>
                <br/>';
    //$pdf->Line(10, 81, 290, 81);
    /*if($get_trans_data != array())
    {
        foreach($get_trans_data as $trans_key=>$trans_data)
        {
            $trade_date='';
            $commission_received_date='';
            $total_amount_invested = ($total_amount_invested+$trans_data['invest_amount']);
            $total_commission_received = ($total_commission_received+$trans_data['commission_received']);
            $total_charges = ($total_charges+$trans_data['charge_amount']);
            if($trans_data['trade_date'] != '0000-00-00'){ $trade_date = date('m/d/Y',strtotime($trans_data['trade_date'])); }
            if($trans_data['commission_received_date'] != '0000-00-00'){ $commission_received_date = date('m/d/Y',strtotime($trans_data['commission_received_date'])); }*/
        $html.='<tr>
                       <td colspan="10" style="font-size:8px;font-weight:bold;text-align:center;">BROKER TRANSACTIONS</td>
                </tr>
                <br/>';  
        $html.='<tr>
                       <td colspan="10" style="font-size:8px;font-weight:bold;text-align:left;">PRODUCT CATEGORY: MUTUAL FUNDS</td>
                </tr>
                <br/>';   
        $html.='<tr>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">03/15/2016</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">ADELNEST, FRANCIS</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">AF 02630T548</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">B</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">10.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">80.0</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                    </tr>';
        $html.='<tr>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">03/15/2016</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">ADELNEST, FRANCIS</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">AF 02630T548</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">B</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">10.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">80.0</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                    </tr>';
        $html.='<tr>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">03/15/2016</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">BLACK RELPH W</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">AIM 000130T548</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">B</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">10.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">80.0</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                    </tr>';
        /*}*/
        $html.='<tr style="background-color: #f1f1f1;">
                   <td style="font-size:8px;font-weight:bold;text-align:right;" colspan="3">* MUTUAL FUNDS SUBTOTAL * </td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;"></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$42,712,786.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;"></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                </tr>
                <br/>';
        
        $html.='<tr style="background-color: #f1f1f1;">
                   <td style="font-size:8px;font-weight:bold;text-align:right;" colspan="3">*** BROKER TRANSACTIONS TOTAL *** </td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;"></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$42,712,786.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;"></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                </tr>
                <br/>';
         
    /*}
    else
    {
        $html.='<tr>
                    <td style="font-size:11px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
                </tr>';
    }  */         
    $html.='</table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" width="100%">';
    //$pdf->Line(10, 81, 290, 81);
    /*if($get_trans_data != array())
    {
        foreach($get_trans_data as $trans_key=>$trans_data)
        {
            $trade_date='';
            $commission_received_date='';
            $total_amount_invested = ($total_amount_invested+$trans_data['invest_amount']);
            $total_commission_received = ($total_commission_received+$trans_data['commission_received']);
            $total_charges = ($total_charges+$trans_data['charge_amount']);
            if($trans_data['trade_date'] != '0000-00-00'){ $trade_date = date('m/d/Y',strtotime($trans_data['trade_date'])); }
            if($trans_data['commission_received_date'] != '0000-00-00'){ $commission_received_date = date('m/d/Y',strtotime($trans_data['commission_received_date'])); }*/
        $html.='<tr>
                       <td colspan="10" style="font-size:8px;font-weight:bold;text-align:center;">ADJUSTMENTS</td>
                </tr>
                <br/>';  
        $html.='<tr>
                       <td width="10%" style="font-size:8px;font-weight:normal;text-align:center;">02/28/2016</td>
                       <td width="15%" style="font-size:8px;font-weight:normal;text-align:center;"></td>
                       <td width="15%" style="font-size:8px;font-weight:normal;text-align:center;">TECHNOLOGY: DBA EMAIL DOMAIN SET UP</td>
                       <td width="5%" style="font-size:8px;font-weight:normal;text-align:center;"></td>
                       <td width="9%" style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td width="9%" style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td width="9%" style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td width="9%" style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td width="9%" style="font-size:8px;font-weight:bold;text-align:right;"></td>
                       <td width="10%" style="font-size:8px;font-weight:normal;text-align:right;">-150.00</td>
                    </tr>';
        $html.='<tr>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">02/28/2016</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;"></td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">ADVANCE: FORGOT TO WEAR TIE</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;"></td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">0.00</td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;"></td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">-10.00</td>
                    </tr>';
        /*}*/
        $html.='<tr style="background-color: #f1f1f1;">
                   <td style="font-size:8px;font-weight:bold;text-align:right;" colspan="3">*** ADJUSTMENTS TOTAL *** </td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;"></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$0.00</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;"></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$-160.00</td>
                </tr>
                <br/>';
         
    /*}
    else
    {
        $html.='<tr>
                    <td style="font-size:11px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
                </tr>';
    }  */         
    $html.='</table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(3);
    
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" width="100%">';
                $html.='<tr>
                       <td colspan="10" style="font-size:8px;font-weight:bold;text-align:right;">BROKER COMMISSION TOTALS</td>
                </tr>';
                $html.='<tr>
                    <td width="90%" style="font-size:8px;font-weight:normal;text-align:right;">Broker Transactions </td>
                    <td width="10%" style="font-size:8px;font-weight:normal;text-align:right;"> $0.00</td>
                </tr>
                <tr>
                    <td width="90%" style="font-size:8px;font-weight:normal;text-align:right;">Split Transactions </td>
                    <td width="10%" style="font-size:8px;font-weight:normal;text-align:right;"> $0.00</td>
                </tr>
                <tr>
                    <td width="90%" style="font-size:8px;font-weight:normal;text-align:right;">Override Transactions </td>
                    <td width="10%" style="font-size:8px;font-weight:normal;text-align:right;"> $0.00</td>
                </tr>
                <tr>
                    <td width="90%" style="font-size:8px;font-weight:normal;text-align:right;">Adjustments </td>
                    <td width="10%" style="font-size:8px;font-weight:normal;text-align:right;"> $-160.00</td>
                </tr>
                <tr>
                    <td width="90%" style="font-size:8px;font-weight:normal;text-align:right;">Payroll Draw </td>
                    <td width="10%" style="font-size:8px;font-weight:normal;text-align:right;"> $0.00</td>
                </tr>
                <tr>
                    <td width="90%" style="font-size:8px;font-weight:normal;text-align:right;">Base Salary </td>
                    <td width="10%" style="font-size:8px;font-weight:normal;text-align:right;"> $0.00</td>
                </tr>
                <tr>
                    <td width="90%" style="font-size:8px;font-weight:normal;text-align:right;">Prior Period Balance </td>
                    <td width="10%" style="font-size:8px;font-weight:normal;text-align:right;"> $0.00</td>
                </tr>
                <tr>
                    <td width="90%" style="font-size:8px;font-weight:normal;text-align:right;">FINRA Assessment </td>
                    <td width="10%" style="font-size:8px;font-weight:normal;text-align:right;"> $0.00</td>
                </tr>
                <tr>
                    <td width="90%" style="font-size:8px;font-weight:normal;text-align:right;">SIPC Assessment </td>
                    <td width="10%" style="font-size:8px;font-weight:normal;text-align:right;"> $0.00</td>
                </tr>
           </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table width="100%">
            <tr align="left" style="font-size:10px;font-weight:normal;text-align:left;">
                <td width="70%">
                    <table width="100%">
                        <tr>
                            <td style="font-size:8px;font-weight:normal;text-align:right;">Please Retain for Your Records </td>
                        </tr>
                        <tr>
                            <td style="font-size:8px;font-weight:normal;text-align:right;">THERE WILL BE NO CHECK THIS PERIOD</td>
                        </tr>
                    </table>
                </td>
                <td width="5%"></td>
                <td width="25%" border="1">
                    <table width="100%">
                        <tr>
                            <td width="70%" style="font-size:8px;font-weight:normal;text-align:right;">Balance Forward </td>
                            <td width="30%" style="font-size:8px;font-weight:normal;text-align:right;"> $-160.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:8px;font-weight:normal;text-align:right;">Year-to-date Earnings</td>
                            <td width="30%" style="font-size:8px;font-weight:normal;text-align:right;"> $-160.00&nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>';
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