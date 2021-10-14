<?php
	class system_master extends db{
		
		public $table = SYSTEM_CONFIGURATION;
		public $errors = '';
        
        /**
		 * @param post array
		 * @return true if success, error message if any errors
		 * */
		public function insert_update($data){
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
            $user_id = isset($_SESSION['user_id'])?$this->re_db_input($_SESSION['user_id']):0;
			$company_name = isset($data['company_name'])?$this->re_db_input($data['company_name']):'';
			$address1 = isset($data['address1'])?$this->re_db_input($data['address1']):'';
            $address2 = isset($data['address2'])?$this->re_db_input($data['address2']):'';
            $city = isset($data['city'])?$this->re_db_input($data['city']):'';
            $state = isset($data['state'])?$this->re_db_input($data['state']):'';
            $zip = isset($data['zip'])?$this->re_db_input($data['zip']):'';
            $minimum_check_amount = isset($data['minimum_check_amount'])?$this->re_db_input($data['minimum_check_amount']):'';
            $finra = isset($data['finra'])?$this->re_db_input($data['finra']):'';
            $sipc = isset($data['sipc'])?$this->re_db_input($data['sipc']):'';
            $brocker_pick_lists = isset($data['brocker_pick_lists'])?$this->re_db_input($data['brocker_pick_lists']):'';
            $branch_pick_lists = isset($data['branch_pick_lists'])?$this->re_db_input($data['branch_pick_lists']):'';
            $brocker_statement = isset($data['brocker_statement'])?$this->re_db_input($data['brocker_statement']):'';
            $logo= isset($_FILES['logo'])?$_FILES['logo']:array();
            $valid_file = array('jpg','jpeg','png','bmp');
            
            $file_image = '';  
            
            $file_name = isset($logo['name'])?$logo['name']:'';
            $tmp_name = isset($logo['tmp_name'])?$logo['tmp_name']:'';
            $error = isset($logo['error'])?$logo['error']:0;
            $size = isset($logo['size'])?$logo['size']:'';
            $type = isset($logo['type'])?$logo['type']:'';
            $target_dir = DIR_FS."upload/logo/";
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
                    $timg = $this->createThumbnails($target_dir,$attachment_file,400,400);
                    $file_image = $attachment_file;
                }
                
            }
            if($this->errors!=''){
				return $this->errors;
			}
            else
            {
                
                $q = "SELECT * FROM `".$this->table."` WHERE `is_delete`='0' AND `id`='1'";
    			$res = $this->re_db_query($q);
    			$return = $this->re_db_num_rows($res);
    			if($return>0){
                        $con = '';
						if($file_image!=''){
							$con .= " , `logo`='".$file_image."' ";
						}
    			        
                        $q = "UPDATE `".$this->table."` SET `company_name`='".$company_name."',`address1`='".$address1."',`address2`='".$address2."',`city`='".$city."',
                                `state`='".$state."',`zip`='".$zip."',`minimum_check_amount`='".$minimum_check_amount."',`finra`='".$finra."',`sipc`='".$sipc."',`brocker_pick_lists`='".$brocker_pick_lists."',
                                `branch_pick_lists`='".$branch_pick_lists."',`brocker_statement`='".$brocker_statement."' ".$con." ".$this->update_common_sql()." where `id`='1'";
                                
    					$res = $this->re_db_query($q);
                        $id = $this->re_db_insert_id();
    					if($res){
    					    $_SESSION['success'] = UPDATE_MESSAGE;
    						return true;
    					}
    					else{
    						$_SESSION['warning'] = UNKWON_ERROR;
    						return false;
    					}
    			}
    			else{
    			        $q = "INSERT INTO `".$this->table."` SET `user_id`='".$user_id."',`company_name`='".$company_name."',`address1`='".$address1."',`address2`='".$address2."',`city`='".$city."',
                                `state`='".$state."',`zip`='".$zip."',`minimum_check_amount`='".$minimum_check_amount."',`finra`='".$finra."',`sipc`='".$sipc."',`brocker_pick_lists`='".$brocker_pick_lists."',
                                `branch_pick_lists`='".$branch_pick_lists."',`brocker_statement`='".$brocker_statement."',`logo`='".$file_image."' ".$this->insert_common_sql();
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
    			
    		}
        }
        /**
		 * @param int status, default all
		 * @return array of record if success, error message if any errors
		 * */
		public function select_account_type(){
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
        /**
		 * @param int id
		 * @return array of record if success, error message if any errors
		 * */
		public function edit(){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `at`.`is_delete`='0' ORDER BY `at`.`id` ASC LIMIT 1";
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
		
		/**
		 * @param id of record
		 * @return true if success, false message if any errors
		 * */
		/*public function delete($id){
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
		}*/
        
    }
?>