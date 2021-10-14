<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new import();

//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';

$return_exception = array();
$file_id = isset($_GET['id'])?$instance->re_db_input($_GET['id']):0;
$get_file_data = $instance->select_user_files($file_id);
$get_total_commission = $instance->get_total_commission_amount($file_id);
$total_commission_amount = $get_total_commission;
$get_file_type = $instance->get_file_type($file_id);
$return_exception = $instance->select_exception_data($file_id);
?>
<?php

    // create new PDF document
    $pdf = new RRPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // add a page
    $pdf->AddPage('L');
    // Title
    $img = '<img src="'.SITE_URL."upload/logo/".$system_logo.'" height="60px" />';
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>
                   <td width="50%" style="font-size:10px;font-weight:bold;text-align:left;">'.date('d/m/Y h:i:s A').'</td>';
                   if(isset($system_company_name) && $system_company_name != '')
                   {
                        $html.='<td width="50%" style="font-size:10px;font-weight:bold;text-align:right;">'.$system_company_name.'</td>';
                   }
        $html.='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    if(isset($system_logo) && $system_logo != '')
    {
        $pdf->SetFont('times','B',12);
        $pdf->SetFont('times','',10);
        $html='<table border="0" width="100%">
                    <tr>
                        <td align="center">'.$img.'</td>
                    </tr>
                </table>';
        $pdf->writeHTML($html, false, 0, false, 0);
        $pdf->Ln(5);
    }
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>';
                    $html .='<td width="100%" style="font-size:16px;font-weight:bold;text-align:center;">EXCEPTION FOR REVIEW</td>';
                $html .='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>';
                    $html .='<td width="23%" style="font-size:12px;font-weight:bold;margin-right: 5% !important;">File: '.$get_file_data['file_name'].'</td>
                    <td width="23%" style="font-size:12px;font-weight:bold;margin-right: 5% !important;">Source: '.$get_file_data['source'].'</td>
                    <td width="24%" style="font-size:12px;font-weight:bold;margin-right: 5% !important;">File Type: '.$get_file_data['file_type'].'</td>
                    <td width="15%" style="font-size:12px;font-weight:bold;margin-right: 5% !important;">Date: '.date('m/d/Y',strtotime($get_file_data['last_processed_date'])).'</td>
                    <td width="15%" style="font-size:12px;font-weight:bold;margin-right: 5% !important;">Amount: $'.number_format($total_commission_amount,2).'</td>';
                $html .='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="5" width="100%">
                <tr style="background-color: #f1f1f1;">
                    <td><h4>DATE</h4></td>
                    <td><h4>REP#</h4></td>
                    <td><h4>REP NAME</h4></td>
                    <td><h4>ACCOUNT#</h4></td>
                    <td><h4>CLIENT NAME</h4></td>';
                    if(isset($get_file_type) && $get_file_type == '1')
                    {
                        $html.='<td><h4>CLIENT ADDRESS</h4></td>';
                    }
                    else if(isset($get_file_type) && $get_file_type == '2')
                    {
                        $html.='<td><h4>CUSIP</h4></td>';
                        $html.='<td><h4>PRINCIPAL</h4></td>';
                        $html.='<td><h4>COMMISSION</h4></td>';
                    }
                    $html.='<td style="width:18%"><h4>ISSUE</h4></td>
                </tr>';
    //$pdf->Line(10, 81, 290, 81);
    if($return_exception != array())
    {
        foreach($return_exception as $error_key=>$error_val)
        {
            $html.='<tr>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.date('m/d/Y',strtotime($error_val['date'])).'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.$error_val['rep'].'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.$error_val['rep_name'].'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.$error_val['account_no'].'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.$error_val['client'].'</td>';
                        if(isset($get_file_type) && $get_file_type == '1')
                        {
                            $get_client_data = $instance->get_client_data($file_id,$error_val['temp_data_id']);
                            $html.='<td style="font-size:13px;font-weight:normal;text-align:left;">'.$get_client_data[0]['client_address'].'</td>';
                        }
                        else if(isset($get_file_type) && $get_file_type == '2')
                        {
                            $html.='<td style="font-size:13px;font-weight:normal;text-align:left;">'.$error_val['cusip'].'</td>';
                            if($error_val['principal'] > 0){ 
                                $html.='<td style="font-size:13px;font-weight:normal;text-align:left;">'.'$'.number_format($error_val['principal'],2).'</td>';
                            }else{
                                $html.='<td style="font-size:13px;font-weight:normal;text-align:left;">$0</td>';
                            }
                            if($error_val['commission'] > 0){ 
                                $html.='<td style="font-size:13px;font-weight:normal;text-align:left;">'.'$'.number_format($error_val['commission'],2).'</td>';
                            }else{
                                $html.='<td style="font-size:13px;font-weight:normal;text-align:left;">$0</td>';
                            }
                        }
                        $html.='<td style="font-size:13px;font-weight:normal;text-align:left;">'.$error_val['error'].'</td>
                   </tr>';
        } 
    }
    else
    {
        $html.='<tr>
                    <td style="font-size:13px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
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
    $pdf->Output('report_exception_data.pdf', 'I');
    
    exit;
?>