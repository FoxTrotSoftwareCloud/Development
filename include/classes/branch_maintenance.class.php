<?php
	class branch_maintenance extends db{
		
		public $errors = '';
        public $table = BRANCH_MASTER;
        /**
		 * @param post array
		 * @return true if success, error message if any errors
		 * */
		
        public function insert_update($data){
            $data['date_established'] = date('Y-m-d',strtotime($data['date_established']));
            $data['date_terminated'] = date('Y-m-d',strtotime($data['date_terminated']));
            $data['finra_start_date'] = date('Y-m-d',strtotime($data['finra_start_date']));
            $data['finra_end_date'] = date('Y-m-d',strtotime($data['finra_end_date']));
            $data['last_audit_date'] = date('Y-m-d',strtotime($data['last_audit_date']));
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
            $name = isset($data['name'])?$this->re_db_input($data['name']):'';
            $broker = isset($data['broker'])?$this->re_db_input($data['broker']):'';
            $b_status = isset($data['b_status'])?$this->re_db_input($data['b_status']):'';
            $contact = isset($data['contact'])?$this->re_db_input($data['contact']):'';
            $company = isset($data['company'])?$this->re_db_input($data['company']):'';
            if(!isset($data['osj']))
            {
                $data['osj']=0;
            }
            $osj = isset($data['osj'])?$this->re_db_input($data['osj']):0;
            if(!isset($data['non_registered']))
            {
                $data['non_registered']=0;
            }
            $non_registered = isset($data['non_registered'])?$this->re_db_input($data['non_registered']):0;
            $finra_fee = isset($data['finra_fee'])?$this->re_db_input($data['finra_fee']):'';
            $business_address1 = isset($data['business_address1'])?$this->re_db_input($data['business_address1']):'';
            $business_address2 = isset($data['business_address2'])?$this->re_db_input($data['business_address2']):'';
            $business_city = isset($data['business_city'])?$this->re_db_input($data['business_city']):'';
            $business_state = isset($data['business_state'])?$this->re_db_input($data['business_state']):'';
            $business_zipcode = isset($data['business_zipcode'])?$this->re_db_input($data['business_zipcode']):'';
            $mailing_address1 = isset($data['mailing_address1'])?$this->re_db_input($data['mailing_address1']):'';
            $mailing_address2 = isset($data['mailing_address2'])?$this->re_db_input($data['mailing_address2']):'';
            $mailing_city = isset($data['mailing_city'])?$this->re_db_input($data['mailing_city']):'';
            $mailing_state = isset($data['mailing_state'])?$this->re_db_input($data['mailing_state']):'';
            $mailing_zipcode = isset($data['mailing_zipcode'])?$this->re_db_input($data['mailing_zipcode']):'';
            $email = isset($data['email'])?$this->re_db_input($data['email']):'';
            $website = isset($data['website'])?$this->re_db_input($data['website']):'';
            $phone = isset($data['phone'])?$this->re_db_input($data['phone']):'';
            $facsimile = isset($data['facsimile'])?$this->re_db_input($data['facsimile']):'';
            $date_established = isset($data['date_established'])?$this->re_db_input($data['date_established']):'';
            $date_terminated = isset($data['date_terminated'])?$this->re_db_input($data['date_terminated']):'';
            $finra_start_date = isset($data['finra_start_date'])?$this->re_db_input($data['finra_start_date']):'';
            $finra_end_date = isset($data['finra_end_date'])?$this->re_db_input($data['finra_end_date']):'';
            $last_audit_date = isset($data['last_audit_date'])?$this->re_db_input($data['last_audit_date']):'';
			
            
			if($name==''){
				$this->errors = 'Please enter branch name.';
			}
            else if($email !='' && $this->validemail($email)==0)
            {
				$this->errors = 'Please enter valid email.';
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
			$q = "SELECT * FROM `".$this->table."` WHERE `is_delete`='0' AND `name`='".$name."' ".$con;
			$res = $this->re_db_query($q);
			$return = $this->re_db_num_rows($res);
			if($return>0){
				$this->errors = 'This branch is already exists.';
			}
			
			if($this->errors!=''){
				return $this->errors;
			}
			else if($id>=0){
				if($id==0){
					$q = "INSERT INTO `".$this->table."` SET `name`='".$name."',`broker`='".$broker."',`b_status`='".$b_status."',`contact`='".$contact."',`company`='".$company."',`osj`='".$osj."',`non_registered`='".$non_registered."',`finra_fee`='".$finra_fee."',`business_address1`='".$business_address1."',`business_address2`='".$business_address2."',`business_city`='".$business_city."',`business_state`='".$business_state."',`business_zipcode`='".$business_zipcode."',`mailing_address1`='".$mailing_address1."',`mailing_address2`='".$mailing_address2."',`mailing_city`='".$mailing_city."',`mailing_state`='".$mailing_state."',`mailing_zipcode`='".$mailing_zipcode."',`email`='".$email."',`website`='".$website."',`phone`='".$phone."',`facsimile`='".$facsimile."',`date_established`='".$date_established."',`date_terminated`='".$date_terminated."',`finra_start_date`='".$finra_start_date."',`finra_end_date`='".$finra_end_date."',`last_audit_date`='".$last_audit_date."'".$this->insert_common_sql();
					$res = $this->re_db_query($q);
                    $id = $this->re_db_insert_id();
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
				    /*unset($data['submit']);
				    $current_data = $this->get_dataview($id);
                    if(count($data)>0 && count($current_data)>0)
                    {//echo '<pre>';print_r($data);echo '<pre>';print_r($current_data);
                      $new_list=array_diff($data,$current_data);//echo '<pre>';print_r($new_list);exit;
                      foreach($new_list as $key=>$val)
                      {
                          $old_data = $current_data[$key];
                          $q = "INSERT INTO `".BRANCH_CHANGES."` SET `branch_id`='".$id."',`user_initial`='".$_SESSION['user_name']."',`feild_changed`='".$key."',`previous_value`='".$old_data."',`new_value`='".$val."'".$this->insert_common_sql();
    					  $res = $this->re_db_query($q);
                      }
                    }*/

				    $q = "UPDATE `".$this->table."` SET `name`='".$name."',`broker`='".$broker."',`b_status`='".$b_status."',`contact`='".$contact."',`company`='".$company."',`osj`='".$osj."',`non_registered`='".$non_registered."',`finra_fee`='".$finra_fee."',`business_address1`='".$business_address1."',`business_address2`='".$business_address2."',`business_city`='".$business_city."',`business_state`='".$business_state."',`business_zipcode`='".$business_zipcode."',`mailing_address1`='".$mailing_address1."',`mailing_address2`='".$mailing_address2."',`mailing_city`='".$mailing_city."',`mailing_state`='".$mailing_state."',`mailing_zipcode`='".$mailing_zipcode."',`email`='".$email."',`website`='".$website."',`phone`='".$phone."',`facsimile`='".$facsimile."',`date_established`='".$date_established."',`date_terminated`='".$date_terminated."',`finra_start_date`='".$finra_start_date."',`finra_end_date`='".$finra_end_date."',`last_audit_date`='".$last_audit_date."'".$this->update_common_sql()." WHERE `id`='".$id."'";
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
			else{
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
            }
		}
        public function insert_update_branch_notes($data){//print_r($data);
			$notes_id = isset($data['notes_id'])?$this->re_db_input($data['notes_id']):0;
			$date = isset($data['date'])?$this->re_db_input($data['date']):'';
            $user_id = isset($data['user_id'])?$this->re_db_input($data['user_id']):'';
            $client_note = isset($data['client_note'])?$this->re_db_input($data['client_note']):'';
            
            if($client_note==''){
				$this->errors = 'Please enter notes.';
			}
			if($this->errors!=''){
				return $this->errors;
			}
			else{
                if($notes_id==0){
                    $q = "INSERT INTO `".BRANCH_NOTES."` SET `date`='".$date."',`user_id`='".$user_id."',`notes`='".$client_note."'".$this->insert_common_sql();
			        $res = $this->re_db_query($q);
                    
                    $notes_id = $this->re_db_insert_id();
    				if($res){
    				    $_SESSION['success'] = INSERT_MESSAGE;
    					return true;
    				}
    				else{
    					$_SESSION['warning'] = UNKWON_ERROR;
    					return false;
    				}
    			}
    			else if($notes_id>0){
    			    
                    $q = "UPDATE `".BRANCH_NOTES."` SET `date`='".$date."',`user_id`='".$user_id."',`notes`='".$client_note."'".$this->update_common_sql()." WHERE `id`='".$notes_id."'";
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
        public function insert_update_branch_attach($data){//print_r($data);exit;
            $attach_id = isset($data['attach_id'])?$this->re_db_input($data['attach_id']):0;
            $date = isset($data['date'])?$this->re_db_input($data['date']):'';
            $user_id = isset($data['user_id'])?$this->re_db_input($data['user_id']):'';
            $file = isset($_FILES['file_attach'])?$_FILES['file_attach']:array();
            $valid_file = array('png','jpg','jpeg','bmp','pdf','xls','txt','xlsx');
            $attachment = $file;
            $file = ''; 
            $file_name = isset($attachment['name'])?$attachment['name']:'';
            $tmp_name = isset($attachment['tmp_name'])?$attachment['tmp_name']:'';
            $error = isset($attachment['error'])?$attachment['error']:0;
            $size = isset($attachment['size'])?$attachment['size']:'';
            $type = isset($attachment['type'])?$attachment['type']:'';
            $target_dir = DIR_FS."upload/";
            $ext = strtolower(end(explode('.',$file_name)));
            if($file_name!='')
            {            
               if(!in_array($ext,$valid_file))
               {
                   $this->errors = 'Please select valid file.';
               }
               else
               {
                   $attachment_file = time().rand(100000,999999).'.'.$ext;
                   move_uploaded_file($tmp_name,$target_dir.$attachment_file);
                   $file = $attachment_file;
                   
                   if($attach_id==0){
                        $q = "INSERT INTO `".BRANCH_ATTACH."` SET `date`='".$date."',`user_id`='".$user_id."',`attach`='".$file."' ,`file_name`='".$file_name."'".$this->insert_common_sql();
            	        $res = $this->re_db_query($q);
                        
                        $attach_id = $this->re_db_insert_id();
            			if($res){
            			    $_SESSION['success'] = INSERT_MESSAGE;
            				return true;
            			}
            			else{
            				$_SESSION['warning'] = UNKWON_ERROR;
            				return false;
            			}
            		}
            		else if($attach_id>0){
            		    
                        $q = "UPDATE `".BRANCH_ATTACH."` SET `date`='".$date."',`user_id`='".$user_id."',`attach`='".$file."' ,`file_name`='".$file_name."'".$this->update_common_sql()." WHERE `id`='".$attach_id."'";
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
        /**
		 * @param int status, default all
		 * @return array of record if success, error message if any errors
		 * */
        public function select_state(){
			$return = array();
			
			$q = "SELECT `s`.*
					FROM `".STATE_MASTER."` AS `s`
                    WHERE `s`.`is_delete`='0' AND `s`.`status`='1'
                    ORDER BY `s`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function select(){
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
        public function select_notes(){
			$return = array();
			
			$q = "SELECT `s`.*
					FROM `".BRANCH_NOTES."` AS `s`
                    WHERE `s`.`is_delete`='0'
                    ORDER BY `s`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        public function select_attach(){
			$return = array();
			
			$q = "SELECT `s`.*
					FROM `".BRANCH_ATTACH."` AS `s`
                    WHERE `s`.`is_delete`='0'
                    ORDER BY `s`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        public function search($search_text=''){
			$return = array();
			$con = '';
            if($search_text!='' && $search_text>=0){
				$con .= " AND `clm`.`name` LIKE '%".$search_text."%' ";
			}
            
            $q = "SELECT `clm`.*
					FROM `".$this->table."` AS `clm`
                    WHERE `clm`.`is_delete`='0' ".$con."
                    ORDER BY `clm`.`id` ASC ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     //print_r($row);exit;
                     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function get_previous_branch($id){
			$return = array();
			
            $q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`id`<".$id."
                    ORDER BY `at`.`id` DESC LIMIT 1";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $return = $this->re_db_fetch_array($res);
            }
            else
            {
                return false;
            }
			return $return;
		} 
        public function get_next_branch($id){
			$return = array();
			
            $q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`id`>".$id."
                    ORDER BY `at`.`id` ASC LIMIT 1";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $return = $this->re_db_fetch_array($res);
            }
            else
            {
                return false;
            }
			return $return;
		}
        /**
		 * @param int id
		 * @return array of record if success, error message if any errors
		 * */
        public function edit($id){
			$return = array();
			$q = "SELECT `at`.*,`b`.first_name as broker_name
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BROKER_MASTER."` as `b` on `b`.`id`=`at`.`broker` 
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function get_branch_changes($id){
			$return = array();
			$q = "SELECT `at`.*,u.first_name as user_initial
					FROM `".BRANCH_HISTORY."` AS `at`
                    LEFT JOIN `".USER_MASTER."` as `u` on `u`.`id`=`at`.`modified_by`
                    WHERE `at`.`is_delete`='0' AND `at`.`branch_id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function get_broker_name($id){
			$return = array();
			$q = "SELECT `at`.first_name as broker
					FROM `".BROKER_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function get_dataview($id){
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
		public function delete_notes($id){
			$id = trim($this->re_db_input($id));
			if($id>0){
				$q = "UPDATE `".BRANCH_NOTES."` SET `is_delete`='1' WHERE `id`='".$id."'";
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
        public function delete_attach($id){
			$id = trim($this->re_db_input($id));
			if($id>0){
				$q = "UPDATE `".BRANCH_ATTACH."` SET `is_delete`='1' WHERE `id`='".$id."'";
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
		
		/**
		 * @param id of record
		 * @return true if success, false message if any errors
		 * */
        public function delete($id){
			$id = trim($this->re_db_input($id));
			if($id>0 && ($status==0 || $status==1) ){
				$q = "UPDATE `".$this->table."` SET `is_delete`='1' WHERE `id`='".$id."'";
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
        
    }
?>