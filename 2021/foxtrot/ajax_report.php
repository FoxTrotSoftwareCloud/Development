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
    $beginning_date = isset($filter_array['beginning_date'])?$filter_array['beginning_date']:'';
    $ending_date = isset($filter_array['ending_date'])?$filter_array['ending_date']:'';
    $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
    $report_for = isset($filter_array['report_for'])?trim($filter_array['report_for']):'';
    $total_records=0;
    $total_records_sub=0;
    
    if($report_for==2)
    {
    
        $return_batches = $instance->get_all_category_batch_data($product_category,$company,$batch,$beginning_date,$ending_date,$sort_by);
        if($product_category>0)
        {
            $product_category_name = $instance->get_product_type($product_category);
        }
        else
        {
            $product_category_name = 'All Categories';
        }
    
        $total_received_amount = 0;
        $total_posted_amount = 0;

        ?>
        <table border="0" width="100%">
                <tr>
                    <?php 
                    if(isset($system_logo) && $system_logo != '')
                    {?>
                        <td width="20%" align="left"><?php echo $img;?></td>
                    <?php } ?>
                    <td width="60%" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo $product_category_name.' Batch Report';?></td>
                    <?php
                    if(isset($system_company_name) && $system_company_name != '')
                    {?>
                        <td width="20%" style="font-size:10px;font-weight:bold;text-align:right;"><?php echo $system_company_name;?></td>
                    <?php
                    }?>
                </tr>
                <tr>
                    <td width="100%" colspan="3" style="font-size:12px;font-weight:bold;text-align:center;"><?php echo $beginning_date.'-'.$ending_date;?></td>
                </tr>
        </table>
        <br />
        <table border="0" cellpadding="1" width="100%">
            <thead>
                <tr style="background-color: #f1f1f1;">
                    <td style="text-align:center;font-weight:bold;"><h5>Batch#</h5></td>
                    <td style="text-align:center;font-weight:bold;"><h5>Date Received</h5></td>
                    <td style="text-align:center;font-weight:bold;"><h5>Amount Received</h5></td>
                    <td style="text-align:center;font-weight:bold;"><h5>Posted to Date</h5></td>
                    <td style="text-align:center;font-weight:bold;"><h5>Statement Description</h5></td>
                </tr>
            </thead>
            <tbody>
            <?php 
            if($return_batches != array())
            {
                foreach($return_batches as $main_key=>$main_val)
                {?>
                    <tr>
                        <td colspan="5" style="font-size:10px;font-weight:bold;text-align:left;">Investment Category: <?php echo $main_key; ?></td>
                    </tr>
              <?php $posted_commission_amount = 0;
                    $amount_received = 0;
                    
                    $cat_total_received_amount = 0;
                    $cat_total_posted_amount = 0;
                    $total_records_sub = 0;
                    
                    foreach($main_val as $sub_key=>$sub_val)
                    {
                        $total_records_sub = $total_records_sub+1;
                        $total_records=$total_records+1;
                        $get_commission_amount = $instance->get_commission_total($sub_val['id']);
                        $amount_received = $sub_val['check_amount'];
                        
                        if(isset($get_commission_amount['posted_commission_amount']) && $get_commission_amount['posted_commission_amount']!='')
                        {
                            $posted_commission_amount = $get_commission_amount['posted_commission_amount'];
                            
                        }else
                        { $posted_commission_amount = 0;}
                        $cat_total_posted_amount = $cat_total_posted_amount+$posted_commission_amount;
                        $cat_total_received_amount = $cat_total_received_amount+$amount_received;
                        ?>
                        <tr>
                               <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $sub_val['id']; ?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo date('m/d/Y',strtotime($sub_val['batch_date'])); ?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($amount_received,2); ?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($posted_commission_amount,2); ?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $sub_val['batch_desc']; ?></td>
                        </tr>
                    <?php }
                    
                    $total_posted_amount = $total_posted_amount+$cat_total_posted_amount;
                    $total_received_amount = $total_received_amount+$cat_total_received_amount; ?>
                    <tr style="background-color: #f1f1f1;">
                           <td style="font-size:10px;font-weight:bold;text-align:center;">Total Category Records: <?php echo $total_records_sub;?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">** Category Total **</td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($cat_total_received_amount,2); ?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($cat_total_posted_amount,2); ?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                    </tr>
            <?php 
                }?>
                <tr style="background-color: #f1f1f1;">
                    <td style="font-size:10px;font-weight:bold;text-align:center;">Total Records: <?php echo $total_records;?></td>
                    <td style="font-size:10px;font-weight:bold;text-align:right;">*** Total ***</td>
                    <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_received_amount,2); ?></td>
                    <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_posted_amount,2); ?></td>
                    <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                </tr> 
                
            <?php 
            }
            else
            {?>
                
            <tr>
                <td style="font-size:10px;font-weight:normal;text-align:center;" colspan="8">No record found.</td>
            </tr>
            
            <?php } ?>   
            
             
           </tbody>
        </table>
