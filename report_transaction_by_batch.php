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
$product_category = '';
$product_category_name = '';
$beginning_date = '';
$ending_date = '';
$batch = 0;
$total_records=0;
//filter batch report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);//echo '<pre>';print_r($filter_array);exit;
    $product_category = isset($filter_array['product_category'])?$filter_array['product_category']:0;
    $company = isset($filter_array['company'])?$filter_array['company']:0;
    $batch = isset($filter_array['batch'])?$filter_array['batch']:0;
    $beginning_date = isset($filter_array['beginning_date'])?$filter_array['beginning_date']:'';
    $ending_date = isset($filter_array['ending_date'])?$filter_array['ending_date']:'';
    $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
    
    $get_trans_data = $instance->select_data_report($product_category,$company,$batch,$beginning_date,$ending_date,$sort_by,1);
    
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
                if($batch > 0){
                        
                    $html .='<td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">TRANSACTION BY BATCH REPORT : '.strtoupper($batch_desc).'</td>';
                 
                } else {
                    
                    $html .='<td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">TRANSACTION BY BATCH REPORT : ALL BATCHES</td>';
                }  
                if(isset($system_company_name) && $system_company_name != '')
                {
                    $html.='<td width="20%" style="font-size:10px;font-weight:bold;text-align:right;">'.$system_company_name.'</td>';
                }
                $html.='</tr>
        </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>';
                if($batch > 0){
                    
                    $html .='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">Batch #'.$batch.'</td>';
                 
                 } else {
                    
                    $html .='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;"></td>';
                 }   
            $html .='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(3);
    
        
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="1" width="100%">
                <tr style="background-color: #f1f1f1;">
                    <td style="text-align:center;"><h5>TRADE#</h5></td>
                    <td style="text-align:center;"><h5>BROKER</h5></td>
                    <td style="text-align:center;"><h5>CLIENT</h5></td>
                    <td style="text-align:center;"><h5>TRADE DATE</h5></td>
                    <td style="text-align:center;"><h5>DATE RECEIVED</h5></td>
                    <td style="text-align:center;"><h5>AMOUNT INVESTED</h5></td>
                    <td style="text-align:center;"><h5>COMMISSION RECEIVED</h5></td>
                    <td style="text-align:center;"><h5>CHARGE</h5></td>
                </tr>';
    //$pdf->Line(10, 81, 290, 81);
    if($get_trans_data != array())
    {
        foreach($get_trans_data as $trans_key=>$trans_data)
        {
            $total_records = $total_records+1;
            $trade_date='';
            $commission_received_date='';
            $total_amount_invested = ($total_amount_invested+$trans_data['invest_amount']);
            $total_commission_received = ($total_commission_received+$trans_data['commission_received']);
            $total_charges = ($total_charges+$trans_data['charge_amount']);
            if($trans_data['trade_date'] != '0000-00-00'){ $trade_date = date('m/d/Y',strtotime($trans_data['trade_date'])); }
            if($trans_data['commission_received_date'] != '0000-00-00'){ $commission_received_date = date('m/d/Y',strtotime($trans_data['commission_received_date'])); }
        $html.='<tr>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.$trans_data['id'].'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.$trans_data['broker_name'].'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.$trans_data['client_name'].'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.$trade_date.'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.$commission_received_date.'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($trans_data['invest_amount'],2).'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($trans_data['commission_received'],2).'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($trans_data['charge_amount'],2).'</td>
                    </tr>';
        }
        $html.='<tr style="background-color: #f1f1f1;">
                   <td style="font-size:8px;font-weight:bold;text-align:center;">Total Records: '.$total_records.'</td>
                   <td></td>
                   <td></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;" colspan="2">Report Total : </td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($total_amount_invested,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($total_commission_received,2).'</td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;">$'.number_format($total_charges,2).'</td>
                </tr>';
         
    }
    else
    {
        $html.='<tr>
                    <td style="font-size:8px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
                </tr>';
    }           
    $html.='</table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    //$pdf->Line(205, 105, 182, 105);
    //$pdf->Line(238, 105, 215, 105);
    //$pdf->Line(280, 105, 265, 105);
    /*$pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="5" width="100%">
                <tr>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td style="font-size:13px;font-weight:bold;text-align:right;" colspan="2">Report Total : </td>
                   <td style="font-size:13px;font-weight:bold;text-align:left;">'.number_format($total_amount_invested,2).'</td>
                   <td style="font-size:13px;font-weight:bold;text-align:left;width:18%;">'.number_format($total_commission_received,2).'</td>
                   <td style="font-size:13px;font-weight:bold;text-align:left;">'.number_format($total_charges,2).'</td>
                </tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);*/
    if(isset($_GET['open']) && $_GET['open'] == 'output_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->lastPage();
    $pdf->Output('report_transaction_by_batch.pdf', 'I');
    
    exit;
?>