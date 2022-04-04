<?php
	class data_interfaces_master extends db{
		
		public $table = DATA_INTERFACES;
		public $errors = '';
        public $last_inserted_id = '';
        /**
		 * @param post array
		 * @return true if success, error message if any errors
		 * 03/26/22 "user_id" deprecated. Should be filtered in Supervisor/System Config settings.
		 * */
		public function insert_update($data){
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
            $dim_id = isset($data['dim_id'])?$this->re_db_input($data['dim_id']):0;
            $uname = isset($data['uname'])?$this->re_db_input($data['uname']):'';
			$password = isset($data['password'])?$this->re_db_input($data['password']):'';
			$trade_activity = isset($data['trade_activity'])?$this->re_db_input($data['trade_activity']):0;
			$add_client = isset($data['add_client'])?$this->re_db_input($data['add_client']):0;
			$update_client = isset($data['update_client'])?$this->re_db_input($data['update_client']):0;
			$local_folder = isset($data['local_folder']) ? $data['local_folder'] : '';
            $is_authorized = isset($data['is_authorized'])?$this->re_db_input($data['is_authorized']):'';
			// $user_id = isset($_SESSION['user_id'])?$this->re_db_input($_SESSION['user_id']):'';
			
			/* check duplicate record */
			if($uname=='' && $dim_id<5){
				$this->errors = 'Please enter username.';
			}
			else if($password=='' && $dim_id<5 && $id == 0){
			     $this->errors = 'Please enter password.';
			}
			if($this->errors!=''){
				return $this->errors;
			}
            else{
                
			$q = "SELECT * FROM `".$this->table."` WHERE `is_delete`='0' AND `dim_id`='".$dim_id."'"; // AND `user_id`='".$user_id."'";
			$res = $this->re_db_query($q);
			$return = $this->re_db_num_rows($res);
			if($return>0){
                    $con = '';
					if($password!=''){
						$con .= " , `password`='".$this->encryptor($password)."' ";
					}
			        // Don't update 'local_folder' <input type="file" returns empty in $_POST. Save name for uploading files - 03/28/22 -> ",`local_folder`='$local_folder'"
                    $q = "UPDATE `".$this->table."` SET `dim_id`='".$dim_id."',`is_authorized_person`='".$is_authorized."',`user_name`='".$uname."',`exclude_non_comm_trade_activity`='".$trade_activity."',`add_client`='".$add_client."',`update_client`='".$update_client."' ".$con." ".$this->update_common_sql()." WHERE `id`='".$id."'";
                            
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
			        $q = "INSERT INTO `".$this->table."` SET `dim_id`='".$dim_id."',`is_authorized_person`='".$is_authorized."',`user_name`='".$uname."',`password`='".md5($password)."',`exclude_non_comm_trade_activity`='".$trade_activity."',`add_client`='".$add_client."',`update_client`='".$update_client."',`local_folder`='".$local_folder."'".$this->insert_common_sql();
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
		 * @param int id
		 * @return array of record if success, error message if any errors
		 * $user_id deprecated 3/21/22 - all users should enjoy!
		 **/
		public function edit($dim_id,$user_id=0){
			$return = array();
			
			$q = "SELECT `at`.*"
					." FROM `".$this->table."` AS `at`"
                    ." WHERE `at`.`is_delete`='0'"
					." AND `at`.`dim_id`='$dim_id'";
			$res = $this->re_db_query($q);
			
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}

		public function select($queryString){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE ".trim($queryString);
			$res = $this->re_db_query($q);

			if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_all($res);
            }
			return $return;
		}
		
	}
?>