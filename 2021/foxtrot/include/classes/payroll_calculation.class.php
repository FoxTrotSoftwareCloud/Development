<?php 
class payroll_calculation extends payroll {
    /** 
     * Process Trade Commissions for Payroll Data
     * 09/26/21 LI - Calculate Override - call overrideCalculate()
     * 10/15/21 LI - Change "broker_name" field to "broker_id" in PAYROLL_REVIEW_MASTER
     *  @param array $paydata -> Trade\Commission query->array to be scanned, assumes data is sorted by "broker_id"
     *  date $payroll_date -> Date of the current payroll. Used in YTD & Payroll-to-date queries. (2)Populating table tableName.payroll_date
     * @return none
     * @throws none
     **/
    function commissionCalculation($paydata=[], $payroll_date='', $payroll_id=0) {
        // Initialize the variables for the entire payroll
        // $applyHigherRate - has to be outside the loop, because it is checked between the 2 "do...while" loops
        $paydata_recno= 0;
        $applyHigherRate = false;
        $higherRate = 0.0000;
        
        // Loop through the "$paydata" array
        // There is a second "do...while" loop within this one for each rep, sometimes twice for the same rep (see $applyHigherRate)
        do {
            $brokerId = $paydata[$paydata_recno]['broker_id'];
            $brokerRecNo = $paydata_recno;
            $firstRate = null;
            $paid_total = 0;
            $gross_total = 0;
            
            // Broker Payout Information
            $q = "SELECT * FROM `".BROKER_PAYOUT_MASTER."` WHERE `is_delete`='0' AND `broker_id`='".$brokerId."'";
            $res = $this->re_db_query($q);
            $broker_payout_master = $this->re_db_fetch_array($res);
            
            // Determine FIXED or SLIDING RATE calculation
            $q = "SELECT category_id,category_rates FROM `" .PAYOUT_FIXED_RATES. "` WHERE `is_delete`='0' AND `broker_id`='" .$brokerId. "' AND category_rates > 0";
            $res = $this->re_db_query($q);
            $useFixed = $res->num_rows;
            $basis_amount = 0; // For the Sliding Rate breakpoint calculation
            
            // For Sliding Scale, aggregate the Broker's Comm or Paid amounts -> $basis_amount
            if (!$useFixed) {
                $rate = $this->slideRate($broker_payout_master, $payroll_date, $basis_amount);

                $q = "SELECT * FROM `" .BROKER_PAYOUT_GRID. "` WHERE `is_delete`='0' AND `broker_id`= '".$broker_payout_master['broker_id']. "' ORDER BY broker_id, id";
                $res = $this->re_db_query($q);
                $broker_slide_grid = $this->re_db_fetch_all($res);
            }

            // Loop through the Payroll Table per Broker ID
            do {
                $commission_paid = 0;
                $rate = 0;
                $updateFlag = '';
                $slide_level = 0;

                if(isset($broker_payout_master) AND count($broker_payout_master) > 0) {
                    // Highest Rate
                    if ($applyHigherRate){
                        $rate = $higherRate;
                        if ($broker_payout_master['basis'] == 1)
                            $basis_amount += $paydata[$paydata_recno]['commission_received'];
                    } elseif ($useFixed) {
                        $q = "SELECT category_id,category_rates FROM `" .PAYOUT_FIXED_RATES. "` WHERE `is_delete`='0' AND `broker_id`='" .$brokerId. "' AND `category_id` = '" .$paydata[$paydata_recno]['product_category']. "'";
                        $res = $this->re_db_query($q);
                        $broker_rates_array = $this->re_db_fetch_array($res);
    
                        if($this->re_db_num_rows($res)>0 AND !empty($broker_rates_array['category_rates'])) {
                            $rate = $broker_rates_array['category_rates'] / 100;
                        }
                    } else {
                            // *** Sliding Rate ***
                        // Calc Detail - add gross or net
                        if ($broker_payout_master['basis'] == 1)
                            $basis_amount += $paydata[$paydata_recno]['commission_received'];

                        $slide_level = $this->getBreakpoint ($broker_slide_grid, $basis_amount, true);
                        if ($slide_level > -1)
                            $rate = $broker_slide_grid[$slide_level]['per'] / 100;
                    }
                    
                    // Charge Deducted From 
                    if (is_null($broker_payout_master)) {
                        $chargeDeduction = 1;
                    } else {
                        $chargeDeduction = $broker_payout_master['clearing_charge_deducted_from'];
                    }
            
                    // Commission Paid
                    if ($rate>0 and $paydata[$paydata_recno]['commission_received'] <> 0) {
                        // Net
                        if ($chargeDeduction <= 1) {
                            $commission_paid = round(($rate*$paydata[$paydata_recno]['commission_received']),2) - $paydata[$paydata_recno]['charge'];
                        } else {
                            $commission_paid = round(($paydata[$paydata_recno]['commission_received'] - $paydata[$paydata_recno]['charge'])*$rate,2);
                        }
                    }
            
                    // Update the payroll table 
                    $q = "";
                    $res = "";
            
                    if ($paydata[$paydata_recno]['is_split'] != 99) {
                        $q = "UPDATE " .PAYROLL_REVIEW_MASTER. "
                                 SET commission_paid = " .$this->re_db_input($commission_paid). " 
                                     ,payout_rate=".$this->re_db_input($rate*100)."
                                 WHERE id = " .$this->re_db_input($paydata[$paydata_recno]['payable_transaction_id'])
                        ;
                    } else {
                        $q = "UPDATE " .PAYROLL_SPLIT_RATES. "
                                SET split_paid = " .$this->re_db_input($commission_paid). "
                                    ,payout_rate = " .$this->re_db_input($rate*100). "
                                WHERE id = " .$this->re_db_input($paydata[$paydata_recno]['split_id'])
                        ;
                    }
                    
                    $res = $this->re_db_query($q);
                }
                
                $paid_total += $commission_paid;
                $gross_total += $paydata[$paydata_recno]['commission_received'];

                // If incremental sliding rate and net commission, accumulate the basis amount
                // AND $broker_payout_master['calculation_detail'] == 1 
                if ($broker_payout_master['basis'] == 2)
                    $basis_amount += $commission_paid;
                
                if (is_null($firstRate)) 
                    $firstRate = $rate;
                
                // *** OVERRIDE For This Trade *** //
                $overRate = $this->calculateOverride($paydata[$paydata_recno], $payroll_date);
                
                $q = "UPDATE ".PAYROLL_REVIEW_MASTER.
                        " SET ".
                            "`override_rate`=override_rate + '" .$this->re_db_input($overRate). "'". 
                            $this->update_common_sql(). 
                        " WHERE `id`='" .$paydata[$paydata_recno]['payable_transaction_id']. "'";
                $res = $this->re_db_query($q);

                ++$paydata_recno;
                
            } while ($paydata_recno < count($paydata) and $paydata[$paydata_recno]['broker_id'] == $brokerId);
            
            
            // If the "Apply Higher Rate" is checked, go back and apply the higher rate to all trades for this rep
            $higherRate = $rate;
            if (!$useFixed AND $broker_payout_master['calculation_detail']==2 AND !is_null($firstRate) AND $firstRate < $rate) {
                if ($applyHigherRate) {
                    $applyHigherRate = false;
                } else {
                    $paydata_recno = $brokerRecNo;
                    $applyHigherRate = true;
                }
            } else {
                $applyHigherRate = false;
            }

        } while ($paydata_recno < count($paydata));
    }
    /** Calculate Overrides
     * - Retrieve the Override Info for the "broker_id" of the $trade, and calculate the Override Commission for the receiving rep (RAP)
     * @param array $trade record
     * @return true - Override Commission exists, and written to PAYROLL_OVERRIDE_RATES table
     *         false - Same as above, but bad WRITE to the table
     *             0 - No override for the specific trade\rep override criteria
     * Calls - payroll.class -> get_override_rates(...)
     */
    function calculateOverride($trade=[], $payroll_date=null) {
        $brokerId = $trade['broker_id'];
        if (is_null($payroll_date) AND isset($trade['payroll'])) {
            $payroll_date = $trade['payroll_date'];
        }
        $receiveOrGive = 2;
        $overRate = 000.0000;
        $overRateTotal = 000.0000;
        
        // Broker Payout Info            
        $q = "SELECT * FROM `".BROKER_PAYOUT_MASTER."` WHERE `is_delete`='0' AND `broker_id`='".$brokerId."'";
        $res = $this->re_db_query($q);
        $broker_payout_master = $this->re_db_fetch_array($res);
        $over_date = isset($trade['trade_date']) ? $trade['trade_date'] : $payroll_date;
        // Broker.apply_to: (1)All Trades - Use BROKER_PAYOUT_OVERRIDES, (2)Only Going Forward - use overrides stored with the trade (TRANSACTION_OVERRIDES)
        $apply_to = $broker_payout_master['apply_to'];

        // Broker Override Info
        $overArray = $this->get_override_rates($over_date,$brokerId, $trade['product_category'], $receiveOrGive, $apply_to, $trade['transaction_id']);
        
        // Cycle through the Broker's Override Reps
        foreach ($overArray as $overrides) {
            $overComm = 0;
            // Calculatation Criteria
            $grossOrNet = $broker_payout_master['calculate_on'];
            $overRate = floatval($overrides['per']);
            // Commission & Charge
            $commRec = floatval($trade['commission_received']) - ($grossOrNet=='2' ? floatval($trade['charge']) : 0);
            
            // Finally, calc this override
            $overComm = round( $commRec * $overRate / 100, 2 );
            // Accumulate the total override % paid on this trade - populate PAYROLL_REVIEW_MASTER "over_rate" field - 9/27/21 li
            $overRateTotal += $overRate;

            // Insert the trade into the Override Trades/Rate Table
            if ($overComm != 0) {
                $q = "INSERT INTO " .PAYROLL_OVERRIDE_RATES. " SET ".
                        "`payroll_id` = " .$this->re_db_input($trade['payroll_id']).", ".
                        "`payable_transaction_id` = " .$this->re_db_input($trade['payable_transaction_id']).", ".
                        "`transaction_id` = " .$this->re_db_input($trade['transaction_id']).", ".
                        "`override_id` = " .$this->re_db_input($overrides['id']).", ".
                        "`broker_id` = " .$this->re_db_input($trade['broker_id']).", ".
                        "`receiving_rep` = " .$this->re_db_input($overrides['rap']).", ".
                        "`rate` = " .$this->re_db_input($overrides['per']).", ".
                        "`rate_per` = " .$this->re_db_input($overrides['per']).", ".
                        "`is_override_broker` = " .$this->re_db_input($overrides['is_override_broker']). ", ".
                        "`status` = 1".", ".
                        "`is_delete` = 0".", ".
                        "`over_gross` = " .$this->re_db_input($trade['commission_received']).", ".
                        "`over_charge` = " .$this->re_db_input($trade['charge']).", ".
                        "`over_paid` = " .$this->re_db_input($overComm).
                        $this->insert_common_sql()
                ;
        
                $res = $this->re_db_query($q);
                
                if($res){
                    $_SESSION['success'] = UPDATE_MESSAGE;
                } else {
                    $_SESSION['warning'] = UNKWON_ERROR;
                }
            }
        }
        return $overRateTotal;
    }
    /* Process the adjustments for payout to the brokers
     * @param mixed $payroll_date 
     * @return array 
    */
    function calculateAdjustments($payroll_date,$payroll_id=0) {
        $this->clearCurrentAdjustments($payroll_id);

        // Filter for "pay_on" & "expire" 
        $q = "SELECT 
                    '0' AS `id`, 
                    `pa`.`id` AS `adjustment_id`, 
                    `pa`.`adjustment_amount`,
                    `pa`.`date`,
                    `pa`.`pay_on`,
                    `pa`.`gl_account`,
                    `pa`.`expire`,
                    `pa`.`category`,
                    `pa`.`taxable_adjustment`,
                    `pa`.`broker` AS `all_brokers`,
                    `pa`.`broker_number` AS `broker_id`,
                    `pa`.`recurring`,
                    `pa`.`recurring_type`,
                    `rt`.`name` AS `type_name`,
                    `pa`.`description`,
                    `pa`.`pay_type`,
                    `pa`.`pay_amount`,
                    `pa`.`is_expired`,
                    `pa`.`total_usage`,
                    `pa`.`status`,
                    `pa`.`is_delete`,
                    '" .$payroll_date. "' AS `payroll_date`,
                    '" .$payroll_id. "' AS `payroll_id`
                FROM `".PAYROLL_ADJUSTMENTS_MASTER."` AS `pa`
                LEFT JOIN `".RECURRING_TYPE_MASTER."` AS `rt` on `rt`.`id` = `pa`.`recurring_type`
                WHERE `pa`.`is_delete`='0' AND ('".$payroll_date."' >= `pa`.`pay_on`) AND (`pa`.`expire`='0000-00-00 00:00:00' OR '".$payroll_date."' <= `pa`.`expire`)
                ORDER BY `pa`.`id` ASC
        ";
        $res = $this->re_db_query($q);
        $adjustments = $this->re_db_fetch_all($res);
        
        $filteredAdjustments = [];
        foreach ($adjustments AS $adjustment) {
            if ($this->checkRecurringType($adjustment)) {
                array_push($filteredAdjustments, $adjustment);
            }
        }

        // Load "All Active Brokers" into the array
        $allBrokersAdjustments = array_filter($filteredAdjustments, function($e){return $e['all_brokers']==1;});
        $activeBrokers = $this->select_active_brokers($payroll_date);
        foreach ($allBrokersAdjustments AS $allBrokerKey=>$allBrokerAdj) {
            $brokerCount = 0;
            foreach ($activeBrokers AS $activeBrokerKey=>$activeBroker){
                $brokerCount += 1;
                if ($brokerCount == 1) {
                    $filteredAdjustments[$allBrokerKey]['broker_id'] = $activeBroker['broker_id'];
                    $filteredAdjustments[$allBrokerKey]['total_usage'] = $brokerCount;
                } else {
                    $allBrokerAdj['broker_id'] = $activeBroker['broker_id'];
                    $allBrokerAdj['total_usage'] = $brokerCount;
                    array_push($filteredAdjustments, $allBrokerAdj);
                }
            }
        }
        
        $loadResult = $this->insert_update_generic(PAYROLL_ADJUSTMENTS_CURRENT, $filteredAdjustments);

        return $loadResult;
    }
    /** Calculate the final Broker Check Amounts in the Payroll Calculation - insert/Update PAYROLL_CURRENT_PAYROLL
    * @param string $payroll_date 
    * @return void 
    * @return bool
    */
    function calculateCurrentPayroll($payroll_date='', $payroll_id=0) {
        $this->clearCurrentPayroll($payroll_id);
        
        // Commissions
        $q = "SELECT broker_id, 
                    '$payroll_date' AS `payroll_date`,
                    '$payroll_id'   AS `payroll_id`,
                    SUM(IF(split_rate = 100, `commission_received`,0)) AS `commission_received`,
                    SUM(IF(split_rate = 100, `charge`,0)) AS `charge`,
                    SUM(IF(split_rate = 100, `commission_paid`,0)) AS `commission_paid`,
                    SUM(IF(split_rate = 100, 0,ROUND(`commission_received`*`split_rate`*.01,2))) AS `split_gross`,
                    SUM(IF(split_rate = 100, 0,ROUND(`charge`*`split_rate`*.01,2))) AS `split_charge`,
                    SUM(IF(split_rate = 100, 0,`commission_paid`)) AS `split_paid`,
                    SUM(IF(split_rate = 100, 0,1)) AS `is_split`,
                    SUM(IF(`product_category`<15 AND `product_category`!=5, ROUND(`commission_received`*`split_rate`*.01,2), 0)) AS `sipc_gross`
                FROM `".PAYROLL_REVIEW_MASTER."`
                WHERE is_delete = 0 AND payroll_id=$payroll_id
                GROUP BY broker_id, payroll_date, payroll_id
                ORDER BY broker_id
            ";
        $res = $this->re_db_query($q);
        $paydata = $this->re_db_fetch_all($res);
        $res = $this->insert_update_current_payroll($paydata);

        // Splits
        $q = "SELECT
                    `pcp`.`id` AS id, 
                    '$payroll_date' AS `payroll_date`,
                    '$payroll_id' AS `payroll_id`,
                    `psr`.`split_broker` AS broker_id, 
                    SUM(`psr`.`split_gross`) AS split_gross,
                    SUM(`psr`.`split_charge`) AS split_charge,
                    SUM(`psr`.`split_paid`) AS split_paid,
                    ROUND(SUM(`psr`.`split_gross`)/SUM(`psr`.`commission_received`) * 100,2) AS split_rate,
                    COUNT(`psr`.`split_broker`) AS is_split,
                    SUM(IF(`prm`.`product_category`<15 AND `prm`.`product_category`!=5, `psr`.`split_gross`, 0)) AS `sipc_gross`
                FROM `".PAYROLL_SPLIT_RATES."` psr
                LEFT JOIN `".PAYROLL_CURRENT_PAYROLL."` AS `pcp` ON `psr`.`split_broker`=`pcp`.`broker_id`  AND `pcp`.`is_delete`=0 AND `pcp`.`payroll_id`=$payroll_id
                LEFT JOIN `" .PAYROLL_REVIEW_MASTER. "` AS  `prm` ON `psr`.`payable_transaction_id`=`prm`.`id`
                WHERE `psr`.`is_delete` = 0 AND `psr`.`payroll_id`=$payroll_id 
                GROUP BY `psr`.`split_broker`, `pcp`.`id`, `payroll_date`, `payroll_id`
                ORDER BY `psr`.`split_broker`
            ";
        $res = $this->re_db_query($q);
        $paydata = $this->re_db_fetch_all($res);
        $payDataCopy = $paydata;
        // Flag "formulas" with "@@" so setString() won't put quotes around them, which will populate the field with a literal rather than calculating the equation(`field`=`field`+'someValue')-10/20/21 li
        foreach ($payDataCopy AS $payIndex=>$payRow) {
            if (!is_null($payRow['id'])) {
                foreach ($payRow AS $rowKey=>$rowValue){
                    if (fnmatch('*split*', $rowKey) OR $rowKey=='sipc_gross') {
                        $paydata[$payIndex][$rowKey] = "@@`".$rowKey."` + ".$rowValue."@@";
                    }
                }
            }
        }
        $res = $this->insert_update_current_payroll($paydata);
    
        // Overrides
        $q = "SELECT 
                    `pcp`.`id` AS id,
                    '$payroll_date' AS `payroll_date`,
                    '$payroll_id' AS `payroll_id`,
                    `por`.`receiving_rep` AS `broker_id`,
                    SUM(`por`.`over_paid`) AS `override_paid`,
                    ROUND(SUM(`por`.`over_paid`)/SUM(`por`.`over_gross`)*100,2) AS `override_rate` 
                FROM `" .PAYROLL_OVERRIDE_RATES. "` `por`
                LEFT JOIN `".PAYROLL_CURRENT_PAYROLL."` `pcp` ON `pcp`.`broker_id`=`por`.`receiving_rep` AND `pcp`.`payroll_id`=$payroll_id 
                WHERE `por`.`is_delete` = 0 AND `por`.`payroll_id`=$payroll_id 
                GROUP BY `por`.`receiving_rep`, `pcp`.`id`, `payroll_date`, `payroll_id`
                ORDER BY `por`.`receiving_rep` 
        ";        
        $res = $this->re_db_query($q);
        $paydata = $this->re_db_fetch_all($res);
        $res = $this->insert_update_current_payroll($paydata);

        // Adjustments
        $q = "SELECT 
                `pcp`.`id`,
                `pac`.`broker_id`,
                '" .$payroll_date. "' AS `payroll_date`,
                '" .$payroll_id. "' AS `payroll_id`,
                SUM(`pac`.`adjustment_amount` * IF(`pac`.`pay_type`=3, `pac`.`pay_amount`*.01, 1)) AS `adjustments`,
                SUM(IF(`pac`.`taxable_adjustment`=1, `pac`.`adjustment_amount` * IF(`pac`.`pay_type`=3, `pac`.`pay_amount`*.01, 1), 0)) AS `taxable_adjustments`,
                SUM(IF(`pac`.`taxable_adjustment`='1', 0, `pac`.`adjustment_amount` * IF(`pac`.`pay_type`=3, `pac`.`pay_amount`*.01, 1))) AS `non-taxable_adjustments`
            FROM `" .PAYROLL_ADJUSTMENTS_CURRENT. "` `pac`
            LEFT JOIN `" .PAYROLL_CURRENT_PAYROLL. "` `pcp` ON `pac`.`broker_id` = `pcp`.`broker_id` AND `pcp`.`payroll_id`=$payroll_id
            WHERE `pac`.`is_delete` = 0 AND `pac`.`payroll_id`=$payroll_id 
            GROUP BY `pac`.`broker_id`, `pcp`.`id`, `payroll_date`, `payroll_id` 
            ORDER BY `pac`.`broker_id`, `pcp`.`id`, `payroll_date`
        ";
        $res = $this->re_db_query($q);
        $paydata = $this->re_db_fetch_all($res);
        $res = $this->insert_update_current_payroll($paydata);

        // Prior Period Balances
        $q = "SELECT 
                    `pcp`.`id`,
                    '" .$payroll_date. "' AS `payroll_date`,
                    `bbm`.`broker_id`,
                    `bbm`.`balance_amount` AS `balance`
                FROM " .BROKER_BALANCES_MASTER. " `bbm`
                LEFT JOIN `" .PAYROLL_CURRENT_PAYROLL. "` `pcp` ON `bbm`.`broker_id` = `pcp`.`broker_id`
                WHERE `bbm`.`is_delete`=0                
        ";
        $res = $this->re_db_query($q);
        $paydata = $this->re_db_fetch_all($res);
        $res = $this->insert_update_current_payroll($paydata);

        // Check Amount
        $payrollData = $this->select_current_payroll();
        $sysConfig = $this->check_minimum_check_amount();
        $updateData = [];
        foreach ($payrollData AS $paycheck) {
            $sipc = ROUND($paycheck['sipc_gross'] * $sysConfig['sipc'] * .01, 2);;
            $finra = ROUND($paycheck['sipc_gross'] * $sysConfig['finra'] * .01, 2);
            $checkAmount = $paycheck['commission_paid'] + $paycheck['split_paid'] + $paycheck['override_paid'] + $paycheck['adjustments'] + $paycheck['balance'] - $sipc - $finra;
            array_push($updateData, ['id'=>$paycheck['id'], 'sipc'=>$sipc, 'finra'=>$finra, 'check_amount'=>$checkAmount, 'minimum_check_amount'=>$sysConfig['minimum_check_amount']]);
        }

        if (count($updateData))
            $res = $this->insert_update_current_payroll($updateData);  

        return $res;
    }
 
    
    /** Clear all rows in the PAYROLL_CURRENT_PAYROLL table for Payroll Calculation
    * Re-populated in calculate_payroll()
    * @return bool  */
    function clearCurrentPayroll($payroll_id=0) {
        $return = true;

        $q = "DELETE FROM `" .PAYROLL_CURRENT_PAYROLL. "` WHERE ".($payroll_id ? "payroll_id=".$payroll_id : '1');
        $res = $this->re_db_query($q);
        
        if($res){
            $_SESSION['success'] = UPDATE_MESSAGE;
        } else {
            $_SESSION['warning'] = UNKWON_ERROR;

            $return = false;
        }

        return $return;
    }
    function clearSplitRates($payroll_id=0) {
        // Clear out the Payroll SPLIT Transactions - populated in Insert Split Rates method
        $q = "DELETE FROM `" .PAYROLL_SPLIT_RATES. "` WHERE ".($payroll_id ? "payroll_id=".$payroll_id : '1');
        $res = $this->re_db_query($q);
        $return = true;
        
        if($res){
            $_SESSION['success'] = UPDATE_MESSAGE;
            
            $q = "ALTER TABLE `" .PAYROLL_SPLIT_RATES. "` AUTO_INCREMENT = 1";
            $res = $this->re_db_query($q);

            if ($res){
                $_SESSION['success'] = UPDATE_MESSAGE;
            } else {
                $_SESSION['warning'] = UNKWON_ERROR;
                $return = false;
            }
        } else {
            $_SESSION['warning'] = UNKWON_ERROR;
            $return = false;
        }

        return $return;
    }
     /** Clear all rows in the OVERRIDE_RATE table for Payroll Calculation
     * Re-populated in calculateOverride()
     * @return bool  */
    function clearOverrides($payroll_id=0) {
        // Clear out the Override Transactions - populated in "overrideCalculation()"
        $q = "DELETE FROM `" .PAYROLL_OVERRIDE_RATES. "` WHERE ".($payroll_id ? "payroll_id=".$payroll_id : '1');
        $res = $this->re_db_query($q);
        $return = true;
        
        if($res){
            $q = "ALTER TABLE `" .PAYROLL_OVERRIDE_RATES. "` AUTO_INCREMENT = 1";
            $res = $this->re_db_query($q);

            $_SESSION['success'] = UPDATE_MESSAGE;
        } else {
            $_SESSION['warning'] = UNKWON_ERROR;
            $return = false;
        }

        return $return;
    }
    public function clearCurrentAdjustments ($payroll_id=0){
        // Clear out the Override Transactions - populated in "overrideCalculation()"
        $q = "DELETE FROM `" .PAYROLL_ADJUSTMENTS_CURRENT. "` WHERE ".($payroll_id ? "payroll_id=".$payroll_id : '1');
        $res = $this->re_db_query($q);
        $return = true;
        
        if($res){
            $q = "ALTER TABLE `" .PAYROLL_ADJUSTMENTS_CURRENT. "` AUTO_INCREMENT = 1";
            $res = $this->re_db_query($q);

            $_SESSION['success'] = UPDATE_MESSAGE;
        } else {
            $_SESSION['warning'] = UNKWON_ERROR;
            $return = false;
        }

        return $return;
    }


