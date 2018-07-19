<?php
	class manage_company extends db{
		
		public $table = COMPANY_MASTER;
		public $errors = '';
        public $last_inserted_id = '';
        /**
		 * @param post array
		 * @return true if success, error message if any errors
		 * */
		public function insert_update($data){
		       
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
            $state_values="";
            if(isset($_POST['state'])){
                foreach($_POST['state'] as $ste)
                {
                    if($state_values==''){ 
                        $state_values=$ste; } 
                    else { 
                        $state_values.=",".$ste; } 
               }
            }
			$company_name = isset($data['company_name'])?$this->re_db_input($data['company_name']):'';
            $company_type = isset($data['company_type'])?$this->re_db_input($data['company_type']):'';
            $manager_name = isset($data['manager_name'])?$this->re_db_input($data['manager_name']):'';
	        $address1 = isset($data['address1'])?$this->re_db_input($data['address1']):'';
            $address2 = isset($data['address2'])?$this->re_db_input($data['address2']):'';
            $business_city = isset($data['business_city'])?$this->re_db_input($data['business_city']):'';
            $state_general = isset($data['state_general'])?$this->re_db_input($data['state_general']):'';
            $zip = isset($data['zip'])?$this->re_db_input($data['zip']):'';
            $mail_address1 = isset($data['mail_address1'])?$this->re_db_input($data['mail_address1']):'';
            $mail_address2 = isset($data['mail_address2'])?$this->re_db_input($data['mail_address2']):'';
            $m_city = isset($data['m_city'])?$this->re_db_input($data['m_city']):'';
            $state_mailing = isset($data['state_mailing'])?$this->re_db_input($data['state_mailing']):'';
            $m_zip = isset($data['m_zip'])?$this->re_db_input($data['m_zip']):'';
            $telephone = isset($data['telephone'])?$this->re_db_input($data['telephone']):'';
            $facsimile = isset($data['facsimile'])?$this->re_db_input($data['facsimile']):'';
            $e_date = isset($data['e_date'])?$this->re_db_input(date('Y-m-d',strtotime($data['e_date']))):'';
            $i_date = isset($data['i_date'])?$this->re_db_input(date('Y-m-d',strtotime($data['i_date']))):'';
            $payout_level = isset($data['payout_level'])?$this->re_db_input($data['payout_level']):'';
            $clearing_charge_calculation = isset($data['clearing_charge_calculation'])?$this->re_db_input($data['clearing_charge_calculation']):'';
            $sliding_scale_commision = isset($data['sliding_scale_commision'])?$this->re_db_input($data['sliding_scale_commision']):'';
            $product_category =isset($data['product_category'])?$this->re_db_input($data['product_category']):'';
            $p_rate = isset($data['p_rate'])?$this->re_db_input($data['p_rate']):'';
            $threshold1 = isset($data['threshold1'])?$this->re_db_input($data['threshold1']):'';
            $l1_rate = isset($data['l1_rate'])?$this->re_db_input($data['l1_rate']):'';
            $threshold2 = isset($data['threshold2'])?$this->re_db_input($data['threshold2']):'';
            $l2_rate = isset($data['l2_rate'])?$this->re_db_input($data['l2_rate']):'';
            $threshold3 = isset($data['threshold3'])?$this->re_db_input($data['threshold3']):'';
            $l3_rate = isset($data['l3_rate'])?$this->re_db_input($data['l3_rate']):'';
            $threshold4 = isset($data['threshold4'])?$this->re_db_input($data['threshold4']):'';
            $l4_rate = isset($data['l4_rate'])?$this->re_db_input($data['l4_rate']):'';
            $threshold5 = isset($data['threshold5'])?$this->re_db_input($data['threshold5']):'';
            $l5_rate = isset($data['l5_rate'])?$this->re_db_input($data['l5_rate']):'';
            $threshold6 = isset($data['threshold6'])?$this->re_db_input($data['threshold6']):'';
            $l6_rate = isset($data['l6_rate'])?$this->re_db_input($data['l6_rate']):'';
            $state = isset($data['state'])?$this->re_db_input($state_values):'';
            $foreign = isset($data['foreign'])?$this->re_db_input($data['foreign']):'';            
            //echo $foreign ;exit();
            if($foreign == 'Foreign')
            {
               $foreign=1;
            }
            else{
                $foreign=0;
            }			
			if($company_name==''){
				$this->errors .= 'Please enter company name.<br />';
			}
            if($address1==''){
				$this->errors .= 'Please enter address1.<br />';
			}
            if($business_city==''){
				$this->errors .= 'Please enter business city .<br />';
			}
            if($zip==''){
				$this->errors .= 'Please enter first city zip code.<br />';
			}
            if($state_general==''){
				$this->errors .= 'Please select state.<br />';
			}
            if($e_date==''){
				$this->errors .= 'Please enter establish date.<br />';
			}
  
			if($this->errors!=''){
				return $this->errors;
			}
			else{
				if($this->errors!=''){
					return $this->errors;
				}
				else if($id>=0){
					if($id==0){
						$q = "INSERT INTO `".$this->table."` SET `company_name`='".$company_name."',`company_type`='".$company_type."',`manager_name`='".$manager_name."',`address1`='".$address1."',`address2`='".$address2."',`business_city`='".$business_city."',`state_general`='".$state_general."',
                        `zip`='".$zip."',`mail_address1`='".$mail_address1."',`mail_address2`='".$mail_address2."',`m_city`='".$m_city."', `m_state`='".$state_mailing."',`m_zip`='".$m_zip."',`telephone`='".$telephone."',`facsimile`='".$facsimile."',
                        `e_date`='".$e_date."',`i_date`='".$i_date."',`payout_level`='".$payout_level."',`clearing_charge_calculation`='".$clearing_charge_calculation."',`sliding_scale_commision`='".$sliding_scale_commision."',
                        `product_category`='".$product_category."',`p_rate`='".$p_rate."',`threshold1`='".$threshold1."',`l1_rate`='".$l1_rate."',`threshold2`='".$threshold2."',`l2_rate`='".$l2_rate."',`threshold3`='".$threshold3."',`l3_rate`='".$l3_rate."',
                        `threshold4`='".$threshold4."',`l4_rate`='".$l4_rate."', `threshold5`='".$threshold5."' , `l5_rate`='".$l5_rate."', `threshold6`='".$threshold6."', `l6_rate`='".$l6_rate."',
                         `state`='".$state."', `forign`='".$foreign."' ".$this->insert_common_sql();
                         
                         
                         
						$res = $this->re_db_query($q);
                        $_SESSION['last_insert_id'] = $this->re_db_insert_id($res);
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
					    $q = "UPDATE `".$this->table."` SET `company_name`='".$company_name."',`company_type`='".$company_type."',`manager_name`='".$manager_name."',`address1`='".$address1."',`address2`='".$address2."',`business_city`='".$business_city."',`state_general`='".$state_general."',
                        `zip`='".$zip."',`mail_address1`='".$mail_address1."',`mail_address2`='".$mail_address2."',`m_city`='".$m_city."', `m_state`='".$state_mailing."',`m_zip`='".$m_zip."',`telephone`='".$telephone."',`facsimile`='".$facsimile."',
                        `e_date`='".$e_date."',`i_date`='".$i_date."',`payout_level`='".$payout_level."',`clearing_charge_calculation`='".$clearing_charge_calculation."',`sliding_scale_commision`='".$sliding_scale_commision."',
                        `product_category`='".$product_category."',`p_rate`='".$p_rate."',`threshold1`='".$threshold1."',`l1_rate`='".$l1_rate."',`threshold2`='".$threshold2."',`l2_rate`='".$l2_rate."',`threshold3`='".$threshold3."',`l3_rate`='".$l3_rate."',
                        `threshold4`='".$threshold4."',`l4_rate`='".$l4_rate."', `threshold5`='".$threshold5."' , `l5_rate`='".$l5_rate."', `threshold6`='".$threshold6."', `l6_rate`='".$l6_rate."',
                         `state`='".$state."', `forign`='".$foreign."' ".$this->update_common_sql()." WHERE `id`='".$id."'";
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
        
        /**
		 * @param int status, default all
		 * @return array of record if success, error message if any errors
		 * */
		public function select_company(){
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
        
        public function insert_update_company_notes($data){//print_r($data);
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
                    $q = "INSERT INTO `".COMPANY_NOTES."` SET `date`='".$date."',`user_id`='".$user_id."',`notes`='".$client_note."'".$this->insert_common_sql();
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
    			    
                    $q = "UPDATE `".COMPANY_NOTES."` SET `date`='".$date."',`user_id`='".$user_id."',`notes`='".$client_note."'".$this->update_common_sql()." WHERE `id`='".$notes_id."'";
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
        public function insert_update_company_attach($data){//print_r($data);exit;
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
                        $q = "INSERT INTO `".COMPANY_ATTACH."` SET `date`='".$date."',`user_id`='".$user_id."',`attach`='".$file."' ,`file_name`='".$file_name."'".$this->insert_common_sql();
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
            		    
                        $q = "UPDATE `".COMPANY_ATTACH."` SET `date`='".$date."',`user_id`='".$user_id."',`attach`='".$file."' ,`file_name`='".$file_name."'".$this->update_common_sql()." WHERE `id`='".$attach_id."'";
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
        public function delete_attach($id){
			$id = trim($this->re_db_input($id));
			if($id>0){
				$q = "UPDATE `".COMPANY_ATTACH."` SET `is_delete`='1' WHERE `id`='".$id."'";
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
        public function select_attach(){
			$return = array();
			
			$q = "SELECT `s`.*
					FROM `".COMPANY_ATTACH."` AS `s`
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
        public function get_multicompany_changes($id){
			$return = array();
			$q = "SELECT `at`.*,u.first_name as user_initial
					FROM `".MULTICOMPANY_HISTORY."` AS `at`
                    LEFT JOIN `".USER_MASTER."` as `u` on `u`.`id`=`at`.`modified_by`
                    WHERE `at`.`is_delete`='0' AND `at`.`company_id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function get_state_name($id){
			$return = array();
			$q = "SELECT `at`.name as state_name
					FROM `".STATE_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function get_broker_name($id){
			$return = array();
			$q = "SELECT `at`.first_name as broker_name
					FROM `".BROKER_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function get_product_category_name($id){
			$return = array();
			$q = "SELECT `at`.type as product_type
					FROM `".PRODUCT_TYPE."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function select_notes(){
			$return = array();
			
			$q = "SELECT `s`.*
					FROM `".COMPANY_NOTES."` AS `s`
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
        public function get_previous_company($id){
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
        public function get_next_company($id){
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
        public function delete_notes($id){
			$id = trim($this->re_db_input($id));
			if($id>0){
				$q = "UPDATE `".COMPANY_NOTES."` SET `is_delete`='1' WHERE `id`='".$id."'";
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
        
        
        public function select_search_company($data){
            $return = array();
            $active_search = isset($data['active_search'])?$this->re_db_input($data['active_search']):'';
            $search_text = isset($data['search_text'])?$this->re_db_input($data['search_text']):'';
            $q = "SELECT `st`.*
					FROM `".$this->table."` AS `st`
                    WHERE `".$active_search."` like '".$search_text."%' and `st`.`is_delete`='0'
                    ORDER BY `st`.`id` ASC";
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
		 * @param int status, default all
		 * @return get state for general information
		 * */
        public function select_state(){
			$return = array();
			
			$q = "SELECT `st`.*
					FROM `".STATE_MASTER."` AS `st`
                    WHERE `st`.`is_delete`='0' AND `st`.`status`='1'
                    ORDER BY `st`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        
        public function select_manager(){
            $return = array();
			
			$q = "SELECT `st`.*
					FROM `".BROKER_MASTER."` AS `st`
                    WHERE `st`.`is_delete`='0'
                    ORDER BY `st`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
        }
        
        public function select_product_category(){
			$return = array();
			
			$q = "SELECT `st`.*
					FROM `".PRODUCT_TYPE."` AS `st`
                    WHERE `st`.`is_delete`='0'
                    ORDER BY `st`.`id` ASC";
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
		public function edit($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."' "; 
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        
        public function search($search_text=''){
			$return = array();
			$con = '';
            if($search_text!='' && $search_text>=0){
				$con .= " AND `clm`.`first_name` LIKE '%".$search_text."%' ";
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