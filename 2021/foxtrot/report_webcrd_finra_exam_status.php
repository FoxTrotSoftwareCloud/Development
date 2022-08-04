<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new webcrd();
$get_webcrd_data = [];
$get_webcrd_main_data = [];
$get_logo = $instance->get_system_logo();
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$webcrd_main_id = isset($_GET['id'])?$instance->re_db_input($_GET['id']):0;
$get_webcrd_data = $instance->select_finra_exam_status_data($webcrd_main_id, 1, 1);

if($webcrd_main_id > 0)
{
    $get_webcrd_main_data = $instance->select_master($webcrd_main_id);
}
else
{
    $get_webcrd_main_data = $instance->select_master();
    $webcrd_main_id = isset($get_webcrd_main_data[0]['id'])?$instance->re_db_input($get_webcrd_main_data[0]['id']):0;
}
//print_r($get_webcrd_data);exit;
$file_date = isset($get_webcrd_main_data[0]['file_date'])?$instance->re_db_input(date('m/d/Y',strtotime($get_webcrd_main_data[0]['file_date']))):'00/00/0000';
$import_date = isset($get_webcrd_main_data[0]['import_date'])?$instance->re_db_input(date('m/d/Y h:i:s A',strtotime($get_webcrd_main_data[0]['import_date']))):'00/00/0000';
$total_added = isset($get_webcrd_main_data[0]['added'])?$instance->re_db_input($get_webcrd_main_data[0]['added']):0;
$total_scan = isset($get_webcrd_main_data[0]['total_scan'])?$instance->re_db_input($get_webcrd_main_data[0]['total_scan']):0;
$file_name = isset($get_webcrd_main_data[0]['file_name'])?$instance->re_db_input($get_webcrd_main_data[0]['file_name']):'*File Name Not Specified*';
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
            $html .='<td width="60%" style="font-size:12px;font-weight:bold;text-align:center;">'.$get_company_name['company_name'].'</td>';
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
                    <td width="100%" style="font-size:14px;font-weight:bold;text-align:center;">WebCRD Exam Status Report</td>
                </tr>
           </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>
                    <td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">File Name: '.$file_name.', Imported: '.$import_date.'</td>
                </tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
        
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="1" width="100%">
                <tr style="background-color: #f1f1f1;">
                    <td style="text-align:left;width:20%"><h5>BROKER NAME</h5></td>
                    <td style="text-align:center;width:10%"><h5>CRD NO.</h5></td>
                    <td style="text-align:left;width:10%"><h5>EXAM SERIES</h5></td>
                    <td style="text-align:left;width:10%"><h5>STATUS</h5></td>
                    <td style="text-align:left;width:10%"><h5>GRADE</h5></td>
                    <td style="text-align:center;width:10%"><h5>VALIDITY</h5></td>
                    <td style="text-align:center;width:10%"><h5>UNTIL DATE</h5></td>
                    <td style="text-align:left;width:10%"><h5>RESULT</h5></td>
                </tr>';
                if($get_webcrd_data != [])
                {
                    foreach($get_webcrd_data as $key=>$val)
                    {
                        $total_records=$total_records+1;
                        $html.='<tr>
                           <td style="font-size:8px;font-weight:normal;text-align:left;">
                                '.$val['last_name'].((empty($val['last_name']) || empty($val['first_name'])) ? "": ", ").$val['first_name'].'
                            </td>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">'.$val['individual_crd_no'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:left;">'.$val['exam_series'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:left;">'.$val['exam_status'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:left;">'.$val['grade'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">'.$val['validity'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:center;">'.$val['valid_until'].'</td>
                           <td style="font-size:8px;font-weight:normal;text-align:left;">'.$val['result'].'</td>
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
    
    if($get_webcrd_data != [])
    {
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="5" width="100%">
                <tr>
                   <td style="font-size:8px;font-weight:bold;text-align:right;width:14%">Total Scanned:</td>
                   <td style="font-size:8px;font-weight:normal;text-align:left;width:14%">'.$total_scan.'</td>
                </tr>
                <tr>
                   <td style="font-size:8px;font-weight:bold;text-align:right;width:14%">Total Added:</td>
                   <td style="font-size:8px;font-weight:normal;text-align:left;width:14%">'.$total_added.'</td>
                </tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    }
    $pdf->lastPage();
    
    if(isset($_GET['open']) && $_GET['open'] == 'webcrd_finra_exam_status_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->Output('report_webcrd_finra_exam_status.pdf', 'I');
    
    exit;
?>