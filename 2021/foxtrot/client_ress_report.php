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
                if($from_broker != ''){
                    
                    $html .='<td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">Client Reassignment Report</td>';
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
    $html='<table border="0" cellpadding="1" width="100%">
                <tr style="background-color: #f1f1f1;">
                    <td style="text-align:center;"><h5>NO#</h5></td>
                    <td style="text-align:center;"><h5>CLIENT NAME</h5></td>
                    <td style="text-align:center;"><h5>FROM BROKER</h5></td>
                    <td style="text-align:center;"><h5>TO BROKER</h5></td>
                    <td style="text-align:center;"><h5>REASSIGNMENT DATE </h5></td>
                </tr>';
    //$pdf->Line(10, 77, 287, 77);
    $count=0;
    if($get_client_ress_data != array())
    {
        foreach($get_client_ress_data as $trans_key=>$trans_data)
        { $count++; 
            $total_records=$total_records+1;
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
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.$count.'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.$trans_data['first_name'].'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.$old_broker.'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.$new_broker.'</td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;">'.date('m-d-Y',strtotime($trans_data['ressign_date'])).'</td>
                </tr>';
        }
        $html.='<tr style="background-color: #f1f1f1;">
            <td colspan="4" style="font-size:8px;font-weight:bold;text-align:right;"></td>
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
    $pdf->Ln(2);
    
   
    $pdf->lastPage();
    
    if(isset($_GET['open']) && $_GET['open'] == 'output_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->Output('report_client_reassignment.pdf', 'I');
    exit;
    
?>