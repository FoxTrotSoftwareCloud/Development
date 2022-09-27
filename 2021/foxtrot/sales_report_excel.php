<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $instance = new batches();
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');*/
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
    $filter_by= isset($filter_array['filter_by'])?$instance->re_db_input($filter_array['filter_by']):1;
    
    $date_by= isset($filter_array['date_by'])?$instance->re_db_input($filter_array['date_by']):1;
   

   
}   
    $earning_by= isset($filter_array['earning_by'])?$instance->re_db_input($filter_array['earning_by']):1;

    if(isset($filter_array['date_earning_by'])) {
        $earning_by = $instance->re_db_input($filter_array['date_earning_by']);
    }
    $report_rank_order_by = $filter_array['report_rank'] ?? 0;
    $broker_type = $filter_array['broker_type'] ?? 0;
    $top_broker_count = $filter_array['top_broker_count'] ?? 1;

    $report_year = isset($filter_array['report_year'])?trim($filter_array['report_year']):date("Y");
    //
    $annul_broker_date_type = $filter_array['annual-broker-date-type'] ?? 0; 

    $report_year = isset($filter_array['report_year'])?trim($filter_array['report_year']):date("Y");

    $prod_cat = isset($filter_array['prod_cat'])?$filter_array['prod_cat']:array();

      $prod_cat =array_filter($prod_cat,function($value) {
                return $value > 0;
            });
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

   
   
    $subheading=strtoupper($report_for);
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
   
    if($report_for == "sponsor"){
        if($sponsor > 0){
            $name  = $instance_trans->select_sponsor_by_id($sponsor); 
            $subheading.="\r FOR ".strtoupper($name);
            $subheading.="\r Broker: (All Brokers), Client: (All Clients)";
        }
        else{
              $subheading.="\r FOR All SPONSOR";
              $subheading.="\r Broker: (All Brokers), Client: (All Clients)";
        }
    }
    if($report_for == "company"){
        if($company > 0){
            //$branch_instance = new branch_maintenance();
            $instance_multi_company = new manage_company();
            $name  = $instance_multi_company->select_company_by_id($company); 

            $subheading.="\r FOR ".strtoupper($name['company_name']);
            $subheading.="\r Broker: (All Brokers), Client: (All Clients)";
        }
        else{
              $subheading.="\r FOR All COMPANIES";
              $subheading.="\r Broker: (All Brokers), Clients)";
        }

    }
     if($report_for == "batch"){
        if($batch > 0){
            $branch_instance = new batches();

            $name  = $branch_instance->edit_batches($batch);
           
            $subheading.="\r FOR ".strtoupper($name['batch_desc']);
            $subheading.="\r Broker: (All Brokers), Client: (All Clients)";
        }
        else{
              $subheading.="\r FOR All BATCHES";
              $subheading.="\r Broker: (All Brokers), Client: (All Clients)";
        }

    }
    if($report_for == "client"){
        if($client > 0){
            $branch_instance = new client_maintenance();
            $name  = $branch_instance->select_client_master($client); 

            $subheading.="\r FOR ".strtoupper($name['last_name'].', '.$name['first_name'])."<br/>";
            //$subheading.="<br/>Broker: (All Brokers), Client: (All Clients)";
        }
        else{
              $subheading.="\r FOR All CLIENTS <br/>";
            //  $subheading.="<br/>Broker: (All Brokers), Client: (All Clients)";
        }

    }
   // $subheading.=", Dates: ".$beginning_date." - ".$ending_date;
        
           
   
        //echo '<pre>';print_r($return);exit;
        
        
    $creator                = "Foxtrot User";
    $last_modified_by       = "Foxtrot User";
   // $title                  = "Production by  Category";
    $subject                = " Production by Production Category";
    $description            = " Production by Production Category";
    $keywords               = " Production by Production Category";
    $category               = " Production by Production Category.";
    $total_sub_sheets       = 1;
    
    $default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
    $excel_name             = ' Production by  Category Report';
    $sub_sheet_title_array  = array("Production by  Category");
    $title                  = "Production by  Category";
    $sheet_data             = array();
    $i=5;
        
    if($report_for == "year_to_date"):  
            


        $excel_name             = ' YEAR-TO-DATE EARNINGS REPORT';
        $sub_sheet_title_array  = array("YEAR-TO-DATE EARNINGS");
        $title                  = "YEAR-TO-DATE EARNINGS";

        $beginning_date = date('Y-m-d', strtotime('first day of january '.$report_year));
        $ending_date = date('Y-m-d', strtotime('last day of december '.$report_year));
        $heading ="All Companies";
        if($company > 0){
            $heading=$companyhead;
        }
        //var_dump($earning_by); die;
        $without_earning= isset($filter_array['without_earning'])?$instance->re_db_input($filter_array['without_earning']):'';
        $get_trans_data = $instance_trans->select_year_to_date_sale_report($beginning_date,$ending_date,$company,$without_earning,$earning_by);  
        // Set output excel array.
        /* Headers. */
        $sheet_data = array( // Set sheet data.
            0=> // Excel sub sheet indexed.
            array(
                
                'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(16),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
                'B1'=>array($img."\r\n YEAR-TO-DATE EARNINGS REPORT \r\n Year ".$report_year.", ".$heading."  ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','F2'))),
                'G1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('G1','H2'))),
                
                'A3'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A3','H3'))),
                'A4'=>array('BROKER',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'B4'=>array("NUMBER" ,array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'C4'=>array("GROSS CONCESSIONS",array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'D4'=>array('NET EARNINGS',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
               
                'E4'=>array('CHECK AMOUNT',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'F4'=>array('1099 EARNINGS',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                
                
                
            )
        );
                
            if(is_array($get_trans_data) && count($get_trans_data)>0)
            {
               //echo '<pre>';print_r($get_trans_data);
                    $total_gross_earning=0.00;
                    $total_net_commission=0.00;
                    $total_check_amount=0.00;
                    $total_earning_1099=0.00;   
                   // print_r($get_trans_data); die;
                     
                    foreach($get_trans_data as $trans_key=>$row_item) {

                            $is_branch =false;
                            $trans_rows= [];
                            if(isset($row_item['transactions'])) {
                                $is_branch =true;
                                $bk_name = '** BRANCH : '.$row_item['branch_name'];
                                $sheet_data[0]['A'.$i] = array($bk_name,array('bold','left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'F'.$i)));
                                $trans_rows = $row_item['transactions'];
                                $i = $i+1;
                            }
                            else {
                                $trans_rows[] = $row_item;
                            }
                            
                            $sub_earning_1099= $sub_check_amount = $sub_net_comm = $sub_gross_earning = 0;
                            foreach($trans_rows as $trans_data ){
                         
                                 $gross_earning=$trans_data['gross_production'] - $trans_data['commission_received'];
                                 $net_commission=$trans_data['commission_paid'] + $trans_data['split_paid'] + $trans_data['override_paid'];
                                 $check_amount=$trans_data['check_amount'] >= $trans_data['minimum_check_amount'] ? $trans_data['check_amount'] : 0.00;
                                 $earning_1099=$trans_data['commission_paid'] + $trans_data['split_paid'] + $trans_data['override_paid'] + $trans_data['taxable_adjustments']  - $trans_data['finra'] - $trans_data['sipc'];
                                 $total_gross_earning+=$gross_earning;
                                 $total_net_commission+=$net_commission;
                                 $total_check_amount+=$check_amount;
                                 $total_earning_1099+=$earning_1099; 

                        
                                $sub_gross_earning+=$gross_earning;
                                $sub_net_comm+=$net_commission;
                                $sub_check_amount+=$check_amount;
                                $sub_earning_1099+=$earning_1099; 
                            
                            $sheet_data[0]['A'.$i] = array($trans_data['broker_lastname'].', '.$trans_data['broker_firstname'],array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                             $sheet_data[0]['B'.$i] = array($trans_data['clearing_number'],array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($gross_earning,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($net_commission,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $sheet_data[0]['E'.$i] = array($instance->re_db_output('$'.number_format($check_amount,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($earning_1099,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $i++;
                           
                            }
                            if($is_branch) {
                                $sheet_data[0]['A'.$i] = array("*** BRANCH SUBTOTAL ***",array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'B'.$i)));
                                $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($sub_gross_earning,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($sub_net_comm,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $sheet_data[0]['E'.$i] = array($instance->re_db_output('$'.number_format($sub_check_amount,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($sub_earning_1099,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $i = $i +1;
                            
                            }

                    }
                     $sheet_data[0]['A'.$i] = array("*** REPORT TOTALS ***",array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'B'.$i)));
                     $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($total_gross_earning,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                     $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($total_net_commission,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                     $sheet_data[0]['E'.$i] = array($instance->re_db_output('$'.number_format($total_check_amount,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                      $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($total_earning_1099,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));


                    
                    $i++;
                
                
                
            }
            else
            {
                $i++;
                $sheet_data[0]['A'.$i] = array($instance->re_db_output('No record found.'),array('center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$i,'H'.$i)));
            }
            
            // Create Excel instance.
            $Excel = new Excel();
            
            $args =  array(
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
                );
            //print_r($args); die;
            $formPost = $Excel->generate( $args);

    
    elseif($report_for == "broker_ranking"):

         $prod_cat =array_filter($prod_cat,function($value) {
                return $value > 0;
            });

            $is_all_category = empty($prod_cat);

            $ranks = ['Total Earnings','Gross Concessions','Total Sales','Profitability'];
           
          
            $heading = "BROKER RANKINGS REPORT \r\n ";

            $excel_name             = 'BROKER RANKINGS REPORT';
            $sub_sheet_title_array  = array("BROKER RANKINGS");
            $title                  = "BROKER RANKINGS";

            $subheading = '';
            if(!empty($prod_cat)) {
                $selected_pro_categories = $instance_trans->select_category($prod_cat);
                if(!empty($selected_pro_categories)) {
                    $cat_names = array_column($selected_pro_categories, 'type');
                    $subheading .= implode(', ', $cat_names); 
                }
            }
            else $subheading.= 'All Categories';
            $subheading.= " \r\n ";
            $subheading.=$ranks[($report_rank_order_by-1)].', ';
            $subheading.= ($broker_type == 1) ? ' All Brokers' :  'Top '.$top_broker_count.' Brokers'; 
            $subheading.= " \r\n ";
            
           // var_dump($is_all_category);
            $limit = ($broker_type == 2) ? $top_broker_count : 0; 
            $earning_filter = compact('earning_by','beginning_date','ending_date');
            $get_trans_data = $instance_trans->select_broker_ranking_sale_report($prod_cat,$company,$report_rank_order_by,$limit,$earning_filter);
           
            $subheading2 = '';
            if($earning_by == 2) {
                $subheading2="Date Received ".$beginning_date." through ".$ending_date;
            }
            $col_head1 = 'TOTAL';
            $col_head2 = 'GROSS';
            $col_head3 ='TOTAL';
            $col_head4 ='TOTAL';
        if(!empty($get_trans_data)) {
             $sheet_data = array( // Set sheet data.
            0=> // Excel sub sheet indexed.
            array(
                
                'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
                'B1'=>array($img."\r\n".$heading." \r\n ".$subheading." \r\n ".$subheading2." ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','F3'))),

                'G1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('G1','H2'))),
                
                'A4'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A4','H4'))),

                'A5'=>array(' ',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),

                'B5'=>array("Broker" ,array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),

                'C5'=>array("INTERNAL NO.",array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),

                'D5'=>array($col_head1.' EARNINGS',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
               
                'E5'=>array($col_head2.' CONCESSIONS',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'F5'=>array($col_head3.' SALES',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'G5'=>array($col_head4.' PROFITABILITY',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            )
        );

        

            $excel_font_style = array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'));
            $row_count = 6;
            $total_profit = $total_concessions = $total_invest = $total_comm = 0;
            foreach($get_trans_data as  $key => $trans_row) {

                $total_comm+= $trans_row['total_earnings'];
                $total_invest += $trans_row['total_investment'];
                $total_concessions += $trans_row['total_concessions'];
                $total_profit += $trans_row['total_profit'];

                $sheet_data[0]['A'.$row_count] = array(($key+1),$excel_font_style);

                $sheet_data[0]['B'.$row_count] = array($trans_row['broker_fullname'],$excel_font_style);
                
                $sheet_data[0]['C'.$row_count] = array($trans_row['internal_id'],$excel_font_style);
                
                $sheet_data[0]['D'.$row_count] = array(number_format($trans_row['total_earnings'],2),$excel_font_style);
                
                $sheet_data[0]['E'.$row_count] = array(number_format($trans_row['total_investment'],2),$excel_font_style);

                $sheet_data[0]['F'.$row_count] = array(number_format($trans_row['total_concessions'],2),$excel_font_style);

                $sheet_data[0]['G'.$row_count] = array(number_format($trans_row['total_profit'],2),$excel_font_style);
                
                $row_count++;
            }

            $sheet_data[0]['A'.$row_count] = array("*** REPORT TOTALS ***",array('bold','right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$row_count,'C'.$row_count)));

            $sheet_data[0]['D'.$row_count] = array(number_format($total_comm,2),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            
            $sheet_data[0]['E'.$row_count] = array(number_format($total_invest,2),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                     
            $sheet_data[0]['F'.$row_count] = array(number_format($total_concessions,2),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            
            $sheet_data[0]['G'.$row_count] = array(number_format($total_profit,2),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));

            $row_count++;

        }
        else {
            $row_count++;
            $sheet_data[0]['A'.$row_count] = array($instance->re_db_output('No record found.'),array('center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$row_count,'H'.$row_count)));
        }
       // print_r($sheet_data); die;

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
         

    elseif($report_for == 'annual_broker_report'):
        $date_heading = ($annul_broker_date_type == 1) ? 'By Trade Date' : 'By Settlement Date';
        $subheading = 'ANNUAL BROKER REPORT ';
        $subheading2 = 'For '.$report_year.' - '.$date_heading;

        if($broker > 0){
                $branch_instance = new broker_master();

                $name  = $branch_instance->select_broker_by_id($broker);
               
                $companyhead.=", ".ucfirst($name['last_name']).' '.ucfirst($name['first_name']);
          
            }
            else {
                $companyhead.=', All Brokers';
            } 

        $excel_name             = 'Annual Broker Report';
        $sub_sheet_title_array  = array("Annual Broker");
        $title                  = "Annual Broker";

        $rows = $instance_trans->select_annual_broker_report($report_year,$is_trail,$broker,$company,$annul_broker_date_type);
        
        $sheet_data = array( // Set sheet data.
            0=> // Excel sub sheet indexed.
            array(
                
                'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(16),'font_name'=>array('Calibri'),'merge'=>array('A1','A3'))),
                'B1'=>array($img."\r\n".$heading." \r\n ".$subheading." \r\n ".$companyhead." \r\n".$subheading2." ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','C3'))),

                'D1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('D1','D3') )),

                'A4'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A4','D4'))),

            
                'A5'=>array('Month',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'B5'=>array("NO. OF TRADES" ,array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'C5'=>array("Gross Concessions",array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'D5'=>array('Net Commission',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            )
        );

        $total_trades = $net_commission = $total_comm = 0;
        $notFoundRow = ['no_of_trades'=>0,'net_commission'=>0,'commission_received'=>0];
        $excel_font_style = array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'));
        $row_count = 6;
        for($month= 1 ;$month <=12 ; $month++) {
                $get_month_transaction = isset($rows[$month]) ? $rows[$month] : $notFoundRow; 
                $dateObj   = DateTime::createFromFormat('!m', $month);
                $total_comm+= $get_month_transaction['commission_received'];
                $net_commission+= $get_month_transaction['net_commission'];
                $total_trades+= $get_month_transaction['no_of_trades'];

                $sheet_data[0]['A'.$row_count] = array($dateObj->format('F'),$excel_font_style);

                $sheet_data[0]['B'.$row_count] = array($get_month_transaction['no_of_trades'],$excel_font_style);

                $sheet_data[0]['C'.$row_count] = array(number_format($get_month_transaction['commission_received'],2),$excel_font_style);

                $sheet_data[0]['D'.$row_count] = array(number_format($get_month_transaction['net_commission'],2),$excel_font_style);
                $row_count++;
        }

        $sheet_data[0]['A'.$row_count] = array("*** REPORT TOTALS ***",array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));

        $sheet_data[0]['B'.$row_count] = array($total_trades,array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            
        $sheet_data[0]['C'.$row_count] = array(number_format($total_comm,2),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                     
        $sheet_data[0]['D'.$row_count] = array(number_format($net_commission,2),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));

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
    elseif($report_for == "monthly_broker_production"):

        $earning_by = 2;
        $earning_filter = compact('earning_by','beginning_date','ending_date');
        $rows = $instance_trans->select_monthly_broker_production_report($company,$earning_filter);


        $subheading = 'BROKER PRODUCTION REPORT ';
        $subheading2 = 'Trade Dates: '.$beginning_date." - ".$ending_date;
        $excel_name             = 'Broker Production Report';
        $sub_sheet_title_array  = array("Broker Production");
        $title                  = "Broker Production";

        if(!empty($rows)) {

            $sheet_data = array( // Set sheet data.
                0=> // Excel sub sheet indexed.
                array(
                    
                    'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','A3'))),
                    'B1'=>array($img."\r\n".$subheading." \r\n ".$subheading2." ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','E3'))),

                    'F1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F1','F3') )),

                    'A4'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A4','F4'))),

                
                    'B5'=>array('Product Category',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    
                    'C5'=>array("Investment Amount",array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    'D5'=>array('Commission Received',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    'E5'=>array('Net Commission',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                )
            );

            $row_count = 6;
            foreach($rows as $trans_items) {
               $net_commission = $comm_rev = $total_inv = 0.00;
                $bk_name = $trans_items['broker_full_name'].'   #'.$trans_items['internal_id'];
                $sheet_data[0]['B'.$row_count] = array($bk_name,array('bold','left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B'.$row_count,'E'.$row_count)));


                foreach($trans_items['transactions'] as $sub_items) {
                    $total_inv+= $sub_items['total_investment'];
                    $comm_rev+= $sub_items['total_concessions'];
                    $net_commission+=$sub_items['net_commission'];

                    $main_invest_total+= $sub_items['total_investment'];
                    $main_total_concessions+= $sub_items['total_commission_received'];
                    $main_net_commission+= $sub_items['net_commission'];

                     $row_count = $row_count+1;
                    $sheet_data[0]['B'.$row_count] = array($sub_items['product_cat_name'],array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['C'.$row_count] = array(number_format($sub_items['total_investment'],2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['D'.$row_count] = array(number_format($sub_items['total_commission_received'],2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['E'.$row_count] = array(number_format($sub_items['net_commission'],2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                   // $row_count++;

                }

                $row_count = $row_count+1;
                $sheet_data[0]['B'.$row_count] = array('*** BROKER SUBTOTAL ***',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['C'.$row_count] = array(number_format($total_inv,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['D'.$row_count] = array(number_format($comm_rev,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['E'.$row_count] = array(number_format($net_commission,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));

                $row_count = $row_count+1;

                $sheet_data[0]['B'.$row_count] = array(' ',array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B'.$row_count,'E'.$row_count)));

                $row_count++;

            }

            $sheet_data[0]['B'.$row_count] = array('*** Report Totals ***',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['C'.$row_count] = array(number_format($main_invest_total,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['D'.$row_count] = array(number_format($main_total_concessions,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['E'.$row_count] = array(number_format($main_net_commission,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));

        }
        else {
            $row_count++;
            $sheet_data[0]['A'.$row_count] = array($instance->re_db_output('No record found.'),array('center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$row_count,'E'.$row_count)));
        }
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


    elseif($report_for == "monthly_branch_office"):

        $subheading = 'BRANCH OFFICE PRODUCTION REPORT';
         $subheading2 = 'Trade Dates: '.$beginning_date." - ".$ending_date;
           // $subheading2 = 'Ending Date: '.date('F d, Y',strtotime($ending_date));
        $rows = $instance_trans->select_monthly_branch_office_report($company,$branch,$ending_date,$beginning_date);


        $excel_name             = 'Branch Office Production Report';
        $sub_sheet_title_array  = array("Branch Office");
        $title                  = "Branch Office Production";

        if(!empty($rows)) {
            $sheet_data = array( // Set sheet data.
                0=> // Excel sub sheet indexed.
                array(
                    
                    'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','A3'))),
                    'B1'=>array($img."\r\n".$subheading." \r\n ".$subheading2." ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','E3'))),

                    'F1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('E1','E3') )),

                    'A4'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A4','F4'))),

                
                    'B5'=>array('Product Category',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    
                    'C5'=>array("Investment Amount",array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    'D5'=>array('Commission Received',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    'E5'=>array('Net Commission ',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                )
            );
            $row_count = 6;
            foreach($rows as $trans_items) {
                $branch_name = $trans_items['branch_name'] ?? 'Misc (Branch Name not available)';
                $sheet_data[0]['B'.$row_count] = array($branch_name,array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B'.$row_count,'E'.$row_count)));

                foreach($trans_items['brokers'] as $broker_id => $sub_items) {

                   $net_commission= $comm_rev = $total_inv = 0;

                    $row_count = $row_count+1;

                    $bk_name = $sub_items['broker_full_name'].'   #'.$sub_items['internal_id'];
                    $sheet_data[0]['B'.$row_count] = array($bk_name,array('bold','left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B'.$row_count,'E'.$row_count)));

                    foreach($sub_items['transactions'] as $sub_inner_items) {
                        $row_count = $row_count+1;

                        $total_inv+= $sub_inner_items['total_investment'];
                        $comm_rev+= $sub_inner_items['total_commission_received'];
                        $net_commission+=$sub_inner_items['net_commission'];

                        $main_invest_total+= $sub_inner_items['total_investment'];
                        $main_total_concessions+= $sub_inner_items['total_commission_received'];
                        $main_net_commission+= $sub_inner_items['net_commission'];

                        $sheet_data[0]['B'.$row_count] = array($sub_inner_items['product_cat_name'],array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                        $sheet_data[0]['C'.$row_count] = array(number_format($sub_inner_items['total_investment'],2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));

                        $sheet_data[0]['D'.$row_count] = array(number_format($sub_inner_items['total_commission_received'],2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));

                        $sheet_data[0]['E'.$row_count] = array(number_format($sub_inner_items['net_commission'],2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    }
                    $row_count = $row_count+1;
                    $sheet_data[0]['B'.$row_count] = array('*** BROKER SUBTOTAL ***',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['C'.$row_count] = array(number_format($total_inv,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['D'.$row_count] = array(number_format($comm_rev,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['E'.$row_count] = array(number_format($net_commission,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));

                    $row_count = $row_count+1;

                    $sheet_data[0]['B'.$row_count] = array(' ',array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B'.$row_count,'E'.$row_count)));

                }
                $row_count++;
            }

           // $row_count = $row_count+1;
            $sheet_data[0]['B'.$row_count] = array('*** Report Totals ***',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['C'.$row_count] = array(number_format($main_invest_total,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['D'.$row_count] = array(number_format($main_total_concessions,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['E'.$row_count] = array(number_format($main_net_commission,2),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));


        }
        else {
            $row_count++;
            $sheet_data[0]['A'.$row_count] = array($instance->re_db_output('No record found.'),array('center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$row_count,'E'.$row_count)));
        }
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

    elseif($report_for != "broker"):


        if($report_for == "Product Category Summary Report") :
                $date_heading = ($date_by == 1) ? 'Trade Dates: ' : ' Commission Received Dates: ';
                $subheading2 = $date_heading.$subheading2;
            endif;


            if($report_for == "Production by Sponsor Report") :
                if(!empty($prod_cat)) {
                    $selected_pro_categories = $instance_trans->select_category($prod_cat);
                    if(!empty($selected_pro_categories)) {
                        $cat_names = array_column($selected_pro_categories, 'type');
                        $companyhead .= "\r\n ". implode(', ', $cat_names); 
                    }
                }
                else $companyhead.= "\r\n All Categories";
            endif;

      $get_trans_data = $instance_trans->select_transcation_history_report_v2($report_for,$sort_by,$branch,$broker,'',$client,$product,$beginning_date,$ending_date,$batch,$date_by,$filter_by,$is_trail,$prod_cat);

      ///  print_r($get_trans_data);
        if(!empty($get_trans_data))
        {
            $get_data_by_category = array();
            foreach($get_trans_data as $key=>$val)
            {
                $get_data_by_category[$val['product_category_name']][] = $val;
            }
            $get_trans_data = $get_data_by_category;
        }
        $batch_desc = isset($get_trans_data[0]['batch_desc'])?$instance->re_db_input($get_trans_data[0]['batch_desc']):'';
        $total_amount_invested = 0;
        $total_commission_received = 0;
        $total_charges = 0;

         $sheet_data = array( // Set sheet data.
                0=> // Excel sub sheet indexed.
                array(
                    
                    'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','A3'))),
                    'B1'=>array($img."\r\n".$subheading." \r\n ".$subheading2." ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','E3'))),

                    'F1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F1','F3') )),

                    'A4'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A4','F4'))),

                
                    'B5'=>array('SPONSOR',array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    
                    'C5'=>array("AMOUNT INVESTED",array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    'D5'=>array('COMMISSION RECEIVED',array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    'E5'=>array('COMMISSION PAID',array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                )
            );

         if($report_for == "Product Category Summary Report")  {

            $sheet_data = array( // Set sheet data.
                0=> // Excel sub sheet indexed.
                array(
                    
                    'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','A3'))),
                    'B1'=>array($img."\r\n".$subheading." \r\n ".$subheading2." ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','E3'))),

                    'F1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F1','F3') )),

                    'A4'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A4','F4'))),

                
                    'B5'=>array('SPONSOR',array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    
                    'C5'=>array("AMOUNT INVESTED",array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    'D5'=>array('COMMISSION RECEIVED',array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    'E5'=>array('COMMISSION PAID',array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    'F5'=>array('NO. OF TRANSACTIONS',array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                    'G5'=>array('%TOTAL',array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                )
            );

         }

        $row_count  = 6;
        $excel_font_style = array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'));
        $right_excel_font_style = array('right','bold','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'));
        if(!empty($get_trans_data)) {
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
            foreach($get_trans_data as $key => $category_data) {

                if($report_for == "Production by Product Category"){     

                    $sheet_data[0]['B'.$row_count] = array('Product Category: '.$key,array('left','bold','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B'.$row_count,'E'.$row_count)));
                    $row_count = $row_count+1;      
                }
                $total_comm_received_cat=0;
                $total_comm_paid_cat=0;
                $total_inv_cat=0;
                $total_no_of_trans_cat=0;
                $cat_percentage=0;
                foreach($category_data as $trans_key=>$trans_data) {
                        $total_comm_received_cat+=$trans_data['commission_received'];
                        $total_comm_paid_cat+=$trans_data['charge_amount'];
                        $total_inv_cat+=$trans_data['invest_amount'];
                        $total_no_of_trans_cat+=1;
                        if($report_for != "Product Category Summary Report") {

                            $sub_cat_name =  ($report_for == "Production by Sponsor Report") ?  $trans_data['sponsor_name'] : $trans_data['product_name']; 
                            $sheet_data[0]['B'.$row_count] = array($sub_cat_name,$excel_font_style);

                            $sheet_data[0]['C'.$row_count] = array($trans_data['invest_amount'],array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));

                            $sheet_data[0]['D'.$row_count] = array(number_format($trans_data['commission_received'],2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));

                            $sheet_data[0]['E'.$row_count] = array(number_format($trans_data['charge_amount'],2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $row_count++;
                        }
                }
                if($report_for == "Product Category Summary Report") {

                    $sheet_data[0]['B'.$row_count] = array($key,$excel_font_style);

                  $sheet_data[0]['C'.$row_count] = array(number_format($total_inv_cat,2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));

                    $sheet_data[0]['D'.$row_count] = array(number_format($total_comm_received_cat,2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));


                    $sheet_data[0]['E'.$row_count] =array(number_format($total_comm_paid_cat,2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));

                    $sheet_data[0]['F'.$row_count] =array(number_format($total_no_of_trans_cat,2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));

                    $sheet_data[0]['G'.$row_count] = array(number_format($total_no_of_trans_cat*100/$total_no_of_trans,2).'%',array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));

                      $row_count++;
                }  else if($report_for == "Production by Product Category")  {
                    $sheet_data[0]['B'.$row_count] = array('** PRODUCT CATEGORY SUBTOTAL **',$right_excel_font_style);

                    $sheet_data[0]['C'.$row_count] = array(number_format($total_inv_cat,2),$right_excel_font_style);

                    $sheet_data[0]['D'.$row_count] = array(number_format($total_comm_received_cat,2),$right_excel_font_style);

                    $sheet_data[0]['E'.$row_count] = array(number_format($total_comm_paid_cat,2),$right_excel_font_style);
                      $row_count++;
                }



              
            }
         //   $row_count = $row_count +1;
            $sheet_data[0]['B'.$row_count] = array('***REPORT TOTALS ***',$right_excel_font_style);

            $sheet_data[0]['C'.$row_count] = array(number_format($total_inv,2),$right_excel_font_style);

            $sheet_data[0]['D'.$row_count] = array(number_format($total_comm_received,2),$right_excel_font_style);

            $sheet_data[0]['E'.$row_count] = array(number_format($total_comm_paid,2),$right_excel_font_style);
            if($report_for == "Product Category Summary Report") {
                $sheet_data[0]['F'.$row_count] = array(number_format($total_no_of_trans,0),$right_excel_font_style);

                $sheet_data[0]['G'.$row_count] = array(' ',$right_excel_font_style);
            }
        } else {
            $sheet_data[0]['A'.$row_count] = array($instance->re_db_output('No record found.'),array('center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$row_count,'H'.$row_count)));
        }









        $excel_name             =strtoupper($report_for);
        $sub_sheet_title_array  = array(strtoupper($report_for));
        $title                  = strtoupper($report_for);
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

    else:

        $get_trans_data = $instance_trans->select_transcation_history_report_v2($report_for,$sort_by,$branch,$broker,'',$client,$product,$beginning_date,$ending_date,$batch,$date_by,$filter_by,$is_trail,$prod_cat);
        if(!empty($get_trans_data))
        {
            $get_data_by_category = array();
            foreach($get_trans_data as $key=>$val)
            {
                $get_data_by_category[$val['product_category_name']][] = $val;
            }
            $get_trans_data = $get_data_by_category;
        }

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
                'B4'=>array($report_for == "client" ? "CLIENT" : "PRODUCT" ,array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'C4'=>array($report_for == "client" ? "BROKER" : "TRADE",array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'D4'=>array('AMOUNT INVESTED',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
               
                'E4'=>array('COMMISSION RECEIVED',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'F4'=>array('COMMISSION PAID',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                
                
                
            )
        );
                
            if(is_array($get_trans_data) && count($get_trans_data)>0)
            {
                $total_inv = 0;
                $total_comm_received=0;
                $total_comm_paid=0;
                 foreach($get_trans_data as $key => $category_data)
                        {
                 
                            $total_comm_received_cat=0;
                            $total_comm_paid_cat=0;
                            $total_inv_cat=0;
                            foreach($category_data as $trans_key=>$trans_data)
                            {
                    
                        $total_comm_received_cat+=$trans_data['commission_received'];
                        $total_comm_paid_cat+=$trans_data['charge_amount'];
                        $total_inv_cat+=$trans_data['invest_amount'];
                        $total_inv+=$trans_data['invest_amount'];
                        $total_comm_received+=$trans_data['commission_received'];
                        $total_comm_paid+=$trans_data['charge_amount'];
                        
                            
                            
                            $sheet_data[0]['A'.$i] = array($instance->re_db_output($trans_data['product_name']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                             $sheet_data[0]['B'.$i] = array($instance->re_db_output('$'.number_format($trans_data['invest_amount'],2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($trans_data['commission_received'],2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($trans_data['charge_amount'],2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                            $i++;
                           
                    }
                     $sheet_data[0]['A'.$i] = array("** Product Category TOTAL **",array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));
                     $sheet_data[0]['B'.$i] = array($instance->re_db_output('$'.number_format($total_inv_cat,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));
                     $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($total_comm_received_cat,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));
                     $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($total_comm_paid_cat,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));


                    
                    $i++;
                }
                $sheet_data[0]['A'.$i] = array("** Report TOTAL **",array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));
                     $sheet_data[0]['B'.$i] = array($instance->re_db_output('$'.number_format($total_inv,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));
                     $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($total_comm_received,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));
                     $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($total_comm_paid,2)),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')),'merge'=>array('C'.$i,'D'.$i));


                    
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

     endif;   
    
    
?>