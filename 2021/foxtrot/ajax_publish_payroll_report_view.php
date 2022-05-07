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

// Data gathering done by "payroll" class functions
$instance_payroll = new payroll();

if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);
    $publish_report = isset($filter_array['publish_report'])?$filter_array['publish_report']:0;
    
    if($publish_report==1)
    {
        $pdf_for_broker = isset($filter_array['pdf_for_broker'])?$filter_array['pdf_for_broker']:'';
        
        $company = isset($filter_array['company'])?$filter_array['company']:0;
        $print_type = isset($filter_array['print_type'])?$filter_array['print_type']:'';
        $output_type = isset($filter_array['output_type'])?$filter_array['output_type']:'';
        $broker = isset($filter_array['broker'])?$filter_array['broker']:0;
        $payroll_id = isset($filter_array['payroll_id'])?$filter_array['payroll_id']:'';
        $payroll_date = $instance_payroll->get_payroll_uploads($payroll_id);
        $payroll_date = $payroll_date['payroll_date'];

        $get_broker_commission_data = $instance_payroll->get_broker_commission_report_data($company,$payroll_id,$broker,$print_type);
    
        if($payroll_date != ''){ 
            $payroll_date = date('F d, Y',strtotime($payroll_date));
        }
        if($company>0)
        {
            $company = isset($get_broker_commission_data['company_name'])?$get_broker_commission_data['company_name']:'';
        }
        else
        {
            $company = 'All Company';
        }
        
        if(isset($get_broker_commission_data['broker_transactions']) && $get_broker_commission_data['broker_transactions'] != array())
        {
            foreach($get_broker_commission_data['broker_transactions'] as $brokers_comm_key=>$brokers_comm_data)
            {
                $total_broker_transactions = 0;
                $total_split_transactions = 0;
                $total_override_transactions = 0;
                $total_adjustments = 0;

                $total_payroll_draw = isset($get_broker_detail['payroll_draw'])?$get_broker_detail['payroll_draw']: 0;
                $total_base_salary = isset($get_broker_detail['salary'])?$get_broker_detail['salary']: 0;
                $total_finra_assessment = -($brokers_comm_data['finra']);
                $total_sipc_assessment = -($brokers_comm_data['sipc']); 
                $total_prior_balance = $brokers_comm_data['balance'];
                $total_forward_balance = $brokers_comm_data['prior_broker_balance'];
                $total_broker_earnings = $brokers_comm_data['prior_broker_earnings'];
                $check_minimum_check_amount = $brokers_comm_data['minimum_check_amount'];
                
                $get_broker_detail = $instance_payroll->get_broker_detail($brokers_comm_key);
            
                $broker_name = isset($get_broker_detail['first_name'])?$get_broker_detail['first_name'].' '.$get_broker_detail['last_name']:'';
                $broker_address1 = isset($get_broker_detail['home_address1_general'])?$get_broker_detail['home_address1_general']:'';
                $broker_address2 = isset($get_broker_detail['home_address2_general'])?$get_broker_detail['home_address2_general']:'';
                $broker_address = '';
                
                if($broker_address1 != '')
                {
                    $broker_address = $broker_address1;
                }
                if($broker_address1 && $broker_address2 != '')
                {
                    $broker_address .= ', '.$broker_address2;
                }
                else
                {
                    $broker_address = $broker_address2;
                }
                
                $broker_city = isset($get_broker_detail['city'])?$get_broker_detail['city']:'';
                $broker_state = isset($get_broker_detail['state_name'])?$get_broker_detail['state_name']:'';
                $broker_zipcode = isset($get_broker_detail['zip_code'])?$get_broker_detail['zip_code']:'';
                $broker_number = isset($get_broker_detail['id'])?$get_broker_detail['id']:'';
                $broker_branch = isset($get_broker_detail['branch_name'])?$get_broker_detail['branch_name']:'';
                
                if($broker_state != '' && $broker_zipcode > 0)
                {
                    $broker_zipcode = ', '.$broker_zipcode;
                }
                else
                {
                    $broker_zipcode = '';
                } 
                ?>
                 <table border="0" width="100%">
                        <tr>
                            <?php 
                            if(isset($system_logo) && $system_logo != '')
                            {?>
                                <td width="30%" align="left"><?php echo $img;?></td>
                            <?php } ?>
                            <td width="40%" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo $company;?></td>
                            <?php
                            if(isset($system_company_name) && $system_company_name != '')
                            {?>
                                <td width="30%" style="font-size:12px;font-weight:bold;text-align:right;"><?php echo $system_company_name;?></td>
                            <?php
                            }?>
                        </tr>
                        <tr>
                            <td width="100%" colspan="3" style="font-size:16px;font-weight:bold;text-align:center;"><?php echo 'BROKER STATEMENT';?></td>
                        </tr>
                        <tr>
                            <td width="100%" colspan="3" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo $payroll_date;?></td>
                        </tr>
                 </table>
                 <table border="0" width="100%">
                        <tr>
                            <td width="70%" align="left" style="font-size:10px;"><?php echo $broker_name;?></td>
                            <td width="30%" align="left" style="font-size:10px;">BROKER#/FUND/INTERNAL : <?php echo $brokers_comm_data['broker_name'].' / '.$brokers_comm_data['fund'].' / '.$brokers_comm_data['internal'];?></td>
                        </tr>
                        <tr>
                            <td width="70%" align="left" style="font-size:10px;"><?php echo $broker_address;?></td>
                            <td width="30%" align="left" style="font-size:10px;">BRANCH# : <?php echo strtoupper($broker_branch);?></td>
                        </tr>
                        <tr>
                            <td width="20%" align="left" style="font-size:10px;"><?php echo $broker_city;?></td>
                        </tr>
                        <tr>
                            <td width="20%" align="left" style="font-size:10px;"><?php echo $broker_state.' '.$broker_zipcode;?></td>
                        </tr>
                </table><br />
                <table border="0" cellpadding="1" width="100%">
                     <thead>
                        <tr style="background-color: #f1f1f1;">
                            <td width="10%" style="text-align:center;"><h5>TRADE DATE</h5></td>
                            <td width="15%" style="text-align:center;"><h5>CLIENT</h5></td>
                            <td width="15%" style="text-align:center;"><h5>INVESTMENT</h5></td>
                            <td width="5%" style="text-align:center;"><h5>B/S</h5></td>
                            <td width="9%" style="text-align:center;"><h5>INVESTMENT AMOUNT</h5></td>
                            <td width="9%" style="text-align:center;"><h5>GROSS COMMISSION</h5></td>
                            <td width="9%" style="text-align:center;"><h5>CLEARING CHARGE</h5></td>
                            <td width="9%" style="text-align:center;"><h5>NET COMMISSION</h5></td>
                            <td width="9%" style="text-align:center;"><h5>RATE</h5></td>
                            <td width="10%" style="text-align:center;"><h5>BROKER COMMISSION</h5></td>
                        </tr>
                    </thead>
                    <tbody>
                    <tr><td>&nbsp;</td></tr>
                    <?php 
                    if(isset($brokers_comm_data['direct_transactions']) && $brokers_comm_data['direct_transactions'] != array())
                    {
                        $broker_investment_amount = 0;
                        $broker_commission_received = 0;
                        $broker_net_commission = 0;
                        $broker_charges = 0;
                        $broker_rate = 0;
                        $broker_broker_commission = 0;
                    ?>
                        <tr>
                               <td colspan="10" style="font-size:10px;font-weight:bold;text-align:center;">BROKER TRANSACTIONS</td>
                        </tr>
                        <?php
                        foreach($brokers_comm_data['direct_transactions'] as $comm_key=>$comm_data)
                        {?>
                            <tr>
                                   <td colspan="10" style="font-size:10px;font-weight:bold;text-align:left;">PRODUCT CATEGORY: <?php echo strtoupper($comm_key);?></td>
                            </tr>
                            <?php
                            $category_investment_amount = 0;
                            $category_commission_received = 0;
                            $category_net_commission = 0;
                            $category_charges = 0;
                            $category_rate = 0;
                            $category_broker_commission = 0;
                            foreach($comm_data as $comm_sub_key=>$comm_sub_data)
                            {
                                $trade_date='';
                                $category_investment_amount = $category_investment_amount+$comm_sub_data['investment_amount'];
                                $category_commission_received = $category_commission_received+$comm_sub_data['commission_received'];
                                $category_net_commission = $category_net_commission+$comm_sub_data['net_commission'];
                                $category_charges = $category_charges+$comm_sub_data['charge'];
                                $category_rate = $category_rate+0;
                                $category_broker_commission = $category_broker_commission+$comm_sub_data['commission_paid'];
                                /*** Moved above foreach($brokers_comm_data['direct_transactions'].....) - Only needed to be updated once, since these numbers are already calculated in Payroll_Calculation() 11/9/21 ***/
                                // $total_finra_assessment = -($comm_sub_data['finra']);
                                // $total_sipc_assessment = -($comm_sub_data['sipc']); 
                                // $total_prior_balance = $comm_sub_data['balance'];  // + $total_prior_balance
                                // $total_forward_balance = $comm_sub_data['prior_broker_balance'];
                                // $total_broker_earnings = $comm_sub_data['prior_broker_earnings'];
                                
                                if(isset($comm_sub_data['buy_sell']) && $comm_sub_data['buy_sell'] == 1)
                                {
                                    $buy_sell = 'B';
                                }
                                else if(isset($comm_sub_data['buy_sell']) && $comm_sub_data['buy_sell'] == 2)
                                {
                                    $buy_sell = 'S';
                                }
                                else
                                {
                                    $buy_sell='';
                                }
                                
                                if($comm_sub_data['trade_date'] != '0000-00-00' && $comm_sub_data['trade_date'] != ''){ $trade_date = date('m/d/Y',strtotime($comm_sub_data['trade_date'])); } ?>
                                <tr>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $trade_date;?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $comm_sub_data['client_firstname'].', '.$comm_sub_data['client_lastname'];?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $comm_sub_data['batch_description'];?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $buy_sell;?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($comm_sub_data['investment_amount'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($comm_sub_data['commission_received'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($comm_sub_data['charge'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($comm_sub_data['net_commission'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($comm_sub_data['rate'],2); ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($comm_sub_data['commission_paid'],2);?></td>
                                </tr>
                            <?php
                            }
                            $broker_investment_amount = $broker_investment_amount+$category_investment_amount;
                            $broker_commission_received = $broker_commission_received+$category_commission_received;
                            $broker_net_commission = $broker_net_commission+$category_net_commission;
                            $broker_charges = $broker_charges+$category_charges;
                            $broker_rate = $broker_rate+$category_rate;
                            $broker_broker_commission = $broker_broker_commission+$category_broker_commission;?>
                
                            <tr style="background-color: #f1f1f1;">
                               <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="3">* <?php echo strtoupper($comm_key);?> SUBTOTAL * </td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_investment_amount,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_commission_received,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_charges,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_net_commission,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_broker_commission,2);?></td>
                            </tr>
                        <?php
                        }
                        $total_broker_transactions = $total_broker_transactions+$broker_broker_commission;?>
                        <tr style="background-color: #f1f1f1;">
                           <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="3">*** BROKER TRANSACTIONS TOTAL *** </td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_investment_amount,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_commission_received,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_charges,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_net_commission,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_broker_commission,2);?></td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                    <?php
                    }
                    if(isset($brokers_comm_data['override_transactions']) && $brokers_comm_data['override_transactions'] != array())
                    {
                        $broker_investment_amount = 0;
                        $broker_commission_received = 0;
                        $broker_net_commission = 0;
                        $broker_charges = 0;
                        $broker_rate = 0;
                        $broker_broker_commission = 0;?>
                        <tr>
                            <td colspan="10" style="font-size:10px;font-weight:bold;text-align:center;">OVERRIDE TRANSACTIONS</td>
                        </tr>
                        <?php
                        foreach($brokers_comm_data['override_transactions'] as $override_comm_key=>$override_comm_data)
                        {?>
                            <tr>
                                <td colspan="10" style="font-size:10px;font-weight:bold;text-align:left;">OVERRIDE BROKER: <?php echo strtoupper($override_comm_key);?></td>
                            </tr>
                            <?php
                            $category_investment_amount = 0;
                            $category_commission_received = 0;
                            $category_net_commission = 0;
                            $category_charges = 0;
                            $category_rate = 0;
                            $category_broker_commission = 0;
                            foreach($override_comm_data as $override_sub_key=>$override_sub_data)
                            {
                                $trade_date='';
                                $category_investment_amount = $category_investment_amount+$override_sub_data['investment_amount'];
                                $category_commission_received = $category_commission_received+$override_sub_data['commission_received'];
                                $category_net_commission = $category_net_commission+$override_sub_data['net_commission'];
                                $category_charges = $category_charges+$override_sub_data['charge'];
                                $category_rate = $category_rate+0;
                                $category_broker_commission = $category_broker_commission+$override_sub_data['rate_amount'];
                                
                                if(isset($override_sub_data['buy_sell']) && $override_sub_data['buy_sell'] == 1)
                                {
                                    $buy_sell = 'B';
                                }
                                else if(isset($override_sub_data['buy_sell']) && $override_sub_data['buy_sell'] == 2)
                                {
                                    $buy_sell = 'S';
                                }
                                else
                                {
                                    $buy_sell='';
                                }
                                
                                if($override_sub_data['trade_date'] != '0000-00-00' && $override_sub_data['trade_date'] != ''){ $trade_date = date('m/d/Y',strtotime($override_sub_data['trade_date'])); }?>  
                                <tr>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $trade_date; ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $override_sub_data['client_firstname'].', '.$override_sub_data['client_lastname'];?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $override_sub_data['batch_description'];?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $buy_sell;?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($override_sub_data['investment_amount'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($override_sub_data['commission_received'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($override_sub_data['charge'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($override_sub_data['net_commission'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($override_sub_data['rate_per'],2); ?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($override_sub_data['rate_amount'],2);?></td>
                                </tr>
                            <?php
                            }
                            $broker_investment_amount = $broker_investment_amount+$category_investment_amount;
                            $broker_commission_received = $broker_commission_received+$category_commission_received;
                            $broker_net_commission = $broker_net_commission+$category_net_commission;
                            $broker_charges = $broker_charges+$category_charges;
                            $broker_rate = $broker_rate+$category_rate;
                            $broker_broker_commission = $broker_broker_commission+$category_broker_commission;?>
                            <tr style="background-color: #f1f1f1;">
                               <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="3">* OVERRIDE SUBTOTAL FOR <?php echo strtoupper($override_comm_key);?> * </td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_investment_amount,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_commission_received,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_charges,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_net_commission,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_broker_commission,2);?></td>
                            </tr>
                        <?php
                        }
                        $total_override_transactions = $total_override_transactions+$broker_broker_commission;?>
                        <tr style="background-color: #f1f1f1;">
                           <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="3">*** OVERRIDE TRANSACTIONS TOTAL *** </td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_investment_amount,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_commission_received,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_charges,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_net_commission,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_broker_commission,2);?></td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                    <?php
                    }
                    if(isset($brokers_comm_data['split_transactions']) && $brokers_comm_data['split_transactions'] != array())
                    {
                        $broker_investment_amount = 0;
                        $broker_commission_received = 0;
                        $broker_net_commission = 0;
                        $broker_charges = 0;
                        $broker_rate = 0;
                        $broker_broker_commission = 0;?>
                        <tr>
                            <td colspan="10" style="font-size:10px;font-weight:bold;text-align:center;">SPLIT TRANSACTIONS</td>
                        </tr>
                        <?php
                        foreach($brokers_comm_data['split_transactions'] as $split_comm_key=>$split_comm_data)
                        {?>
                            <tr>
                                <td colspan="10" style="font-size:10px;font-weight:bold;text-align:left;">SPLIT BROKER: <?php echo strtoupper($split_comm_key);?></td>
                            </tr>
                            <?php 
                            $category_investment_amount = 0;
                            $category_commission_received = 0;
                            $category_net_commission = 0;
                            $category_charges = 0;
                            $category_rate = 0;
                            $category_broker_commission = 0;
                            foreach($split_comm_data as $split_sub_key=>$split_sub_data)
                            {
                                $trade_date='';
                                $category_investment_amount = $category_investment_amount+$split_sub_data['investment_amount'];
                                $category_commission_received = $category_commission_received+$split_sub_data['commission_received'];
                                $category_net_commission = $category_net_commission+$split_sub_data['net_commission'];
                                $category_charges = $category_charges+$split_sub_data['charge'];
                                $category_rate = $category_rate+0;
                                $category_broker_commission = $category_broker_commission+$split_sub_data['rate_amount'];
                                
                                if(isset($split_sub_data['buy_sell']) && $split_sub_data['buy_sell'] == 1)
                                {
                                    $buy_sell = 'B';
                                }
                                else if(isset($split_sub_data['buy_sell']) && $split_sub_data['buy_sell'] == 2)
                                {
                                    $buy_sell = 'S';
                                }
                                else
                                {
                                    $buy_sell='';
                                }
                                
                                if($split_sub_data['trade_date'] != '0000-00-00' && $split_sub_data['trade_date'] != ''){ $trade_date = date('m/d/Y',strtotime($split_sub_data['trade_date'])); }?>  
                                <tr>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $trade_date;?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $split_sub_data['client_firstname'].', '.$split_sub_data['client_lastname'];?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $split_sub_data['batch_description'];?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $buy_sell;?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($split_sub_data['investment_amount'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($split_sub_data['commission_received'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($split_sub_data['charge'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($split_sub_data['net_commission'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($split_sub_data['rate'],2);?></td>
                                   <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($split_sub_data['rate_amount'],2);?></td>
                                </tr>
                            <?php
                            }
                            $broker_investment_amount = $broker_investment_amount+$category_investment_amount;
                            $broker_commission_received = $broker_commission_received+$category_commission_received;
                            $broker_net_commission = $broker_net_commission+$category_net_commission;
                            $broker_charges = $broker_charges+$category_charges;
                            $broker_rate = $broker_rate+$category_rate;
                            $broker_broker_commission = $broker_broker_commission+$category_broker_commission;?>
                            <tr style="background-color: #f1f1f1;">
                               <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="3">* SPLIT SUBTOTAL FOR <?php echo strtoupper($split_comm_key);?> * </td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_investment_amount,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_commission_received,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_charges,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_net_commission,2);?></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                               <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($category_broker_commission,2);?></td>
                            </tr>
                        <?php
                        }
                        $total_split_transactions = $total_split_transactions+$broker_broker_commission;?>
                        <tr style="background-color: #f1f1f1;">
                           <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="3">*** SPLIT TRANSACTIONS TOTAL *** </td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_investment_amount,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_commission_received,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_charges,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_net_commission,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($broker_broker_commission,2);?></td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                    <?php
                    }
                    if(isset($brokers_comm_data['adjustments']) && $brokers_comm_data['adjustments'] != array())
                    {?>
                        <tr>
                               <td colspan="10" style="font-size:10px;font-weight:bold;text-align:center;">ADJUSTMENTS</td>
                        </tr>
                        <?php
                        $adjustment_total = 0;
                        foreach($brokers_comm_data['adjustments'] as $adj_key=>$adj_data)
                        {
                            $adjustment_total = $adjustment_total + $adj_data['adjustment_amount'];?>
                            <tr>
                               <td width="10%" style="font-size:10px;font-weight:normal;text-align:center;">02/28/2016</td>
                               <td width="15%" style="font-size:10px;font-weight:normal;text-align:center;"></td>
                               <td width="15%" style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $adj_data['description'];?></td>
                               <td width="5%" style="font-size:10px;font-weight:normal;text-align:center;"></td>
                               <td width="9%" style="font-size:10px;font-weight:normal;text-align:right;">0.00</td>
                               <td width="9%" style="font-size:10px;font-weight:normal;text-align:right;">0.00</td>
                               <td width="9%" style="font-size:10px;font-weight:normal;text-align:right;">0.00</td>
                               <td width="9%" style="font-size:10px;font-weight:normal;text-align:right;">0.00</td>
                               <td width="9%" style="font-size:10px;font-weight:bold;text-align:right;"></td>
                               <td width="10%" style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($adj_data['adjustment_amount'],2);?></td>
                            </tr>
                        <?php
                        }
                        $total_adjustments = $total_adjustments+$adjustment_total;?>
                        <tr style="background-color: #f1f1f1;">
                           <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="3">*** ADJUSTMENTS TOTAL *** </td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$0.00</td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$0.00</td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$0.00</td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$0.00</td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;">$<?php echo number_format($adjustment_total,2);?></td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                    <?php
                    }
                    $total_check_amount = ($total_broker_transactions+$total_split_transactions+$total_override_transactions+$total_adjustments+$total_base_salary+$total_prior_balance+$total_finra_assessment+$total_sipc_assessment);
                    ?>
                    </tbody>
                </table>
                <table border="0" width="100%">
                        <tr>
                            <td colspan="10" style="font-size:10px;font-weight:bold;text-align:right;">BROKER COMMISSION TOTALS</td>
                        </tr>
                        <tr>
                            <td width="90%" style="font-size:10px;font-weight:normal;text-align:right;">Broker Transactions </td>
                            <td width="10%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_broker_transactions,2);?></td>
                        </tr>
                        <tr>
                            <td width="90%" style="font-size:10px;font-weight:normal;text-align:right;">Split Transactions </td>
                            <td width="10%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_split_transactions,2);?></td>
                        </tr>
                        <tr>
                            <td width="90%" style="font-size:10px;font-weight:normal;text-align:right;">Override Transactions </td>
                            <td width="10%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_override_transactions,2);?></td>
                        </tr>
                        <tr>
                            <td width="90%" style="font-size:10px;font-weight:normal;text-align:right;">Adjustments </td>
                            <td width="10%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_adjustments,2);?></td>
                        </tr>
                        <tr>
                            <td width="90%" style="font-size:10px;font-weight:normal;text-align:right;">Payroll Draw </td>
                            <td width="10%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_payroll_draw,2);?></td>
                        </tr>
                        <tr>
                            <td width="90%" style="font-size:10px;font-weight:normal;text-align:right;">Base Salary </td>
                            <td width="10%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_base_salary,2);?></td>
                        </tr>
                        <tr>
                            <td width="90%" style="font-size:10px;font-weight:normal;text-align:right;">Prior Period Balance </td>
                            <td width="10%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_prior_balance,2);?></td>
                        </tr>
                        <tr>
                            <td width="90%" style="font-size:10px;font-weight:normal;text-align:right;">FINRA Assessment </td>
                            <td width="10%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_finra_assessment,2);?></td>
                        </tr>
                        <tr>
                            <td width="90%" style="font-size:10px;font-weight:normal;text-align:right;">SIPC Assessment </td>
                            <td width="10%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_sipc_assessment,2);?></td>
                        </tr>
                   </table>
                   <table width="100%">
                    <tr align="left" style="font-size:10px;font-weight:normal;text-align:left;">
                        <td width="70%">
                            <table width="100%">
                                <tr>
                                    <td style="font-size:10px;font-weight:normal;text-align:right;">Please Retain for Your Records </td>
                                </tr>
                                <?php 
                                // 11/11/21 Take the minimum check amount from the data - see in the totals for the individual broker data ($brokers_comm_data['minimum_check_amount'])
                                //$check_minimum_check_amount=$instance_payroll->check_minimum_check_amount();
                                if ($check_minimum_check_amount>$total_check_amount){
                                ?>
                                    <tr>
                                        <td style="font-size:10px;font-weight:normal;text-align:right;">THERE WILL BE NO CHECK THIS PERIOD</td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                        <td width="5%"></td>
                        <td width="25%" border="1" style="border: 1px solid;">
                            <table width="100%">
                                <?php 
                                $total_broker_earnings = $total_broker_earnings+$total_check_amount;
                                if ($check_minimum_check_amount>$total_check_amount){
                                ?>
                                <tr>
                                    <td width="70%" style="font-size:10px;font-weight:normal;text-align:right;">Balance Forward </td>
                                    <td width="30%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_check_amount,2);?>&nbsp;&nbsp;</td>
                                </tr>
                                <?php }
                                else
                                {?>
                                <tr>
                                    <td width="70%" style="font-size:10px;font-weight:normal;text-align:right;">Check Amount </td>
                                    <td width="30%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_check_amount,2);?>&nbsp;&nbsp;</td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td width="70%" style="font-size:10px;font-weight:normal;text-align:right;">Year-to-date Earnings</td>
                                    <td width="30%" style="font-size:10px;font-weight:normal;text-align:right;"> $<?php echo number_format($total_broker_earnings,2);?>&nbsp;&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                   </tr>
                 </table>
                 <br />
        <?php
        }
                
    }
    else
    {?>
        <table border="0" width="100%">
                <tr>
                    <?php 
                    if(isset($system_logo) && $system_logo != '')
                    {?>
                        <td width="30%" align="left"><?php echo $img;?></td>
                    <?php } ?>
                    <td width="40%" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo $company;?></td>
                    <?php
                    if(isset($system_company_name) && $system_company_name != '')
                    {?>
                        <td width="30%" style="font-size:12px;font-weight:bold;text-align:right;"><?php echo $system_company_name;?></td>
                    <?php
                    }?>
                </tr>
                <tr>
                    <td width="100%" colspan="3" style="font-size:16px;font-weight:bold;text-align:center;"><?php echo 'COMMISSION STATEMENT';?></td>
                </tr>
                <tr>
                    <td width="100%" colspan="3" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo $payroll_date;?></td>
                </tr>
        </table>
        <table>
            <tr style="background-color: #f1f1f1;">
                <td width="10%" style="text-align:center;"><h5>TRADE DATE#</h5></td>
                <td width="15%" style="text-align:center;"><h5>CLIENT</h5></td>
                <td width="15%" style="text-align:center;"><h5>INVESTMENT</h5></td>
                <td width="5%" style="text-align:center;"><h5>B/S</h5></td>
                <td width="9%" style="text-align:center;"><h5>INVESTMENT AMOUNT</h5></td>
                <td width="9%" style="text-align:center;"><h5>GROSS COMMISSION</h5></td>
                <td width="9%" style="text-align:center;"><h5>CLEARING CHARGE</h5></td>
                <td width="9%" style="text-align:center;"><h5>NET COMMISSION</h5></td>
                <td width="9%" style="text-align:center;"><h5>RATE</h5></td>
                <td width="10%" style="text-align:center;"><h5>BROKER COMMISSION</h5></td>
            </tr>
            <br/>
            <tr>
                <td style="font-size:11px;font-weight:cold;text-align:center;" colspan="10">No Records Found.</td>
            </tr>
        </table>
    <?php 
    }      
    if($pdf_for_broker == 1)
    {
      echo "|||".$pdf_for_broker;
    }
          
    }
      else if($publish_report==2)
      { 
        $get_company_data = array();
        $company = isset($filter_array['company'])?$filter_array['company']:0;
        $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
        $payroll_id = isset($filter_array['payroll_id'])?$filter_array['payroll_id']:'';
        $get_company_data = $instance_payroll->get_company_statement_report_data($company,$sort_by,$payroll_id);
        $get_payroll_upload = $instance_payroll->get_payroll_uploads($payroll_id);
        $payroll_date = $get_payroll_upload['payroll_date'];

        ?>
         <table border="0" width="100%">
                <tr>
                    <?php 
                    if(isset($system_logo) && $system_logo != '')
                    {?>
                        <td width="30%" align="left"><?php echo $img;?></td>
                    <?php } ?>
                    <td width="40%" style="font-size:16px;font-weight:bold;text-align:center;"><?php echo 'COMPANY COMMISSION STATEMENT';?></td>
                    <?php
                    if(isset($system_company_name) && $system_company_name != '')
                    {?>
                        <td width="30%" style="font-size:12px;font-weight:bold;text-align:right;"><?php echo $system_company_name;?></td>
                    <?php
                    }?>
                </tr>
                <?php 
                if($company > 0){
                    $company_name = '';
                    foreach($get_company_data as $key=>$val)
                    {
                        $company_name = $key;
                    }
                ?>
                <tr>
                    <td width="100%" colspan="3" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo $company_name;?></td>
                </tr>
                <?php } else { ?>
                <tr>
                    <td width="100%" colspan="3" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo 'All Companies';?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td width="100%" colspan="3" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo date('m/d/Y', strtotime($payroll_date));?></td>
                </tr>
         </table>
         <table border="0" cellpadding="1" width="100%">
            <thead>
                <tr style="background-color: #f1f1f1;">
                    <td width="5%" style="text-align:center;"><h5>REP#</h5></td>
                    <td width="10%" style="text-align:center;"><h5>NAME</h5></td>
                    <td width="10%" style="text-align:center;"><h5>GROSS COMMISSIONS</h5></td>
                    <td width="10%" style="text-align:center;"><h5>NET COMMISSIONS</h5></td>
                    <td width="10%" style="text-align:center;"><h5>CHARGE</h5></td>
                    <td width="10%" style="text-align:center;"><h5>OVERRIDE COMMISSIONS</h5></td>
                    <td width="10%" style="text-align:center;"><h5>PRIOR BALANCE</h5></td>
                    <td width="10%" style="text-align:center;"><h5>ADVANCES/ ADJUSTMENTS</h5></td>
                    <td width="8%" style="text-align:center;"><h5>FINRA/SIPC</h5></td>
                    <td width="7%" style="text-align:center;"><h5>CHECK AMOUNT</h5></td>
                    <td width="10%" style="text-align:center;"><h5>B/D RETENTION</h5></td>
                </tr>
            </thead>
            <tbody>
            <?php 
                if($get_company_data != array())
                {
                    $report_gross_comm_total = 0;
                    $report_net_comm_total = 0;
                    $report_charge_total = 0;
                    $report_override_comm_total = 0;
                    $report_balances_total = 0;
                    $report_adjustments_total = 0;
                    $report_finra_sipc_total = 0;
                    $report_check_amount_total = 0;
                    $report_retention_total = 0;
                    foreach($get_company_data as $com_key=>$com_data)
                    {
                    ?>
                        <tr>
                            <td colspan="11" style="font-size:12px;font-weight:bold;text-align:left;"><?php echo $com_key;?></td>
                        </tr>
                        <?php
                        $company_gross_comm_total = 0;
                        $company_net_comm_total = 0;
                        $company_charge_total = 0;
                        $company_override_comm_total = 0;
                        $company_balances_total = 0;
                        $company_adjustments_total = 0;
                        $company_finra_sipc_total = 0;
                        $company_check_amount_total = 0;
                        $company_retention_total = 0;
                        foreach($com_data as $com_sub_key=>$com_sub_data)
                        {
                            $retention = $com_sub_data['commission_received']-$com_sub_data['check_amount'];
                            $finra_sipc = -$com_sub_data['finra']-$com_sub_data['sipc'];
                            
                            $company_gross_comm_total = $company_gross_comm_total+$com_sub_data['commission_received'];
                            $company_net_comm_total = $company_net_comm_total+$com_sub_data['commission_paid'];
                            $company_charge_total = $company_charge_total+$com_sub_data['charge'];
                            $company_override_comm_total = $company_override_comm_total+$com_sub_data['override_paid'];
                            $company_balances_total = $company_balances_total+$com_sub_data['balance'];
                            $company_adjustments_total = $company_adjustments_total+$com_sub_data['adjustments'];
                            $company_finra_sipc_total = $company_finra_sipc_total+$finra_sipc;
                            $company_check_amount_total = $company_check_amount_total+$com_sub_data['check_amount'];
                            $company_retention_total = $company_retention_total+$retention;
                            ?>
                            <tr>
                               <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $com_sub_data['fund'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $com_sub_data['broker_firstname'].' '.$com_sub_data['broker_lastname'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo $com_sub_data['commission_received'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo $com_sub_data['commission_paid'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo $com_sub_data['charge'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo $com_sub_data['override_paid'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo $com_sub_data['balance'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo $com_sub_data['adjustments'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($finra_sipc,2);?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo $com_sub_data['check_amount'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($retention,2);?></td>
                            </tr>
                            <?php 
                        }
                        $report_gross_comm_total = $report_gross_comm_total+$company_gross_comm_total;
                        $report_net_comm_total = $report_net_comm_total+$company_net_comm_total;
                        $report_charge_total = $report_charge_total+$company_charge_total;
                        $report_override_comm_total = $report_override_comm_total+$company_override_comm_total;
                        $report_balances_total = $report_balances_total+$company_balances_total;
                        $report_adjustments_total = $report_adjustments_total+$company_adjustments_total;
                        $report_finra_sipc_total = $report_finra_sipc_total+$company_finra_sipc_total;
                        $report_check_amount_total = $report_check_amount_total+$company_check_amount_total;
                        $report_retention_total = $report_retention_total+$company_retention_total;?>
                        <tr style="background-color: #f1f1f1;">
                           <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="2">* Company Total *</td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($company_gross_comm_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($company_net_comm_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($company_charge_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($company_override_comm_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($company_balances_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($company_adjustments_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($company_finra_sipc_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($company_check_amount_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($company_retention_total,2);?></td>
                        </tr>
                    <?php 
                    }
                    ?>
                        <tr style="background-color: #f1f1f1;">
                           <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="2">*** Report Total ***</td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_gross_comm_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_net_comm_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_charge_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_override_comm_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_balances_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_adjustments_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_finra_sipc_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_check_amount_total,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_retention_total,2);?></td>
                        </tr>
                    <?php 
                    }
                    else
                    {
                    ?>
                    <tr>
                        <td style="font-size:13px;font-weight:cold;text-align:center;" colspan="11">No Records Found.</td>
                    </tr>
                    <?php
                    }?>
           </tbody>
        </table>
      <?php 
      }
      else if($publish_report==3)
      { 
        $company = isset($filter_array['company'])?$filter_array['company']:0;
        $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
        // 11/23/21 Payroll ID passed instead of 'payroll_date' from the form submit
        $payroll_id = isset($filter_array['payroll_id'])?$filter_array['payroll_id']:'';
        $get_payroll_upload = $instance_payroll->get_payroll_uploads($payroll_id);
        $payroll_date = date('m/d/Y', strtotime($get_payroll_upload['payroll_date']));
        $output_type = isset($filter_array['output_type'])?$filter_array['output_type']:'';
        
        $get_adjustments_data = $instance_payroll->get_adjustments_report_data($company,$payroll_id,$sort_by,$output_type);
        
        if($company>0)
        {
            $company_name = isset($get_adjustments_data['company_name'])?$get_adjustments_data['company_name']:'';
        }
        else
        {
            $company_name = 'All Company';
        }
        
        if(isset($sort_by) && $sort_by == 1)
        {
            $sorted_by = 'Sorted by Rep Name';
        }
        else if(isset($sort_by) && $sort_by == 2)
        {
            $sorted_by = 'Sorted by Rep Number';
        }
        else if(isset($sort_by) && $sort_by == 3)
        {
            $sorted_by = 'Sorted by Category';
        }
        else if(isset($sort_by) && $sort_by == 4)
        {
            $sorted_by = 'Sorted by G/L Account';
        }
        else
        {
            $sorted_by='';
        }
        
        ?>
         <table border="0" width="100%">
                <tr>
                    <?php 
                    if(isset($system_logo) && $system_logo != '')
                    {?>
                        <td width="30%" align="left"><?php echo $img;?></td>
                    <?php 
                    }?>
                    <td width="40%" style="font-size:12px;font-weight:normal;text-align:center;"><?php echo $company_name;?></td>
                    <?php
                    if(isset($system_company_name) && $system_company_name != '')
                    {?>
                        <td width="30%" style="font-size:12px;font-weight:bold;text-align:right;"><?php echo $system_company_name;?></td>
                    <?php
                    }?>
                </tr>
                <tr>
                    <td width="100%" colspan="3" style="font-size:16px;font-weight:bold;text-align:center;"><?php echo 'COMMISSION ADJUSTMENT LOG';?></td>
                </tr>
                <tr>
                    <td width="100%" colspan="3" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo $sorted_by;?></td>
                </tr>
                <tr>
                    <td width="100%" colspan="3" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo $payroll_date;?></td>
                </tr>
         </table>
         <table border="0" cellpadding="1" width="100%">
            <thead>
                <tr style="background-color: #f1f1f1;">
                <?php
                if(isset($output_type) && $output_type != '2')
                {?>
                    <td width="10%" style="text-align:center;"><h5>ADJUST#</h5></td>
                <?php
                }
                if(isset($output_type) && $output_type == '2')
                {?>
                    <td width="10%" style="text-align:center;" colspan="5"><h5>REP#</h5></td>
                <?php
                }
                else
                {?>
                    <td width="10%" style="text-align:center;"><h5>REP#</h5></td>
                <?php
                }
                if(isset($output_type) && $output_type != '2')
                {?>
                    <td width="10%" style="text-align:center;"><h5>CLEAR NUMBER</h5></td>
                    <td width="20%" style="text-align:center;"><h5>DESCRIPTION</h5></td>
                    <td width="20%" style="text-align:center;"><h5>CATEGORY</h5></td>
                <?php
                }?>
                    <td width="9%" style="text-align:center;"><h5>TAXABLE AMOUNT</h5></td>
                    <td width="9%" style="text-align:center;"><h5>NON-TAXABLE AMOUNT</h5></td>
                    <td width="9%" style="text-align:center;"><h5>ADVANCE</h5></td>
                </tr>
            </thead>
            <tbody>
            <?php
            if(isset($get_adjustments_data['data']) && $get_adjustments_data['data'] != array())
            {
                $report_taxable_adjustments = 0;
                $report_non_taxable_adjustments = 0;
                $report_advance = 0;
                if(isset($output_type) && ($output_type == '1' || $output_type == '3'))
                {
                    foreach($get_adjustments_data['data'] as $adj_key=>$adj_data)
                    {
                    ?>
                        <tr>
                            <td colspan="11" style="font-size:12px;font-weight:bold;text-align:left;"><?php echo 'BROKER #'.strtoupper($adj_key);?></td>
                        </tr>
                        <?php 
                        $broker_taxable_adjustments = 0;
                        $broker_non_taxable_adjustments = 0;
                        $broker_advance = 0;
                        foreach($adj_data as $adj_sub_key=>$adj_sub_data)
                        {  
                            if(isset($adj_sub_data['payroll_category']) && strtolower($adj_sub_data['payroll_category']) == strtolower('ADVANCE'))
                            {
                                $taxable_adjustments = 0;
                                $non_taxable_adjustments = 0;
                                $advance = isset($adj_sub_data['adjustment_amount']) && $adj_sub_data['adjustment_amount'] != '' ?$adj_sub_data['adjustment_amount']:0;
                            }
                            else
                            {
                                $taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 1 ?$adj_sub_data['adjustment_amount']:0;
                                $non_taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 0 ?$adj_sub_data['adjustment_amount']:0;
                                $advance = 0;
                            }
                            
                            $broker_taxable_adjustments = $broker_taxable_adjustments+$taxable_adjustments;
                            $broker_non_taxable_adjustments = $broker_non_taxable_adjustments+$non_taxable_adjustments;
                            $broker_advance = $broker_advance+$advance;
                            ?>
                            <tr>
                               <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $adj_sub_data['id'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $adj_sub_data['broker_id'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $adj_sub_data['fund'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $adj_sub_data['description'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $adj_sub_data['payroll_category'];?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($taxable_adjustments,2);?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($non_taxable_adjustments,2);?></td>
                               <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo number_format($advance,2);?></td>
                            </tr>
                        <?php
                        }
                        $report_taxable_adjustments = $report_taxable_adjustments+$broker_taxable_adjustments;
                        $report_non_taxable_adjustments = $report_non_taxable_adjustments+$broker_non_taxable_adjustments;
                        $report_advance = $report_advance+$broker_advance;
                        ?>
                        <tr style="background-color: #f1f1f1;">
                           <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="5"><?php echo '* #'.strtoupper($adj_key).' TOTAL *';?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($broker_taxable_adjustments,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($broker_non_taxable_adjustments,2);?></td>
                           <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($broker_advance,2);?></td>
                        </tr>
                    <?php 
                    }
                }
                else if(isset($output_type) && $output_type == '2')
                {
                    foreach($get_adjustments_data['data'] as $adj_key=>$adj_data)
                    {
                    ?>
                        <?php 
                        $broker_taxable_adjustments = 0;
                        $broker_non_taxable_adjustments = 0;
                        $broker_advance = 0;
                        foreach($adj_data as $adj_sub_key=>$adj_sub_data)
                        {  
                            if(isset($adj_sub_data['payroll_category']) && strtolower($adj_sub_data['payroll_category']) == strtolower('ADVANCE'))
                            {
                                $taxable_adjustments = 0;
                                $non_taxable_adjustments = 0;
                                $advance = isset($adj_sub_data['adjustment_amount']) && $adj_sub_data['adjustment_amount'] != '' ?$adj_sub_data['adjustment_amount']:0;
                            }
                            else
                            {
                                $taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 1 ?$adj_sub_data['adjustment_amount']:0;
                                $non_taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 0 ?$adj_sub_data['adjustment_amount']:0;
                                $advance = 0;
                            }
                            
                            $broker_taxable_adjustments = $broker_taxable_adjustments+$taxable_adjustments;
                            $broker_non_taxable_adjustments = $broker_non_taxable_adjustments+$non_taxable_adjustments;
                            $broker_advance = $broker_advance+$advance;
                            ?>
                            
                        <?php
                        }
                        $report_taxable_adjustments = $report_taxable_adjustments+$broker_taxable_adjustments;
                        $report_non_taxable_adjustments = $report_non_taxable_adjustments+$broker_non_taxable_adjustments;
                        $report_advance = $report_advance+$broker_advance;
                        ?>
                        <tr>
                           <td style="font-size:10px;font-weight:normal;text-align:left;" colspan="5"><?php echo 'BROKER #'.strtoupper($adj_key);?></td>
                           <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($broker_taxable_adjustments,2);?></td>
                           <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($broker_non_taxable_adjustments,2);?></td>
                           <td style="font-size:10px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($broker_advance,2);?></td>
                        </tr>
                    <?php 
                    }
                }?>
                <tr style="background-color: #f1f1f1;">
                   <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="5"><?php echo '*** REPORT TOTALS ****';?> </td>
                   <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_taxable_adjustments,2);?></td>
                   <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_non_taxable_adjustments,2);?></td>
                   <td style="font-size:10px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_advance,2);?></td>
                </tr>
            <?php
            }
            else
            {?>
                <tr>
                    <td style="font-size:13px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
                </tr>
            <?php
            }?>
           </tbody>
        </table>
    <!-- ------------------------ -->
    <!-- PAYROLL RECON REPORT (4) -->
    <!-- ------------------------ -->
    <?php } else if($publish_report==4) { 
        $get_reconci_data = array();
        $product_category = isset($filter_array['product_category'])?$filter_array['product_category']:0;
        // 11/23/21 Payroll ID passed instead of 'payroll_date' from the form submit
        $payroll_id = isset($filter_array['payroll_id'])?$filter_array['payroll_id']:'';
        $get_payroll_upload = $instance_payroll->get_payroll_uploads($payroll_id);
        $payroll_date = date('m/d/Y', strtotime($get_payroll_upload['payroll_date']));
        $output_type = isset($filter_array['output_type'])?$filter_array['output_type']:'';

        $get_reconci_data = $instance_payroll->get_reconciliation_report_data($product_category,$payroll_id);
        
        ?>
         <table border="0" width="100%">
                <tr>
                    <?php 
                    if(isset($system_logo) && $system_logo != '')
                    {?>
                        <td width="30%" align="left"><?php echo $img;?></td>
                    <?php } ?>
                    <td width="40%" style="font-size:16px;font-weight:bold;text-align:center;"><?php echo 'PAYROLL BATCH REPORT';?></td>
                    <?php
                    if(isset($system_company_name) && $system_company_name != '')
                    {?>
                        <td width="30%" style="font-size:12px;font-weight:bold;text-align:right;"><?php echo $system_company_name;?></td>
                    <?php
                    }?>
                </tr>
                <tr>
                    <td width="100%" colspan="3" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo 'ALL BATCH GROUPS';?></td>
                </tr>
         </table>
         <table border="0" cellpadding="1" width="100%">
            <thead>
                <tr style="background-color: #f1f1f1;">
                    <td width="10%" style="text-align:center;"><h5>BATCH#</h5></td>
                    <td width="10%" style="text-align:center;"><h5>BATCH DATE</h5></td>
                    <td width="25%" style="text-align:center;"><h5>STATEMENT \ DESCRIPTION</h5></td>
                    <td width="10%" style="text-align:center;"><h5>TRADE COUNT</h5></td>
                    <td width="9%" style="text-align:center;"><h5>GROSS COMMISION</h5></td>
                    <td width="9%" style="text-align:center;"><h5>HOLD COMMISION</h5></td>
                    <td width="9%" style="text-align:center;"><h5>TOTAL COMMISSION</h5></td>
                    <td width="9%" style="text-align:center;"><h5>CHECK AMOUNT</h5></td>
                    <td width="9%" style="text-align:center;"><h5>DIFFERENCE</h5></td>
                </tr>
            </thead>
            <tbody>
            <?php 
                if($get_reconci_data != array())
                {
                    $report_trade_count_total = 0;
                    $report_gross_commission_total = 0;
                    $report_hold_commission_total = 0;
                    $report_total_commission_total = 0;
                    $report_check_amount_total = 0;
                    $report_difference_total = 0;
                    foreach($get_reconci_data as $recon_key=>$recon_data)
                    {
                    ?>
                    <tr>
                           <td colspan="11" style="font-size:8px;font-weight:bold;text-align:left;"><?php echo strtoupper($recon_key);?></td>
                    </tr>
                    <?php
                    $category_trade_count = 0;
                    $category_gross_commission = 0;
                    $category_hold_commission = 0;
                    $category_total_commission = 0;
                    $category_check_amount = 0;
                    $category_difference = 0;
                    foreach($recon_data as $recon_sub_key=>$recon_sub_data)
                    {
                        $difference = $recon_sub_data['batch_check_amount']-$recon_sub_data['total_commission'];
                        $category_trade_count = $category_trade_count+$recon_sub_data['trade_count'];
                        $category_gross_commission = $category_gross_commission+$recon_sub_data['gross_commission'];
                        $category_hold_commission = $category_hold_commission+$recon_sub_data['total_hold_commission'];
                        $category_total_commission = $category_total_commission+$recon_sub_data['total_commission'];
                        $category_check_amount = $category_check_amount+$recon_sub_data['batch_check_amount'];
                        $category_difference = $category_difference+$difference;
                    ?>
                    <tr>
                       <td style="font-size:8px;font-weight:normal;text-align:center;"><?php echo $recon_sub_data['batch_number'];?></td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;"><?php echo date('m/d/Y',strtotime($recon_sub_data['batch_date']));?></td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;"><?php echo $recon_sub_data['batch_description'];?></td>
                       <td style="font-size:8px;font-weight:normal;text-align:center;"><?php echo $recon_sub_data['trade_count'];?></td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;"><?php echo number_format($recon_sub_data['gross_commission'],2);?></td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;"><?php echo number_format($recon_sub_data['total_hold_commission'],2);?></td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;"><?php echo number_format($recon_sub_data['total_commission'],2);?></td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;"><?php echo number_format($recon_sub_data['batch_check_amount'],2);?></td>
                       <td style="font-size:8px;font-weight:normal;text-align:right;"><?php echo number_format($difference,2);?></td>
                    </tr>
                    <?php
                    }
                    $report_trade_count_total = $report_trade_count_total+$category_trade_count;
                    $report_gross_commission_total = $report_gross_commission_total+$category_gross_commission;
                    $report_hold_commission_total = $report_hold_commission_total+$category_hold_commission;
                    $report_total_commission_total = $report_total_commission_total+$category_total_commission;
                    $report_check_amount_total = $report_check_amount_total+$category_check_amount;
                    $report_difference_total = $report_difference_total+$category_difference;
                    ?>
                
                    <tr style="background-color: #f1f1f1;">
                       <td style="font-size:8px;font-weight:bold;text-align:right;" colspan="3">* <?php echo strtoupper($recon_key);?> SUBTOTAL * </td>
                       <td style="font-size:8px;font-weight:bold;text-align:center;"><?php echo $category_trade_count;?></td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($category_gross_commission,2);?></td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($category_hold_commission,2);?></td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($category_total_commission,2);?></td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($category_check_amount,2);?></td>
                       <td style="font-size:8px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($category_difference,2);?></td>
                    </tr>
                <?php } ?>
                <tr style="background-color: #f1f1f1;">
                   <td style="font-size:8px;font-weight:bold;text-align:right;" colspan="3">REPORT TOTAL: </td>
                   <td style="font-size:8px;font-weight:bold;text-align:center;"><?php echo $report_trade_count_total;?></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_gross_commission_total,2);?></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_hold_commission_total,2);?></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_total_commission_total,2);?></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_check_amount_total,2);?></td>
                   <td style="font-size:8px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_difference_total,2);?></td>
                </tr>
            <?php 
            }
            else
            {
            ?>
            <tr>
                <td style="font-size:13px;font-weight:cold;text-align:center;" colspan="9">No Records Found.</td>
            </tr>
            <?php
            }?>
       </tbody>
       </table>
    <!-- ---------------------------------- -->
    <!-- PAYROLL SUMMARY REPORT (5) 4/30/22 -->
    <!-- ---------------------------------- -->
    <?php } else if($publish_report==5) { 
        $reportData = array();
        $payroll_id = isset($filter_array['payroll_id'])?$filter_array['payroll_id']:'';
        $get_payroll_upload = $instance_payroll->get_payroll_uploads($payroll_id);
        $payroll_date = date('m/d/Y', strtotime($get_payroll_upload['payroll_date']));
        $output_type = isset($filter_array['output_type'])?$filter_array['output_type']:'';
        // 5/6/22 Company Filter
        $instance_company = new manage_company();
        $company = isset($filter_array['company'])?$filter_array['company']:0;
        $companyData = ($company==0) ? ["company_name"=>"All Companies"]: $instance_company->select_company_by_id($company);
        
        $summaryData = $instance_payroll->select_current_payroll($payroll_id, 1, $company);
        $reportData = $instance_payroll->get_payroll_summary_report_data($payroll_id, $company);
        
         
        ?>
        <table border="0" width="100%">
                <tr>
                    <?php 
                    if(isset($system_logo) && $system_logo != '')
                    {?>
                        <td width="30%" align="left"><?php echo $img;?></td>
                    <?php } ?>
                    <td width="40%" style="font-size:16px;font-weight:bold;text-align:center;"><?php echo 'PAYROLL SUMMARY REPORT';?></td>
                    <?php
                    if(isset($system_company_name) && $system_company_name != '')
                    {?>
                        <td width="30%" style="font-size:12px;font-weight:bold;text-align:right;"><?php echo $system_company_name;?></td>
                    <?php
                    }?>
                </tr>
                <tr>
                    <td width="100%" colspan="3" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo $companyData['company_name'];?></td>
                </tr>
                <tr>
                    <td width="100%" colspan="3" style="font-size:14px;font-weight:bold;text-align:center;"><?php echo $payroll_date;?></td>
                </tr>
        </table>
        <table border="0" cellpadding="1" width="100%">
            <thead>
                <tr style="background-color: #f1f1f1;">
                    <td width="25%" style="text-align:center;"><h5>PRODUCT CATEGORY</h5></td>
                    <td width="15%" style="text-align:right; "><h5>TRADE COUNT</h5></td>
                    <td width="20%" style="text-align:right;"><h5>GROSS COMMISSION</h5></td>
                    <td width="20%" style="text-align:right;"><h5>% of GROSS</h5></td>
                    <td width="20%" style="text-align:right;"><h5>NET COMMISSION</h5></td>
                </tr>
            </thead>
            <tbody>
                <?php if($reportData != array()) {
                    $report_trade_count_total = 0;
                    $report_gross_commission_total = 0;
                    $report_net_commission_total = 0;
                    
                    foreach($reportData AS $dataKey=>$dataRow) {   
                        $pctOfGross = ($summaryData[0]['GROSS_COMMISSION']==0 ? 0 : round($dataRow['GROSS_COMMISSION']*100 /$summaryData[0]['GROSS_COMMISSION'], 0));
                    ?>
                        <tr>
                            <td style="font-size:12px;font-weight:normal;text-align:left;"><?php echo $dataRow['PRODUCT_CATEGORY'];?></td>
                            <td style="font-size:12px;font-weight:normal;text-align:right;"><?php echo number_format($dataRow['TRADE_COUNT'],0);?></td>
                            <td style="font-size:12px;font-weight:normal;text-align:right;"><?php echo number_format($dataRow['GROSS_COMMISSION'],2);?></td>
                            <td style="font-size:12px;font-weight:normal;text-align:right;"><?php echo (string)$pctOfGross."%";?></td>
                            <td style="font-size:12px;font-weight:normal;text-align:right;"><?php echo number_format($dataRow['NET_COMMISSION'],2);?></td>
                        </tr>
                        
                        <?php 
                        $report_trade_count_total += $dataRow['TRADE_COUNT'];
                        $report_gross_commission_total += $dataRow['GROSS_COMMISSION'];
                        $report_net_commission_total += $dataRow['NET_COMMISSION'];;
                    } ?>
                
                    <tr style="background-color: #f1f1f1;">
                       <td style="font-size:12px;font-weight:bold;text-align:right;">* TRANSACTION TOTALS *</td>
                       <td style="font-size:12px;font-weight:bold;text-align:right;"><?php echo number_format($report_trade_count_total,0);?></td>
                       <td style="font-size:12px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_gross_commission_total,2);?></td>
                       <td style="font-size:12px;font-weight:bold;text-align:right;"><?php echo '100%';?></td>
                       <td style="font-size:12px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($report_net_commission_total,2);?></td>
                    </tr>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:bold;text-align:center;" colspan="5">&nbsp</td>
                    </tr>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:bold;text-align:center;" colspan="3">&nbsp</td>
                        <td style="font-size:12px;font-weight:bold;text-align:center;" colspan="2">* PAYOUT SUMMARY *</td>
                    </tr>
                     <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:left;" colspan="3">&nbsp</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;">FINRA</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;"><?php echo $instance_payroll->payroll_accounting_format(-$summaryData[0]['FINRA_TOTAL'],2);?></td>
                    </tr>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:left;" colspan="3">&nbsp</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;">SIPC</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;"><?php echo $instance_payroll->payroll_accounting_format(-$summaryData[0]['SIPC_TOTAL'],2);?></td>
                    </tr>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:left;" colspan="2">&nbsp</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;" colspan="2">TAXABLE ADJUSTMENTS</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;"><?php echo $instance_payroll->payroll_accounting_format($summaryData[0]['TAXABLE_ADJUSTMENTS_TOTAL'],2);?></td>
                    </tr>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:left;" colspan="2">&nbsp</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;" colspan="2">NON-TAX ADJUSTMENTS</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;"><?php echo $instance_payroll->payroll_accounting_format($summaryData[0]['NON-TAXABLE_ADJUSTMENTS_TOTAL'],2);?></td>
                    </tr>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:left;" colspan="2">&nbsp</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;" colspan="2">OVERRIDES PAID</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;"><?php echo $instance_payroll->payroll_accounting_format($summaryData[0]['OVERRIDES_PAID_TOTAL'],2);?></td>
                    </tr>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:left;" colspan="2">&nbsp</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;" colspan="2">PRIOR BALANCES</td>
                        <td style="font-size:12px;font-weight:normal;font-style:italic;text-align:right;"><?php echo $instance_payroll->payroll_accounting_format($summaryData[0]['PRIOR_BALANCE_TOTAL'],2);?></td>
                    </tr>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:bold;text-align:left;" colspan="2">&nbsp</td>
                        <td style="font-size:12px;font-weight:bold;text-align:right;" colspan="2"><b>TOTAL PAYROLL</b></td>
                        <td style="font-size:12px;font-weight:bold;text-align:right;"><b><?php echo $instance_payroll->payroll_accounting_format($summaryData[0]['TOTAL_PAYROLL'],2);?></b></td>
                    </tr>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:bold;text-align:center;" colspan="5">&nbsp</td>
                    </tr>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:bold;text-align:left;" colspan="2">&nbsp</td>
                        <td style="font-size:12px;font-weight:bold;text-align:right;" colspan="2"><b>TOTAL CHECKS</b></td>
                        <td style="font-size:12px;font-weight:bold;text-align:right;"><b><?php echo $instance_payroll->payroll_accounting_format($summaryData[0]['TOTAL_CHECKS'],2);?></b></td>
                    </tr>
                    <tr style="background-color: #f1f1f1;">
                        <td style="font-size:12px;font-weight:bold;text-align:left;" colspan="2">&nbsp</td>
                        <td style="font-size:12px;font-weight:bold;text-align:right;" colspan="2"><b>BALANCES CARRIED FORWARD</b></td>
                        <td style="font-size:12px;font-weight:bold;text-align:right;"><b><?php echo $instance_payroll->payroll_accounting_format($summaryData[0]['TOTAL_CARRIED_FORWARD'],2);?></b></td>
                    </tr>
                <?php  } else { ?>
                    <tr>
                        <td style="font-size:13px;font-weight:cold;text-align:center;" colspan="9">No Records Found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
<?php }
?>
       