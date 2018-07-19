<?php
	class import extends db{
		
		public $errors = '';
        public $table = IMPORT_CURRENT_FILES;
        /**
		 * @param post array
		 * @return true if success, error message if any errors
		 * */
		
        public function insert_update_ftp($data){
            
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
            $host_name = isset($data['host_name'])?$this->re_db_input($data['host_name']):'';
            $user_name = isset($data['user_name'])?$this->re_db_input($data['user_name']):'';
            $password = isset($data['password'])?trim($this->re_db_input($data['password'])):'';
			$confirm_password = isset($data['confirm_password'])?trim($this->re_db_input($data['confirm_password'])):'';
            $folder_location = isset($data['folder_location'])?$this->re_db_input($data['folder_location']):'';
            $ftp_file_type = isset($data['ftp_file_type'])?$this->re_db_input($data['ftp_file_type']):'';
            $status = isset($data['status'])?$this->re_db_input($data['status']):1;
            
			if($host_name==''){
				$this->errors = 'Please enter host name.';
			}
            else if($user_name==''){
				$this->errors = 'Please enter user name.';
			}
            else if($password=='' && $id==0){
				$this->errors = 'Please enter password.';
			}
            else if($password!='' && $confirm_password==''){
				$this->errors = 'Please confirm password.';
			}
			else if($password!=$confirm_password){
				$this->errors = 'Confirm password must be same as password.';
			}
            else if($status==''){
				$this->errors = 'Please select status.';
			}
            else if($ftp_file_type==''){
				$this->errors = 'Please select file type.';
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
			$q = "SELECT * FROM `".IMPORT_FTP_MASTER."` WHERE `is_delete`='0' AND `user_name`='".$user_name."' ".$con;
			$res = $this->re_db_query($q);
			$return = $this->re_db_num_rows($res);
			if($return>0){
				$this->errors = 'This user is already exists.';
			}
			
			if($this->errors!=''){
				return $this->errors;
			}
			else if($id>=0){
				if($id==0){
				    
					$q = "INSERT INTO `".IMPORT_FTP_MASTER."` SET `host_name`='".$host_name."',`user_name`='".$user_name."',`password`='".$this->encryptor($password)."',`folder_location`='".$folder_location."',`status`='".$status."',`ftp_file_type`='".$ftp_file_type."'".$this->insert_common_sql();
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
				    $con = '';
					if($password!=''){
						$con .= " , `password`='".$this->encryptor($password)."' ";
					}
                        
				    $q = "UPDATE `".IMPORT_FTP_MASTER."` SET `host_name`='".$host_name."',`user_name`='".$user_name."',`folder_location`='".$folder_location."',`status`='".$status."',`ftp_file_type`='".$ftp_file_type."' ".$con." ".$this->update_common_sql()." WHERE `id`='".$id."'";
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
        public function update_exceptions($data){
            $result = 0;
			$exception_file_id = isset($data['exception_file_id'])?$this->re_db_input($data['exception_file_id']):0;
            $exception_file_type = isset($data['exception_file_type'])?$this->re_db_input($data['exception_file_type']):0;
            $error_code_id = isset($data['error_code_id'])?$this->re_db_input($data['error_code_id']):0;
            $exception_data_id = isset($data['exception_data_id'])?$this->re_db_input($data['exception_data_id']):0;
            $exception_field = isset($data['exception_field'])?$this->re_db_input($data['exception_field']):'';
            $broker_termination_date = isset($data['broker_termination_date'])?$this->re_db_input($data['broker_termination_date']):'';
            if($exception_field == 'u5')
            {
                $exception_value = isset($data['exception_value_date'])?$this->re_db_input($data['exception_value_date']):'';
            }
            else if($exception_field == 'social_security_number')
            {
                $exception_value = isset($data['social_security_number'])?$this->re_db_input($data['social_security_number']):'';
            }
            else if($exception_field == 'active')
            {
                $exception_value = isset($data['active'])?$this->re_db_input($data['active']):'';
            }
            else if($exception_field == 'active_check')
            {
                $exception_value = isset($data['active_state'])?$this->re_db_input($data['active_state']):'';
            }
            else if($exception_field == 'status')
            {
                $exception_value = isset($data['status'])?$this->re_db_input($data['status']):'';
            }
            else if($exception_field == 'objectives')
            {
                $exception_value = isset($data['objectives'])?$this->re_db_input($data['objectives']):'';
            }
            else if($exception_field == 'sponsor')
            {
                $exception_value = isset($data['sponsor'])?$this->re_db_input($data['sponsor']):'';
            }
            else if($exception_field == 'CUSIP_number' && $error_code_id == '13')
            {
                $exception_value = isset($data['cusip_number'])?$this->re_db_input($data['cusip_number']):'';
            }
            else if($exception_field == 'CUSIP_number' && $error_code_id == '11')
            {
                $exception_value = isset($data['assign_cusip_number'])?$this->re_db_input($data['assign_cusip_number']):'';
            }
            else if($exception_field == 'alpha_code')
            {
                $exception_value = isset($data['alpha_code'])?$this->re_db_input($data['alpha_code']):'';
            }
            else if($exception_field == 'cusip_number')
            {
                $exception_value = isset($data['existing_cusip_number'])?$this->re_db_input($data['existing_cusip_number']):'';
            }
            else if($exception_field == 'ticker_symbol')
            {
                $exception_value = isset($data['existing_ticker_symbol'])?$this->re_db_input($data['existing_ticker_symbol']):'';
            }
            else 
            {
                $exception_value = isset($data['exception_value'])?$this->re_db_input($data['exception_value']):''; 
            }
            $rep_for_broker = isset($data['rep_for_broker'])?$this->re_db_input($data['rep_for_broker']):'';
            $acc_for_client = isset($data['acc_for_client'])?$this->re_db_input($data['acc_for_client']):'';
            
			if($exception_value=='')
            {
				$this->errors = 'Please enter field value.';
			}
            if($exception_field == 'representative_number' && $exception_value == '')
            {
                $this->errors = 'Please enter rep number.';
            }
            if($exception_field == 'representative_number' && $rep_for_broker == '')
            {
                $this->errors = 'Please select broker.';
            }
            if($exception_field == 'customer_account_number' && $exception_value == '')
            {
                $this->errors = 'Account number blank, Please enter client for account number.';
            }
            if($exception_field == 'customer_account_number' && $acc_for_client == '')
            {
                $this->errors = 'Please select client.';
            }
            if($this->errors!=''){
				return $this->errors;
			}
            else{
                     if(isset($exception_file_type) && $exception_file_type == '1')
                     {
                        if($exception_field == 'u5')
                        {
                            if(!isset($data['resolve_broker_terminated'])||$data['resolve_broker_terminated']=='')
                            {
                                $this->errors = 'Please select one option.';
                            }
                            if($this->errors!=''){
                				return $this->errors;
                			}
                            else
                            {
                                if(isset($data['resolve_broker_terminated']) && $data['resolve_broker_terminated'] == 1)
                                {
                                    //pending add hold commission true for trade
                                    /*$q = "UPDATE `".IMPORT_DETAIL_DATA."` SET `on_hold`='1' WHERE `file_id`='".$exception_file_id."' and `id`='".$exception_data_id."'";
                                    $res = $this->re_db_query($q);*/
                                    
                                    $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                    $res1 = $this->re_db_query($q1);
                                    if($res1)
                                    {
                                        $result = 1;
                                    }
                                }
                                else if(isset($data['resolve_broker_terminated']) && $data['resolve_broker_terminated'] == 2)
                                {
                                    $rep_number = 0;
                                    $broker = 0;
                                    $q = "SELECT rep FROM `".IMPORT_EXCEPTION."` WHERE `is_delete`='0' AND `solved`='0' AND `temp_data_id`='".$exception_data_id."' ";
        			                $res = $this->re_db_query($q);
                                    while($row = $this->re_db_fetch_array($res))
                                    {
                                        $rep_number = $row['rep'];
                                    }
                                    
                                    $q = "SELECT id FROM `".BROKER_MASTER."` WHERE `is_delete`='0' AND `fund`='".$rep_number."' ";
        			                $res = $this->re_db_query($q);
                                    while($row = $this->re_db_fetch_array($res))
                                    {
                                        $broker = $row['id'];
                                    }
                                    
                                    $q = "UPDATE `".BROKER_GENERAL."` SET `".$exception_field."`='' WHERE `broker_id`='".$broker."'";
                                    $res = $this->re_db_query($q);
                                    if($res)
                                    {
                                        $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                        $res1 = $this->re_db_query($q1);
                                        if($res1)
                                        {
                                            $result = 1;
                                        }
                                    }
                                    
                                }
                                else if(isset($data['resolve_broker_terminated']) && $data['resolve_broker_terminated'] == 3)
                                {
                                    if($rep_for_broker == '')
                                    {
                                        $this->errors = 'Please select new broker for client.';
                                    }
                                    if($this->errors!=''){
                        				return $this->errors;
                        			}
                                    else
                                    {
                                        $new_rep = '';
                                        $q = "SELECT fund FROM `".BROKER_MASTER."` WHERE `is_delete`='0' AND `id`='".$rep_for_broker."' ";
            			                $res = $this->re_db_query($q);
                                        while($row = $this->re_db_fetch_array($res))
                                        {
                                            $new_rep = $row['fund'];
                                        }
                                        if($new_rep != '')
                                        {
                                            $q1 = "UPDATE `".IMPORT_DETAIL_DATA."` SET `representative_number`='".$new_rep."' WHERE `id`='".$exception_data_id."' AND `file_id`='".$exception_file_id."'";
                                            $res1 = $this->re_db_query($q1);
                                            if($res1)
                                            {
                                                $q2 = "UPDATE `".IMPORT_EXCEPTION."` SET `rep`='".$new_rep."',`solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                                $res2 = $this->re_db_query($q2);
                                                if($res2)
                                                {
                                                    $result = 1;
                                                }
                                            }
                                        }
                                    }
                                    
                                }
                                else if(isset($data['resolve_broker_terminated']) && $data['resolve_broker_terminated'] == 4)
                                {
                                    $q = "UPDATE `".IMPORT_DETAIL_DATA."` SET `is_delete`='1' WHERE `id`='".$exception_data_id."' AND `file_id`='".$exception_file_id."'";
                                    $res = $this->re_db_query($q);
                                    if($res)
                                    {
                                        $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `is_delete`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."'";
                                        $res1 = $this->re_db_query($q1);
                                        if($res1)
                                        {
                                            $result = 1;
                                        }
                                    }
                                }
                            }
                            
                        }
                        else if($exception_field == 'representative_number')
                        {
                                $header_detail = $this->get_files_header_detail($exception_file_id,$exception_data_id,$exception_file_type);
                                if($header_detail != array()){
                                        $system_id = $header_detail['system_id'];
                                        $management_code = $header_detail['management_code'];
                                        $sponsor_detail = $this->get_sponsor_on_system_management_code($system_id,$management_code);
                                        $alias_sponsor = isset($sponsor_detail['id'])?$sponsor_detail['id']:'';
                                        $alias_number = $exception_value;
                                        
                                        $q = "INSERT INTO `".BROKER_ALIAS."` SET `broker_id`='".$rep_for_broker."' ,`alias_name`='".$alias_number."' ,`sponsor_company`='".$alias_sponsor."' , 
                                `date`='".date('Y-m-d')."' ".$this->insert_common_sql();
                				        $res = $this->re_db_query($q);
                                }
                                $q = "UPDATE `".BROKER_MASTER."` SET `fund`='".$exception_value."' WHERE `id`='".$rep_for_broker."'";
                                $res = $this->re_db_query($q);
                                
                                $q = "UPDATE `".IMPORT_DETAIL_DATA."` SET `".$exception_field."`='".$exception_value."' WHERE `id`='".$exception_data_id."' and `file_id`='".$exception_file_id."'";
                                $res = $this->re_db_query($q);
                            
                                $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and  `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                /*`error_code_id`='1' and `field`='".$exception_field."' and `rep`='".$exception_value."'*/
                                $res1 = $this->re_db_query($q1);
                                if($res1)
                                {
                                    $result = 1;
                                }
                                
                        }
                        else
                        {
                            if($exception_field == 'social_security_number')
                            {
                                $q = "SELECT * FROM `".CLIENT_MASTER."` WHERE `is_delete`='0' AND `client_ssn`='".$exception_value."' ";
                				$res = $this->re_db_query($q);
                				$return = $this->re_db_num_rows($res);
                                if($return>0)
                                {
                                    $this->errors = 'Social security number already exist.';
                                }
                            }
                            if($exception_field == 'registration_line1')
                            {
                                $client_name_array = explode(' ',$exception_value);
                                if(isset($client_name_array[2]) && $client_name_array[2] != '')
                                {
                                    $first_name = isset($client_name_array[0])?$this->re_db_input($client_name_array[0]):'';
                                    $middle_name = isset($client_name_array[1])?$this->re_db_input($client_name_array[1]):'';
                                    $last_name = isset($client_name_array[2])?$this->re_db_input($client_name_array[2]):'';
                                }
                                else
                                {
                                    $first_name = isset($client_name_array[0])?$this->re_db_input($client_name_array[0]):'';
                                    $middle_name = '';
                                    $last_name = isset($client_name_array[1])?$this->re_db_input($client_name_array[1]):'';
                                }
                                
                                $q = "SELECT * FROM `".CLIENT_MASTER."` WHERE `is_delete`='0' AND `first_name`='".$first_name."' AND `mi`='".$middle_name."' AND `last_name`='".$last_name."'";
                				$res = $this->re_db_query($q);
                				$return = $this->re_db_num_rows($res);
                                if($return>0)
                                {
                                    $this->errors = 'Client already exist.';
                                }
                            }
                            if($exception_field == 'mutual_fund_customer_account_number')
                            {
                                $q = "SELECT * FROM `".CLIENT_ACCOUNT."` WHERE `is_delete`='0' AND `account_no`='".$exception_value."' ";
                				$res = $this->re_db_query($q);
                				$return = $this->re_db_num_rows($res);
                                if($return>0)
                                {
                                    $this->errors = 'Account number already exist.';
                                }
                            }
                            if($this->errors!='')
                            {
                				return $this->errors;
                			}
                            else
                            {
                                $q = "UPDATE `".IMPORT_DETAIL_DATA."` SET `".$exception_field."`='".$exception_value."' WHERE `id`='".$exception_data_id."' and `file_id`='".$exception_file_id."'";
                                $res = $this->re_db_query($q);
                                
                                if($res)
                                {
                                    $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                    $res1 = $this->re_db_query($q1);
                                    if($res1)
                                    {
                                        $result = 1;
                                    }
                                }
                            }
                        }
                        
                     }
                     
                     else if(isset($exception_file_type) && $exception_file_type == '2')
                     {
                        if($exception_field == 'u5')
                        {
                            if(!isset($data['resolve_broker_terminated'])||$data['resolve_broker_terminated']=='')
                            {
                                $this->errors = 'Please select one option.';
                            }
                            if($this->errors!=''){
                				return $this->errors;
                			}
                            else
                            {
                                if(isset($data['resolve_broker_terminated']) && $data['resolve_broker_terminated'] == 1)
                                {
                                    //pending add hold commission true for trade
                                    $q = "UPDATE `".IMPORT_IDC_DETAIL_DATA."` SET `on_hold`='1' WHERE `file_id`='".$exception_file_id."' and `id`='".$exception_data_id."'";
                                    $res = $this->re_db_query($q);
                                    
                                    $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                    $res1 = $this->re_db_query($q1);
                                    if($res1)
                                    {
                                        $result = 1;
                                    }
                                }
                                else if(isset($data['resolve_broker_terminated']) && $data['resolve_broker_terminated'] == 2)
                                {
                                    $rep_number = 0;
                                    $broker = 0;
                                    $q = "SELECT rep FROM `".IMPORT_EXCEPTION."` WHERE `is_delete`='0' AND `solved`='0' AND `temp_data_id`='".$exception_data_id."' ";
        			                $res = $this->re_db_query($q);
                                    while($row = $this->re_db_fetch_array($res))
                                    {
                                        $rep_number = $row['rep'];
                                    }
                                    
                                    $q = "SELECT id FROM `".BROKER_MASTER."` WHERE `is_delete`='0' AND `fund`='".$rep_number."' ";
        			                $res = $this->re_db_query($q);
                                    while($row = $this->re_db_fetch_array($res))
                                    {
                                        $broker = $row['id'];
                                    }
                                    
                                    $q = "UPDATE `".BROKER_GENERAL."` SET `".$exception_field."`='' WHERE `broker_id`='".$broker."'";
                                    $res = $this->re_db_query($q);
                                    if($res)
                                    {
                                        $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                        $res1 = $this->re_db_query($q1);
                                        if($res1)
                                        {
                                            $result = 1;
                                        }
                                    }
                                    
                                }
                                else if(isset($data['resolve_broker_terminated']) && $data['resolve_broker_terminated'] == 3)
                                {
                                    if($rep_for_broker == '')
                                    {
                                        $this->errors = 'Please select new broker for client.';
                                    }
                                    if($this->errors!=''){
                        				return $this->errors;
                        			}
                                    else
                                    {
                                        $new_rep = '';
                                        $q = "SELECT fund FROM `".BROKER_MASTER."` WHERE `is_delete`='0' AND `id`='".$rep_for_broker."' ";
            			                $res = $this->re_db_query($q);
                                        while($row = $this->re_db_fetch_array($res))
                                        {
                                            $new_rep = $row['fund'];
                                        }
                                        if($new_rep != '')
                                        {
                                            $q1 = "UPDATE `".IMPORT_IDC_DETAIL_DATA."` SET `representative_number`='".$new_rep."' WHERE `id`='".$exception_data_id."' AND `file_id`='".$exception_file_id."'";
                                            $res1 = $this->re_db_query($q1);
                                            if($res1)
                                            {
                                                $q2 = "UPDATE `".IMPORT_EXCEPTION."` SET `rep`='".$new_rep."',`solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                                $res2 = $this->re_db_query($q2);
                                                if($res2)
                                                {
                                                    $result = 1;
                                                }
                                            }
                                        }
                                    }
                                    
                                }
                                else if(isset($data['resolve_broker_terminated']) && $data['resolve_broker_terminated'] == 4)
                                {
                                    $q = "UPDATE `".IMPORT_IDC_DETAIL_DATA."` SET `is_delete`='1' WHERE `id`='".$exception_data_id."' AND `file_id`='".$exception_file_id."'";
                                    $res = $this->re_db_query($q);
                                    if($res)
                                    {
                                        $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `is_delete`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."'";
                                        $res1 = $this->re_db_query($q1);
                                        if($res1)
                                        {
                                            $result = 1;
                                        }
                                    }
                                }
                            }
                            
                        }
                        else if($exception_field == 'representative_number')
                        {
                                $header_detail = $this->get_files_header_detail($exception_file_id,$exception_data_id,$exception_file_type);
                                if($header_detail != array()){
                                        $system_id = $header_detail['system_id'];
                                        $management_code = $header_detail['management_code'];
                                        $sponsor_detail = $this->get_sponsor_on_system_management_code($system_id,$management_code);
                                        $alias_sponsor = isset($sponsor_detail['id'])?$sponsor_detail['id']:'';
                                        $alias_number = $exception_value;
                                        
                                        $q = "INSERT INTO `".BROKER_ALIAS."` SET `broker_id`='".$rep_for_broker."' ,`alias_name`='".$alias_number."' ,`sponsor_company`='".$alias_sponsor."' , 
                                `date`='".date('Y-m-d')."' ".$this->insert_common_sql();
                				        $res = $this->re_db_query($q);
                                }
                                $q = "UPDATE `".BROKER_MASTER."` SET `fund`='".$exception_value."' WHERE `id`='".$rep_for_broker."'";
                                $res = $this->re_db_query($q);
                                
                                $q = "UPDATE `".IMPORT_IDC_DETAIL_DATA."` SET `".$exception_field."`='".$exception_value."' WHERE `id`='".$exception_data_id."' and `file_id`='".$exception_file_id."'";
                                $res = $this->re_db_query($q);
                            
                                $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                $res1 = $this->re_db_query($q1);
                                if($res1)
                                {
                                    $result = 1;
                                }
                        }
                        else if($exception_field == 'customer_account_number')
                        {
                                $sponsor = '';
                                $header_detail = $this->get_files_header_detail($exception_file_id,$exception_data_id,$exception_file_type);
                                if($header_detail != array()){
                                        $system_id = $header_detail['system_id'];
                                        $management_code = $header_detail['management_code'];
                                        $sponsor_detail = $this->get_sponsor_on_system_management_code($system_id,$management_code);
                                        $sponsor = isset($sponsor_detail['id'])?$sponsor_detail['id']:'';
                                }
                                
                                $q = "INSERT `".CLIENT_ACCOUNT."` SET `account_no`='".$exception_value."',`sponsor_company`='".$sponsor."',`client_id`='".$acc_for_client."' ";
                                $res = $this->re_db_query($q);
                                
                                $q = "UPDATE `".IMPORT_IDC_DETAIL_DATA."` SET `".$exception_field."`='".$exception_value."' WHERE `id`='".$exception_data_id."' and `file_id`='".$exception_file_id."'";
                                $res = $this->re_db_query($q);
                            
                                $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                $res1 = $this->re_db_query($q1);
                                if($res1)
                                {
                                    $result = 1;
                                }
                        }
                        else if($exception_field == 'active_check')
                        {
                            
                            $rep_number = 0;
                            $broker = 0;
                            $customer_account_number = 0;
                            $state_id = 0;
                            $q = "SELECT rep FROM `".IMPORT_EXCEPTION."` WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
			                $res = $this->re_db_query($q);
                            while($row = $this->re_db_fetch_array($res))
                            {
                                $rep_number = $row['rep'];
                            }
                            
                            $q = "SELECT customer_account_number FROM `".IMPORT_IDC_DETAIL_DATA."` WHERE `is_delete`='0' AND `file_id`='".$exception_file_id."' AND `id`='".$exception_data_id."' ";
			                $res = $this->re_db_query($q);
                            while($row = $this->re_db_fetch_array($res))
                            {
                                $customer_account_number = $row['customer_account_number'];
                            }
                            
                            $q = "SELECT id FROM `".BROKER_MASTER."` WHERE `is_delete`='0' AND `fund`='".$rep_number."' ";
			                $res = $this->re_db_query($q);
                            while($row = $this->re_db_fetch_array($res))
                            {
                                $broker = $row['id'];
                            }
                            
                            $q = "SELECT `cm`.`state` FROM `".CLIENT_MASTER."` as `cm`
                            LEFT JOIN `".CLIENT_ACCOUNT."` as `ca` on `ca`.`client_id`=`cm`.`id`
                            WHERE `cm`.`is_delete`='0' AND `ca`.`account_no`='".$customer_account_number."' ";
			                $res = $this->re_db_query($q);
                            while($row = $this->re_db_fetch_array($res))
                            {
                                $state_id = $row['state'];
                            }
                            
                            $q = "UPDATE `".BROKER_LICENCES_SECURITIES."` SET `".$exception_field."`='".$exception_value."' WHERE `broker_id`='".$broker."' AND `state_id`='".$state_id."'";
                            $res = $this->re_db_query($q);
                            
                            $q = "UPDATE `".BROKER_LICENCES_RIA."` SET `".$exception_field."`='".$exception_value."' WHERE `broker_id`='".$broker."' AND `state_id`='".$state_id."'";
                            $res = $this->re_db_query($q);
                            
                            $q = "UPDATE `".BROKER_LICENCES_INSURANCE."` SET `".$exception_field."`='".$exception_value."' WHERE `broker_id`='".$broker."' AND `state_id`='".$state_id."'";
                            $res = $this->re_db_query($q);
                            
                            $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                            $res1 = $this->re_db_query($q1);
                            if($res1)
                            {
                                $result = 1;
                            }
                        }
                        else if($exception_field == 'status')
                        {
                            $CUSIP_number = 0;
                            $q = "SELECT CUSIP_number FROM `".IMPORT_IDC_DETAIL_DATA."` WHERE `is_delete`='0' AND `file_id`='".$exception_file_id."' AND `id`='".$exception_data_id."' ";
			                $res = $this->re_db_query($q);
                            while($row = $this->re_db_fetch_array($res))
                            {
                                $CUSIP_number = $row['CUSIP_number'];
                            }
                            $q = "SELECT * FROM `".PRODUCT_TYPE."` WHERE `is_delete`='0' and `status`='1'";
            				$res = $this->re_db_query($q);
            				if($this->re_db_num_rows($res)>0){
                                while($row = $this->re_db_fetch_array($res))
                                {
                                    $q2 = "UPDATE `product_category_".$row['id']."` SET `".$exception_field."`='".$exception_value."'  WHERE `is_delete`='0' and `status`='0' and `cusip`='".$CUSIP_number."'";
            				        $res2 = $this->re_db_query($q2);
                                    if($res2 == true)
                                    {
                                        $q3 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                        $res3 = $this->re_db_query($q3);
                                        if($res3)
                                        {
                                            $result = 1;
                                        }
                                    }
                        		}
                            }
                        }
                        else if($exception_field == 'objectives')
                        {
                            $CUSIP_number = 0;
                            $account_number = 0;
                            $client_id = 0;
                            $objective = 0;
                            
                            $q = "SELECT CUSIP_number,customer_account_number FROM `".IMPORT_IDC_DETAIL_DATA."` WHERE `is_delete`='0' AND `file_id`='".$exception_file_id."' AND `id`='".$exception_data_id."' ";
			                $res = $this->re_db_query($q);
                            while($row = $this->re_db_fetch_array($res))
                            {
                                $CUSIP_number = $row['CUSIP_number'];
                                $account_number = $row['customer_account_number'];
                            }
                            
                            $q1 = "SELECT * FROM `".PRODUCT_TYPE."` WHERE `is_delete`='0' and `status`='1'";
            				$res1 = $this->re_db_query($q1);
            				if($this->re_db_num_rows($res1)>0){
                                while($row1 = $this->re_db_fetch_array($res1))
                                {
                                    $q2 = "SELECT objective FROM `product_category_".$row1['id']."` WHERE `is_delete`='0' and `cusip`='".$CUSIP_number."'";
            				        $res2 = $this->re_db_query($q2);
                                    if($this->re_db_num_rows($res2)>0){
                                        while($row2 = $this->re_db_fetch_array($res2))
                                        {
                                            $objective = $row2['objective'];
                                        }
                                        
                                        $q3 = "SELECT client_id FROM `".CLIENT_ACCOUNT."` WHERE `is_delete`='0' AND `account_no`='".$account_number."' ";
            			                $res3 = $this->re_db_query($q3);
                                        if($this->re_db_num_rows($res3)>0)
                                        {
                                            while($row3 = $this->re_db_fetch_array($res3))
                                            {
                                                $client_id = $row3['client_id'];
                                            }
                                            if($objective != $exception_value)
                                            {
                                                $this->errors = 'Objective does not match, Please select another objective.';
                                            }
                                            if($this->errors!=''){
                                				return $this->errors;
                                			}
                                            else
                                            {
                                                if($client_id>0 && $objective>0)
                                                {
                                                    $q6 = "SELECT * FROM `".CLIENT_OBJECTIVES."` WHERE `is_delete`='0' AND `client_id`='".$client_id."' AND `objectives`='".$exception_value."'";
                                        			$res6 = $this->re_db_query($q6);
                                        			$return = $this->re_db_num_rows($res6);
                                        			if($return>0){
                                        				$q7 = "UPDATE `".CLIENT_OBJECTIVES."` SET `is_delete`='1' WHERE `client_id`='".$client_id."' AND `objectives`='".$exception_value."'";
                                                        $res7 = $this->re_db_query($q7);
                                        			}
                                                    
                                                    $q4 = "INSERT INTO `".CLIENT_OBJECTIVES."` SET `client_id`='".$client_id."',`objectives`='".$exception_value."' ".$this->insert_common_sql();
                                                    $res4 = $this->re_db_query($q4);
                                                    if($res4 == true)
                                                    {
                                                        $q5 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                                        $res5 = $this->re_db_query($q5);
                                                        if($res5)
                                                        {
                                                            $result = 1;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else if($exception_field == 'sponsor')
                        {
                            $system_id = '';
                            $management_code = '';
                            $sponsor = isset($data['sponsor'])?$data['sponsor']:'';
                            
                            if(isset($sponsor) && $sponsor == '')
                            {
                               $this->errors = 'Please select sponsor.';
                            }
                            if($this->errors!=''){
           				       return $this->errors;
            			    }
                            else
                            {
                                $q = "SELECT system_id,management_code FROM `".IMPORT_IDC_HEADER_DATA."` WHERE `is_delete`='0' AND `file_id`='".$exception_file_id."'";
    			                $res = $this->re_db_query($q);
                                while($row = $this->re_db_fetch_array($res))
                                {
                                    $system_id = $row['system_id'];
                                    $management_code = $row['management_code'];
                                }
                                
                                $q2 = "UPDATE `".SPONSOR_MASTER."` SET `dst_system_id`='".$system_id."',`dst_mgmt_code`='".$management_code."'  WHERE `is_delete`='0' and `id`='".$sponsor."'";
        				        $res2 = $this->re_db_query($q2);
                                if($res2 == true)
                                {
                                    $q3 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `field`='".$exception_field."'";
                                    $res3 = $this->re_db_query($q3);
                                    if($res3)
                                    {
                                        $result = 1;
                                    }
                                }
                            }
                        }
                        else if($exception_field == 'CUSIP_number' && $error_code_id == '13')
                        {
                             $q = "UPDATE `".IMPORT_IDC_DETAIL_DATA."` SET `".$exception_field."`='".$exception_value."' WHERE `id`='".$exception_data_id."' and `file_id`='".$exception_file_id."'";
                             $res = $this->re_db_query($q);
                            
                             $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                             $res1 = $this->re_db_query($q1);
                             if($res1)
                             {
                                 $result = 1;
                             }
                        }
                        else if($exception_field == 'CUSIP_number' && $error_code_id == '11')
                        {
                             $product_category = isset($data['assign_cusip_product_category'])?$data['assign_cusip_product_category']:0;
                             $product = isset($data['assign_cusip_product'])?$data['assign_cusip_product']:0;
                             if($product_category <=0)
                             {
                                 $this->errors = 'Please select product category.';
                             }
                             else if($product<=0)
                             {
                                $this->errors = 'Please select product.';
                             }
                             if($this->errors!=''){
                				 return $this->errors;
                			 }
                             else
                             {
                                 $q = "UPDATE `product_category_".$product_category."` SET `cusip`='".$exception_value."' WHERE `id`='".$product."'";
                                 $res = $this->re_db_query($q);
                                 
                                 $q1 = "UPDATE `".IMPORT_IDC_DETAIL_DATA."` SET `".$exception_field."`='".$exception_value."' WHERE `id`='".$exception_data_id."' and `file_id`='".$exception_file_id."'";
                                 $res1 = $this->re_db_query($q1);
                                
                                 $q2 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                 $res2 = $this->re_db_query($q2);
                                 if($res2)
                                 {
                                     $result = 1;
                                 }
                             }
                        }
                        else if($exception_field == 'alpha_code')
                        {
                             $q = "UPDATE `".IMPORT_IDC_DETAIL_DATA."` SET `".$exception_field."`='".$exception_value."' WHERE `id`='".$exception_data_id."' and `file_id`='".$exception_file_id."'";
                             $res = $this->re_db_query($q);
                            
                             $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                             $res1 = $this->re_db_query($q1);
                             if($res1)
                             {
                                 $result = 1;
                             }
                        }
                        else if($exception_field == 'active')
                        {
                            $ssn_number = 0;
                            $q = "SELECT social_security_number FROM `".IMPORT_IDC_DETAIL_DATA."` WHERE `is_delete`='0' AND `file_id`='".$exception_file_id."' AND `id`='".$exception_data_id."' ";
			                $res = $this->re_db_query($q);
                            while($row = $this->re_db_fetch_array($res))
                            {
                                $ssn_number = $row['social_security_number'];
                            }
                            
                            $q2 = "UPDATE `".CLIENT_MASTER."` SET `".$exception_field."`='".$exception_value."'  WHERE `is_delete`='0' and `client_ssn`='".$ssn_number."'";
    				        $res2 = $this->re_db_query($q2);
                            if($res2 == true)
                            {
                                $q3 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                $res3 = $this->re_db_query($q3);
                                if($res3)
                                {
                                    $result = 1;
                                }
                            }
                        }
                        
                        else
                        {
                            /*if($exception_field == 'customer_account_number')
                            {
                                $q = "SELECT * FROM `".CLIENT_ACCOUNT."` WHERE `is_delete`='0' AND `account_no`='".$exception_value."' ";
                				$res = $this->re_db_query($q);
                				$return = $this->re_db_num_rows($res);
                                if($return<=0)
                                {
                                    $this->errors = 'Account number does not match.';
                                }
                            }
                            if($this->errors!='')
                            {
                				return $this->errors;
                			}
                            else
                            {*/
                                $q = "UPDATE `".IMPORT_IDC_DETAIL_DATA."` SET `".$exception_field."`='".$exception_value."' WHERE `id`='".$exception_data_id."' and `file_id`='".$exception_file_id."'";
                                $res = $this->re_db_query($q);
                                
                                $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                $res1 = $this->re_db_query($q1);
                                if($res1)
                                {
                                    $result = 1;
                                }
                            /*}*/
                        }
                     }
                     else if(isset($exception_file_type) && $exception_file_type == '3')
                     {
                        if($exception_field == 'cusip_number')
                        {
                            $q = "SELECT * FROM `".PRODUCT_TYPE."` WHERE `is_delete`='0' and `status`='1'";
            				$res = $this->re_db_query($q);
            				if($this->re_db_num_rows($res)>0){
                                while($row = $this->re_db_fetch_array($res))
                                {
                                    $q2 = "SELECT * FROM `product_category_".$row['id']."` WHERE `is_delete`='0' and `cusip`='".$exception_value."'";
            				        $res2 = $this->re_db_query($q2);
                                    if($this->re_db_num_rows($res2)>0)
                                    {
                                       $this->errors = 'Cusip already exist.';
                                    }
                                    if($this->errors!=''){
                        				return $this->errors;
                        			}
                                    else
                                    {
                                        $q3 = "UPDATE `".IMPORT_SFR_DETAIL_DATA."` SET `".$exception_field."`='".$exception_value."' WHERE `id`='".$exception_data_id."' and `file_id`='".$exception_file_id."'";
                                        $res3 = $this->re_db_query($q3);
                            
                                        $q4 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                        $res4 = $this->re_db_query($q4);
                                        if($res4)
                                        {
                                            $result = 1;
                                        }
                                    }
                        		}
                            }
                        }
                        if($exception_field == 'ticker_symbol')
                        {
                            $q = "SELECT * FROM `".PRODUCT_TYPE."` WHERE `is_delete`='0' and `status`='1'";
            				$res = $this->re_db_query($q);
            				if($this->re_db_num_rows($res)>0){
                                while($row = $this->re_db_fetch_array($res))
                                {
                                    $q2 = "SELECT * FROM `product_category_".$row['id']."` WHERE `is_delete`='0' and `ticker_symbol`='".$exception_value."'";
            				        $res2 = $this->re_db_query($q2);
                                    if($this->re_db_num_rows($res2)>0)
                                    {
                                       $this->errors = 'Ticker symbol already exist.';
                                    }
                                    if($this->errors!=''){
                        				return $this->errors;
                        			}
                                    else
                                    {
                                        $q3 = "UPDATE `".IMPORT_SFR_DETAIL_DATA."` SET `".$exception_field."`='".$exception_value."' WHERE `id`='".$exception_data_id."' and `file_id`='".$exception_file_id."'";
                                        $res3 = $this->re_db_query($q3);
                            
                                        $q4 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$exception_file_id."' and `temp_data_id`='".$exception_data_id."' and `field`='".$exception_field."'";
                                        $res4 = $this->re_db_query($q4);
                                        if($res4)
                                        {
                                            $result = 1;
                                        }
                                        break;
                                    }
                        		}
                            }
                        }
                     }
                     
                     if($result == 1){
                        if($exception_field == 'customer_account_number')
                        {
                            $_SESSION['success'] = $exception_value.' Added.';
    					    return true;
                        }
                        else
                        {
                            $_SESSION['success'] = 'Exception solved successfully.';
    					    return true;
                        }
    				    
     				 }
    				 else{
    					$_SESSION['warning'] = UNKWON_ERROR;
    					return false;
    				 }
    		}
		}
        
        public function select_ftp(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_FTP_MASTER."` AS `at`
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
        
        public function select_ftp_user($id=''){
			$return = array();
			
            if($id !='')
            {
    			$q = "SELECT `at`.*
    					FROM `".IMPORT_FTP_MASTER."` AS `at`
                        WHERE `at`.`is_delete`='0' and `at`.`id`='".$id."'
                        ORDER BY `at`.`id` ASC";
    			$res = $this->re_db_query($q);
                if($this->re_db_num_rows($res)>0){
                  
                    $return = $this->re_db_fetch_array($res);
                }
            }
			return $return;
		}
        
        public function edit_ftp($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".IMPORT_FTP_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        
        public function ftp_status($id,$status){
			$id = trim($this->re_db_input($id));
			$status = trim($this->re_db_input($status));
			if($id>0 && ($status==0 || $status==1) ){
				$q = "UPDATE `".IMPORT_FTP_MASTER."` SET `status`='".$status."' WHERE `id`='".$id."'";
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
		
        public function ftp_delete($id){
			$id = trim($this->re_db_input($id));
			if($id>0 && ($status==0 || $status==1) ){
				$q = "UPDATE `".IMPORT_FTP_MASTER."` SET `is_delete`='1' WHERE `id`='".$id."'";
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
        public function insert_update_files($data){
            $all_files = $id = isset($_FILES['files'])?$_FILES['files']:array();
            
            if(isset($all_files['name'][0]) && $all_files['name'][0] == ''){
				$this->errors = 'Please select files.';
			}
            if(isset($all_files['name']) && count($all_files['name'])>15)
            {
                $this->errors = 'File selection limit maximum 15.';
            }
            if($this->errors!=''){
				return $this->errors;
			}
            else{
            
                $files_array = $this->reArrayFiles($all_files);
                
                foreach($files_array as $file_key=>$file_val)
                {
                    $valid_file = array('zip');
                    $dir = $file_val['tmp_name'];
                    $file_name = $file_val['name'];
                    $path= $dir;
                    
                    $file_import = '';  
                    $ext_filename = '';
                    
                    $ext = strtolower(end(explode('.',$file_name)));
                    
                    if($file_name!='')
                    {
                        if(!in_array($ext,$valid_file))
                        {
                            $this->errors = 'Please select valid file.';
                        }
                        else
                        {
                              $zip = new ZipArchive;
                              $res = $zip->open($path);
                              
                              if ($res === TRUE) {
                                for ($i = 0; $i < $zip->numFiles; $i++) {
                                     
                                     $ext_filename = $zip->getNameIndex($i);
                                     
                                 }
                                 if (!file_exists(DIR_FS."import_files/user_".$_SESSION['user_id'])) {
                                    mkdir(DIR_FS."import_files/user_".$_SESSION['user_id'], 0777, true);
                                 }
                                 //print_r(DIR_FS."import_files/user_".$_SESSION['user_id']);exit;
                                 $zip->extractTo(DIR_FS."import_files/user_".$_SESSION['user_id']);
                                 $zip->close();
                              } 
                        }
                    }
                    if($this->errors!=''){
        				return $this->errors;
        			}
                    else
                    {
                        $source = '';
                        $already_file_array = $this->check_current_files($_SESSION['user_id']);
                        if(!in_array($ext_filename,$already_file_array))
                        {
                            $file_type_array = array('07'=>'Non-Financial Activity','08'=>'New Account Activity','09'=>'Account Master Position','C1'=>'DST Commission');
                            $file_name_array = explode('.',$ext_filename);
                            $file_type_checkkey = substr($file_name_array[0], -2);//print_r($ext_filename);exit;
                            if (array_key_exists($file_type_checkkey, $file_type_array)) 
                            {
                                if(isset($file_type_checkkey) && ($file_type_checkkey == '07' || $file_type_checkkey == '08' || $file_type_checkkey == '09')){
                                    $source = 'DSTFANMail';
                                }
                                else if(isset($file_type_checkkey) && $file_type_checkkey == 'C1'){
                                    $source = 'DSTIDC';
                                }
                                
                                $q = "INSERT INTO `".IMPORT_CURRENT_FILES."` SET `user_id`='".$_SESSION['user_id']."',`imported_date`='".date('Y-m-d')."',`last_processed_date`='',`file_name`='".$ext_filename."',`file_type`='".$file_type_array[$file_type_checkkey]."',`source`='".$source."'".$this->insert_common_sql();
                    			$res = $this->re_db_query($q);
                                $id = $this->re_db_insert_id();
                            }
                            else
                            {
                                $q = "INSERT INTO `".IMPORT_CURRENT_FILES."` SET `user_id`='".$_SESSION['user_id']."',`imported_date`='".date('Y-m-d')."',`last_processed_date`='',`file_name`='".$ext_filename."',`file_type`='-',`source`=''".$this->insert_common_sql();
                    			$res = $this->re_db_query($q);
                                $id = $this->re_db_insert_id();
                            }
                        }
            			
                    }
                    
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
		}
        public function reArrayFiles($file_post) {
           $file_ary = array();
           $file_count = count($file_post['name']);
           $file_keys = array_keys($file_post);
           for ($i=0; $i<$file_count; $i++) {
               foreach ($file_keys as $key) {
                   $file_ary[$i][$key] = $file_post[$key][$i];
               }
           }
           return $file_ary;
       }
       public function process_current_files($id){
            $data_status = false;
            if($id > 0)
            {
                $q = "SELECT * FROM `".IMPORT_CURRENT_FILES."` WHERE `is_delete`='0' AND `processed`='1' AND `id`='".$id."'; ";
				$res = $this->re_db_query($q);
				$return = $this->re_db_num_rows($res);
                $this->errors='';
				if($return == 0){
					/*$this->errors = 'This file is already processed.';
				}                
				if($this->errors!=''){
					return $this->errors;
				}
                else
                {*/
                    $file_string_array = array();
                    $get_file = $this->select_user_files($id);
                    $file_name = $get_file['file_name'];
                    $file_path = DIR_FS."import_files/user_".$_SESSION['user_id']."/".$file_name;
                    $file = fopen($file_path, "r");
                    while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
                     {
                        array_push($file_string_array,$getData[0]);
                     }
                     $data_array = array();
                     $array_key = 0;
                     $array_detail_key = 0;
                     $array_detail_check_key = 0;
                     $array_detail_key_sfr = 0;
                     $array_detail_check_key_sfr = 0;
                     $file_name_array = explode('.',$file_name);
                     $file_type_key = substr($file_name_array[0], -2);
                     $file_type_check = $file_type_key;
                     
                     if(isset($file_type_check) && ($file_type_check == '07' || $file_type_check == '08' || $file_type_check == '09'))
                     {
                         foreach($file_string_array as $key_string=>$val_string)
                         {
                            $record_type = substr($val_string, 0, 3);
                            if(isset($record_type) && $record_type == 'RHR')
                            {
                                $file_type = trim(substr($val_string, 6, 15));
                                if($file_type == 'SECURITY FILE')
                                {
                                    $data_array[$array_key][$record_type] = array("record_type" => substr($val_string, 0, 3),"sequence_number" => substr($val_string, 3, 3),"file_type" => substr($val_string, 6, 15),"super_sheet_date" => substr($val_string, 21, 8),"processed_date" => substr($val_string, 29, 8),"processed_time" => substr($val_string, 37, 8),"job_name" => substr($val_string, 45, 8),"file_format_code" => substr($val_string, 53, 3),"request_number" => substr($val_string, 56, 7),"*" => substr($val_string, 63, 1),"system_id" => substr($val_string, 64, 3),"management_code" => substr($val_string, 67, 2),"**" => substr($val_string, 69, 1),"unused_mutual_fund" => substr($val_string, 70, 1),"life_date_type" => substr($val_string, 71, 1),"unused_header_RHR" => substr($val_string, 72, 88));
                                }
                                else
                                {
                                    $data_array[$array_key][$record_type] = array("record_type" => substr($val_string, 0, 3),"sequence_number" => substr($val_string, 3, 3),"file_type" => substr($val_string, 6, 15),"super_sheet_date" => substr($val_string, 21, 8),"processed_date" => substr($val_string, 29, 8),"processed_time" => substr($val_string, 37, 8),"job_name" => substr($val_string, 45, 8),"file_format_code" => substr($val_string, 53, 3),"request_number" => substr($val_string, 56, 7),"*" => substr($val_string, 63, 1),"system_id" => substr($val_string, 64, 3),"management_code" => substr($val_string, 67, 2),"**" => substr($val_string, 69, 1),"populated_by_dst" => substr($val_string, 70, 1),"variable_universal_life" => substr($val_string, 71, 1),"unused_header_RHR" => substr($val_string, 72, 88));
                                }
                            
                            }
                            else if(isset($record_type) && $record_type == 'PLH')
                            {
                                $header_record_sequence = substr($val_string, 3, 3);
                                if(isset($header_record_sequence) && $header_record_sequence == 001)
                                {
                                    $data_array[$array_key][$record_type][$header_record_sequence] = array("record_type1" => substr($val_string, 0, 3),"sequence_number1" => substr($val_string, 3, 3),"anniversary_date" => substr($val_string, 6, 8),"issue_date" => substr($val_string, 14, 8),"product_code" => substr($val_string, 22, 7),"policy_contract_number" => substr($val_string, 29, 20),"death_benefit_option" => substr($val_string, 49, 2),"current_policy_face_amount" => substr($val_string, 51, 12),"current_sum_of_riders" => substr($val_string, 63, 12),"current_face_amount_including_sum_of_riders" => substr($val_string, 75, 12),"name_of_primary_beneficiary" => substr($val_string, 87, 31),"multiple_primary_beneficiary(M)" => substr($val_string, 118, 1),"name_of_secondary_beneficiary" => substr($val_string, 119, 30),"multiple_secondary_beneficiary(M)" => substr($val_string, 149, 1),"policy_status" => substr($val_string, 150, 2),"unused_PLH_001" => substr($val_string, 152, 8));
                                }
                                else if(isset($header_record_sequence) && $header_record_sequence == 002)
                                {
                                    $data_array[$array_key][$record_type][$header_record_sequence] = array("record_type2" => substr($val_string, 0, 3),"sequence_number2" => substr($val_string, 3, 3),"billing_type" => substr($val_string, 6, 1),"billing_frequency" => substr($val_string, 7, 1),"billing_amount" => substr($val_string, 8, 15),"guideline_annual_premium" => substr($val_string, 23, 15),"guideline_single_premium" => substr($val_string, 38, 15),"target_premium" => substr($val_string, 53, 15),"no_lapse_guarantee_premium" => substr($val_string, 68, 15),"seven_pay_premium" => substr($val_string, 83, 15),"MEC_indicator" => substr($val_string, 98, 1),"unused_PLH_002" => substr($val_string, 99, 61));
                                    /*array_push($header_array['PLH'][$array_plh_key],$header_array_002);
                                    $array_plh_key++;*/
                                }
                            }
                            else if(isset($record_type) && ($record_type == 'NAA' || $record_type == 'NFA' || $record_type == 'AMP' ))
                            {
                                $detail_record_type = substr($val_string, 3, 3);
                                if($detail_record_type == 001)
                                {
                                    if($array_detail_check_key>0)
                                    {
                                        $array_detail_key++;
                                    }
                                    $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("record_type1" => substr($val_string, 0, 3),"sequence_number1" => substr($val_string, 3, 3),"dealer_number" => substr($val_string, 6, 7),"dealer_branch_number" => substr($val_string, 13, 9),"cusip_number" => substr($val_string, 22, 9),"mutual_fund_fund_code" => substr($val_string, 31, 7),"mutual_fund_customer_account_number" => substr($val_string, 38, 20),"account_number_code" => substr($val_string, 58, 1),"mutual_fund_established_date" => substr($val_string, 59, 8),"last_maintenance_date" => substr($val_string, 67, 8),"line_code" => substr($val_string, 75, 1),"alpha_code" => substr($val_string, 76, 10),"mutual_fund_dealer_level_control_code" => substr($val_string, 86, 1),"social_code" => substr($val_string, 87, 3),"resident_state_country_code" => substr($val_string, 90, 3),"social_security_number" => substr($val_string, 93, 9),"ssn_status_code" => substr($val_string, 102, 1),"systematic_withdrawal_plan(SWP)_account" => substr($val_string, 103, 1),"pre_authorized_checking_amount" => substr($val_string, 104, 1),"automated_clearing_house_account(ACH)" => substr($val_string, 105, 1),"mutual_fund_reinvest_to_another_account" => substr($val_string, 106, 1),"mutual_fund_capital_gains_distribution_option" => substr($val_string, 107, 1),"mutual_fund_divident_distribution_option" => substr($val_string, 108, 1),"check_writing_account" => substr($val_string, 109, 1),"expedited_redemption_account" => substr($val_string, 110, 1),"mutual_fund_sub_account" => substr($val_string, 111, 1),"foreign_tax_rate" => substr($val_string, 112, 3),"zip_code" => substr($val_string, 115, 9),"zipcode_future_expansion" => substr($val_string, 124, 2),"cumulative_discount_number" => substr($val_string, 126, 9),"letter_of_intent(LOI)_number" => substr($val_string, 135, 9),"timer_flag" => substr($val_string, 144, 1),"list_bill_account" => substr($val_string, 145, 1),"mutual_fund_monitored_VIP_account" => substr($val_string, 146, 1),"mutual_fund_expedited_exchange_account" => substr($val_string, 147, 1),"mutual_fund_penalty_withholding_account" => substr($val_string, 148, 1),"certificate_issuance_code" => substr($val_string, 149, 1),"mutual_fund_stop_transfer_flag" => substr($val_string, 150, 1),"mutual_fund_blue_sky_exemption_flag" => substr($val_string, 151, 1),"bank_card_issued" => substr($val_string, 152, 1),"fiduciary_account" => substr($val_string, 153, 1),"plan_status_code" => substr($val_string, 154, 1),"mutual_fund_net_asset_value(NAV)_account" => substr($val_string, 155, 1),"mailing_flag" => substr($val_string, 156, 1),"interested_party_code" => substr($val_string, 157, 1),"mutual_fund_share_account_phone_check_redemption_code" => substr($val_string, 158, 1),"mutual_fund_share_account_house_account_code" => substr($val_string, 159, 1));
                                    $array_detail_check_key++;
                                }
                                else if($detail_record_type == 002)
                                {
                                    $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("record_type2" => substr($val_string, 0, 3),"sequence_number2" => substr($val_string, 3, 3),"mutual_fund_dividend_mail_account" => substr($val_string, 6, 1),"mutual_fund_stop_purchase_account" => substr($val_string, 7, 1),"stop_mail_account" => substr($val_string, 8, 1),"mutual_fund_fractional_check" => substr($val_string, 9, 1),"registration_line1" => substr($val_string, 10, 35),"registration_line2" => substr($val_string, 45, 35),"registration_line3" => substr($val_string, 80, 35),"registration_line4" => substr($val_string, 115, 35),"customer_date_of_birth" => substr($val_string, 150, 8),"mutual_fund_account_price_schedule_code" => substr($val_string, 158, 1),"unused_detail" => substr($val_string, 159, 1));
                                }
                                else if($detail_record_type == 003)
                                {
                                    if(isset($file_name_array[0]) && $file_name_array[0] == 'WSTVU07')
                                    {
                                        
                                        $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("record_type3" => substr($val_string, 0, 3),"sequence_number3" => substr($val_string, 3, 3),"account_registration_line5" => substr($val_string, 6, 35),"account_registration_line6" => substr($val_string, 41, 35),"account_registration_line7" => substr($val_string, 76, 35),"representative_number" => substr($val_string, 111, 22),"representative_name" => substr($val_string, 133, 17),"position_close_out_indicator" => substr($val_string, 150, 1),"account_type_indicator" => substr($val_string, 151, 4),"product_identifier_code(VA only)" => substr($val_string, 155, 3),"mutual_fund_alternative_investment_program_managers_variable_annuties_and_VUL" => substr($val_string, 158, 2));
                                    
                                    }
                                    else
                                    {
                                        $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("record_type3" => substr($val_string, 0, 3),"sequence_number3" => substr($val_string, 3, 3),"account_registration_line5" => substr($val_string, 6, 35),"account_registration_line6" => substr($val_string, 41, 35),"account_registration_line7" => substr($val_string, 76, 35),"representative_number" => substr($val_string, 111, 9),"representative_name" => substr($val_string, 120, 30),"position_close_out_indicator" => substr($val_string, 150, 1),"account_type_indicator" => substr($val_string, 151, 4),"product_identifier_code(VA only)" => substr($val_string, 155, 3),"mutual_fund_alternative_investment_program_managers_variable_annuties_and_VUL" => substr($val_string, 158, 2));
                                    }
                                    
                                }
                                else if($detail_record_type == 004)
                                {
                                    $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("record_type4" => substr($val_string, 0, 3),"sequence_number4" => substr($val_string, 3, 3),"brokerage_identification_number(BIN)" => substr($val_string, 6, 20),"account_number_code_004" => substr($val_string, 26, 1),"primary_investor_phone_number" => substr($val_string, 27, 15),"secondary_investor_phone_number" => substr($val_string, 42, 15),"NSCC_trust_company_number" => substr($val_string, 57, 4),"NSCC_third_party_administrator_number" => substr($val_string, 61, 4),"unused_004_1" => substr($val_string, 65, 23),"trust_custodian_id_number" => substr($val_string, 88, 7),"third_party_administrator_id_number" => substr($val_string, 95, 7),"unused_004_2" => substr($val_string, 102, 58));
                                
                                }
                                
                            }
                            else if(isset($record_type) && $record_type == 'SFR')
                            {
                                $detail_record_type = substr($val_string, 3, 3);
                                if($detail_record_type == 001)
                                {
                                    if($array_detail_check_key_sfr>0)
                                    {
                                        $array_detail_key_sfr++;
                                    }
                                    $data_array[$array_key]['DETAIL'][$array_detail_key_sfr][$detail_record_type] = array("record_type1" => substr($val_string, 0, 3),"sequence_number1" => substr($val_string, 3, 3),"cusip_number" => substr($val_string, 6, 9),"fund_code" => substr($val_string, 15, 7),"fund_name" => substr($val_string, 22, 40),"product_name" => substr($val_string, 62, 38),"ticker_symbol" => substr($val_string, 100, 8),"major_security_type" => substr($val_string, 108, 2),"unused" => substr($val_string, 110, 50));
                                    $array_detail_check_key_sfr++;
                                }
                            }
                            else if(isset($record_type) && $record_type == 'RTR')
                            {
                                $data_array[$array_key][$record_type] = array("record_type" => substr($val_string, 0, 3),"sequence_number" => substr($val_string, 3, 3),"file_type" => substr($val_string, 6, 15),"trailer_record_count" => substr($val_string, 21, 9),"unused" => substr($val_string, 30, 130));
                                $array_key++;
                            }
                         }
                         //insert file data in import_detail_table
                         foreach($data_array as $main_key=>$main_val)
                         {
                            $RHR = $main_val['RHR'];
                            
                            $file_type = trim($RHR['file_type']);
                            if($file_type == 'SECURITY FILE')
                            {
                                $q = "INSERT INTO `".IMPORT_SFR_HEADER_DATA."` SET `file_id`='".$id."',`record_type`='".$RHR['record_type']."',`sequence_number`='".$RHR['sequence_number']."',`file_type`='".$RHR['file_type']."',`super_sheet_date`='".date('Y-m-d',strtotime($RHR['super_sheet_date']))."',`processed_date`='".date('Y-m-d',strtotime($RHR['processed_date']))."',`processed_time`='".date('H:i:s',strtotime($RHR['processed_time']))."',`job_name`='".$RHR['job_name']."',`file_format_code`='".$RHR['file_format_code']."',`request_number`='".$RHR['request_number']."',`extra_*_1`='".$RHR['*']."',`system_id`='".$RHR['system_id']."',`management_code`='".$RHR['management_code']."',`extra_*_2`='".$RHR['**']."',`unused_mutual_fund`='".$RHR['unused_mutual_fund']."',`life_date_type`='".$RHR['life_date_type']."',`unused_RHR_001`='".$RHR['unused_header_RHR']."'".$this->insert_common_sql();                                     }
                            else
                            {
                                $q = "INSERT INTO `".IMPORT_HEADER1_DATA."` SET `file_id`='".$id."',`record_type`='".$RHR['record_type']."',`sequence_number`='".$RHR['sequence_number']."',`file_type`='".$RHR['file_type']."',`super_sheet_date`='".date('Y-m-d',strtotime($RHR['super_sheet_date']))."',`processed_date`='".date('Y-m-d',strtotime($RHR['processed_date']))."',`processed_time`='".date('H:i:s',strtotime($RHR['processed_time']))."',`job_name`='".$RHR['job_name']."',`file_format_code`='".$RHR['file_format_code']."',`request_number`='".$RHR['request_number']."',`extra_*_1`='".$RHR['*']."',`system_id`='".$RHR['system_id']."',`management_code`='".$RHR['management_code']."',`extra_*_2`='".$RHR['**']."',`populated_by_dst`='".$RHR['populated_by_dst']."',`variable_universal_life`='".$RHR['variable_universal_life']."',`unused_RHR_001`='".$RHR['unused_header_RHR']."'".$this->insert_common_sql();                
                            }
                			$res = $this->re_db_query($q);
                            $rhr_inserted_id = $this->re_db_insert_id();
                            $data_status = true;
                            
                            
                            $PLH = isset($main_val['PLH'])?$main_val['PLH']:array();
                            if($PLH != array())
                            {
                            foreach($PLH as $plh_key=>$plh_val)
                            {
                                if($plh_key == 001)
                                {
                                    $q = "INSERT INTO `".IMPORT_HEADER2_DATA."` SET `file_id`='".$id."',`header_id`='".$rhr_inserted_id."',`record_type1`='".$plh_val['record_type1']."',`sequence_number1`='".$plh_val['sequence_number1']."',`anniversary_date`='".date('Y-m-d',strtotime($plh_val['anniversary_date']))."',`issue_date`='".date('Y-m-d',strtotime($plh_val['issue_date']))."',`product_code`='".$plh_val['product_code']."',`policy_contract_number`='".$plh_val['policy_contract_number']."',`death_benefit_option`='".$plh_val['death_benefit_option']."',`current_policy_face_amount`='".$plh_val['current_policy_face_amount']."',`current_sum_of_riders`='".$plh_val['current_sum_of_riders']."',`current_face_amount_including_sum_of_riders`='".$plh_val['current_face_amount_including_sum_of_riders']."',`name_of_primary_beneficiary`='".$plh_val['name_of_primary_beneficiary']."',`multiple_primary_beneficiary(M)`='".$plh_val['multiple_primary_beneficiary(M)']."',`name_of_secondary_beneficiary`='".$plh_val['name_of_secondary_beneficiary']."',`multiple_secondary_beneficiary(M)`='".$plh_val['multiple_secondary_beneficiary(M)']."',`policy_status`='".$plh_val['policy_status']."',`unused_PLH_001`='".$plh_val['unused_PLH_001']."'".$this->insert_common_sql();
                        			$res = $this->re_db_query($q);
                                    $plh_inserted_id = $this->re_db_insert_id();
                                    $data_status = true;
                                }
                                else if($plh_key == 002)
                                {
                                    $q = "UPDATE `".IMPORT_HEADER2_DATA."` SET `record_type2`='".$plh_val['record_type2']."',`sequence_number2`='".$plh_val['sequence_number2']."',`billing_type`='".$plh_val['billing_type']."',`billing_frequency`='".$plh_val['billing_frequency']."',`billing_amount`='".$plh_val['billing_amount']."',`guideline_annual_premium`='".$plh_val['guideline_annual_premium']."',`guideline_single_premium`='".$plh_val['guideline_single_premium']."',`target_premium`='".$plh_val['target_premium']."',`no_lapse_guarantee_premium`='".$plh_val['no_lapse_guarantee_premium']."',`seven_pay_premium`='".$plh_val['seven_pay_premium']."',`MEC_indicator`='".$plh_val['MEC_indicator']."',`unused_PLH_002`='".$plh_val['unused_PLH_002']."'".$this->update_common_sql()." WHERE `id`='".$plh_inserted_id."'";
                        			$res = $this->re_db_query($q);
                                    $data_status = true;
                                }
                            }
                            }
                            $DETAIL = $main_val['DETAIL'];
                            foreach($DETAIL as $detail_key=>$detail_val)
                            {
                                foreach($detail_val as $seq_key=>$seq_val)
                                {
                                    if($seq_key == 001)
                                    {
                                        if($seq_val['record_type1'] == 'SFR')
                                        {
                                            $q = "INSERT INTO `".IMPORT_SFR_DETAIL_DATA."` SET `file_id`='".$id."',`sfr_header_id`='".$rhr_inserted_id."',`record_type1`='".$seq_val['record_type1']."',`sequence_number1`='".$seq_val['sequence_number1']."',`cusip_number`='".$seq_val['cusip_number']."',`fund_code`='".$seq_val['fund_code']."',`fund_name`='".$seq_val['fund_name']."',`product_name`='".$seq_val['product_name']."',`ticker_symbol`='".$seq_val['ticker_symbol']."',`major_security_type`='".$seq_val['major_security_type']."',`unused`='".$seq_val['unused']."'".$this->insert_common_sql();
                                        }
                                        else
                                        {
                                            $q = "INSERT INTO `".IMPORT_DETAIL_DATA."` SET `file_id`='".$id."',`header_id`='".$rhr_inserted_id."',`record_type1`='".$seq_val['record_type1']."',`sequence_number1`='".$seq_val['sequence_number1']."',`dealer_number`='".$seq_val['dealer_number']."',`dealer_branch_number`='".$seq_val['dealer_branch_number']."',`cusip_number`='".$seq_val['cusip_number']."',`mutual_fund_fund_code`='".$seq_val['mutual_fund_fund_code']."',`mutual_fund_customer_account_number`='".$seq_val['mutual_fund_customer_account_number']."',`account_number_code`='".$seq_val['account_number_code']."',`mutual_fund_established_date`='".date('Y-m-d',strtotime($seq_val['mutual_fund_established_date']))."',`last_maintenance_date`='".date('Y-m-d',strtotime($seq_val['last_maintenance_date']))."',`line_code`='".$seq_val['line_code']."',`alpha_code`='".$seq_val['alpha_code']."',`mutual_fund_dealer_level_control_code`='".$seq_val['mutual_fund_dealer_level_control_code']."',`social_code`='".$seq_val['social_code']."',`resident_state_country_code`='".$seq_val['resident_state_country_code']."',`social_security_number`='".$seq_val['social_security_number']."',`ssn_status_code`='".$seq_val['ssn_status_code']."',`systematic_withdrawal_plan(SWP)_account`='".$seq_val['systematic_withdrawal_plan(SWP)_account']."',`pre_authorized_checking_amount`='".$seq_val['pre_authorized_checking_amount']."',`automated_clearing_house_account(ACH)`='".$seq_val['automated_clearing_house_account(ACH)']."',`mutual_fund_reinvest_to_another_account`='".$seq_val['mutual_fund_reinvest_to_another_account']."',`mutual_fund_capital_gains_distribution_option`='".$seq_val['mutual_fund_capital_gains_distribution_option']."',`mutual_fund_divident_distribution_option`='".$seq_val['mutual_fund_divident_distribution_option']."',`check_writing_account`='".$seq_val['check_writing_account']."',`expedited_redemption_account`='".$seq_val['expedited_redemption_account']."',`mutual_fund_sub_account`='".$seq_val['mutual_fund_sub_account']."',`foreign_tax_rate`='".$seq_val['foreign_tax_rate']."',`zip_code`='".$seq_val['zip_code']."',`zipcode_future_expansion`='".$seq_val['zipcode_future_expansion']."',`cumulative_discount_number`='".$seq_val['cumulative_discount_number']."',`letter_of_intent(LOI)_number`='".$seq_val['letter_of_intent(LOI)_number']."',`timer_flag`='".$seq_val['timer_flag']."',`list_bill_account`='".$seq_val['list_bill_account']."',`mutual_fund_monitored_VIP_account`='".$seq_val['mutual_fund_monitored_VIP_account']."',`mutual_fund_expedited_exchange_account`='".$seq_val['mutual_fund_expedited_exchange_account']."',`mutual_fund_penalty_withholding_account`='".$seq_val['mutual_fund_penalty_withholding_account']."',`certificate_issuance_code`='".$seq_val['certificate_issuance_code']."',`mutual_fund_stop_transfer_flag`='".$seq_val['mutual_fund_stop_transfer_flag']."',`mutual_fund_blue_sky_exemption_flag`='".$seq_val['mutual_fund_blue_sky_exemption_flag']."',`bank_card_issued`='".$seq_val['bank_card_issued']."',`fiduciary_account`='".$seq_val['fiduciary_account']."',`plan_status_code`='".$seq_val['plan_status_code']."',`mutual_fund_net_asset_value(NAV)_account`='".$seq_val['mutual_fund_net_asset_value(NAV)_account']."',`mailing_flag`='".$seq_val['mailing_flag']."',`interested_party_code`='".$seq_val['interested_party_code']."',`mutual_fund_share_account_phone_check_redemption_code`='".$seq_val['mutual_fund_share_account_phone_check_redemption_code']."',`mutual_fund_share_account_house_account_code`='".$seq_val['mutual_fund_share_account_house_account_code']."'".$this->insert_common_sql();
                                        }
                            			$res = $this->re_db_query($q);
                                        $detail_inserted_id = $this->re_db_insert_id();
                                        $data_status = true;
                                    }
                                    else if($seq_key == 002)
                                    {
                                        $q = "UPDATE `".IMPORT_DETAIL_DATA."` SET `record_type2`='".$seq_val['record_type2']."',`sequence_number2`='".$seq_val['sequence_number2']."',`mutual_fund_dividend_mail_account`='".$seq_val['mutual_fund_dividend_mail_account']."',`mutual_fund_stop_purchase_account`='".$seq_val['mutual_fund_stop_purchase_account']."',`stop_mail_account`='".$seq_val['stop_mail_account']."',`mutual_fund_fractional_check`='".$seq_val['mutual_fund_fractional_check']."',`registration_line1`='".$seq_val['registration_line1']."',`registration_line2`='".$seq_val['registration_line2']."',`registration_line3`='".$seq_val['registration_line3']."',`registration_line4`='".$seq_val['registration_line4']."',`customer_date_of_birth`='".date('Y-m-d',strtotime($seq_val['customer_date_of_birth']))."',`mutual_fund_account_price_schedule_code`='".$seq_val['mutual_fund_account_price_schedule_code']."',`unused_002_1`='".$seq_val['unused_detail']."'".$this->update_common_sql()." WHERE `id`='".$detail_inserted_id."'";
                            			$res = $this->re_db_query($q);
                                        $data_status = true;
                                    }
                                    else if($seq_key == 003)
                                    {
                                        $q = "UPDATE `".IMPORT_DETAIL_DATA."` SET `record_type3`='".$seq_val['record_type3']."',`sequence_number3`='".$seq_val['sequence_number3']."',`account_registration_line5`='".$seq_val['account_registration_line5']."',`account_registration_line6`='".$seq_val['account_registration_line6']."',`account_registration_line7`='".$seq_val['account_registration_line7']."',`representative_number`='".$seq_val['representative_number']."',`representative_name`='".$seq_val['representative_name']."',`position_close_out_indicator`='".$seq_val['position_close_out_indicator']."',`account_type_indicator`='".$seq_val['account_type_indicator']."',`product_identifier_code(VA only)`='".$seq_val['product_identifier_code(VA only)']."',`mutual_fund_alternative_investment_program_managers_variable_ann`='".$seq_val['mutual_fund_alternative_investment_program_managers_variable_annuties_and_VUL']."'".$this->update_common_sql()." WHERE `id`='".$detail_inserted_id."'";
                            			$res = $this->re_db_query($q);
                                        $data_status = true;
                                    }
                                    else if($seq_key == 004)
                                    {
                                        $q = "UPDATE `".IMPORT_DETAIL_DATA."` SET `record_type4`='".$seq_val['record_type4']."',`sequence_number4`='".$seq_val['sequence_number4']."',`brokerage_identification_number(BIN)`='".$seq_val['brokerage_identification_number(BIN)']."',`account_number_code_004`='".$seq_val['account_number_code_004']."',`primary_investor_phone_number`='".$seq_val['primary_investor_phone_number']."',`secondary_investor_phone_number`='".$seq_val['secondary_investor_phone_number']."',`NSCC_trust_company_number`='".$seq_val['NSCC_trust_company_number']."',`NSCC_third_party_administrator_number`='".$seq_val['NSCC_third_party_administrator_number']."',`unused_004_1`='".$seq_val['unused_004_1']."',`trust_custodian_id_number`='".$seq_val['trust_custodian_id_number']."',`third_party_administrator_id_number`='".$seq_val['third_party_administrator_id_number']."',`unused_004_2`='".$seq_val['unused_004_2']."'".$this->update_common_sql()." WHERE `id`='".$detail_inserted_id."'";
                            			$res = $this->re_db_query($q);
                                        $data_status = true;
                                    }
                                }
                                
                            }
                            
                            $RTR = $main_val['RTR'];
                            $file_type = trim($RTR['file_type']);
                            if($file_type == 'SECURITY FILE')
                            {
                                $q = "INSERT INTO `".IMPORT_SFR_FOOTER_DATA."` SET `file_id`='".$id."',`sfr_header_id`='".$rhr_inserted_id."',`record_type`='".$RTR['record_type']."',`sequence_number`='".$RTR['sequence_number']."',`file_type`='".$RTR['file_type']."',`trailer_record_count`='".$RTR['trailer_record_count']."',`unused`='".$RTR['unused']."'".$this->insert_common_sql();
                            }
                            else
                            {
                                $q = "INSERT INTO `".IMPORT_FOOTER_DATA."` SET `file_id`='".$id."',`header_id`='".$rhr_inserted_id."',`record_type`='".$RTR['record_type']."',`sequence_number`='".$RTR['sequence_number']."',`file_type`='".$RTR['file_type']."',`trailer_record_count`='".$RTR['trailer_record_count']."',`unused`='".$RTR['unused']."'".$this->insert_common_sql();
                            }
                            
                			$res = $this->re_db_query($q);
                            $data_status = true;
                            
                         }
                         //Generate exception on fanmail-file data
                         
                         $check_fanmail_array = $this->get_fanmail_detail_data($id);
                         foreach($check_fanmail_array as $check_data_key=>$check_data_val)
                         {
                            $result = 0;
                            if(isset($check_data_val['representative_number']))
                            {
                                $rep_number = isset($check_data_val['representative_number'])?$this->re_db_input($check_data_val['representative_number']):'';
                                
                                if($rep_number != '')
                                {
                                    $q_broker = "SELECT * FROM `".BROKER_MASTER."` WHERE `is_delete`='0' AND `fund`='".$rep_number."'";
                    				$res_broker = $this->re_db_query($q_broker);
                    				$return = $this->re_db_num_rows($res_broker);
                    				if($return <= 0)
                                    {
                    				    $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."',`client`='".$check_data_val['registration_line1']."',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='1',`field`='representative_number',`file_type`='1'".$this->insert_common_sql();
                    			        $res = $this->re_db_query($q);
                                        $result = 1;
                    				}
                                    else
                                    {
                                        $check_broker_termination = $this->broker_termination_date($rep_number);
                                        if($check_broker_termination != '')
                                        {
                                            $current_date = date('Y-m-d');
                                            if($current_date>$check_broker_termination)
                                            {
                                                $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."',`client`='".$check_data_val['registration_line1']."',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='2',`field`='u5',`file_type`='1'".$this->insert_common_sql();
                            			        $res = $this->re_db_query($q);
                                                $result = 1;
                                            }
                                        }
                                    }
                                }
                            }
                            
                            if(isset($check_data_val['registration_line1']))
                            {
                                $registration_line1 = isset($check_data_val['registration_line1'])?$this->re_db_input($check_data_val['registration_line1']):'';
                                $client_name_array = explode(' ',$registration_line1);
                                if(isset($client_name_array[2]) && $client_name_array[2] != '')
                                {
                                    $first_name = isset($client_name_array[0])?$this->re_db_input($client_name_array[0]):'';
                                    $middle_name = isset($client_name_array[1])?$this->re_db_input($client_name_array[1]):'';
                                    $last_name = isset($client_name_array[2])?$this->re_db_input($client_name_array[2]):'';
                                }
                                else
                                {
                                    $first_name = isset($client_name_array[0])?$this->re_db_input($client_name_array[0]):'';
                                    $middle_name = '';
                                    $last_name = isset($client_name_array[1])?$this->re_db_input($client_name_array[1]):'';
                                }
                                if($registration_line1 != '')
                                {
                                    $q = "SELECT * FROM `".CLIENT_MASTER."` WHERE `is_delete`='0' AND `first_name`='".$first_name."' AND `mi`='".$middle_name."' AND `last_name`='".$last_name."' AND `zip_code`='".$check_data_val['zip_code']."'";
                    				$res = $this->re_db_query($q);
                    				$return = $this->re_db_num_rows($res);
                    				if($return > 0)
                                    {
                    				    $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."',`client`='".$check_data_val['registration_line1']."',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='12',`field`='registration_line1',`file_type`='1'".$this->insert_common_sql();
                    			        $res = $this->re_db_query($q);
                                        $result = 1;
                    				}
                                }
                                /*else
                                {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."',`client`='".$check_data_val['registration_line1']."',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='10',`field`='registration_line1',`file_type`='1'".$this->insert_common_sql();
                    			        $res = $this->re_db_query($q);
                                        $result = 1;
                                }*/
                			}
                            
                            /*if(isset($check_data_val['mutual_fund_customer_account_number']))
                            {
                                $mutual_fund_customer_account_number = isset($check_data_val['mutual_fund_customer_account_number'])?$this->re_db_input($check_data_val['mutual_fund_customer_account_number']):'';
                                
                                if($mutual_fund_customer_account_number == '')
                                {
                                    $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."',`client`='".$check_data_val['registration_line1']."',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='18',`field`='mutual_fund_customer_account_number',`file_type`='1'".$this->insert_common_sql();
                 			        $res = $this->re_db_query($q);
                                    $result = 1;
                                }
                            }*/
                            
                            /*if(isset($check_data_val['social_security_number']))
                            {
                                $social_security_number = isset($check_data_val['social_security_number'])?$this->re_db_input($check_data_val['social_security_number']):'';
                                
                                if($social_security_number != '')
                                {
                                    $q = "SELECT * FROM `".CLIENT_MASTER."` WHERE `is_delete`='0' AND `client_ssn`='".$social_security_number."' ";
                    				$res = $this->re_db_query($q);
                    				$return = $this->re_db_num_rows($res);
                    				if($return > 0)
                                    {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."',`client`='".$check_data_val['registration_line1']."',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='19',`field`='social_security_number',`file_type`='1'".$this->insert_common_sql();
                    			        $res = $this->re_db_query($q);
                                        $result = 1;
                                    }
                                }
                            }*/
                            if(isset($result) && $result == 0)
                            {
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."',`client`='".$check_data_val['registration_line1']."',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='0',`field`='',`solved`='1',`file_type`='1'".$this->insert_common_sql();
             			        $res = $this->re_db_query($q);
                            }
                            
                         }
                        
                         $check_sfr_array = $this->get_sfr_detail_data($id);
                         foreach($check_sfr_array as $check_data_key=>$check_data_val)
                         {
                            $result = 0;
                            if(isset($check_data_val['major_security_type']) && $check_data_val['major_security_type'] != '' && isset($check_data_val['product_name']) && $check_data_val['product_name'] != '')
                            {
                                $q = "SELECT * FROM `".PRODUCT_TYPE."` WHERE `is_delete`='0' and `status`='1' and `type_code`='".$check_data_val['major_security_type']."'";
                				$res = $this->re_db_query($q);
                				if($this->re_db_num_rows($res)>0){
                                    while($row = $this->re_db_fetch_array($res))
                                    {
                                        $q2 = "SELECT * FROM `product_category_".$row['id']."` WHERE `is_delete`='0' and `cusip`='".$check_data_val['cusip_number']."'";
                        				$res2 = $this->re_db_query($q2);
                        				if($this->re_db_num_rows($res2)>0)
                                        {
                                            $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='',`rep_name`='',`account_no`='',`client`='',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='16',`field`='cusip_number',`file_type`='3'".$this->insert_common_sql();
                        			        $res = $this->re_db_query($q);
                                            $result = 1;
                                            
                                        }
                                        $q3 = "SELECT * FROM `product_category_".$row['id']."` WHERE `is_delete`='0' and `ticker_symbol`='".$check_data_val['ticker_symbol']."'";
                        				$res3 = $this->re_db_query($q3);
                        				if($this->re_db_num_rows($res3)>0)
                                        {
                                            $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='',`rep_name`='',`account_no`='',`client`='',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='15',`field`='ticker_symbol',`file_type`='3'".$this->insert_common_sql();
                        			        $res = $this->re_db_query($q);
                                            $result = 1;
                                            
                                        }
                                    }
                                    if(isset($result) && $result == 0)
                                    {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='',`rep_name`='',`account_no`='',`client`='',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='0',`field`='',`file_type`='3',`solved`='1'".$this->insert_common_sql();
                     			        $res = $this->re_db_query($q);
                                    }
                                }
                            }
                         }
                     }
                     else if(isset($file_type_check) && $file_type_check == 'C1')
                     {
                         foreach($file_string_array as $key_string=>$val_string)
                         {
                            $record_type = substr($val_string, 0, 3);
                            if(isset($record_type) && $record_type == 'RHR')
                            {
                                $data_array[$array_key][$record_type] = array("record_type" => substr($val_string, 0, 3),"file_type" => substr($val_string, 3, 10),"system_id" => substr($val_string, 13, 3),"management_code" => substr($val_string, 16, 2),"fund_sponsor_id" => substr($val_string, 18, 5),"transmission_date" => substr($val_string, 23, 8),"unused_RHR" => substr($val_string, 31, 169));
                            }
                            else if(isset($record_type) && ($record_type != 'RHR' && $record_type != 'RTR'))
                            {
                                $commission_record_type_code = substr($val_string, 0, 1);
                                if($commission_record_type_code == '1' || $commission_record_type_code == '3')
                                {
                                    $data_array[$array_key]['DETAIL'][$commission_record_type_code][] = array("commission_record_type_code" => substr($val_string, 0, 1),"dealer_number" => substr($val_string, 1, 7),"dealer_branch_number" => substr($val_string, 8, 9),"representative_number" => substr($val_string, 17, 9),"representative_name" => substr($val_string, 26, 30),"CUSIP_number" => substr($val_string, 56, 9),"alpha_code" => substr($val_string, 65, 10),"trade_date" => substr($val_string, 75, 8),"gross_transaction_amount" => substr($val_string, 83, 15),"gross_amount_sign_code" => substr($val_string, 98, 1),"dealer_commission_amount" => substr($val_string, 99, 15),"commission_rate" => substr($val_string, 114, 5),"customer_account_number" => substr($val_string, 119, 20),"account_number_type_code" => substr($val_string, 139, 1),"purchase_type_code" => substr($val_string, 140, 1),"social_code" => substr($val_string, 141, 3),"cumulative_discount_number" => substr($val_string, 144, 9),"letter_of_intent(LOI)_number" => substr($val_string, 153, 9),"social_security_number" => substr($val_string, 162, 9),"social_security_number_status_code" => substr($val_string, 171, 1),"transaction_share_count" => substr($val_string, 172, 15),"share_price_amount" => substr($val_string, 187, 9),"resident_state_country_code" => substr($val_string, 196, 3),"dealer_commission_sign_code" => substr($val_string, 199, 1));
                                }
                            }
                            else if(isset($record_type) && $record_type == 'RTR')
                            {
                                $data_array[$array_key][$record_type] = array("record_type" => substr($val_string, 0, 3),"file_type" => substr($val_string, 3, 10),"trailer_record_count" => substr($val_string, 13, 7),"unused_RTR" => substr($val_string, 20, 180));
                                $array_key++;
                            }
                            
                         }
                         foreach($data_array as $main_key=>$main_val)
                         {
                            $RHR = $main_val['RHR'];
                            $q = "INSERT INTO `".IMPORT_IDC_HEADER_DATA."` SET `file_id`='".$id."',`record_type`='".$RHR['record_type']."',`file_type`='".$RHR['file_type']."',`system_id`='".$RHR['system_id']."',`management_code`='".$RHR['management_code']."',`fund_sponsor_id`='".$RHR['fund_sponsor_id']."',`transmission_date`='".date('Y-m-d',strtotime($RHR['transmission_date']))."',`unused_RHR`='".$RHR['unused_RHR']."'".$this->insert_common_sql();
                			$res = $this->re_db_query($q);
                            $rhr_inserted_id = $this->re_db_insert_id();
                            $data_status = true;
                            //echo '<pre>';print_r($main_val);exit;
                            $DETAIL = $main_val['DETAIL'];
                            foreach($DETAIL as $detail_key=>$detail_val)
                            {
                                if($detail_key == '1' || $detail_key == '3')
                                {
                                    foreach($detail_val as $seq_key=>$seq_val)
                                    {   
                                        $q = "INSERT INTO `".IMPORT_IDC_DETAIL_DATA."` SET `file_id`='".$id."',`idc_header_id`='".$rhr_inserted_id."',`commission_record_type_code`='".$seq_val['commission_record_type_code']."',`dealer_number`='".$seq_val['dealer_number']."',`dealer_branch_number`='".$seq_val['dealer_branch_number']."',`representative_number`='".$seq_val['representative_number']."',`representative_name`='".$seq_val['representative_name']."',`CUSIP_number`='".$seq_val['CUSIP_number']."',`alpha_code`='".$seq_val['alpha_code']."',`trade_date`='".date('Y-m-d',strtotime($seq_val['trade_date']))."',`gross_transaction_amount`='".($seq_val['gross_transaction_amount']/100)."',`gross_amount_sign_code`='".$seq_val['gross_amount_sign_code']."',`dealer_commission_amount`='".($seq_val['dealer_commission_amount']/100)."',`commission_rate`='".$seq_val['commission_rate']."',`customer_account_number`='".$seq_val['customer_account_number']."',`account_number_type_code`='".$seq_val['account_number_type_code']."',`purchase_type_code`='".$seq_val['purchase_type_code']."',`social_code`='".$seq_val['social_code']."',`cumulative_discount_number`='".$seq_val['cumulative_discount_number']."',`letter_of_intent(LOI)_number`='".$seq_val['letter_of_intent(LOI)_number']."',`social_security_number`='".$seq_val['social_security_number']."',`social_security_number_status_code`='".$seq_val['social_security_number_status_code']."',`transaction_share_count`='".$seq_val['transaction_share_count']."',`share_price_amount`='".$seq_val['share_price_amount']."',`resident_state_country_code`='".$seq_val['resident_state_country_code']."',`dealer_commission_sign_code`='".$seq_val['dealer_commission_sign_code']."'".$this->insert_common_sql();
                            			$res = $this->re_db_query($q);
                                        $data_status = true;
                                   }
                               }
                            }
                            
                            $RTR = $main_val['RTR'];
                            $q = "INSERT INTO `".IMPORT_IDC_FOOTER_DATA."` SET `file_id`='".$id."',`idc_header_id`='".$rhr_inserted_id."',`record_type`='".$RTR['record_type']."',`file_type`='".$RTR['file_type']."',`trailer_record_count`='".$RTR['trailer_record_count']."',`unused_RTR`='".$RTR['unused_RTR']."'".$this->insert_common_sql();
                			$res = $this->re_db_query($q);
                            $data_status = true;
                         }
                         //Generate exception on idc-file data
                         
                         $check_idc_array = $this->get_idc_detail_data($id);
                         foreach($check_idc_array as $check_data_key=>$check_data_val)
                         {
                            $result=0;
                            if(isset($check_data_val['representative_number']))
                            {
                                $rep_number = isset($check_data_val['representative_number'])?$this->re_db_input($check_data_val['representative_number']):'';
                                
                                if($rep_number != '')
                                {
                                    $q_broker = "SELECT * FROM `".BROKER_MASTER."` WHERE `is_delete`='0' AND `fund`='".$rep_number."'";
                    				$res_broker = $this->re_db_query($q_broker);
                    				$return = $this->re_db_num_rows($res_broker);
                    				if($return <= 0)
                                    {
                    				    $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='1',`field`='representative_number',`file_type`='2'".$this->insert_common_sql();
                    			        $res = $this->re_db_query($q);
                                        $result = 1;
                    				}
                                    else
                                    {
                                        $check_broker_termination = $this->broker_termination_date($rep_number);
                                        $check_hold_commission = $this->get_hold_commission($check_data_val['file_id'],$check_data_val['id']);
                                        if($check_broker_termination != '' && $check_hold_commission == '0')
                                        {
                                            $current_date = date('Y-m-d');
                                            if($current_date>$check_broker_termination)
                                            {
                                                $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='2',`field`='u5',`file_type`='2'".$this->insert_common_sql();
                            			        $res = $this->re_db_query($q);
                                                $result = 1;
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='13',`field`='representative_number',`file_type`='2'".$this->insert_common_sql();
                			        $res = $this->re_db_query($q);
                                    $result = 1;
                                }
                            }
                            if(isset($check_data_val['CUSIP_number']) && trim($check_data_val['CUSIP_number']) == '')
                            {
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='13',`field`='CUSIP_number',`file_type`='2'".$this->insert_common_sql();
            			        $res = $this->re_db_query($q);
                                $result = 1;
                            }
                            if(isset($check_data_val['CUSIP_number']) && trim($check_data_val['CUSIP_number']) != '')
                            {
                                $y=0;
                                $q = "SELECT * FROM `".PRODUCT_TYPE."` WHERE `is_delete`='0' and `status`='1'";
                				$res = $this->re_db_query($q);
                				if($this->re_db_num_rows($res)>0){
                                    while($row = $this->re_db_fetch_array($res))
                                    {
                                        if($y==0)
                                        {
                                            $q2 = "SELECT * FROM `product_category_".$row['id']."` WHERE `is_delete`='0' and `cusip`='".$check_data_val['CUSIP_number']."'";
                            				$res2 = $this->re_db_query($q2);
                            				if($this->re_db_num_rows($res2)>0)
                                            {
                                                while($row2 = $this->re_db_fetch_array($res2))
                                                {
                                                    if($row2['status']==0)
                                                    {
                                                        $q3 = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='8',`field`='status',`file_type`='2'".$this->insert_common_sql();
                                     			        $res3 = $this->re_db_query($q3);
                                                        $result=1;
                                                    }
                                                    if($row2['objective'] > 0 && $check_data_val['customer_account_number'] != '')
                                                    { 
                                                        $q4 = "SELECT `cm`.*
                                                        FROM `".CLIENT_MASTER."` as `cm`
                                                        LEFT JOIN `".CLIENT_ACCOUNT."` as `ca` on `ca`.`client_id`=`cm`.`id`
                                                        WHERE `cm`.`is_delete`='0' AND `ca`.`account_no`='".$check_data_val['customer_account_number']."'";
                                        				$res4 = $this->re_db_query($q4);
                                        				if($this->re_db_num_rows($res4)>0)
                                                        {
                                                            while($row4 = $this->re_db_fetch_array($res4))
                                                            {
                                                                $q5 = "SELECT `co`.*
                                                                FROM `".CLIENT_OBJECTIVES."` as `co`
                                                                WHERE `co`.`is_delete`='0' AND `co`.`objectives`='".$row2['objective']."' AND `client_id`='".$row4['id']."'";
                                                				$res5 = $this->re_db_query($q5);
                                                				if($this->re_db_num_rows($res5)<=0)
                                                                {
                                                                    $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='9',`field`='objectives',`file_type`='2'".$this->insert_common_sql();
                                                			        $res = $this->re_db_query($q);
                                                                    $result = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    /*if($row2['sponsor']==''||$row2['sponsor']<=0)
                                                    {
                                                        $q3 = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='14',`field`='sponsor',`file_type`='2'".$this->insert_common_sql();
                                     			        $res3 = $this->re_db_query($q3);
                                                        $result=1;
                                                    }*/
                                                }
                                                $y++;
                                                break;  
                                            }
                                            else
                                            {
                                                $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='11',`field`='CUSIP_number',`file_type`='2'".$this->insert_common_sql();
                            			        $res = $this->re_db_query($q);
                                                $result = 1;
                                            }
                                        }
                                    }
                                }
                            }
                            if(isset($check_data_val['system_id']) && $check_data_val['system_id'] != '')
                            {
                                $q = "SELECT * FROM `".SPONSOR_MASTER."` WHERE `is_delete`='0' and `dst_system_id`='".$check_data_val['system_id']."' and `dst_mgmt_code`='".$check_data_val['management_code']."'";
                				$res = $this->re_db_query($q);
                				if($this->re_db_num_rows($res)<=0){
                				    
                				    $q2 = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='14',`field`='sponsor',`file_type`='2'".$this->insert_common_sql();
                 			        $res2 = $this->re_db_query($q2);
                                    $result=1;
            				    }
                            }
                            if(isset($check_data_val['customer_account_number']))
                            {
                                $customer_account_number = isset($check_data_val['customer_account_number'])?$this->re_db_input($check_data_val['customer_account_number']):'';
                                
                                if($customer_account_number != '')
                                {
                                    /*$q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`client`='".$check_data_val['alpha_code']."',`broker`='".$check_data_val['representative_name']."',`product`='',`amount`='',`error_code_id`='18',`field`='customer_account_number',`file_type`='2'".$this->insert_common_sql();
                 			        $res = $this->re_db_query($q);
                                    $result=1;
                                }
                                else
                                {*/
                                    $q = "SELECT `cm`.`state`
                                    FROM `".CLIENT_MASTER."` as `cm`
                                    LEFT JOIN `".CLIENT_ACCOUNT."` as `ca` on `ca`.`client_id`=`cm`.`id`
                                    WHERE `cm`.`is_delete`='0' AND `ca`.`account_no`='".$customer_account_number."' ";
                    				$res = $this->re_db_query($q);
                    				if($this->re_db_num_rows($res)>0){
                                        while($row = $this->re_db_fetch_array($res)){
                                            
                                            if(isset($check_data_val['representative_number']) && $check_data_val['representative_number'] != '' && isset($row['state']) && $row['state'] != '')
                                            {
                                                $q = "SELECT `bm`.* ,`bls`.`active_check` as active_check_security,`blr`.`active_check` as active_check_ria,`bli`.`active_check` as active_check_insurance
                                                FROM `".BROKER_MASTER."` as `bm`
                                                LEFT JOIN `".BROKER_LICENCES_SECURITIES."` as `bls` on `bls`.`broker_id`=`bm`.`id`
                                                LEFT JOIN `".BROKER_LICENCES_RIA."` as `blr` on `blr`.`broker_id`=`bm`.`id`
                                                LEFT JOIN `".BROKER_LICENCES_INSURANCE."` as `bli` on `bli`.`broker_id`=`bm`.`id`
                                                WHERE `bm`.`is_delete`='0' AND `bm`.`fund`='".$check_data_val['representative_number']."' AND `bls`.`state_id`='".$row['state']."' AND `blr`.`state_id`='".$row['state']."' AND `bli`.`state_id`='".$row['state']."' ";
                                			    $res = $this->re_db_query($q);
                                				if($this->re_db_num_rows($res)>0){
                                                    while($row = $this->re_db_fetch_array($res))
                                                    {
                                                        if($row['active_check_security'] != 1 && $row['active_check_ria'] != 1 && $row['active_check_insurance'] != 1)
                                                        {
                                                            $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='6',`field`='active_check',`file_type`='2'".$this->insert_common_sql();
                                        			        $res = $this->re_db_query($q);
                                                            $result = 1;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='18',`field`='customer_account_number',`file_type`='2'".$this->insert_common_sql();
                     			        $res = $this->re_db_query($q);
                                        $result=1;
                                    }
                                }
                                else
                                {
                                    $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='13',`field`='customer_account_number',`file_type`='2'".$this->insert_common_sql();
                			        $res = $this->re_db_query($q);
                                    $result = 1;
                                } 
                            }
                            if(isset($check_data_val['social_security_number']))
                            {
                                $social_security_number = isset($check_data_val['social_security_number'])?$this->re_db_input($check_data_val['social_security_number']):'';
                                
                                if($social_security_number != '')
                                {
                                    $q = "SELECT * FROM `".CLIENT_MASTER."` WHERE `is_delete`='0'  AND `active`='0'  AND `client_ssn`='".$social_security_number."' ";
                    				$res = $this->re_db_query($q);
                    				$return = $this->re_db_num_rows($res);
                                    if($return > 0)
                                    {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='7',`field`='active',`file_type`='2'".$this->insert_common_sql();
                    			        $res = $this->re_db_query($q);
                                    }
                                }
                            }
                            if(isset($result) && $result == 0)
                            {
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='0',`field`='',`file_type`='2',`solved`='1'".$this->insert_common_sql();
             			        $res = $this->re_db_query($q);
                                $result = 0;
                            }
                         }
                     }
                     if($data_status == 1)
                     {
                        $q_ = "UPDATE `".IMPORT_CURRENT_FILES."` SET `processed`='1',`last_processed_date`='".date('Y-m-d')."'".$this->update_common_sql()." WHERE `id`='".$id."'";
     			        $res_ = $this->re_db_query($q_);
                     }
                     if($res_ == true){
                        $_SESSION['success'] = 'Data successfully processed.';
        				return true;
        			 }
        			 else{
        				$_SESSION['warning'] = UNKWON_ERROR;
        				return false;
        			 }
                }
                
            }
       }
       public function reprocess_current_files($id){
            $reprocess_status = false;
            if($id > 0)
            {
                $q = "SELECT * FROM `".IMPORT_CURRENT_FILES."` WHERE `is_delete`='0' AND `process_completed`='1' AND `id`='".$id."'";
				$res = $this->re_db_query($q);
				$return = $this->re_db_num_rows($res);
                $this->errors='';
				if($return>0){
					$this->errors = 'Data already processed.';
				}                
				if($this->errors!=''){
					return $this->errors;
				}
                else
                {
                 //Remove already exist file trades   
                 $q = "SELECT * FROM `".TRANSACTION_MASTER."` WHERE `is_delete`='0' AND `file_id`='".$id."'";
				 $res = $this->re_db_query($q);
				 $return = $this->re_db_num_rows($res);
                 if($return>0){
                    
					 $q = "update `".TRANSACTION_MASTER."` set `is_delete`='1' WHERE `file_id`='".$id."'";
				     $res = $this->re_db_query($q);
				 } 
                 
                 //Remove already exist file clients   
                 $q = "SELECT * FROM `".CLIENT_MASTER."` WHERE `is_delete`='0' AND `file_id`='".$id."'";
				 $res = $this->re_db_query($q);
				 $return = $this->re_db_num_rows($res);
                 if($return>0){
                    
					 $q = "update `".CLIENT_MASTER."` set `is_delete`='1' WHERE `file_id`='".$id."'";
				     $res = $this->re_db_query($q);
				 } 
                   
                 $q = "update `".IMPORT_EXCEPTION."` set `is_delete`='1' WHERE `file_id`='".$id."'";
				 $res = $this->re_db_query($q);
                 //Generate exception on fanmail-file data
                 $check_fanmail_array = $this->get_fanmail_detail_data($id);
                 foreach($check_fanmail_array as $check_data_key=>$check_data_val)
                 {
                    $broker_id = 0;
                    $first_name = '';
                    $middle_name = '';
                    $last_name = '';
                    $result = 0;
                    if(isset($check_data_val['representative_number']))
                    {
                        $rep_number = isset($check_data_val['representative_number'])?$this->re_db_input($check_data_val['representative_number']):0;
                        
                        if($rep_number != '')
                        {
                            $q_broker = "SELECT * FROM `".BROKER_MASTER."` WHERE `is_delete`='0' AND `fund`='".$rep_number."'";
            				$res_broker = $this->re_db_query($q_broker);
            				$return = $this->re_db_num_rows($res_broker);
            				if($return <= 0)
                            {
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."',`client`='".$check_data_val['registration_line1']."',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='1',`field`='representative_number',`file_type`='1'".$this->insert_common_sql();
            			        $res = $this->re_db_query($q);
                                $result = 1;
            				}
                            else
                            {   $return = $this->re_db_fetch_array($res_broker);
                                $broker_id = $return['id'];
                                $check_broker_termination = $this->broker_termination_date($rep_number);
                                if($check_broker_termination != '')
                                {
                                    $current_date = date('Y-m-d');
                                    if($current_date>$check_broker_termination)
                                    {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."',`client`='".$check_data_val['registration_line1']."',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='2',`field`='u5',`file_type`='1'".$this->insert_common_sql();
                    			        $res = $this->re_db_query($q);
                                        $result = 1;
                                    }
                                }
                            }
                        }
                    }
                    if(isset($check_data_val['registration_line1']))
                    {
                        $registration_line1 = isset($check_data_val['registration_line1'])?$this->re_db_input($check_data_val['registration_line1']):'';
                        $client_name_array = explode(' ',$registration_line1);
                        if(isset($client_name_array[2]) && $client_name_array[2] != '')
                        {
                            $first_name = isset($client_name_array[0])?$this->re_db_input($client_name_array[0]):'';
                            $middle_name = isset($client_name_array[1])?$this->re_db_input($client_name_array[1]):'';
                            $last_name = isset($client_name_array[2])?$this->re_db_input($client_name_array[2]):'';
                        }
                        else
                        {
                            $first_name = isset($client_name_array[0])?$this->re_db_input($client_name_array[0]):'';
                            $middle_name = '';
                            $last_name = isset($client_name_array[1])?$this->re_db_input($client_name_array[1]):'';
                        }
                        
                    }
                    /*if(isset($check_data_val['mutual_fund_customer_account_number']))
                    {
                        $mutual_fund_customer_account_number = isset($check_data_val['mutual_fund_customer_account_number'])?$this->re_db_input($check_data_val['mutual_fund_customer_account_number']):'';
                        
                        if($mutual_fund_customer_account_number == '')
                        {
                            $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."',`client`='".$check_data_val['registration_line1']."',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='18',`field`='mutual_fund_customer_account_number',`file_type`='1'".$this->insert_common_sql();
         			        $res = $this->re_db_query($q);
                            $result = 1;
                        }
                    }*/
                    if(isset($result) && $result == 0)
                    {
                        $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."',`client`='".$check_data_val['registration_line1']."',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='0',`field`='',`solved`='1',`process_completed`='1',`file_type`='1'".$this->insert_common_sql();
     			        $res = $this->re_db_query($q);
                        
                        $get_client_data = $this->get_client_data($check_data_val['file_id'],$check_data_val['id']);
                        
                        $q = "INSERT INTO `".CLIENT_MASTER."` SET `file_id`='".$check_data_val['file_id']."',`first_name`='".$first_name."',`mi`='".$middle_name."',`last_name`='".$last_name."',`address1`='".$get_client_data[0]['client_address']."',`birth_date`='".$check_data_val['customer_date_of_birth']."',`zip_code`='".$check_data_val['zip_code']."',`broker_name`='".$broker_id."',`client_ssn`='".$check_data_val['social_security_number']."',`last_contacted`='".$check_data_val['last_maintenance_date']."'".$this->insert_common_sql();
     			        $res = $this->re_db_query($q);
                        $last_inserted_id = $this->re_db_insert_id();
                        if($res)
                        {
                            $q = "INSERT INTO `".CLIENT_ACCOUNT."` SET `client_id`='".$last_inserted_id."',`account_no`='".$check_data_val['mutual_fund_customer_account_number']."'".$this->insert_common_sql();
            				$res = $this->re_db_query($q);
                            $reprocess_status = true;
                        }
                    }
                 }
                 //Generate exception on security-file data
                 $check_sfr_array = $this->get_sfr_detail_data($id);
                 foreach($check_sfr_array as $check_data_key=>$check_data_val)
                 {
                    $result = 0;
                    if(isset($check_data_val['major_security_type']) && $check_data_val['major_security_type'] != '' && isset($check_data_val['product_name']) && $check_data_val['product_name'] != '')
                    {
                        if(isset($result) && $result == 0)
                        {
                            $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='',`rep_name`='',`account_no`='',`client`='',`cusip`='".$check_data_val['cusip_number']."',`principal`='0',`commission`='0',`error_code_id`='0',`field`='',`file_type`='3',`solved`='1',`process_completed`='1'".$this->insert_common_sql();
         			        $res = $this->re_db_query($q);
                            
                            $q = "SELECT * FROM `".PRODUCT_TYPE."` WHERE `is_delete`='0' and `status`='1' and `type_code`='".$check_data_val['major_security_type']."'";
            				$res = $this->re_db_query($q);
            				if($this->re_db_num_rows($res)>0){
                                while($row = $this->re_db_fetch_array($res))
                                {
                                    $q1 = "INSERT INTO `product_category_".$row['id']."` SET `category`='".$row['id']."',`name`='".$check_data_val['product_name']."',`ticker_symbol`='".$check_data_val['ticker_symbol']."',`cusip`='".$check_data_val['cusip_number']."',`fund_code`='".$check_data_val['fund_code']."'".$this->insert_common_sql();
                 			        $res1 = $this->re_db_query($q1);
                                    if($res1 == true)
                                    {
                                        $reprocess_status = true;
                                    }
                                }
                            }
                            
                        }
                    }
                    
                 }
                 //Generate exception on idc-file data
                 $sponsor_id = 0;
                 $check_idc_array = $this->get_idc_detail_data($id);
                 foreach($check_idc_array as $check_data_key=>$check_data_val)
                 {
                    //$batch_id = 0;
                    $broker_id = 0;
                    $client_id = 0;
                    $product_category_id = 0;
                    $product_id = 0;
                    $check_hold_commission = 0;
                    $result=0;
                    
                    if(isset($check_data_val['representative_number']))
                    {
                        $rep_number = isset($check_data_val['representative_number'])?$this->re_db_input($check_data_val['representative_number']):'';
                        
                        if($rep_number != '')
                        {
                            $q_broker = "SELECT * FROM `".BROKER_MASTER."` WHERE `is_delete`='0' AND `fund`='".$rep_number."'";
            				$res_broker = $this->re_db_query($q_broker);
            				$return = $this->re_db_num_rows($res_broker);
            				if($return <= 0)
                            {
            				    $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='1',`field`='representative_number',`file_type`='2'".$this->insert_common_sql();
            			        $res = $this->re_db_query($q);
                                $result = 1;
            				}
                            else
                            {
                                $return = $this->re_db_fetch_array($res_broker);
                                $broker_id = $return['id'];
                                $check_broker_termination = $this->broker_termination_date($rep_number);
                                $check_hold_commission = $this->get_hold_commission($check_data_val['file_id'],$check_data_val['id']);
                                if($check_broker_termination != '' && $check_hold_commission == '0')
                                {
                                    $current_date = date('Y-m-d');
                                    if($current_date>$check_broker_termination)
                                    {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='2',`field`='u5',`file_type`='2'".$this->insert_common_sql();
                    			        $res = $this->re_db_query($q);
                                        $result = 1;
                                    }
                                }
                            }
                        }
                        else
                        {
                            $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='13',`field`='representative_number',`file_type`='2'".$this->insert_common_sql();
        			        $res = $this->re_db_query($q);
                            $result = 1;
                        }
                    }
                    if(isset($check_data_val['CUSIP_number']) && trim($check_data_val['CUSIP_number']) == '')
                    {
                        $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='13',`field`='CUSIP_number',`file_type`='2'".$this->insert_common_sql();
    			        $res = $this->re_db_query($q);
                        $result = 1;
                    }
                    if(isset($check_data_val['CUSIP_number']) && trim($check_data_val['CUSIP_number']) != '')
                    {
                        $y=0;
                        $q = "SELECT * FROM `".PRODUCT_TYPE."` WHERE `is_delete`='0' and `status`='1'";
        				$res = $this->re_db_query($q);
        				if($this->re_db_num_rows($res)>0){
                            while($row = $this->re_db_fetch_array($res))
                            {
                                if($y==0)
                                {
                                    $q2 = "SELECT * FROM `product_category_".$row['id']."` WHERE `is_delete`='0' and `cusip`='".$check_data_val['CUSIP_number']."'";
                    				$res2 = $this->re_db_query($q2);
                    				if($this->re_db_num_rows($res2)>0)
                                    {
                                        while($row2 = $this->re_db_fetch_array($res2))
                                        {
                                            
                                            $product_id = $row2['id'];
                                            $product_category_id = $row2['category'];
                                            if($row2['status']==0)
                                            {
                                                $q3 = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='8',`field`='status',`file_type`='2'".$this->insert_common_sql();
                             			        $res3 = $this->re_db_query($q3);
                                                $result=1;
                                            }
                                            if($row2['objective'] > 0 && $check_data_val['customer_account_number'] != '')
                                            { 
                                                $q4 = "SELECT `cm`.*
                                                FROM `".CLIENT_MASTER."` as `cm`
                                                LEFT JOIN `".CLIENT_ACCOUNT."` as `ca` on `ca`.`client_id`=`cm`.`id`
                                                WHERE `cm`.`is_delete`='0' AND `ca`.`account_no`='".$check_data_val['customer_account_number']."'";
                                				$res4 = $this->re_db_query($q4);
                                				if($this->re_db_num_rows($res4)>0)
                                                {
                                                    while($row4 = $this->re_db_fetch_array($res4))
                                                    {
                                                        $q5 = "SELECT `co`.*
                                                        FROM `".CLIENT_OBJECTIVES."` as `co`
                                                        WHERE `co`.`is_delete`='0' AND `co`.`objectives`='".$row2['objective']."' AND `client_id`='".$row4['id']."'";
                                        				$res5 = $this->re_db_query($q5);
                                        				if($this->re_db_num_rows($res5)<=0)
                                                        {
                                                            $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='9',`field`='objectives',`file_type`='2'".$this->insert_common_sql();
                                        			        $res = $this->re_db_query($q);
                                                            $result = 1;
                                                        }
                                                    }
                                                }
                                            }
                                            /*if($row2['sponsor']==''||$row2['sponsor']<=0)
                                            {
                                                $q3 = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='14',`field`='sponsor',`file_type`='2'".$this->insert_common_sql();
                             			        $res3 = $this->re_db_query($q3);
                                                $result=1;
                                            }*/
                                        }
                                        $y++;
                                        break;  
                                    }
                                    else
                                    {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='11',`field`='CUSIP_number',`file_type`='2'".$this->insert_common_sql();
                    			        $res = $this->re_db_query($q);
                                        $result = 1;
                                    }
                                }
                            }
                        }
                    }
                    if(isset($check_data_val['system_id']) && $check_data_val['system_id'] != '')
                    {
                        $q = "SELECT * FROM `".SPONSOR_MASTER."` WHERE `is_delete`='0' and `dst_system_id`='".$check_data_val['system_id']."' and `dst_mgmt_code`='".$check_data_val['management_code']."'";
        				$res = $this->re_db_query($q);
        				if($this->re_db_num_rows($res)<=0){
        				    
        				    $q2 = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='14',`field`='sponsor',`file_type`='2'".$this->insert_common_sql();
         			        $res2 = $this->re_db_query($q2);
                            $result=1;
    				    }
                    }
                    if(isset($check_data_val['customer_account_number']))
                    {
                        $customer_account_number = isset($check_data_val['customer_account_number'])?$this->re_db_input($check_data_val['customer_account_number']):'';
                        
                        if($customer_account_number != '')
                        {
                            /*$q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`client`='".$check_data_val['alpha_code']."',`broker`='".$check_data_val['representative_name']."',`product`='',`amount`='',`error_code_id`='18',`field`='customer_account_number',`file_type`='2'".$this->insert_common_sql();
         			        $res = $this->re_db_query($q);
                            $result=1;
                        }
                        else
                        {*/
                            $q = "SELECT `cm`.*
                            FROM `".CLIENT_MASTER."` as `cm`
                            LEFT JOIN `".CLIENT_ACCOUNT."` as `ca` on `ca`.`client_id`=`cm`.`id`
                            WHERE `cm`.`is_delete`='0' AND `ca`.`account_no`='".$customer_account_number."' ";
            				$res = $this->re_db_query($q);
            				if($this->re_db_num_rows($res)>0){
                                while($row = $this->re_db_fetch_array($res)){
                                    
                                    $client_id = $row['id'];
                                    if(isset($check_data_val['representative_number']) && $check_data_val['representative_number'] != '' && isset($row['state']) && $row['state'] != '')
                                    {
                                        $q = "SELECT `bm`.* ,`bls`.`active_check` as active_check_security,`blr`.`active_check` as active_check_ria,`bli`.`active_check` as active_check_insurance
                                        FROM `".BROKER_MASTER."` as `bm`
                                        LEFT JOIN `".BROKER_LICENCES_SECURITIES."` as `bls` on `bls`.`broker_id`=`bm`.`id`
                                        LEFT JOIN `".BROKER_LICENCES_RIA."` as `blr` on `blr`.`broker_id`=`bm`.`id`
                                        LEFT JOIN `".BROKER_LICENCES_INSURANCE."` as `bli` on `bli`.`broker_id`=`bm`.`id`
                                        WHERE `bm`.`is_delete`='0' AND `bm`.`fund`='".$check_data_val['representative_number']."' AND `bls`.`state_id`='".$row['state']."' AND `blr`.`state_id`='".$row['state']."' AND `bli`.`state_id`='".$row['state']."' ";
                        			    $res = $this->re_db_query($q);
                        				if($this->re_db_num_rows($res)>0){
                                            while($row = $this->re_db_fetch_array($res))
                                            {
                                                if($row['active_check_security'] != 1 && $row['active_check_ria'] != 1 && $row['active_check_insurance'] != 1)
                                                {
                                                    $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='6',`field`='active_check',`file_type`='2'".$this->insert_common_sql();
                                			        $res = $this->re_db_query($q);
                                                    $result = 1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='18',`field`='customer_account_number',`file_type`='2'".$this->insert_common_sql();
             			        $res = $this->re_db_query($q);
                                $result=1;
                            }
                        }
                        else
                        {
                            $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='13',`field`='customer_account_number',`file_type`='2'".$this->insert_common_sql();
        			        $res = $this->re_db_query($q);
                            $result = 1;
                        } 
                    }
                    if(isset($check_data_val['social_security_number']))
                    {
                        $social_security_number = isset($check_data_val['social_security_number'])?$this->re_db_input($check_data_val['social_security_number']):'';
                        
                        if($social_security_number != '')
                        {
                            $q = "SELECT * FROM `".CLIENT_MASTER."` WHERE `is_delete`='0'  AND `active`='0'  AND `client_ssn`='".$social_security_number."' ";
            				$res = $this->re_db_query($q);
            				$return = $this->re_db_num_rows($res);
                            if($return > 0)
                            {
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='7',`field`='active',`file_type`='2'".$this->insert_common_sql();
            			        $res = $this->re_db_query($q);
                            }
                        }
                    }
                    if(isset($result) && $result == 0)
                    {
                        $total_check_amount = 0;
                        $q = "SELECT SUM(dealer_commission_amount) as total_check_amount FROM `".IMPORT_IDC_DETAIL_DATA."` WHERE `is_delete`='0' and `file_id`='".$check_data_val['file_id']."'";
        				$res = $this->re_db_query($q);
        				if($this->re_db_num_rows($res)>0)
                        {
        				    while($row = $this->re_db_fetch_array($res))
                            {
                                $total_check_amount = $row['total_check_amount'];
                            }
                        }
                        
                        $q = "SELECT *
                              FROM `".BATCH_MASTER."` 
                              WHERE `is_delete`='0' AND `file_id`='".$check_data_val['file_id']."' ";
        				$res = $this->re_db_query($q);
        				if($this->re_db_num_rows($res)<=0)
                        {
                            $sponsor_name = '';
                            $q = "SELECT * FROM `".SPONSOR_MASTER."` WHERE `is_delete`='0' and `dst_system_id`='".$check_data_val['system_id']."' and `dst_mgmt_code`='".$check_data_val['management_code']."'";
            				$res = $this->re_db_query($q);
            				if($this->re_db_num_rows($res)>0)
                            {
            				    while($row = $this->re_db_fetch_array($res))
                                {
                                    $sponsor_name = $row['name'];
                                    $sponsor_id = $row['id'];
                                }
                				if($sponsor_name != '')
                                {
                                    $batch_description = $sponsor_name.' '.date('m/d/Y');
                                    $q = "INSERT INTO `".BATCH_MASTER."` SET `file_id`='".$check_data_val['file_id']."',`pro_category`='".$product_category_id."',`batch_desc`='".$batch_description."',`sponsor`='".$sponsor_id."',`check_amount`='".$total_check_amount."',`batch_date`='".date('Y-m-d')."'".$this->insert_common_sql();
         			                $res = $this->re_db_query($q);
                                    $_SESSION['batch_id'] = $this->re_db_insert_id();
                                }    
                            }
                        }
                        else
                        {
                            while($row = $this->re_db_fetch_array($res)){
                                $_SESSION['batch_id'] = $row['id'];
                            }
                        }
                        if(isset($_SESSION['batch_id']) && $_SESSION['batch_id'] != '')
                        {
        				    $q = "INSERT INTO `".IMPORT_EXCEPTION."` SET `file_id`='".$check_data_val['file_id']."',`temp_data_id`='".$check_data_val['id']."',`date`='".date('Y-m-d')."',`rep`='".$check_data_val['representative_number']."',`rep_name`='".$check_data_val['representative_name']."',`account_no`='".$check_data_val['customer_account_number']."',`client`='".$check_data_val['alpha_code']."',`cusip`='".$check_data_val['CUSIP_number']."',`principal`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`commission`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`error_code_id`='0',`field`='',`file_type`='2',`solved`='1',`process_completed`='1'".$this->insert_common_sql();
         			        $res = $this->re_db_query($q);
                            $result = 0;
                            
                            $broker_class = new broker_master();
                            $check_broker_commission = $broker_class->check_broker_commission_status($broker_id);
                            $broker_hold_commission = $check_broker_commission['hold_commissions'];
                            
                            $con = '';
                            if($check_hold_commission==1)
                            {
                                $con .=",`hold_commission`='".$check_hold_commission."',`hold_resoan`='BROKER TERMINATED'";
                            }
                            else if($broker_hold_commission == 1)
                            {
                                $con .=",`hold_commission`='".$broker_hold_commission."',`hold_resoan`='HOLD COMMISSION BY BROKER'";
                            }
                            else
                            {
                                $con .=",`hold_commission`='2'";
                            }
                            $trans_ins = new transaction();
                            $get_branch_company_detail = $trans_ins->select_branch_company_ref($broker_id);
                            $branch = isset($get_branch_company_detail['branch_id'])?$get_branch_company_detail['branch_id']:'';
                            $company = isset($get_branch_company_detail['company_id'])?$get_branch_company_detail['company_id']:'';
                            
                            $q1 = "INSERT INTO `".TRANSACTION_MASTER."` SET `file_id`='".$check_data_val['file_id']."',`source`='DS',`trade_date`='".$check_data_val['trade_date']."',`posting_date`='".date('Y-m-d')."',`invest_amount`='".ltrim($check_data_val['gross_transaction_amount'],0)."',`gross_amount_sign_code`='".$check_data_val['gross_amount_sign_code']."',`dealer_commission_sign_code`='".$check_data_val['dealer_commission_sign_code']."',`commission_received`='".ltrim($check_data_val['dealer_commission_amount'],0)."',`product_cate`='".$product_category_id."',`product`='".$product_id."',`batch`='".$_SESSION['batch_id']."',`sponsor`='".$sponsor_id."',`broker_name`='".$broker_id."',`client_name`='".$client_id."',`client_number`='".$check_data_val['customer_account_number']."',`branch`='".$branch."',`company`='".$company."',`split`='1',`buy_sell`='1',`cancel`='2'".$con."".$this->insert_common_sql();
         			        $res1 = $this->re_db_query($q1);
                            $last_inserted_id = $this->re_db_insert_id();
                            
                            $get_client_split_rates = $trans_ins->get_client_split_rate($client_id);
                            
                            if(isset($get_client_split_rates[0]['split_broker']) && $get_client_split_rates[0]['split_broker'] != '')
                            {
                                $q = "INSERT INTO `".TRANSACTION_TRADE_SPLITS."` SET `transaction_id`='".$last_inserted_id."',`split_client_id`='".$client_id."',`split_broker_id`='0',`split_broker`='".$get_client_split_rates[0]['split_broker']."',`split_rate`='".$get_client_split_rates[0]['split_rate']."'".$this->insert_common_sql();
                				$res = $this->re_db_query($q);
                            }
                            $get_broker_split_rate = $trans_ins->get_broker_split_rate($broker_id);
                            if(isset($get_broker_split_rate[0]['rap']) && $get_broker_split_rate[0]['rap'] != '')
                            {
                                foreach($get_broker_split_rate as $keyedit_split=>$valedit_split){
                                    $q = "INSERT INTO `".TRANSACTION_TRADE_SPLITS."` SET `transaction_id`='".$last_inserted_id."',`split_client_id`='0',`split_broker_id`='".$broker_id."',`split_broker`='".$valedit_split['rap']."',`split_rate`='".$valedit_split['rate']."'".$this->insert_common_sql();
                    				$res = $this->re_db_query($q);
                                }
                            }
                            
                            if($res1 == true)
                            {
                                $reprocess_status = true;
                                unset($_SESSION['batch_id']);
                            }
                        }
                    }
                 }
             }
             $check_file_exception_process = $this->check_file_exception_process($id);
             if($reprocess_status == 1 && $check_file_exception_process == 0)
             {
                $q = "UPDATE `".IMPORT_CURRENT_FILES."` SET `process_completed`='1',`last_processed_date`='".date('Y-m-d')."'".$this->update_common_sql()." WHERE `id`='".$id."'";
		        $res = $this->re_db_query($q);
             }
             if($res)
             {
                $_SESSION['success'] = 'Data successfully processed.';
				return true;
			 }
			 else{
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			 }
          }
       }
       public function move_to_archived_files($file_id)
       {
            $q = "UPDATE `".IMPORT_CURRENT_FILES."` SET `is_archived`='1'".$this->update_common_sql()." WHERE `id`='".$file_id."'";
            $res = $this->re_db_query($q);
            if($res){
                $_SESSION['success'] = 'File moved successfully.';
				return true;
			}
			else{
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
       }
       public function select_current_files(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_CURRENT_FILES."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`is_archived`='0' and `at`.`user_id`='".$_SESSION['user_id']."'
                    ORDER BY `at`.`imported_date` DESC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function broker_termination_date($fund_clearing_number){
			$return = '';
			
			$q = "SELECT `bg`.`u5`
					FROM `".BROKER_MASTER."` AS `at`
                    LEFT JOIN `".BROKER_GENERAL."` AS `bg` ON `bg`.`broker_id`=`at`.`id`
                    WHERE `at`.`is_delete`='0' and `at`.`fund`='".$fund_clearing_number."'
                    ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['u5'];
                }
            }
			return $return;
		}
        public function check_u5_termination($broker_id){
			$return = '';
			
			$q = "SELECT `bg`.`u5`
					FROM `".BROKER_MASTER."` AS `at`
                    LEFT JOIN `".BROKER_GENERAL."` AS `bg` ON `bg`.`broker_id`=`at`.`id`
                    WHERE `at`.`is_delete`='0' and `at`.`id`='".$broker_id."'
                    ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['u5'];
                }
            }
			return $return;
		}
        public function select_archive_files(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_CURRENT_FILES."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`process_completed`='1' and `at`.`is_archived`='1' and `at`.`user_id`='".$_SESSION['user_id']."'
                    ORDER BY `at`.`imported_date` DESC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function select_current_file_id(){
			$return = array();
			
			$q = "SELECT `at`.`id`
					FROM `".IMPORT_CURRENT_FILES."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`user_id`='".$_SESSION['user_id']."'
                    ORDER BY `at`.`imported_date` DESC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function check_exception_data($file_id){
			$return = 0;
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_EXCEPTION."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`solved`='0' and `at`.`file_id`='".$file_id."'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $return = 4;
            }
			return $return;
		}
        public function get_exception_data($file_id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_EXCEPTION."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`solved`='0' and `at`.`file_id`='".$file_id."'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function check_processed_data($file_id){
			$return = 0;
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_EXCEPTION."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`solved`='1' and `at`.`file_id`='".$file_id."'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $return = 3;
            }
			return $return;
		}
        public function check_file_exception_process($file_id){
			$return = 0;
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_CURRENT_FILES."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`processed`='1' and `at`.`id`='".$file_id."'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $get_exceptions = $this->get_exception_data($file_id);
                if($get_exceptions != array())
                {
                    $return = 1;
                }
            }
			return $return;
		}
        public function get_fanmail_detail_data($id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_DETAIL_DATA."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`file_id`='".$id."'
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
        public function get_sfr_detail_data($id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_SFR_DETAIL_DATA."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`file_id`='".$id."'
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
        public function get_idc_detail_data($file_id){
			$return = array();
			
			$q = "SELECT `at`.*,`idch`.`system_id`,`idch`.`management_code`
					FROM `".IMPORT_IDC_DETAIL_DATA."` AS `at`
                    LEFT JOIN `".IMPORT_IDC_HEADER_DATA."` as `idch` on `idch`.`id`=`at`.`idc_header_id`   
                    WHERE `at`.`is_delete`='0' and `at`.`file_id`='".$file_id."'
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
        public function get_file_array($file_id)
        {
            $return = array();
            if($file_id>0)
            {
                $file_string_array = array();
                $get_file = $this->select_user_files($file_id);
                $file_name = $get_file['file_name'];
                $file_path = DIR_FS."import_files/user_".$_SESSION['user_id']."/".$file_name;
                $file = fopen($file_path, "r");
                while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
                 {
                    array_push($file_string_array,$getData[0]);
                 }
                 $data_array = array();
                 $array_key = 0;
                 $array_detail_key = 0;
                 $array_detail_check_key = 0;
                 $array_detail_key_sfr = 0;
                 $array_detail_check_key_sfr = 0;
                 $file_name_array = explode('.',$file_name);
                 $file_type_key = substr($file_name_array[0], -2);
                 $file_type_check = $file_type_key;
                 $dst_system_id = '';
                 $dst_management_code = '';
                 
                 if(isset($file_type_check) && ($file_type_check == '07' || $file_type_check == '08' || $file_type_check == '09'))
                 {
                     foreach($file_string_array as $key_string=>$val_string)
                     {
                        $record_type = substr($val_string, 0, 3);
                        if(isset($record_type) && $record_type == 'RHR')
                        {
                            $file_type = trim(substr($val_string, 6, 15));
                            if($file_type == 'SECURITY FILE')
                            {
                                $data_array[$array_key][$record_type] = array("record_type" => substr($val_string, 0, 3),"sequence_number" => substr($val_string, 3, 3),"file_type" => substr($val_string, 6, 15),"super_sheet_date" => substr($val_string, 21, 8),"processed_date" => substr($val_string, 29, 8),"processed_time" => substr($val_string, 37, 8),"job_name" => substr($val_string, 45, 8),"file_format_code" => substr($val_string, 53, 3),"request_number" => substr($val_string, 56, 7),"*" => substr($val_string, 63, 1),"system_id" => substr($val_string, 64, 3),"management_code" => substr($val_string, 67, 2),"**" => substr($val_string, 69, 1),"unused_mutual_fund" => substr($val_string, 70, 1),"life_date_type" => substr($val_string, 71, 1),"unused_header_RHR" => substr($val_string, 72, 88));
                            }
                            else
                            {
                                $dst_system_id = substr($val_string, 64, 3);
                                $dst_management_code = substr($val_string, 67, 2);
                                $data_array[$array_key][$record_type] = array("record_type" => substr($val_string, 0, 3),"sequence_number" => substr($val_string, 3, 3),"file_type" => substr($val_string, 6, 15),"super_sheet_date" => substr($val_string, 21, 8),"processed_date" => substr($val_string, 29, 8),"processed_time" => substr($val_string, 37, 8),"job_name" => substr($val_string, 45, 8),"file_format_code" => substr($val_string, 53, 3),"request_number" => substr($val_string, 56, 7),"*" => substr($val_string, 63, 1),"system_id" => substr($val_string, 64, 3),"management_code" => substr($val_string, 67, 2),"**" => substr($val_string, 69, 1),"populated_by_dst" => substr($val_string, 70, 1),"variable_universal_life" => substr($val_string, 71, 1),"unused_header_RHR" => substr($val_string, 72, 88));
                            }
                        
                        }
                        else if(isset($record_type) && $record_type == 'PLH')
                        {
                            $header_record_sequence = substr($val_string, 3, 3);
                            if(isset($header_record_sequence) && $header_record_sequence == 001)
                            {
                                $data_array[$array_key][$record_type][$header_record_sequence] = array("record_type1" => substr($val_string, 0, 3),"sequence_number1" => substr($val_string, 3, 3),"anniversary_date" => substr($val_string, 6, 8),"issue_date" => substr($val_string, 14, 8),"product_code" => substr($val_string, 22, 7),"policy_contract_number" => substr($val_string, 29, 20),"death_benefit_option" => substr($val_string, 49, 2),"current_policy_face_amount" => substr($val_string, 51, 12),"current_sum_of_riders" => substr($val_string, 63, 12),"current_face_amount_including_sum_of_riders" => substr($val_string, 75, 12),"name_of_primary_beneficiary" => substr($val_string, 87, 31),"multiple_primary_beneficiary(M)" => substr($val_string, 118, 1),"name_of_secondary_beneficiary" => substr($val_string, 119, 30),"multiple_secondary_beneficiary(M)" => substr($val_string, 149, 1),"policy_status" => substr($val_string, 150, 2),"unused_PLH_001" => substr($val_string, 152, 8));
                            }
                            else if(isset($header_record_sequence) && $header_record_sequence == 002)
                            {
                                $data_array[$array_key][$record_type][$header_record_sequence] = array("record_type2" => substr($val_string, 0, 3),"sequence_number2" => substr($val_string, 3, 3),"billing_type" => substr($val_string, 6, 1),"billing_frequency" => substr($val_string, 7, 1),"billing_amount" => substr($val_string, 8, 15),"guideline_annual_premium" => substr($val_string, 23, 15),"guideline_single_premium" => substr($val_string, 38, 15),"target_premium" => substr($val_string, 53, 15),"no_lapse_guarantee_premium" => substr($val_string, 68, 15),"seven_pay_premium" => substr($val_string, 83, 15),"MEC_indicator" => substr($val_string, 98, 1),"unused_PLH_002" => substr($val_string, 99, 61));
                                /*array_push($header_array['PLH'][$array_plh_key],$header_array_002);
                                $array_plh_key++;*/
                            }
                        }
                        else if(isset($record_type) && ($record_type == 'NAA' || $record_type == 'NFA' || $record_type == 'AMP' ))
                        {
                            $detail_record_type = substr($val_string, 3, 3);
                            if($detail_record_type == 001)
                            {
                                if($array_detail_check_key>0)
                                {
                                    $array_detail_key++;
                                }
                                $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("dst_system_id"=>$dst_system_id,"dst_management_code"=>$dst_management_code,"record_type1" => substr($val_string, 0, 3),"sequence_number1" => substr($val_string, 3, 3),"dealer_number" => substr($val_string, 6, 7),"dealer_branch_number" => substr($val_string, 13, 9),"cusip_number" => substr($val_string, 22, 9),"mutual_fund_fund_code" => substr($val_string, 31, 7),"mutual_fund_customer_account_number" => substr($val_string, 38, 20),"account_number_code" => substr($val_string, 58, 1),"mutual_fund_established_date" => substr($val_string, 59, 8),"last_maintenance_date" => substr($val_string, 67, 8),"line_code" => substr($val_string, 75, 1),"alpha_code" => substr($val_string, 76, 10),"mutual_fund_dealer_level_control_code" => substr($val_string, 86, 1),"social_code" => substr($val_string, 87, 3),"resident_state_country_code" => substr($val_string, 90, 3),"social_security_number" => substr($val_string, 93, 9),"ssn_status_code" => substr($val_string, 102, 1),"systematic_withdrawal_plan(SWP)_account" => substr($val_string, 103, 1),"pre_authorized_checking_amount" => substr($val_string, 104, 1),"automated_clearing_house_account(ACH)" => substr($val_string, 105, 1),"mutual_fund_reinvest_to_another_account" => substr($val_string, 106, 1),"mutual_fund_capital_gains_distribution_option" => substr($val_string, 107, 1),"mutual_fund_divident_distribution_option" => substr($val_string, 108, 1),"check_writing_account" => substr($val_string, 109, 1),"expedited_redemption_account" => substr($val_string, 110, 1),"mutual_fund_sub_account" => substr($val_string, 111, 1),"foreign_tax_rate" => substr($val_string, 112, 3),"zip_code" => substr($val_string, 115, 9),"zipcode_future_expansion" => substr($val_string, 124, 2),"cumulative_discount_number" => substr($val_string, 126, 9),"letter_of_intent(LOI)_number" => substr($val_string, 135, 9),"timer_flag" => substr($val_string, 144, 1),"list_bill_account" => substr($val_string, 145, 1),"mutual_fund_monitored_VIP_account" => substr($val_string, 146, 1),"mutual_fund_expedited_exchange_account" => substr($val_string, 147, 1),"mutual_fund_penalty_withholding_account" => substr($val_string, 148, 1),"certificate_issuance_code" => substr($val_string, 149, 1),"mutual_fund_stop_transfer_flag" => substr($val_string, 150, 1),"mutual_fund_blue_sky_exemption_flag" => substr($val_string, 151, 1),"bank_card_issued" => substr($val_string, 152, 1),"fiduciary_account" => substr($val_string, 153, 1),"plan_status_code" => substr($val_string, 154, 1),"mutual_fund_net_asset_value(NAV)_account" => substr($val_string, 155, 1),"mailing_flag" => substr($val_string, 156, 1),"interested_party_code" => substr($val_string, 157, 1),"mutual_fund_share_account_phone_check_redemption_code" => substr($val_string, 158, 1),"mutual_fund_share_account_house_account_code" => substr($val_string, 159, 1));
                                $array_detail_check_key++;
                            }
                            else if($detail_record_type == 002)
                            {
                                $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("record_type2" => substr($val_string, 0, 3),"sequence_number2" => substr($val_string, 3, 3),"mutual_fund_dividend_mail_account" => substr($val_string, 6, 1),"mutual_fund_stop_purchase_account" => substr($val_string, 7, 1),"stop_mail_account" => substr($val_string, 8, 1),"mutual_fund_fractional_check" => substr($val_string, 9, 1),"registration_line1" => substr($val_string, 10, 35),"registration_line2" => substr($val_string, 45, 35),"registration_line3" => substr($val_string, 80, 35),"registration_line4" => substr($val_string, 115, 35),"customer_date_of_birth" => substr($val_string, 150, 8),"mutual_fund_account_price_schedule_code" => substr($val_string, 158, 1),"unused_detail" => substr($val_string, 159, 1));
                            }
                            else if($detail_record_type == 003)
                            {
                                $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("record_type3" => substr($val_string, 0, 3),"sequence_number3" => substr($val_string, 3, 3),"account_registration_line5" => substr($val_string, 6, 35),"account_registration_line6" => substr($val_string, 41, 35),"account_registration_line7" => substr($val_string, 76, 35),"representative_number" => substr($val_string, 111, 9),"representative_name" => substr($val_string, 120, 30),"position_close_out_indicator" => substr($val_string, 150, 1),"account_type_indicator" => substr($val_string, 151, 4),"product_identifier_code(VA only)" => substr($val_string, 155, 3),"mutual_fund_alternative_investment_program_managers_variable_annuties_and_VUL" => substr($val_string, 158, 2));
                                
                            }
                            else if($detail_record_type == 004)
                            {
                                $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("record_type4" => substr($val_string, 0, 3),"sequence_number4" => substr($val_string, 3, 3),"brokerage_identification_number(BIN)" => substr($val_string, 6, 20),"account_number_code_004" => substr($val_string, 26, 1),"primary_investor_phone_number" => substr($val_string, 27, 15),"secondary_investor_phone_number" => substr($val_string, 42, 15),"NSCC_trust_company_number" => substr($val_string, 57, 4),"NSCC_third_party_administrator_number" => substr($val_string, 61, 4),"unused_004_1" => substr($val_string, 65, 23),"trust_custodian_id_number" => substr($val_string, 88, 7),"third_party_administrator_id_number" => substr($val_string, 95, 7),"unused_004_2" => substr($val_string, 102, 58));
                            
                            }
                            
                        }
                        else if(isset($record_type) && $record_type == 'SFR')
                        {
                            $detail_record_type = substr($val_string, 3, 3);
                            if($detail_record_type == 001)
                            {
                                if($array_detail_check_key_sfr>0)
                                {
                                    $array_detail_key_sfr++;
                                }
                                $data_array[$array_key]['DETAIL'][$array_detail_key_sfr][$detail_record_type] = array("record_type1" => substr($val_string, 0, 3),"sequence_number1" => substr($val_string, 3, 3),"cusip_number" => substr($val_string, 6, 9),"fund_code" => substr($val_string, 15, 7),"fund_name" => substr($val_string, 22, 40),"product_name" => substr($val_string, 62, 38),"ticker_symbol" => substr($val_string, 100, 8),"major_security_type" => substr($val_string, 108, 2),"unused" => substr($val_string, 110, 50));
                                $array_detail_check_key_sfr++;
                            }
                        }
                        else if(isset($record_type) && $record_type == 'RTR')
                        {
                            $data_array[$array_key][$record_type] = array("record_type" => substr($val_string, 0, 3),"sequence_number" => substr($val_string, 3, 3),"file_type" => substr($val_string, 6, 15),"trailer_record_count" => substr($val_string, 21, 9),"unused" => substr($val_string, 30, 130));
                            $array_key++;
                        }
                     }
                     foreach($data_array as $main_key=>$main_val)
                     {
                        foreach($main_val['DETAIL'] as $sub_main_key=>$sub_main_val)
                        {
                            $array_type_merge = array();
                            foreach($sub_main_val as $sub_key=>$sub_val)
                            {
                                foreach($sub_val as $data_key=>$data_val)
                                {
                                    $array_type_merge[$data_key]=$data_val;
                                }
                            }
                            array_push($return,$array_type_merge); 
                            
                        }
                     }
                }
                if(isset($file_type_check) && $file_type_check == 'C1')
                {
                     foreach($file_string_array as $key_string=>$val_string)
                     {
                        $record_type = substr($val_string, 0, 3);
                        if(isset($record_type) && $record_type == 'RHR')
                        {
                            $dst_system_id = substr($val_string, 13, 3);
                            $dst_management_code = substr($val_string, 16, 2);
                            $data_array[$array_key][$record_type] = array("record_type" => substr($val_string, 0, 3),"file_type" => substr($val_string, 3, 10),"system_id" => substr($val_string, 13, 3),"management_code" => substr($val_string, 16, 2),"fund_sponsor_id" => substr($val_string, 18, 5),"transmission_date" => substr($val_string, 23, 8),"unused_RHR" => substr($val_string, 31, 169));
                        }
                        else if(isset($record_type) && ($record_type != 'RHR' && $record_type != 'RTR'))
                        {
                            $commission_record_type_code = substr($val_string, 0, 1);
                            if($commission_record_type_code == '1' || $commission_record_type_code == '3')
                            {
                                $data_array[$array_key]['DETAIL'][$commission_record_type_code][] = array("dst_system_id"=>$dst_system_id,"dst_management_code"=>$dst_management_code,"commission_record_type_code" => substr($val_string, 0, 1),"dealer_number" => substr($val_string, 1, 7),"dealer_branch_number" => substr($val_string, 8, 9),"representative_number" => substr($val_string, 17, 9),"representative_name" => substr($val_string, 26, 30),"CUSIP_number" => substr($val_string, 56, 9),"alpha_code" => substr($val_string, 65, 10),"trade_date" => substr($val_string, 75, 8),"gross_transaction_amount" => substr($val_string, 83, 15),"gross_amount_sign_code" => substr($val_string, 98, 1),"dealer_commission_amount" => substr($val_string, 99, 15),"commission_rate" => substr($val_string, 114, 5),"customer_account_number" => substr($val_string, 119, 20),"account_number_type_code" => substr($val_string, 139, 1),"purchase_type_code" => substr($val_string, 140, 1),"social_code" => substr($val_string, 141, 3),"cumulative_discount_number" => substr($val_string, 144, 9),"letter_of_intent(LOI)_number" => substr($val_string, 153, 9),"social_security_number" => substr($val_string, 162, 9),"social_security_number_status_code" => substr($val_string, 171, 1),"transaction_share_count" => substr($val_string, 172, 15),"share_price_amount" => substr($val_string, 187, 9),"resident_state_country_code" => substr($val_string, 196, 3),"dealer_commission_sign_code" => substr($val_string, 199, 1));
                            }
                        }
                        else if(isset($record_type) && $record_type == 'RTR')
                        {
                            $data_array[$array_key][$record_type] = array("record_type" => substr($val_string, 0, 3),"file_type" => substr($val_string, 3, 10),"trailer_record_count" => substr($val_string, 13, 7),"unused_RTR" => substr($val_string, 20, 180));
                            $array_key++;
                        }
                        
                     }
                     foreach($data_array as $main_key=>$main_val)
                     {
                        foreach($main_val['DETAIL'] as $sub_main_key=>$sub_main_val)
                        {
                            foreach($sub_main_val as $sub_key=>$sub_val)
                            {
                                array_push($return,$sub_val);
                            } 
                        }
                     }
                }
            }
            return $return;
        }
        public function get_idc_record_details($file_id,$data_id){
			$return = array();
			
			$q = "SELECT `dtl`.*,`hdr`.*,`ftr`.*
					FROM `".IMPORT_IDC_DETAIL_DATA."` AS `dtl`
                    LEFT JOIN `".IMPORT_IDC_HEADER_DATA."` AS `hdr` ON `hdr`.`id`=`dtl`.`idc_header_id`
                    LEFT JOIN `".IMPORT_IDC_FOOTER_DATA."` AS `ftr` ON `ftr`.`idc_header_id`=`hdr`.`id`
                    WHERE `dtl`.`is_delete`='0' and `dtl`.`file_id`='".$file_id."' and `dtl`.`id`='".$data_id."'
                    ORDER BY `dtl`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;
                     
    			}
            }
			return $return;
		}
        public function get_hold_commission($file_id,$data_id){
			$return = array();
			
			$q = "SELECT `dtl`.*
					FROM `".IMPORT_IDC_DETAIL_DATA."` AS `dtl`
                    WHERE `dtl`.`is_delete`='0' and `dtl`.`file_id`='".$file_id."' and `dtl`.`id`='".$data_id." and `on_hold`=0'
                    ORDER BY `dtl`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['on_hold'];
                     
    			}
            }
			return $return;
		}
        public function get_objectives_data(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".OBJECTIVE_MASTER."` AS `at`
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
        public function select_processed_data($file_id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_EXCEPTION."` AS `at`
                    LEFT JOIN `".IMPORT_CURRENT_FILES."` AS `cf` on `at`.`file_id` = `cf`.`id`
                    WHERE `at`.`is_delete`='0' and `at`.`solved`='1' and `at`.`process_completed`='1' and `at`.`file_id`='".$file_id."' and `cf`.`user_id`='".$_SESSION['user_id']."'
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
        public function select_exception_data($file_id){
			$return = array();
			
			$q = "SELECT `at`.*,`em`.`error`
					FROM `".IMPORT_EXCEPTION."` AS `at`
                    LEFT JOIN `".IMPORT_EXCEPTION_MASTER."` AS `em` on `at`.`error_code_id` = `em`.`id`
                    LEFT JOIN `".IMPORT_CURRENT_FILES."` AS `cf` on `at`.`file_id` = `cf`.`id`
                    WHERE `at`.`is_delete`='0' and `at`.`solved`='0' and `at`.`file_id`='".$file_id."' and `cf`.`user_id`='".$_SESSION['user_id']."'
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
        public function select_single_exception_data($file_id,$exception_id){
			$return = array();
			
			$q = "SELECT `at`.*,`em`.`error`
					FROM `".IMPORT_EXCEPTION."` AS `at`
                    LEFT JOIN `".IMPORT_EXCEPTION_MASTER."` AS `em` on `at`.`error_code_id` = `em`.`id`
                    LEFT JOIN `".IMPORT_CURRENT_FILES."` AS `cf` on `at`.`file_id` = `cf`.`id`
                    WHERE `at`.`is_delete`='0' and `at`.`solved`='0' and `at`.`file_id`='".$file_id."' and `at`.`temp_data_id`='".$exception_id."' and `cf`.`user_id`='".$_SESSION['user_id']."'
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
        public function select_existing_sfr_data($temp_data_id){
			$return = array();
			
			$q = "SELECT `sfr`.*
					FROM `".IMPORT_SFR_DETAIL_DATA."` AS `sfr`
                    WHERE `sfr`.`is_delete`='0' and `sfr`.`id`='".$temp_data_id."'
                    ORDER BY `sfr`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;
                     
    			}
            }
			return $return;
		}
        public function select_existing_idc_data($temp_data_id){
			$return = array();
			
			$q = "SELECT `idc`.*
					FROM `".IMPORT_IDC_DETAIL_DATA."` AS `idc`
                    WHERE `idc`.`is_delete`='0' and `idc`.`id`='".$temp_data_id."'
                    ORDER BY `idc`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;
                     
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
        public function select_existing_fanmail_data($temp_data_id){
			$return = array();
			
			$q = "SELECT `fan`.*
					FROM `".IMPORT_DETAIL_DATA."` AS `fan`
                    WHERE `fan`.`is_delete`='0' and `fan`.`id`='".$temp_data_id."'
                    ORDER BY `fan`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;
                     
    			}
            }
			return $return;
		}
        public function select_solved_exception_data($file_id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_EXCEPTION."` AS `at`
                    LEFT JOIN `".IMPORT_CURRENT_FILES."` AS `cf` on `at`.`file_id` = `cf`.`id`
                    WHERE `at`.`is_delete`='0' and `at`.`solved`='1' and `at`.`file_id`='".$file_id."' and `cf`.`user_id`='".$_SESSION['user_id']."'
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
        public function get_total_commission_amount($file_id){
			$return = array();
			
			$q = "SELECT SUM(`at`.`commission`) as total_commission_amount
					FROM `".IMPORT_EXCEPTION."` AS `at`
                    LEFT JOIN `".IMPORT_CURRENT_FILES."` AS `cf` on `at`.`file_id` = `cf`.`id`
                    WHERE `at`.`is_delete`='0' and `at`.`solved`='1' and `at`.`file_id`='".$file_id."' and `cf`.`user_id`='".$_SESSION['user_id']."'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['total_commission_amount'];
                     
    			}
            }
			return $return;
		}
        public function select_user_files($id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".IMPORT_CURRENT_FILES."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`user_id`='".$_SESSION['user_id']."' and `at`.`id`='".$id."'
                    ORDER BY `at`.`imported_date` DESC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;
                     
    			}
            }
			return $return;
		}
        public function check_current_files($user_id){
			$return = array();
			
			$q = "SELECT `at`.`file_name`
					FROM `".IMPORT_CURRENT_FILES."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`user_id`='".$user_id."'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return[] = $row['file_name'];
                }
            }
			return $return;
		}
        public function check_file_type($file_id){
			$return = array();
			
			$q = "SELECT `at`.`source`
					FROM `".IMPORT_CURRENT_FILES."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`id`='".$file_id."'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['source'];
                }
            }
			return $return;
		}
        public function get_files_header_detail($file_id='',$record_id='',$file_type=''){
			$return = array();
			
			if($file_type==1)
            {
                $q = "SELECT `fd`.`header_id`,`fh`.*
					FROM `".IMPORT_DETAIL_DATA."` AS `fd`
                    LEFT JOIN `".IMPORT_HEADER1_DATA."` AS `fh` on `fh`.`id`=`fd`.`header_id`
                    WHERE `fd`.`is_delete`='0' and `fd`.`id`='".$record_id."' and `fd`.`file_id`='".$file_id."'
                    ORDER BY `fd`.`id` ASC";
			    $res = $this->re_db_query($q);
            }
            else
            {
                $q = "SELECT `idcd`.`idc_header_id`,`ih`.*
					FROM `".IMPORT_IDC_DETAIL_DATA."` AS `idcd`
                    LEFT JOIN `".IMPORT_IDC_HEADER_DATA."` AS `ih` on `ih`.`id`=`idcd`.`idc_header_id`
                    WHERE `idcd`.`is_delete`='0' and `idcd`.`id`='".$record_id."' and `idcd`.`file_id`='".$file_id."'
                    ORDER BY `idcd`.`id` ASC";
			    $res = $this->re_db_query($q);
            }
            
            if(isset($res) && $this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;
                }
            }
			return $return;
		}
        public function get_file_batch($file_id){
			$return = '';
			
			$q = "SELECT `at`.`id` as batch_id
					FROM `".BATCH_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`file_id`='".$file_id."'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['batch_id'];
                }
            }
			return $return;
		}
        public function get_file_type($file_id){
			$return = '';
			
			$q = "SELECT `at`.`file_type`
					FROM `".IMPORT_EXCEPTION."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`file_id`='".$file_id."'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['file_type'];
                }
            }
			return $return;
		}
        public function get_current_file_type($file_id){
			$return = '';
			
			$q = "SELECT `at`.`source`
					FROM `".IMPORT_CURRENT_FILES."` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`id`='".$file_id."'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['source'];
                }
            }
			return $return;
		}
        public function get_client_data($file_id,$temp_data_id=''){
			$return = array();
			$con = '';
            if($temp_data_id != '')
            {
                $con .=" and id='".$temp_data_id."'";
            }
            
			$q = "SELECT *, 
                  (CASE 
                    WHEN line_code = '1' THEN CONCAT(registration_line1, ' ', registration_line2, ' ',registration_line3 , ' ',registration_line4) 
                    WHEN line_code = '2' THEN CONCAT(registration_line2, ' ',registration_line3 , ' ',registration_line4) 
                    WHEN line_code = '3' THEN CONCAT(registration_line3 , ' ',registration_line4) 
                    WHEN line_code = '4' THEN registration_line4 
                    ELSE '' 
                  END) AS client_address 
                  FROM `".IMPORT_DETAIL_DATA."` 
                  WHERE file_id='".$file_id."' AND status=1 AND is_delete='0' ".$con."
                  ORDER BY id ASC";
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
        public function get_product($id){
			$return = array();
            
            $q = "SELECT `at`.*
					FROM `product_category_".$id."` AS `at`
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
        public function delete_current_files($id){
			$id = trim($this->re_db_input($id));
			if($id>0){
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