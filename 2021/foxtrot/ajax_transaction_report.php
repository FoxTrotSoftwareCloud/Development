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
    $sponsor = isset($filter_array['sponsor'])?$instance->re_db_input($filter_array['sponsor']):'';
    $rep_no = isset($filter_array['rep_no'])?$instance->re_db_input($filter_array['rep_no']):'';
    $date_by= isset($filter_array['date_by'])?$instance->re_db_input($filter_array['date_by']):1;
    $filter_by= isset($filter_array['filter_by'])?$instance->re_db_input($filter_array['filter_by']):1;
    $is_trail= isset($filter_array['is_trail'])?$instance->re_db_input($filter_array['is_trail']):0;
    $product_cate= isset($filter_array['product_cate'])?$instance->re_db_input($filter_array['product_cate']):'';
    $subheading="TRANSACTION BY ".strtoupper($report_for)." REPORT ";
    
   
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
        $subheading.=", DATES: ".$beginning_date." - ".$ending_date;

    }

    
   
           if($report_for != "broker"):
            $get_trans_data = $instance_trans->select_transcation_history_report($branch,$broker,'',$client,$product,$beginning_date,$ending_date,$batch,$date_by,$filter_by,$is_trail);
           
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
                         <td width="20%" align="right">PAGE 1</td>
                    </tr>    
                    
            </table>
            <br />
            <table border="0" cellpadding="1" width="100%">
                <thead>
                    <tr style="background-color: #f1f1f1;">
                        <td style="text-align:left;font-weight:bold;"><h5>DATE</h5></td>
                       
                        <?php if($report_for == "client") : ?>
                            <td style="text-align:left;font-weight:bold;"><h5>CLIENT </h5></td>
                        <td style="text-align:left;font-weight:bold;"><h5>BROKER #</h5></td>
                        <?php else : ?>
                        <td style="text-align:left;font-weight:bold;"><h5>PRODUCT </h5></td>
                        <td style="text-align:left;font-weight:bold;"><h5>TRADE #</h5></td>
                         <?php endif; ?>
                        <td style="text-align:right;font-weight:bold;"><h5>AMOUNT INVESTED</h5></td>
                        <td style="text-align:right;font-weight:bold;"><h5>COMMISSION RECEIVED</h5></td>
                        <td style="text-align:right;font-weight:bold;"><h5>COMMISSION PAID</h5></td>
                    </tr>
                </thead>
                <tbody>
                <?php 
                if(!empty($get_trans_data))
                {
                    

                        $total_comm_received=0;
                        $total_comm_paid=0;
                        foreach($get_trans_data as $trans_key=>$trans_data)
                        {
                            $total_comm_received+=$trans_data['commission_received'];
                            $total_comm_paid+=$trans_data['charge_amount'];
                            $date = ($date_by == "1") ? $trans_data['trade_date'] : $trans_data['commission_received_date'];
                            ?>
                            <tr>
                                   <td style="font-size:10px;font-weight:normal;text-align:left;"><?php  echo date('m/d/Y',strtotime($date));  ?></td>
                                   <?php if($report_for=='broker' ) : ?>
                                   <td style="font-size:10px;font-weight:normal;text-align:left;"><?php echo $trans_data['broker_last_name'].', '.$trans_data['broker_name']; ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:left;"><?php echo $trans_data['id']; ?></td>
                                   
                                    <?php elseif($report_for == "client") : ?>
                                   <td style="font-size:10px;font-weight:normal;text-align:left;"><?php echo $trans_data['client_name']; ?></td>
                                     <td style="font-size:10px;font-weight:normal;text-align:left;"><?php echo $trans_data['broker_last_name'].', '.$trans_data['broker_name']; ?></td>
                                    <?php else : ?>
                                        <td style="font-size:10px;font-weight:normal;text-align:left;"><?php echo $trans_data['product_name']; ?></td>
                                        <td style="font-size:10px;font-weight:normal;text-align:left;"><?php echo $trans_data['id']; ?></td>
                                    <?php endif; ?>    
                                  
                                   
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['invest_amount'],2); ?></td>
                                   
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['commission_received'],2); ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['charge_amount'],2); ?> </td>
                            </tr>
                        <?php } 
                        
                        ?>
                       
                        <tr style="background-color: #f1f1f1;">
                            <td></td>
                            <td></td>
                            <td colspan="2" style="font-size:10px;font-weight:bold;text-align:right;"><b>*** REPORT TOTALS ***</b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_comm_received,2);?></b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_comm_paid,2);?></b></td>
                         
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
      else:
         $get_trans_data = $instance_trans->select_transcation_history_by_broker($broker,$beginning_date,$ending_date,$date_by,$filter_by,$is_trail);
           
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
                         <td width="20%" align="right">PAGE 1</td>
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
                                <td STYLE="font-size:10px;font-weight:normal;text-align:left;"><?php echo  date("m/d/Y",strtotime($trans_data['trade_date'])) ."<br/>" . date("m/d/Y",strtotime($trans_data['settlement_date'])); ?></td>
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
       