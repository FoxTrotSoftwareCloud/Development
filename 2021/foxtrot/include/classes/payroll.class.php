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
		$payroll_date = isset($data['payroll_date'])?$this->re_db_input($data['payroll_date']):'';
		$clearing_business_cutoff_date = isset($data['clearing_business_cutoff_date'])?$this->re_db_input($data['clearing_business_cutoff_date']):'';
        $direct_business_cutoff_date = isset($data['direct_business_cutoff_date'])?$this->re_db_input($data['direct_business_cutoff_date']):'';
        	
        if($payroll_date == ''){
            $this->errors = 'Please enter a payroll date.';
        } else if($clearing_business_cutoff_date ==''){
            $this->errors = 'Please select clearing business cutoff date.';
		} else if($direct_business_cutoff_date ==''){
            $this->errors = 'Please select direct business cutoff date.';
		} else {
            // 11/17/21 Check for an open payroll date -> prompt if the user wants to assign trades to the existing payroll, else exit
            $payroll = $this->get_payroll_uploads(0,1,0,$payroll_date);

            if (!empty($payroll)) {
                if ($data['duplicate_payroll_proceed'] !="true") {
                    $this->errors = 'Payroll Date already exists: '.date('m/d/Y', strtotime($payroll['payroll_date']));
                    $_SESSION['upload_payroll']['duplicate_payroll'] = true;
                }
            }
        }

        if($this->errors!=''){
			return $this->errors;
		}
		else{
		  
            /*$q = "SELECT * FROM `".$this->table."` WHERE is_delete = 0 AND is_close = 0 AND is_calculate = 1 AND payroll_date != '0000-00-00'";
			$res = $this->re_db_query($q);
			$return = $this->re_db_num_rows($res);
			if($return>0){
				$this->errors = 'Payroll data already exist. Please close current payroll !';
			}
			if($this->errors!=''){
				return $this->errors;
			}
		    else 
            {*/        
                if ($data['duplicate_payroll_proceed']=="true") {
                    $last_inserted_id = $payroll['id'];

                    $q = "UPDATE ".$this->table." 
                            SET `clearing_business_cutoff_date`='".date('Y-m-d',strtotime($clearing_business_cutoff_date))."',
                                `direct_business_cutoff_date`='".date('Y-m-d',strtotime($direct_business_cutoff_date))."'" 
                                .$this->update_common_sql()."
                            WHERE id = '".$last_inserted_id."'
                    ";
                    $res = $this->re_db_query($q);
                } else {
                    $q = "INSERT INTO ".$this->table." 
                            SET `payroll_date`='".date('Y-m-d',strtotime($payroll_date))."',
                                `clearing_business_cutoff_date`='".date('Y-m-d',strtotime($clearing_business_cutoff_date))."',
                                `direct_business_cutoff_date`='".date('Y-m-d',strtotime($direct_business_cutoff_date))."'".
                                $this->insert_common_sql();
                    $res = $this->re_db_query($q);
                    $last_inserted_id = $this->re_db_insert_id();
                }
                
                $trades_array = $this->select_trades($direct_business_cutoff_date,1);
                foreach($trades_array as $key=>$val)
                {
                    $q = "INSERT INTO ".PAYROLL_REVIEW_MASTER." SET `payroll_id`='".$last_inserted_id."',`trade_number`='".$val['id']."',`trade_date`='".$val['trade_date']."' ,`product`='".$val['product']."',`product_category`='".$val['product_cate']."',`client_account_number`='".$val['client_number']."',`client_name`='".$val['client_name']."',`broker_id`='".$val['broker_name']."',`quantity`='".$val['units']."',`price`='".$val['shares']."',`investment_amount`='".$val['invest_amount']."',`commission_expired`='',`charge`='".$val['charge_amount']."',`date_received`='".date('Y-m-d',strtotime($val['commission_received_date']))."',`commission_received`='".$val['commission_received']."',`buy_sell`='".$val['buy_sell']."',`hold`='".$val['hold_commission']."',`hold_reason`='".$val['hold_resoan']."',`is_split`='".$val['split']."',`cancel`='".$val['cancel']."',`branch`='".$val['branch']."'".$this->insert_common_sql();
    				$res = $this->re_db_query($q);
                    
                    $q = "UPDATE ".TRANSACTION_MASTER." SET `is_payroll`='1',`payroll_id`='".$last_inserted_id."' ".$this->update_common_sql()." WHERE `id`='".$val['id']."'";
    				$res = $this->re_db_query($q);
                }
                $total_trades = count($trades_array);

                $trades_array = $this->select_trades($clearing_business_cutoff_date,2);
                foreach($trades_array as $key=>$val)
                {
                    $q = "INSERT INTO ".PAYROLL_REVIEW_MASTER." SET `payroll_id`='".$last_inserted_id."',`trade_number`='".$val['id']."',`trade_date`='".$val['trade_date']."' ,`product`='".$val['product']."',`product_category`='".$val['product_cate']."',`client_account_number`='".$val['client_number']."',`client_name`='".$val['client_name']."',`broker_id`='".$val['broker_name']."',`quantity`='".$val['units']."',`price`='".$val['shares']."',`investment_amount`='".$val['invest_amount']."',`commission_expired`='',`charge`='".$val['charge_amount']."',`date_received`='".date('Y-m-d',strtotime($val['commission_received_date']))."',`commission_received`='".$val['commission_received']."',`buy_sell`='".$val['buy_sell']."',`hold`='".$val['hold_commission']."',`hold_reason`='".$val['hold_resoan']."',`is_split`='".$val['split']."',`cancel`='".$val['cancel']."',`branch`='".$val['branch']."'".$this->insert_common_sql();
    				$res = $this->re_db_query($q);
                    
                    $q = "UPDATE ".TRANSACTION_MASTER." SET `is_payroll`='1',`payroll_id`='".$last_inserted_id."' ".$this->update_common_sql()." WHERE `id`='".$val['id']."'";
    				$res = $this->re_db_query($q);
                }
                $total_trades += count($trades_array);

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
    public function reverse_payroll($data=''){
        $uploaded_payrolls = $this->get_payroll_uploads(0,1);
        if (count($uploaded_payrolls) > 1 AND is_array($uploaded_payrolls[0])) {
            if (!isset($data['payroll_date']) OR empty($data['payroll_date'])) {
                $this->errors = 'More than one open payroll. Please specify a Payroll Date.';
                return $this->errors;
            } else {
                $uploaded_payrolls = $this->get_payroll_uploads(0,1,0,date('Y-m-d', strtotime($data['payroll_date'])));
            }
        }

        if (empty($uploaded_payrolls)) {
            $this->errors = 'Payroll data does not exist!';
		}
        if($this->errors!=''){
            return $this->errors;
		}
        else
        {
            // Flag PAYROLL_UPLOAD & Master Payroll records as deleted
            $q = "UPDATE ".$this->table." SET `is_delete`='1' ".$this->update_common_sql()." WHERE `id`='".$uploaded_payrolls['id']."'";
            $res = $this->re_db_query($q);
        
            $q = "UPDATE ".PAYROLL_REVIEW_MASTER." SET `is_delete`='1' ".$this->update_common_sql()." WHERE `payroll_id`='".$uploaded_payrolls['id']."'";
            $res = $this->re_db_query($q);
            
            // Flag Transaction Master records as reversed - maintain "payroll_id" if the reversal needs reversing
            $payroll_transactions_array = $this->select_payroll_transactions($uploaded_payrolls['id']);
            foreach($payroll_transactions_array as $key=>$val)
            {
                $q = "UPDATE ".TRANSACTION_MASTER." SET `is_payroll`='0',`is_reverse`='1',`payroll_id`='".$val['payroll_id']."' ".$this->update_common_sql()." WHERE `id`='".$val['trade_number']."'";
    		    $res = $this->re_db_query($q);
            }
            // Remove the orphaned payroll data created during Payroll Calculation
            $payCalcClass = new payroll_calculation();
            $payCalcClass->clearCurrentPayroll($uploaded_payrolls['id']);
            $payCalcClass->clearSplitRates($uploaded_payrolls['id']);
            $payCalcClass->clearOverrides($uploaded_payrolls['id']);
            $payCalcClass->clearCurrentAdjustments($uploaded_payrolls['id']);

            if($res){
        	    $_SESSION['success'] = 'Payroll reversed successfully.';
        		return true;
        	} else {
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
     * @return bool calculate_payroll(
     * 8/30/21 Gutting the code to get Fixed Rates to run - li
     */
    public function calculate_payroll ($data, $payroll_date=''){
        $calculate = new payroll_calculation();
        
        if (isset($data['payroll_id'])) {
            $payroll_id = $data['payroll_id'];
        } else {
            $payroll_id = 0;
        }

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
        $q = "UPDATE `" .PAYROLL_REVIEW_MASTER. "` SET `commission_paid` = 0, `override_rate` = 0 WHERE payroll_id=".$payroll_id;
        $res = $this->re_db_query($q);

        // Breakout SPLIT transactions before calculating the "paid" field - 10/11/21
        $calculate->insert_payroll_split_rates($payroll_id);

        // **********************************************
        // * 1. Broker Commissions 
        // * 2. Split Commissions - added 10/12/21 li
        // * 3. Override Commissions - added 9/26/21 li
        // **********************************************
        $payroll_commissions = $calculate->select_payroll_calculation_transactions($payroll_id);
        
        // Clear out the Override Transactions - populated in "overrideCalculation()"
        $calculate->clearOverrides($payroll_id);

        if(count($payroll_commissions)>0) {
            $calculate->commissionCalculation($payroll_commissions, $payroll_date, $payroll_id);
            $calculate->calculateAdjustments($payroll_date, $payroll_id);
            $calculate->calculateCurrentPayroll($payroll_date, $payroll_id);

            $q = "UPDATE " .PAYROLL_UPLOAD. "
                    SET
                        `is_calculate`='1',
                        `calculated_time`=CURRENT_TIME
                      WHERE `id`=$payroll_id
            ";
            $res = $calculate->re_db_query($q);
        }
        return true;
    }

    public function payroll_close($payroll_id = 0){
        $uploaded_payroll = $this->get_payroll_uploads($payroll_id);
        
        if (empty($uploaded_payroll)) {
            $this->errors = 'Payroll ID does not exist.';
		}

        if ($this->errors!=''){
            return $this->errors;
		} else {
            
            // 11/20/21 Prior Payroll table - update from the new fields in CURRENT_PAYROLL table
            $payroll_date = $uploaded_payroll['payroll_date'];
            $payCalcClass = new payroll_calculation();
            $get_current_payroll = $payCalcClass->select_current_payroll($payroll_id);

            if(count($get_current_payroll)>0)
            {
                foreach($get_current_payroll as $key_current_payroll=>$val_current_payroll)
                {
                    $broker_id = $val_current_payroll['broker_id'];
                    $instance_broker = new broker_master();
                    $get_broker_data = $instance_broker->edit($broker_id);
                                    
                    $q = "INSERT INTO ".PRIOR_PAYROLL_MASTER." 
                            SET 
                                `payroll_id`='".$val_current_payroll['payroll_id']."',
                                `payroll_date`='".$payroll_date."',
                                `current_payroll_id`='".$val_current_payroll['id']."',
                                `broker_id`='".$broker_id."',
                                `branch`='".$val_current_payroll['branch']."',
                                `clearing_number`='".$get_broker_data['fund']."',
                                `gross_production`='".$val_current_payroll['commission_received']."',
                                `check_amount`='".$val_current_payroll['check_amount']."',
                                `minimum_check_amount`='".$val_current_payroll['minimum_check_amount']."',
                                `finra`='".$val_current_payroll['finra']."',
                                `sipc`='".$val_current_payroll['sipc']."',
                                `sipc_gross`='".$val_current_payroll['sipc_gross']."',
                                `net_production`='".$val_current_payroll['commission_paid']."',
                                `adjustments`='".$val_current_payroll['adjustments']."',
                                `taxable_adjustments`='".$val_current_payroll['taxable_adjustments']."',
                                `non-taxable_adjustments`='".$val_current_payroll['non-taxable_adjustments']."',
                                `net_earnings`='".$val_current_payroll['check_amount']."',
                                `check_number`='".$val_current_payroll['check_number']."',
                                `charge`='".$val_current_payroll['charge']."',
                                `commission_received`='".$val_current_payroll['commission_received']."',
                                `commission_paid`='".$val_current_payroll['commission_paid']."',
                                `is_split`='".$val_current_payroll['is_split']."',
                                `split_charge`='".$val_current_payroll['split_charge']."',
                                `split_rate`='".$val_current_payroll['split_rate']."',
                                `split_gross`='".$val_current_payroll['split_gross']."',
                                `split_paid`='".$val_current_payroll['split_paid']."',
                                `override_rate`='".$val_current_payroll['override_rate']."',
                                `override_paid`='".$val_current_payroll['override_paid']."',
                                `balance`='".$val_current_payroll['balance']."'
                                ".$this->insert_common_sql()
                    ;
                    $res = $this->re_db_query($q);
                    $prior_payroll_id = $this->re_db_insert_id();

                    // PRIOR PERIOD BALANCES
                    $q = "UPDATE ".BROKER_BALANCES_MASTER." 
                            SET `is_delete`='1'
                                ".$this->update_common_sql()." 
                            WHERE `is_delete`=0 AND `broker_id`='".$broker_id."'";
                    $res = $this->re_db_query($q);

                    if ($val_current_payroll['check_amount'] < $val_current_payroll['minimum_check_amount']) {
                        $q = "INSERT INTO ".BROKER_BALANCES_MASTER." 
                                SET `payroll_id`='".$val_current_payroll['payroll_id']."',
                                    `current_payroll_id`='".$val_current_payroll['id']."',
                                    `prior_payroll_id`='".$prior_payroll_id."',
                                    `payroll_date`='".$payroll_date."',
                                    `broker_id`='".$broker_id."',
                                    `clearing_number`='".$get_broker_data['fund']."',
                                    `balance_amount`='".$val_current_payroll['check_amount']."' 
                                    ".$this->insert_common_sql()
                        ;
                        $res = $this->re_db_query($q);
                    }
                    // CURRENT PAYROLL records
                    $q = "UPDATE `".PAYROLL_CURRENT_PAYROLL."`
                            SET `is_delete`='1',
                                `status` = '2' 
                                ".$this->update_common_sql()."
                            WHERE `is_delete`=0 AND `status`='0' AND `id`='".$val_current_payroll['id']."'"
                    ;
                    $res = $this->re_db_query($q);
    }
            }
            
            $q = "UPDATE `".PAYROLL_UPLOAD."`
                    SET `is_close`='1' 
                        ".$this->update_common_sql()."
                    WHERE `is_delete`=0 AND `is_close`='0' AND `id`='".$uploaded_payroll['id']."'"
            ;
            $res = $this->re_db_query($q);

            if($res){
                $_SESSION['success'] = "Payroll closed successfully";
                return true;
            }
            else{
                $_SESSION['warning'] = UNKWON_ERROR;
                return false;
            }
        }
    }

    function reverse_close ($payroll_id=0) {
        $thisPayrollClass = new payroll();
        $uploaded_payroll = $thisPayrollClass->get_payroll_uploads($payroll_id);
        
        if (empty($uploaded_payroll)) {
            echo "<h2>Payroll #$payroll_id doesn't exist.</h2>";
		// } else if ($uploaded_payroll['is_close']==0){
        //     echo "<h2>Payroll #$payroll_id not closed.</h2>";
        } else {
            // 11/20/21 Prior Payroll table - update from the new fields in CURRENT_PAYROLL table
            $payroll_date = $uploaded_payroll['payroll_date'];
            $payCalcClass = new payroll_calculation();

            // UNCLOSE AND/OR UNDELETE Tables
            $q = "UPDATE `".PAYROLL_UPLOAD."`
                    SET `is_close`='0' 
                        ".$thisPayrollClass->update_common_sql()."
                    WHERE `is_delete`=0 AND `is_close`='1' AND `id`='".$uploaded_payroll['id']."'"
            ;
            $res = $thisPayrollClass->re_db_query($q);

            $q = "UPDATE `".PAYROLL_CURRENT_PAYROLL."`
                    SET `is_delete`='0',
                        `status` = '1' 
                        ".$thisPayrollClass->update_common_sql()."
                    WHERE `is_delete`=1 AND `status`=2 AND `payroll_id`='".$uploaded_payroll['id']."'"
            ;
            $res = $thisPayrollClass->re_db_query($q);

            // DELETE DATA FOR THE TABLES BELOW
            $q = "UPDATE ".BROKER_BALANCES_MASTER." 
                SET `is_delete`='1',
                    `status`=-1
                    ".$thisPayrollClass->update_common_sql()."
                WHERE `is_delete`=0 AND `status`>-1 AND `payroll_id`='".$uploaded_payroll['id']."'"
            ;
            $res = $thisPayrollClass->re_db_query($q);

            $q = "UPDATE `".PRIOR_PAYROLL_MASTER."` 
                    SET `is_delete`='1',
                        `status`=-1 
                        ".$thisPayrollClass->update_common_sql()."
                    WHERE `is_delete`=0 AND `status`>-1 AND `payroll_id`='".$uploaded_payroll['id']."'"
            ;
            $res = $thisPayrollClass->re_db_query($q);
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
        // 11/20/21 Update the fields that correspond to the "new" fields for the Payroll Calculation
        $commission_received = $gross_production;
        $commission_paid = $net_production;

        if($id==0){
                
			 $q = "INSERT INTO ".PRIOR_PAYROLL_MASTER." 
                    SET `payroll_date`='".date('Y-m-d',strtotime($payroll_date))."',
                        `broker_id`='".$broker_id."',
                        `clearing_number`='".$clearing_number."',
                        `gross_production`='".$gross_production."',
                        `check_amount`='".$check_amount."',
                        `net_production`='".$net_production."',
                        `adjustments`='".$adjustments."',
                        `net_earnings`='".$net_earnings."',
                        `commission_received`='".$commission_received."',
                        `commission_paid`='".$commission_paid."'
                        ".$this->insert_common_sql();
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
		 
			$q = "UPDATE ".PRIOR_PAYROLL_MASTER." 
                    SET `payroll_date`='".date('Y-m-d',strtotime($payroll_date))."',
                        `broker_id`='".$broker_id."',
                        `clearing_number`='".$clearing_number."',
                        `gross_production`='".$gross_production."',
                        `check_amount`='".$check_amount."',
                        `net_production`='".$net_production."',
                        `adjustments`='".$adjustments."',
                        `net_earnings`='".$net_earnings."',
                        `commission_received`='".$commission_received."',
                        `commission_paid`='".$commission_paid."'
                        ".$this->update_common_sql()." 
                    WHERE `id`='".$id."'";
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
    public function get_payroll_uploads($id=0, $allOpenClosed=0, $isCalculated=0, $payroll_date=''){
    	$return = array();
        $con = "";

        if ($id != 0){
            $con .= " AND  `up`.`id` = $id ";
        }
        if ($allOpenClosed == 1) {
            $con .= " AND  `up`.`is_close` = 0 ";
        } else if ($allOpenClosed == 2){
            $con .= " AND `up`.`is_close` != 0 ";
        }        
        if ($isCalculated == 1) {
            $con .= " AND  `up`.`is_calculate` != 0 ";
        }
        if ($payroll_date != ''){
            $con .= " AND `up`.`payroll_date` = '".date('Y-m-d', strtotime($payroll_date))."'";
        }

        $q = "SELECT `up`.*
                FROM `".PAYROLL_UPLOAD."` AS `up` 
                WHERE `up`.`is_delete`='0' ".$con."
                ORDER BY `up`.`payroll_date`, `up`.`id` DESC";
                
    	$res = $this->re_db_query($q);
        if ($id != 0 OR $payroll_date != ''){
            $return = $this->re_db_fetch_array($res);
        } else if ($this->re_db_num_rows($res)>0) {
            $return = $this->re_db_fetch_all($res);
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

    public function select_trades($commission_received_date, $directClearing=0){
    	$return = array();
        
        $con = '';
    	if ($directClearing == 1) {
            $con .= " AND (`trans`.`source`='' OR `trans`.`source` IN ('DS', 'DZ'))";
        } else if ($directClearing == 2) {
            $con .= " AND (`trans`.`source`!='' AND `trans`.`source` NOT IN ('DS', 'DZ'))";
        }
 
        if($commission_received_date != '')
        {
            $q = "SELECT `trans`.*,`bt`.id as batch_number,`cl`.first_name as client_firstname,`cl`.last_name as client_lastname,`bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname
    			FROM `".TRANSACTION_MASTER."` AS `trans`
                LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `trans`.`batch`
                LEFT JOIN `".CLIENT_MASTER."` as `cl` on `cl`.`id` = `trans`.`client_name`
                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `trans`.`broker_name`
                WHERE `trans`.`is_delete`='0' 
                  AND `trans`.`hold_commission`!='1' 
                  AND `trans`.`is_payroll`='0' 
                  AND `trans`.`commission_received_date`<='".date('Y-m-d',strtotime($commission_received_date))."' 
                  AND `trans`.`commission_received_date`!='0000-00-00 00:00:00'
                  ".$con."
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
  public function select_payroll_transactions($payroll_id=0){
    	$return = array();
        
        $con = '';
        if ($payroll_id > 0){
            $con .= " AND `up`.`id`= '".$payroll_id."'";
        }
    	
        $q = "SELECT `up`.*,pt.*
			FROM `".$this->table."` AS `up`
            LEFT JOIN `".PAYROLL_REVIEW_MASTER."` as `pt` on `pt`.`payroll_id` = `up`.`id`
            WHERE `up`.`is_delete`='0' and `up`.`is_close`= 0
                  ".$con."
            ORDER BY `pt`.`trade_number` ASC";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
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
   *************************************************************************/
    public function get_broker_commission_report_data($company='',$payroll_id='',$broker='',$print_type=''){
		$return = array();
        $con='';
        $payroll_date = date('Y-m-d');
        $ytdEarnings = 0;
        $manage_company = new manage_company();
        $companyName = 'ALL COMPANIES';

        if($company>0) {
            $con .= " AND (`br1`.`company`=$company OR `br2`.`company`=$company OR `br3`.`company`=$company)";
            $companyName = $manage_company->select_company_by_id($company);
            $companyName = $companyName['company_name'];
        }
        if($payroll_id != '') {
            $con.=" AND `pc`.`payroll_id` = '".$payroll_id."'";
            $payroll_date = $this->get_payroll_uploads($payroll_id);
            $payroll_date = $payroll_date['payroll_date'];
        }
        if($broker>0) {
            $con .= ' and `pc`.`broker_id`="'.$broker.'"';
        }
        // Order by 
        if($print_type == 2) {
            $con .= " ORDER BY `bm`.`internal`, ";
        } else if ($print_type == 3) {
            $con .= " ORDER BY `bm`.`fund`, ";
        } else {
            // Default to "name" inserted in the next line
            $con .= " ORDER BY ";
        }
        $con .= "`bm`.`last_name`, `bm`.`first_name`, `bm`.`id`";
        
        $q = "SELECT 
                    `pc`.`broker_id` AS `broker_name`,
                    `pc`.`id` AS payroll_current_id,
                    `pc`.`payroll_id`,
                    `pc`.`check_amount`,
                    `pc`.`minimum_check_amount`,
                    `pc`.`sipc`,
                    `pc`.`finra`,
                    `pc`.`balance`,
                    `pc`.`balance` AS prior_broker_balance,
                    `pc`.`check_amount` AS prior_broker_earnings,
                    `bm`.`first_name` AS broker_firstname,
                    `bm`.`last_name` AS broker_lastname,
                    `bm`.`fund`,
                    `bm`.`internal`,
                    0 AS `payroll_draw`,
                    0 AS `salary`,
                    `br1`.`name` AS `branch_name1`, `br1`.`company` AS `branch_company1`,
                    `br2`.`name` AS `branch_name2`, `br2`.`company` AS `branch_company2`,
                    `br3`.`name` AS `branch_name3`, `br3`.`company` AS `branch_company3`
                FROM `".PAYROLL_CURRENT_PAYROLL."` AS `pc`
                LEFT JOIN `".BROKER_MASTER."` AS `bm` ON `pc`.`broker_id`=`bm`.`id` AND `bm`.`is_delete`='0'
                LEFT JOIN `".BROKER_BRANCHES."` AS `repbr` ON `pc`.`broker_id`=`repbr`.`broker_id` AND `repbr`.`is_delete`='0'
                LEFT JOIN `".BRANCH_MASTER."` AS `br1` ON `br1`.`id`=`repbr`.`branch1` AND `br1`.`is_delete`=0
                LEFT JOIN `".BRANCH_MASTER."` AS `br2` ON `br2`.`id`=`repbr`.`branch2` AND `br2`.`is_delete`=0
                LEFT JOIN `".BRANCH_MASTER."` AS `br3` ON `br3`.`id`=`repbr`.`branch3` AND `br3`.`is_delete`=0
                WHERE `pc`.`is_delete`='0' ".$con."
        ";
                
        $res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $return['company_name'] = $companyName;

            while($row = $this->re_db_fetch_array($res)){
                $return['broker_transactions'][$row['broker_name']] = $row;
                $ytdEarnings = $this->get_prior_earnings('net_earnings', $row['broker_name'], '', $payroll_date);
                $return['broker_transactions'][$row['broker_name']]['prior_broker_earnings'] = $ytdEarnings;
                
                $direct_transactions = $this->get_direct_transactions_for_broker_statement($row['broker_name'],$payroll_id, $company);
                $return['broker_transactions'][$row['broker_name']]['direct_transactions'] = $direct_transactions;
                $adjustments = $this->get_adjustments_for_broker_statement($row['broker_name'], $payroll_id);
                $return['broker_transactions'][$row['broker_name']]['adjustments'] = $adjustments;
                $split_transactions = $this->get_broker_split_commission_data($row['broker_name'], $payroll_id);
                $return['broker_transactions'][$row['broker_name']]['split_transactions'] = $split_transactions;
                $override_transactions = $this->get_broker_override_commission_data($row['broker_name'], $payroll_id);
                $return['broker_transactions'][$row['broker_name']]['override_transactions'] = $override_transactions;
            }
        }
        return $return;
	}
    /** Sub-array of "Product Category"
     * @param int $broker_id 
     * @return array 
     */
    public function get_direct_transactions_for_broker_statement($broker_id=0, $payroll_id=0, $company=''){
        $return =[];
        $con = '';

        if($company>0) {
            $con.=" AND `ts`.`company` = ".$company."";
        }

        $q = "SELECT 
                    `rp`.`id` AS `payable_transaction_id`,
                    `rp`.`trade_date`,
                    `rp`.`buy_sell`,
                    `rp`.`investment_amount`,
                    ROUND(`rp`.`charge`*`rp`.`split_rate`*.01,2) AS charge,
                    ROUND((`rp`.`commission_received`-`rp`.`charge`)*`rp`.`split_rate`*.01,2) AS net_commission,
                    ROUND(`rp`.`commission_received`*`rp`.`split_rate`*.01,2) AS commission_received,
                    `rp`.`commission_paid`,
                    `rp`.`payout_rate` AS rate,
                    `rp`.`is_split`,
                    `rp`.`broker_id` AS `broker_name`,
                    `com`.`company_name`,
                    `cm`.`first_name` AS client_firstname,
                    `cm`.`last_name` AS client_lastname,
                    `pt`.`type` AS product_category,
                    `bc`.`batch_desc` AS batch_description
                FROM `".PAYROLL_REVIEW_MASTER."` AS `rp` 
                LEFT JOIN `".CLIENT_MASTER."` AS `cm` on `rp`.`client_name`=`cm`.`id` and `cm`.`is_delete`='0'
                LEFT JOIN `".TRANSACTION_MASTER."` AS `ts` on `rp`.`trade_number`=`ts`.`id` and `ts`.`is_delete`='0'
                LEFT JOIN `".PRODUCT_TYPE."` AS `pt` on `rp`.`product_category`=`pt`.`id` and `pt`.`is_delete`='0'
                LEFT JOIN `".COMPANY_MASTER."` AS `com` on `ts`.`company`=`com`.`id` and `com`.`is_delete`='0'
                LEFT JOIN `".BATCH_MASTER."` AS `bc` on `ts`.`batch`=`bc`.`id` and `bc`.`is_delete`='0'
                WHERE `rp`.`is_delete`=0 AND `rp`.`broker_id`=$broker_id AND `rp`.`payroll_id`=$payroll_id ".$con."
        ";

		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
			while($row = $this->re_db_fetch_array($res)){
                $return[$row['product_category']][] = $row;
            }
        } 
        return $return;
    }
    /**
     * @param int $broker_id 
     * @return array 
     */
    public function get_adjustments_for_broker_statement($broker_id=0, $payroll_id=0){
        $q = "SELECT `pac`.*
                FROM `".PAYROLL_ADJUSTMENTS_CURRENT."` AS `pac`
                WHERE `pac`.`is_delete`='0' AND `pac`.`payroll_id`=$payroll_id AND `pac`.`broker_id`=$broker_id
        ";
        $res = $this->re_db_query($q);
        return($this->re_db_num_rows($res)>0) ? $this->re_db_fetch_all($res) : array();
	}
    /**
     * @param int $broker 
     * @return array 
     */
    public function get_broker_split_commission_data($broker=0, $payroll_id=0){
        $return = [];
        $q = "SELECT 
                    `com`.`company_name`,
                    `psr`.`split_broker` as broker_id,
                    `psr`.`broker_id` as giving_broker,
                    `bm2`.`first_name` as giving_firstname,
                    `bm2`.`last_name` as giving_lastname,
                    `pt`.`type` as product_category,
                    `psr`.`id` as split_id,
                    `rp`.`trade_date`,
                    `cm`.`first_name` as client_firstname,
                    `cm`.`last_name` as client_lastname,
                    `bc`.`batch_desc` as batch_description,
                    `rp`.`buy_sell`,
                    `rp`.`investment_amount`,
                    `psr`.`split_gross` AS `commission_received`,
                    `psr`.`split_charge` AS `charge`,
                    `psr`.`split_gross` - `psr`.`split_charge` AS `net_commission`,
                    `psr`.`split_rate` as `rate_per`,
                    `psr`.`split_paid` as `rate_amount`,
                    `psr`.`payout_rate` as `rate`
                FROM `".PAYROLL_SPLIT_RATES."` AS `psr`
				LEFT JOIN `".PAYROLL_REVIEW_MASTER."` AS `rp` ON `psr`.`payable_transaction_id`=`rp`.`id`
                LEFT JOIN `".BROKER_MASTER."` AS `bm2` ON `psr`.`broker_id`=`bm2`.`id`
                LEFT JOIN `".CLIENT_MASTER."` AS `cm` ON `rp`.`client_name`=`cm`.`id`
                LEFT JOIN `".TRANSACTION_MASTER."` AS `ts` ON `rp`.`trade_number`=`ts`.`id`
                LEFT JOIN `".PRODUCT_TYPE."` AS `pt` ON `rp`.`product_category`=`pt`.`id`
                LEFT JOIN `".COMPANY_MASTER."` AS `com` on`ts`.`company`= `com`.`id`
                LEFT JOIN `".BATCH_MASTER."` AS `bc` ON `ts`.`batch`=`bc`.`id`
                WHERE `psr`.`is_delete`='0' AND `psr`.`split_broker`=$broker AND `psr`.`payroll_id`=$payroll_id
                ORDER BY `giving_lastname`, `giving_firstname`, `giving_broker`
        ";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
			while($row = $this->re_db_fetch_array($res)){
			     $return[$row['giving_firstname']." ".$row['giving_lastname']." (".$row['giving_broker'].")"][] = $row;
            }
        }
        return $return;
	}
    /**
     * @param int $broker 
     * @return array 
     */
    public function get_broker_override_commission_data($broker=0, $payroll_id=0){
        $return = [];
        $q = "SELECT 
                    `com`.`company_name`,
                    `por`.`receiving_rep` as broker_id,
                    `por`.`broker_id` as giving_broker,
                    `bm2`.`first_name` as giving_firstname,
                    `bm2`.`last_name` as giving_lastname,
                    `pt`.`type` as product_category,
                    `por`.`id` as override_id,
                    `rp`.`trade_date`,
                    `cm`.`first_name` as client_firstname,
                    `cm`.`last_name` as client_lastname,
                    `bc`.`batch_desc` as batch_description,
                    `rp`.`buy_sell`,
                    `rp`.`investment_amount`,
                    `por`.`over_gross` AS `commission_received`,
                    `por`.`over_charge` AS `charge`,
                    `por`.`over_gross` - `por`.`over_charge` AS `net_commission`,
                    `por`.`rate` as `rate_per`,
                    `por`.`over_paid` as `rate_amount`
                FROM `".PAYROLL_OVERRIDE_RATES."` AS `por`
				LEFT JOIN `".PAYROLL_REVIEW_MASTER."` AS `rp` ON `por`.`payable_transaction_id`=`rp`.`id`
                LEFT JOIN `".BROKER_MASTER."` AS `bm2` ON `por`.`broker_id`= `bm2`.`id`
                LEFT JOIN `".CLIENT_MASTER."` AS `cm` ON `rp`.`client_name`=`cm`.`id`
                LEFT JOIN `".TRANSACTION_MASTER."` AS `ts` ON `rp`.`trade_number`=`ts`.`id`
                LEFT JOIN `".PRODUCT_TYPE."` AS `pt` ON `rp`.`product_category`=`pt`.`id`
                LEFT JOIN `".COMPANY_MASTER."` AS `com` ON `ts`.`company`=`com`.`id`
                LEFT JOIN `".BATCH_MASTER."` AS `bc` ON `ts`.`batch`=`bc`.`id`
                WHERE `por`.`is_delete`='0' AND `por`.`receiving_rep`=$broker AND `por`.`payroll_id`=$payroll_id
                ORDER BY `giving_lastname`, `giving_firstname`, `por`.`broker_id`, `rp`.`trade_date`
        ";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
			while($row = $this->re_db_fetch_array($res)){
			     $return[$row['giving_firstname']." ".$row['giving_lastname']." (".$row['giving_broker'].")"][] = $row;
            }
        }
        return $return;
    }
    /**
     * @param int $broker_id 
     * @return array|false|null|string 
     */
    public function get_broker_detail($broker_id=0){
        //$return = array();
        $q = "SELECT `bm`.*,`bg`.`home_address1_general`,`bg`.`home_address2_general`,`bg`.`city`,`st`.`name` as state_name,`bg`.`zip_code`,`br`.`name` as branch_name ,`bp`.`payroll_draw`, `bp`.`salary`
                FROM `".BROKER_MASTER."` AS `bm`
				LEFT JOIN `".BROKER_GENERAL."` AS `bg` on `bg`.`broker_id`=`bm`.`id`
                LEFT JOIN `".BRANCH_MASTER."` AS `br` on `br`.`broker`=`bm`.`id`
                LEFT JOIN `".BROKER_PAYOUT_MASTER."` AS `bp` on `bp`.`broker_id`=`bm`.`id`
                LEFT JOIN `".STATE_MASTER."` AS `st` on `st`.`id`=`bg`.`state_id`
                WHERE `bm`.`is_delete`='0' AND `bm`.`id`=$broker_id
        ";
		$res = $this->re_db_query($q);
		return ($this->re_db_num_rows($res)>0) ? $this->re_db_fetch_array($res) : '';
	}

    public function get_prior_earnings($earningsFields='net_earnings', $broker_id=0, $startDate='', $endDate='') {
        $return = 0;

        if (empty($earningsFields) OR empty($broker_id)){
            return $return;
        }
        if (empty($endDate)) {
            $endDate = date('Y-m-d');
        }
        if (empty($startDate)) {
            $startDate = date('Y-m-d', strtotime($endDate.' -1 year + 1 day'));
        }

        $q = "SELECT `broker_id`,
                     ROUND(SUM($earningsFields),2) AS `total`
                FROM `".PRIOR_PAYROLL_MASTER."`
                WHERE `is_delete`=0 AND `broker_id`='$broker_id' AND `payroll_date` BETWEEN '$startDate' AND '$endDate'
                GROUP BY `broker_id`
        ";
		
        $res = $this->re_db_query($q);
		$return = ($this->re_db_num_rows($res)>0) ? $this->re_db_fetch_array($res) : ['total'=>0];
        return $return['total'];
    }
    // *** END: Broker Payroll Statement Functions - 11/2/21 *** //

    /*
    * COMPANY COMMISSION STATEMENT - data set function - report_payroll_company_statement.php
    */
    public function get_company_statement_report_data($company='',$sort_by='',$payroll_id){
		$return = array();
        $con='';
        $payroll_date = date('Y-m-d');
        $manage_company = new manage_company();
        $companyName = 'ALL COMPANIES';
        
        if($company>0) {
            $con .= " AND (`br1`.`company`=$company OR `br2`.`company`=$company OR `br3`.`company`=$company)";
            $companyName = $manage_company->select_company_by_id($company);
            $companyName = $companyName['company_name'];
        }
        if($payroll_id != '') {
            $con.=" AND `pc`.`payroll_id` = '".$payroll_id."'";
            $payroll_date = $this->get_payroll_uploads($payroll_id);
            $payroll_date = $payroll_date['payroll_date'];
        }
        // Add the "ORDER BY" string
        $orderBy = empty($company) ? "`company_name`" : "";
        if  ($sort_by == 2) {
            $orderBy .= (empty($orderBy)? "" : ", ")."`bm`.`fund`";
        }
        $orderBy .= (empty($orderBy)? "" : ", ")."`bm`.`last_name`, `bm`.`first_name`, `bm`.`internal`, `bm`.`id`";

        $q = "SELECT 
                    `pc`.`broker_id` AS `broker_name`,
                    `pc`.`id` AS payroll_current_id,
                    `pc`.`payroll_id`,
                    `pc`.`commission_received` + `pc`.`split_gross` AS `commission_received`,
                    `pc`.`commission_paid` + `pc`.`split_paid` AS `commission_paid`,
                    `pc`.`charge` + `pc`.`charge` AS `charge`,
                    `pc`.`override_paid`,
                    `pc`.`check_amount`,
                    `pc`.`minimum_check_amount`,
                    `pc`.`sipc`,
                    `pc`.`finra`,
                    `pc`.`adjustments`,
                    `pc`.`balance`,
                    `pc`.`balance` AS prior_broker_balance,
                    `pc`.`check_amount` AS prior_broker_earnings,
                    `bm`.`first_name` AS broker_firstname,
                    `bm`.`last_name` AS broker_lastname,
                    `bm`.`fund`,
                    `bm`.`internal`,
                    0 AS `payroll_draw`,
                    0 AS `salary`,
                    `br1`.`name` AS `branch_name1`, `br1`.`company` AS `branch_company1`,
                    `br2`.`name` AS `branch_name2`, `br2`.`company` AS `branch_company2`,
                    `br3`.`name` AS `branch_name3`, `br3`.`company` AS `branch_company3`,
                    CASE WHEN `br1`.`company`=`co1`.`id` AND `co1`.`is_delete`=0 THEN `co1`.`company_name`
                         WHEN `br2`.`company`=`co2`.`id` AND `co2`.`is_delete`=0 THEN `co2`.`company_name`
                         WHEN `br3`.`company`=`co3`.`id` AND `co3`.`is_delete`=0 THEN `co3`.`company_name`
                         ELSE '* No Company *'
                         END AS `company_name`
                FROM `".PAYROLL_CURRENT_PAYROLL."` AS `pc`
                LEFT JOIN `".BROKER_MASTER."` AS `bm` ON `pc`.`broker_id`=`bm`.`id` AND `bm`.`is_delete`='0'
                LEFT JOIN `".BROKER_BRANCHES."` AS `repbr` ON `pc`.`broker_id`=`repbr`.`broker_id` AND `repbr`.`is_delete`='0'
                LEFT JOIN `".BRANCH_MASTER."` AS `br1` ON `br1`.`id`=`repbr`.`branch1` AND `br1`.`is_delete`=0
                LEFT JOIN `".BRANCH_MASTER."` AS `br2` ON `br2`.`id`=`repbr`.`branch2` AND `br2`.`is_delete`=0
                LEFT JOIN `".BRANCH_MASTER."` AS `br3` ON `br3`.`id`=`repbr`.`branch3` AND `br3`.`is_delete`=0
                LEFT JOIN `".COMPANY_MASTER."` AS `co1` ON `br1`.`company`=`co1`.`id` AND `co1`.`is_delete`=0
                LEFT JOIN `".COMPANY_MASTER."` AS `co2` ON `br2`.`company`=`co2`.`id` AND `co2`.`is_delete`=0
                LEFT JOIN `".COMPANY_MASTER."` AS `co3` ON `br3`.`company`=`co3`.`id` AND `co3`.`is_delete`=0
                WHERE `pc`.`is_delete`='0' ".$con."
                ORDER BY ".$orderBy."
        ";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
			while($row = $this->re_db_fetch_array($res)){
                // Some brokers are in more than one company, so if the query is for ONE company, put all records into the specified query Company, so they will be grouped under one company on the report
                if ($company > 0) {
                    $return[$companyName][] = $row;
                } else {
                    $return[$row['company_name']][] = $row;
                }
			}
        }
		return $return;
	}
    public function get_reconciliation_report_data($category='', $payroll_id='') {
		$return = array();

        $con='';
        
        if($category>0)
        {
            $con.=" AND `ts`.`product_cate` = ".$category."";
        }
        if($payroll_id != '')
        {
            $con.=" AND `prm`.`payroll_id` = '".$payroll_id."'";
        }
        
        $q = "SELECT 
                    `bc`.`id` AS `batch_number`,
                    `bc`.`batch_date` AS `batch_date`,
                    `bc`.`batch_desc` AS `batch_description`,
                    `bc`.`check_amount` AS `batch_check_amount`,
                    `bc`.`commission_amount` AS `batch_commission_amount`,
                    `bc`.`pro_category`,
                    `pc`.`type` AS `product_category`,
                    COUNT(`prm`.`trade_number`) AS `trade_count`,
                    COUNT(`prm`.`trade_number`) AS `hold_trade_count`,
                    SUM(`prm`.`commission_received`) AS `gross_commission`,
                    SUM(`prm`.`commission_received`) AS `total_commission`,
                    SUM(`prm`.`investment_amount`) AS `total_investment_amount`
				FROM `".PAYROLL_REVIEW_MASTER."` AS `prm`
                LEFT JOIN `".TRANSACTION_MASTER."` AS `ts` ON `prm`.`trade_number` = `ts`.`id` AND `ts`.`is_delete`=0
                LEFT JOIN `".BATCH_MASTER."` AS `bc` ON `ts`.`batch`=`bc`.`id` AND `bc`.`is_delete`=0
                LEFT JOIN `".PRODUCT_TYPE."` AS `pc` ON `bc`.`pro_category`=`pc`.`id` AND `pc`.`is_delete`=0
                WHERE `prm`.`is_delete`='0' ".$con." 
                GROUP BY `batch_number`, `batch_date`, `batch_description`, `batch_check_amount`, `batch_commission_amount`, `ts`.`product_cate`, `product_category`
                ORDER BY `bc`.`pro_category`, `product_category`, `batch_number`
        ";
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
			while($row = $this->re_db_fetch_array($res)){
			     $total_hold_commission = $this->get_hold_commissions_data($row['batch_number']);//print_r($total_hold_commission);exit;
                if (count($total_hold_commission)>0) {
                    $row['total_hold_commission'] = $total_hold_commission['total_hold_commission'];
                    $row['total_commission'] +=  $total_hold_commission['total_hold_commission'];
                    $row['hold_trade_count'] = $total_hold_commission['hold_trade_count'];
                    $row['trade_count'] += $total_hold_commission['hold_trade_count'];
                } else {
                    $row['total_hold_commission'] = 0;
                    $row['hold_trade_count'] = 0;
                }
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
            $con.=" AND `ts`.`batch` = '".$batch."'";
        }
        
        $q = "SELECT COUNT(`ts`.`commission_received`) AS hold_trade_count, 
                     SUM(`ts`.`commission_received`) as total_hold_commission
				FROM `".TRANSACTION_MASTER."` AS `ts`
                WHERE `ts`.`is_delete`='0' and `ts`.`hold_commission`='1' ".$con."
                GROUP BY `ts`.`batch`
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
    public function get_adjustments_report_data($company='',$payroll_id=0,$sort_by='',$output_type=''){
		$return = array();
        $con="`adj_cur`.`is_delete`='0'";

        // Query Strings
        if($payroll_id != 0) {
            $con.=" AND `adj_cur`.`payroll_id` = '".$payroll_id."'";
        }

        if($company>0) {
            $con .= " AND (`br1`.`company`=$company OR `br2`.`company`=$company OR `br3`.`company`=$company)";
        }

        if($output_type==3) {
            $con.=" AND `adj_cur`.`recurring` = 1";
        }

        // ORDER BY query strings
        $orderBy = '';
        if($sort_by == 1) {
            $orderBy .= "`bm`.`last_name`, `bm`.`first_name`, `bm`.`internal`, `bm`.`id`";
        }
        else if($sort_by == 2) {
            $orderBy .= "`bm`.`internal`, `bm`.`last_name`, `bm`.`first_name`, `bm`.`id`";
        }
        else if($sort_by == 3) {
            $orderBy .= "`payroll_category`, `adj_cur`.`category`, `bm`.`last_name`, `bm`.`first_name`, `bm`.`internal`, `bm`.`id`";
        }
        else //if($sort_by == 4) 
        {
            $orderBy .= "`adj_cur`.`gl_account`, `bm`.`last_name`, `bm`.`first_name`, `bm`.`internal`, `bm`.`id`";
        }
        
        $queryString = 
                "SELECT 
                    `adj_cur`.`id`,
                    `adj_cur`.`broker_id`,
                    `adj_cur`.`description`,
                    `adj_cur`.`category` AS `category_id`,
                    `adj_cur`.`adjustment_amount`,
                    `adj_cur`.`taxable_adjustment`,
                    `rt`.`name` AS `recurring_type`,
                    `pr_tp`.`type` AS `payroll_category`,
                    `bm`.`first_name` AS `broker_firstname`,
                    `bm`.`last_name` AS `broker_lastname`,
                    `bm`.`fund`,
                    `bm`.`internal`,
                    `br1`.`name` AS `branch_name1`, `br1`.`company` AS `branch_company1`,
                    `br2`.`name` AS `branch_name2`, `br2`.`company` AS `branch_company2`,
                    `br3`.`name` AS `branch_name3`, `br3`.`company` AS `branch_company3`,
                    CASE WHEN `br1`.`company`=`co1`.`id` AND `co1`.`is_delete`=0 THEN `co1`.`company_name`
                         WHEN `br2`.`company`=`co2`.`id` AND `co2`.`is_delete`=0 THEN `co2`.`company_name`
                         WHEN `br3`.`company`=`co3`.`id` AND `co3`.`is_delete`=0 THEN `co3`.`company_name`
                         ELSE '* No Company *'
                         END AS `company_name`
                FROM `".PAYROLL_ADJUSTMENTS_CURRENT."` AS `adj_cur`
                LEFT JOIN `".RECURRING_TYPE_MASTER."` AS `rt` ON `adj_cur`.`recurring_type`=`rt`.`id` AND `rt`.`is_delete`='0'
                LEFT JOIN `".PAYROLL_TYPE."` AS `pr_tp` ON `adj_cur`.`category`=`pr_tp`.`id` AND `pr_tp`.`is_delete`='0'
                LEFT JOIN `".BROKER_MASTER."` AS `bm` ON `adj_cur`.`broker_id`=`bm`.`id` AND `bm`.`is_delete`='0'
                LEFT JOIN `".BROKER_BRANCHES."` AS `repbr` ON `adj_cur`.`broker_id`=`repbr`.`broker_id` AND `repbr`.`is_delete`='0'
                LEFT JOIN `".BRANCH_MASTER."` AS `br1` ON `br1`.`id`=`repbr`.`branch1` AND `br1`.`is_delete`=0
                LEFT JOIN `".BRANCH_MASTER."` AS `br2` ON `br2`.`id`=`repbr`.`branch2` AND `br2`.`is_delete`=0
                LEFT JOIN `".BRANCH_MASTER."` AS `br3` ON `br3`.`id`=`repbr`.`branch3` AND `br3`.`is_delete`=0
                LEFT JOIN `".COMPANY_MASTER."` AS `co1` ON `br1`.`company`=`co1`.`id` AND `co1`.`is_delete`=0
                LEFT JOIN `".COMPANY_MASTER."` AS `co2` ON `br2`.`company`=`co2`.`id` AND `co2`.`is_delete`=0
                LEFT JOIN `".COMPANY_MASTER."` AS `co3` ON `br3`.`company`=`co3`.`id` AND `co3`.`is_delete`=0
                WHERE ".$con."
                ORDER BY ".$orderBy."
        ";

		$res = $this->re_db_query($queryString);
        if($this->re_db_num_rows($res)>0){
			while($row = $this->re_db_fetch_array($res)){
			     $return['company_name']=$row['company_name'];
			     $return['data'][$row['broker_id'].' - '.$row['broker_firstname'].', '.$row['broker_lastname']][] = $row;
                 
			}
        }
		return $return;
	}

}
?>