<?php }
      else if($report_for==1)
      {
            $get_trans_data = $instance_trans->select_data_report($product_category,$company,$batch,$beginning_date,$ending_date,$sort_by,1);
            $batch_desc = isset($get_trans_data[0]['batch_desc'])?$instance->re_db_input($get_trans_data[0]['batch_desc']):'';
            $total_amount_invested = 0;
            $total_commission_received = 0;
            $total_charges = 0;
            
             
            ?>
            <table border="0" width="100%">
                    <tr>
                        <?php 
                        if(isset($system_logo) && $system_logo != '')
                        {?>
                            <td width="20%" align="left"><?php echo $img;?></td>
                        <?php } ?>
                         <?php if($batch > 0){?>
                            <td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">TRANSACTION BY BATCH REPORT : <?php echo strtoupper($batch_desc);?></td>
                        <?php } else { ?>
                            <td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">TRANSACTION BY BATCH REPORT : ALL BATCHES</td>
                        <?php } ?>
                        <?php
                        if(isset($system_company_name) && $system_company_name != '')
                        {?>
                            <td width="20%" style="font-size:10px;font-weight:bold;text-align:right;"><?php echo $system_company_name;?></td>
                        <?php
                        }?>
                    </tr>
                    <tr>
                        <?php if($batch > 0){?>
                            <th colspan="9"><h4 style="text-align: center;">Batch #<?php echo $batch;?></h4></th>
                        <?php } ?>
                    </tr>
            </table>
            <br />
            <table border="0" cellpadding="1" width="100%">
                <thead>
                    <tr style="background-color: #f1f1f1;">
                        <td style="text-align:center;font-weight:bold;"><h5>TRADE#</h5></td>
                         <td style="text-align:center;font-weight:bold;"><h5>BATCH#</h5></td>
                        <td style="text-align:center;font-weight:bold;"><h5>CLIENT ACCOUNT #</h5></td>
                        <td style="text-align:center;font-weight:bold;"><h5>CLIENT</h5></td>
                        <td style="text-align:center;font-weight:bold;"><h5>TRADE DATE</h5></td>
                        <td style="text-align:center;font-weight:bold;"><h5>DATE RECEIVED</h5></td>
                        <td style="text-align:center;font-weight:bold;"><h5>AMOUNT INVESTED</h5></td>
                        <td style="text-align:center;font-weight:bold;"><h5>CHARGE</h5></td>
                        <td style="text-align:center;font-weight:bold;"><h5>COMMISSION RECEIVED</h5></td>
                    </tr>
                </thead>
                <tbody>
                <?php 
                if($get_trans_data != array())
                {
                    foreach($get_trans_data as $trans_main_key=>$trans_main_data)
                    {

                        $sub_total_records=0;
                        $sub_total_amount_invested = 0;
                        $sub_total_commission_received = 0;
                        $sub_total_charges = 0;
                        //BROKER: Adams, John (#3)
                        $broker_name = $trans_main_data[0]['broker_last_name'].', '.$trans_main_data[0]['broker_name'].' (#'.$trans_main_key.')';
                        ?>
                        <tr>
                           <td style="font-size:10px;font-weight:bold;text-align:left;" colspan="9"><?php echo "BROKER: ".$broker_name ?></td>
                        </tr>
                        <?php

                       
                        foreach($trans_main_data as $trans_key=>$trans_data)
                        {
                            $total_records = $total_records+1;
                            $sub_total_records = $sub_total_records+1;
                            $total_amount_invested = ($total_amount_invested+$trans_data['invest_amount']);
                            $total_commission_received = ($total_commission_received+$trans_data['commission_received']);
                            $total_charges = ($total_charges+$trans_data['charge_amount']);
                            
                            $sub_total_amount_invested = ($sub_total_amount_invested+$trans_data['invest_amount']);
                            $sub_total_commission_received = ($sub_total_commission_received+$trans_data['commission_received']);
                            $sub_total_charges = ($sub_total_charges+$trans_data['charge_amount']);
                            ?>
                            <tr>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $trans_data['id']; ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $trans_data['batch']; ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $trans_data['client_number']; ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $trans_data['client_name']; ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php if($trans_data['trade_date'] != '0000-00-00'){ echo date('m/d/Y',strtotime($trans_data['trade_date'])); } ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php if($trans_data['commission_received_date'] != '0000-00-00'){ echo date('m/d/Y',strtotime($trans_data['commission_received_date'])); } ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['invest_amount'],2); ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['charge_amount'],2); ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['commission_received'],2); ?></td>
                            </tr>
                        <?php } 
                        
                        ?>
                       <tr style="background-color: #f1f1f1;">
                       <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="6"><?php echo $broker_name.' TOTAL: ';?></td>
                       <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($sub_total_amount_invested,2);?></td>
                       <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($sub_total_charges,2);?></td>
                       <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($sub_total_commission_received,2);?></td>
                </tr>
                <?php } ?>
                <tr style="background-color: #f1f1f1;">
                   <td style="font-size:10px;font-weight:bold;text-align:center;">Total Records: <?php echo $total_records;?></td>
                   <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="5">*** REPORT TOTALS *** </td>
                   <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_amount_invested,2);?></td>
                   <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_charges,2);?></td>
                   <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_commission_received,2);?></td>
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
<?php  }
 else if($report_for==3)
      {
            $get_trans_data = $instance_trans->select_data_report($product_category,$company,$batch,$beginning_date,$ending_date,$sort_by,1,true);
            $batch_desc = isset($get_trans_data[0]['batch_desc'])?$instance->re_db_input($get_trans_data[0]['batch_desc']):'';
            $total_amount_invested = 0;
            $total_commission_received = 0;
            $total_charges = 0;
            $instance_multi_company = new manage_company();
            if($company==0){
               $company_name = "All Companies";
            }
            else{
                  $company_data= $instance_multi_company->edit($company);
                   $company_name = $company_data['company_name'];  
            }
            
           
            
             
            ?>
            <table border="0" width="100%">
                    <tr>
                        
                           <?php 
                        if(isset($system_logo) && $system_logo != '')
                        {?>
                            <td width="20%" align="left"><?php echo $img;?></td>
                        <?php } ?>
                        
                        
                         <td width="60%" style="text-align: center;">
                            <span><?php echo $company_name; ?></span><br/>
                           <span style="font-size:14px;font-weight:bold;text-align:center;"> ALL CATEGORIES COMMISSION HOLD REPORT </span>
                         </td>
                         <td width="60%">
                            <?php echo $system_company_name; ?>
                         </td>
                       
                       
                    </tr>
                   
            </table>
            <br />
            <table border="0" cellpadding="1" width="100%">
                <thead>
                     <tr style="background-color: #f1f1f1;vertical-align: bottom;">
                        <th style="text-align:left;width:20%;font-weight:bold;"><h5 style="    display: inline-block;">TRADE #</h5><h5 style="margin-left: 20px;display: inline-block;">DATE</h5></th>
                        <th style="text-align:left;font-weight:bold;"><h5>PRODUCT</h5></th>
                        <th style="text-align:left;font-weight:bold;"><h5>CLIENT</h5></th>
                        <th style="font-weight:bold;"><h5>AMOUNT<br>INVESTED</h5></th>
                        <th ><h5>COMM<br>EXPECTED<br>RECEIVED</h5></th>
                        <th style="text-align:left;font-weight:bold;"><h5>HOLD REASON<br>DATE RECEIVED</h5></th>
                      
                    </tr>
                </thead>
                <tbody>
                <?php 
                if($get_trans_data != array())
                {
                    foreach($get_trans_data as $trans_main_key=>$trans_main_data)
                    {



                        $sub_total_records=0;
                        $sub_total_amount_invested = 0;
                        $sub_total_commission_received = 0;
                        $sub_total_charges = 0;?>
                        <tr>
                           <td style="font-size:10px;font-weight:bold;text-align:left;" colspan="8">
                            <?php echo '#'.strtoupper($trans_main_key); ?> -  <?php echo $trans_main_data[0]['broker_last_name'],', ',$trans_main_data[0]['broker_name'] ?>
                                
                        </td>
                        </tr>
                        <?php

                       
                        foreach($trans_main_data as $trans_key=>$trans_data)
                        {
                            $total_records = $total_records+1;
                            $sub_total_records = $sub_total_records+1;
                            $total_amount_invested = ($total_amount_invested+$trans_data['invest_amount']);
                            $total_commission_received = ($total_commission_received+$trans_data['commission_received']);
                            $total_charges = ($total_charges+$trans_data['charge_amount']);
                            
                            $sub_total_amount_invested = ($sub_total_amount_invested+$trans_data['invest_amount']);
                            $sub_total_commission_received = ($sub_total_commission_received+$trans_data['commission_received']);
                            $sub_total_charges = ($sub_total_charges+$trans_data['charge_amount']);
                            ?>
                            <tr>
                                <td STYLE="font-size:10px;font-weight:normal;text-align:center;"><span style="margin-right:15px;"><?php echo $trans_data['id'] ?></span><span><?php echo $trans_data['trade_date'] ?></span></td>
                                <td STYLE="font-size:10px;font-weight:normal;text-align:left;"><?php  echo $trans_data['product_name'] ?></td>
                                <td STYLE="font-size:10px;font-weight:normal;text-align:left;"> <?php  echo $trans_data['client_name'] ?></td>
                                <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['invest_amount'],2); ?></td>
                                <td STYLE="font-size:10px;font-weight:normal;text-align:right;"> <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['commission_received'],2); ?></td></td>
                                 <td STYLE="font-size:10px;font-weight:normal;text-align:center;"><?php  echo $trans_data['hold_resoan'] ?><br><?php if($trans_data['commission_received_date'] != '0000-00-00'){ echo date('m/d/Y',strtotime($trans_data['commission_received_date'])); } ?></td>
                                 
                            </tr>
                        <?php } 
                        
                        ?>

                        <tr style="background-color: #f1f1f1;">
                            <td></td>
                            <td></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b>** Broker Total **</b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($sub_total_amount_invested,2);?></b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($sub_total_commission_received,2);?></b></td>
                            <td style="font-size:10px;font-weight:bold;text-align:right;"><b><?php echo '$'.number_format($total_commission_received,2);?></b></td>
                        </tr>
                       
                
                <?php } ?>
                <tr> 
                     <td colspan="5"></td>
                </tr>
                <tr style="background-color: #f1f1f1;">
                    <td></td>
                    <td></td>
                    <td style="font-size:10px;font-weight:bold;text-align:right;">*** Report Total ***</td>
                    <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_amount_invested,2);?></td>
                    <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_commission_received,2);?></td>
                    <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_commission_received,2);?></td>
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
<?php  }
else if($report_for==4)
      {
            

            $payable_type=isset($filter_array['payable_type'])?$filter_array['payable_type']:1;

            $cuttoff_date = isset($filter_array['cuttoff_date'])?$instance->re_db_input($filter_array['cuttoff_date']):'';
             $get_trans_data = $instance_trans->get_payable_report($product_category,$company,$batch,$cuttoff_date,$sort_by,$payable_type);
            $batch_desc = isset($get_trans_data[0]['batch_desc'])?$instance->re_db_input($get_trans_data[0]['batch_desc']):'';
            $total_amount_invested = 0;
            $total_commission_received = 0;
            $total_charges = 0;
            $total_payable_charges = 0;
            $payable_type_text= $payable_type == 1 ? "COMMISSIONS RECEVIED" : "ALL UNPAID COMMISSIONS";
            
             
            ?>
            <table border="0" width="100%">
                    <tr>
                        <?php 
                        if(isset($system_logo) && $system_logo != '')
                        {?>
                            <td width="20%" align="left"><?php echo $img;?></td>
                        <?php } ?>
                       
                            <td width="60%" style="font-size:14px;font-weight:bold;text-align:center;line-height: 1;">
                                PAYABLES REPORT <br/>
                                TRADE WITH <?php echo $payable_type_text; ?> <br/>
                                    Cutoff - <?php  echo $cuttoff_date; ?>
                            </td>
                       
                        <?php
                        if(isset($system_company_name) && $system_company_name != '')
                        {?>
                            <td width="20%" style="font-size:10px;font-weight:bold;text-align:right;"><?php echo $system_company_name;?></td>
                        <?php
                        }?>
                    </tr>
                    <tr>
                        <?php if($batch > 0){?>
                            <th colspan="8"><h4 style="text-align: center;">Batch #<?php echo $batch;?></h4></th>
                        <?php } ?>
                    </tr>
            </table>
            <br />
            <table border="0" cellpadding="1" width="100%">
                <thead>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:10px;text-align:center;font-weight:bold;"><h5 style="font-size:10px;">DATE</h5></td>
                        <td style="font-size:10px;width: 15%;text-align:center;font-weight:bold;"><h5 style="font-size:10px;">CLIENT</h5></td>
                        <td style="font-size:10px;width: 20%;text-align:center;font-weight:bold;"><h5 style="font-size:10px;">PRODUCT</h5></td>
                        <td style="font-size:10px;text-align:center;font-weight:bold;"><h5 style="font-size:10px;">INVESTMENT AMOUNT</h5></td>
                        <td style="font-size:10px;text-align:center;font-weight:bold;"><h5 style="font-size:10px;">COMMISSION RECEIVED</h5></td>
                        <td style="font-size:10px;text-align:center;font-weight:bold;"><h5 style="font-size:10px;">CHARGE</h5></td>
                        <td style="font-size:10px;text-align:center;font-weight:bold;"><h5 style="font-size:10px;">BROKER PAYOUT</h5></td>
                    </tr>
                </thead>
                <tbody>
                <?php 
                if($get_trans_data != array())
                {
                    foreach($get_trans_data as $trans_main_key=>$trans_main_data)
                    {
                        $broker_name=$trans_main_data[0]['broker_last_name'].', '.$trans_main_data[0]['broker_name'].' #'.$trans_main_key;
                        $sub_total_records=0;
                        $sub_total_amount_invested = 0;
                        $sub_total_commission_received = 0;
                        $sub_total_charges = 0;
                         $sub_payable_charges = 0;
                        ?>
                        <tr>
                           <td style="font-size:10px;font-weight:bold;text-align:left;" colspan="8">
                             <?php echo $broker_name ?> 
                        </tr>
                        <?php

                       
                        foreach($trans_main_data as $trans_key=>$trans_data)
                        {
                            $total_records = $total_records+1;
                            $sub_total_records = $sub_total_records+1;
                            $total_amount_invested = ($total_amount_invested+$trans_data['invest_amount']);
                            $total_commission_received = ($total_commission_received+$trans_data['commission_received']);
                            $total_charges = ($total_charges+$trans_data['charge_amount']);
                            //var_dump( empty((float)$trans_data["rates"][$trans_data['product_cate']]));
                            $payable_flat_rate = isset($trans_data["rates"]) && !empty($trans_data["rates"]) ? $trans_data["rates"] : 100;
                            $payable_amount = ($trans_data['commission_received'] * $payable_flat_rate) / 100;
                            $total_payable_charges=($total_payable_charges+$payable_amount);
                            $sub_payable_charges=$sub_payable_charges+$payable_amount;
                            
                            $sub_total_amount_invested = ($sub_total_amount_invested+$trans_data['invest_amount']);
                            $sub_total_commission_received = ($sub_total_commission_received+$trans_data['commission_received']);
                            $sub_total_charges = ($sub_total_charges+$trans_data['charge_amount']);
                            $trans_data['product_name'] = !empty($trans_data['transaction_id']) ? "*".$trans_data['product_name']: $trans_data['product_name'];
                            //$product_name = !empty($trans_data['product_name']) ? "*".$trans_data['product_name']:

                           


                            ?>
                            <tr>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php if($trans_data['trade_date'] != '0000-00-00'){ echo date('m/d/Y',strtotime($trans_data['trade_date'])); } ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php  echo $trans_data['client_name'] ?></td>
                                  <td STYLE="font-size:10px;font-weight:normal;text-align:left;"><?php  echo $trans_data['product_name'] ?></td>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['invest_amount'],2); ?></td>
                                     <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['commission_received'],2); ?></td>
                                      <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['charge_amount'],2); ?></td>
                                       <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($payable_amount,2); ?></td>
                                     
                                 
                            </tr>
                        <?php } 
                        
                        ?>
                       <tr style="background-color: #f1f1f1;">
                       <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="3"><?php echo "*&nbsp;&nbsp;".$broker_name. '&nbsp;&nbsp;TOTAL *';?></td>
                       <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($sub_total_amount_invested,2);?></td>
                       <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($sub_total_commission_received,2);?></td>
                       <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($sub_total_charges,2);?></td>
                        <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($sub_payable_charges,2);?></td>
                </tr>
                <?php } ?>
                <tr style="background-color: #f1f1f1;">
                   <td style="font-size:10px;font-weight:bold;text-align:center;">*Date = Override Trade</td>
                   <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="2"> REPORT TOTALS</td>
                   <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_amount_invested,2);?></td>
                   <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_commission_received,2);?></td>
                    <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_charges,2);?></td>
                   <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_payable_charges,2);?></td>
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
            
<?php  }
}
?>
       