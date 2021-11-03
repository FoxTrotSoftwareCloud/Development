<?php

class payroll extends db{
    // 11/1/21 Changed field name IN PAYROLL_REVIEW_MASTER - broker_name->broker_id. Templates still use "broker_name" - li
    // 11/1/21 Changed field name IN BROKER_BALANCES_MASTER - broker_name->broker_id. Templates still use "broker_name" - li
    // 11/1/21 Changed field name IN PRIOR_PAYROLL_MASTER - rep_number->broker_id. rep_name-><removed> -> Templates still use "rep_number" & "rep_name" - li
    
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
        $broker_id = $broker_name;
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
            
			$q = "INSERT INTO ".PAYROLL_REVIEW_MASTER." SET `trade_number`='".$trade_number."',`trade_date`='".$trade_date."' ,`product`='".$product."',`product_category`='".$product_category."',`client_account_number`='".$client_account_number."',`client_name`='".$client_name."',`broker_id`='".$broker_id."',`quantity`='".$quantity."',`price`='".$price."',`investment_amount`='".$investment_amount."',`commission_expired`='".$commission_expired."',`charge`='".$charge."',`date_received`='".$date_received."',`commission_received`='".$commission_received."',`buy_sell`='".$buy_sell."',`hold`='".$hold."',`hold_reason`='".$hold_reason."',`cancel`='".$cancel."',`branch`='".$branch."'".$this->insert_common_sql();
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
		 
			$q = "UPDATE ".PAYROLL_REVIEW_MASTER." SET `trade_number`='".$trade_number."',`trade_date`='".$trade_date."' ,`product`='".$product."',`product_category`='".$product_category."',`client_account_number`='".$client_account_number."',`client_name`='".$client_name."',`broker_id`='".$broker_id."',`quantity`='".$quantity."',`price`='".$price."',`investment_amount`='".$investment_amount."',`commission_expired`='".$commission_expired."',`charge`='".$charge."',`date_received`='".$date_received."',`commission_received`='".$commission_received."',`buy_sell`='".$buy_sell."',`hold`='".$hold."',`hold_reason`='".$hold_reason."',`cancel`='".$cancel."',`branch`='".$branch."'".$this->update_common_sql()." WHERE `id`='".$id."'";
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
                    $q = "INSERT INTO ".PAYROLL_REVIEW_MASTER." SET `payroll_id`='".$last_inserted_id."',`trade_number`='".$val['id']."',`trade_date`='".$val['trade_date']."' ,`product`='".$val['product']."',`product_category`='".$val['product_cate']."',`client_account_number`='".$val['client_number']."',`client_name`='".$val['client_name']."',`broker_id`='".$val['broker_name']."',`quantity`='".$val['units']."',`price`='".$val['shares']."',`investment_amount`='".$val['invest_amount']."',`commission_expired`='',`charge`='".$val['charge_amount']."',`date_received`='".date('Y-m-d',strtotime($val['commission_received_date']))."',`commission_received`='".$val['commission_received']."',`buy_sell`='".$val['buy_sell']."',`hold`='".$val['hold_commission']."',`hold_reason`='".$val['hold_resoan']."',`is_split`='".$val['split']."',`cancel`='".$val['cancel']."',`branch`='".$val['branch']."'".$this->insert_common_sql();
    				$res = $this->re_db_query($q);
                    
