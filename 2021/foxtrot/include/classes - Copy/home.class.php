<?php

    class home extends db{
        
        public $errors = '';
        
        public function select_invest_amount($con=''){
            
			$q = "SELECT SUM(invest_amount) as count 
					FROM `".TRANSACTION_MASTER."` WHERE `is_delete`='0' ".$con." "; 
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function select_charge_amount($con=''){
           
			$q = "SELECT SUM(charge_amount) as count 
					FROM `".TRANSACTION_MASTER."` WHERE `is_delete`='0' ".$con." "; 
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function select_commission_received_amount($con=''){
           
			$q = "SELECT SUM(commission_received) as count 
					FROM `".TRANSACTION_MASTER."` WHERE `is_delete`='0' ".$con." "; 
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function select_daily_import_completed_files($con=''){
           
			$q = "SELECT COUNT(*) as total_completed_files
					FROM `".IMPORT_CURRENT_FILES."` WHERE `processed`='1' and `process_completed`='1' and `is_archived`='0' and `is_delete`='0' ".$con." ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function select_daily_import_process_files_0($con=''){//for all process files
           
			$ins = new import();
            $files_array = array();
            $total_complete_process = 0;
			$q = "SELECT *
					FROM `".IMPORT_CURRENT_FILES."` WHERE `processed`='1' and `process_completed`='0' and `is_delete`='0' ".$con." ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                while($row = $this->re_db_fetch_array($res)){
                    if(isset($row['source']) && $row['source'] == 'DSTFANMail')
                    {
                        $total_complete_process = 0;//$ins->get_process_per($row['id'],1);
                    }else{
                        
                        $total_complete_process = 0;//$ins->get_process_per($row['id'],2);
                    }
                    
                    if($total_complete_process <= 0)
                    {
                        $files_array[]=$row['id'];
                    }
                }
                $result['total_processed_files_at_0'] = count($files_array);
    			$return = $result;
            }
			return $return;
		}
        public function select_daily_import_process_files($con=''){
            $ins = new import();
            $files_array = array();
            $total_complete_process = 0;
			$q = "SELECT *
					FROM `".IMPORT_CURRENT_FILES."` WHERE `processed`='1' and `process_completed`='0' and `is_delete`='0' ".$con." ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                while($row = $this->re_db_fetch_array($res)){
                    if(isset($row['source']) && $row['source'] == 'DSTFANMail')
                    {
                        $total_complete_process = 0;//$ins->get_process_per($row['id'],1);
                    }else{
                        
                        $total_complete_process = 0; //$ins->get_process_per($row['id'],2);
                    }
                    
                    if($total_complete_process > 0)
                    {
                        $files_array[]=$row['id'];
                    }
                }
                $result['total_processed_files'] = count($files_array);
    			$return = $result;
            }
			return $return;
		}
        public function select_daily_import_new_files($con=''){
           
			$q = "SELECT COUNT(*) as total_new_files
					FROM `".IMPORT_CURRENT_FILES."` WHERE `processed`='0' and `process_completed`='0' and `is_delete`='0' ".$con." ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function select_ytd_amount_list($con=''){
           
            $return = array();
            $return_row = array();
            
            $q = "SELECT `t`.`product_cate`, SUM(`t`.`invest_amount`) AS total_ytd_investment_amount,`pc`.`type` AS product_category, 
            IFNULL((SELECT SUM(ftm.commission_received) 
            FROM `".TRANSACTION_MASTER."` as ftm 
            WHERE `t`.`product_cate`=ftm.product_cate AND ftm.`is_delete`='0' AND ftm.`hold_commission`='2' 
            GROUP BY ftm.`product_cate`),0) AS total_ytd_commission_received, 
            
            IFNULL((SELECT SUM(ftma.commission_received) 
            FROM `".TRANSACTION_MASTER."` as ftma 
            WHERE `t`.`product_cate`=ftma.product_cate AND ftma.`is_delete`='0' AND ftma.`hold_commission`='1' 
            GROUP BY ftma.`product_cate`),0) AS total_ytd_commission_pending 
            
            FROM `".TRANSACTION_MASTER."` AS `t` 
            LEFT JOIN `".PRODUCT_TYPE."` AS `pc` ON `pc`.`id`=`t`.`product_cate` 
            WHERE `t`.`is_delete`='0' ".$con." 
            GROUP BY `t`.`product_cate` 
            ORDER BY `t`.`product_cate` ASC";
            
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     $return_row['product_cate'][]= $row['product_cate'];
                     $return_row['product_category'][]= $row['product_category'];
                     $return_row['total_ytd_investment_amount'][]= $row['total_ytd_investment_amount'];
                     $return_row['total_ytd_commission_received'][]= $row['total_ytd_commission_received'];
                     $return_row['total_ytd_commission_pending'][]= $row['total_ytd_commission_pending'];
    			}
                $return = $return_row;
            }
            return $return;
		}
        public function select_ytd_amount($con=''){
           
            $return = array();
            $return_row = array();
            
            $q = "SELECT `t`.`product_cate`,MONTH(`t`.`created_time`) as month, SUM(`t`.`invest_amount`) AS total_ytd_investment_amount,`pc`.`type` AS product_category, 
            IFNULL((SELECT SUM(ftm.commission_received) 
            FROM `".TRANSACTION_MASTER."` as ftm 
            WHERE MONTH(`t`.`created_time`) = MONTH(`ftm`.`created_time`) AND ftm.`is_delete`='0' AND ftm.`hold_commission`='2' 
            GROUP BY MONTH(`ftm`.`created_time`)),0) AS total_ytd_commission_received,
            
            IFNULL((SELECT SUM(ftma.commission_received) 
            FROM `".TRANSACTION_MASTER."` as ftma 
            WHERE MONTH(`t`.`created_time`) = MONTH(`ftma`.`created_time`) AND ftma.`is_delete`='0' AND ftma.`hold_commission`='1' 
            GROUP BY MONTH(`ftma`.`created_time`)),0) AS total_ytd_commission_pending 
            
            FROM `".TRANSACTION_MASTER."` AS `t` 
            LEFT JOIN `".PRODUCT_TYPE."` AS `pc` ON `pc`.`id`=`t`.`product_cate` 
            WHERE `t`.`is_delete`='0' ".$con." 
            GROUP BY MONTH(`t`.`created_time`) 
            ORDER BY MONTH(`t`.`created_time`) ASC";
            
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     $return_row[$row['month']]['month'] = $row['month'];
                     $return_row[$row['month']]['product_cate'] = $row['product_cate'];
                     $return_row[$row['month']]['product_category'] = $row['product_category'];
                     $return_row[$row['month']]['total_ytd_investment_amount'] = $row['total_ytd_investment_amount'];
                     $return_row[$row['month']]['total_ytd_commission_received'] = $row['total_ytd_commission_received'];
                     $return_row[$row['month']]['total_ytd_commission_pending'] = $row['total_ytd_commission_pending'];
                     
                     /*$return_row[$row['month']]['month'][]= $row['month'];
                     $return_row[$row['month']]['product_cate'][]= $row['product_cate'];
                     $return_row[$row['month']]['product_category'][]= $row['product_category'];
                     $return_row[$row['month']]['total_ytd_investment_amount'][]= $row['total_ytd_investment_amount'];
                     $return_row[$row['month']]['total_ytd_commission_received'][]= $row['total_ytd_commission_received'];
                     $return_row[$row['month']]['total_ytd_commission_pending'][]= $row['total_ytd_commission_pending'];*/
    			}
                $return = $return_row;//echo '<pre>';print_r($return);exit;
            }
            return $return;
		}
        public function select_processed_commission($con=''){
            $return = array();
            
			$q = "SELECT COUNT(*) as total_processed_commission,MONTH(created_time) as month
            FROM `".TRANSACTION_MASTER."` WHERE `is_delete`='0' and `hold_commission`='2' ".$con." GROUP BY MONTH(created_time)";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                while($row = $this->re_db_fetch_array($res)){
                     $return[$row['month']] = $row['total_processed_commission'];
                }
            }
			return $return;
		}
        public function select_hold_commission($con=''){
            $return = array();
            
            $q = "SELECT COUNT(*) as total_hold_commission,MONTH(created_time) as month
            FROM `".TRANSACTION_MASTER."` WHERE `is_delete`='0' and `hold_commission`='1' ".$con." GROUP BY MONTH(created_time)";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                while($row = $this->re_db_fetch_array($res)){
                     $return[$row['month']] = $row['total_hold_commission'];
                }
            }
			
			return $return;
		}
        public function select_payroll_data_list($con=''){
           
            $return = array();
            
            $q = "SELECT `up`.*,SUM(`rp`.`commission_received`) as gross_commission,SUM(`rp`.`charge`) as charge,SUM(`rp`.`commission_paid`) as net_commission,SUM(`rp`.`adjustments`) as adjustments,SUM(`rp`.`balance`) as balance,SUM(`bm`.`check_amount`) as batch_check_amount,SUM(`rp`.`check_amount`) as check_amount
				  FROM `".PAYROLL_UPLOAD."` AS `up`
                  LEFT JOIN `".PAYROLL_REVIEW_MASTER."` AS `rp` on `rp`.`payroll_id`=`up`.`id`
                  LEFT JOIN `".TRANSACTION_MASTER."` AS `tm` on `tm`.`id`=`rp`.`trade_number`
                  LEFT JOIN `".BATCH_MASTER."` AS `bm` on `bm`.`id`=`tm`.`batch`
                  WHERE `up`.`is_delete`='0' and `up`.`is_close`='1' ".$con." 
                  ORDER BY `up`.`id` DESC limit 1";
            
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			   $return = $row;
    			}
            }
            return $return;
		}
        public function select_payroll_data_chart($con=''){
           
            $return = array();
            $return_row = array();
            
            $q = "SELECT `up`.*,`rp`.`product_category`,`pt`.`type` AS product_category_name,SUM(`rp`.`commission_received`) as gross_commission,SUM(`rp`.`charge`) as charge,SUM(`rp`.`commission_paid`) as net_commission,SUM(`rp`.`adjustments`) as adjustments,SUM(`rp`.`balance`) as balance,SUM(`bm`.`check_amount`) as batch_check_amount,SUM(`rp`.`check_amount`) as check_amount
				  FROM `".PAYROLL_UPLOAD."` AS `up`
                  LEFT JOIN `".PAYROLL_REVIEW_MASTER."` AS `rp` on `rp`.`payroll_id`=`up`.`id`
                  LEFT JOIN `".TRANSACTION_MASTER."` AS `tm` on `tm`.`id`=`rp`.`trade_number`
                  LEFT JOIN `".BATCH_MASTER."` AS `bm` on `bm`.`id`=`tm`.`batch`
                  LEFT JOIN `".PRODUCT_TYPE."` AS `pt` on `pt`.`id`=`rp`.`product_category`
                  WHERE `up`.`is_delete`='0' and `up`.`is_close`='1' ".$con." 
                  GROUP BY `rp`.`product_category` ORDER BY `rp`.`product_category` ASC";
            
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			 if($row['product_category_name'] && $row['product_category'] != '')
                 {
        			 $return_row['product_category'][]= $row['product_category_name'];
                     $return_row['total_gross_commission'][]= isset($row['gross_commission']) && $row['gross_commission'] != ''?$row['gross_commission']:0;
                     $return_row['total_net_commission'][]= isset($row['net_commission']) && $row['net_commission'] != ''?$row['net_commission']:0;
                     $retention = $row['gross_commission']-$row['net_commission'];
                     $return_row['total_retention'][] = isset($retention) && $retention != ''?$retention:0;
                 }
  			    }
                $return = $return_row;
            }
            return $return;
		}
        
    }
?>
