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
    $img = '<img src="'.SITE_URL."upload/logo/".$system_logo.'" height="30px" />';
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" width="100%">
                <tr>';
                if(isset($system_logo) && $system_logo != '')
                {
                    $html .='<td width="20%" align="left">'.$img.'</td>';
                }
                    $html .='<td width="60%" style="font-size:13px;font-weight:normal;text-align:center;">All Companies</td>';
                if(isset($system_company_name) && $system_company_name != '')
                {
                    $html.='<td width="20%" style="font-size:10px;font-weight:bold;text-align:right;">'.$system_company_name.'</td>';
                }
                $html.='</tr>
                <tr>';
                    $html .='<td width="100%" style="font-size:16px;font-weight:bold;text-align:center;">Commissions Payable Report</td>';
            $html .='</tr>
        </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" width="100%">';
                $html.='<tr style="background-color: #f1f1f1;">
                    <td width="10%" style="text-align:center;"><h4>DATE</h4></td>
                    <td width="10%" style="text-align:center;"><h4>TRANS #</h4></td>
                    <td width="20%" style="text-align:center;"><h4>INVESTMENT</h4></td>
                    <td width="20%" style="text-align:center;"><h4>CLIENT</h4></td>
                    <td width="10%" style="text-align:center;"><h4>PRICE</h4></td>
                    <td width="10%" style="text-align:center;"><h4>INVESTMENT AMOUNT</h4></td>
                    <td width="10%" style="text-align:center;"><h4>COMMISSION RECEIVED</h4></td>
                    <td width="10%" style="text-align:center;"><h4>BROKER COMMISSION</h4></td>
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
                       <td colspan="8" style="font-size:11px;font-weight:bold;text-align:left;">BROKER:Split Broker, Jones/roberts / HS23</td>
                </tr>
                <br/>';   
        $html.='<tr>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">03/15/2016</td>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">56</td>
                       <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">AF 02630T548</td>
                       <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">094667220 ADELNEST, F.</td>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.000000</td>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">21,341,234.00</td>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.00</td>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.00</td>
                </tr>';
        $html.='<tr>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">03/15/2016</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">7154</td>
               <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">AIM 001413301</td>
               <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">094667220 ADELNEST, F.</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.000000</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">21,341,234.00</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.00</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.00</td>
        </tr>';
        $html.='<tr>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">03/15/2016</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">49</td>
               <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">AIM 001413301</td>
               <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">094667220 ADELNEST, F.</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.000000</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">10.00</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.00</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.00</td>
        </tr>
        <br/>';
        /*}*/
        $html.='<tr>
                   <td style="font-size:11px;font-weight:bold;text-align:right;" colspan="5"></td>
                   <td style="font-size:11px;font-weight:bold;text-align:right;">42,712,786.00</td>
                   <td style="font-size:11px;font-weight:bold;text-align:right;">0.00</td>
                   <td style="font-size:11px;font-weight:bold;text-align:right;">0.00</td>
                </tr>
                <br/>';
                
        $html.='<tr align="left" style="font-size:10px;font-weight:normal;text-align:left;">
                <td width="60%">
                </td>
                <td width="40%" border="1">
                    <table width="100%">
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Ticket Charge: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 0.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Override Pay: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 0.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Adjustments: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> -160.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Prior Period Balance: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 0.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">FINRA Assessment: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 0.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">SIPC Assessment: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 0.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:bold;text-align:right;">Total Jones/roberts Split Broker: </td>
                            <td width="30%" style="font-size:11px;font-weight:bold;text-align:right;"> -160.00&nbsp;&nbsp;</td>
                        </tr><br/>
                    </table>
                </td>
            </tr>';
        /**second broker record ***/    
        $html.='<tr>
                       <td colspan="8" style="font-size:11px;font-weight:bold;text-align:left;">BROKER:Jones, Jim / B116</td>
                </tr>
                <br/>';   
        $html.='<tr>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">03/15/2016</td>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">56</td>
                       <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">AF 02630T548</td>
                       <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">094667220 ADELNEST, F.</td>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.000000</td>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">10,670,617.00 </td>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">121.00 </td>
                       <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">60.50</td>
                </tr>';
        $html.='<tr>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">03/15/2016</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">7154</td>
               <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">AF 02630T548</td>
               <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">094667220 ADELNEST, F.</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.000000</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">10,670,617.00 </td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">121.00 </td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">60.50</td>
        </tr>';
        $html.='<tr>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">03/15/2016</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">49</td>
               <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">AIM 001413301</td>
               <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">094667220 ADELNEST, F.</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.000000</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">10,000.00</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">437.50</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">212.50</td>
        </tr>';
        $html.='<tr>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">03/15/2016</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:left;">49</td>
               <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">AIM 001413301</td>
               <td width="20%" style="font-size:11px;font-weight:normal;text-align:left;">094667220 ADELNEST, F.</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">0.000000</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">5,000.00</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">62.50</td>
               <td width="10%" style="font-size:11px;font-weight:normal;text-align:right;">22.50</td>
        </tr>
        <br/>';
        /*}*/
        $html.='<tr>
                   <td style="font-size:11px;font-weight:bold;text-align:right;" colspan="5"></td>
                   <td style="font-size:11px;font-weight:bold;text-align:right;">21,356,393.00</td>
                   <td style="font-size:11px;font-weight:bold;text-align:right;">743.50</td>
                   <td style="font-size:11px;font-weight:bold;text-align:right;">356.75</td>
                </tr>
                <br/>';
                
        $html.='<tr align="left" style="font-size:10px;font-weight:normal;text-align:left;">
                <td width="60%">
                </td>
                <td width="40%" border="1">
                    <table width="100%">
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Ticket Charge: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 30.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Override Pay: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 0.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Adjustments: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> -150.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Prior Period Balance: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 0.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">FINRA Assessment: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> -0.93&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">SIPC Assessment: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> -13.94&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:bold;text-align:right;">Total Jim Jones:</td>
                            <td width="30%" style="font-size:11px;font-weight:bold;text-align:right;"> 191.88&nbsp;&nbsp;</td>
                        </tr><br/>
                    </table>
                </td>
            </tr>';
    $html.='</table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    /*}
    else
    {
        $html.='<tr>
                    <td style="font-size:11px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
                </tr>';
    }  */         
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table width="100%">
            <tr align="left" style="font-size:10px;font-weight:normal;text-align:left;">
                <td width="60%">
                </td>
                <td width="40%" border="1">
                    <table width="100%">
                        <tr>
                            <td colspan="8" style="font-size:11px;font-weight:bold;text-align:center;">REPORT TOTALS</td>
                        </tr>
                        <br/>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Ticket Charge: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 60.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Broker/Split Pay: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 1,063.11&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Override Pay: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 0.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Adjustments: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> -310.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">Prior Period Balance: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 0.00&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">FINRA Assessment: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> -1.86&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:normal;text-align:right;">SIPC Assessment: </td>
                            <td width="30%" style="font-size:11px;font-weight:normal;text-align:right;"> 723.37&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%" style="font-size:11px;font-weight:bold;text-align:right;">Total Commission: </td>
                            <td width="30%" style="font-size:11px;font-weight:bold;text-align:right;"> 723.37&nbsp;&nbsp;</td>
                        </tr><br/>
                    </table>
                </td>
            </tr>';
    $html.='</table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    if(isset($_GET['open']) && $_GET['open'] == 'output_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->lastPage();
    $pdf->Output('report_payroll_adjustment.pdf', 'I');
    
    exit;
?>