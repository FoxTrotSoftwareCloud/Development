<?php

class payroll extends db{
    
    public $errors = '';
    public $table = PAYROLL_UPLOAD;
    
    public function insert_update($data){
            
		$id = isset($data['id'])?$this->re_db_input($data['id']):0;
        $payroll_id = isset($data['payroll_id'])?$this->re_db_input($data['payroll_id']):0;
        
        $trade_number = isset($data['trade_number'])?$this->re_db_input($data['trade_number']):'';
        $trade_date = isset($data['trade_date'])?$this->re_db_input($data['trade_date']):'';
        if($trade_date != '')
        {
            $trade_date = date('Y-m-d',strtotime($trade_date));
        }
        $product = isset($data['product'])?$this->re_db_input($data['product']):'';
        $product_category = isset($data['product_category'])?$this->re_db_input($data['product_category']):'';
        $client_account_number = isset($data['client_account_number'])?$this->re_db_input($data['client_account_number']):'';
        $client_name = isset($data['client_name'])?$this->re_db_input($data['client_name']):'';
        $broker_name = isset($data['broker_name'])?$this->re_db_input($data['broker_name']):'';
        $quantity = isset($data['quantity'])?$this->re_db_input($data['quantity']):'';
        $price = isset($data['price'])?$this->re_db_input($data['price']):'';
        $investment_amount = isset($data['investment_amount'])?$this->re_db_input($data['investment_amount']):'';
        $commission_expired = isset($data['commission_expired'])?$this->re_db_input($data['commission_expired']):'';
        $charge = isset($data['charge'])?$this->re_db_input($data['charge']):'';
        $date_received = isset($data['date_received'])?$this->re_db_input($data['date_received']):'';
        if($date_received != '')
        {
            $date_received = date('Y-m-d',strtotime($date_received));
        }
        $commission_received = isset($data['commission_received'])?$this->re_db_input($data['commission_received']):'';
        $buy_sell = isset($data['buy_sell'])?$this->re_db_input($data['buy_sell']):'';
        $hold = isset($data['hold'])?$this->re_db_input($data['hold']):'';
        $hold_reason = isset($data['hold_reason'])?$this->re_db_input($data['hold_reason']):'';
        $cancel = isset($data['cancel'])?$this->re_db_input($data['cancel']):'';
        $branch = isset($data['branch'])?$this->re_db_input($data['branch']):'';
        
        if($id==0){
            
			$q = "INSERT INTO ".PAYROLL_REVIEW_MASTER." SET `trade_number`='".$trade_number."',`trade_date`='".$trade_date."' ,`product`='".$product."',`product_category`='".$product_category."',`client_account_number`='".$client_account_number."',`client_name`='".$client_name."',`broker_name`='".$broker_name."',`quantity`='".$quantity."',`price`='".$price."',`investment_amount`='".$investment_amount."',`commission_expired`='".$commission_expired."',`charge`='".$charge."',`date_received`='".$date_received."',`commission_received`='".$commission_received."',`buy_sell`='".$buy_sell."',`hold`='".$hold."',`hold_reason`='".$hold_reason."',`cancel`='".$cancel."',`branch`='".$branch."'".$this->insert_common_sql();
			$res = $this->re_db_query($q);
            
            if($res){
			    $_SESSION['success'] = INSERT_MESSAGE;
				return true;
			}
			else{
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		}
		else if($id>0){
		 
			$q = "UPDATE ".PAYROLL_REVIEW_MASTER." SET `trade_number`='".$trade_number."',`trade_date`='".$trade_date."' ,`product`='".$product."',`product_category`='".$product_category."',`client_account_number`='".$client_account_number."',`client_name`='".$client_name."',`broker_name`='".$broker_name."',`quantity`='".$quantity."',`price`='".$price."',`investment_amount`='".$investment_amount."',`commission_expired`='".$commission_expired."',`charge`='".$charge."',`date_received`='".$date_received."',`commission_received`='".$commission_received."',`buy_sell`='".$buy_sell."',`hold`='".$hold."',`hold_reason`='".$hold_reason."',`cancel`='".$cancel."',`branch`='".$branch."'".$this->update_common_sql()." WHERE `id`='".$id."'";
			$res = $this->re_db_query($q);
            
            if($res){
			    $_SESSION['success'] = UPDATE_MESSAGE;
				return true;
			}
			else{
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		}
	}
    public function upload_payroll($data){
            
		$clearing_business_cutoff_date = isset($data['clearing_business_cutoff_date'])?$this->re_db_input($data['clearing_business_cutoff_date']):'';
        $direct_business_cutoff_date = isset($data['direct_business_cutoff_date'])?$this->re_db_input($data['direct_business_cutoff_date']):'';
        
        	
        if($clearing_business_cutoff_date ==''){
			$this->errors = 'Please select clearing business cutoff date.';
		}
        else if($direct_business_cutoff_date ==''){
			$this->errors = 'Please select direct business cutoff date.';
		}
        if($this->errors!=''){
			return $this->errors;
		}
		else{
		  
            /*$q = "SELECT * FROM `".$this->table."` WHERE is_delete = 0 AND is_close = 0 AND is_calculate = 1 AND payroll_date != '0000-00-00'";
			$res = $this->re_db_query($q);
			$return = $this->re_db_num_rows($res);
			if($return>0){
				$this->errors = 'Payroll data already exist,Please clear current payroll !';
			}
			if($this->errors!=''){
				return $this->errors;
			}
		    else
            {*/
                $q = "INSERT INTO ".$this->table." SET `clearing_business_cutoff_date`='".date('Y-m-d',strtotime($clearing_business_cutoff_date))."',`direct_business_cutoff_date`='".date('Y-m-d',strtotime($direct_business_cutoff_date))."'".$this->insert_common_sql();
    			$res = $this->re_db_query($q);
                $last_inserted_id = $this->re_db_insert_id();
                
                $trades_array = $this->select_trades($direct_business_cutoff_date);
                foreach($trades_array as $key=>$val)
                {
                    $q = "INSERT INTO ".PAYROLL_REVIEW_MASTER." SET `payroll_id`='".$last_inserted_id."',`trade_number`='".$val['id']."',`trade_date`='".$val['trade_date']."' ,`product`='".$val['product']."',`product_category`='".$val['product_cate']."',`client_account_number`='".$val['client_number']."',`client_name`='".$val['client_name']."',`broker_name`='".$val['broker_name']."',`quantity`='".$val['units']."',`price`='".$val['shares']."',`investment_amount`='".$val['invest_amount']."',`commission_expired`='',`charge`='".$val['charge_amount']."',`date_received`='".date('Y-m-d',strtotime($val['commission_received_date']))."',`commission_received`='".$val['commission_received']."',`buy_sell`='".$val['buy_sell']."',`hold`='".$val['hold_commission']."',`hold_reason`='".$val['hold_resoan']."',`is_split`='".$val['split']."',`cancel`='".$val['cancel']."',`branch`='".$val['branch']."'".$this->insert_common_sql();
    				$res = $this->re_db_query($q);
                    
                    $q = "UPDATE ".TRANSACTION_MASTER." SET `is_payroll`='1',`payroll_id`='".$last_inserted_id."' ".$this->update_common_sql()." WHERE `id`='".$val['id']."'";
    				$res = $this->re_db_query($q);
                }
                if($res){
    			    $_SESSION['success'] = 'Payroll uploaded successfully.';
    				return true;
    			}
    			else{
    				$_SESSION['warning'] = UNKWON_ERROR;
    				return false;
    			}
            //}
        }
	}
    public function reverse_payroll(){
            
		$payroll_transactions_array = $this->select_payroll_transactions();
        if(count($payroll_transactions_array)<=0)
        {
            $this->errors = 'Payroll data does not exist!';
		}
        if($this->errors!=''){
			return $this->errors;
		}
        else
        {
            foreach($payroll_transactions_array as $key=>$val)
            {
                $q = "UPDATE ".TRANSACTION_MASTER." SET `is_payroll`='0',`is_reverse`='1',`payroll_id`='".$val['payroll_id']."' ".$this->update_common_sql()." WHERE `id`='".$val['trade_number']."'";
    		    $res = $this->re_db_query($q);
                
                $q = "UPDATE ".$this->table." SET `is_delete`='1' ".$this->update_common_sql()." WHERE `id`='".$val['payroll_id']."'";
        		$res = $this->re_db_query($q);
                
                $q = "UPDATE ".PAYROLL_REVIEW_MASTER." SET `is_delete`='1' ".$this->update_common_sql()." WHERE `payroll_id`='".$val['payroll_id']."'";
        		$res = $this->re_db_query($q);
            }
            if($res){
        	    $_SESSION['success'] = 'Payroll reversed successfully.';
        		return true;
        	}
        	else{
        		$_SESSION['warning'] = UNKWON_ERROR;
        		return false;
        	}
        }
    }
    public function calculate_payroll($data){
        
        $payroll_date = isset($data['payroll_date'])?$this->re_db_input($data['payroll_date']):'';
        
        if($payroll_date != '')
        {
            $payroll_date = date('Y-m-d',strtotime($payroll_date));
        }
        $get_uploaded_commissions = $this->select();
        if(count($get_uploaded_commissions)<=0)
        {
            $this->errors = 'Payroll data does not exist,Please upload commissions first.';
        }
        if($this->errors!=''){
			return $this->errors;
		}
        else
        {
            $step='';
            foreach($get_uploaded_commissions as $key_data=>$val_data)
            {
                $net_pay = 0;
                $gross_production = 0;
                $total_adjustments = 0;
                $both_total_adjustments = 0;
                $total_balance = 0;
                $broker = isset($val_data['broker_name'])?$val_data['broker_name']:0;
                $product_category = isset($val_data['product_category'])?$val_data['product_category']:0;
                $trade_number = isset($val_data['trade_number'])?$val_data['trade_number']:0;
                $commission_received_date = isset($val_data['date_received'])?$val_data['date_received']:'';
                if($broker>0)
                {
                    $q = "SELECT * FROM `".BROKER_PAYOUT_MASTER."` WHERE `is_delete`='0' AND `broker_id`='".$broker."'";
                    $res = $this->re_db_query($q);
                    if($this->re_db_num_rows($res)>0){
                        while($row = $this->re_db_fetch_array($res)){
                            
                            $charge_amount = isset($val_data['charge'])?$val_data['charge']:0;
                            $basis = isset($row['basis'])?$row['basis']:'';
                            $basis_amount = 0;
                            $calculation_detail = isset($row['calculation_detail'])?$row['calculation_detail']:'';
                            $charge_deduction = 0;
                            //$step.=0;
                            //$is_break=0;
                            
                            if($basis == 1) 
                            {
                                $basis_condition_on = isset($val_data['commission_received'])?$val_data['commission_received']:0;
                            }
                            else if($basis == 2)
                            {
                                $basis_condition_on = isset($val_data['investment_amount'])?$val_data['investment_amount']:0;
                            }
                            $basis_amount = isset($val_data['commission_received'])?$val_data['commission_received']:0;
                            $gross_production = $basis_amount;
                            
                            if($calculation_detail == 1)
                            {
                                $q = "SELECT * FROM `".BROKER_PAYOUT_GRID."` WHERE `is_delete`='0' AND `broker_id`='".$broker."'";
                				$res = $this->re_db_query($q);                            
                                if($this->re_db_num_rows($res)>0){
                                    $temp_get=true;
                                    while($row_s = $this->re_db_fetch_array($res)){
                                        if($temp_get)
                                        {
                                            $to_threshold = isset($row_s['to'])?$row_s['to']:'';
                                            if($to_threshold > 0)
                                            {
                                                $pending_basis_amount = $basis_amount-$to_threshold;
                                                $incremental_per = isset($row_s['per'])?$row_s['per']:0;
                                                if($basis_condition_on>$to_threshold)
                                                {//$step.=1;
                                                    $basis_amount = $to_threshold;
                                                    if($charge_deduction == 0)
                                                    {
                                                        $clearing_charge_deducted_from = isset($row['clearing_charge_deducted_from'])?$row['clearing_charge_deducted_from']:'';
                                
                                                        if($charge_amount<=($basis_amount*$incremental_per)/100)
                                                        {
                                                             if($clearing_charge_deducted_from==1)
                                                             {
                                                                 $net_pay = $net_pay+($basis_amount*$incremental_per)/100-$charge_amount;
                                                             }
                                                             else if($clearing_charge_deducted_from==2)
                                                             {
                                                                 $net_pay = $net_pay+($basis_amount-$charge_amount)*$incremental_per/100;
                                                             }
                                                             $charge_deduction = 1;
                                                        }
                                                        else
                                                        {
                                                            /*$check_firm_does_not_participate = $this->check_firm_does_not_participate();print_r($check_firm_does_not_participate);exit;
                                                            if(isset($firm_does_not_participate['firm_does_not_participate']) && $firm_does_not_participate['firm_does_not_participate']==1)
                                                            {
                                                                $net_pay = $basis_amount-$charge_amount;
                                                            }*/
                                                            $net_pay = $net_pay+$basis_amount-$charge_amount;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $net_pay = $net_pay+($basis_amount*$incremental_per)/100;
                                                    }
                                                    $basis_amount = $pending_basis_amount;
                                                }
                                                else if($basis_condition_on>0 && $basis_condition_on<=$to_threshold)
                                                {   
                                                    $temp_get=false;
                                                    //$step.=2;
                                                    if($charge_deduction == 0)
                                                    {
                                                        $clearing_charge_deducted_from = isset($row['clearing_charge_deducted_from'])?$row['clearing_charge_deducted_from']:'';
                                
                                                        if($charge_amount<=($basis_amount*$incremental_per)/100)
                                                        {
                                                             if($clearing_charge_deducted_from==1)
                                                             {
                                                                 $net_pay = $net_pay+($basis_amount*$incremental_per)/100-$charge_amount;                                                         
                                                             }
                                                             else if($clearing_charge_deducted_from==2)
                                                             {
                                                                 $net_pay = $net_pay+($basis_amount-$charge_amount)*$incremental_per/100;                                                         
                                                             }
                                                             $charge_deduction = 1;
                                                        }
                                                        else
                                                        {
                                                            /*$check_firm_does_not_participate = $this->check_firm_does_not_participate();print_r($check_firm_does_not_participate);exit;
                                                            if(isset($firm_does_not_participate['firm_does_not_participate']) && $firm_does_not_participate['firm_does_not_participate']==1)
                                                            {
                                                                $net_pay = $basis_amount-$charge_amount;
                                                            }*/
                                                            $net_pay = $net_pay+$basis_amount-$charge_amount;                                                    
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $net_pay = $net_pay+($basis_amount*$incremental_per)/100;                                                 
                                                    }                                            
                                                }
                                            }
                                        }
                                    }
                                }
                                else
                                {//$step.=3;
                                    $q = "SELECT * FROM `".PAYOUT_FIXED_RATES."` WHERE `is_delete`='0' AND `broker_id`='".$broker."' AND `category_id`='".$val_data['product_category']."'";
                                    $res = $this->re_db_query($q);
                                    if($this->re_db_num_rows($res)>0){
                                        while($row_s = $this->re_db_fetch_array($res)){
                            			   $deduction_per = isset($row_s['category_rates'])?$row_s['category_rates']:0;
                                           $clearing_charge_deducted_from = isset($row['clearing_charge_deducted_from'])?$row['clearing_charge_deducted_from']:'';
                                    
                                            if($charge_amount<=($basis_amount*$deduction_per)/100)
                                            {
                                                 if($clearing_charge_deducted_from==1)
                                                 {
                                                     $net_pay = ($basis_amount*$deduction_per)/100-$charge_amount;
                                                 }
                                                 else if($clearing_charge_deducted_from==2)
                                                 {
                                                     $net_pay = ($basis_amount-$charge_amount)*$deduction_per/100;
                                                 }
                                            }
                                            else
                                            {
                                                /*$check_firm_does_not_participate = $this->check_firm_does_not_participate();print_r($check_firm_does_not_participate);exit;
                                                if(isset($firm_does_not_participate['firm_does_not_participate']) && $firm_does_not_participate['firm_does_not_participate']==1)
                                                {
                                                    $net_pay = $basis_amount-$charge_amount;
                                                }*/
                                                $net_pay = $basis_amount-$charge_amount;
                                            }
                                        }
                                    }
                                }
                            }
                            else if($calculation_detail == 2)
                            {
                                $q = "SELECT * FROM `".BROKER_PAYOUT_GRID."` WHERE `is_delete`='0' AND `broker_id`='".$broker."' AND ".$basis_condition_on." BETWEEN `from` AND `to`";
                				$res = $this->re_db_query($q);
                                if($this->re_db_num_rows($res)>0){
                                    while($row_s = $this->re_db_fetch_array($res)){
                                        $deduction_per = isset($row_s['per'])?$row_s['per']:0;
                        			
                                        $clearing_charge_deducted_from = isset($row['clearing_charge_deducted_from'])?$row['clearing_charge_deducted_from']:'';
                                
                                        if($charge_amount<=($basis_amount*$deduction_per)/100)
                                        {
                                             if($clearing_charge_deducted_from==1)
                                             {
                                                 $net_pay = ($basis_amount*$deduction_per)/100-$charge_amount;
                                             }
                                             else if($clearing_charge_deducted_from==2)
                                             {
                                                 $net_pay = ($basis_amount-$charge_amount)*$deduction_per/100;
                                             }
                                        }
                                        else
                                        {
                                            /*$check_firm_does_not_participate = $this->check_firm_does_not_participate();print_r($check_firm_does_not_participate);exit;
                                            if(isset($firm_does_not_participate['firm_does_not_participate']) && $firm_does_not_participate['firm_does_not_participate']==1)
                                            {
                                                $net_pay = $basis_amount-$charge_amount;
                                            }*/
                                            $net_pay = $basis_amount-$charge_amount;
                                        }
                                    }
                                }
                                else
                                {
                                    $q = "SELECT * FROM `".PAYOUT_FIXED_RATES."` WHERE `is_delete`='0' AND `broker_id`='".$broker."' AND `category_id`='".$val_data['product_category']."'";
                                    $res = $this->re_db_query($q);
                                    if($this->re_db_num_rows($res)>0){
                                        while($row_s = $this->re_db_fetch_array($res)){
                            			   $deduction_per = isset($row_s['category_rates'])?$row_s['category_rates']:0;
                                           $clearing_charge_deducted_from = isset($row['clearing_charge_deducted_from'])?$row['clearing_charge_deducted_from']:'';
                                    
                                            if($charge_amount<=($basis_amount*$deduction_per)/100)
                                            {
                                                 if($clearing_charge_deducted_from==1)
                                                 {
                                                     $net_pay = ($basis_amount*$deduction_per)/100-$charge_amount;
                                                 }
                                                 else if($clearing_charge_deducted_from==2)
                                                 {
                                                     $net_pay = ($basis_amount-$charge_amount)*$deduction_per/100;
                                                 }
                                            }
                                            else
                                            {
                                                /*$check_firm_does_not_participate = $this->check_firm_does_not_participate();print_r($check_firm_does_not_participate);exit;
                                                if(isset($firm_does_not_participate['firm_does_not_participate']) && $firm_does_not_participate['firm_does_not_participate']==1)
                                                {
                                                    $net_pay = $basis_amount-$charge_amount;
                                                }*/
                                                $net_pay = $basis_amount-$charge_amount;
                                            }
                                        }
                                    }
                                }
                            }
                            else
                            {//$step.=4;
                                $q = "SELECT * FROM `".PAYOUT_FIXED_RATES."` WHERE `is_delete`='0' AND `broker_id`='".$broker."' AND `category_id`='".$val_data['product_category']."'";
                                $res = $this->re_db_query($q);
                                if($this->re_db_num_rows($res)>0){
                                    while($row_s = $this->re_db_fetch_array($res)){
                        			   $deduction_per = isset($row_s['category_rates'])?$row_s['category_rates']:0;
                                       $clearing_charge_deducted_from = isset($row['clearing_charge_deducted_from'])?$row['clearing_charge_deducted_from']:'';
                                
                                        if($charge_amount<=($basis_amount*$deduction_per)/100)
                                        {
                                             if($clearing_charge_deducted_from==1)
                                             {
                                                 $net_pay = ($basis_amount*$deduction_per)/100-$charge_amount;
                                             }
                                             else if($clearing_charge_deducted_from==2)
                                             {
                                                 $net_pay = ($basis_amount-$charge_amount)*$deduction_per/100;
                                             }
                                        }
                                        else
                                        {
                                            /*$check_firm_does_not_participate = $this->check_firm_does_not_participate();print_r($check_firm_does_not_participate);exit;
                                            if(isset($firm_does_not_participate['firm_does_not_participate']) && $firm_does_not_participate['firm_does_not_participate']==1)
                                            {
                                                $net_pay = $basis_amount-$charge_amount;
                                            }*/
                                            $net_pay = $basis_amount-$charge_amount;
                                        }
                                    }
                                }
                            }
                            
                        }
                        //check brokers adjustments:
                        $check_broker_adjustments = array();
                        $is_expired='';
                        if(isset($val_data['is_calculate']) && $val_data['is_calculate'] == 1)
                        {
                            $is_expired = 0;
                            $check_broker_adjustments = $this->select_adjustments_master($val_data['id'],0,$broker);
                        }
                        else
                        {
                            $check_broker_adjustments = $this->select_adjustments_master('',0,$broker);
                        }
                        //echo '<pre>';print_r($check_broker_adjustments);exit;
                        $total_adjustments = 0;
                        if(count($check_broker_adjustments)>0)
                        {
                            foreach($check_broker_adjustments as $key_amount=>$val_amount)
                            {
                                if($payroll_date>=$val_amount['pay_on'])
                                {
                                    $total_usage=$val_amount['total_usage'];
                                    if($val_amount['payable_trans_id']==$val_data['id'])
                                    {
                                        $total_usage=$total_usage+$val_amount['recalc_old_usage'];
                                    }
                                    $recalc_total_usage=$total_usage;
                                    if(isset($val_amount['pay_type']) && $val_amount['pay_type']== 3 && $val_amount['pay_amount'] != '')                                {
                                        $total_adjustments_user_manual = ($val_amount['adjustment_amount']*$val_amount['pay_amount'])/100;
                                        $total_adjustments = $total_adjustments+$total_adjustments_user_manual;
                                    }
                                    else
                                    {
                                        $total_adjustments = $total_adjustments+$val_amount['adjustment_amount'];
                                    }
                                    if($total_usage>0)
                                    {
                                        $total_adjustments = $total_adjustments*$total_usage;
                                    }
                                    
                                    $is_expired = 1;
                                    $adjustment_id = $val_amount['id'];
                                    if(isset($val_amount['recurring_type_id']) && $val_amount['recurring_type_id'] == 2)
                                    {
                                        $expired_adjustments = $this->renew_mid_month_adjustments($is_expired,$adjustment_id,$val_data['id'],0,$recalc_total_usage);
                                    }
                                    if(isset($val_amount['recurring_type_id']) && $val_amount['recurring_type_id'] == 3)
                                    {
                                        $expired_adjustments = $this->renew_end_month_adjustments($is_expired,$adjustment_id,$val_data['id'],0,$recalc_total_usage);
                                    }
                                    if(isset($val_amount['recurring_type_id']) && $val_amount['recurring_type_id'] == 4)
                                    {
                                        $expired_adjustments = $this->renew_semi_mid_month_adjustments($is_expired,$adjustment_id,$val_data['id'],0,$recalc_total_usage);
                                    }
                                    if(isset($val_amount['recurring_type_id']) && $val_amount['recurring_type_id'] == 5)
                                    {
                                        $expired_adjustments = $this->renew_semi_end_month_adjustments($is_expired,$adjustment_id,$val_data['id'],0,$recalc_total_usage);
                                    }
                                    if(isset($val_amount['recurring_type_id']) && $val_amount['recurring_type_id'] == 6)
                                    {
                                        $expired_adjustments = $this->renew_qua_mid_month_adjustments($is_expired,$adjustment_id,$val_data['id'],0,$recalc_total_usage);
                                    }
                                    if(isset($val_amount['recurring_type_id']) && $val_amount['recurring_type_id'] == 7)
                                    {
                                        $expired_adjustments = $this->renew_qua_end_month_adjustments($is_expired,$adjustment_id,$val_data['id'],0,$recalc_total_usage);
                                    }
                                }
                            }
                        }
                        $both_total_adjustments=$both_total_adjustments+$total_adjustments;
                        $net_pay = $net_pay+$total_adjustments;
                        
                        //total adjustments for all broker:
                        $check_adjustments = $this->select_adjustments_master('',0,'',1);
                        $total_adjustments = 0;
                        if(count($check_adjustments)>0)
                        {
                            foreach($check_adjustments as $key_amount=>$val_amount)
                            {
                                if($payroll_date>=$val_amount['pay_on'])
                                {
                                    if(isset($val_amount['pay_type']) && $val_amount['pay_type']== 3 && $val_amount['pay_amount'] != '') 
                                    {
                                        $total_adjustments_user_manual = ($val_amount['adjustment_amount']*$val_amount['pay_amount'])/100;
                                        $total_adjustments = $total_adjustments+$total_adjustments_user_manual;
                                    }
                                    else
                                    {
                                        $total_adjustments = $total_adjustments+$val_amount['adjustment_amount'];
                                    }
                                }
                            }
                        }
                        $both_total_adjustments=$both_total_adjustments+$total_adjustments;
                        $net_pay = $net_pay+$total_adjustments;
                        
                        //check brokers balance if balance available then add:
                        $check_broker_balance = $this->select_balances_master($broker);
                        $total_balance = 0;
                        if(count($check_broker_balance)>0)
                        {
                            foreach($check_broker_balance as $key_amount=>$val_amount)
                            {
                                $total_balance = $total_balance+$val_amount['balance_amount'];
                                $q = "UPDATE ".BROKER_BALANCES_MASTER." SET `is_delete`='1'".$this->update_common_sql()." WHERE `id`='".$val_amount['id']."'";
                                $res = $this->re_db_query($q);
                            }
                        }
                        $net_pay = $net_pay+$total_balance;
                    }
                    //Apply system rates and check with minimum check amount:
                    $check_minimum_check_amount=$this->check_minimum_check_amount();
                    
                    $finra = isset($check_minimum_check_amount['finra'])?$check_minimum_check_amount['finra']:0;
                    $sipc = isset($check_minimum_check_amount['sipc'])?$check_minimum_check_amount['sipc']:0;
                    $check_amount = isset($check_minimum_check_amount['minimum_check_amount'])?$check_minimum_check_amount['minimum_check_amount']:0;
                    $finra_sipc_amount = $finra+$sipc;
                    $total_system_rate = $net_pay*$finra_sipc_amount/100;
                    $net_pay = $net_pay-$total_system_rate;
                    
                    //Override rates of other broker deduct:
                    $get_override_rates = $this->get_override_rates($payroll_date,$broker,$product_category,2);
                    if(count($get_override_rates)>0)
                    {
                        $total_override_deduct = 0;
                        foreach($get_override_rates as $rate_key=>$rate_val)
                        {
                            $override_rate = $rate_val['per'];
                            $total_deduction = $net_pay*$override_rate/100;
                            $total_override_deduct = $total_override_deduct+$total_deduction;
                            
                            $q = "INSERT INTO ".PAYROLL_OVERRIDE_RATES." SET `payable_transaction_id` ='".$val_data['id']."', `transaction_id`='".$val_data['trade_number']."',`broker_id`='".$val_data['broker_name']."',`receiving_rep`='".$rate_val['rap']."',`rate`='".$total_deduction."' ".$this->insert_common_sql();
                            $res = $this->re_db_query($q);
                            
                        }
                        $net_pay = $net_pay-$total_override_deduct;
                    }
                    
                    //Override rates of current broker add:
                    $get_override_rates = $this->get_override_rates($payroll_date,$broker,$product_category,1);
                    if(count($get_override_rates)>0)
                    {
                        $total_override_add = 0;
                        foreach($get_override_rates as $rate_key=>$rate_val)
                        {
                            $override_rate = $rate_val['per'];
                            $total_addition = $net_pay*$override_rate/100;
                            $total_override_add = $total_override_add+$total_addition;
                            
                            $q = "INSERT INTO ".PAYROLL_OVERRIDE_RATES." SET `payable_transaction_id` ='".$val_data['id']."', `transaction_id`='".$val_data['trade_number']."',`broker_id`='".$val_data['broker_name']."',`receiving_rep`='".$rate_val['rap']."',`rate`='".$total_addition."' ".$this->insert_common_sql();
                            $res = $this->re_db_query($q);
                            
                        }
                        $net_pay = $net_pay+$total_override_add;
                    }
                    //apply split rate if split yes checked on trades:
                    if(isset($val_data['is_split']) && $val_data['is_split'] == 1)
                    {
                        //Split rates of other broker deduct:
                        $get_split_rates = $this->get_split_rates($payroll_date,$broker,2);
                        if(count($get_split_rates)>0)
                        {
                            $total_split_deduct = 0;
                            foreach($get_split_rates as $rate_key=>$rate_val)
                            {
                                $split_rate = $rate_val['rate'];
                                $total_deduction = $net_pay*$split_rate/100;
                                $total_split_deduct = $total_split_deduct+$total_deduction;
                                
                                $q = "INSERT INTO ".PAYROLL_SPLIT_RATES." SET `payable_transaction_id` ='".$val_data['id']."',`transaction_id`='".$val_data['trade_number']."',`broker_id`='".$val_data['broker_name']."',`split_broker`='".$rate_val['rap']."',`split_rate`='".$total_deduction."' ".$this->insert_common_sql();
                                $res = $this->re_db_query($q);
                                
                            }
                            $net_pay = $net_pay-$total_split_deduct;
                        }
                        
                        //Split rates of current broker add:
                        $get_split_rates = $this->get_split_rates($payroll_date,$broker,1);
                        if(count($get_split_rates)>0)
                        {
                            $total_split_add = 0;
                            foreach($get_split_rates as $rate_key=>$rate_val)
                            {
                                $split_rate = $rate_val['rate'];
                                $total_addition = $net_pay*$split_rate/100;
                                $total_split_add = $total_split_add+$total_addition;
                                
                                $q = "INSERT INTO ".PAYROLL_SPLIT_RATES." SET `payable_transaction_id` ='".$val_data['id']."',`transaction_id`='".$val_data['trade_number']."',`broker_id`='".$val_data['broker_name']."',`split_broker`='".$rate_val['rap']."',`split_rate`='".$total_addition."' ".$this->insert_common_sql();
                                $res = $this->re_db_query($q);
                                
                            }
                            $net_pay = $net_pay+$total_split_add;
                        }
                    }
                }
                
                if(isset($check_amount) && $net_pay<$check_amount)
                {
                    $q = "UPDATE ".PAYROLL_REVIEW_MASTER." SET `is_balance`='1',`commission_paid`='".$net_pay."',`adjustments`='".$both_total_adjustments."',`balance`='".$total_balance."',`finra`='".$finra."',`sipc`='".$sipc."',`check_amount`='".$check_amount."' ".$this->update_common_sql()." WHERE `id`='".$val_data['id']."'";
                    $res = $this->re_db_query($q);
                    
                    $q = "UPDATE ".PAYROLL_UPLOAD." SET `is_calculate`='1',`payroll_date`='".$payroll_date."'".$this->update_common_sql()." WHERE `id`='".$val_data['payroll_id']."'";
                    $res = $this->re_db_query($q);
                }
                else
                {
                    $q = "UPDATE ".PAYROLL_REVIEW_MASTER." SET `is_balance`='0',`commission_paid`='".$net_pay."',`adjustments`='".$both_total_adjustments."',`balance`='".$total_balance."',`finra`='".$finra."',`sipc`='".$sipc."',`check_amount`='".$check_amount."' ".$this->update_common_sql()." WHERE `id`='".$val_data['id']."'";
                    $res = $this->re_db_query($q);
                    
                    $q = "UPDATE ".PAYROLL_UPLOAD." SET `is_calculate`='1',`payroll_date`='".$payroll_date."'".$this->update_common_sql()." WHERE `id`='".$val_data['payroll_id']."'";
                    $res = $this->re_db_query($q);
                }
            }
            if(isset($res) && $res != ''){
        	    $_SESSION['success'] = 'Payroll calculated successfully.';
        		return true;
        	}
        	else{
        		$_SESSION['warning'] = UNKWON_ERROR;
        		return false;
        	}
        }
        
    }
    public function payroll_close(){
    
		$q = "UPDATE `".PAYROLL_UPLOAD."` SET `is_close`='1' WHERE `is_close`='0' and `is_calculate`='1' ";
		$res = $this->re_db_query($q);
        
        
        $get_prior_payrolls = $this->get_brokers_payroll();
        if(count($get_prior_payrolls)>0)
        {
            foreach($get_prior_payrolls as $key_prior_payrolls=>$val_prior_payrolls)
            {
                $broker = $val_prior_payrolls['broker_name'];
                $instance_broker = new broker_master();
                $get_broker_data = $instance_broker->edit($broker);
                
                $q = "UPDATE ".PRIOR_PAYROLL_MASTER." SET `is_delete`='1'".$this->update_common_sql()." WHERE `rep_name`='".$broker."'";
                $res = $this->re_db_query($q);
                
                $q = "INSERT INTO ".PRIOR_PAYROLL_MASTER." SET `payroll_date`='".$val_prior_payrolls['payroll_date']."',`rep_number`='".$get_broker_data['id']."',`clearing_number`='".$get_broker_data['fund']."',`rep_name`='".$get_broker_data['id']."',`gross_production`='".$val_prior_payrolls['gross_commissions']."',`check_amount`='".$val_prior_payrolls['prior_check_amounts']."' ".$this->insert_common_sql();
                $res = $this->re_db_query($q);
            }
        }
        $get_broker_balance = $this->get_brokers_balance();
        if(count($get_broker_balance)>0)
        {
            foreach($get_broker_balance as $key_balance=>$val_balance)
            {
                $broker = $val_balance['broker_name'];
                $instance_broker = new broker_master();
                $get_broker_data = $instance_broker->edit($broker);
                
                $q = "UPDATE ".BROKER_BALANCES_MASTER." SET `is_delete`='1'".$this->update_common_sql()." WHERE `broker_name`='".$broker."'";
                $res = $this->re_db_query($q);
                
                $q = "INSERT INTO ".BROKER_BALANCES_MASTER." SET `broker_number`='".$get_broker_data['id']."',`broker_name`='".$get_broker_data['id']."',`clearing_number`='".$get_broker_data['fund']."',`balance_amount`='".$val_balance['broker_balance_amounts']."' ".$this->insert_common_sql();
                $res = $this->re_db_query($q);
                
            }
        }
        if($res){
		    $_SESSION['success'] = "Payroll closed successfully";
			return true;
		}
		else{
		    $_SESSION['warning'] = UNKWON_ERROR;
			return false;
		}
    	
    }
    public function insert_update_adjustment_master($data){
            
		$id = isset($data['id'])?$this->re_db_input($data['id']):0;
        
        $adjustment_amount = isset($data['adjustment_amount'])?$this->re_db_input($data['adjustment_amount']):0;
        $date = isset($data['date'])?$this->re_db_input($data['date']):'';
        $pay_date = isset($data['pay_date'])?$this->re_db_input($data['pay_date']):'';
        $account = isset($data['account'])?$this->re_db_input($data['account']):'';
        $expire_date = isset($data['expire_date'])?$this->re_db_input($data['expire_date']):'';
        $payroll_category = isset($data['payroll_category'])?$this->re_db_input($data['payroll_category']):'';
        $taxable_adjustment = isset($data['taxable_adjustment'])?$this->re_db_input($data['taxable_adjustment']):0;
        $broker = isset($data['broker'])?$this->re_db_input($data['broker']):0;
        if($broker==2)
        {
            $broker_number = isset($data['broker_number'])?$this->re_db_input($data['broker_number']):'';
            $broker_name = isset($data['broker_name'])?$this->re_db_input($data['broker_name']):'';
        }
        else
        {
            $broker_number = '';
            $broker_name = '';
        }
        $recurring = isset($data['recurring'])?$this->re_db_input($data['recurring']):0;
        if($recurring==1)
        {
            $recurring_type = isset($data['recurring_type'])?$this->re_db_input($data['recurring_type']):'';
        }
        else
        {
            $recurring_type = '';
        }
        $description = isset($data['description'])?$this->re_db_input($data['description']):'';
        $pay_type = isset($data['pay_type'])?$this->re_db_input($data['pay_type']):'';
        $pay_amount = isset($data['pay_amount'])?$this->re_db_input($data['pay_amount']):'';
        
        if($id==0){
                
			 $q = "INSERT INTO ".PAYROLL_ADJUSTMENTS_MASTER." SET `adjustment_amount`='".$adjustment_amount."',`date`='".date('Y-m-d',strtotime($date))."',`pay_on`='".date('Y-m-d',strtotime($pay_date))."',`gl_account`='".$account."',
            `expire`='".date('Y-m-d',strtotime($expire_date))."',`category`='".$payroll_category."',`taxable_adjustment`='".$taxable_adjustment."',`broker`='".$broker."',`broker_number`='".$broker_number."',`broker_name`='".$broker_name."',`recurring`='".$recurring."',`recurring_type`='".$recurring_type."',`description`='".$description."',`pay_type`='".$pay_type."',`pay_amount`='".$pay_amount."'".$this->insert_common_sql();
			$res = $this->re_db_query($q);
            
            if($res){
			    $_SESSION['success'] = INSERT_MESSAGE;
				return true;
			}
			else{
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		}
		else if($id>0){
		 
			$q = "UPDATE ".PAYROLL_ADJUSTMENTS_MASTER." SET `adjustment_amount`='".$adjustment_amount."',`date`='".date('Y-m-d',strtotime($date))."',`pay_on`='".date('Y-m-d',strtotime($pay_date))."',`gl_account`='".$account."',
            `expire`='".date('Y-m-d',strtotime($expire_date))."',`category`='".$payroll_category."',`taxable_adjustment`='".$taxable_adjustment."',`broker`='".$broker."',`broker_number`='".$broker_number."',`broker_name`='".$broker_name."',`recurring`='".$recurring."',`recurring_type`='".$recurring_type."',`description`='".$description."',`pay_type`='".$pay_type."',`pay_amount`='".$pay_amount."'".$this->update_common_sql()." WHERE `id`='".$id."'";
			$res = $this->re_db_query($q);
            
            if($res){
			    $_SESSION['success'] = UPDATE_MESSAGE;
				return true;
			}
			else{
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		}
	}
    public function upload_adjustments($data){
        
        $upload_type = isset($data['upload_type'])?$this->re_db_input($data['upload_type']):'';
        $upload_adjustments = isset($_FILES['upload_adjustments'])?$_FILES['upload_adjustments']:array();
        $header = 1;
        
        $filename=$upload_adjustments["tmp_name"];	
        $get_file_data_array = array();
        
    	 if($upload_adjustments["size"] > 0)
    	 {
    	  	$file = fopen($filename, "r");
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
            {
                if($upload_type==1)
                {
                    if($header == 1)
                    {
                        if(isset($getData[0]) && $getData[0] == 'Rep Foxtrot #' && isset($getData[1]) && $getData[1] == 'Date' && isset($getData[2]) && $getData[2] == 'Category' && isset($getData[3]) && $getData[3] == 'Taxable Amount' && isset($getData[4]) && $getData[4] == 'Non-Taxable' && isset($getData[5]) && $getData[5] == 'Advance' && isset($getData[6]) && $getData[6] == 'Description' && isset($getData[7]) && $getData[7] == 'GL Acct#' && isset($getData[8]) && $getData[8] == 'Frequency\Recurring* (see Frequency Codes tab)' && isset($getData[9]) && $getData[9] == 'Pay On' && isset($getData[10]) && $getData[10] == 'Pay Until')
                        {
                            $header = 0;
                            continue;
                        }
                        else
                        {
                            $this->errors = 'Please select valid format of adjustments csv.';
                        }
                    }
                    if($this->errors!=''){
            			return $this->errors;
            		}
        		    else
                    {
                        if($header == 0)
                        {
                            if(isset($getData[3]) && $getData[3] != '')
                            {
                                $taxable_adjustment = 1;
                                $adjustment_amount = $getData[3];   
                            }
                            else
                            {
                                $taxable_adjustment = 0;
                                $adjustment_amount = $getData[4];  
                            }
                            if(isset($getData[0]) && $getData[0] == '')
                            {
                                $broker = 1;
                            }
                            else{
                                $broker = 2;
                            }
                            if(isset($getData[8]) && $getData[8] != ''){
                                $recurring = 1;
                            }
                            else{
                                $recurring = 0;
                            }
                            
                            //check payroll category if not then add
                            $category = '';
                            if(isset($getData[2]) && $getData[2] != ''){
            					$q = "SELECT * FROM `".PAYROLL_TYPE."` WHERE `is_delete`='0' AND `type`='".$getData[2]."' ";
                				$res = $this->re_db_query($q);
                                if($this->re_db_num_rows($res)>0){
                                    while($row = $this->re_db_fetch_array($res)){
                        			    $category = $row['id'];
                        			}
                                }
                                else
                                {
                                    $q = "INSERT INTO `".PAYROLL_TYPE."` SET `type`='".$getData[2]."' ".$this->insert_common_sql();
            						$res = $this->re_db_query($q);
                                    $category = $this->re_db_insert_id();
                                }
                			}
                            
                            $q = "INSERT INTO ".PAYROLL_ADJUSTMENTS_MASTER." SET `adjustment_amount`='".$adjustment_amount."',`date`='".date('Y-m-d',strtotime($getData[1]))."',`pay_on`='".date('Y-m-d',strtotime($getData[9]))."',`gl_account`='".$getData[7]."',
                        `expire`='".date('Y-m-d',strtotime($getData[10]))."',`category`='".$category."',`taxable_adjustment`='".$taxable_adjustment."',`broker`='".$broker."',`broker_number`='".$getData[0]."',`broker_name`='".$getData[0]."',`recurring`='".$recurring."',`recurring_type`='".$getData[8]."',`description`='".$getData[6]."',`pay_type`='',`pay_amount`=''".$this->insert_common_sql();
                			$res = $this->re_db_query($q);
                        }
                    }
                 }
                 if($upload_type==2)
                 {
                    if($header == 1)
                    {
                        if(isset($getData[3]) && $getData[3] == 'Amount' && isset($getData[1]) && $getData[1] == 'Category' && isset($getData[8]) && $getData[8] == 'Charge Description' && isset($getData[16]) && $getData[16] == 'Transaction Date' && isset($getData[0]) && $getData[0] == 'Posted')
                            {
                                $header = 0;
                                continue;
                            }
                            else
                            {
                                $this->errors = 'Please select valid format of finra ebill csv.';
                            }
                    }
                    if($this->errors!=''){
            			return $this->errors;
            		}
        		    else
                    {
                        if($header == 0)
                        {
                            $broker = 1;
                            $broker_number = '';
                            $broker_name = '';
                            
                            if(isset($getData[13]) && $getData[13] != ''){
                                $q = "SELECT * FROM `".BROKER_MASTER."` WHERE `is_delete`='0' AND `crd`='".$getData[13]."' ";
                				$res = $this->re_db_query($q);
                                if($this->re_db_num_rows($res)>0){
                                    while($row = $this->re_db_fetch_array($res)){
                        			    $broker = 2;
                                        $broker_number = $row['id'];
                                        $broker_name = $row['id'];                        			
                                    }
                                }
                            
                            }
                            $category = '';
                            if(isset($getData[1]) && $getData[1] != ''){
            					$q = "SELECT * FROM `".PAYROLL_TYPE."` WHERE `is_delete`='0' AND `type`='".$getData[1]."' ";
                				$res = $this->re_db_query($q);
                                if($this->re_db_num_rows($res)>0){
                                    while($row = $this->re_db_fetch_array($res)){
                        			    $category = $row['id'];
                        			}
                                }
                                else
                                {
                                    $q = "INSERT INTO `".PAYROLL_TYPE."` SET `type`='".$getData[1]."' ".$this->insert_common_sql();
            						$res = $this->re_db_query($q);
                                    $category = $this->re_db_insert_id();
                                }
                			}
            				
                            
                            $q = "INSERT INTO ".PAYROLL_ADJUSTMENTS_MASTER." SET `adjustment_amount`='".$getData[3]."',`date`='".date('Y-m-d',strtotime($getData[16]))."',`pay_on`='".date('Y-m-d',strtotime($getData[0]))."',`gl_account`='',
                        `expire`='',`category`='".$category."',`taxable_adjustment`='',`broker`='".$broker."',`broker_number`='".$broker_number."',`broker_name`='".$broker_name."',`recurring`='',`recurring_type`='',`description`='".$getData[8]."',`pay_type`='',`pay_amount`=''".$this->insert_common_sql();
                			$res = $this->re_db_query($q);
                        }
                    }
                 }
             }
             if(isset($res) && $res != ''){
			      $_SESSION['success'] = INSERT_MESSAGE;
			  	  return true;
			 }
			 else{
				  $_SESSION['warning'] = UNKWON_ERROR;
				  return false;
			 }
                    
             
         }
    }
    public function insert_update_balances_master($data){
            
		$id = isset($data['id'])?$this->re_db_input($data['id']):0;
        
        $broker_number = isset($data['broker_number'])?$this->re_db_input($data['broker_number']):0;
        $broker_name = isset($data['broker_name'])?$this->re_db_input($data['broker_name']):'';
        $clearing_number = isset($data['clearing_number'])?$this->re_db_input($data['clearing_number']):'';
        $balance_amount = isset($data['balance_amount'])?$this->re_db_input($data['balance_amount']):'';
        
        if($id==0){
                
			 $q = "INSERT INTO ".BROKER_BALANCES_MASTER." SET `broker_number`='".$broker_number."',`broker_name`='".$broker_name."',`clearing_number`='".$clearing_number."',`balance_amount`='".$balance_amount."'".$this->insert_common_sql();
			$res = $this->re_db_query($q);
            
            if($res){
			    $_SESSION['success'] = INSERT_MESSAGE;
				return true;
			}
			else{
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		}
		else if($id>0){
		 
			$q = "UPDATE ".BROKER_BALANCES_MASTER." SET `broker_number`='".$broker_number."',`broker_name`='".$broker_name."',`clearing_number`='".$clearing_number."',`balance_amount`='".$balance_amount."'".$this->update_common_sql()." WHERE `id`='".$id."'";
			$res = $this->re_db_query($q);
            
            if($res){
			    $_SESSION['success'] = UPDATE_MESSAGE;
				return true;
			}
			else{
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		}
	}
    public function insert_update_prior_payrolls_master($data){
            
		$id = isset($data['id'])?$this->re_db_input($data['id']):0;
        
        $payroll_date = isset($data['payroll_date'])?$this->re_db_input($data['payroll_date']):'';
        $rep_number = isset($data['rep_number'])?$this->re_db_input($data['rep_number']):'';
        $clearing_number = isset($data['clearing'])?$this->re_db_input($data['clearing']):'';
        $rep_name = isset($data['rep_name'])?$this->re_db_input($data['rep_name']):'';
        $gross_production = isset($data['gross_production'])?$this->re_db_input($data['gross_production']):'';
        $check_amount = isset($data['check_amount'])?$this->re_db_input($data['check_amount']):'';
        $net_production = isset($data['net_production'])?$this->re_db_input($data['net_production']):'';
        $adjustments = isset($data['adjustments'])?$this->re_db_input($data['adjustments']):'';
        $net_earnings = isset($data['net_earnings'])?$this->re_db_input($data['net_earnings']):'';
        
        if($id==0){
                
			 $q = "INSERT INTO ".PRIOR_PAYROLL_MASTER." SET `payroll_date`='".date('Y-m-d',strtotime($payroll_date))."',`rep_number`='".$rep_number."',`clearing_number`='".$clearing_number."',`rep_name`='".$rep_name."',`gross_production`='".$gross_production."',`check_amount`='".$check_amount."',`net_production`='".$net_production."',`adjustments`='".$adjustments."',`net_earnings`='".$net_earnings."'".$this->insert_common_sql();
			$res = $this->re_db_query($q);
            
            if($res){
			    $_SESSION['success'] = INSERT_MESSAGE;
				return true;
			}
			else{
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		}
		else if($id>0){
		 
			$q = "UPDATE ".PRIOR_PAYROLL_MASTER." SET `payroll_date`='".date('Y-m-d',strtotime($payroll_date))."',`rep_number`='".$rep_number."',`clearing_number`='".$clearing_number."',`rep_name`='".$rep_name."',`gross_production`='".$gross_production."',`check_amount`='".$check_amount."',`net_production`='".$net_production."',`adjustments`='".$adjustments."',`net_earnings`='".$net_earnings."'".$this->update_common_sql()." WHERE `id`='".$id."'";
			$res = $this->re_db_query($q);
            
            if($res){
			    $_SESSION['success'] = UPDATE_MESSAGE;
				return true;
			}
			else{
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		}
   }
   public function edit_review_payroll($id){
		$return = array();
		$q = "SELECT `trn`.*
				FROM ".PAYROLL_REVIEW_MASTER." AS `trn`
                WHERE `trn`.`is_delete`='0' AND `trn`.`id`='".$id."'";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_array($res);
        }
		return $return;
   }
   public function edit_review_trade_overrides($id,$transaction_id){
		$return = array();
		$q = "SELECT `pto`.*
				FROM ".PAYROLL_TRADE_OVERRIDES." AS `pto`
                WHERE `pto`.`is_delete`='0' AND `pto`.`transaction_id`='".$transaction_id."'";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
			while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
		return $return;
   }
   public function edit_review_trade_splits($id,$transaction_id){
		$return = array();
		$q = "SELECT `pts`.*
				FROM ".PAYROLL_TRADE_SPLITS." AS `pts`
                WHERE `pts`.`is_delete`='0' AND `pts`.`transaction_id`='".$transaction_id."'";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
			while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
		return $return;
   }
   public function edit_adjustments_master($id){
		$return = array();
		$q = "SELECT `ad`.*
				FROM ".PAYROLL_ADJUSTMENTS_MASTER." AS `ad`
                WHERE `ad`.`is_delete`='0' AND `ad`.`id`='".$id."'";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_array($res);
        }
		return $return;
   }
   public function edit_balances_master($id){
		$return = array();
		$q = "SELECT `bb`.*
				FROM ".BROKER_BALANCES_MASTER." AS `bb`
                WHERE `bb`.`is_delete`='0' AND `bb`.`id`='".$id."'";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_array($res);
        }
		return $return;
   }
   public function edit_prior_payrolls_master($id){
		$return = array();
		$q = "SELECT `pr`.*
				FROM ".PRIOR_PAYROLL_MASTER." AS `pr`
                WHERE `pr`.`is_delete`='0' AND `pr`.`id`='".$id."'";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_array($res);
        }
		return $return;
   }
   public function select($is_listing=0){
    	$return = array();
        $con='';
        
        if($is_listing == 1)
        {
            $con.= " AND `up`.`is_calculate` > 0";
        }
    	
    	$q = "SELECT `up`.*,`pt`.*,`bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname,`cl`.first_name as client_firstname,`cl`.last_name as client_lastname
                FROM `".PAYROLL_UPLOAD."` AS `up`
    			LEFT JOIN `".PAYROLL_REVIEW_MASTER."` AS `pt` on `pt`.`payroll_id`= `up`.`id`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `pt`.`broker_name`
                LEFT JOIN `".CLIENT_MASTER."` as `cl` on `cl`.`id` = `pt`.`client_name`
                WHERE `up`.`is_delete`='0' and `up`.`is_close` = 0 ".$con."
                ORDER BY `pt`.`id` ASC";
                
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
    	return $return;
  }
  public function get_override_rates($payroll_date='',$broker='',$product_category='',$current='')
  {
        $return = array();
        $con='';
        
        if($broker>0 && $current == 2)
        {
            $con.= " AND `ovr`.`broker_id` = '".$broker."'";
        }
        if($broker>0 && $current == 1)
        {
            $con.= " AND `ovr`.`rap` = '".$broker."'";
        }
        if($product_category>0)
        {
            $con.= " AND (`ovr`.`product_category` = '".$product_category."' or `ovr`.`product_category` = '0')";
        }
        if($payroll_date != '')
        {
            $con.= " AND '".date('Y-m-d',strtotime($payroll_date))."' BETWEEN `ovr`.`from` AND `ovr`.`to` ";
        }
    	$q = "SELECT `ovr`.*
    			FROM `".BROKER_MASTER."` AS `bm`
                LEFT JOIN `".BROKER_PAYOUT_OVERRIDE."` as `ovr` on `ovr`.`broker_id` = `bm`.`id`
                WHERE `ovr`.`is_delete`='0' ".$con."
                ORDER BY `ovr`.`id` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
    	return $return;
  }
  public function get_split_rates($payroll_date='',$broker='',$current='')
  {
        $return = array();
        $con='';
        
        if($broker>0 && $current == 2)
        {
            $con.= " AND `spl`.`broker_id` = '".$broker."'";
        }
        if($broker>0 && $current == 1)
        {
            $con.= " AND `spl`.`rap` = '".$broker."'";
        }
        if($payroll_date != '')
        {
            $con.= " AND '".date('Y-m-d',strtotime($payroll_date))."' BETWEEN `spl`.`start` AND `spl`.`until` ";
        }
    	$q = "SELECT `spl`.*
    			FROM `".BROKER_MASTER."` AS `bm`
                LEFT JOIN `".BROKER_PAYOUT_SPLIT."` as `spl` on `spl`.`broker_id` = `bm`.`id`
                WHERE `spl`.`is_delete`='0' ".$con."
                ORDER BY `spl`.`id` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
    	return $return;
  }
  public function get_brokers_payroll($broker=''){
    	$return = array();
        $con='';
        
        if($broker != '')
        {
            $con.= " AND `pt`.`broker_name` = '".$broker."'";
        }
    	
    	$q = "SELECT `up`.*, sum(`pt`.commission_received) as gross_commissions,sum(`pt`.commission_paid) as prior_check_amounts,`pt`.*,`bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname,`cl`.first_name as client_firstname,`cl`.last_name as client_lastname
                FROM `".PAYROLL_UPLOAD."` AS `up`
    			LEFT JOIN `".PAYROLL_REVIEW_MASTER."` AS `pt` on `pt`.`payroll_id` = `up`.`id`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `pt`.`broker_name`
                LEFT JOIN `".CLIENT_MASTER."` as `cl` on `cl`.`id` = `pt`.`client_name`
                WHERE `up`.`is_delete`='0' and `up`.`is_calculate`='1' and `up`.`is_close`='1' and `pt`.`is_balance`='0' ".$con."
                GROUP BY `pt`.`broker_name` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
    	return $return;
  } 
  public function get_brokers_balance($broker=''){
    	$return = array();
        $con='';
        
        if($broker != '')
        {
            $con.= " AND `pt`.`broker_name` = '".$broker."'";
        }
    	
    	$q = "SELECT sum(`pt`.commission_received) as gross_commissions,sum(`pt`.commission_paid) as broker_balance_amounts,`pt`.*,`bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname,`cl`.first_name as client_firstname,`cl`.last_name as client_lastname
                FROM `".PAYROLL_UPLOAD."` AS `up`
    			LEFT JOIN `".PAYROLL_REVIEW_MASTER."` AS `pt` on `pt`.`payroll_id` = `up`.`id`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `pt`.`broker_name`
                LEFT JOIN `".CLIENT_MASTER."` as `cl` on `cl`.`id` = `pt`.`client_name`
                WHERE `up`.`is_delete`='0' and `up`.`is_calculate`='1' and `up`.`is_close`='1' and `pt`.`is_balance`='1' ".$con."
                GROUP BY `pt`.`broker_name` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
    	return $return;
  } 
  public function select_adjustments_master($payable_trans_id='',$is_expired='',$broker='',$all_brokers=''){
    	$return = array();
        $con='';
        
        if($broker>0)
        {
            $con.=" AND `pa`.`broker`='2' AND `pa`.`broker_number`='".$broker."'";
        }
        if($all_brokers>0)
        {
            $con.=" AND `pa`.`broker`='1'";
        }
        if($is_expired!='')
        {
            $con.=" AND `pa`.`is_expired`='".$is_expired."'";
        }
        if($payable_trans_id>0)
        {
            $con.=" or `pa`.`payable_trans_id`='".$payable_trans_id."'";
        }
        
    	$q = "SELECT `pa`.*,`pa`.`recurring_type` as recurring_type_id ,`bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname,`rt`.`name` as recurring_type,`pt`.`type` as category
    			FROM `".PAYROLL_ADJUSTMENTS_MASTER."` AS `pa`
                LEFT JOIN `".RECURRING_TYPE_MASTER."` as `rt` on `rt`.`id` = `pa`.`recurring_type` 
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `pa`.`broker_name`
                LEFT JOIN `".PAYROLL_TYPE."` as `pt` on `pt`.`id` = `pa`.`category`
                WHERE `pa`.`is_delete`='0' ".$con."
                ORDER BY `pa`.`id` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
    	return $return;
  } 
  public function select_balances_master($broker=''){
    	$return = array();
        $con='';
        
        if($broker>0)
        {
            $con.=" AND `bb`.`broker_number`='".$broker."'";
        }
        
    	$q = "SELECT `bb`.*,`bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname
    			FROM `".BROKER_BALANCES_MASTER."` AS `bb`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `bb`.`broker_name`
                WHERE `bb`.`is_delete`='0' ".$con."
                ORDER BY `bb`.`id` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
    	return $return;
  } 
  public function select_prior_payrolls_master($broker=''){
    	$return = array();
        $con='';
        
        if($broker>0)
        {
            $con.=" AND `pr`.`rep_name`='".$broker."'";
        }
        
    	$q = "SELECT `pr`.*,`bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname
    			FROM `".PRIOR_PAYROLL_MASTER."` AS `pr`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `pr`.`rep_name`
                WHERE `pr`.`is_delete`='0' ".$con."
                ORDER BY `pr`.`id` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
    	return $return;
  } 
   public function check_minimum_check_amount(){
    	$return = array();
    	
    	$q = "SELECT `sc`.*
    			FROM `".SYSTEM_CONFIGURATION."` AS `sc`
                WHERE `sc`.`is_delete`='0'
                ORDER BY `sc`.`id` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     $return = $row;
    		}
        }
    	return $return;
  } 
  public function select_trades($commission_received_date){
    	$return = array();
    	
        if($commission_received_date != '')
        {
            $q = "SELECT `trans`.*,`bt`.id as batch_number,`cl`.first_name as client_firstname,`cl`.last_name as client_lastname,`bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname
    			FROM `".TRANSACTION_MASTER."` AS `trans`
                LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `trans`.`batch`
                LEFT JOIN `".CLIENT_MASTER."` as `cl` on `cl`.`id` = `trans`.`client_name`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `trans`.`broker_name`
                WHERE `trans`.`is_delete`='0' and `trans`.`hold_commission`!='1' and `trans`.`is_payroll`='0' and `trans`.`commission_received_date`<='".date('Y-m-d',strtotime($commission_received_date))."' and `trans`.`commission_received_date`!='0000-00-00 00:00:00'
                ORDER BY `trans`.`id` ASC";
        	$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
        		while($row = $this->re_db_fetch_array($res)){
        		  
        		     $transaction_id = $row['id'];
                     $row['splits_rate']=$this->select_trade_splits($transaction_id);
                     $row['overrides_rate']=$this->select_trade_overrides($transaction_id);
        		     array_push($return,$row);
                     
        		}
            }
        }
    	return $return;
  } 
  public function select_trade_splits($transaction_id){
    	$return = array();
    	
        $q = "SELECT `ts`.*
			FROM `".TRANSACTION_TRADE_SPLITS."` AS `ts`
            WHERE `ts`.`is_delete`='0' and `ts`.`transaction_id`='".$transaction_id."'
            ORDER BY `ts`.`id` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
        return $return;
  }
  public function select_trade_overrides($transaction_id){
    	$return = array();
    	
        $q = "SELECT `to`.*
			FROM `".TRANSACTION_OVERRIDES."` AS `to`
            WHERE `to`.`is_delete`='0' and `to`.`transaction_id`='".$transaction_id."'
            ORDER BY `to`.`id` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
        return $return;
  }
  public function select_payroll_transactions(){
    	$return = array();
    	
        $q = "SELECT `up`.*,pt.*
			FROM `".$this->table."` AS `up`
            LEFT JOIN `".PAYROLL_REVIEW_MASTER."` as `pt` on `pt`.`payroll_id` = `up`.`id`
            WHERE `up`.`is_delete`='0' and `up`.`is_close`= 0
            ORDER BY `pt`.`trade_number` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
        return $return;
  }
  public function select_recurring_type(){
    	$return = array();
    	
        $q = "SELECT `rt`.*
			FROM `".RECURRING_TYPE_MASTER."` AS `rt`
            WHERE `rt`.`is_delete`='0'
            ORDER BY `rt`.`id` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
        return $return;
  } 
  public function select_pay_type(){
    	$return = array();
    	
        $q = "SELECT `pt`.*
			FROM `".PAY_TYPE_MASTER."` AS `pt`
            WHERE `pt`.`is_delete`='0'
            ORDER BY `pt`.`id` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     array_push($return,$row);
    		}
        }
        return $return;
  }
  public function check_firm_does_not_participate(){
    	$return = array();
    	
        $q = "SELECT `sc`.*
			FROM `".SYSTEM_CONFIGURATION."` AS `sc`
            WHERE `sc`.`is_delete`='0'
            ORDER BY `sc`.`id` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     $return = $row;
    		}
        }
        return $return;
  }     
  public function delete($id){
    	$id = trim($this->re_db_input($id));
    	if($id>0){
    		$q = "UPDATE `".PAYROLL_REVIEW_MASTER."` SET `is_delete`='1' WHERE `id`='".$id."'";
    		$res = $this->re_db_query($q);
            
    		if($res){
    		    $_SESSION['success'] = DELETE_MESSAGE;
    			return true;
    		}
    		else{
    		    $_SESSION['warning'] = UNKWON_ERROR;
    			return false;
    		}
    	}
    	else{
    	     $_SESSION['warning'] = UNKWON_ERROR;
    		return false;
    	}
  }
  public function delete_adjustments_master($id){
    	$id = trim($this->re_db_input($id));
    	if($id>0){
    		$q = "UPDATE `".PAYROLL_ADJUSTMENTS_MASTER."` SET `is_delete`='1' WHERE `id`='".$id."'";
    		$res = $this->re_db_query($q);
            if($res){
    		    $_SESSION['success'] = DELETE_MESSAGE;
    			return true;
    		}
    		else{
    		    $_SESSION['warning'] = UNKWON_ERROR;
    			return false;
    		}
    	}
  }
  public function delete_selected_adjustments_master($data){
    	
        $delete_array = isset($data['delete'])?$data['delete']:array();
        
    	if($delete_array!=array()){
    	   
            foreach($delete_array as $key_id=>$key_val)
            {
                $q = "UPDATE `".PAYROLL_ADJUSTMENTS_MASTER."` SET `is_delete`='1' WHERE `id`='".$key_id."'";
    		    $res = $this->re_db_query($q);
            }
    		if($res){
    		    $_SESSION['success'] = DELETE_MESSAGE;
    			return true;
    		}
    		else{
    		    $_SESSION['warning'] = UNKWON_ERROR;
    			return false;
    		}
    	}
  }
  public function delete_balances_master($id){
	   $id = trim($this->re_db_input($id));
	   if($id>0){
		  $q = "UPDATE `".BROKER_BALANCES_MASTER."` SET `is_delete`='1' WHERE `id`='".$id."'";
		  $res = $this->re_db_query($q);
            if($res){
    		    $_SESSION['success'] = DELETE_MESSAGE;
    			return true;
    		}
    		else{
    		    $_SESSION['warning'] = UNKWON_ERROR;
    			return false;
    		}
    	}
  } 
  public function delete_prior_payrolls_master($id){
	   $id = trim($this->re_db_input($id));
	   if($id>0){
		  $q = "UPDATE `".PRIOR_PAYROLL_MASTER."` SET `is_delete`='1' WHERE `id`='".$id."'";
		  $res = $this->re_db_query($q);
            if($res){
    		    $_SESSION['success'] = DELETE_MESSAGE;
    			return true;
    		}
    		else{
    		    $_SESSION['warning'] = UNKWON_ERROR;
    			return false;
    		}
    	}
  }
  public function renew_mid_month_adjustments($is_expired,$adjustment_id='',$payable_trans_id='',$total_usage='',$recalc_total_usage=''){
      $con='';
      $add_str='';
    
      if($adjustment_id>0)
      {
           $con.=" AND id='".$adjustment_id."'";
      }
      if($payable_trans_id > 0)
      {
           $add_str.=",payable_trans_id='".$payable_trans_id."',recalc_old_usage='".$recalc_total_usage."'";
      }
	   
	  $q = "UPDATE `".PAYROLL_ADJUSTMENTS_MASTER."` SET `is_expired`='".$is_expired."',`total_usage`='".$total_usage."' ".$add_str." WHERE `recurring_type`='2' ".$con."";
	  $res = $this->re_db_query($q);
      if($res){
	     return true;
	  }
	  else{
         return false;
	  }
    	
  }
  public function renew_end_month_adjustments($is_expired,$adjustment_id='',$payable_trans_id='',$total_usage='',$recalc_total_usage=''){
      $con='';
      $add_str='';
    
      if($adjustment_id>0)
      {
           $con.=" AND id='".$adjustment_id."'";
      }
      if($payable_trans_id > 0)
      {
           $add_str.=",payable_trans_id='".$payable_trans_id."',recalc_old_usage='".$recalc_total_usage."'";
      }
	   
	  $q = "UPDATE `".PAYROLL_ADJUSTMENTS_MASTER."` SET `is_expired`='".$is_expired."',`total_usage`='".$total_usage."' ".$add_str." WHERE `recurring_type`='3' ".$con."";
	  $res = $this->re_db_query($q);
      if($res){
	     return true;
	  }
	  else{
         return false;
	  }
    	
  }
  public function renew_semi_mid_month_adjustments($is_expired,$adjustment_id='',$payable_trans_id='',$total_usage='',$recalc_total_usage=''){
      $con='';
      $add_str='';
    
      if($adjustment_id>0)
      {
           $con.=" AND id='".$adjustment_id."'";
      }
      if($payable_trans_id > 0)
      {
           $add_str.=",payable_trans_id='".$payable_trans_id."',recalc_old_usage='".$recalc_total_usage."'";
      }
	   
	  $q = "UPDATE `".PAYROLL_ADJUSTMENTS_MASTER."` SET `is_expired`='".$is_expired."',`total_usage`='".$total_usage."' ".$add_str." WHERE `recurring_type`='4' ".$con."";
	  $res = $this->re_db_query($q);
      if($res){
	     return true;
	  }
	  else{
         return false;
	  }
    	
  } 
  public function renew_semi_end_month_adjustments($is_expired,$adjustment_id='',$payable_trans_id='',$total_usage='',$recalc_total_usage=''){
      $con='';
      $add_str='';
    
      if($adjustment_id>0)
      {
           $con.=" AND id='".$adjustment_id."'";
      }
      if($payable_trans_id > 0)
      {
           $add_str.=",payable_trans_id='".$payable_trans_id."',recalc_old_usage='".$recalc_total_usage."'";
      }
	   
	  $q = "UPDATE `".PAYROLL_ADJUSTMENTS_MASTER."` SET `is_expired`='".$is_expired."',`total_usage`='".$total_usage."' ".$add_str." WHERE `recurring_type`='5' ".$con."";
	  $res = $this->re_db_query($q);
      if($res){
	     return true;
	  }
	  else{
         return false;
	  }
    	
  } 
  public function renew_qua_mid_month_adjustments($is_expired,$adjustment_id='',$payable_trans_id='',$total_usage='',$recalc_total_usage=''){
      $con='';
      $add_str='';
    
      if($adjustment_id>0)
      {
           $con.=" AND id='".$adjustment_id."'";
      }
      if($payable_trans_id > 0)
      {
           $add_str.=",payable_trans_id='".$payable_trans_id."',recalc_old_usage='".$recalc_total_usage."'";
      }
	   
	  $q = "UPDATE `".PAYROLL_ADJUSTMENTS_MASTER."` SET `is_expired`='".$is_expired."',`total_usage`='".$total_usage."' ".$add_str." WHERE `recurring_type`='6' ".$con."";
	  $res = $this->re_db_query($q);
      if($res){
	     return true;
	  }
	  else{
         return false;
	  }
    	
  }
  public function renew_qua_end_month_adjustments($is_expired,$adjustment_id='',$payable_trans_id='',$total_usage='',$recalc_total_usage=''){
      $con='';
      $add_str='';
    
      if($adjustment_id>0)
      {
           $con.=" AND id='".$adjustment_id."'";
      }
      if($payable_trans_id > 0)
      {
           $add_str.=",payable_trans_id='".$payable_trans_id."',recalc_old_usage='".$recalc_total_usage."'";
      }
	   
	  $q = "UPDATE `".PAYROLL_ADJUSTMENTS_MASTER."` SET `is_expired`='".$is_expired."',`total_usage`='".$total_usage."' ".$add_str." WHERE `recurring_type`='7' ".$con."";
	  $res = $this->re_db_query($q);
      if($res){
	     return true;
	  }
	  else{
         return false;
	  }
    	
  }              
                                 
}
?>