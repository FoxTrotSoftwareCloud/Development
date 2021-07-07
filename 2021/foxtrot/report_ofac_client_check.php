<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new ofac_fincen();
$get_ofac_data = array();
$get_ofac_main_data = array();
$get_logo = $instance->get_system_logo();
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$ofac_main_id = isset($_GET['id'])?$instance->re_db_input($_GET['id']):0;
if($ofac_main_id >0)
{
    $get_ofac_main_data = $instance->select_data_master_report($ofac_main_id);
    $get_ofac_data = $instance->select_data_report($ofac_main_id);
}
else
{
    $get_ofac_main_data = $instance->select_data_master_report();
    $ofac_main_id = isset($get_ofac_main_data['id'])?$instance->re_db_input($get_ofac_main_data['id']):0;
    $get_ofac_data = $instance->select_data_report($ofac_main_id);
}
//print_r($get_ofac_data);exit;
$file_date = isset($get_ofac_main_data['created_time'])?$instance->re_db_input(date('m/d/Y',strtotime($get_ofac_main_data['created_time']))):'00/00/0000';
$total_matches = isset($get_ofac_main_data['total_match'])?$instance->re_db_input($get_ofac_main_data['total_match']):0;
$total_scan = isset($get_ofac_main_data['total_scan'])?$instance->re_db_input($get_ofac_main_data['total_scan']):0;
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
                $html .='<td width="60%" style="font-size:12px;font-weight:bold;text-align:center;">Concorde Investment Services</td>';
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
                <tr>
                    <td width="100%" style="font-size:14px;font-weight:bold;text-align:center;">SPECIALLY DESIGNATED NATIONALS CLIENT CHECK </td>
                </tr>
           </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>
                    <td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">File Date - '.$file_date.'</td>
                </tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
        
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="1" width="100%">
                <tr style="background-color: #f1f1f1;">
                    <td style="text-align:center;width:20%"><h5>SDN NAME</h5></td>
                    <td style="text-align:center;width:10%"><h5>ID NO.</h5></td>
                    <td style="text-align:center;width:10%"><h5>PROGRAM</h5></td>
                    <td style="text-align:center;width:10%"><h5>CLIENT NO.</h5></td>
                    <td style="text-align:center;width:20%"><h5>FOXTROT CLIENT NAME</h5></td>
                    <td style="text-align:center;width:10%"><h5>REP NO.</h5></td>
                    <td style="text-align:center;width:20%"><h5>REP NAME</h5></td>
                </tr>';
                if($get_ofac_data != array())
                {
                    foreach($get_ofac_data as $key=>$val)
                    {
                        $total_records=$total_records+1;
                        $html.='<tr>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">'.$val['sdn_name'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">'.$val['id_no'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">'.$val['program'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">'.$val['client_id'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">'.$val['client_name'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">-</td>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">-</td>
                        </tr>';
                    }
                    $html.='<tr style="background-color: #f1f1f1;">
                        <td colspan="6" style="font-size:8px;font-weight:bold;text-align:right;"></td>
                        <td style="font-size:8px;font-weight:bold;text-align:center;">Total Records: '.$total_records.'</td>';
                    $html.='</tr>';
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
    $html='<br/>';
    
    if($get_ofac_data != array())
    {
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="5" width="100%">
                <tr>
                   <td style="font-size:8px;font-weight:bold;text-align:right;width:14%">Total Scanned:</td>
                   <td style="font-size:8px;font-weight:normal;text-align:left;width:14%">'.$total_scan.'</td>
                </tr>
                <tr>
                   <td style="font-size:8px;font-weight:bold;text-align:right;width:14%">Total Matches:</td>
                   <td style="font-size:8px;font-weight:normal;text-align:left;width:14%">'.$total_matches.'</td>
                </tr>
                <tr>
                   <td style="font-size:8px;font-weight:bold;text-align:right;width:14%">*Foxtrot Client Name: </td>
                   <td style="font-size:8px;font-weight:normal;text-align:Left;width:20%">Match found in joint name</td>
                </tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    }
    $pdf->lastPage();
    
    if(isset($_GET['open']) && $_GET['open'] == 'ofac_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->Output('report_ofac_client_check.pdf', 'I');
    
    exit;
?>