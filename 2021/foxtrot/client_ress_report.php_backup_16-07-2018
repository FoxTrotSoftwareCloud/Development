<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new client_ress();
$get_client_ress_data = array();
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
//print_r($get_logo);exit;
$from_broker = '';
if(isset($_GET['from_broker']) && $_GET['from_broker'] != '')
{
    $from_broker = $_GET['from_broker'];
    $get_client_ress_data = $instance->select_data_report($from_broker);
    $get_broker=$instance->select_broker();
}   
else
{
    $get_client_ress_data = $instance->select_data_report();
}

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
                    if($from_broker != ''){
                        
                        $html .='<td width="100%" style="font-size:16px;font-weight:bold;text-align:center;">Client Reassigment Report</td>';
                     
                     } 
                   
                $html .='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>';
                if($from_broker != ''){
                    
                    $html .='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;"></td>';
                 
                 } else {
                    
                    $html .='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;"></td>';
                 }   
            $html .='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);    
    
        
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="5" width="100%">
                <tr style="background-color: #f1f1f1;">
                    <td><h4>NO#</h4></td>
                    <td><h4>CLIENT NAME</h4></td>
                    <td><h4>FROM BROKER</h4></td>
                    <td><h4>TO BROKER</h4></td>
                    <td><h4>REASSIGMENT DATE </h4></td>
                </tr>';
    //$pdf->Line(10, 77, 287, 77);
    $count=0;
    if($get_client_ress_data != array())
    {
        foreach($get_client_ress_data as $trans_key=>$trans_data)
        { $count++; 
        
            foreach($get_broker as $key=>$val){
                if($val['id'] == $trans_data['broker_name'])
                {
                    $new_broker=$val['first_name'].' '.$val['last_name'];
                }
                if($val['id'] == $trans_data['broker_old_name'])
                {
                    $old_broker=$val['first_name'].' '.$val['last_name'];
                }
            }
            
        $html.='<tr>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.$count.'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.$trans_data['first_name'].'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.$old_broker.'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.$new_broker.'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.date('m-d-Y',strtotime($trans_data['ressign_date'])).'</td>
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
    
   
    $pdf->lastPage();
    
    if(isset($_GET['open']) && $_GET['open'] == 'output_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->Output('report_client_reassignment.pdf', 'I');
    exit;
    
?>