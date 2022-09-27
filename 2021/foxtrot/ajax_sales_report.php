<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new batches();
$instance_trans = new transaction();
$get_trans_data = array();
$return_batches = array();
$filter_array = array();
$product_category = '';
$product_category_name = '';
$beginning_date = '';
$ending_date = '';

//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
$img = '<img src="'.SITE_URL."upload/logo/".$system_logo.'" height="25px" />';

//filter batch report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);
    $product_category = isset($filter_array['product_category'])?$filter_array['product_category']:0;

    $company = isset($filter_array['company'])?$filter_array['company']:0;
    $batch = isset($filter_array['batch'])?$filter_array['batch']:0;
    $branch = isset($filter_array['branch'])?$filter_array['branch']:0;
    $batch_cate= isset($filter_array['batch_cate'])?$instance->re_db_input($filter_array['batch_cate']):'';
    $broker = isset($filter_array['broker'])?$filter_array['broker']:0;
    $client = isset($filter_array['client'])?$filter_array['client']:0;
    $product = isset($filter_array['product'])?$filter_array['product']:0;
    $beginning_date = isset($filter_array['beginning_date'])?$filter_array['beginning_date']:'';
    $ending_date = isset($filter_array['ending_date'])?$filter_array['ending_date']:'';
    $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
    $report_for = isset($filter_array['report_for'])?trim($filter_array['report_for']):'';
    $prod_cat = isset($filter_array['prod_cat'])?$filter_array['prod_cat']:array();
     $prod_cat =array_filter($prod_cat,function($value) {
                return $value > 0;
            });
   // print_r($prod_cat);
    $sponsor = isset($filter_array['sponsor'])?$instance->re_db_input($filter_array['sponsor']):'';
    $rep_no = isset($filter_array['rep_no'])?$instance->re_db_input($filter_array['rep_no']):'';
    $date_by= isset($filter_array['date_by'])?$instance->re_db_input($filter_array['date_by']):1;
    $filter_by= isset($filter_array['filter_by'])?$instance->re_db_input($filter_array['filter_by']):1;
    $is_trail= isset($filter_array['is_trail'])?$instance->re_db_input($filter_array['is_trail']):0;
    $product_cate= isset($filter_array['product_cate'])?$instance->re_db_input($filter_array['product_cate']):'';
    $earning_by= isset($filter_array['earning_by'])?$instance->re_db_input($filter_array['earning_by']):1;
    $subheading=strtoupper($report_for);
    
   if(isset($filter_array['date_earning_by'])) {
        $earning_by = $instance->re_db_input($filter_array['date_earning_by']);
    }
    $report_rank_order_by =  $filter_array['report_rank'] ?? 0;
    $broker_type = $filter_array['broker_type'] ?? 0;
    $top_broker_count = $filter_array['top_broker_count'] ?? 1;

    $report_year = isset($filter_array['report_year'])?trim($filter_array['report_year']):date("Y");
    //
    $annul_broker_date_type = $filter_array['annual-broker-date-type'] ?? 0; 


    if($report_for == "sponsor"){
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

            $subheading.="<br/> FOR ".strtoupper($name['last_name'].', '.$name['first_name']);
            //$subheading.="<br/>Broker: (ALL Brokers), Client: (ALL Clients)";
        }
        else{
              $subheading.="<br/> FOR ALL CLIENTS";
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

    if($report_for == "product"){
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


           if($report_for == "year_to_date"):
             
            $beginning_date = date('Y-m-d', strtotime('first day of january '.$report_year));
            $ending_date = date('Y-m-d', strtotime('last day of december '.$report_year));
            $heading ="All Companies";
            if($company > 0){
                $heading=$companyhead;
            }
            $without_earning= isset($filter_array['without_earning'])?$instance->re_db_input($filter_array['without_earning']):'';
            $get_trans_data = $instance_trans->select_year_to_date_sale_report($beginning_date,$ending_date,$company,$without_earning,$earning_by);
             /*echo '<pre>';print_r($get_trans_data);
             echo '</pre>';*/
            ?>
            <table border="0" width="100%">
                    <tr>
                         <td width="20%" align="left"><?php echo date("m/d/Y"); ?> </td>

                         <td width="60%" align="center"><?php echo $img; ?> <br/>
                          <strong><h5>YEAR-TO-DATE EARNINGS REPORT </h5> <h6>Year <?php echo $report_year.', '.$heading;?></h6> </td>
                         <td width="20%" align="right" 1>Page 1</td>
                    </tr>    
                    
            </table>
            <br />
            <table border="0" cellpadding="1" width="100%">
                <thead>
                    <tr style="background-color: #f1f1f1;">
                   
                       
                        <td style="text-align:left;font-weight:bold;"><h5>BROKER </h5></td>
                       
                        <td style="text-align:left;font-weight:bold;"><h5>NUMBER </h5></td>
                       
                        <td style="text-align:right;font-weight:bold;"><h5>GROSS CONCESSIONS</h5></td>
                        <td style="text-align:right;font-weight:bold;"><h5>NET EARNINGS</h5></td>
                        <td style="text-align:right;font-weight:bold;"><h5>CHECK AMOUNT</h5></td>
                        <td style="text-align:right;font-weight:bold;"><h5>1099 EARNINGS</h5></td>
                      
                    </tr>
                </thead>
                <tbody>
                <?php 

                
                if(!empty($get_trans_data))
                {                   
                    //echo '<pre>';print_r($get_trans_data);
                    $total_gross_earning=0.00;
                    $total_net_commission=0.00;
                    $total_check_amount=0.00;
                    $total_earning_1099=0.00;   
                    
                   
                        foreach($get_trans_data as $key => $row_item)
                        {
                            $is_branch =false;
                            $trans_rows= [];
                            if(isset($row_item['transactions'])) {
                                $is_branch =true;
                                echo '<tr> <td colspan="6" align="left"> <span style="font-weight:bold;font-size:14px;">** BRANCH : '.$row_item['branch_name'].'</span></td> </tr>';
                                $trans_rows = $row_item['transactions'];
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

                            ?>
                             <tr>
                            <td style="text-align:left;font-weight:bold;"><h5><?php echo $trans_data['broker_lastname'].', '.$trans_data['broker_firstname'];?> </h5></td>
                           
                            <td style="text-align:left;font-weight:bold;"><h5><?php echo $trans_data['clearing_number'];?> </h5></td>
                           
                            <td style="text-align:right;font-weight:bold;"><h5><?php echo '$'.number_format($gross_earning,2);?></h5></td>
                            <td style="text-align:right;font-weight:bold;"><h5><?php echo '$'.number_format($net_commission,2);?></h5></td>
                            <td style="text-align:right;font-weight:bold;"><h5><?php echo '$'.number_format($check_amount,2);?></h5></td>
                            <td style="text-align:right;font-weight:bold;"><h5><?php echo '$'.number_format($earning_1099,2);?></h5></td>
                             </tr>
                             <?php
                         }
                         if($is_branch) {
                            echo '<tr class="t-footer-items">
                             <td  style="font-size:10px;font-weight:bold;text-align:right;" colspan="2" class="total" align="right">*** BRANCH SUBTOTAL ***</td> 
                                <td style="font-size:12px;font-weight:bold;text-align:right;" align="right"> $'.number_format($sub_gross_earning,2).'</td>
                                <td style="font-size:12px;font-weight:bold;text-align:right;"  align="right"> $'.number_format($sub_net_comm,2).'</td>
                                <td  style="font-size:12px;font-weight:bold;text-align:right;" align="right">$'.number_format($sub_check_amount,2).'</td>
                                <td  style="font-size:12px;font-weight:bold;text-align:right;" align="right"> $'.number_format($sub_earning_1099,2).'</td>
                             </tr>';

                         }

                        }
                    

                    ?>                       
                        <tr style="background-color: #f1f1f1;">
                            <td style="font-size:12px;font-weight:bold;text-align:right;" colspan="2"><b>*** REPORT TOTALS ***</b></td>
                            <td style="font-size:12px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_gross_earning,2);?></b></td>
                            <td style="font-size:12px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_net_commission,2);?></b></td>
                            <td style="font-size:12px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_check_amount,2);?></b></td>
                            <td style="font-size:12px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_earning_1099,2);?></b></td>
                             
                         
                        </tr>
              
                

            <?php } 
            else
            {?>
                <tr>
                   <td style="font-size:10px;font-weight:normal;text-align:center;" colspan="9">No Records Found.</td>
                </tr>
            <?php } ?>           
            </tbody>
        </table>
    <?php 
        elseif($report_for == "broker_ranking"):

            $prod_cat =array_filter($prod_cat,function($value) {
                return $value > 0;
            });
          //  print_r($prod_cat);
            $is_all_category = empty($prod_cat);

            $ranks = ['Total Earnings','Gross Concessions','Total Sales','Profitability'];
           // var_dump($report_rank_order_by);
            $subheading = '<h6> <strong> BROKER RANKINGS REPORT </strong> </h6> <h5> '.$companyhead.' </h5>  <h6>  ';
            

            if(!empty($prod_cat)) {
                $selected_pro_categories = $instance_trans->select_category($prod_cat);
                if(!empty($selected_pro_categories)) {
                    $cat_names = array_column($selected_pro_categories, 'type');
                    $subheading .= implode(', ', $cat_names); 
                }
            }
            else $subheading.= 'All Categories';
            $subheading.= '</h6> <h6>';
            $subheading.=$ranks[($report_rank_order_by-1)].', ';
            $subheading.= ($broker_type == 1) ? ' All Brokers' :  'Top '.$top_broker_count.' Brokers'; 
            $subheading.= '</h6>';
            
           // var_dump($is_all_category);
            $limit = ($broker_type == 2) ? $top_broker_count : 0; 
            $earning_filter = compact('earning_by','beginning_date','ending_date');
            $get_trans_data = $instance_trans->select_broker_ranking_sale_report($prod_cat,$company,$report_rank_order_by,$limit,$earning_filter);
           
            $subheading2 = '';
            if($earning_by == 2) {
                $subheading2="Date Received ".$beginning_date." through ".$ending_date;
            }
           

            echo '<table border="0" width="100%">
                    <tr>
                         <td width="20%" align="left">'.date("m/d/Y").'</td>
                         <td width="60%" align="center">'. $img.'<br/>
                          '.$subheading.'
                        
                          <h6>'.$subheading2.'</h6></td>
                         <td width="20%" align="right">Page 1</td>
                    </tr>        
            </table>  <br />';
            echo '<table border="0" cellpadding="1" width="100%">
                <thead class="modal-heading-row">
                <tr >'; 
                   if(!$is_all_category)  echo '<td> &nbsp; </td>';
                 echo '<td>Broker </td>
                    <td align="right">INTERNAL No.</td>
                    <td align="right">'; echo 'TOTAL'; echo ' EARNINGS </td> 
                    <td align="right">'; echo 'GROSS'; echo ' CONCESSIONS </td>
                    <td align="right">'; echo  'TOTAL'; echo ' SALES </td>
                    <td align="right">'; echo  'TOTAL'; echo ' PROFITABILITY </td>
                </tr>
                </thead>';

            if(!empty($get_trans_data)) {
                $total_profit = $total_concessions = $total_invest = $total_comm = 0;

                echo '<tbody class="modal-tbody-rows">';
                foreach($get_trans_data as $key => $trans_row) {
                    $total_comm+= $trans_row['total_earnings'];
                    $total_invest += $trans_row['total_investment'];
                    $total_concessions += $trans_row['total_concessions'];
                    $total_profit += $trans_row['total_profit'];
                    echo '<tr>';
                    if(!$is_all_category)   echo '<td>'.($key+1).' </td>';
                    echo '<td> '.$trans_row['broker_fullname'].'</td>';
                    echo '<td align="right">'.$trans_row['internal_id'].'</td>';
                    echo '<td align="right">'.number_format($trans_row['total_earnings'],2).'</td>'; 
                    echo '<td align="right"> '.number_format($trans_row['total_investment'],2).' </td>';
                    echo '<td align="right"> '.number_format($trans_row['total_concessions'],2).' </td>';
                    echo '<td align="right">'.number_format($trans_row['total_profit'],2).' </td>';
                    echo '</tr>';
                }
                echo '</tbody>';
               
                echo '<tfoot> <tr class="t-footer-items">';
                if(!$is_all_category)  echo '<td >&nbsp;</td>';
                    echo '<td >&nbsp;</td>';
                    echo '<td class="total">*** REPORT TOTALS ***</td>';
                     echo '<td align="right">'.number_format($total_comm,2).'</td>'; 
                    echo '<td align="right"> '.number_format($total_invest,2).' </td>';
                    echo '<td align="right"> '.number_format($total_concessions,2).' </td>';
                     echo '<td align="right">'.number_format($total_profit,2).'</td>';
                echo '</tr> </tfoot>';
            }
            echo '</table>';

        elseif($report_for == "annual_broker_report"): 

            $rows = $instance_trans->select_annual_broker_report($report_year,$is_trail,$broker,$company,$annul_broker_date_type);
            

             if($broker > 0){
                $branch_instance = new broker_master();

                $name  = $branch_instance->select_broker_by_id($broker);
               
                $companyhead.=", ".ucfirst($name['last_name']).' '.ucfirst($name['first_name']);
          
            }
            else {
                $companyhead.=', All Brokers';
            } 
                //  print_r($rows);
            $date_heading = ($annul_broker_date_type == 1) ? 'By Trade Date' : ' By Settlement Date';
            $subheading = 'ANNUAL BROKER REPORT ';
            $subheading2 = 'For '.$report_year.' - '.$date_heading;
            echo '<table border="0" width="100%">
                    <tr>
                         <td width="20%" align="left">'.date("m/d/Y").'</td>
                         <td width="60%" align="center">'. $img.'<br/>
                          
                           <h5> '.$subheading.' </h5>  
                           <h6>'.$companyhead.'</h6>
                          <h6>'.$subheading2.'</h6>
                           </td>
                         <td width="20%" align="right">Page 1</td>
                    </tr>        
            </table>  <br />';
             echo '<table border="0" cellpadding="1" width="100%">
                <thead class="modal-heading-row">
                <tr > 
                    <td align="right">MONTH </td>
                    <td align="right">NO. OF TRADES </td>
                    <td align="right">GROSS CONCESSIONS </td>
                    <td align="right">NET COMMISSION </td>
                   
                </tr>
                </thead>';
            echo '<tbody class="modal-tbody-rows">';
            $total_trades = $net_commission = $total_comm = 0;
            $notFoundRow = ['no_of_trades'=>0,'net_commission'=>0,'commission_received'=>0];
            for($i= 1 ;$i <=12 ; $i++) {
                $get_month_transaction = isset($rows[$i]) ? $rows[$i] : $notFoundRow; 
                $dateObj   = DateTime::createFromFormat('!m', $i);
                $total_comm+= $get_month_transaction['commission_received'];
                $net_commission+= $get_month_transaction['net_commission'];
                $total_trades+= $get_month_transaction['no_of_trades'];
                echo '<tr>';
                echo '<td align="right">'.$dateObj->format('F').' </td>';
                echo '<td align="right">'.$get_month_transaction['no_of_trades'].'</td> ';
                echo '<td align="right">'.number_format($get_month_transaction['commission_received'],2).'</td> ';
                echo '<td align="right">'.number_format($get_month_transaction['net_commission'],2).'</td> ';
              
                echo '</tr>';
            }
            echo '</tbody>';
            echo '<tfoot>';
            echo '<tr class="t-footer-items">';
            echo '<td align="right" class="total">*** REPORT TOTALS ***</td>';
            echo '<td align="right">'.$total_trades.'</td> ';
             echo '<td align="right">'.number_format($total_comm,2).'</td> ';
            echo '<td align="right">'.number_format($net_commission,2).'</td> ';
           
            echo '</tr>';
            echo '</tfoot>';
            echo '</table>';


        elseif($report_for == "monthly_broker_production"):
            $earning_by = 2;
            $earning_filter = compact('earning_by','beginning_date','ending_date');
            $rows = $instance_trans->select_monthly_broker_production_report($company,$earning_filter);
         //  echo '<pre>'; print_r($rows); echo '</pre>';
            $subheading = 'BROKER PRODUCTION REPORT ';
            $subheading2 = 'Trade Dates: '.$beginning_date." - ".$ending_date;
            echo '<table border="0" width="100%">
                    <tr>
                         <td width="20%" align="left">'.date("m/d/Y").'</td>
                         <td width="60%" align="center">'. $img.'<br/>
                           <h5> '.$subheading.' </h5>  
                            <h6>'.$companyhead.'</h6>
                          <h6>'.$subheading2.'</h6> </td>
                         <td width="20%" align="right">Page 1</td>
                    </tr>        
            </table>  <br />';
            echo '<table border="0" cellpadding="1" width="100%">
                <thead class="modal-heading-row">
                 <tr style="vertical-align: bottom;">
                    <td align="right">Product Category</td>
                    <td align="right">Investment Amount</td>
                    <td align="right">Commission Received </td>
                    <td align="right">Net Commission </td>
                </tr>
                </thead>';
            if(!empty($rows)) {

                $main_net_commission = $main_invest_total = $main_commision_received = 0;
                echo '<tbody class="modal-tbody-rows">';
                foreach($rows as $trans_items) {
                   $net_commission = $comm_rev = $total_inv = 0;
                    echo '<tr>
                        <td  align="left"> <span class="broker_name" style="font-weight:bold;"> '.$trans_items['broker_full_name'].' &nbsp;&nbsp;&nbsp; 
                            <span class="broker_id">#'.$trans_items['internal_id'].'</span>
                            </span>
                        </td>
                        <td colspan="3"> </td>
                    </tr>';
                    foreach($trans_items['transactions'] as $sub_items) {
                        $total_inv+= $sub_items['total_investment'];
                        $comm_rev+= $sub_items['total_commission_received'];
                        $net_commission+= $sub_items['net_commission'];


                        $main_invest_total+= $sub_items['total_investment'];
                        $main_commision_received+= $sub_items['total_commission_received'];
                        $main_net_commission+= $sub_items['net_commission'];

                        echo '<tr class="sub-items">
                        <td align="right">'.$sub_items['product_cat_name'].'</td>
                        <td align="right">'.number_format($sub_items['total_investment'],2).' </td>
                        <td align="right"> '.number_format($sub_items['total_commission_received'],2).'</td>
                        <td align="right"> '.number_format($sub_items['net_commission'],2).'</td>
                        </tr>';
                    }
                    echo '<tr class="t-footer-items" > 
                            <td align="right" class="total" style="font-size:10px"> *** BROKER SUBTOTAL ***</td>
                            <td align="right" style="font-size:10px"><span> '.number_format($total_inv,2).'</span></td>
                            <td align="right" style="font-size:10px"><span>'.number_format($comm_rev,2).'</span></td>
                            <td align="right" style="font-size:10px"><span>'.number_format($net_commission,2).'</span></td>
                        </tr>';
                }
                echo '</tbody>';
                echo '<tr class="t-footer-items"> 
                            <td align="right" class="total"> *** REPORT TOTALS ***</td>
                            <td align="right"><span> '.number_format($main_invest_total,2).'</span></td>
                            <td align="right"><span>'.number_format($main_commision_received,2).'</span></td>
                            <td align="right"><span>'.number_format($main_net_commission,2).'</span></td>
                        </tr>';

            }
            echo '</table>'; 
        elseif($report_for == "monthly_branch_office"):

            $subheading = 'BRANCH OFFICE PRODUCTION REPORT';
            $subheading2 = 'Trade Dates: '.$beginning_date." - ".$ending_date;
           // $subheading2 = 'Ending Date: '.date('F d, Y',strtotime($ending_date));
            $rows = $instance_trans->select_monthly_branch_office_report($company,$branch,$ending_date,$beginning_date);
            //echo '<pre>'; print_r($rows); echo '</pre>';
            echo '<table border="0" width="100%">
                    <tr>
                         <td width="20%" align="left">'.date("m/d/Y").'</td>
                         <td width="60%" align="center">'. $img.'<br/>
                           <h5> '.$subheading.' </h5>  
                           <h6>'.$companyhead.'</h6>
                          <h6>'.$subheading2.'</h6> </td>
                         <td width="20%" align="right">Page 1</td>
                    </tr>        
            </table>  <br />';
            echo '<table border="0" cellpadding="1" width="100%">
                <thead class="modal-heading-row">
                <tr style="vertical-align: bottom;">
                    <td align="right">Product Category</td>
                    <td align="right">Investment Amount</td>
                    <td align="right">Commission Received </td>
                    <td align="right">Net Commission </td>
                </tr>
                </thead>';
               /* echo '<pre>';
                print_r($rows);
                echo '</pre>';*/
            if(!empty($rows)) {
                $main_net_commission = $main_total_concessions = $main_invest_total = 0;
                echo '<tbody class="modal-tbody-rows">';

                foreach($rows as $trans_items) {
                  $branch_name = $trans_items['branch_name'] ?? 'Misc (Branch Name not available)';
                    echo '<tr>
                        <td class="td-branch"   colspan="4" align="center"> <span class="branch_name"> '.$branch_name.' 
                            </span>
                        </td>
                    </tr>';
                    foreach($trans_items['brokers'] as $broker_id => $sub_items) {
                         $net_commission = $comm_rev = $total_inv = 0;
                       echo '<tr>
                        <td  align="left"> <span class="broker_name" style="font-weight:bold;"> '.$sub_items['broker_full_name'].' &nbsp;&nbsp;&nbsp; 
                            <span class="broker_id">#'.$sub_items['internal_id'].'</span>
                            </span>
                        </td>
                        <td colspan="3"> </td>
                    </tr>';

                        
                        foreach($sub_items['transactions'] as $sub_items) {
                            $total_inv+= $sub_items['total_investment'];
                            $comm_rev+= $sub_items['total_commission_received'];
                            $net_commission+=$sub_items['net_commission'];

                            $main_invest_total+= $sub_items['total_investment'];
                            $main_total_concessions+= $sub_items['total_commission_received'];
                            $main_net_commission+= $sub_items['net_commission'];
                            echo '<tr class="sub-items">
                            <td align="right">'.$sub_items['product_cat_name'].'</td>
                            <td align="right">'.number_format($sub_items['total_investment'],2).' </td>
                            <td align="right"> '.number_format($sub_items['total_commission_received'],2).'</td>
                            <td align="right"> '.number_format($sub_items['net_commission'],2).'</td>
                            </tr>';
                        }

                        echo '<tr class="t-footer-items"> 
                                <td align="right" class="total" style="font-size:10px;"> *** BROKER TOTAL ***</td>
                                <td align="right" style="font-size:10px;"><span> '.number_format($total_inv,2).'</span></td>
                                <td align="right" style="font-size:10px;"><span>'.number_format($comm_rev,2).'</span></td>
                                <td align="right" style="font-size:10px;"><span>'.number_format($net_commission,2).'</span></td>
                            </tr>';
                    }


                }
                echo '</tbody>';
                echo '<tr class="t-footer-items"> 
                                <td align="right" class="total"> ***  Report Totals ***</td>
                                <td align="right"><span> '.number_format($main_invest_total,2).'</span></td>
                                <td align="right"><span>'.number_format($main_total_concessions,2).'</span></td>
                                <td align="right"><span>'.number_format($main_net_commission,2).'</span></td>
                        </tr>';
            }

            echo '</table>';

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
                        $companyhead .= '<br> '. implode(', ', $cat_names); 
                    }
                }
                else $companyhead.= '<br> All Categories';
            endif;


            $get_trans_data = $instance_trans->select_transcation_history_report_v2($report_for,$sort_by,$branch,$broker,'',$client,$product,$beginning_date,$ending_date,$batch,$date_by,$filter_by,$is_trail,$prod_cat);

          //  print_r($get_trans_data);
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
            
             
            ?>
            <table border="0" width="100%">
                    <tr>
                         <td width="20%" align="left"><?php echo date("m/d/Y"); ?> </td>
                         <td width="60%" align="center"><?php echo $img; ?><br/>
                          <strong><h5><?php echo $subheading; ?></h6><h6><?php echo $companyhead; ?><br/><?php echo $subheading2; ?><br/><?php echo $subheading1;?></h6></strong> </td>
                         <td width="20%" align="right" 2>Page 1</td>
                    </tr>    
                    
            </table>
            <br />
            <table border="0" cellpadding="1" width="100%">
                <thead>
                    <tr style="background-color: #f1f1f1;">
                   
                        <?php if($report_for == "Production by Sponsor Report") : ?>
                        <td style="text-align:left;font-weight:bold;"><h5>SPONSOR </h5></td>
                        <?php else : ?>
                        <td style="text-align:left;font-weight:bold;"><h5>SPONSOR </h5></td>
                        <?php endif; ?>
                        <td style="text-align:right;font-weight:bold;"><h5>AMOUNT INVESTED</h5></td>
                        <td style="text-align:right;font-weight:bold;"><h5>COMMISSION RECEIVED</h5></td>
                        <td style="text-align:right;font-weight:bold;"><h5>COMMISSION PAID</h5></td>
                        <?php if($report_for == "Product Category Summary Report") : ?>
                        <td style="text-align:right;font-weight:bold;"><h5> NO. OF TRANSACTIONS </h5></td>
                        <td style="text-align:right;font-weight:bold;"><h5>%TOTAL</h5></td>
                        <?php endif; ?> 
                    </tr>
                </thead>
                <tbody>
                <?php 
                if(!empty($get_trans_data))
                {                   
                    //echo '<pre>';print_r($get_trans_data);
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
                     
                    foreach($get_trans_data as $key => $category_data)
                    {
                        
                        ?>
                         <?php if($report_for == "Production by Product Category") : ?>
                            
                        <tr>
                            <td colspan="3" style="font-size:12px;font-weight:bold;text-align:left;">Product Category: <?php echo $key; ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php
                        $total_comm_received_cat=0;
                        $total_comm_paid_cat=0;
                        $total_inv_cat=0;
                        $total_no_of_trans_cat=0;
                        $cat_percentage=0;
                        foreach($category_data as $trans_key=>$trans_data)
                        {
                        
                        $total_no_of_trans_cat+=1;
                        $total_comm_received_cat+=$trans_data['commission_received'];
                        $total_comm_paid_cat+=$trans_data['charge_amount'];
                        $total_inv_cat+=$trans_data['invest_amount'];
                        // $date = ($date_by == "1") ? $trans_data['trade_date'] : $trans_data['commission_received_date'];
                        ?>
                        <?php if($report_for != "Product Category Summary Report") : ?> 
                        <tr>
                               
                                <?php if($report_for == "Production by Sponsor Report") : ?>
                                    <td style="font-size:10px;font-weight:normal;text-align:left;"><?php echo $trans_data['sponsor_name']; ?></td>
                                <?php else : ?>
                                    <td style="font-size:10px;font-weight:normal;text-align:left;"><?php echo $trans_data['product_name']; ?></td>
                                <?php endif; ?>    
                              
                               
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['invest_amount'],2); ?></td>
                               
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['commission_received'],2); ?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['charge_amount'],2); ?> </td>
                               <?php if($report_for == "Product Category Summary Report") : ?>
                               <td style="font-size:10px;font-weight:normal;text-align:left;"><?php echo $trans_data['client_name']; ?></td>
                                 <td style="font-size:10px;font-weight:normal;text-align:left;"><?php echo $trans_data['broker_last_name']; ?></td>
                                <?php endif; ?>
                                
                        </tr>
                        <?php endif; ?>
                    <?php } ?>
                       
                             <?php if($report_for == "Product Category Summary Report") : ?>
                                 <tr >
                                    <td style="font-size:10px;font-weight:normal;text-align:left;"><?php echo $key; ?></td>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($total_inv_cat,2);?></td>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($total_comm_received_cat,2);?></td>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($total_comm_paid_cat,2);?></td>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($total_no_of_trans_cat,0);?></td>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($total_no_of_trans_cat*100/$total_no_of_trans,2).'%';?></td>
                                    </tr>

                            <?php elseif($report_for == "Production by Product Category") : ?>
                                     <tr style="background-color: #f1f1f1;">
                                     <td style="font-size:10px;font-weight:bold;text-align:right;"><b>** PRODUCT CATEGORY SUBTOTAL **</b></td>
                             
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_inv_cat,2);?></b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_comm_received_cat,2);?></b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_comm_paid_cat,2);?></b></td>
                            </tr>
                                <?php endif; ?>
                         
                        



                    <?php
                    }
                    ?>                       
                        <tr style="background-color: #f1f1f1;">
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b>*** REPORT TOTALS ***</b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_inv,2);?></b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_comm_received,2);?></b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_comm_paid,2);?></b></td>
                               <?php if($report_for == "Product Category Summary Report") : ?>
                        <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo number_format($total_no_of_trans,0);?></b></td>
                        <td style="font-size:10px;font-weight:bold;text-align:right;"><b></b></td>
                        <?php endif; ?>
                         
                        </tr>
              
                

            <?php } 
            else
            {?>
                <tr>
                   <td style="font-size:10px;font-weight:normal;text-align:center;" colspan="9">No Records Found.</td>
                </tr>
            <?php } ?>           
            </tbody>
    </table>
