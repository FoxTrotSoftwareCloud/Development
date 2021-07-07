<?php
	class manage_sponsor extends db{
		
		public $errors = '';
        
        /**
		 * @param post array
		 * @return true if success, error message if any errors
		 * */
		
        public function insert_update_sponsor($data){
            
			$id = isset($data['sponsor_id'])?$this->re_db_input($data['sponsor_id']):0;
			$sponser_name = isset($data['sponser_name'])?$this->re_db_input($data['sponser_name']):'';
            $saddress1 = isset($data['saddress1'])?$this->re_db_input($data['saddress1']):'';
            $saddress2 = isset($data['saddress2'])?$this->re_db_input($data['saddress2']):'';
            $scity = isset($data['scity'])?$this->re_db_input($data['scity']):'';
            $sstate = isset($data['sstate'])?$this->re_db_input($data['sstate']):'';
            $szip_code = isset($data['szip_code'])?$this->re_db_input($data['szip_code']):'';
            $semail = isset($data['semail'])?$this->re_db_input($data['semail']):'';
            $swebsite = isset($data['swebsite'])?$this->re_db_input($data['swebsite']):'';
            $sgeneral_contact = isset($data['sgeneral_contact'])?$this->re_db_input($data['sgeneral_contact']):'';
            $sgeneral_phone = isset($data['sgeneral_phone'])?$this->re_db_input($data['sgeneral_phone']):'';
            $soperations_contact = isset($data['soperations_contact'])?$this->re_db_input($data['soperations_contact']):'';
            $soperations_phone = isset($data['soperations_phone'])?$this->re_db_input($data['soperations_phone']):'';
            $sdst_system_id = isset($data['sdst_system_id'])?$this->re_db_input($data['sdst_system_id']):'';
            $sdst_mgmt_code = isset($data['sdst_mgmt_code'])?$this->re_db_input($data['sdst_mgmt_code']):'';
            $sdst_import = isset($data['sdst_import'])?$this->re_db_input($data['sdst_import']):'';
            $sdazl_code = isset($data['sdazl_code'])?$this->re_db_input($data['sdazl_code']):'';
            $sdazl_import = isset($data['sdazl_import'])?$this->re_db_input($data['sdazl_import']):'';
            $sdtcc_nscc = isset($data['sdtcc_nscc'])?$this->re_db_input($data['sdtcc_nscc']):'';
            $sclr_firm = isset($data['sclr_firm'])?$this->re_db_input($data['sclr_firm']):'';
            
            //for import module
            $for_import = isset($data['for_import'])?$this->re_db_input($data['for_import']):'false';
            $file_id = isset($data['file_id'])?$this->re_db_input($data['file_id']):'';
            $temp_data_id = isset($data['temp_data_id'])?$this->re_db_input($data['temp_data_id']):'';
            
			if($sponser_name==''){
				$this->errors = 'Please enter sponsor name.';
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
			$q = "SELECT * FROM `".SPONSOR_MASTER."` WHERE `is_delete`='0' AND `name`='".$sponser_name."' ".$con;
			$res = $this->re_db_query($q);
			$return = $this->re_db_num_rows($res);
			if($return>0){
				$this->errors = 'This sponser is already exists.';
			}
			
			if($this->errors!=''){
				return $this->errors;
			}
			else if($id>=0){
				if($id==0){
					$q = "INSERT INTO `".SPONSOR_MASTER."` SET `name`='".strtoupper($sponser_name)."',`address1`='".$saddress1."',`address2`='".$saddress2."',`city`='".$scity."',`state`='".$sstate."',`zip_code`='".$szip_code."',`email`='".$semail."',`website`='".$swebsite."',`general_contact`='".$sgeneral_contact."',`general_phone`='".$sgeneral_phone."',`operations_contact`='".$soperations_contact."',`operations_phone`='".$soperations_phone."',`dst_system_id`='".$sdst_system_id."',`dst_mgmt_code`='".$sdst_mgmt_code."',`dst_importing`='".$sdst_import."',`dazl_code`='".$sdazl_code."',`dazl_importing`='".$sdazl_import."',`dtcc_nscc_id`='".$sdtcc_nscc."',`clearing_firm_id`='".$sclr_firm."'".$this->insert_common_sql();
					$res = $this->re_db_query($q);
                    $id = $this->re_db_insert_id();
                    
                    if($for_import == 'true')
                    {
                        $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$file_id."' and `error_code_id`='14'";
                        $res1 = $this->re_db_query($q1);
                    }
                        
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
					$q = "UPDATE `".SPONSOR_MASTER."` SET `name`='".strtoupper($sponser_name)."',`address1`='".$saddress1."',`address2`='".$saddress2."',`city`='".$scity."',`state`='".$sstate."',`zip_code`='".$szip_code."',`email`='".$semail."',`website`='".$swebsite."',`general_contact`='".$sgeneral_contact."',`general_phone`='".$sgeneral_phone."',`operations_contact`='".$soperations_contact."',`operations_phone`='".$soperations_phone."',`dst_system_id`='".$sdst_system_id."',`dst_mgmt_code`='".$sdst_mgmt_code."',`dst_importing`='".$sdst_import."',`dazl_code`='".$sdazl_code."',`dazl_importing`='".$sdazl_import."',`dtcc_nscc_id`='".$sdtcc_nscc."',`clearing_firm_id`='".$sclr_firm."'".$this->update_common_sql()." WHERE `id`='".$id."'";
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
        public function insert_update_sponsor_notes($data){//print_r($data);
			$notes_id = isset($data['notes_id'])?$this->re_db_input($data['notes_id']):0;
			$date = isset($data['date'])?$this->re_db_input($data['date']):'';
            $user_id = isset($data['user_id'])?$this->re_db_input($data['user_id']):'';
            $sponsor_note = isset($data['sponsor_note'])?$this->re_db_input($data['sponsor_note']):'';
            
            if($sponsor_note==''){
				$this->errors = 'Please enter notes.';
			}
			if($this->errors!=''){
				return $this->errors;
			}
			else{
                if($notes_id==0){
                    $q = "INSERT INTO `".SPONSOR_NOTES."` SET `date`='".$date."',`user_id`='".$user_id."',`notes`='".$sponsor_note."'".$this->insert_common_sql();
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
    			    
                    $q = "UPDATE `".SPONSOR_NOTES."` SET `date`='".$date."',`user_id`='".$user_id."',`notes`='".$sponsor_note."'".$this->update_common_sql()." WHERE `id`='".$notes_id."'";
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
        public function select_notes(){
			$return = array();
			
			$q = "SELECT `s`.*
					FROM `".SPONSOR_NOTES."` AS `s`
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
        public function delete_notes($id){
			$id = trim($this->re_db_input($id));
			if($id>0){
				$q = "UPDATE `".SPONSOR_NOTES."` SET `is_delete`='1' WHERE `id`='".$id."'";
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
        public function insert_update_sponsor_attach($data){
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
                if($file_name == ''){
    				$this->errors = 'Please select file.';
    			}
    			if($this->errors!=''){
    				return $this->errors;
    			}
                else
                {
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
                                $q = "INSERT INTO `".SPONSOR_ATTACH."` SET `date`='".$date."',`user_id`='".$user_id."',`attach`='".$file."' ,`file_name`='".$file_name."'".$this->insert_common_sql();
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
                    		    
                                $q = "UPDATE `".SPONSOR_ATTACH."` SET `date`='".$date."',`user_id`='".$user_id."',`attach`='".$file."' ,`file_name`='".$file_name."'".$this->update_common_sql()." WHERE `id`='".$attach_id."'";
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
		}
        public function select_attach(){
			$return = array();
			
			$q = "SELECT `s`.*
					FROM `".SPONSOR_ATTACH."` AS `s`
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
        public function delete_attach($id){
			$id = trim($this->re_db_input($id));
			if($id>0){
				$q = "UPDATE `".SPONSOR_ATTACH."` SET `is_delete`='1' WHERE `id`='".$id."'";
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
        public function get_previous_sponsor($id){
			$return = array();
			
            $q = "SELECT `at`.*
					FROM `".SPONSOR_MASTER."` AS `at`
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
        public function get_next_sponsor($id){
			$return = array();
			
            $q = "SELECT `at`.*
					FROM `".SPONSOR_MASTER."` AS `at`
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
        public function get_sponsor_changes($id){
			$return = array();
			$q = "SELECT `at`.*,u.first_name as user_initial
					FROM `".SPONSOR_HISTORY."` AS `at`
                    LEFT JOIN `".USER_MASTER."` as `u` on `u`.`id`=`at`.`modified_by`
                    WHERE `at`.`is_delete`='0' AND `at`.`sponsor_id`='".$id."'";
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
        public function get_sponsor_on_system_management_code($system_id='',$management_code=''){
			$return = array();
            
            $con = '';
            if($system_id!='')
            {
                $con = " AND `sp`.`dst_system_id`='".$system_id."'";
            }
            if($management_code!='')
            {
                $con .= " AND `sp`.`dst_mgmt_code`='".$management_code."'";
            }
			
			$q = "SELECT `sp`.*
					FROM `".SPONSOR_MASTER."` AS `sp`
                    WHERE `sp`.`is_delete`='0' ".$con."
                    ORDER BY `sp`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;
                     
    			}
            }
			return $return;
		}
        public function search_sponsor($search_text=''){
			$return = array();
			$con = '';
            if($search_text!='' && $search_text>=0){
				$con .= " AND `clm`.`name` LIKE '%".$search_text."%' ";
			}
            
            $q = "SELECT `clm`.*
					FROM `".SPONSOR_MASTER."` AS `clm`
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
		 * @param int id
		 * @return array of record if success, error message if any errors
		 * */
        public function edit_sponsor($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".SPONSOR_MASTER."` AS `at`
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
		
        public function sponsor_status($id,$status){
			$id = trim($this->re_db_input($id));
			$status = trim($this->re_db_input($status));
			if($id>0 && ($status==0 || $status==1) ){
				$q = "UPDATE `".SPONSOR_MASTER."` SET `status`='".$status."' WHERE `id`='".$id."'";
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
        public function sponsor_delete($id){
			$id = trim($this->re_db_input($id));
			if($id>0 && ($status==0 || $status==1) ){
				$q = "UPDATE `".SPONSOR_MASTER."` SET `is_delete`='1' WHERE `id`='".$id."'";
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