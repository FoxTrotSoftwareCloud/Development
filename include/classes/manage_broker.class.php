<?php
	class broker_master extends db{
		
		public $table = BROKER_MASTER;
		public $errors = '';
        public $last_inserted_id = '';
        /**
		 * @param post array
		 * @return true if success, error message if any errors
		 * */
		public function insert_update($data){
		  
            //echo '<pre>';print_r($_POST);exit;
            $_SESSION['last_insert_id'] = 0;
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
			$fname = isset($data['fname'])?$this->re_db_input($data['fname']):'';
			$lname = isset($data['lname'])?$this->re_db_input($data['lname']):'';
			$mname = isset($data['mname'])?$this->re_db_input($data['mname']):'';
			$suffix = isset($data['suffix'])?$this->re_db_input($data['suffix']):'';
			$fund = isset($data['fund'])?$this->re_db_input($data['fund']):'';
			$internal = isset($data['internal'])?$this->re_db_input($data['internal']):'';
            $display_on_statement = isset($data['display_on_statement'])?$this->re_db_input($data['display_on_statement']):'';
			$ssn_mask = isset($data['ssn'])?$this->re_db_input($data['ssn']):'';
            $ssn = str_replace("-", '', $ssn_mask);
            $tax_id_mask = isset($data['tax_id'])?$this->re_db_input($data['tax_id']):'';
            $tax_id = str_replace("-", '', $tax_id_mask);
			$crd = isset($data['crd'])?$this->re_db_input($data['crd']):'';
            $active_status_cdd = isset($data['active_status_cdd'])?$this->re_db_input($data['active_status_cdd']):'';
			$pay_method = isset($data['pay_method'])?$this->re_db_input($data['pay_method']):'';
			/*$branch_manager = isset($data['branch_manager'])?$this->re_db_input($data['branch_manager']):'';
            $branch_name = isset($data['branch_name'])?$this->re_db_input($data['branch_name']):'';
            $branch_office = isset($data['branch_office'])?$this->re_db_input($data['branch_office']):'';*/
			$for_import = isset($data['for_import'])?$this->re_db_input($data['for_import']):'false';
            $file_id = isset($data['file_id'])?$this->re_db_input($data['file_id']):'';
            $temp_data_id = isset($data['temp_data_id'])?$this->re_db_input($data['temp_data_id']):'';
			
			/*if($fname==''){
				$this->errors = 'Please enter first name.';
			}
            else*/ if($lname==''){
				$this->errors = 'Please enter last name.';
			}
            /*else if($mname==''){
				$this->errors = 'Please enter middle name.';
			}
			else if($suffix==''){
				$this->errors = 'Please enter suffix.';
			}
			else if($fund==''){//echo $fund;exit;
				$this->errors = 'Please enter fund.';
			}
            else if($internal==''){
				$this->errors = 'Please enter internal.';
			}
			else if($ssn==''){
				$this->errors = 'Please enter ssn.';
			}
			else if($tax_id==''){
				$this->errors = 'Please enter tax.';
			}
            else if($crd==''){
				$this->errors = 'Please select crd.';
			}
            else if($active_status_cdd==''){
                $this->errors = 'Please select active status.';
            }
			else if($pay_method==''){
                $this->errors = 'Please select pay type.';
            }*/
            
			if($this->errors!=''){
				return $this->errors;
			}
			else{
				
				/* check duplicate record */
				$con = '';
				if($id>0){
					$con = " AND `id`!='".$id."'";
				}
				$q = "SELECT * FROM `".$this->table."` WHERE `is_delete`='0' AND `first_name`='".$fname."' ".$con;
				$res = $this->re_db_query($q);
				$return = $this->re_db_num_rows($res);
				if($return>0){
					$this->errors = 'This broker is already exists.';
				}
				
				if($this->errors!=''){
					return $this->errors;
				}
				else 
                {
					if($id==0){
						$q = "INSERT INTO `".$this->table."` SET `first_name`='".$fname."',`last_name`='".$lname."',`middle_name`='".$mname."',`suffix`='".$suffix."',`fund`='".$fund."',`internal`='".$internal."',`display_on_statement`='".$display_on_statement."',`tax_id`='".$tax_id."',`crd`='".$crd."',`ssn`='".$ssn."',`active_status`='".$active_status_cdd."',`pay_method`='".$pay_method."'".$this->insert_common_sql();
						$res = $this->re_db_query($q);
                        $_SESSION['last_insert_id'] = $this->re_db_insert_id();
                        if($res){
                            
                            if($for_import == 'true')
                            {
                                $q1 = "UPDATE `".IMPORT_EXCEPTION."` SET `solved`='1' WHERE `file_id`='".$file_id."' and `temp_data_id`='".$temp_data_id."'";
                                $res1 = $this->re_db_query($q1);
                                
                                $instance_import = new import();
                                $get_file_type = $instance_import->check_file_type($file_id);
                                if($get_file_type == 'DSTFANMail')
                                {
                                    $q1 = "UPDATE `".IMPORT_DETAIL_DATA."` SET `representative_number`='".$fund."' WHERE `file_id`='".$file_id."' and `id`='".$temp_data_id."'";
                                    $res1 = $this->re_db_query($q1);
                                }
                                else
                                {
                                    $q1 = "UPDATE `".IMPORT_IDC_DETAIL_DATA."` SET `representative_number`='".$fund."' WHERE `file_id`='".$file_id."' and `id`='".$temp_data_id."'";
                                    $res1 = $this->re_db_query($q1);
                                }
                            }
						      
                            $_SESSION['success'] = INSERT_MESSAGE;
							return true;
						}
						else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}
					}
					else if($id>0){
					    $q = "UPDATE `".$this->table."` SET `first_name`='".$fname."',`last_name`='".$lname."',`middle_name`='".$mname."',`suffix`='".$suffix."',`fund`='".$fund."',`internal`='".$internal."',`display_on_statement`='".$display_on_statement."',`tax_id`='".$tax_id."',`crd`='".$crd."',`ssn`='".$ssn."',`active_status`='".$active_status_cdd."',`pay_method`='".$pay_method."'".$this->update_common_sql()." WHERE `id`='".$id."'";
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
        /** Insert update general data for broker. **/
        public function insert_update_general($data){
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
			$home_general = isset($data['home_general'])?$this->re_db_input($data['home_general']):'';
			$home_address1_general = isset($data['home_address1_general'])?$this->re_db_input($data['home_address1_general']):'';
			$home_address2_general = isset($data['home_address2_general'])?$this->re_db_input($data['home_address2_general']):'';
            $business_address1_general = isset($data['business_address1_general'])?$this->re_db_input($data['business_address1_general']):'';
			$business_address2_general = isset($data['business_address2_general'])?$this->re_db_input($data['business_address2_general']):'';
			$city_general = isset($data['city_general'])?$this->re_db_input($data['city_general']):'';
			$state_general = isset($data['state_general'])?$this->re_db_input($data['state_general']):'';
			$zip_code_general = isset($data['zip_code_general'])?$this->re_db_input($data['zip_code_general']):'';
            $telephone_mask = isset($data['telephone_general'])?$this->re_db_input($data['telephone_general']):'';
            $telephone_no = str_replace("-", '', $telephone_mask);
            $telephone_brack1 = str_replace("(", '', $telephone_no);
            $telephone_general = str_replace(")", '', $telephone_brack1);
            $cell_mask = isset($data['cell_general'])?$this->re_db_input($data['cell_general']):'';
            $cell_no = str_replace("-", '', $cell_mask);
            $cell_brack1 = str_replace("(", '', $cell_no);
            $cell_general = str_replace(")", '', $cell_brack1);
            $fax_mask = isset($data['fax_general'])?$this->re_db_input($data['fax_general']):'';
            $fax_no = str_replace("-", '', $fax_mask);
            $fax_brack1 = str_replace("(", '', $fax_no);
            $fax_general = str_replace(")", '', $fax_brack1);
			$gender_general = isset($data['gender_general'])?$this->re_db_input($data['gender_general']):'';
			$status_general = isset($data['status_general'])?$this->re_db_input($data['status_general']):'';
			$spouse_general = isset($data['spouse_general'])?$this->re_db_input($data['spouse_general']):'';
            $children_general = isset($data['children_general'])?$this->re_db_input($data['children_general']):'';
			$email1_general = isset($data['email1_general'])?$this->re_db_input($data['email1_general']):'';
			$email2_general = isset($data['email2_general'])?$this->re_db_input($data['email2_general']):'';
			$web_id_general = isset($data['web_id_general'])?$this->re_db_input($data['web_id_general']):'';
			$web_password_general = isset($data['web_password_general'])?$this->re_db_input($data['web_password_general']):'';
			$dob_general = isset($data['dob_general'])?$this->re_db_input(date('Y-m-d',strtotime($data['dob_general']))):'';
			$prospect_date_general = isset($data['prospect_date_general'])?$this->re_db_input(date('Y-m-d',strtotime($data['prospect_date_general']))):'';
			$reassign_broker_general = isset($data['reassign_broker_general'])?$this->re_db_input($data['reassign_broker_general']):'';
			$u4_general = isset($data['u4_general'])?$this->re_db_input(date('Y-m-d',strtotime($data['u4_general']))):'';
            if($data['u5_general'] != '')
            {
                $u5_general = isset($data['u5_general'])?$this->re_db_input(date('Y-m-d',strtotime($data['u5_general']))):'';
            }
            else
            {
                $u5_general = '';
            }
            $day_after_u5 = isset($data['day_after_u5'])?$this->re_db_input($data['day_after_u5']):'';
			$dba_name_general = isset($data['dba_name_general'])?$this->re_db_input($data['dba_name_general']):'';
			$eft_info_general = isset($data['eft_info_general'])?$this->re_db_input($data['eft_info_general']):'';
            $start_date_general = isset($data['start_date_general'])?$this->re_db_input(date('Y-m-d',strtotime($data['start_date_general']))):'';
			$transaction_type_general = isset($data['transaction_type_general1'])?$this->re_db_input($data['transaction_type_general1']):'';
			$routing_general = isset($data['routing_general'])?$this->re_db_input($data['routing_general']):'';
			$account_no_general = isset($data['account_no_general'])?$this->re_db_input($data['account_no_general']):'';
			$summarize_trailers_general = isset($data['summarize_trailers_general'])?$this->re_db_input($data['summarize_trailers_general']):0;
			$summarize_direct_imported_trades = isset($data['summarize_direct_imported_trades'])?$this->re_db_input($data['summarize_direct_imported_trades']):0;
			$from_date_general = isset($data['from_date_general'])?$this->re_db_input(date('Y-m-d',strtotime($data['from_date_general']))):'';
			$to_date_general = isset($data['to_date_general'])?$this->re_db_input(date('Y-m-d',strtotime($data['to_date_general']))):'';
			$cfp_general = isset($data['cfp_general'])?$this->re_db_input($data['cfp_general']):0;
            $chfp_general = isset($data['chfp_general'])?$this->re_db_input($data['chfp_general']):0;
			$cpa_general = isset($data['cpa_general'])?$this->re_db_input($data['cpa_general']):0;
			$clu_general = isset($data['clu_general'])?$this->re_db_input($data['clu_general']):0;
            $cfa_general = isset($data['cfa_general'])?$this->re_db_input($data['cfa_general']):0;
            $ria_general = isset($data['ria_general'])?$this->re_db_input($data['ria_general']):0;
			$insurance_general = isset($data['insurance_general'])?$this->re_db_input($data['insurance_general']):0;
			
			/*if($home_general==''){
				$this->errors = 'Please select one option.';
			}
            else if($address1_general==''){
				$this->errors = 'Please enter address.';
			}
            else if($city_general==''){
				$this->errors = 'Please enter city.';
			}
			else if($state_general==''){
				$this->errors = 'Please select state.';
			}
			else if($zip_code_general==''){//echo $fund;exit;
				$this->errors = 'Please enter zip-code.';
			}
            else if($telephone_general==''){
				$this->errors = 'Please enter telephone.';
			}
			else if($cell_general==''){
				$this->errors = 'Please enter cell.';
			}
			else if($fax_general==''){
				$this->errors = 'Please enter fax.';
			}
            else if($gender_general==''){
				$this->errors = 'Please select gender.';
			}
            else if($status_general==''){
                $this->errors = 'Please select status.';
            }
			else if($spouse_general==''){
                $this->errors = 'Please enter spouse.';
            }
            else if($children_general==''){
                $this->errors = 'Please select children.';
            }
            else if($email1_general==''){
                $this->errors = 'Please enter email.';
            }
            else if($web_id_general==''){
                $this->errors = 'Please enter web id.';
            }
            else if($web_password_general==''){
                $this->errors = 'Please enter web password.';
            }
            else if($dob_general==''){
                $this->errors = 'Please enter birth date.';
            }
            else if($prospect_date_general==''){
                $this->errors = 'Please enter prospect date.';
            }
            else if($reassign_broker_general==''){
                $this->errors = 'Please select reassign broker.';
            }
            else if($u4_general==''){
                $this->errors = 'Please enter u4.';
            }
            else if($u5_general==''){
                $this->errors = 'Please enter u5.';
            }
            else if($dba_name_general==''){
                $this->errors = 'Please enter DBA name.';
            }
            
            else if($eft_info_general==''){
                $this->errors = 'Please select one EFT option.';
            }
            else if($start_date_general==''){
                $this->errors = 'Please select start date.';
            }
            else if($transaction_type_general==''){
                $this->errors = 'Please select transaction type.';
            }
            else if($routing_general==''){
                $this->errors = 'Please enter routing.';
            }
            else if($account_no_general==''){
                $this->errors = 'Please enter account no.';
            }
            else if($from_date_general==''){
                $this->errors = 'Please enter from date.';
            }
            else if($to_date_general==''){
                $this->errors = 'Please enter to date.';
            }*/
            /*if($email1_general!='' && $this->validemail($email1_general)==0){
				$this->errors = 'Please enter valid email 1.';
			}
            else if($email2_general!='' && $this->validemail($email2_general)==0){
				$this->errors = 'Please enter valid email 2.';
			}
            if($this->errors!=''){
				return $this->errors;
			}
			else{*/
				
				/* check duplicate record */
				/*$con = '';
				if($id>0){
					$con = " AND `id`!='".$id."'";
				}
				$q = "SELECT * FROM `".$this->table."` WHERE `is_delete`='0' AND `first_name`='".$fname."' ".$con;
				$res = $this->re_db_query($q);
				$return = $this->re_db_num_rows($res);
				if($return>0){
					$this->errors = 'This broker is already exists.';
				}
				*/
				if($this->errors!=''){
					return $this->errors;
				}
				else if($id>=0){
					if($id==0){
						$q = "INSERT INTO `".BROKER_GENERAL."` SET `broker_id`='".$_SESSION['last_insert_id']."',`home`='".$home_general."',`home_address1_general`='".$home_address1_general."',`home_address2_general`='".$home_address2_general."',`business_address1_general`='".$business_address1_general."',`business_address2_general`='".$business_address2_general."',`city`='".$city_general."',`state_id`='".$state_general."',`zip_code`='".$zip_code_general."',`telephone`='".$telephone_general."',`cell`='".$cell_general."',`fax`='".$fax_general."',`gender`='".$gender_general."',`marital_status`='".$status_general."',`spouse`='".$spouse_general."',`children`='".$children_general."',`email1`='".$email1_general."',`email2`='".$email2_general."',`web_id`='".$web_id_general."',`web_password`='".$web_password_general."',`dob`='".$dob_general."',`prospect_date`='".$prospect_date_general."',`reassign_broker`='".$reassign_broker_general."',`u4`='".$u4_general."',`u5`='".$u5_general."',`day_after_u5`='".$day_after_u5."',`dba_name`='".$dba_name_general."',`eft_information`='".$eft_info_general."',`start_date`='".$start_date_general."',`transaction_type`='".$transaction_type_general."',`routing`='".$routing_general."',`account_no`='".$account_no_general."',`cfp`='".$cfp_general."',`chfp`='".$chfp_general."',`cpa`='".$cpa_general."',`clu`='".$clu_general."',`cfa`='".$cfa_general."',`ria`='".$ria_general."',`insurance`='".$insurance_general."'".$this->insert_common_sql();
						$res = $this->re_db_query($q);
                        if($res){
						      
                            $_SESSION['success'] = INSERT_MESSAGE;
							return true;
						}
						/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
				}
                else {
					    $q = "UPDATE `".BROKER_GENERAL."`  SET `home`='".$home_general."',`home_address1_general`='".$home_address1_general."',`home_address2_general`='".$home_address2_general."',`business_address1_general`='".$business_address1_general."',`business_address2_general`='".$business_address2_general."',`city`='".$city_general."',`state_id`='".$state_general."',`zip_code`='".$zip_code_general."',`telephone`='".$telephone_general."',`cell`='".$cell_general."',`fax`='".$fax_general."',`gender`='".$gender_general."',`marital_status`='".$status_general."',`spouse`='".$spouse_general."',`children`='".$children_general."',`email1`='".$email1_general."',`email2`='".$email2_general."',`web_id`='".$web_id_general."',`web_password`='".$web_password_general."',`dob`='".$dob_general."',`prospect_date`='".$prospect_date_general."',`reassign_broker`='".$reassign_broker_general."',`u4`='".$u4_general."',`u5`='".$u5_general."',`day_after_u5`='".$day_after_u5."',`dba_name`='".$dba_name_general."',`eft_information`='".$eft_info_general."',`start_date`='".$start_date_general."',`transaction_type`='".$transaction_type_general."',`routing`='".$routing_general."',`account_no`='".$account_no_general."',`cfp`='".$cfp_general."',`chfp`='".$chfp_general."',`cpa`='".$cpa_general."',`clu`='".$clu_general."',`cfa`='".$cfa_general."',`ria`='".$ria_general."',`insurance`='".$insurance_general."'".$this->update_common_sql()." WHERE `broker_id`='".$id."'";
						$res = $this->re_db_query($q);
						if($res){
						      
                            $_SESSION['success'] = UPDATE_MESSAGE;
							return true;
						}
						/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
					}
				/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
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
        public function insert_update_payout_grid($data ,$id){
           
           $id = isset($id)?$this->re_db_input($id):0;
           $flag=0;
               if($id==0)
               {
                    foreach($data as $key=>$val)
                    { 
                        $sliding_rates = isset($val['sliding_rates'])?$this->re_db_input($val['sliding_rates']):'';
                        $from =isset($val['from'])?$this->re_db_input($val['from']):'';
                        $to =isset($val['to'])?$this->re_db_input($val['to']):'';
                        $per =isset($val['per'])?$this->re_db_input($val['per']):'';
                        if($from!='' && $to != '' && $per != ''){
                            
                            $q = "INSERT INTO `".BROKER_PAYOUT_GRID."` SET `broker_id`='".$_SESSION['last_insert_id']."',`sliding_rates`='".$sliding_rates."' ,`from`='".$from."' ,`to`='".$to."' ,`per`='".$per."' ".$this->insert_common_sql();
            				
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
                        if ($flag==0){
                           $qq="update `".BROKER_PAYOUT_GRID."` SET is_delete=1 where `broker_id`=".$id."";
                           $res = $this->re_db_query($qq);
                           $flag=1;
                        }
                        if($from!='' && $to != '' && $per != ''){
                             
                             $q = "INSERT INTO `".BROKER_PAYOUT_GRID."` SET `broker_id`='".$id."' ,`sliding_rates`='".$sliding_rates."' ,`from`='".$from."' ,`to`='".$to."' , 
                            `per`='".$per."' ".$this->insert_common_sql();
            				 $res = $this->re_db_query($q); 
                        }
                        else
                        {
                            $res='';
                        }
                    }    
               }
               if($res){
    			      
                    $_SESSION['success'] = INSERT_MESSAGE;
    				return true;
    			}
    			          
        }
        public function reArrayFiles_override($file_post){
               $file_ary = array();
               foreach($file_post as $key=>$val)
               {
                    $reindexed_filepost[$key] = array_values($val);
               }
               
               $file_count = count($reindexed_filepost['to1']);
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
               //echo'<pre>';print_r($file_ary);exit;
               return $file_ary;
        }
        public function insert_update_payout_override($data ,$id){
           $id = isset($id)?$this->re_db_input($id):0;
           $rap=isset($rap)?$this->re_db_input($rap):0;
           $flag1=0;
           
           if($id==0)
           {
                foreach($data as $key=>$val)
                {   
                    $rap=isset($val['receiving_rep1'])?$this->re_db_input($val['receiving_rep1']):'';
                    $from1=isset($val['from1'])?$this->re_db_input(date('Y-m-d',strtotime($val['from1']))):'0000-00-00';
                    $to1=isset($val['to1'])?$this->re_db_input(date('Y-m-d',strtotime($val['to1']))):'0000-00-00';
                    $product_category=isset($val['product_category1'])?$this->re_db_input($val['product_category1']):'';
                    $per1=isset($val['per1'])?$this->re_db_input($val['per1']):'';
                    if($from1!='' && $to1!='' && $rap != ''){
                        
                        $q = "INSERT INTO `".BROKER_PAYOUT_OVERRIDE."` SET `broker_id`='".$_SESSION['last_insert_id']."',`rap` = '".$rap."',`from`='".$from1."' ,`to`='".$to1."' ,`product_category`='".$product_category."' ,
                        `per`='".$per1."' ".$this->insert_common_sql();
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
                    $rap=isset($val['receiving_rep1'])?$this->re_db_input($val['receiving_rep1']):'';
                    $from1=isset($val['from1'])?$this->re_db_input(date('Y-m-d',strtotime($val['from1']))):'0000-00-00';
                    $to1=isset($val['to1'])?$this->re_db_input(date('Y-m-d',strtotime($val['to1']))):'0000-00-00';
                    $product_category=isset($val['product_category1'])?$this->re_db_input($val['product_category1']):'';
                    $per1=isset($val['per1'])?$this->re_db_input($val['per1']):'';
                    if($from1!='' && $to1!='' && $rap != ''){
                        if($flag1==0){
                            $qq="update `".BROKER_PAYOUT_OVERRIDE."` SET is_delete=1 where `broker_id`=".$id."";
                            $res = $this->re_db_query($qq);
                            $flag1=1;
                        }
                        $q = "INSERT INTO `".BROKER_PAYOUT_OVERRIDE."` SET `broker_id`='".$id."',`rap` = '".$rap."',`from`='".$from1."' ,`to`='".$to1."' ,`product_category`='".$product_category."' , 
                        `per`='".$per1."' ".$this->insert_common_sql();
        				$res = $this->re_db_query($q);
                    }
                    else
                    {
                        $res='';
                    }
                }    
           }
           if($res){
    		      
                $_SESSION['success'] = INSERT_MESSAGE;
    			return true;
    	   }
			
        } 
        public function reArrayFiles_split($file_post){
               $file_ary = array();
               foreach($file_post as $key=>$val)
               {
                    $reindexed_filepost[$key] = array_values($val);
               }
               
               $file_count = count($reindexed_filepost['rap']);
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
               return $file_ary;
        }
        public function insert_update_payout_split($data ,$id){//echo '<pre>';print_r($data);exit;
           $id = isset($id)?$this->re_db_input($id):0;
           $flag2=0;
           //echo '<pre>';print_r($data);exit;
           if($id==0)
           {
                foreach($data as $key=>$val)
                {   
                    $rap=isset($val['rap'])?$this->re_db_input($val['rap']):'';
                    $rate=isset($val['rate'])?$this->re_db_input($val['rate']):'';
                    $start=isset($val['start'])?$this->re_db_input(date('Y-m-d',strtotime($val['start']))):'0000-00-00';
                    $until = isset($val['until'])?$this->re_db_input(date('Y-m-d',strtotime($val['until']))):'0000-00-00';
                    if($rap!='' && $rate!='' && $start!='' && $until != ''){
                        
                        $q = "INSERT INTO `".BROKER_PAYOUT_SPLIT."` SET `broker_id`='".$_SESSION['last_insert_id']."' ,`rap`='".$rap."' ,`rate`='".$rate."' , 
                        `start`='".$start."' , `until`='".$until."' ".$this->insert_common_sql();
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
                    $rap=isset($val['rap'])?$this->re_db_input($val['rap']):'';
                    $rate=isset($val['rate'])?$this->re_db_input($val['rate']):'';
                    $start=isset($val['start'])?$this->re_db_input(date('Y-m-d',strtotime($val['start']))):'0000-00-00';
                    $until = isset($val['until'])?$this->re_db_input(date('Y-m-d',strtotime($val['until']))):'0000-00-00';
                    if($rap!='' && $rate!='' && $start!='' && $until != ''){
                        if($flag2==0){
                            $qq="update `".BROKER_PAYOUT_SPLIT."` SET is_delete=1 where `broker_id`=".$id."";
                            $res = $this->re_db_query($qq);
                            $flag2=1;
                        }
                        $q = "INSERT INTO `".BROKER_PAYOUT_SPLIT."` SET `broker_id`='".$id."' ,`rap`='".$rap."' ,`rate`='".$rate."' , 
                        `start`='".$start."' , `until`='".$until."' ".$this->insert_common_sql();
        				$res = $this->re_db_query($q);
                    }
                    else
                    {
                        $res='';
                    }
                }    
           }
           
            if($res){
			      
                $_SESSION['success'] = INSERT_MESSAGE;
				return true;
			}
			
        } 
        public function insert_update_payout_fixed_rates($data ,$id){//echo '<pre>';print_r($data);exit;
           $id = isset($id)?$this->re_db_input($id):0;
           
           if($id==0)
           {
                foreach($data['fixed_category_id'] as $key=>$val)
                {   
                    $category_rates = isset($data['category_rates_'.$val])?$data['category_rates_'.$val]:'';
                    $q = "INSERT INTO `".PAYOUT_FIXED_RATES."` SET `broker_id`='".$_SESSION['last_insert_id']."' ,`category_id`='".$val."' ,`category_rates`='".$category_rates."'".$this->insert_common_sql();
       				$res = $this->re_db_query($q);
                }    
           }
           else
           {
                $qq="update `".PAYOUT_FIXED_RATES."` SET is_delete=1 where `broker_id`=".$id."";
                $res = $this->re_db_query($qq);
                foreach($data['fixed_category_id'] as $key=>$val)
                {   
                    $category_rates = isset($data['category_rates_'.$val])?$data['category_rates_'.$val]:'';
                    $q = "INSERT INTO `".PAYOUT_FIXED_RATES."` SET `broker_id`='".$id."' ,`category_id`='".$val."' ,`category_rates`='".$category_rates."'".$this->insert_common_sql();
       				$res = $this->re_db_query($q);
                } 
           }
           if($res){
			      
                $_SESSION['success'] = INSERT_MESSAGE;
				return true;
			}
			
        } 
        public function insert_update_payout($data)
        {
            
            $id = isset($data['id'])?$this->re_db_input($data['id']):'0';
            $payout_schedule_id = isset($data['schedule_id'])?$this->re_db_input($data['schedule_id']):'';
            $payout_schedule_name = isset($data['schedule_name'])?$this->re_db_input($data['schedule_name']):'';            
            $transaction_type_general = isset($data['transaction_type_general'])?$this->re_db_input($data['transaction_type_general']):'1';
            $product_category1 = isset($data['product_category1'])?$this->re_db_input($data['product_category1']):'';
            $product_category2 = isset($data['product_category2'])?$this->re_db_input($data['product_category2']):'0';
            $product_category3 = isset($data['product_category3'])?$this->re_db_input($data['product_category3']):'0';
            $product_category4 = isset($data['product_category4'])?$this->re_db_input($data['product_category4']):'0';
            $basis = isset($data['basis'])?$this->re_db_input($data['basis']):'';
            $cumulative = isset($data['cumulative'])?$this->re_db_input($data['cumulative']):'0';
            $year = isset($data['year'])?$this->re_db_input($data['year']):'';
            $calculation_detail = isset($data['calculation_detail'])?$this->re_db_input($data['calculation_detail']):'';
            $clearing_charge_deducted_from = isset($data['clearing_charge_deducted_from'])?$this->re_db_input($data['clearing_charge_deducted_from']):'';
            $reset = isset($data['reset'])?$this->re_db_input(date('Y-m-d',strtotime($data['reset']))):'0000-00-00';
            $description_type = isset($data['description_type'])?$this->re_db_input($data['description_type']):'';
            $team_member = isset($data['team_member'])?$data['team_member']:array();
            $team_member_string = implode (",", $team_member);
            $minimum_trade_gross = isset($data['minimum_trade_gross'])?$this->re_db_input($data['minimum_trade_gross']):'';
            $minimum_12B1_gross = isset($data['minimum_12B1_gross'])?$this->re_db_input($data['minimum_12B1_gross']):'';
            $summarize_payroll_adjustments = isset($data['summarize_payroll_adjustments'])?$this->re_db_input($data['summarize_payroll_adjustments']):'';
            $summarize_12B1_from_autoposting = isset($data['summarize_12B1_from_autoposting'])?$this->re_db_input($data['summarize_12B1_from_autoposting']):'';
            $start = isset($data['start'])?$this->re_db_input(date('Y-m-d',strtotime($data['start']))):'0000-00-00';
            $until = isset($data['until'])?$this->re_db_input(date('Y-m-d',strtotime($data['until']))):'0000-00-00';
            $apply_to = isset($data['apply_to'])?$this->re_db_input($data['apply_to']):'';
            $product_2 = isset($data['product_2'])?$this->re_db_input($data['product_2']):'';
            $product_3 = isset($data['product_3'])?$this->re_db_input($data['product_3']):'';
            $product_4 = isset($data['product_4'])?$this->re_db_input($data['product_4']):'';
            $product_5 = isset($data['product_5'])?$this->re_db_input($data['product_5']):'';
            $calculate_on = isset($data['calculate_on'])?$this->re_db_input($data['calculate_on']):'';
            $deduct = isset($data['deduct'])?$this->re_db_input($data['deduct']):'';
            $hold_commissions = isset($data['hold_commissions'])?$this->re_db_input($data['hold_commissions']):'';
            if(isset($data['hold_commission_until']) && $data['hold_commission_until']!='')
            {
                $hold_commission_until = date('Y-m-d',strtotime($data['hold_commission_until']));
            }
            else
            {
                $hold_commission_until = '';
            }
            if(isset($data['hold_commission_after']) && $data['hold_commission_after']!='')
            {
                $hold_commission_after = date('Y-m-d',strtotime($data['hold_commission_after']));
            }
            else
            {
                $hold_commission_after = '';
            }
            
            if($id>=0){
                
                $qq="select broker_id from ".BROKER_PAYOUT_MASTER." where `broker_id`=".$id."";
                $re=$this->re_db_query($qq);
                $record=$this->re_db_num_rows($re);
                
				if($record==0){ 
                        
                        $q = "INSERT INTO `".BROKER_PAYOUT_MASTER."` SET `broker_id`='".$_SESSION['last_insert_id']."' ,`payout_schedule_id`='".$payout_schedule_id."' ,`payout_schedule_name`='".$payout_schedule_name."' ,`transaction_type_general`='".$transaction_type_general."' ,`product_category1`='".$product_category1."' , 
                        `product_category2`='".$product_category2."' , `product_category3`='".$product_category3."' ,`product_category4`='".$product_category4."' ,`basis`='".$basis."' ,
                        `cumulative`='".$cumulative."' ,`year`='".$year."' ,`calculation_detail`='".$calculation_detail."' ,`clearing_charge_deducted_from`='".$clearing_charge_deducted_from."',`reset`='".$reset."',`description_type`='".$description_type."',`minimum_trade_gross`='".$minimum_trade_gross."',`minimum_12B1_gross`='".$minimum_12B1_gross."' ,
                        `team_member`='".$team_member_string."' ,`start`='".$start."',`until`='".$until."',`apply_to`='".$apply_to."',`product_2`='".$product_2."' ,`product_3`='".$product_3."',`product_4`='".$product_4."',
                        `product_5`='".$product_5."' ,`calculate_on`='".$calculate_on."',`deduct`='".$deduct."',`hold_commissions`='".$hold_commissions."' ,`hold_commission_until`='".$hold_commission_until."',`hold_commission_after`='".$hold_commission_after."',`summarize_payroll_adjustments`='".$summarize_payroll_adjustments."',`summarize_12B1_from_autoposting`='".$summarize_12B1_from_autoposting."'".$this->insert_common_sql();
    					$res = $this->re_db_query($q);
                          
                        if($res){
    					      
                            $_SESSION['success'] = INSERT_MESSAGE;
    						return true;
    					}
    					else{
    						$_SESSION['warning'] = UNKWON_ERROR;
    						return false;
    					}
                    
    			}
                else if($record>0){
                    
                        $q = "UPDATE `".BROKER_PAYOUT_MASTER."`  SET `broker_id`='".$id."',`payout_schedule_id`='".$payout_schedule_id."' ,`payout_schedule_name`='".$payout_schedule_name."' ,`transaction_type_general`='".$transaction_type_general."' ,`product_category1`='".$product_category1."' , 
                        `product_category2`='".$product_category2."' , `product_category3`='".$product_category3."' ,`product_category4`='".$product_category4."' ,`basis`='".$basis."' ,
                        `cumulative`='".$cumulative."' ,`year`='".$year."',`calculation_detail`='".$calculation_detail."',`clearing_charge_deducted_from`='".$clearing_charge_deducted_from."',`reset`='".$reset."',`description_type`='".$description_type."',`minimum_trade_gross`='".$minimum_trade_gross."',`minimum_12B1_gross`='".$minimum_12B1_gross."' ,
                        `team_member`='".$team_member_string."' ,`start`='".$start."',`until`='".$until."',`apply_to`='".$apply_to."',`product_2`='".$product_2."' ,`product_3`='".$product_3."',`product_4`='".$product_4."',
                        `product_5`='".$product_5."' ,`calculate_on`='".$calculate_on."',`deduct`='".$deduct."',`hold_commissions`='".$hold_commissions."' ,`hold_commission_until`='".$hold_commission_until."',`hold_commission_after`='".$hold_commission_after."',`summarize_payroll_adjustments`='".$summarize_payroll_adjustments."',`summarize_12B1_from_autoposting`='".$summarize_12B1_from_autoposting."'".$this->update_common_sql()." WHERE  `broker_id`='".$id."'";
      					
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
                /*if($res){			      
                    $_SESSION['success'] = UPDATE_MESSAGE;
					return true;
				}
				/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
            }
        }
        
        public function insert_update_licences($data)
        { //echo '<pre>';print_r($data['id']);exit;
            $id = isset($data['id'])?$this->re_db_input($data['id']):0;
            $type_of_licences =isset($data['type'])?$this->re_db_input($data['type']):'';
            $waive_home_state_fee = isset($data['pass_through'])?$this->re_db_input($data['pass_through']):'0';
            $product_category = isset($data['product_category'])?$this->re_db_input($data['product_category']):'0';
            if($id>=0){ 
                
				if($id==0){ 
				    foreach($data['data1'] as $key=>$val)
                    {        
                        $active_check=isset($val['active_check'])?$this->re_db_input($val['active_check']):'0';
                        $fee=isset($val['fee'])?$this->re_db_input($val['fee']):'';
                        $received=isset($val['received'])?$this->re_db_input(date('Y-m-d',strtotime($val['received']))):'0000-00-00';
                        $terminated=isset($val['terminated'])?$this->re_db_input(date('Y-m-d',strtotime($val['terminated']))):'0000-00-00';
                        $reason=isset($val['reason'])?$this->re_db_input($val['reason']):'';
                        
                        $q = "INSERT INTO `".BROKER_LICENCES_SECURITIES."` SET `broker_id`='".$_SESSION['last_insert_id']."' ,`type_of_licences`='".$type_of_licences."' ,`state_id`='".$key."' , 
                        `waive_home_state_fee`='".$waive_home_state_fee."' , `product_category`='".$product_category."' ,`active_check`='".$active_check."' ,`fee`='".$fee."' ,
                        `received`='".$received."' ,`terminated`='".$terminated."',`reson`='".$reason."' ".$this->insert_common_sql();
    					$res = $this->re_db_query($q);
                    }   
                          
                    if($res){
					      
                        $_SESSION['success'] = INSERT_MESSAGE;
						return true;
					}
					/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
                    
    			} 
                else if($id>0){   //echo $id;exit;
                    $qq="select broker_id from ".BROKER_LICENCES_SECURITIES." where `broker_id`=".$id."";
                    $re=$this->re_db_query($qq);
                    $variable=$this->re_db_num_rows($re);
                    foreach($data['data1'] as $key=>$val)
                    {
                        $active_check=isset($val['active_check'])?$this->re_db_input($val['active_check']):'0';
                        $fee=isset($val['fee'])?$this->re_db_input($val['fee']):'';
                        $received=isset($val['received'])?$this->re_db_input(date('Y-m-d',strtotime($val['received']))):'0000-00-00';
                        $terminated=isset($val['terminated'])?$this->re_db_input(date('Y-m-d',strtotime($val['terminated']))):'0000-00-00';
                        $reason=isset($val['reason'])?$this->re_db_input($val['reason']):'';
                        
                        if($variable>0)
                        {
                            $q = "UPDATE `".BROKER_LICENCES_SECURITIES."`  SET `type_of_licences`='".$type_of_licences."' ,`state_id`='".$key."' , 
                            `waive_home_state_fee`='".$waive_home_state_fee."' , `product_category`='".$product_category."' ,`active_check`='".$active_check."' ,`fee`='".$fee."' ,
                            `received`='".$received."' ,`terminated`='".$terminated."',`reson`='".$reason."' ".$this->update_common_sql()." WHERE `state_id`='".$key."' and `broker_id`='".$id."'";
      					
                            $res = $this->re_db_query($q);
    		            }
                        else
                        {   
        				    $q="INSERT INTO `".BROKER_LICENCES_SECURITIES."` SET `broker_id`='".$id."' ,`type_of_licences`='".$type_of_licences."' ,`state_id`='".$key."' , 
                            `waive_home_state_fee`='".$waive_home_state_fee."' , `product_category`='".$product_category."' ,`active_check`='".$active_check."' ,`fee`='".$fee."' ,
                            `received`='".$received."' ,`terminated`='".$terminated."',`reson`='".$reason."' ".$this->insert_common_sql();
        					$res = $this->re_db_query($q);
                        }  
                        	
                }
                if($res){			      
                    $_SESSION['success'] = UPDATE_MESSAGE;
					return true;
				}
				/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
        				}
            }
            //echo $id;
            //exit;
        }
        public function insert_update_licences1($data)
        {
            //echo '<pre>';print_r($data);
            $id = isset($data['id'])?$this->re_db_input($data['id']):0;
            $type_of_licences =isset($data['type'])?$this->re_db_input($data['type']):'';
            $waive_home_state_fee = isset($data['pass_through'])?$this->re_db_input($data['pass_through']):'0';
            $product_category = isset($data['product_category'])?$this->re_db_input($data['product_category']):'0';
            if($id>=0){
				if($id==0){
				    foreach($data['data2'] as $key=>$val)
                    {        
                        $active_check=isset($val['active_check'])?$this->re_db_input($val['active_check']):'0';
                        $fee=isset($val['fee'])?$this->re_db_input($val['fee']):'';
                        $received=isset($val['received'])?$this->re_db_input(date('Y-m-d',strtotime($val['received']))):'0000-00-00';
                        $terminated=isset($val['terminated'])?$this->re_db_input(date('Y-m-d',strtotime($val['terminated']))):'0000-00-00';
                        $reason=isset($val['reason'])?$this->re_db_input($val['reason']):'';
                        
                        $q = "INSERT INTO `".BROKER_LICENCES_INSURANCE."` SET `broker_id`='".$_SESSION['last_insert_id']."' ,`type_of_licences`='".$type_of_licences."' ,`state_id`='".$key."' , 
                        `waive_home_state_fee`='".$waive_home_state_fee."' , `product_category`='".$product_category."' ,`active_check`='".$active_check."' ,`fee`='".$fee."' ,
                        `received`='".$received."' ,`terminated`='".$terminated."',`reson`='".$reason."' ".$this->insert_common_sql();
    					$res = $this->re_db_query($q);
                    }   
                          
                    if($res){
					      
                        $_SESSION['success'] = INSERT_MESSAGE;
						return true;
					}
					/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
                    
    			}
                else if($id>0){
                    $qq="select broker_id from ".BROKER_LICENCES_INSURANCE." where `broker_id`=".$id."";
                    $re=$this->re_db_query($qq);
                    $variable=$this->re_db_num_rows($re);
                    foreach($data['data2'] as $key=>$val)
                    {
                        $active_check=isset($val['active_check'])?$this->re_db_input($val['active_check']):'0';
                        $fee=isset($val['fee'])?$this->re_db_input($val['fee']):'';
                        $received=isset($val['received'])?$this->re_db_input(date('Y-m-d',strtotime($val['received']))):'0000-00-00';
                        $terminated=isset($val['terminated'])?$this->re_db_input(date('Y-m-d',strtotime($val['terminated']))):'0000-00-00';
                        $reason=isset($val['reason'])?$this->re_db_input($val['reason']):'';
                        
                    
    				   if($variable>0)
                        {
                            $q = "UPDATE `".BROKER_LICENCES_INSURANCE."`  SET `type_of_licences`='".$type_of_licences."' ,`state_id`='".$key."' , 
                            `waive_home_state_fee`='".$waive_home_state_fee."' , `product_category`='".$product_category."' ,`active_check`='".$active_check."' ,`fee`='".$fee."' ,
                            `received`='".$received."' ,`terminated`='".$terminated."',`reson`='".$reason."' ".$this->update_common_sql()." WHERE `state_id`='".$key."' and `broker_id`='".$id."'";
      					
                            $res = $this->re_db_query($q);
    		            }
                        else
                        {   
        				    $q="INSERT INTO `".BROKER_LICENCES_INSURANCE."` SET `broker_id`='".$id."' ,`type_of_licences`='".$type_of_licences."' ,`state_id`='".$key."' , 
                            `waive_home_state_fee`='".$waive_home_state_fee."' , `product_category`='".$product_category."' ,`active_check`='".$active_check."' ,`fee`='".$fee."' ,
                            `received`='".$received."' ,`terminated`='".$terminated."',`reson`='".$reason."' ".$this->insert_common_sql();
        					$res = $this->re_db_query($q);
                        } 
                    }
                	if($res){
    				      
                        $_SESSION['success'] = UPDATE_MESSAGE;
    					return true;
    				}
    				/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
    				}
            }
            //echo $id;
            //exit;
        }
        public function insert_update_licences2($data)
        {
            //echo '<pre>';print_r($data);
            $id = isset($data['id'])?$this->re_db_input($data['id']):0;
            $type_of_licences =isset($data['type'])?$this->re_db_input($data['type']):'';
            $waive_home_state_fee = isset($data['pass_through'])?$this->re_db_input($data['pass_through']):'0';
            $product_category = isset($data['product_category'])?$this->re_db_input($data['product_category']):'0';
            if($id>=0){
				if($id==0){
				    foreach($data['data3'] as $key=>$val)
                    {        
                        $active_check=isset($val['active_check'])?$this->re_db_input($val['active_check']):'0';
                        $fee=isset($val['fee'])?$this->re_db_input($val['fee']):'';
                        $received=isset($val['received'])?$this->re_db_input(date('Y-m-d',strtotime($val['received']))):'0000-00-00';
                        $terminated=isset($val['terminated'])?$this->re_db_input(date('Y-m-d',strtotime($val['terminated']))):'0000-00-00';
                        $reason=isset($val['reason'])?$this->re_db_input($val['reason']):'';
                        
                        $q = "INSERT INTO `".BROKER_LICENCES_RIA."` SET `broker_id`='".$_SESSION['last_insert_id']."' ,`type_of_licences`='".$type_of_licences."' ,`state_id`='".$key."' , 
                        `waive_home_state_fee`='".$waive_home_state_fee."' , `product_category`='".$product_category."' ,`active_check`='".$active_check."' ,`fee`='".$fee."' ,
                        `received`='".$received."' ,`terminated`='".$terminated."',`reson`='".$reason."' ".$this->insert_common_sql();
    					$res = $this->re_db_query($q);
                    }   
                          
                    if($res){
					      
                        $_SESSION['success'] = INSERT_MESSAGE;
						return true;
					}
					/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
                    
    			}
                else if($id>0){
                    $qq="select broker_id from ".BROKER_LICENCES_RIA." where `broker_id`=".$id."";
                    $re=$this->re_db_query($qq);
                    $variable=$this->re_db_num_rows($re);
                    foreach($data['data3'] as $key=>$val)
                    {
                        $active_check=isset($val['active_check'])?$this->re_db_input($val['active_check']):'0';
                        $fee=isset($val['fee'])?$this->re_db_input($val['fee']):'';
                        $received=isset($val['received'])?$this->re_db_input(date('Y-m-d',strtotime($val['received']))):'0000-00-00';
                        $terminated=isset($val['terminated'])?$this->re_db_input(date('Y-m-d',strtotime($val['terminated']))):'0000-00-00';
                        $reason=isset($val['reason'])?$this->re_db_input($val['reason']):'';
                        
                    
    				    if($variable>0)
                        {
                            $q = "UPDATE `".BROKER_LICENCES_RIA."`  SET `type_of_licences`='".$type_of_licences."' ,`state_id`='".$key."' , 
                            `waive_home_state_fee`='".$waive_home_state_fee."' , `product_category`='".$product_category."' ,`active_check`='".$active_check."' ,`fee`='".$fee."' ,
                            `received`='".$received."' ,`terminated`='".$terminated."',`reson`='".$reason."' ".$this->update_common_sql()." WHERE `state_id`='".$key."' and `broker_id`='".$id."'";
      					
                            $res = $this->re_db_query($q);
    		            }
                        else
                        {   
        				    $q="INSERT INTO `".BROKER_LICENCES_RIA."` SET `broker_id`='".$id."' ,`type_of_licences`='".$type_of_licences."' ,`state_id`='".$key."' , 
                            `waive_home_state_fee`='".$waive_home_state_fee."' , `product_category`='".$product_category."' ,`active_check`='".$active_check."' ,`fee`='".$fee."' ,
                            `received`='".$received."' ,`terminated`='".$terminated."',`reson`='".$reason."' ".$this->insert_common_sql();
        					$res = $this->re_db_query($q);
                        }
		          }
                    	if($res){
    					      
                            $_SESSION['success'] = UPDATE_MESSAGE;
    						return true;
    					}
    					/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
    				}
            }
            //echo $id;
            //exit;
        }
        public function reArrayFiles($file_post){
               $file_ary = array();
               //echo '<pre>';print_R($file_post);
               $file_count = count($file_post['docs_description']);
               $file_keys = array_keys($file_post);
              //echo '<pre>';print_R($file_keys);
               for ($i=1; $i<=$file_count; $i++) { 
                   foreach ($file_keys as $key) {      
                        if(isset($file_post[$key][$i]))
                        {
                            $file_ary[$i][$key] = $file_post[$key][$i];   
                        }
                        else
                        {
                            $file_ary[$i][$key] = '';
                        }
                   }
               }
               //echo '<pre>';print_R($file_ary);exit;
               return $file_ary;
           }
           
        public function insert_update_req_doc($data ,$id1){
           //echo '<pre>';print_r($data);exit;
           $id = isset($id1)?$this->re_db_input($id1):0;
           $flag4=0;
           /*foreach($data as $key=>$val)
            {   
                $docs_receive=isset($val['docs_receive'])?$this->re_db_input($val['docs_receive']):'';
                $docs_description=isset($val['docs_description'])?$this->re_db_input($val['docs_description']):'';
                $docs_date=isset($val['docs_date'])?$this->re_db_input(date('Y-m-d',strtotime($val['docs_date']))):'0000-00-00';
                $docs_required = isset($val['docs_required'])?$this->re_db_input($val['docs_required']):'';
                
                if( $docs_description!=''){
                    if($flag4==0){
                        $qq="update `".BROKER_REQ_DOC."` SET is_delete=1 where `broker_id`=".$id."";
                        $res = $this->re_db_query($qq);
                        $flag4=1;
                    }
                    $q = "INSERT INTO `".BROKER_REQ_DOC."` SET `broker_id`='".$_SESSION['last_insert_id']."' ,`received`='".$docs_receive."' ,`description`='".$docs_description."' , 
                    `date`='".$docs_date."' , `required`='".$docs_required."' ".$this->insert_common_sql();
    				$res = $this->re_db_query($q);
                }
                else
                {
                    $res='';
                }
            }    
            if($res){
			      
                $_SESSION['success'] = INSERT_MESSAGE;
				return true;
			}*/
			/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
           if($id>=0){
				if($id==0){
    			    foreach($data as $key=>$val)
                    {     
                        $docs_receive=isset($val['docs_receive'])?$this->re_db_input($val['docs_receive']):'';
                        $docs_description=isset($val['docs_description'])?$this->re_db_input($val['docs_description']):'';
                        $docs_date=isset($val['docs_date'])?$this->re_db_input(date('Y-m-d',strtotime($val['docs_date']))):'';
                        $docs_required = isset($val['docs_required'])?$this->re_db_input($val['docs_required']):'';
                        
                        if( $docs_description!=''){
                            
                            $q = "INSERT INTO `".BROKER_REQ_DOC."` SET `broker_id`='".$_SESSION['last_insert_id']."' ,`received`='".$docs_receive."' ,`description`='".$docs_description."' , 
                            `date`='".$docs_date."' , `required`='".$docs_required."' ".$this->insert_common_sql();
        					$res = $this->re_db_query($q);
                        }
                    }    
                    if($res){
    				      
                        $_SESSION['success'] = INSERT_MESSAGE;
    					return true;
    				}
    		    }
                else if($id>0){
                    
                    foreach($data as $key=>$val)
                    {
                        $docs_receive=isset($val['docs_receive'])?$this->re_db_input($val['docs_receive']):'';
                        $docs_description=isset($val['docs_description'])?$this->re_db_input($val['docs_description']):'';
                        $docs_date=isset($val['docs_date'])?$this->re_db_input(date('Y-m-d',strtotime($val['docs_date']))):'';
                        $docs_required = isset($val['docs_required'])?$this->re_db_input($val['docs_required']):'';
                                            
    				    if( $docs_description!=''){
                            
                            if($flag4==0){
                                $qq="update `".BROKER_REQ_DOC."` SET is_delete=1 where `broker_id`=".$id."";
                                $res = $this->re_db_query($qq);
                                $flag4=1;
                            }
                        
                            $q = "INSERT INTO `".BROKER_REQ_DOC."` SET `broker_id`='".$id."' ,`received`='".$docs_receive."' ,`description`='".$docs_description."' , 
                            `date`='".$docs_date."' , `required`='".$docs_required."' ".$this->insert_common_sql();
        					$res = $this->re_db_query($q);
                        }
                        
                        /*$q = "UPDATE `".BROKER_REQ_DOC."` SET `broker_id`='".$id."' ,`received`='".$docs_receive."' ,`description`='".$docs_description."' , 
                        `date`='".$docs_date."' , `required`='".$docs_required."'  ".$this->update_common_sql()." WHERE `broker_id`='".$id."'";
  					
                        $res = $this->re_db_query($q);*/
                    }
                	if($res){
					      
                        $_SESSION['success'] = UPDATE_MESSAGE;
						return true;
					}
 				}
           
            }
        }
        public function reArrayFiles_alias($file_post){
               $file_ary = array();
               foreach($file_post as $key=>$val)
               {
                    $reindexed_filepost[$key] = array_values($val);
               }
               
               $file_count = count($reindexed_filepost['alias_name']);
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
               return $file_ary;
        }
        public function insert_update_alias($data ,$id){//echo '<pre>';print_r($data);exit;
           $id = isset($id)?$this->re_db_input($id):0;
           $flag8=0;
           if($id==0)
           {
                foreach($data as $key=>$val)
                {   
                    $alias_name=isset($val['alias_name'])?$this->re_db_input($val['alias_name']):'';
                    $sponsor_company=isset($val['sponsor_company'])?$this->re_db_input($val['sponsor_company']):0;
                    $date=isset($val['date'])?$this->re_db_input(date('Y-m-d',strtotime($val['date']))):'0000-00-00';
                    if($alias_name!='' && $sponsor_company!='' && $date!=''){
                        
                        $q = "INSERT INTO `".BROKER_ALIAS."` SET `broker_id`='".$_SESSION['last_insert_id']."' ,`alias_name`='".$alias_name."' ,`sponsor_company`='".$sponsor_company."' , 
                        `date`='".$date."' ".$this->insert_common_sql();
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
                    $alias_name=isset($val['alias_name'])?$this->re_db_input($val['alias_name']):'';
                    $sponsor_company=isset($val['sponsor_company'])?$this->re_db_input($val['sponsor_company']):0;
                    $date=isset($val['date'])?$this->re_db_input(date('Y-m-d',strtotime($val['date']))):'0000-00-00';
                    if($alias_name!='' && $sponsor_company!='' && $date!=''){
                        if($flag8==0){
                            $qq="update `".BROKER_ALIAS."` SET is_delete=1 where `broker_id`=".$id."";
                            $res = $this->re_db_query($qq);
                            $flag8=1;
                        }
                        $q = "INSERT INTO `".BROKER_ALIAS."` SET `broker_id`='".$id."' ,`alias_name`='".$alias_name."' ,`sponsor_company`='".$sponsor_company."' , 
                        `date`='".$date."' ".$this->insert_common_sql();
        				$res = $this->re_db_query($q);
                    }
                    else
                    {
                        $res='';
                    }
                }    
           }
           
            if($res){
			      
                $_SESSION['success'] = INSERT_MESSAGE;
				return true;
			}
        }
        public function insert_update_register($data){
           $id = isset($data['id'])?$this->re_db_input($data['id']):0;
           
            if($id>=0){
				if($id==0){ 
				    foreach($data['data4'] as $key=>$val)
                    {       
                        $approval_date=isset($val['approval_date'])?$this->re_db_input(date('Y-m-d',strtotime($val['approval_date']))):'';
                        $expiration_date=isset($val['expiration_date'])?$this->re_db_input(date('Y-m-d',strtotime($val['expiration_date']))):'';
                        $register_reason=isset($val['register_reason'])?$this->re_db_input($val['register_reason']):'';
                        $type = isset($val['type'])?$this->re_db_input($val['type']):'';
                        $q = "INSERT INTO `".BROKER_REGISTER_MASTER."` SET `broker_id`='".$_SESSION['last_insert_id']."' ,`license_id`='".$key."' ,`license_name`='".$type."' , 
                        `approval_date`='".$approval_date."' , `expiration_date`='".$expiration_date."' ,`reason`='".$register_reason."' ".$this->insert_common_sql();
    					$res = $this->re_db_query($q);
                    }   
                          
                    if($res){
					      
                        $_SESSION['success'] = INSERT_MESSAGE;
						return true;
					}
					/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
                    
    			}
                else if($id>0){
                    $qq="select broker_id from ".BROKER_REGISTER_MASTER." where `broker_id`=".$id."";
                    $re=$this->re_db_query($qq);
                    $variable=$this->re_db_num_rows($re);
                    foreach($data['data4'] as $key=>$val)
                    {
                        $approval_date=isset($val['approval_date'])?$this->re_db_input(date('Y-m-d',strtotime($val['approval_date']))):'';
                        $expiration_date=isset($val['expiration_date'])?$this->re_db_input(date('Y-m-d',strtotime($val['expiration_date']))):'';
                        $register_reason=isset($val['register_reason'])?$this->re_db_input($val['register_reason']):'';
                        $type = isset($val['type'])?$this->re_db_input($val['type']):'';
                           
                        if($variable>0)
                        {                 
        				    $q = "UPDATE `".BROKER_REGISTER_MASTER."` SET `license_id`='".$key."' ,`license_name`='".$type."' , 
                            `approval_date`='".$approval_date."' , `expiration_date`='".$expiration_date."' ,`reason`='".$register_reason."' ".$this->update_common_sql()." WHERE  `license_id`='".$key."' and `broker_id`='".$id."'";
                            $res = $this->re_db_query($q);
      					}
                        else
                        {
                            $q = "INSERT INTO `".BROKER_REGISTER_MASTER."` SET `broker_id`='".$id."' ,`license_id`='".$key."' ,`license_name`='".$type."' , 
                            `approval_date`='".$approval_date."' , `expiration_date`='".$expiration_date."' ,`reason`='".$register_reason."' ".$this->insert_common_sql();
        					$res = $this->re_db_query($q);
                        }
                        
		          }
                    	if($res){
    					      
                            $_SESSION['success'] = UPDATE_MESSAGE;
    						return true;
    					}
    					/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
    				}
            }
        }
        /** Insert update charges data for broker. **/
        public function insert_update_charges1231321564($data){
        $id = isset($data['id'])?$this->re_db_input($data['id']):0;
				if($id==0){
				    
				        foreach($data as $key=>$val)
                        {
                            $charges_type=$key;
                            foreach($val as $key=>$value)
                            {
                                $charges_name=$key;//echo '<pre>';print_r($value);exit;
                                $q = "INSERT INTO `".BROKER_CHARGES_MASTER."` SET `broker_id`='".$_SESSION['last_insert_id']."',`charges_type`='".$charges_type."',`charges_name`='".$charges_name."',`manage_clearing`='".$value['clearing']."',`manage_execution`='".$value['execution']."',`non_manage_clearing`='".$value['non_clearing']."',`non_manage_execution`='".$value['non_execution']."'".$this->insert_common_sql();
        						$res = $this->re_db_query($q);
                            }exit;
                        }
                        if($res){
						    $_SESSION['success'] = INSERT_MESSAGE;
							return true;
						}
						/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
				}
				/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
		}
        /** Insert update charges data for broker. **/
        public function insert_update_charges($data){//print_r($data['pass_through1']);exit;
            $pass_through = isset($data['pass_through1'])?$this->re_db_input($data['pass_through1']):0;
        if(isset($data['id']) && $data['id']=='0'){$id=$_SESSION['last_insert_id']; } else { $id =isset($data['id'])?$this->re_db_input($data['id']):0 ; }
            if($id>0){
			    foreach($data['inp_type'] as $type_id=>$type_vale)
                {
                    foreach($type_vale as $name_id=>$name_val)
                    {
                        foreach($name_val as $accout_type=>$account_val)
                        {
                            foreach($account_val as $account_process=>$value)
                            {
                                $res=$this->re_db_query("SELECT cd.charge_detail_id, cv.value FROM ft_charge_detail cd left join ft_charge_value cv on cd.charge_detail_id=cv.charge_detail_id and cv.broker_id='".$id."' WHERE cd.charge_type_id='".$type_id."' AND cd.charge_name_id='".$name_id."' AND account_type='".$accout_type."' AND account_process='".$account_process."'");
                                $row=$this->re_db_fetch_array($res);
                                if($row['value']!='')
                                {
                                    $r=$this->re_db_query("update ft_charge_value set none_check = '".$pass_through."',value='".$value."'".$this->update_common_sql()." where charge_detail_id='".$row['charge_detail_id']."' and broker_id='".$id."'");                           
                                } 
                                else
                                {
                                    $r=$this->re_db_query("INSERT INTO ft_charge_value set charge_detail_id='".$row['charge_detail_id']."',broker_id='".$id."',none_check = '".$pass_through."',value='".$value."'".$this->insert_common_sql());
                                }   
                            }   
                        }
                         
                    }
                }
                if($r){
				    $_SESSION['success'] = INSERT_MESSAGE;
					return true;
				}
				/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
			}
			/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
		}
        /**
		 * @param int status, default all
		 * @return array of record if success, error message if any errors
		 * */
        public function insert_update_broker_notes($data){//print_r($data);
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
                    $q = "INSERT INTO `".BROKER_NOTES."` SET `date`='".$date."',`user_id`='".$user_id."',`notes`='".$client_note."'".$this->insert_common_sql();
			        $res = $this->re_db_query($q);
                    
                    $notes_id = $this->re_db_insert_id();
    				if($res){
    				    $_SESSION['success'] = INSERT_MESSAGE;
    					return true;
    				}
    				/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
    			}
    			else if($notes_id>0){
    			    
                    $q = "UPDATE `".BROKER_NOTES."` SET `date`='".$date."',`user_id`='".$user_id."',`notes`='".$client_note."'".$this->update_common_sql()." WHERE `id`='".$notes_id."'";
       				$res = $this->re_db_query($q);
                    
                    if($res){
    				    $_SESSION['success'] = UPDATE_MESSAGE;
    					return true;
    				}
    				/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
    			}
            }
		}
        public function insert_update_broker_attach($data){//print_r($data);exit;
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
                        $q = "INSERT INTO `".BROKER_ATTACH."` SET `date`='".$date."',`user_id`='".$user_id."',`attach`='".$file."' ,`file_name`='".$file_name."'".$this->insert_common_sql();
            	        $res = $this->re_db_query($q);
                        
                        $attach_id = $this->re_db_insert_id();
            			if($res){
            			    $_SESSION['success'] = INSERT_MESSAGE;
            				return true;
            			}
            			/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
            		}
            		else if($attach_id>0){
            		    
                        $q = "UPDATE `".BROKER_ATTACH."` SET `date`='".$date."',`user_id`='".$user_id."',`attach`='".$file."' ,`file_name`='".$file_name."'".$this->update_common_sql()." WHERE `id`='".$attach_id."'";
            			$res = $this->re_db_query($q);
                        
                        if($res){
            			    $_SESSION['success'] = UPDATE_MESSAGE;
            				return true;
            			}
            			/*else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}*/
            		}
                }
               
            }	
		}
        /** Insert update branches data for broker. **/
        public function insert_update_branches($data){
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
			$branch_broker = isset($data['branch_broker'])?$this->re_db_input($data['branch_broker']):'';
			$branch_1 = isset($data['branch_1'])?$this->re_db_input($data['branch_1']):'';
			$branch_office_1 = isset($data['branch_office_1'])?$this->re_db_input($data['branch_office_1']):'';
            $branch_2 = isset($data['branch_2'])?$this->re_db_input($data['branch_2']):'';
			$branch_office_2 = isset($data['branch_office_2'])?$this->re_db_input($data['branch_office_2']):'';
            $branch_3 = isset($data['branch_3'])?$this->re_db_input($data['branch_3']):'';
			$branch_office_3 = isset($data['branch_office_3'])?$this->re_db_input($data['branch_office_3']):'';
            $assess_branch_office_fee = isset($data['assess_branch_office_fee'])?$this->re_db_input($data['assess_branch_office_fee']):0;
			$assess_audit_fee = isset($data['assess_audit_fee'])?$this->re_db_input($data['assess_audit_fee']):0;
			$stamp = isset($data['stamp'])?$this->re_db_input($data['stamp']):0;
			$stamp_certification = isset($data['stamp_certification'])?$this->re_db_input($data['stamp_certification']):0;
			$stamp_indemnification = isset($data['stamp_indemnification'])?$this->re_db_input($data['stamp_indemnification']):0;
            
			if($id==0){
				$q = "INSERT INTO `".BROKER_BRANCHES."` SET `broker_id`='".$_SESSION['last_insert_id']."',`broker_name`='".$branch_broker."',`branch1`='".$branch_1."',`branch_office1`='".$branch_office_1."',`branch2`='".$branch_2."',`branch_office2`='".$branch_office_2."',`branch3`='".$branch_3."',`branch_office3`='".$branch_office_3."',`assess_branch_office_fee`='".$assess_branch_office_fee."',`assess_audit_fee`='".$assess_audit_fee."',`stamp`='".$stamp."',`stamp_certification`='".$stamp_certification."',`stamp_indemnification`='".$stamp_indemnification."'".$this->insert_common_sql();
				$res = $this->re_db_query($q);
                if($res){
				      
                    $_SESSION['success'] = INSERT_MESSAGE;
					return true;
				}
				/*else{
					$_SESSION['warning'] = UNKWON_ERROR;
					return false;
				}*/
			}
            else 
            {
			    $q = "UPDATE `".BROKER_BRANCHES."`  SET `broker_name`='".$branch_broker."',`branch1`='".$branch_1."',`branch_office1`='".$branch_office_1."',`branch2`='".$branch_2."',`branch_office2`='".$branch_office_2."',`branch3`='".$branch_3."',`branch_office3`='".$branch_office_3."',`assess_branch_office_fee`='".$assess_branch_office_fee."',`assess_audit_fee`='".$assess_audit_fee."',`stamp`='".$stamp."',`stamp_certification`='".$stamp_certification."',`stamp_indemnification`='".$stamp_indemnification."'".$this->update_common_sql()." WHERE `broker_id`='".$id."'";
				$res = $this->re_db_query($q);
				if($res){
				      
                    $_SESSION['success'] = UPDATE_MESSAGE;
					return true;
				}
				/*else{
					$_SESSION['warning'] = UNKWON_ERROR;
					return false;
				}*/
			}
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
        public function select_notes(){
			$return = array();
			
			$q = "SELECT `s`.*
					FROM `".BROKER_NOTES."` AS `s`
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
        public function select_docs(){
			$return = array();
			
			$q = "SELECT `s`.*
					FROM `".BROKER_DOCUMENT_MASTER."` AS `s`
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
					FROM `".BROKER_ATTACH."` AS `s`
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
				$q = "UPDATE `".BROKER_NOTES."` SET `is_delete`='1' WHERE `id`='".$id."'";
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
				$q = "UPDATE `".BROKER_ATTACH."` SET `is_delete`='1' WHERE `id`='".$id."'";
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
        public function get_broker_changes($id){
			$return = array();
			$q = "SELECT `at`.*,u.first_name as user_initial
					FROM `".BROKER_HISTORY."` AS `at`
                    LEFT JOIN `".USER_MASTER."` as `u` on `u`.`id`=`at`.`modified_by`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."'";
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
        public function get_payout_schedule(){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".BROKER_PAYOUT_SCHEDULE."` AS `at`
                    WHERE `at`.`is_delete`='0'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function get_broker_doc_name(){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".BROKER_DOCUMENT_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function get_charges_name($id){
			$return = array();
			$q = "SELECT `at`.*,`ct`.charge_type,`cn`.charge_name
					FROM `".CHARGE_DETAIL."` AS `at`
                    LEFT JOIN `".CHARGE_TYPE_MASTER."` as `ct` on `ct`.`charge_type_id`=`at`.`charge_type_id` 
                    LEFT JOIN `".CHARGE_NAME_MASTER."` as `cn` on `cn`.`charge_name_id`=`at`.`charge_name_id`
                    WHERE `at`.`charge_detail_id`='".$id."'";
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
         
		public function select(){
			$return = array();
			
			$q = "SELECT `at`.*,`bg`.`u4`
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BROKER_GENERAL."` AS `bg` on `bg`.`broker_id`=`at`.`id`
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
        public function select_percentage(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `ft_percentage_detail` AS `at`
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
        public function edit_payout($id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".BROKER_PAYOUT_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."'";
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function edit_payout_fixed_rates($id){
			$return = array();
			
			$q = "SELECT `at`.*,`pt`.`type`
					FROM `".PAYOUT_FIXED_RATES."` AS `at`
                    LEFT JOIN `".PRODUCT_TYPE."` AS `pt` on `pt`.`id`=`at`.`category_id`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."'";
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function edit_grid($id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".BROKER_PAYOUT_GRID."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."'";
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function edit_split($id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".BROKER_PAYOUT_SPLIT."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."'";
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function edit_alias($id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".BROKER_ALIAS."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."'";
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function edit_override($id){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".BROKER_PAYOUT_OVERRIDE."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function get_previous_broker($id){
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
        public function get_next_broker($id){
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
        public function select_register(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".REGISTER_MASTER."` AS `at`
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
                    WHERE `s`.`status`='1' and `s`.`is_delete`='0'
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
        public function select_branch_office(){
			$return = array();
			
			$q = "SELECT `bo`.*
					FROM `".BRANCH_OFFICE_MASTER."` AS `bo`
                    WHERE `bo`.`status`='1' and `bo`.`is_delete`='0'
                    ORDER BY `bo`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function select_state_new(){
			$return = array();
			
			$q = "SELECT `s`.*
					FROM `".STATE_MASTER."` AS `s`
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
        /**
		 * @param int status, default all
		 * @return get state for general information
		 * */
      
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
        public function edit_general($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".BROKER_GENERAL."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."' "; 
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function edit_charge_check($id){
			$return = array();
			$q = "SELECT `at`.`none_check`
					FROM `ft_charge_value` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."' "; 
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function edit_licences_securities($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".BROKER_LICENCES_SECURITIES."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."' "; 
           
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
               
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        public function edit_licences_insurance($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".BROKER_LICENCES_INSURANCE."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."' "; 
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
               
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        public function edit_licences_ria($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".BROKER_LICENCES_RIA."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."' "; 
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
               
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        public function edit_registers($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".BROKER_REGISTER_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."' "; 
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function edit_required_docs($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".BROKER_REQ_DOC."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."' "; 
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function edit_branches($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".BROKER_BRANCHES."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."' "; 
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function check_broker_commission_status($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".BROKER_PAYOUT_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$id."' "; 
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
                $q = "UPDATE `".BROKER_LICENCES_SECURITIES."` SET `is_delete`='1' WHERE `broker_id`='".$id."'";
				$res = $this->re_db_query($q);
                $q = "UPDATE `".BROKER_LICENCES_RIA."` SET `is_delete`='1' WHERE `broker_id`='".$id."'";
				$res = $this->re_db_query($q);
                $q = "UPDATE `".BROKER_REGISTER_MASTER."` SET `is_delete`='1' WHERE `broker_id`='".$id."'";
				$res = $this->re_db_query($q);
                $q = "UPDATE `".BROKER_LICENCES_INSURANCE."` SET `is_delete`='1' WHERE `broker_id`='".$id."'";
				$res = $this->re_db_query($q);
                $q = "UPDATE `".BROKER_REQ_DOC."` SET `is_delete`='1' WHERE `broker_id`='".$id."'";
				$res = $this->re_db_query($q);
                $q = "UPDATE `".BROKER_GENERAL."` SET `is_delete`='1' WHERE `broker_id`='".$id."'";
				$res = $this->re_db_query($q);
                $q = "DELETE FROM`".CHARGE_VALUE."` WHERE `broker_id`='".$id."'";
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
        public function select_charge_type(){
			$return = array();
			
			$q = "SELECT * FROM `".CHARGE_TYPE_MASTER."`";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function select_charge_name($charge_type_id){
			$return = array();
			
			$q = "SELECT cnm.* FROM `".CHARGE_NAME_MASTER."` cnm, `".CHARGE_DETAIL."` cd 
                WHERE cnm.charge_name_id=cd.charge_name_id and cd.charge_type_id='".$charge_type_id."' group by cnm.charge_name_id";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function select_charge_detail($charge_type_id,$charge_name_id){
			$return = array();
			
			$q = "SELECT cd.* FROM `".CHARGE_DETAIL."` cd 
                WHERE cd.charge_type_id='".$charge_type_id."' and cd.charge_name_id='".$charge_name_id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function select_broker_charge($id){
			$return = array();
			$q="SELECT ctm.charge_type_id,cnm.charge_name_id,cd.account_type,cd.account_process,cv.value FROM `".CHARGE_TYPE_MASTER."` ctm, `".CHARGE_NAME_MASTER."` cnm, `".CHARGE_DETAIL."` cd, `".CHARGE_VALUE."` cv WHERE ctm.charge_type_id=cd.charge_type_id and cnm.charge_name_id=cd.charge_name_id and cd.charge_detail_id=cv.charge_detail_id and cv.broker_id='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return[$row['charge_type_id']][$row['charge_name_id']][$row['account_type']][$row['account_process']]=$row['value'];
    			}
            }//echo "<pre>"; print_r($return);exit;
			return $return;
		}
        
    }
?>