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
    $batch_cate= isset($filter_array['batch_cate'])?$instance->re_db_input($filter_array['batch_cate']):'';
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
    $product_cate= isset($filter_array['product_cate'])?$instance->re_db_input($filter_array['product_cate']):'';
   
}  
    $enter_char = chr(10) . chr(13); 

   
   
              
   
    $subheading="TRANSACTION BY ".strtoupper($report_for)." REPORT ".$enter_char;
    
   
    if($report_for == "sponsor"){
        $index_column="sponsor";
        if($sponsor > 0){
            $name  = $instance_trans->select_sponsor_by_id($sponsor); 
            $subheading.="<br/> FOR ".strtoupper($name);
           // $subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }
        else{
              $subheading.="<br/> FOR ALL SPONSORS";
             // $subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }
    }
    if($report_for == "branch"){
         $index_column="branch";
        if($branch > 0){
            $branch_instance = new branch_maintenance();
            $name  = $branch_instance->select_branch_by_id($branch); 
            $subheading.="<br/> FOR ".strtoupper($name['name']);
           // $subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }
        else{
              $subheading.="<br/> FOR ALL BRANCHES";
             // $subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }

    }
    if($report_for == "batch"){
         $index_column="batch";
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
         $index_column="client_name";
        if($client > 0){
            $branch_instance = new client_maintenance();
            $name  = $branch_instance->select_client_master($client); 

            $subheading.="<br/> FOR ".strtoupper($name['last_name'].', '.$name['first_name']);
            //$subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }
        else{
              $subheading.="<br/> FOR ALL CLIENTS";
            //  $subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }

    }
    if($report_for == "broker"){
         $index_column="broker_name";
        if($broker > 0){
            $branch_instance = new broker_master();

            $name  = $branch_instance->select_broker_by_id($broker);
           
            $subheading.="<br/> FOR ".strtoupper($name['last_name']).' '.strtoupper($name['first_name']);
          
        }
        else{
              $subheading.="<br/> FOR ALL BROKERS";
             
        }

    }

    if($report_for == "product"){
         $index_column="product";
        $branch_instance = new batches();
        $subheading.="<br/>FOR ";
        if($product_cate > 0){
            $type=$branch_instance->select_batches_with_cat($product_cate);
         
            $subheading.=strtoupper($type['type']).", ";
         }
        if($product > 0){
            $product_instance = new product_maintenance();
            $name  = $product_instance->edit_product($product); 
            $subheading.=strtoupper($name['name']);
           
        }
        else{
              $subheading.=" ALL PRODUCTS";
            
        }
    }
   if($filter_by == "1"){

         $subheading.=", DATES: ".$beginning_date." - ".$ending_date;
    }
       
            $get_trans_data = $instance_trans->select_transcation_history_report($branch,$broker,'',$client,$product,$beginning_date,$ending_date,$batch,$date_by,$filter_by,$is_trail,$sponsor,$index_column);
        //echo '<pre>';print_r($return);exit;
        
        
    $creator                = "Foxtrot User";
    $last_modified_by       = "Foxtrot User";
    $title                  = "Transaction History Report";
    $subject                = "Foxtrot Transaction History Report";
    $description            = "Foxtrot Transaction History Report. Generated on : ".date('Y-m-d h:i:s');
    $keywords               = "Foxtrot Transaction History report office 2007";
    $category               = "Foxtrot Transaction History Report.";
    $total_sub_sheets       = 1;
    $sub_sheet_title_array  = array("Transaction History Report");
    $default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
    $excel_name             = 'Foxtrot Transaction History Report';
    $sheet_data             = array();
    $i=5;
    if($report_for == "client" || $report_for == 'product') 
        $column= $report_for == "client" ? "PRODUCT": "CLIENT";
    else
        $column=  "PRODUCT";     
            
    // Set output excel array.
    /* Headers. */
    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            
            'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(16),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
            'B1'=>array($img.$subheading,array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','F2'))),
            'G1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('G1','H2'))),
            
            'A3'=>array($batch_name,array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A3','H3'))),
            'A4'=>array('DATE',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'B4'=>array( $column ,array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'C4'=>array($report_for == "client" ? "BROKER" : "TRADE",array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'D4'=>array('AMOUNT INVESTED',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
           
            'E4'=>array('COMMISSION RECEIVED',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'F4'=>array('COMMISSION PAID',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            
            
            
        )
    );
            
        if(is_array($get_trans_data) && count($get_trans_data)>0)
        {
            $total_comm_received=0;
            $total_comm_paid=0;
            foreach($get_trans_data as $trans_main_key=>$trans_main_data)
            {
                
                    
                    
                    $sub_total_records=0;
                    $sub_total_amount_invested = 0;
                    $sub_total_commission_received = 0;
                    $sub_total_charges = 0;
                    $sheet_data[0]['A'.$i] = array($trans_main_data['broker'],array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'F'.$i)));
                    $i++;
                    foreach($trans_main_data['products'] as $trans_key=>$val){    
                    
                        $date = ($date_by == "1") ? $val['trade_date'] : $val['commission_received_date'];
                        $sub_total_amount_invested = ($sub_total_amount_invested+$val['charge_amount']);
                        $sub_total_commission_received = ($sub_total_commission_received+$val['commission_received']);
                        $sub_total_charges = ($sub_total_charges+$val['charge_amount']);
                        $total_comm_received+=$val['commission_received'];
                         $total_comm_paid+=$val['charge_amount'];

                            
                            $sheet_data[0]['A'.$i] = array(date('m/d/Y',strtotime($date)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            if($report_for == "client" || $report_for == 'product') : 
                                  $col_value = $report_for == "client" ?  $instance->re_db_output($val['product_name']):$val['client_name'];
                                $sheet_data[0]['B'.$i] = array($col_value ,array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                             else:
                                $sheet_data[0]['B'.$i] = array($instance->re_db_output($val['product_name']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            endif;
                            $sheet_data[0]['C'.$i] = array($report_for == "client" ? $val['broker_last_name'].', '.$val['broker_name']: $val['id'],array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($val['invest_amount'],2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $sheet_data[0]['E'.$i] = array($instance->re_db_output('$'.number_format($val['commission_received'],2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($val['charge_amount'],2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $i++;
                       
                }
                 $sheet_data[0]['C'.$i] = array("*** Sub TOTALS ***  ",array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));
                 $sheet_data[0]['E'.$i] = array($instance->re_db_output('$'.number_format($sub_total_commission_received,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));
                 $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($sub_total_amount_invested,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));
                 $i++;
            }
                $i++;
                 $sheet_data[0]['C'.$i] = array("*** REPORT TOTALS ***  ",array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));
                 $sheet_data[0]['E'.$i] = array($instance->re_db_output('$'.number_format($total_comm_received,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));
                 $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($total_comm_paid,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));


                
                $i++;
           
            
        }
        else
        {
            $i++;
            $sheet_data[0]['A'.$i] = array($instance->re_db_output('No record found.'),array('center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$i,'H'.$i)));
        }
        
        // Create Excel instance.
        $Excel = new Excel();
        
        
        $formPost = $Excel->generate(
            array(
                'creator'=>$creator,
                'last_modified_by'=>$last_modified_by,
                'title'=>$title,
                'subject'=>$subject,
                'description'=>$description,
                'keywords'=>$keywords,
                'category'=>$category,
                'total_sub_sheets'=>$total_sub_sheets,
                'sub_sheet_title'=>$sub_sheet_title_array,
                'default_open_sub_sheet'=>$default_open_sub_sheet,
                'sheet_data'=>$sheet_data,
                'excel_name'=>$excel_name
            )
        );
        
        
    
    
?>