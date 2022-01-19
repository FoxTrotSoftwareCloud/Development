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
    $report_for = isset($filter_array['report_for'])?trim($filter_array['report_for']):'';
    $sponsor = isset($filter_array['sponsor'])?$instance->re_db_input($filter_array['sponsor']):'';
    $date_by= isset($filter_array['date_by'])?$instance->re_db_input($filter_array['date_by']):1;
    $filter_by= isset($filter_array['filter_by'])?$instance->re_db_input($filter_array['filter_by']):1;
    $is_trail= isset($filter_array['is_trail'])?$instance->re_db_input($filter_array['is_trail']):0;
   
}   

   
   
   
   
    $subheading="<br/>TRANSACTION BY ".strtoupper($report_for)." REPORT";
    
   
    if($report_for == "sponsor"){
        if($sponsor > 0){
            $name  = $instance_trans->select_sponsor_by_id($sponsor); 
            $subheading.="<br/> FOR ".strtoupper($name);
            //$subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }
        else{
              $subheading.="<br/> FOR ALL SPONSORS";
             // $subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }
    }
    if($report_for == "branch"){
        if($branch > 0){
            $branch_instance = new branch_maintenance();
            $name  = $branch_instance->select_branch_by_id($branch); 
            $subheading.="<br/> FOR ".strtoupper($name['name']);
           // $subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }
        else{
              $subheading.="<br/> FOR ALL BRANCHES";
            //  $subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }

    }
    if($report_for == "batch"){
         $branch_instance = new batches();
         $subheading.="<br/>FOR ";
         if($batch_cate > 0){
            $type=$branch_instance->select_batches_with_cat($batch_cate);
         
            $subheading.=strtoupper($type['type']).", ";
         }
         
        if($batch > 0){
           

            $name  = $branch_instance->edit_batches($batch);
           
            $subheading.=" ".strtoupper($name['batch_desc']);
           
        }
        else{
             
              $subheading.=" ALL BATCHES";
           
        }

    }
    if($report_for == "client"){
        if($client > 0){
            $branch_instance = new client_maintenance();
            $name  = $branch_instance->select_client_master($client); 

            $subheading.="<br/> FOR ".strtoupper($name['last_name'].', '.$name['first_name'])."<br/>";
            //$subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }
        else{
              $subheading.="<br/> FOR ALL CLIENTS <br/>";
            //  $subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }

    }
    if($report_for == "broker"){
        if($broker > 0){
            $branch_instance = new broker_master();

            $name  = $branch_instance->select_broker_by_id($broker);
           
            $subheading.="<br/> FOR ".strtoupper($name['last_name']).' '.strtoupper($name['first_name']);
          
        }
        else{
              $subheading.="<br/> FOR ALL BROKERS";
             
        }

    }
    if($filter_by == "1"){

         $subheading.=", DATES: ".$beginning_date." - ".$ending_date;
    }

    $get_trans_data = $instance_trans->select_transcation_history_by_broker($broker,$beginning_date,$ending_date,$date_by,$filter_by,$is_trail);
           

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
                
                $html .='<td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">'.$img.''.$subheading.'</td>'; 
                
                    $html.='<td width="20%" align="right">PAGE 1</td>';
                
                $html.='</tr>
        </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(6);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
   /* $html='<table border="0">
                <tr><td colspan="7"></td>';
                  //  $html .='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">'.$beginning_date.'-'.$ending_date.'</td>';
            $html .='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);*/
    
 
    $html='<table border="0" width="100%">
                <tr style="background-color: #f1f1f1;">
                   <td style="text-align:center;"><h5>TRADE #</h5></td>';
    $html.=' <td width="15%" style="text-align:center;"><h5>TRADE DATE / SETTLE DATE</h5></td>
                <td style="text-align:center;"><h5>CLIENT / FILE #</h5></td>';
          
    $html.='<td style="text-align:center;"><h5>AMOUNT INVESTED</h5></td>
                        <td style="text-align:center;"><h5>COMM RECD </h5></td>
                        <td style="text-align:center;"><h5>COMM PAID</h5></td>
                        <td style="text-align:center;"><h5>DATE RECD</h5></td>
                        <td style="text-align:center;"><h5>DATE PAID</h5></td>';
    $html.='</tr><br/>';
    
    
    if(!empty($get_trans_data))
    {
        
               $total_comm_received=0;
            $total_comm_paid=0;
            foreach($get_trans_data as $trans_main_key=>$trans_main_data)
            {
                 $html.='<tr><td style="font-size:8px;font-weight:bold;text-align:left;" colspan="8">
                            BROKER: '.$trans_main_data['broker'].'</td></tr>';
                foreach($trans_main_data['products'] as $trans_key=>$trans_data_product){
                        $sub_total_records=0;
                        $sub_total_amount_invested = 0;
                        $sub_total_commission_received = 0;
                        $sub_total_charges = 0;
                        $html.='<tr> <td style="font-size:8px;font-weight:bold;text-align:left;" colspan="8">
                             PRODUCT: '.$trans_data_product[0]['product_name'].'</td> </tr>';
                       

                       
                        foreach($trans_data_product as $trans_key=>$trans_data)
                        {
                             $total_comm_received+=$trans_data['commission_received'];
                            $total_comm_paid+=$trans_data['charge_amount'];
                            $total_records = $total_records+1;
                            $sub_total_records = $sub_total_records+1;
                            $total_amount_invested = ($total_amount_invested+$trans_data['invest_amount']);
                            $total_commission_received = ($total_commission_received+$trans_data['commission_received']);
                            $total_charges = ($total_charges+$trans_data['charge_amount']);
                            
                            $sub_total_amount_invested = ($sub_total_amount_invested+$trans_data['charge_amount']);
                            $sub_total_commission_received = ($sub_total_commission_received+$trans_data['commission_received']);
                            $sub_total_charges = ($sub_total_charges+$trans_data['charge_amount']);
                            $html.='<tr>
                                  <td style="font-size:8px;font-weight:normal;text-align:left;">'.$trans_data['id'].'</td>
                                  <td style="font-size:8px;font-weight:normal;text-align:left;">'.date("m/d/Y",strtotime($trans_data['trade_date'])) ."<br/>" . date("m/d/Y",strtotime($trans_data['settlement_date'])).'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:center;">'.$trans_data['client_name']. "<br/>" .$trans_data['client_number'].'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($trans_data['invest_amount'],2).'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($trans_data['commission_received'],2).'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:right;">$'.number_format($trans_data['charge_amount'],2).'</td>
                                   <td style="font-size:8px;font-weight:normal;text-align:right;">'.date("m/d/Y",strtotime($trans_data['commission_received_date'])).'</td> 
                                   <td style="font-size:8px;font-weight:normal;text-align:right;">'.date("m/d/Y",strtotime($trans_data['ch_date'])).'</td>';

                            $html.='</tr>';
                   }

                   $html.='<tr style="background-color: #f1f1f1;">
                            <td></td>
                            <td></td>
                            <td colspan="2" style="font-size:8px;font-weight:bold;text-align:right;"><b>** PRODUCT SUBTOTAL **</b></td>
                            <td style="font-size:8px;font-weight:bold;text-align:right;"><b>$'.number_format($sub_total_commission_received,2).'</b></td>
                            <td style="font-size:8px;font-weight:bold;text-align:right;"><b>$'.number_format($sub_total_amount_invested,2).'</b></td><td> </td> <td> </td> </tr>';
                } 

            }

                $html.='<tr style="background-color: #f1f1f1;">
                            <td></td>
                            <td></td>
                            
                            <td colspan="2" style="font-size:8px;font-weight:bold;text-align:right;"><b>*** REPORT TOTALS ***  </b></td>
                            <td  style="font-size:8px;font-weight:bold;text-align:right;"><b>$'.number_format($total_comm_received,2).'</b></td><td  style="font-size:8px;font-weight:bold;text-align:right;"><b>$'.number_format($total_comm_paid,2).'</b></td><td> </td> <td> </td> </tr>';   
           
            
            
        
       
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
    $pdf->Output('transaction_history_report_batch.pdf', 'I');
    
    exit;
?>