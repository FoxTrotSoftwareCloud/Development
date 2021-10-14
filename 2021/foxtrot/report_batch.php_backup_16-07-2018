<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new batches();

//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';

$return_batches = array();
$filter_array = array();
$product_category = '';
$product_category_name = '';
$beginning_date = '';
$ending_date = '';

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
    
    $return_batches = $instance->get_all_category_batch_data($product_category,$company,$batch,$beginning_date,$ending_date,$sort_by);
    if($product_category>0)
    {
        $product_category_name = $instance->get_product_type($product_category);
    }
    else
    {
        $product_category_name = 'All Categories';
    }
}

$total_received_amount = 0;
$total_posted_amount = 0;

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
                    $html .='<td width="100%" style="font-size:16px;font-weight:bold;text-align:center;">'.$product_category_name.' Batch Report</td>';
                $html .='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>';
                    $html .='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">'.$beginning_date.'-'.$ending_date.'</td>';
            $html .='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="5" width="100%">
                <tr style="background-color: #f1f1f1;">
                    <td style="width:20%"><h4>BATCH#</h4></td>
                    <td style="width:20%"><h4>DATE RECEIVED</h4></td>
                    <td style="width:15%"><h4>AMOUNT RECEIVED</h4></td>
                    <td style="width:15%"><h4>POSTED TO DATE</h4></td>
                    <td style="width:30%"><h4>STATEMENT DESCRIPTION</h4></td>';
    $html.='</tr>';
    
    
    if($return_batches != array())
    {
        foreach($return_batches as $main_key=>$main_val)
        {
            $html.='<tr>
                <td colspan="5" style="font-size:13px;font-weight:bold;text-align:left;">Investment Category: '.$main_key.'</td>';
            $html.='</tr>';
            
            $posted_commission_amount = 0;
            $amount_received = 0;
            
            $cat_total_received_amount = 0;
            $cat_total_posted_amount = 0;
            
            foreach($main_val as $sub_key=>$sub_val)
            {
                $get_commission_amount = $instance->get_commission_total($sub_val['id']);
                $amount_received = $sub_val['check_amount'];
                
                if(isset($get_commission_amount['posted_commission_amount']) && $get_commission_amount['posted_commission_amount']!='')
                {
                    $posted_commission_amount = $get_commission_amount['posted_commission_amount'];
                    
                }else
                { $posted_commission_amount = 0;}
                $cat_total_posted_amount = $cat_total_posted_amount+$posted_commission_amount;
                $cat_total_received_amount = $cat_total_received_amount+$amount_received;
                
                $html.='<tr>
                       <td style="font-size:13px;font-weight:normal;text-align:right;">'.$sub_val['id'].'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.date('m/d/Y',strtotime($sub_val['batch_date'])).'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:right;">$'.number_format($amount_received,2).'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:right;">$'.number_format($posted_commission_amount,2).'</td>
                       <td style="font-size:13px;font-weight:normal;text-align:left;">'.$sub_val['batch_desc'].'</td>';
                $html.='</tr>';
            }
            $total_posted_amount = $total_posted_amount+$cat_total_posted_amount;
            $total_received_amount = $total_received_amount+$cat_total_received_amount;
            $html.='<tr>
                   <td style="font-size:13px;font-weight:bold;text-align:right;"></td>
                           <td style="font-size:13px;font-weight:bold;text-align:right;">** Category Total **</td>
                           <td style="font-size:13px;font-weight:bold;text-align:right;">$'.number_format($cat_total_received_amount,2).'</td>
                           <td style="font-size:13px;font-weight:bold;text-align:right;">$'.number_format($cat_total_posted_amount,2).'</td>
                           <td style="font-size:13px;font-weight:bold;text-align:left;"></td>';
            $html.='</tr>';
        }
        $html.='<tr>
                   <td style="font-size:13px;font-weight:bold;text-align:right;width:20%"></td>
                   <td style="font-size:13px;font-weight:bold;text-align:right;width:20%">*** Total *** </td>
                   <td style="font-size:13px;font-weight:bold;text-align:right;width:15%">$'.number_format($total_received_amount,2).'</td>
                   <td style="font-size:13px;font-weight:bold;text-align:right;width:15%">$'.number_format($total_posted_amount,2).'</td>
                   <td style="font-size:13px;font-weight:bold;text-align:left;width:30%"></td>
                </tr>'; 
    }
    else
    {
        $html.='<tr>
                    <td style="font-size:13px;font-weight:cold;text-align:center;" colspan="8">No record found.</td>
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
                   <td style="font-size:13px;font-weight:bold;text-align:right;width:20%"></td>
                   <td style="font-size:13px;font-weight:bold;text-align:right;width:20%">*** Total *** </td>
                   <td style="font-size:13px;font-weight:bold;text-align:right;width:15%">$'.number_format($total_received_amount,2).'</td>
                   <td style="font-size:13px;font-weight:bold;text-align:right;width:15%">$'.number_format($total_posted_amount,2).'</td>
                   <td style="font-size:13px;font-weight:bold;text-align:left;width:30%"></td>
                </tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);*/
   
    $pdf->lastPage();
    if(isset($_GET['open']) && $_GET['open'] == 'output_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->Output('report_batch.pdf', 'I');
    
    exit;
?>