   /**  Populate PAYROLL_SPLIT_RATES table from PAYROLL_REVIEW_MASTER that exist in TRANSACTION_TRADE_SPLITS - 10/13/21
     *  @param int payroll_id 
     * @return void 
    **/
    function insert_payroll_split_rates ($payroll_id=0) {
        // *** Initialize the SPLIT RATE & PAYROLL MASTER tables for the splits ***
        $this->clearSplitRates($payroll_id);
        $payrollUpload = ($payroll_id==0) ? ['payroll_date'=>date('Y-m-d')] : $this->get_payroll_uploads($payroll_id);
        $payroll_date = $payrollUpload['payroll_date'];
        $payrollIdQuery = ($payroll_id==0) ? '' : " AND `pm`.`payroll_id`=$payroll_id";
        
        $q = "UPDATE `" .PAYROLL_REVIEW_MASTER. "` 
                SET `is_split`= 0, `split_rate`= 100" .$this->update_common_sql(). "
                WHERE payroll_id = $payroll_id
        ";
        $res = $this->re_db_query($q);
        
        // ***  Format data to insert into SPLIT RATES table structure ***
        $q = "SELECT 
                    `pm`.`payroll_id`,
                    `pm`.`id` AS `payable_transaction_id`, 
                    `pm`.`trade_number` AS `transaction_id`, 
                    `pm`.`broker_id`, 
                    `pm`.`commission_received`, 
                    `pm`.`charge` AS `trade_charge`, 
                    `pm`.`is_split`, 
                    `spl`.`id` AS `split_id`,
                    `spl`.`split_broker`, 
                    `spl`.`split_rate`, 
                    `spl`.`is_delete`
                FROM `".PAYROLL_REVIEW_MASTER."` AS `pm`
                LEFT JOIN `" .TRANSACTION_TRADE_SPLITS. "` `spl` ON `pm`.`trade_number` = `spl`.`transaction_id` AND `spl`.`is_delete`=0
                WHERE `pm`.`is_delete` = 0 $payrollIdQuery
                ORDER BY `trade_number`
        ";

        $res = $this->re_db_query($q);
        $splitTrades = $this->re_db_fetch_all($res);
        
        // Cycle through ALL the trades in Payroll Master to check if any trades NOT found in TRANSACTION SPLITS has a Broker Split involved
        foreach ($splitTrades AS $trade_key=>$trade_row) {
            $split_broker = $trade_row['split_broker'];
            
            if (is_null($split_broker)) {
                    // *** BROKER SPLITS ***
                // Check for Broker Split
                $getSplits = $this->get_split_rates($payroll_date, $trade_row['broker_id'], 2);
                
                if (count($getSplits)) {
                    // Calculate the charge and gross_commission
                    foreach ($getSplits AS $keyGetSplits=>$valueGetSplits) {
                        $split_rate  = $valueGetSplits['rate'];
                        $split_gross = round($trade_row['commission_received'] * $split_rate * .01, 2);
                        $split_charge = round((is_numeric($trade_row['trade_charge']) ? $trade_row['trade_charge'] :0) * $split_rate * .01, 2);
                        
                        // Only INSERT a new record if there is a split rate, otherwise it's a waste of time to process later
                        if ($trade_row['broker_id'] > 0  AND  $valueGetSplits['rap'] > 0  AND  $split_rate > 0)
                        $this->insert_into_split_rates($trade_row, $valueGetSplits['rap'], $valueGetSplits['id'], $valueGetSplits['rate'], $split_gross, $split_charge, 1);
                    }
                }
            } else {
                    // *** TRADE SPLITS ***
                // Calculate the charge and gross_commission
                $split_gross = round($trade_row['commission_received'] * $trade_row['split_rate'] * .01, 2);
                $split_charge = round($trade_row['trade_charge'] * $trade_row['split_rate'] * .01, 2);
                $split_rate  = $trade_row['split_rate'];

                        // Only INSERT a new record if there is a split rate
                if ($trade_row['broker_id'] > 0  AND  $trade_row['split_broker'] > 0  AND  $split_rate > 0)
                    $this->insert_into_split_rates($trade_row, $trade_row['split_broker'], $trade_row['split_id'], $trade_row['split_rate'], $split_gross, $split_charge);
            }
        }
    }
    /** Insert record into Payroll Split Rates table AND update Payroll Review Master table from the payroll calculation
     * @param mixed $trade 
     * @param int $split_broker 
     * @param int $split_id 
     * @param int $split_rate 
     * @param int $split_gross 
     * @param int $split_charge 
     * @param int $is_split_broker 
     * @return mixed 
     */
    function insert_into_split_rates($trade, $split_broker=0, $split_id=0, $split_rate = 0, $split_gross=0, $split_charge=0, $is_split_broker=0) {
        $q = "INSERT INTO `" .PAYROLL_SPLIT_RATES. "` SET ". 
                " `payroll_id` = '" .$trade['payroll_id']. "', ".  
                " `payable_transaction_id` = '" .$trade['payable_transaction_id']. "', ".  
                " `transaction_id` = '" .$trade['transaction_id']. "', ".  
                " `split_id` = '" .$split_id. "', ".  
                " `broker_id` = '" .$trade['broker_id']. "', ".  
                " `split_broker` = '" .$split_broker. "', ".  
                " `split_rate` = '" .$split_rate. "', ". 
                " `is_split_broker` = '" .$is_split_broker. "', ". 
                " `commission_received` = '" .$trade['commission_received']. "', ".  
                " `trade_charge` = '" .$trade['trade_charge']. "', ".  
                " `split_gross` = '" .$split_gross. "', ".  
                " `split_charge` = '" .$split_charge. "', ".  
                " `split_paid` = '0'"
                .$this->insert_common_sql();

        $res = $this->re_db_query($q);
    
        $q = "UPDATE `" .PAYROLL_REVIEW_MASTER. "` ". 
            "   SET `is_split`= `is_split` + 1, ".
            "       `split_rate` = `split_rate` - " .$split_rate
                    .$this->update_common_sql().
            " WHERE `is_delete` = 0 AND `id` = " .$trade['payable_transaction_id'];
        $res = $this->re_db_query($q);
        
        return $res;
    }
/** Insert\Update the Current_Payroll - summary of the Payroll Calculation (calculate_payroll) routine -> Final "Check Amount" for the broker
* @param array $array - 2-dimensional - inner array is an associative array ('fieldName'=>fieldValue) must have an "id" key 
* @return bool 
*/
public function insert_update_current_payroll ($data) {
    // Get column names for table-array column comparison later
    $q = "SHOW COLUMNS FROM ".PAYROLL_CURRENT_PAYROLL;
    $res = $this->re_db_query($q);
    $tableColumns = $this->re_db_fetch_all($res);
    $dataCopy = $data;
    
    $fieldValues = '';
    $fieldNames = '';
    $createdBySessionValues = $this->insert_common_sql(2);
    $fieldNameIndex = 0;

    foreach ($dataCopy AS $dataIndex=>$dataFields) {
        // ADD else UPDATE table
        if (!isset($dataFields['id']) OR empty($dataFields['id'])) {
            foreach ($tableColumns AS $tableFields) {
                if (!array_key_exists($tableFields['Field'], $dataFields)){
                    $data[$dataIndex][$tableFields['Field']] = '';
                }
            }
            foreach ($createdBySessionValues AS $createdByField=>$createdByValue) {
                $data[$dataIndex][$createdByField] = $createdByValue;
            }
            // Load the field values string to be bulk inserted below
            $fieldValues .= (empty($fieldValues)? "": ",")."(".$this->setString($data[$dataIndex], 2).")";
            $fieldNameIndex = $dataIndex;
        } else {
            $q = "UPDATE " .PAYROLL_CURRENT_PAYROLL. "
                    SET ".$this->setString($data[$dataIndex]).                            
                        $this->update_common_sql()." 
                    WHERE `id`='" .$data[$dataIndex]['id']. "'"
                ;
            $res = $this->re_db_query($q);
        }
    }
    
    if (!empty($fieldValues)){
        $fieldNames = $this->setString($data[$fieldNameIndex], 3);
        $q = "INSERT INTO ".PAYROLL_CURRENT_PAYROLL." (".$fieldNames.") VALUES ".$fieldValues;
        $res = $this->re_db_query($q);
    }    

    if ($res){
        $_SESSION['success'] = INSERT_MESSAGE;
    } else {
        $_SESSION['warning'] = UNKWON_ERROR;
    }

    return $res;
}
/** Insert/Update specified table from a 2-dimensional array derived from a SQL Query
     * @param mixed $table 
     * @param mixed $data 
     * @return void 
     */
public function insert_update_generic ($table, $data) {
    // Get column names for table-array column comparison later
    $q = "SHOW COLUMNS FROM ".$table;
    $res = $this->re_db_query($q);
    $tableColumns = $this->re_db_fetch_all($res);

    $fieldValues = '';
    $createdBySessionValues = $this->insert_common_sql(2);
    $updateData = [];
    
    foreach ($data AS $dataIndex=>$dataFields) {
        // Only INSERT/UPDATE fields that exist in $data
        foreach ($tableColumns AS $tableFields) {
            if (array_key_exists($tableFields['Field'], $dataFields)){
                $updateData[$tableFields['Field']] = $dataFields[$tableFields['Field']];
            }
        }

        // ADD else UPDATE table
        if (!isset($dataFields['id']) OR empty($dataFields['id'])) {
            foreach ($createdBySessionValues AS $createdByField=>$createdByValue) {
                $updateData[$createdByField] = $createdByValue;
            }
            // Load the field values string to be bulk inserted below
            $fieldValues .= (empty($fieldValues)? "": ",")."(".$this->setString($updateData, 2).")";
        } else {
            if (count($updateData)>1){
                $q = "UPDATE " .$table. "
                        SET ".$this->setString($updateData).$this->update_common_sql()." 
                        WHERE `id`='" .$updateData['id']. "'"
                ;
                $res = $this->re_db_query($q);
            }
        }
    }

    if (!empty($fieldValues)){
        $fieldNames = $this->setString($updateData, 3);
        $q = "INSERT INTO ".$table." (".$fieldNames.") VALUES ".$fieldValues;
        $res = $this->re_db_query($q);
    }    

    if ($res){
        $_SESSION['success'] = INSERT_MESSAGE;
    } else {
        $_SESSION['warning'] = UNKWON_ERROR;
    }

    return $res;
}


