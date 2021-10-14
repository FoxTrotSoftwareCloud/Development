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
					FROM `".IMPORT_CURRENT_FILES."` WHERE `processed`='1' and `process_completed`='1' and `is_delete`='0' and `user_id`='".$_SESSION['user_id']."' ".$con." ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function select_daily_import_process_files($con=''){
           
			$q = "SELECT COUNT(*) as total_processed_files
					FROM `".IMPORT_CURRENT_FILES."` WHERE `processed`='1' and `process_completed`='0' and `is_delete`='0' and `user_id`='".$_SESSION['user_id']."' ".$con." ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function select_daily_import_new_files($con=''){
           
			$q = "SELECT COUNT(*) as total_new_files
					FROM `".IMPORT_CURRENT_FILES."` WHERE `processed`='0' and `process_completed`='0' and `is_delete`='0' and `user_id`='".$_SESSION['user_id']."' ".$con." ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function select_ytd_amount($con=''){
           
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
        
    }
?>