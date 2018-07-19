<?php

    class batches extends db{
        
        public $errors = '';
        public $table = BATCH_MASTER;
        
        public function insert_update($data){//echo '<pre>';print_r($data);exit;
            
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
            $pro_category= isset($data['pro_category'])?$this->re_db_input($data['pro_category']):'';
            //$batch_number= isset($data['batch_number'])?$this->re_db_input($data['batch_number']):'';
            $batch_desc= isset($data['batch_desc'])?$this->re_db_input($data['batch_desc']):'';
            $sponsor= isset($data['sponsor'])?$this->re_db_input($data['sponsor']):'';
            $batch_date= isset($data['batch_date'])?$this->re_db_input($data['batch_date']):'';
            if($batch_date != '')
            {
                $batch_date = date('Y-m-d',strtotime($batch_date));
            }
            $deposit_date= isset($data['deposit_date'])?$this->re_db_input($data['deposit_date']):'';
            if($deposit_date != '')
            {
                $deposit_date = date('Y-m-d',strtotime($deposit_date));
            }
            $trade_start_date= isset($data['trade_start_date'])?$this->re_db_input($data['trade_start_date']):'';
            if($trade_start_date != '')
            {
                $trade_start_date = date('Y-m-d',strtotime($trade_start_date));
            }
            $trade_end_date= isset($data['trade_end_date'])?$this->re_db_input($data['trade_end_date']):'';
            if($trade_end_date != '')
            {
                $trade_end_date = date('Y-m-d',strtotime($trade_end_date));
            }
            $check_amount= isset($data['check_amount'])?$this->re_db_input($data['check_amount']):0;
            //$check_amount = str_replace(",", '', $check_amount_mask);
            $commission_amount= isset($data['commission_amount'])?$this->re_db_input($data['commission_amount']):0;
            $split= isset($data['split'])?$this->re_db_input($data['split']):'';
            $prompt_for_check_amount= isset($data['prompt_for_check_amount'])?$this->re_db_input($data['prompt_for_check_amount']):0;
            $posted_amounts= isset($data['posted_amounts'])?$this->re_db_input($data['posted_amounts']):0;
            			
			if($pro_category==''){
				$this->errors = 'Please select product category.';
			}
            /*else if($batch_number==''){
				$this->errors = 'Please enter batch number';
			}*/
			else if($sponsor==''){
				$this->errors = 'Please select sponsor.';
			}
			if($this->errors!=''){
				return $this->errors;
			}
			else{
				if($id>=0){
					if($id==0){
						$q = "INSERT INTO ".$this->table." SET `pro_category`='".$pro_category."',`batch_desc`='".$batch_desc."',
                        `sponsor`='".$sponsor."',`batch_date`='".$batch_date."',`deposit_date`='".$deposit_date."',`trade_start_date`='".$trade_start_date."',
                        `trade_end_date`='".$trade_end_date."',`check_amount`='".$check_amount."',`commission_amount`='".$commission_amount."',`split`='".$split."',
                        `prompt_for_check_amount`='".$prompt_for_check_amount."',`posted_amounts`='".$posted_amounts."'".$this->insert_common_sql();
						$res = $this->re_db_query($q);
                        $_SESSION['last_inserted_batch_id'] = $this->re_db_insert_id();
                        
                        if($res){
						    $_SESSION['success'] = 'Batch Number '.$_SESSION['last_inserted_batch_id'].' successfully saved';
							return true;
						}
						else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}
					}
					else if($id>0){
						$q = "UPDATE ".$this->table." SET `pro_category`='".$pro_category."',`batch_desc`='".$batch_desc."',
                        `sponsor`='".$sponsor."',`batch_date`='".$batch_date."',`deposit_date`='".$deposit_date."',`trade_start_date`='".$trade_start_date."',
                        `trade_end_date`='".$trade_end_date."',`check_amount`='".$check_amount."',`commission_amount`='".$commission_amount."',`split`='".$split."',
                        `prompt_for_check_amount`='".$prompt_for_check_amount."',`posted_amounts`='".$posted_amounts."'".$this->update_common_sql()." WHERE `id`='".$id."'";
                        $res = $this->re_db_query($q);
						if($res){
						    $_SESSION['success'] = 'Batch Number '.$_SESSION['last_inserted_batch_id'].' successfully updated';
							return true;
						}
						else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}
					}
				}
				else{
					$_SESSION['warning'] = UNKWON_ERROR;
					return false;
				}
			}
		}
        public function edit_batches($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM ".$this->table." AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function select(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`batch_date` DESC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        public function get_commission_total($id=''){
			$return = array();
			
			$q = "SELECT SUM(commission_received) as posted_commission_amount
					FROM `".TRANSACTION_MASTER."`
                    WHERE is_delete='0' and batch=".$id."
                    ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function get_trade_date($id=''){
			$return = '';
			
			$q = "SELECT `trade_date`
					FROM `".TRANSACTION_MASTER."`
                    WHERE is_delete='0' and batch=".$id." 
                    ORDER BY `id` DESC LIMIT 1
                    ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['trade_date'];
    			}
            }
			return $return;
		}
        public function get_product_type($id){
			$return = '';
			
			$q = "SELECT `type`
					FROM `".PRODUCT_TYPE."`
                    WHERE is_delete='0' and id=".$id." 
                    ORDER BY `id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['type'];
    			}
            }
			return $return;
		}
        public function search_batch($data){
            
            $search_type= isset($data['search_type'])?$this->re_db_input($data['search_type']):'';
            $search_text_batches= isset($data['search_text_batches'])?$this->re_db_input($data['search_text_batches']):'';
            
			$return = array();
			if($search_type==''){
				$this->errors = 'Please select search type.';
			}
            if($search_type=='batch_number' || $search_type=='batch_date'){
                $q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `".$search_type."` like '".$search_text_batches."%' and `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
            }
            else if($search_type=='pro_category'){
                $q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `".$search_type."` in (SELECT `id` FROM ".PRODUCT_TYPE." where `type` like '".$search_text_batches."%')and `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
            }
            else if($search_type=='sponsor'){
                $q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `".$search_type."` in (SELECT `id` FROM ".SPONSOR_MASTER." where `name` like '".$search_text_batches."%') and `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
            }
            else{
                $q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";   
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
        public function delete($id){
			$id = trim($this->re_db_input($id));
			if($id>0){
				$q = "UPDATE `".$this->table."` SET `is_delete`='1' WHERE `id`='".$id."'";
				$res = $this->re_db_query($q);
                $q = "UPDATE `".TRANSACTION_MASTER."` SET `is_delete`='1' WHERE `batch`='".$id."'";
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
        public function unpost_trades($id){
			$id = trim($this->re_db_input($id));
			if($id>0){
				$q = "UPDATE `".TRANSACTION_MASTER."` SET `commission_received`='0',`commission_received_date`='',`posting_date`='' WHERE `batch`='".$id."'";
				$res = $this->re_db_query($q);
                $q = "UPDATE `".$this->table."` SET `commission_amount`= '0' WHERE `id`='".$id."'";
				$res = $this->re_db_query($q);
				if($res){
				    $_SESSION['success'] = 'Trades are successfully unposted';
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
        public function get_all_category_batch_data($product_category='',$company='',$batch='',$beginning_date='',$ending_date='',$sort_by=''){
			$return = array();
            $con='';
            if($product_category>0)
            {
                $con.=" AND `at`.`pro_category` = ".$product_category."";
            }
            if($company>0)
            {
                $con.=" AND `ts`.`company` = ".$company."";
            }
            if($batch>0)
            {
                $con.=" AND `at`.`id` = ".$batch."";
            }
            if($beginning_date != '' && $ending_date != '')
            {
                $con.=" AND `at`.`batch_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."'";
            }
            if($sort_by == 1)
            {
                $con .= " GROUP BY `ts`.`batch` ORDER BY `at`.`sponsor` ASC";
            }
            else if($sort_by == 2)
            {
                $con .= " GROUP BY `ts`.`batch` ORDER BY `at`.`id` ASC";
            }
            else if($sort_by == 3)
            {
                $con .= " GROUP BY `ts`.`batch` ORDER BY `at`.`batch_date` ASC";
            }
            else
            {
                $con .= " GROUP BY `ts`.`batch` ORDER BY `at`.`pro_category` ASC";
            }
            
			$q = "SELECT `at`.*,`pc`.`type` as pro_category,`ts`.`batch`
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".PRODUCT_TYPE."` AS `pc` on `pc`.`id`=`at`.`pro_category`
                    LEFT JOIN `".TRANSACTION_MASTER."` AS `ts` on `ts`.`batch`=`at`.`id`
                    WHERE `at`.`is_delete`='0' ".$con."
                    ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return[$row['pro_category']][] = $row;
    			}
            }
			return $return;
		}
        public function select_sponsor(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".SPONSOR_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function select_category(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".PRODUCT_TYPE."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
    }
?>