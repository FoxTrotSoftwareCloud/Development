<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");

$instance = new batches();

//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
$instance_trans = new transaction();

$return_batches = array();
$filter_array = array();
$product_category = '';
$product_category_name = '';
$beginning_date = '';
$ending_date = '';

//filter batch report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);
    $product_category = isset($filter_array['product_category'])?$filter_array['product_category']:0;
    $company = isset($filter_array['company'])?$filter_array['company']:0;
    $batch = isset($filter_array['batch'])?$filter_array['batch']:0;
    $branch = isset($filter_array['branch'])?$filter_array['branch']:0;
    $broker = isset($filter_array['broker'])?$filter_array['broker']:0;
    $client = isset($filter_array['client'])?$filter_array['client']:0;
    $product = isset($filter_array['product'])?$filter_array['product']:0;
    $beginning_date = isset($filter_array['beginning_date'])?$filter_array['beginning_date']:'';
    $ending_date = isset($filter_array['ending_date'])?$filter_array['ending_date']:'';
    $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
    $filter_by= isset($filter_array['filter_by'])?$instance->re_db_input($filter_array['filter_by']):1;
    $prod_cat = isset($filter_array['prod_cat'])?$filter_array['prod_cat']:array();
    $report_for = isset($filter_array['report_for'])?trim($filter_array['report_for']):'';
    $is_trail= isset($filter_array['is_trail'])?$instance->re_db_input($filter_array['is_trail']):0;
    $sponsor = isset($filter_array['sponsor'])?$instance->re_db_input($filter_array['sponsor']):'';
    $date_by= isset($filter_array['date_by'])?$instance->re_db_input($filter_array['date_by']):1;
    $product_cate= isset($filter_array['product_cate'])?$instance->re_db_input($filter_array['product_cate']):'';
   $subheading=strtoupper($report_for)." REPORT ";
    
}   

   
    if($company > 0){
            //$branch_instance = new branch_maintenance();
            $instance_multi_company = new manage_company();
            $name  = $instance_multi_company->select_company_by_id($company); 
            $companyhead=$name['company_name'];
            //$subheading.="\r Broker: (ALL Brokers), Client: (ALL Clients)";
        }
        else{
              $companyhead="All Companies";
             // $subheading.="\r Broker: (ALL Brokers), Client: (ALL Clients)";
        }
   
   
    $subheading=strtoupper($report_for);
    if($filter_by == "1"){
        $subheading2=$beginning_date." thru ".$ending_date;

    }
    if($sort_by=="1")
    {
        $subheading1="Sort by Sponsor";
    }
    else if($sort_by=="2")
    {
        $subheading1="Sort by Investment Amount";
    } 

   
    
    //$subheading.=", Dates: ".$beginning_date." - ".$ending_date;
       
        
        $beginning_date = date('Y-m-d', strtotime('first day of january this year'));
        $ending_date = date("Y-m-d");
        $heading ="All Companies";
        if($company > 0){
            $title="";
        }

        $get_trans_data = $instance_trans->select_year_to_date_sale_report($beginning_date,$ending_date,$company);
           if(!empty($get_trans_data))
            {
                $get_data_by_category = array();
                foreach($get_trans_data as $key=>$val)
                {
                    $get_data_by_category[$val['product_category_name']][] = $val;
                }
                $get_trans_data = $get_data_by_category;
            }

    // create new PDF document
    $pdf = new RRPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // add a page
    $pdf->AddPage('L');
    // Title
    $img = '<img src="'.SITE_URL."upload/logo/".$system_logo.'" height="40px" />';
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);

    $html='<table border="0" width="100%">
                <tr>';
                 $html .='<td width="20%" align="left">'.date("m/d/Y").'</td>';
                
                $html .='<td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">'.$img.'<br/><strong><h9>'.$companyhead.'<br/>'.$subheading.'<br/>'.$subheading2.'<br/>'.$subheading1.'</h9></strong></td>';
                                 
                    $html.='<td width="20%" align="right">PAGE 1</td>';
                
                $html.='</tr>
        </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>';
                    $html .='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;"></td>';
            $html .='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="1" width="100%">
                <tr style="background-color: #f1f1f1;">';
                 if($report_for == "Production by Sponsor Report") 
                        {$html.='<td style="text-align:left;font-weight:bold;"><h5>SPONSER </h5></td>';}
                else 
                        {$html.='<td style="text-align:left;font-weight:bold;"><h5>PRODUCT </h5></td>';}
                 
                $html.='
                   <td style="text-align:right;font-weight:bold;"><h5>AMOUNT INVESTED</h5></td>
                        <td style="text-align:right;font-weight:bold;"><h5>COMMISSION RECEIVED</h5></td>
                        <td style="text-align:right;font-weight:bold;"><h5>COMMISSION PAID</h5></td>';
                if($report_for == "Category Summary Report") {
                    $html.='<td style="text-align:right;font-weight:bold;"><h5>#TRANS </h5></td>
                        <td style="text-align:right;font-weight:bold;"><h5>%TOTAL</h5></td>';
                }
                
    $html.='</tr>';
    
    
    if(!empty($get_trans_data))
    {
            $total_comm_received=0;
            $total_comm_paid=0;
            $total_inv=0;
            $total_no_of_trans=0;
            foreach($get_trans_data as $key => $category_data)
            {
                foreach($category_data as $trans_key=>$trans_data)
                {
                    $total_comm_received+=$trans_data['commission_received'];
                    $total_comm_paid+=$trans_data['charge_amount'];
                    $total_inv+=$trans_data['invest_amount'];
                    $total_no_of_trans+=1;
                }
            }
            //echo '<pre>';print_r($get_trans_data);
            foreach($get_trans_data as $key => $category_data)
            {
                if($report_for == "Production by Product Category"){               
                    $html.= ' <tr>
                                    <td colspan="3" style="font-size:12px;font-weight:bold;text-align:left;">'.$key.'</td>
                                </tr>' ;               
                             
                }
                $total_comm_received_cat=0;
                $total_comm_paid_cat=0;
                $total_inv_cat=0;
                $total_no_of_trans_cat=0;
                $cat_percentage=0;
                foreach($category_data as $trans_key=>$trans_data)
                {
                        //echo '<pre>';print_r($category_data);
                        // $total_comm_received+=$trans_data['commission_received'];
                        // $total_comm_paid+=$trans_data['charge_amount'];
                        // $total_inv+=$trans_data['invest_amount'];
                        $total_comm_received_cat+=$trans_data['commission_received'];
                        $total_comm_paid_cat+=$trans_data['charge_amount'];
                        $total_inv_cat+=$trans_data['invest_amount'];
                        $total_no_of_trans_cat+=1;
                
                    if($report_for != "Category Summary Report")
                    {
                        $html.='<tr>';
                        if($report_for == "Production by Sponsor Report") {
                             $html.='<td style="font-size:10px;font-weight:normal;text-align:left;">'.$trans_data['sponsor_name'].'</td>';}
                        else{               

                             $html.='<td style="font-size:10px;font-weight:normal;text-align:left;">'.$trans_data['product_name'].'</td>';
                        }
                        $html.='
                               <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($trans_data['invest_amount'],2).'</td>
                               <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($trans_data['commission_received'],2).'</td>
                               <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($trans_data['charge_amount'],2).'</td>';
                        $html.='</tr>';
                    }
                }
            if($report_for == "Category Summary Report") 
            {
                $html.=' <tr >
                                    <td style="font-size:10px;font-weight:normal;text-align:left;">'.$key.'</td>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;">$'.number_format($total_inv_cat,2).'</td>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;">$'.number_format($total_comm_received_cat,2).'</td>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;">$'.number_format($total_comm_paid_cat,2).'</td>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;">'.number_format($total_no_of_trans_cat,0).'</td>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;">'.number_format($total_no_of_trans_cat*100/$total_no_of_trans,2).'%</td>
                                    </tr>';
            }                
            else if($report_for == "Production by Product Category") 
            { 
             $html.='<tr style="background-color: #f1f1f1;">
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b>*** PRODUCT CATEGORY SUBTOTALS ***</b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b>$'.number_format($total_inv_cat,2).'</b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b>$'.number_format($total_comm_received_cat,2).'</b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b>$'.number_format($total_comm_paid_cat,2).'</b></td></tr>';
            }
            
        
        }
            $html.='<tr style="background-color: #f1f1f1;">
                            <td  style="font-size:10px;font-weight:bold;text-align:right;"><b>**REPORT TOTAL **</b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b>$'.number_format($total_inv,2).'</b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b>$'.number_format($total_comm_received,2).'</b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b>$'.number_format($total_comm_paid,2).'</b></td>';
            if($report_for == "Category Summary Report") {
                $html.='<td style="font-size:10px;font-weight:bold;text-align:right;"><b>'.number_format($total_no_of_trans,0).'</b></td>
                        <td style="font-size:10px;font-weight:bold;text-align:right;"><b></b></td>';
            }
            $html.='</tr>';
        
                   
       
    }
    else
    {
        $html.='<tr>
                    <td style="font-size:8px;font-weight:cold;text-align:center;" colspan="8">No record found.</td>
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
    $pdf->Output('transaction_report_batch.pdf', 'I');
    
    exit;
?>