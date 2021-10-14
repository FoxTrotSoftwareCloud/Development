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
    $html='<table border="0" width="100%">
                <tr>
                    <td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">Concorde Investment Services</td>
                </tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>
                    <td width="100%" style="font-size:16px;font-weight:bold;text-align:center;">SPECIALLY DESIGNATED NATIONALS CLIENT CHECK </td>
                </tr>
           </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>
                    <td width="100%" style="font-size:14px;font-weight:bold;text-align:center;">File Date - '.$file_date.'</td>
                </tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
        
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="5" width="100%">
                <tr style="background-color: #f1f1f1;">
                    <td style="width:20%"><h4>SDN NAME</h4></td>
                    <td style="width:10%"><h4>ID NO.</h4></td>
                    <td style="width:10%"><h4>PROGRAM</h4></td>
                    <td style="width:10%"><h4>CLIENT NO.</h4></td>
                    <td style="width:20%"><h4>FOXTROT CLIENT NAME</h4></td>
                    <td style="width:10%"><h4>REP NO.</h4></td>
                    <td style="width:20%"><h4>REP NAME</h4></td>
                </tr>';
                if($get_ofac_data != array())
                {
                    foreach($get_ofac_data as $key=>$val)
                    {
                        $html.='<tr>
                           <td style="font-size:10px;font-weight:normal;text-align:left;">'.$val['sdn_name'].'</td>
                           <td style="font-size:10px;font-weight:normal;text-align:left;">'.$val['id_no'].'</td>
                           <td style="font-size:10px;font-weight:normal;text-align:left;">'.$val['program'].'</td>
                           <td style="font-size:10px;font-weight:normal;text-align:left;">'.$val['client_id'].'</td>
                           <td style="font-size:10px;font-weight:normal;text-align:left;">'.$val['client_name'].'</td>
                           <td style="font-size:10px;font-weight:normal;text-align:left;">-</td>
                           <td style="font-size:10px;font-weight:normal;text-align:left;">-</td>
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
    $html='<br/>';
    
    if($get_ofac_data != array())
    {
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="5" width="100%">
                <tr>
                   <td style="font-size:10px;font-weight:normal;text-align:right;width:14%">Total Scanned:</td>
                   <td style="font-size:10px;font-weight:normal;text-align:left;width:14%">'.$total_scan.'</td>
                </tr>
                <tr>
                   <td style="font-size:10px;font-weight:normal;text-align:right;width:14%">Total Matches:</td>
                   <td style="font-size:10px;font-weight:normal;text-align:left;width:14%">'.$total_matches.'</td>
                </tr>
                <tr>
                   <td style="font-size:10px;font-weight:normal;text-align:left;width:14%">*Foxtrot Client Name - </td>
                   <td style="font-size:10px;font-weight:normal;text-align:Left;width:20%">Match found in joint name</td>
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