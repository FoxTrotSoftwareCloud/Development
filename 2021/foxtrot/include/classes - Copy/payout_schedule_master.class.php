<?php
	class payout_schedule_master extends db{
		
		public $table = BROKER_PAYOUT_SCHEDULE;
		public $errors = '';
        
        /**
		 * @param post array
		 * @return true if success, error message if any errors
		 * */
		public function insert_update($data){
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
			$schedule_name = isset($data['schedule_name'])?$this->re_db_input($data['schedule_name']):'';
            $transaction_type_general = isset($data['transaction_type_general'])?$this->re_db_input($data['transaction_type_general']):'1';
            $product_category = isset($data['product_category'])?$this->re_db_input($data['product_category']):'';
            $basis = isset($data['basis'])?$this->re_db_input($data['basis']):'';
            $cumulative = isset($data['cumulative'])?$this->re_db_input($data['cumulative']):'0';
            $year = isset($data['year'])?$this->re_db_input($data['year']):'';
            $calculation_detail = isset($data['calculation_detail'])?$this->re_db_input($data['calculation_detail']):'';
            $clearing_charge_deducted_from = isset($data['clearing_charge_deducted_from'])?$this->re_db_input($data['clearing_charge_deducted_from']):'';
            $reset = isset($data['reset'])?$this->re_db_input(date('Y-m-d',strtotime($data['reset']))):'0000-00-00';
            $description_type = isset($data['description_type'])?$this->re_db_input($data['description_type']):'';
            $minimum_trade_gross = isset($data['minimum_trade_gross'])?$this->re_db_input($data['minimum_trade_gross']):'';
            $minimum_12B1_gross = isset($data['minimum_12B1_gross'])?$this->re_db_input($data['minimum_12B1_gross']):'';
            $team_member = isset($data['team_member'])?$data['team_member']:array();
            $team_member_string = implode (",", $team_member);
            $is_default = isset($data['is_default'])?$this->re_db_input($data['is_default']):0;
            
			if($schedule_name==''){
				$this->errors = 'Please enter schedule name.';
			}
			
			if($this->errors!=''){
				return $this->errors;
			}
			else{
				
				/* check duplicate record */
				$con = '';
				if($id>0){
					$con = " AND `id`!='".$id."'";
				}
				$q = "SELECT * FROM `".$this->table."` WHERE `is_delete`='0' AND `payout_schedule_name`='".$schedule_name."' ".$con;
				$res = $this->re_db_query($q);
				$return = $this->re_db_num_rows($res);
				if($return>0){
					$this->errors = 'This schedule is already exists.';
				}
				
				if($this->errors!=''){
					return $this->errors;
				}
				else if($id>=0){
					if($id==0){
					   
                        if($is_default>0)
                        {
                            $q = "UPDATE `".BROKER_PAYOUT_SCHEDULE."`  SET `is_default`='0' WHERE  `is_default`='1'";
          					$res = $this->re_db_query($q);
                        }
                        
                        $q = "INSERT INTO `".BROKER_PAYOUT_SCHEDULE."` SET `payout_schedule_name`='".$schedule_name."' ,`transaction_type_general`='".$transaction_type_general."' ,`product_category`='".$product_category."',`basis`='".$basis."' ,
                        `cumulative`='".$cumulative."' ,`clearing_charge_deducted_from`='".$clearing_charge_deducted_from."',`reset`='".$reset."',`description_type`='".$description_type."',`minimum_trade_gross`='".$minimum_trade_gross."' ,`minimum_12B1_gross`='".$minimum_12B1_gross."'  ,
                        `team_member`='".$team_member_string."' ,`year`='".$year."',`calculation_detail`='".$calculation_detail."',`is_default`='".$is_default."'".$this->insert_common_sql();
    					$res = $this->re_db_query($q);
                        $_SESSION['last_payout_schedule_id'] = $this->re_db_insert_id();
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
                        
                        if($is_default>0)
                        {
                            $q = "UPDATE `".BROKER_PAYOUT_SCHEDULE."`  SET `is_default`='0' WHERE  `is_default`='1'";
          					$res = $this->re_db_query($q);
                        }
                        
                        $q = "UPDATE `".BROKER_PAYOUT_SCHEDULE."`  SET `payout_schedule_name`='".$schedule_name."' ,`transaction_type_general`='".$transaction_type_general."' ,`product_category`='".$product_category."',`basis`='".$basis."' ,
                        `cumulative`='".$cumulative."' ,`clearing_charge_deducted_from`='".$clearing_charge_deducted_from."',`reset`='".$reset."',`description_type`='".$description_type."' ,`minimum_trade_gross`='".$minimum_trade_gross."' ,`minimum_12B1_gross`='".$minimum_12B1_gross."'  ,
                        `team_member`='".$team_member_string."' ,`year`='".$year."',`calculation_detail`='".$calculation_detail."',`is_default`='".$is_default."'".$this->update_common_sql()." WHERE  `id`='".$id."'";
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
				
			}
		}
        public function insert_update_payout_schedule_grid($data ,$id){
           
           $id = isset($id)?$this->re_db_input($id):0;
           $flag=0;
               if($id==0)
               {
                    foreach($data as $key=>$val)
                    {   
                        $sliding_rates =isset($val['sliding_rates'])?$this->re_db_input($val['sliding_rates']):'';
                        $from =isset($val['from'])?$this->re_db_input($val['from']):'';
                        $to =isset($val['to'])?$this->re_db_input($val['to']):'';
                        $per =isset($val['per'])?$this->re_db_input($val['per']):'';
                        if($from!='' && $to != ''){
                            
                            $q = "INSERT INTO `".BROKER_PAYOUT_SCHEDULE_GRID."` SET `payout_schedule_id`='".$_SESSION['last_payout_schedule_id']."' ,`sliding_rates`='".$sliding_rates."' ,`from`='".$from."' ,`to`='".$to."' ,`per`='".$per."' ".$this->insert_common_sql();
            				$res = $this->re_db_query($q); 
                        }
                        else
                        {
                            $res='';
                        }
                    }    
               }
               else
               {
                    foreach($data as $key=>$val)
                    {
                        $sliding_rates =isset($val['sliding_rates'])?$this->re_db_input($val['sliding_rates']):'';
                        $from =isset($val['from'])?$this->re_db_input($val['from']):'';
                        $to =isset($val['to'])?$this->re_db_input($val['to']):'';
                        $per =isset($val['per'])?$this->re_db_input($val['per']):'';
                        if($from!='' && $to != ''){
                            
                            if ($flag==0){
                               $qq="update `".BROKER_PAYOUT_SCHEDULE_GRID."` SET is_delete=1 where `payout_schedule_id`=".$id."";
                               $res = $this->re_db_query($qq);
                               $flag=1;
                            }
                            
                             $q = "INSERT INTO `".BROKER_PAYOUT_SCHEDULE_GRID."` SET `payout_schedule_id`='".$id."',`sliding_rates`='".$sliding_rates."' ,`from`='".$from."' ,`to`='".$to."' , 
                            `per`='".$per."' ".$this->insert_common_sql();
            				 $res = $this->re_db_query($q); 
                        }
                        else
                        {
                            $res='';
                        }
                    }    
               }
        }
        public function reArrayFiles_grid($file_post){
               $file_ary = array();
               foreach($file_post as $key=>$val)
               {
                    $reindexed_filepost[$key] = array_values($val);
               }
               
               $file_count = count($reindexed_filepost['to']);
               $file_keys = array_keys($reindexed_filepost);
               for ($i=0; $i<$file_count; $i++) { 
                   foreach ($file_keys as $key) {      
                        if(isset($reindexed_filepost[$key][$i]))
                        {
                            $file_ary[$i][$key] = $reindexed_filepost[$key][$i];   
                        }
                        else
                        {
                            $file_ary[$i][$key] = '';
                        }
                   }
               }
               //echo '<pre>';print_r($file_ary);exit;
               return $file_ary;
        }
        /**
		 * @param int status, default all
		 * @return array of record if success, error message if any errors
		 * */
		public function select_payout_schedule(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
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
        public function select_broker(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".BROKER_MASTER."` AS `at`
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
        /**
		 * @param int id
		 * @return array of record if success, error message if any errors
		 * */
		public function edit_payout($id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."'";
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function edit_grid($id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".BROKER_PAYOUT_SCHEDULE_GRID."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`payout_schedule_id`='".$id."'";
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        /**
		 * @param id of record
		 * @param status to update
		 * @return true if success, false message if any errors
		 * */
         public function status($id,$status){
			$id = trim($this->re_db_input($id));
			$status = trim($this->re_db_input($status));
			if($id>0 && ($status==0 || $status==1) ){
				$q = "UPDATE `".$this->table."` SET `status`='".$status."' WHERE `id`='".$id."'";
				$res = $this->re_db_query($q);
				if($res){
				    $_SESSION['success'] = STATUS_MESSAGE;
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
		
	}
?>