    /** Broker Sliding Rate set up function - 10/16/21 li
    * @param array $broker_payout_master 
    * @return float rate -> Sliding Scale Rate 
    * =populates "basis_total" for use in calling functions. It's the sum of the "Basis" field given the specified date range criteria (Cumulative, Reset, Year)
    */
    function slideRate($broker_payout_master=[], $pay_date='', &$basis_total=0) {
        // Basis - (2)Net Commission, (1)Gross Concession
        // *Backwards on the manage_broker.tpl.php screen: Net Commission is on the left, and Gross is on the right
        $basis = $broker_payout_master['basis'];
        $basis_field = $basis==1 ? 'gross_production' : 'net_production';
        // Cumulative - (1)Payroll-To-Date, (2)YTD
        $cumulative = $broker_payout_master['cumulative'];
        // Reset - when to reset the aggregation
        $reset = $broker_payout_master['reset'];
        // Year - What "year" is - (1)Calendar - any 12 month period, (2)Rolling - measured back from a given date
        $year = $broker_payout_master['year'];
        // When a Threshold is crossed - (1)Incremental - check each trade to see if the broker has crossed a breakpoint, (2)Apply Higher Payout Rate - get the highest rate and apply to all subsequent trades
        $calculation_detail = $broker_payout_master['calculation_detail'];
        // Date Range for the query
        $end_date = $pay_date;
        // Start Date: 1=Calendar(January 1st), 2=Rolling(1 year prior to ending date), 
        if ($reset != '' AND $reset < $pay_date) {
            $start_date = $reset;
        } elseif ($year == 1) {
            $start_date = substr($end_date, 0, 4) .'-01-01';
        } else {
            $start_date = date_sub(date_create($end_date), date_interval_create_from_date_string('1 year'));
            $start_date = date_add($start_date, date_interval_create_from_date_string('1 day'))->format('Y-m-d');
        }

        // *** BROKER RATE GRID *** //
        $q = "SELECT * FROM `" .BROKER_PAYOUT_GRID. "` WHERE `is_delete`='0' AND `broker_id`= '".$broker_payout_master['broker_id']. "' ORDER BY broker_id, id";
        $res = $this->re_db_query($q);
        $slide_grid_array = $this->re_db_fetch_all($res);
        
        // *** AGGREGATE the data from the Prior Payroll Master *** //
        // Cumulative: 1.Payroll-To-Date(use the Current Payroll: PAYROLL_REVIEW_MASTER), 2.Year-To-Date(PRIOR PAYROLL MASTER)
        if ($cumulative == 1) {
        } else {
            $q = "SELECT sum(`" .$basis_field. "`) as basis_total". 
                    " FROM `" .PRIOR_PAYROLL_MASTER. "`".
                    " WHERE `is_delete`='0' ".
                    "   AND `broker_id`='" .$broker_payout_master['broker_id']. "'".
                    "   AND `payroll_date` BETWEEN '" .$start_date. "' AND '" .$end_date. "'".
                    " GROUP BY broker_id";
        
            $res = $this->re_db_query($q);
            $basis_query = $this->re_db_fetch_array($res);
            $basis_total = round($basis_query['basis_total'], 2);
        }
        
        // *** RATE - Call the function to get the correct rate per the Sliding Scale Grid *** //
        $slideLevel = $this->getBreakpoint($slide_grid_array, $basis_total, true);
        $return = 0;
        
        if ($slideLevel >= 0) {
            $return = round($slide_grid_array[$slideLevel]['per'] / 100, 4);
        }
        return $return;
    }
    /** Find the breakpoint in a sliding scale grid for a given Commission Amount
     * @param array $gridArray -> Array created by the query on BROKER_PAYOUT_GRID table fields (from, to)
     * @param int $comm 
     * @param int $defaultToLast -> Default payout to the last breakpoint, if $comm is greater than the last breakpoint
     * @return null|int -> Row # of the array
    */
    function getBreakpoint ($gridArray=array(), $comm=0, $defaultToLast=0) {
        $return = null;

        if (count($gridArray)){
            foreach ($gridArray as $key => $level){
                if ($comm >= $level['from'] AND $comm <= $level['to']) {
                    $return = $key;
                    break;
                }
            }
    
            // If the Comm is larger than the last breakpoint, default to the last payout rate
            if (is_null($return) AND $defaultToLast AND $comm > $gridArray[$key]['to']){
                $return = $key;
            }
        }

        return is_null($return) ? -1 : $return;
    }
    /** Filter out adjustments by payroll_date=>Recurring_Type->Name
     * @param array 
     * @return array 
     */
    public function checkRecurringType($data) {
        $return = 0;
        $payrollDay = date('d', strtotime($data['payroll_date']));
        $payrollMonth = date('m', strtotime($data['payroll_date']));
        
        // Day Validation
        if (empty($data['recurring']) OR preg_match('/Both(.*)Payrolls/i', $data['type_name']) OR empty($data['type_name'])) {
            $return = 1;
        } else if (preg_match('/Mid(.*)Month/i', $data['type_name'])) {
            $return = ($payrollDay < 16) ? 1: 0;
        } else if (preg_match('/End(.*)Month/i', $data['type_name'])){
            $return = ($payrollDay >= 16) ? 1: 0;
        } else {
            $return = 1;
        }
        
        // Month Validation
        if (empty($data['recurring']) OR preg_match('/Both(.*)Payrolls/i', $data['type_name']) OR empty($data['type_name'])) {
            $return += 1;
        } else if (preg_match('/Semi(.*)Annual(.*)/i', $data['type_name'])) {
            $return += in_array($payrollMonth, [1,7]) ? 1: 0;
        } else if (preg_match('/Quarter(.*)/i', $data['type_name'])){
                $return += in_array($payrollMonth, [1,4,7,10]) ? 1: 0;
        } else {
            $return += 1;
        }

        return ($return == 2);
    }

