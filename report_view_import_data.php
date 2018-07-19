<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new import();

//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';

$return_solved_exception = array();
$file_id = isset($_GET['id'])?$instance->re_db_input($_GET['id']):0;
$get_file_data = $instance->select_user_files($file_id);
$get_total_commission = $instance->get_total_commission_amount($file_id);
$total_commission_amount = $get_total_commission;
$get_file_type = $instance->get_file_type($file_id);
$return_solved_exception = $instance->select_processed_data($file_id);

$total_investments = 0;
$total_commissions = 0;
$total_records=0;

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
                $html .='<td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">VIEW PROCESSED DATA</td>';
                if(isset($system_company_name) && $system_company_name != '')
                {
                    $html.='<td width="20%" style="font-size:10px;font-weight:bold;text-align:right;">'.$system_company_name.'</td>';
                }
                $html.='</tr>
        </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(3);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>';
                    $html .='<td width="23%" style="font-size:10px;font-weight:bold;margin-right: 5% !important;">File: '.$get_file_data['file_name'].'</td>
                    <td width="23%" style="font-size:10px;font-weight:bold;margin-right: 5% !important;">Source: '.$get_file_data['source'].'</td>
                    <td width="24%" style="font-size:10px;font-weight:bold;margin-right: 5% !important;">File Type: '.$get_file_data['file_type'].'</td>
                    <td width="15%" style="font-size:10px;font-weight:bold;margin-right: 5% !important;">Date: '.date('m/d/Y',strtotime($get_file_data['last_processed_date'])).'</td>';
                    if(isset($get_file_type) && $get_file_type == '2')
                    {
                        $html .='<td width="15%" style="font-size:10px;font-weight:bold;margin-right: 5% !important;">Amount: $'.number_format($total_commission_amount,2).'</td>';
                    }
                    
                $html .='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="1" width="100%">
                <tr style="background-color: #f1f1f1;">
                    <td style="text-align:center;"><h5>DATE</h5></td>
                    <td style="width:8%;text-align:center;"><h5>REP#</h5></td>
                    <td style="width:20%;text-align:center;"><h5>REP NAME</h5></td>
                    <td style="text-align:center;"><h5>ACCOUNT#</h5></td>
                    <td style="text-align:center;"><h5>CLIENT NAME</h5></td>';
                    if(isset($get_file_type) && $get_file_type == '1')
                    {
                        $html.='<td style="width:25%;text-align:center;" ><h5>CLIENT ADDRESS</h5></td>';
                    }
                    else if(isset($get_file_type) && $get_file_type == '2')
                    {
                        $html.='<td style="text-align:center;"><h5>CUSIP</h5></td>';
                        $html.='<td style="text-align:center;"><h5>PRINCIPAL</h5></td>';
                        $html.='<td style="text-align:center;"><h5>COMMISSION</h5></td>';
                    }
        
        $html.='</tr>';
    //$pdf->Line(10, 81, 290, 81);
    if($return_solved_exception != array())
    {
        foreach($return_solved_exception as $process_key=>$process_val)
        {
            $total_records = $total_records+1;
            $html.='<tr style="height:1px;">
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.date('m/d/Y',strtotime($process_val['date'])).'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;width:8%;">'.$process_val['rep'].'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;width:20%;">'.$process_val['rep_name'].'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.$process_val['account_no'].'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.$process_val['client'].'</td>';
                        if(isset($get_file_type) && $get_file_type == '1')
                        {
                            $get_client_data = $instance->get_client_data($file_id,$process_val['temp_data_id']);
                            $html.='<td style="font-size:8px;font-weight:normal;text-align:center;">'.$get_client_data[0]['client_address'].'</td>';
                        }
                        else if(isset($get_file_type) && $get_file_type == '2')
                        {
                            $total_investments = $total_investments+$process_val['principal'];
                            $total_commissions = $total_commissions+$process_val['commission'];
                            $html.='<td style="font-size:8px;font-weight:normal;text-align:center;">'.$process_val['cusip'].'</td>';
                            if($process_val['principal'] > 0){ 
                                $html.='<td style="font-size:8px;font-weight:normal;text-align:right;">'.'$'.number_format($process_val['principal'],2).'</td>';
                            }else{
                                $html.='<td style="font-size:8px;font-weight:normal;text-align:right;">$0</td>';
                            }
                            if($process_val['commission'] > 0){ 
                                $html.='<td style="font-size:8px;font-weight:normal;text-align:right;">'.'$'.number_format($process_val['commission'],2).'</td>';
                            }else{
                                $html.='<td style="font-size:8px;font-weight:normal;text-align:right;">$0</td>';
                            }
                        }
            $html.='</tr>';
        }
        if(isset($get_file_type) && $get_file_type == '1')
        {
            $html.='<tr style="background-color: #f1f1f1;">
                        <td colspan="5" style="font-size:8px;font-weight:bold;text-align:right;"></td>
                        <td style="font-size:8px;font-weight:bold;text-align:center;">Total Records: '.$total_records.'</td>';
            $html.='</tr>';
        }
        else if(isset($get_file_type) && $get_file_type == '2')
        {
            $html.='<tr style="background-color: #f1f1f1;">
                        <td colspan="5" style="font-size:8px;font-weight:bold;text-align:right;"></td>
                        <td style="font-size:8px;font-weight:bold;text-align:center;">Total Records: '.$total_records.'</td>';
                        if(isset($get_file_type) && $get_file_type == '2')
                        {
                            if($total_investments > 0){ 
                                $html.='<td style="font-size:8px;font-weight:bold;text-align:right;">'.'$'.number_format($total_investments,2).'</td>';
                            }else{
                                $html.='<td style="font-size:8px;font-weight:bold;text-align:right;">$0</td>';
                            }
                            if($total_commissions > 0){ 
                                $html.='<td style="font-size:8px;font-weight:bold;text-align:right;">'.'$'.number_format($total_commissions,2).'</td>';
                            }else{
                                $html.='<td style="font-size:8px;font-weight:bold;text-align:right;">$0</td>';
                            }
                        }
            $html.='</tr>'; 
        }
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
   
    $pdf->lastPage();
    $pdf->Output('report_view_import_data.pdf', 'I');
    
    exit;
?>