                    $q = "UPDATE ".TRANSACTION_MASTER." SET `is_payroll`='1',`payroll_id`='".$last_inserted_id."' ".$this->update_common_sql()." WHERE `id`='".$val['id']."'";
    				$res = $this->re_db_query($q);
                }
                $total_trades = count($trades_array);
                if($res){
                    if($total_trades>0)
                    {
                        $_SESSION['success'] = $total_trades.' trades uploaded successfully.';
                    }
                    else
                    {
                        $_SESSION['success'] = $total_trades.' trades uploaded.';
                    }
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
    
    /**
     * Calculate Payroll - process uploaded payroll trades, overrides, adjustments for Broker Commission Statements
     * - Updates the checks table ()
     * - called from calculate_payrolls.php
     * @param mixed $data 
     * @return bool 
     * 8/30/21 Gutting the code to get Fixed Rates to run - li
     */
    public function calculate_payroll($data, $payroll_date=''){
        $calculate = new payroll_calculation();
        
        if (!empty($payroll_date) AND $payroll_date != '0000-00-00 00:00:00'){
            // Do nothing
        } elseif(count($data) > 0 AND $data['payroll_date'] != '0000-00-00 00:00:00') {
            $payroll_date = $data['payroll_date'];
        } else {
            $payroll_date = '';
        }

        if($payroll_date != '') {
            $payroll_date = date('Y-m-d',strtotime($payroll_date));
        }
        
        // Clear Commission Paid field in Payroll Master
        $q = "UPDATE `" .PAYROLL_REVIEW_MASTER. "` SET `commission_paid` = 0, `override_rate` = 0 WHERE 1";
        $res = $this->re_db_query($q);

        // Breakout SPLIT transactions before calculating the "paid" field - 10/11/21
        $calculate->insert_payroll_split_rates($payroll_date);

        // **********************************************
        // * 1. Broker Commissions 
        // * 2. Split Commissions - added 10/12/21 li
        // * 3. Override Commissions - added 9/26/21 li
        // **********************************************
        $payroll_commissions = $calculate->select_payroll_calculation_transactions();
        
        // Clear out the Override Transactions - populated in "overrideCalculation()"
        $calculate->clearOverrides();

        if(count($payroll_commissions)<=0) {
            // No uploaded trades
            $q = "UPDATE " .PAYROLL_UPLOAD. "
                    SET
                        `is_calculate`='1',
                        `payroll_date`='" .$calculate->re_db_input($payroll_date). "'".
                        $calculate->update_common_sql(). "
                    WHERE `is_calculate`='0' and `is_delete`='0' and `is_close`='0'
            ";
            $res = $calculate->re_db_query($q);
        } else {
            $calculate->commissionCalculation($payroll_commissions, $payroll_date);
            $calculate->calculateAdjustments($payroll_date);
            $calculate->calculateCurrentPayroll($payroll_date);
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
                
                $q = "UPDATE ".PRIOR_PAYROLL_MASTER." SET `is_delete`='1'".$this->update_common_sql()." WHERE `broker_id`='".$broker."'";
                $res = $this->re_db_query($q);
                
                $q = "INSERT INTO ".PRIOR_PAYROLL_MASTER." SET `payroll_date`='".$val_prior_payrolls['payroll_date']."',`broker_id`='".$get_broker_data['id']."',`clearing_number`='".$get_broker_data['fund']."',`gross_production`='".$val_prior_payrolls['gross_commissions']."',`check_amount`='".$val_prior_payrolls['prior_check_amounts']."' ".$this->insert_common_sql();
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
                
                $q = "UPDATE ".BROKER_BALANCES_MASTER." SET `is_delete`='1'".$this->update_common_sql()." WHERE `broker_id`='".$broker."'";
                $res = $this->re_db_query($q);
                
                $q = "INSERT INTO ".BROKER_BALANCES_MASTER." SET `broker_number`='".$get_broker_data['id']."',`broker_id`='".$get_broker_data['id']."',`clearing_number`='".$get_broker_data['fund']."',`balance_amount`='".$val_balance['broker_balance_amounts']."' ".$this->insert_common_sql();
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
        
        $broker_name = isset($data['broker_name'])?$this->re_db_input($data['broker_name']):'';
        $broker_id = $broker_name;
        $clearing_number = isset($data['clearing_number'])?$this->re_db_input($data['clearing_number']):'';
        $balance_amount = isset($data['balance_amount'])?$this->re_db_input($data['balance_amount']):'';
        
        if($id==0){
                
			 $q = "INSERT INTO ".BROKER_BALANCES_MASTER." SET `broker_id`='".$broker_id."',`clearing_number`='".$clearing_number."',`balance_amount`='".$balance_amount."'".$this->insert_common_sql();
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
		 
			$q = "UPDATE ".BROKER_BALANCES_MASTER." SET `broker_id`='".$broker_id."',`clearing_number`='".$clearing_number."',`balance_amount`='".$balance_amount."'".$this->update_common_sql()." WHERE `id`='".$id."'";
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
        // $rep_number deprecated in PRIOR_PAYROLL_MASTER database, replaced with "broker_id" - 11/1/21 li
        $broker_id = isset($data['rep_number'])?$this->re_db_input($data['rep_number']):'';
        $rep_name = isset($data['rep_name'])?$this->re_db_input($data['rep_name']):'';
        $clearing_number = isset($data['clearing'])?$this->re_db_input($data['clearing']):'';
        $gross_production = isset($data['gross_production'])?$this->re_db_input($data['gross_production']):'';
        $check_amount = isset($data['check_amount'])?$this->re_db_input($data['check_amount']):'';
        $net_production = isset($data['net_production'])?$this->re_db_input($data['net_production']):'';
        $adjustments = isset($data['adjustments'])?$this->re_db_input($data['adjustments']):'';
        $net_earnings = isset($data['net_earnings'])?$this->re_db_input($data['net_earnings']):'';
        
        if($id==0){
                
			 $q = "INSERT INTO ".PRIOR_PAYROLL_MASTER." SET `payroll_date`='".date('Y-m-d',strtotime($payroll_date))."',`broker_id`='".$broker_id."',`clearing_number`='".$clearing_number."',`gross_production`='".$gross_production."',`check_amount`='".$check_amount."',`net_production`='".$net_production."',`adjustments`='".$adjustments."',`net_earnings`='".$net_earnings."'".$this->insert_common_sql();
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
		 
			$q = "UPDATE ".PRIOR_PAYROLL_MASTER." SET `payroll_date`='".date('Y-m-d',strtotime($payroll_date))."',`broker_id`='".$broker_id."',`clearing_number`='".$clearing_number."',`gross_production`='".$gross_production."',`check_amount`='".$check_amount."',`net_production`='".$net_production."',`adjustments`='".$adjustments."',`net_earnings`='".$net_earnings."'".$this->update_common_sql()." WHERE `id`='".$id."'";
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
        $q = "SELECT `trn`.*, `trn`.`broker_id` AS broker_name
                FROM ".PAYROLL_REVIEW_MASTER." AS `trn`
                WHERE `trn`.`is_delete`='0' AND `trn`.`id`='".$id."'";
        $res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $return = $this->re_db_fetch_array($res);
        }
        return $return;
    }
   /** INVALID: Table PAYROLL_TRADE_OVERRIDES doesn't exist, but may be added later - 10/29/21 li
    * @param mixed $id 
    * @param mixed $transaction_id 
    * @return array 
    */
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
   /** INVALID: Table PAYROLL_TRADE_SPLITS doesn't exist, but may be added later - 10/29/21 li
    * @param mixed $id 
    * @param mixed $transaction_id 
    * @return array 
    */
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
		$q = "SELECT `bb`.*, `bb`.`broker_id` AS `broker_name`, `bb`.`broker_id` AS `broker_number`
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
		$q = "SELECT `pr`.`id`,
                     `pr`.`payroll_date`,
                     `pr`.`broker_id` AS `rep_number`,
                     `pr`.`broker_id` AS `rep_name`,
                     `pr`.`clearing_number`,
                     `pr`.`gross_production`,
                     `pr`.`check_amount`,
                     `pr`.`minimum_check_amount`,
                     `pr`.`finra`,
                     `pr`.`sipc`,
                     `pr`.`sipc_gross`,
                     `pr`.`net_production`,
                     `pr`.`adjustments`,
                     `pr`.`taxable_adjustments`,
                     `pr`.`non-taxable_adjustments`,
                     `pr`.`net_earnings`,
                     `pr`.`status`,
                     `pr`.`is_delete`
				FROM ".PRIOR_PAYROLL_MASTER." AS `pr`
                WHERE `pr`.`is_delete`='0' AND `pr`.`id`='".$id."'";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_array($res);
        }
		return $return;
   }
    public function get_last_payroll(){
    	$return = array();
        $con='';
        
        $q = "SELECT `up`.*
                FROM `".PAYROLL_UPLOAD."` AS `up` 
                WHERE `up`.`is_delete`='0' and `up`.`is_close` = 0 AND `up`.`is_calculate` > 0 
                ORDER BY `up`.`id` DESC limit 1";
                
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
    		while($row = $this->re_db_fetch_array($res)){
    		     $return = $row;
    		}
        }
    	return $return;
  }
  public function get_brokers_payroll($broker=''){
    	$return = array();
        $con='';
        
        if($broker != '')
        {
            $con.= " AND `pt`.`broker_id` = '".$broker."'";
        }
    	
    	$q = "SELECT `up`.*, sum(`pt`.commission_received) as gross_commissions,sum(`pt`.commission_paid) as prior_check_amounts,`pt`.*,`bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname,`cl`.first_name as client_firstname,`cl`.last_name as client_lastname
                FROM `".PAYROLL_UPLOAD."` AS `up`
    			LEFT JOIN `".PAYROLL_REVIEW_MASTER."` AS `pt` on `pt`.`payroll_id` = `up`.`id`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `pt`.`broker_id`
                LEFT JOIN `".CLIENT_MASTER."` as `cl` on `cl`.`id` = `pt`.`client_name`
                WHERE `up`.`is_delete`='0' and `up`.`is_calculate`='1' and `up`.`is_close`='1' and `pt`.`is_balance`='0' ".$con."
                GROUP BY `pt`.`broker_id` ASC";
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
            $con.= " AND `pt`.`broker_id` = '".$broker."'";
        }
    	
    	$q = "SELECT `pt`.`broker_id`, sum(`pt`.commission_received) as gross_commissions,sum(`pt`.commission_paid) as broker_balance_amounts,`pt`.*,`bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname,`cl`.first_name as client_firstname,`cl`.last_name as client_lastname
                FROM `".PAYROLL_UPLOAD."` AS `up`
    			LEFT JOIN `".PAYROLL_REVIEW_MASTER."` AS `pt` on `pt`.`payroll_id` = `up`.`id`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `pt`.`broker_id`
                LEFT JOIN `".CLIENT_MASTER."` as `cl` on `cl`.`id` = `pt`.`client_name`
                WHERE `up`.`is_delete`='0' and `up`.`is_calculate`='1' and `up`.`is_close`='1' and `pt`.`is_balance`='1' ".$con."
                GROUP BY `pt`.`broker_id` ASC";
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
        $extraFields = '';
        
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
            $con.=" AND `bb`.`broker_id`='".$broker."'";
        }
        
    	$q = "SELECT `bb`.*,`bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname, `bb`.`id` AS broker_name, `bb`.`id` AS broker_number
    			FROM `".BROKER_BALANCES_MASTER."` AS `bb`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `bb`.`broker_id`
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
        // 11/1/21 li - phase out field "rep_name", and rename "rep_number" to "broker_id" to normalize database
    	$return = array();
        $con='';
        
        if($broker>0)
        {
            $con.=" AND `pr`.`broker_id`='".$broker."'";
        }
        
    	$q = "SELECT `pr`.`id`,
                     `pr`.`payroll_date`,
                     `pr`.`broker_id` AS `rep_number`,
                     `pr`.`broker_id` AS `rep_name`,
                     `pr`.`clearing_number`,
                     `pr`.`gross_production`,
                     `pr`.`check_amount`,
                     `pr`.`minimum_check_amount`,
                     `pr`.`finra`,
                     `pr`.`sipc`,
                     `pr`.`sipc_gross`,
                     `pr`.`net_production`,
                     `pr`.`adjustments`,
                     `pr`.`taxable_adjustments`,
                     `pr`.`non-taxable_adjustments`,
                     `pr`.`net_earnings`,
                     `pr`.`status`,
                     `pr`.`is_delete`,
                     `bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname
    			FROM `".PRIOR_PAYROLL_MASTER."` AS `pr`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `pr`.`broker_id`
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
    	
    	$q = "SELECT `sc`.`minimum_check_amount`, `sc`.`finra`, `sc`.`sipc`
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

    public function select($is_listing=0) {
        $return = array();
        $con='';
      
        if($is_listing == 1) {
            $con.= " AND `up`.`is_calculate` > 0";
        }
    	
        // 9/26/21 li - Order by the broker, date, product category for better payroll calculation, especially for Sliding Scale reps going to higher breakpoint in the middle of the payroll run
    	$q = "SELECT `up`.`id`,`up`.`is_calculate`,`up`.`payroll_date`,`pt`.*,
                    `bm`.first_name as broker_firstname,
                    `bm`.last_name as broker_lastname,
                    `cl`.first_name as client_firstname,
                    `cl`.last_name as client_lastname,
                    `pt`.`broker_id` AS `broker_name`
                FROM `".PAYROLL_UPLOAD."` AS `up`
    			LEFT JOIN `".PAYROLL_REVIEW_MASTER."` AS `pt` on `pt`.`payroll_id`= `up`.`id`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `pt`.`broker_id`
                LEFT JOIN `".CLIENT_MASTER."` as `cl` on `cl`.`id` = `pt`.`client_name`
                WHERE `up`.`is_delete`='0' and `up`.`is_close` = 0 ".$con."
                ORDER BY `pt`.`broker_id`, `pt`.`trade_date`, `pt`.`product_category`";
                
        $res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
            while($row = $this->re_db_fetch_array($res)){
                // 9/27/21 LI - Remove Uploads that don't have a matching record in Review Master ($row['id']=null)
                if (!is_null($row['id']))
                    array_push($return,$row);
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


  /*************************************************************************
   * REPORT FUNCTIONS ADDED RECENTLY
   * 10/31/21 Added back, but the code isn't right. To be updated 10/31/21+
   *************************************************************************/
    public function get_company_statement_report_data($company='',$sort_by='',$payroll_date){
		$return = array();
        $con='';
        
        if($company>0)
        {
            $con.=" AND `ts`.`company` = ".$company."";
        }
        if($payroll_date != '')
        {
            $con.=" AND `up`.`is_close`='0' AND `up`.`payroll_date` = '".date('Y-m-d',strtotime($payroll_date))."'";
        }
        else
        {
            $con.=" AND `up`.`is_close`='0' ";
        }
        if($sort_by == 1)
        {
            $con .= " ORDER BY bm.first_name ASC";
        }
        else if($sort_by == 2)
        {
            $con .= " ORDER BY bm.fund ASC";
        }
        $q = "SELECT `up`.*,`rp`.*,bm.first_name as broker_firstname,bm.last_name as broker_lastname,bm.fund,cm.company_name
				FROM `".$this->table."` AS `up`,`".PAYROLL_REVIEW_MASTER."` AS `rp`
                LEFT JOIN `".TRANSACTION_MASTER."` AS `ts` on `ts`.`id`=`rp`.`trade_number`
                LEFT JOIN `".BROKER_MASTER."` AS `bm` on `bm`.`id`=`ts`.`broker_name`
                LEFT JOIN `".COMPANY_MASTER."` AS `cm` on `cm`.`id`=`ts`.`company`
                WHERE `up`.`is_delete`='0' and `rp`.`payroll_id`=`up`.`id` ".$con."
                ";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
			while($row = $this->re_db_fetch_array($res)){
			     $return[$row['company_name']][] = $row;
			}
        }
		return $return;
	}
    public function get_reconciliation_report_data($category='',$payroll_date=''){
		$return = array();
        $con='';
        
        if($category>0)
        {
            $con.=" AND `ts`.`product_cate` = ".$category."";
        }
        if($payroll_date != '')
        {
            $con.=" AND `up`.`is_close`='0' AND `up`.`payroll_date` = '".date('Y-m-d',strtotime($payroll_date))."'";
        }
        else
        {
            $con.=" AND `up`.`is_close`='0' ";
        }
        
        $q = "SELECT `up`.*,`rp`.*,`bc`.`id` as batch_number,`bc`.`batch_date` as batch_date,`bc`.`batch_desc` as batch_description,`bc`.`check_amount` as batch_check_amount,`pc`.`type` as product_category,COUNT(`rp`.`trade_number`) as trade_count,SUM(`rp`.`commission_received`) as gross_commission,SUM(`rp`.`commission_paid`) as total_commission,SUM(`rp`.`investment_amount`) as total_investment_amount
				FROM `".$this->table."` AS `up`,`".PAYROLL_REVIEW_MASTER."` AS `rp`
                LEFT JOIN `".TRANSACTION_MASTER."` AS `ts` on `ts`.`id`=`rp`.`trade_number`
                LEFT JOIN `".BATCH_MASTER."` AS `bc` on `bc`.`id`=`ts`.`batch`
                LEFT JOIN `".PRODUCT_TYPE."` AS `pc` on `pc`.`id`=`ts`.`product_cate`
                WHERE `up`.`is_delete`='0' and `rp`.`payroll_id`=`up`.`id` ".$con." group by `bc`.id
                ";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
			while($row = $this->re_db_fetch_array($res)){
			     $total_hold_commission = $this->get_hold_commissions_data($row['batch_number']);//print_r($total_hold_commission);exit;
			     $row['total_hold_commission'] = $total_hold_commission['total_hold_commission'];
                 $return[$row['product_category']][] = $row;
			}
        }
		return $return;
	}
    public function get_hold_commissions_data($batch=''){
		$return = array();
        $con='';
        
        if($batch>0)
        {
            $con.=" AND `ts`.`batch` = ".$batch."";
        }
        
        $q = "SELECT SUM(`ts`.`commission_received`) as total_hold_commission
				FROM `".TRANSACTION_MASTER."` AS `ts`
                WHERE `ts`.`is_delete`='0' and `ts`.`hold_commission`='1' ".$con."
                ";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
			while($row = $this->re_db_fetch_array($res)){
			     $return = $row;
			}
        }
		return $return;
	}
    public function get_adjustments_report_data($company='',$payroll_date='',$sort_by='',$output_type=''){
		$return = array();
        $con='';
        
        if($payroll_date != '')
        {
            $con.=" AND `up`.`is_close`='0' AND `up`.`payroll_date` = '".date('Y-m-d',strtotime($payroll_date))."'";
        }
        else
        {
            $con.=" AND `up`.`is_close`='0' ";
        }
        if($company>0)
        {
            $con.=" AND `ts`.`company` = ".$company."";
        }
        if($output_type==3)
        {
            $con.=" AND `pam`.`recurring` = 1";
        }
        if($sort_by == 1)
        {
            $con .= " ORDER BY bm.first_name ASC";
        }
        else if($sort_by == 2)
        {
            $con .= " ORDER BY bm.id ASC";
        }
        else if($sort_by == 3)
        {
            $con .= " ORDER BY pt.type ASC";
        }
        else if($sort_by == 4)
        {
            $con .= " ORDER BY pa.gl_account ASC";
        }
        
        $q = "SELECT `pa`.*,`bm`.`fund`,`pt`.`type` as payroll_category,cm.company_name,bm.first_name as broker_firstname,bm.last_name as broker_lastname,`bm`.`id` as broker_id
                FROM `".$this->table."` AS `up`,`".PAYROLL_REVIEW_MASTER."` AS `rp`
				LEFT JOIN `".PAYROLL_BROKERS_ADJUSTMENTS."` AS `pa` on `pa`.`payable_trans_id`=`rp`.`id`
                LEFT JOIN `".PAYROLL_ADJUSTMENTS_MASTER."` AS `pam` on `pam`.`id`=`pa`.`adjustment_id`
                LEFT JOIN `".BROKER_MASTER."` AS `bm` on `bm`.`id`=`pa`.`broker_id`
                LEFT JOIN `".PAYROLL_TYPE."` AS `pt` on `pt`.`id`=`pa`.`category`
                LEFT JOIN `".TRANSACTION_MASTER."` AS `ts` on `ts`.`id`=`rp`.`trade_number`
                LEFT JOIN `".COMPANY_MASTER."` AS `cm` on `cm`.`id`=`ts`.`company`
                WHERE `up`.`is_delete`='0' AND `pa`.`is_delete`='0' and `rp`.`payroll_id`=`up`.`id` ".$con."
                ";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
			while($row = $this->re_db_fetch_array($res)){
			     $return['company_name']=$row['company_name'];
			     $return['data'][$row['broker_id'].' - '.$row['broker_firstname'].', '.$row['broker_lastname']][] = $row;
                 
			}
        }
		return $return;
	}
    public function get_broker_commission_report_data($company='',$payroll_date='',$broker='',$print_type=''){
		$return = array();
        $con='';
        
        if($company>0)
        {
            $con.=" AND `ts`.`company` = ".$company."";
        }
        if($payroll_date != '')
        {
            $con.=" AND `up`.`is_close`='0' AND `up`.`payroll_date` = '".date('Y-m-d',strtotime($payroll_date))."'";
        }
        else
        {
            $con.=" AND `up`.`is_close`='0' ";
        }
        if($broker>0)
        {
            $con .= ' and `rp`.`broker_name`="'.$broker.'"';
        }
        if($print_type == 1)
        {
            $con .= " ORDER BY `bm`.`first_name` ASC";
        }
        else
        {
            $con .= " ORDER BY `bm`.`fund` ASC";
        }
        
        $q = "SELECT `rp`.*,com.company_name,bm.first_name as broker_firstname,bm.last_name as broker_lastname,`bm`.`id` as broker_id,`bm`.`fund`,cm.first_name as client_firstname,cm.last_name as client_lastname,`pt`.`type` as product_category,`bc`.`batch_desc` as batch_description,`pe`.`check_amount` as prior_broker_earnings,`bl`.`balance_amount` as prior_broker_balance,`rp`.`broker_id` AS `broker_name`
                FROM `".$this->table."` AS `up`,`".PAYROLL_REVIEW_MASTER."` AS `rp`
				LEFT JOIN `".BROKER_MASTER."` AS `bm` on `bm`.`id`=`rp`.`broker_id` and `bm`.`is_delete`='0'
                LEFT JOIN `".CLIENT_MASTER."` AS `cm` on `cm`.`id`=`rp`.`client_name` and `cm`.`is_delete`='0'
                LEFT JOIN `".TRANSACTION_MASTER."` AS `ts` on `ts`.`id`=`rp`.`trade_number` and `ts`.`is_delete`='0'
                LEFT JOIN `".PRODUCT_TYPE."` AS `pt` on `pt`.`id`=`rp`.`product_category` and `pt`.`is_delete`='0'
                LEFT JOIN `".COMPANY_MASTER."` AS `com` on `com`.`id`=`ts`.`company` and `com`.`is_delete`='0'
                LEFT JOIN `".BATCH_MASTER."` AS `bc` on `bc`.`id`=`ts`.`batch` and `bc`.`is_delete`='0'
                LEFT JOIN `".PRIOR_PAYROLL_MASTER."` AS `pe` on `pe`.`broker_id`=`rp`.`broker_id` and `pe`.`is_delete`='0'
                LEFT JOIN `".BROKER_BALANCES_MASTER."` AS `bl` on `bl`.`broker_id`=`rp`.`broker_id` and `bl`.`is_delete`='0' 
                WHERE `up`.`is_delete`='0' and `rp`.`payroll_id`=`up`.`id` and `rp`.`is_delete`='0' ".$con."
                ";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
			while($row = $this->re_db_fetch_array($res)){
			     $return['company_name']=$row['company_name'];
			     $return['broker_transactions'][$row['broker_name']]['direct_transactions'][$row['product_category']][] = $row;
                 $adjustments = $this->get_adjustments_for_broker_statement($row['id'],$row['broker_name']);
                 $return['broker_transactions'][$row['broker_name']]['adjustments'] = $adjustments;
                 $split_transactions = $this->get_broker_split_commission_data($row['broker_name']);
                 $return['broker_transactions'][$row['broker_name']]['split_transactions'] = $split_transactions;
                 $override_transactions = $this->get_broker_override_commission_data($row['broker_name']);
                 $return['broker_transactions'][$row['broker_name']]['override_transactions'] = $override_transactions;
            }
        }
		return $return;
	}

}
?>