<?php  

   /* else if($report_for == 'broker_ranking'):

        echo 'I am here ';*/
    else:

         $get_trans_data = $instance_trans->select_transcation_history_by_broker_v2($broker,$beginning_date,$ending_date,$date_by,$filter_by,$is_trail);
           
            $batch_desc = isset($get_trans_data[0]['batch_desc'])?$instance->re_db_input($get_trans_data[0]['batch_desc']):'';
            $total_amount_invested = 0;
            $total_commission_received = 0;
            $total_charges = 0;
        ?>
         <table border="0" width="100%">
                    <tr>
                         <td width="20%" align="left"><?php echo date("m/d/Y"); ?> </td>
                         <td width="60%" align="center"><?php echo $img; ?><br/>
                          <strong><?php echo $subheading; ?> </strong> </td>
                         <td width="20%" align="right" 3>Page 1</td>
                    </tr>    
                    
            </table>
            <br />
        <table border="0" cellpadding="1" width="100%">
                <thead>
                     <tr style="background-color: #f1f1f1;vertical-align: bottom;">
                        <td style="text-align:center;width:10%;font-weight:bold;"><h5 style="display: inline-block;">TRADE #</h5></td>
                        <td style="text-align:left;font-weight:bold;"><h5>TRADE DATE / SETTLE DATE</h5></td>
                        <td style="text-align:left;font-weight:bold;"><h5>CLIENT / FILE #</h5></td>
                         <td style="font-weight:bold;"><h5>AMOUNT INVESTED</h5></td>
                        <td style="text-align:center;font-weight:bold;"><h5>COMM RECD </h5></td>
                        <td style="text-align:center;font-weight:bold;"><h5>COMM PAID </h5></td>
                        <td style="text-align:center;font-weight:bold;"><h5>DATE RECD</h5></td>
                        <td style="text-align:center;font-weight:bold;"><h5>DATE PAID</h5></td>
                      
                    </tr>
                </thead>
                <tbody>
                <?php 
                if($get_trans_data != array())
                {
                     $total_comm_received=0;
                        $total_comm_paid=0;
                    foreach($get_trans_data as $trans_main_key=>$trans_main_data)
                    {
                       //print_r($trans_main_data[0]);die;
                       ?>
                       <tr>
                           <td style="font-size:10px;font-weight:bold;text-align:left;" colspan="6">
                            BROKER: <?php echo $trans_main_data['broker'] ?>
                                
                        </td>
                        </tr>
                       <?php
                       foreach($trans_main_data['products'] as $trans_key=>$trans_data_product){


                        $sub_total_records=0;
                        $sub_total_amount_invested = 0;
                        $sub_total_commission_received = 0;
                        $sub_total_charges = 0;?>
                        <tr>
                            
                           <td style="font-size:10px;font-weight:bold;text-align:left;" colspan="7">
                             PRODUCT: <?php echo $trans_data_product[0]['product_name']; ?>
                                
                        </td>
                        </tr>
                        
                        <?php

                       
                        foreach($trans_data_product as $trans_key=>$trans_data)
                        {
                            $total_records = $total_records+1;
                            $sub_total_records = $sub_total_records+1;
                            $total_amount_invested = ($total_amount_invested+$trans_data['invest_amount']);
                            $total_commission_received = ($total_commission_received+$trans_data['commission_received']);
                            $total_charges = ($total_charges+$trans_data['charge_amount']);
                            
                            $sub_total_amount_invested = ($sub_total_amount_invested+$trans_data['charge_amount']);
                            $sub_total_commission_received = ($sub_total_commission_received+$trans_data['commission_received']);
                            $sub_total_charges = ($sub_total_charges+$trans_data['charge_amount']);
                             $total_comm_received+=$trans_data['commission_received'];
                            $total_comm_paid+=$trans_data['charge_amount'];

                            ?>
                            <tr>
                                <td STYLE="font-size:10px;font-weight:normal;text-align:center;"><?php echo $trans_data['id'] ?></td>
                                <td STYLE="font-size:10px;font-weight:normal;text-align:left;"><!-- <?php echo  date("m/d/Y",strtotime($trans_data['trade_date'])) ."<br/>" . date("m/d/Y",strtotime($trans_data['settlement_date'])); ?> -->
                                    
                                </td>
                                <td STYLE="font-size:10px;font-weight:normal;text-align:left;"> <?php  echo $trans_data['client_name']. "<br/>" .$trans_data['client_number'] ?></td>
                                <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['invest_amount'],2); ?></td>
                                <td STYLE="font-size:10px;font-weight:normal;text-align:right;"> <?php echo '$'.number_format($trans_data['commission_received'],2); ?></td>
                                <td STYLE="font-size:10px;font-weight:normal;text-align:right;"> <?php echo '$'.number_format($trans_data['charge_amount'],2); ?></td>
                                <td STYLE="font-size:10px;font-weight:normal;text-align:center;"> <?php  echo date("m/d/Y",strtotime($trans_data['commission_received_date'])); ?></td>
                                <td STYLE="font-size:10px;font-weight:normal;text-align:center;"> <?php  echo  date("m/d/Y",strtotime($trans_data['ch_date'])); ?></td>
                                 
                            </tr>
                        <?php } 
                        
                        ?>

                        <tr style="background-color: #f1f1f1;">
                            <td></td>
                            <td></td>
                            <td colspan="2" style="font-size:10px;font-weight:bold;text-align:right;"><b>** PRODUCT SUBTOTAL **</b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($sub_total_commission_received,2);?></b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($sub_total_amount_invested,2);?></b></td>
                            <td></td>
                            <td></td>
                         
                        </tr>
                       
                
                <?php }
                 } ?>

                 <tr style="background-color: #f1f1f1;">
                            <td></td>
                            <td></td>
                           
                            <td colspan="2" style="font-size:10px;font-weight:bold;text-align:right;"><b>*** REPORT TOTALS ***</b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_comm_received,2);?></b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_comm_paid,2);?></b></td>
                           
                            <td></td>
                            <td></td>
                         
                        </tr>
               
             
            <?php } 
            else
            {?>
                <tr>
                   <td style="font-size:10px;font-weight:normal;text-align:center;" colspan="8">No Records Found.</td>
                </tr>
            <?php } ?>           
            </tbody>
    </table>    
        <?php
       endif;        

}
?>
       