    public function get_override_rates($in_date='',$broker='',$product_category='',$receiveOrGive=2,$brokerOrTrade=1,$trade_id=0) {
        $return = array();
        $con='';
        
        if($broker>0 && $receiveOrGive == 2)
            $con.= " AND `ovr`.`broker_id` = '".$broker."'";

        if($broker>0 && $receiveOrGive == 1)
            $con.= " AND `ovr`.`rap` = '".$broker."'";

        if($product_category>0)
            $con.= " AND (`ovr`.`product_category` = '".$product_category."' or `ovr`.`product_category` = '0')";

        if($in_date != '')
            $con.= " AND '".date('Y-m-d',strtotime($in_date))."' BETWEEN `ovr`.`from` AND `ovr`.`to` ";

        if ($brokerOrTrade == 1) {
            $q = "SELECT `ovr`.*, 1 AS `is_override_broker`
                    FROM `".BROKER_MASTER."` AS `bm`
                    LEFT JOIN `".BROKER_PAYOUT_OVERRIDE."` as `ovr` on `ovr`.`broker_id` = `bm`.`id`
                    WHERE `ovr`.`is_delete`='0' ".$con."
                    ORDER BY `ovr`.`id` ASC"
                ;
        } else {
            $q = "SELECT `tvr`.`id`, `tvr`.`receiving_rep` AS `rap`, `tvr`.`per`, `tvr`.`transaction_id` AS `trade_number`, 0 AS `is_override_broker`
                    FROM `" .TRANSACTION_OVERRIDES. "` AS `tvr`
                    WHERE `tvr`.`is_delete`='0' AND  `tvr`.`transaction_id` = '" .$trade_id. "' 
                    ORDER BY `tvr`.`id` ASC"
                ;
        }
        
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

    /***
    4/29/22 Moved to "payroll" class so all may enjoy! - especially Payroll Summary Report
    ***/
    // public function select_current_payroll($id=0) {
    //     $con = '';
    //     if ($id > 0){
    //         $con .= " AND `pcp`.`payroll_id` = '".$id."'";
    //     }

    //     $q = "SELECT *
    //             FROM `" .PAYROLL_CURRENT_PAYROLL. "` `pcp`
    //             WHERE `pcp`.`is_delete`='0' ".$con." 
    //     ";

    //     $res = $this->re_db_query($q);
    //     return  $this->re_db_fetch_all($res);
    // }
    
    public function select_active_brokers($payroll_date) {
        $q = "SELECT 
                    `bm`.`id` AS `broker_id`, 
                    `bm`.`first_name`, 
                    `bm`.`last_name`,
                    `bm`.`active_status`, 
                    `bg`.`u5`,
                    `bg`.`day_after_u5`
                FROM `" .BROKER_MASTER. "` `bm`
                LEFT JOIN (SELECT `broker_id`, `u5`, `day_after_u5` FROM `" .BROKER_GENERAL. "` WHERE `is_delete`=0) `bg` ON `bm`.`id`=`bg`.`broker_id`
                WHERE `bm`.`is_delete`='0' 
                AND ( (`bm`.`active_status`='1') OR ('" .$payroll_date. "' <= DATE_ADD(`bg`.`u5`, INTERVAL TRIM(`bg`.`day_after_u5`) DAY)) )
        ";

        $res = $this->re_db_query($q);
        return  $this->re_db_fetch_all($res);
    }
    /** Payroll Master Transactions including Splits
     * Called by Payroll Calculation for computing Commission_Paid
     * - Commission_Received and Charge fields adjusted for split %'s
     * - Created 10/12/21 li 
     * @return array 
     */
    function select_payroll_calculation_transactions($payroll_id=0) {
        $payrollIdQuery1 = ($payroll_id==0) ? "" : " AND pm1.payroll_id=$payroll_id";
        $payrollIdQuery  = ($payroll_id==0) ? "" : " AND pm.payroll_id=$payroll_id";

        $q = "
                SELECT  CONCAT(LPAD(CONVERT(pm1.broker_id,char),6,'0'), DATE_FORMAT(pm1.trade_date, '%Y%m%d'), LPAD(CONVERT(pm1.product_category,char),6,'0'))  AS sort_index,
                        pm1.trade_number,
                        pm1.id AS payable_transaction_id,
                        pm1.trade_number AS transaction_id, 
                        0 AS split_id,
                        pm1.broker_id, 
                        trade_date, 
                        pm1.product_category,
                        pm1.investment_amount, 
                        pm1.commission_received AS commission_full, 
                        ROUND(pm1.commission_received * pm1.split_rate * .01, 2) AS commission_received,
                        pm1.charge AS charge_full,
                        ROUND(pm1.charge * pm1.split_rate * .01, 2) AS charge,
                        000 AS payout_rate,
                        pm1.net_commission, 
                        0 AS `commission_paid`, 
                        pm1.is_split,
                        pm1.split_rate, 
                        000000000 as split_ctrl, 
                        000000.00 as split_paid, 
                        pm1.payroll_id
                    FROM " .PAYROLL_REVIEW_MASTER. " AS `pm1`
                    WHERE pm1.is_delete = 0 $payrollIdQuery1
                UNION ALL
                SELECT  CONCAT(LPAD(CONVERT(spl.split_broker,char),6,'0'), DATE_FORMAT(pm.trade_date, '%Y%m%d'), LPAD(CONVERT(pm.product_category,char),6,'0')) AS sort_index,
                        pm.trade_number,
                        spl.payable_transaction_id,
                        spl.transaction_id, 
                        spl.id AS split_id,
                        spl.split_broker AS `broker_id`, 
                        pm.trade_date, 
                        pm.product_category,
                        pm.investment_amount,
                        pm.commission_received AS commission_full, 
                        ROUND(pm.commission_received * spl.split_rate * .01, 2) AS commission_received,
                        pm.charge AS charge_full,
                        ROUND(pm.charge * spl.split_rate * .01, 2) AS charge,
                        000 AS payout_rate,
                        pm.net_commission, 
                        0 AS `commission_paid`, 
                        99 AS is_split,
                        spl.split_rate, 
                        pm.broker_id AS `split_ctrl`, 
                        spl.split_paid,
                        pm.payroll_id
                    FROM " .PAYROLL_REVIEW_MASTER. " AS pm
                         JOIN `".PAYROLL_SPLIT_RATES."` spl ON pm.id = spl.payable_transaction_id AND spl.is_delete=0
                    WHERE pm.is_delete = 0 $payrollIdQuery
                    ORDER BY sort_index
        ";

        $res = $this->re_db_query($q);
        return $this->re_db_fetch_all($res);
    }


    /** Convert associative array of fields into a comma delimited string 
     * @param array $fields - associative array of table: column=>value
     * @param int $setOrValues - string to return: 1) `field`='value' -> for SQL UPDATE SET ... 'value' is formatted for MySQL Insertion, (2) Value String, (3) Field List
     *  -> 2 & 3 are the similar implode(array), but code already written for SET(#1) so added for readability in the code - 10/18/21 li
     * @return string 
    */
    public function setString ($fields=[], $setOrValues=1) {
        global $payroll;

        $sqlSetString = $setStringValue = $valueList = $fieldList = '';

        foreach ($fields AS $fieldName=>$fieldValue){
            // Don't surround formulas with single quotes, SET `field`='`field` + 100' -> takes assignment as a literal string(populates field with 0)
            // - Flag formulas(SET `fieldName` = `fieldName` + 'someValue') by surrounding it with '@@' pattern for now. If you have a better solution, use it! 10/19/21 li
            if (substr($fieldValue,0,2)=='@@' AND substr($fieldValue,-2)=='@@') {
                $setStringValue = substr($this->re_db_input($fieldValue), 2, -2);
            } else {
                $setStringValue = "'".$this->re_db_input($fieldValue)."'";
            }

            $sqlSetString .= (empty($sqlSetString)?"":",") ."`".$fieldName. "`=".$setStringValue."";
            $valueList .= (empty($valueList)?"":",") ."'".$this->re_db_input($fieldValue)."'";
            $fieldList .= (empty($fieldList)?"":",")."`".$fieldName."`";
        }

        if ($setOrValues == 1) {
            return $sqlSetString;
        } else if ($setOrValues == 2){
            return $valueList;
        } else {
            return $fieldList;
        }
    }
}
?>