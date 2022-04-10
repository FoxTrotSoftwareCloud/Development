<?php
    require_once("include/namepars.php");

    class import extends db{
		public $errors = '';
        public $table = IMPORT_CURRENT_FILES;
        public $GENERIC_file_type = 9;
        public $IMPORT_File_Types = [1=>'DST CLIENTS', 2=>'DST IDC COMMISSIONS', 3=>'DST PRODUCTS', 9=>'GENERIC CSV COMMISSIONS'];
        
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
			} else if($user_name==''){
				$this->errors = 'Please enter user name.';
			} else if($password=='' && $id==0){
				$this->errors = 'Please enter password.';
			} else if($password!='' && $confirm_password==''){
				$this->errors = 'Please confirm password.';
			} else if($password!=$confirm_password){
				$this->errors = 'Confirm password must be same as password.';
			} else if($status==''){
				$this->errors = 'Please select status.';
			} else if($ftp_file_type==''){
				$this->errors = 'Please select file type.';
			}
            
            if($this->errors!=''){
				return $this->errors;
			} else{
                /* check duplicate record */
    			$con = '';
	    		
                if($id>0){
		    		$con = " AND `id`!='".$id."'";
			    }
			
                $q = "SELECT * FROM `".IMPORT_FTP_MASTER."` WHERE `is_delete`=0 AND `user_name`='".$user_name."' ".$con;
			    $res = $this->re_db_query($q);
			    $return = $this->re_db_num_rows($res);
			
                if($return>0){
			    	$this->errors = 'This user is already exists.';
			    }

			    if($this->errors!=''){
				    return $this->errors;
			    } else if($id>=0){
				    if($id==0){
                        $q = "INSERT INTO `".IMPORT_FTP_MASTER."` SET `host_name`='".$host_name."',`user_name`='".$user_name."',`password`='".$this->encryptor($password)."',`folder_location`='".$folder_location."',`status`='".$status."',`ftp_file_type`='".$ftp_file_type."'".$this->insert_common_sql();
                        $res = $this->re_db_query($q);
                        $id = $this->re_db_insert_id();

                        if($res){
                            $_SESSION['success'] = INSERT_MESSAGE;
                            return true;
    					} else{
						    $_SESSION['warning'] = UNKWON_ERROR;
						    return false;
					    }
				    } else if($id>0){
				        $con = '';
                        $id = (int)$id;

					    if($password!=''){
						    $con .= " , `password`='".$this->encryptor($password)."' ";
					    }

				        $q = "UPDATE `".IMPORT_FTP_MASTER."` SET `host_name`='".$host_name."',`user_name`='".$user_name."',`folder_location`='".$folder_location."',`status`='".$status."',`ftp_file_type`='".$ftp_file_type."' ".$con." ".$this->update_common_sql()." WHERE `id`=$id";
                        $res = $this->re_db_query($q);
					    
                        if($res){
					        $_SESSION['success'] = UPDATE_MESSAGE;
						    return true;
                        } else{
						    $_SESSION['warning'] = UNKWON_ERROR;
						    return false;
					    }
				    }
			    } else{
				    $_SESSION['warning'] = UNKWON_ERROR;
				    return false;
			    }
            }
		}

        /** Resolve Exceptions and Update the Exception table:
         *  Resolve_Action:
         *      1) Place Hold,
         *      2) Add to Cloudfox(dependent on what's not found- Broker, Client, or Product)
         *      3) Reassign TRADE to another Broker/Client
         *      4) Delete/Skip Record
         * @param mixed $data
         * @return mixed
         */
        public function resolve_exceptions($data){
            $instance_manage_sponsor = new manage_sponsor();
            $result = $resolveAction = 0;
            $resultMessage = '';
			$exception_file_id = isset($data['exception_file_id']) ? (int)$this->re_db_input($data['exception_file_id']) : 0;
            $exception_file_type = isset($data['exception_file_type']) ? (int)$this->re_db_input($data['exception_file_type']) : 0;
            $error_code_id = isset($data['error_code_id'])? (int)$this->re_db_input($data['error_code_id']) : 0;
            $exception_data_id = isset($data['exception_data_id']) ? (int)$this->re_db_input($data['exception_data_id']) : 0;
            $exception_field = isset($data['exception_field']) ? $this->re_db_input($data['exception_field']) : '';
            $exception_record_id = isset($data['exception_record_id']) ? $this->re_db_input($data['exception_record_id']) : '';
            
            // Populate "Exception Value" per Exception Field
            if($exception_field == 'u5'){
                $exception_value = isset($data['exception_value_date'])?$this->re_db_input($data['exception_value_date']):'';
            } else if($exception_field == 'social_security_number'){
                if($exception_file_type==1){
                    // Skip error for missing SSN. Field will be empty
                    $exception_value = $exception_record_id;
                } else {
                    $exception_value = isset($data['social_security_number'])?$this->re_db_input($data['social_security_number']):'';
                }
            } else if($exception_field == 'active'){
                $exception_value = isset($data['active'])?$this->re_db_input($data['active']):'';
            } else if($exception_field == 'active_check'){
                $exception_value = isset($data['exception_field'])?$this->re_db_input($data['exception_field']):'';
            } else if($exception_field == 'status'){
                $exception_value = isset($data['status'])?$this->re_db_input($data['status']):'';
            } else if($exception_field == 'objectives'){
                if ($exception_file_type==2 AND $error_code_id==9){
                    // Objectives is not populated for IDC file, so just default it to the exception record id for $data validation
                    $exception_value = $exception_record_id;
                } else {
                    $exception_value = isset($data['objectives'])?$this->re_db_input($data['objectives']):'';
                }
            } else if($exception_field == 'sponsor'){
                $exception_value = isset($data['sponsor'])?$this->re_db_input($data['sponsor']):'';
            } else if($exception_field == 'cusip_number' && $error_code_id == '13'){
                $exception_value = isset($data['cusip_number'])?$this->re_db_input($data['cusip_number']):'';
            } else if($exception_field == 'cusip_number' && $error_code_id == '11'){
                $exception_value = isset($data['assign_cusip_number'])?$this->re_db_input($data['assign_cusip_number']):'';
            } else if($exception_field == 'alpha_code'){
                $exception_value = isset($data['alpha_code'])?$this->re_db_input($data['alpha_code']):'';
            } else if($exception_field == 'cusip_number'){
                $exception_value = isset($data['existing_cusip_number'])?$this->re_db_input($data['existing_cusip_number']):'';
            } else if($exception_field == 'ticker_symbol'){
                $exception_value = isset($data['existing_ticker_symbol'])?$this->re_db_input($data['existing_ticker_symbol']):'';
            } else if($exception_field == 'major_security_type' && $error_code_id == '17'){
                $exception_value = isset($data['assign_cusip_product_category'])?$this->re_db_input($data['assign_cusip_product_category']):'';
            } else {
                $exception_value = isset($data['exception_value'])?$this->re_db_input($data['exception_value']):'';
            }
            
            // Form-User Inputs
            $rep_for_broker = (isset($data['rep_for_broker']) ? (int)$this->re_db_input($data['rep_for_broker']) : 0);
            $acc_for_client = (isset($data['acc_for_client']) ? (int)$this->re_db_input($data['acc_for_client']) : 0);
            $skipException = (isset($data['skip_exception']) ? (int)$this->re_db_input($data['skip_exception']) : 0);
            // Element is used for more than just "Broker Terminated" exceptions (radio button input element)
            if(isset($data['resolve_broker_terminated'])){
                $resolveAction = (int)$data['resolve_broker_terminated'];
            }
            
            // Some Exceptions only have one Action. Let the program know what "action" to take. flagged in import.tpl.php
            if(!empty($data['resolveAction'])){
                $resolveAction = (int)$data['resolveAction'];
            }
            
            if($skipException){
                // Delete/Skip Exception
                $resolveAction = 4;
                $exception_value = (empty($exception_value) ? 'delete exception' : $exception_value);
            }
            
            // Validate Exception Field 
			if($exception_value==''){
				$this->errors = 'Please enter field value.';
			}
            if($exception_field == 'representative_number' AND $exception_value == '' AND empty($rep_for_broker))
            {
                $this->errors = 'Please enter rep number.';
            }
            if(($exception_field == 'representative_number' OR ($exception_field == 'u5' AND $resolveAction == 3)) AND empty($rep_for_broker))
            {
                $this->errors = 'Please select broker.';
            }
            if($exception_field == 'customer_account_number' AND $exception_value == '' AND $error_code_id!=13 AND empty($acc_for_client))
            {
                $this->errors = 'Account number blank, Please enter client for account number.';
            }
            if($exception_field == 'customer_account_number' AND empty($acc_for_client))
            {
                $this->errors = 'Please select client.';
            }
            if($exception_file_type == 3 AND $error_code_id == 13)
            {
                // Adding product, make sure all the fields are populated
                $resultMessage = '';
                
                if (empty($exception_value)){
                    $resultMessage .= 'Please enter Fund Name.';
                }
                if (empty($data['cusip_number'])){
                    $resultMessage .= ' Please enter CUSIP number.';
                }
                if (empty($data['assign_cusip_product_category'])){
                    $resultMessage .= ' Please select a Product Category.';
                }

                if ($resultMessage != ''){
                    $this->errors = $resultMessage;
                }
            }

            if($this->errors!=''){
				return $this->errors;
			}

            //--- CLIENT FILE ---//
            if($exception_file_type == '1'){
                // Broker Not Found
                if ($exception_field == 'representative_number' AND $error_code_id == 1) {
                    // Add alias to specified rep
                    $result = $this->resolve_exception_2AddAlias($exception_value, $rep_for_broker, $exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                }
                // Terminated Broker
                else if($exception_field == 'u5' OR $error_code_id == 2){

                    if($resolveAction == 2){
                        $result = $this->resolve_exception_2Reactivate($exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id, $error_code_id);
                    }
                    else if($resolveAction == 3){
                        $result = $this->resolve_exception_3Reassign('broker_id', $rep_for_broker, $exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                    }
                    else if($resolveAction == 4){
                        $result = $this->resolve_exception_4Delete($exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                    }
                }
                // Missing Data - Broker #
                else if($exception_field == 'representative_number' AND $error_code_id == 13){
                    $result = $this->resolve_exception_3Reassign('broker_id', $rep_for_broker, $exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                }
                else if($exception_field == 'social_security_number'){
                    if($resolveAction == 4){
                        $result = $this->resolve_exception_4Delete($exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                    } else if(!empty($acc_for_client)){
                        $result = $this->resolve_exception_3Reassign('client_id', $acc_for_client, $exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                    } else {
                        $this->errors = 'Please select a resolution.';
                    }

                }
                //*** Exceptions not populated in Process/Reprocess. Remove later if not found to be useful 3/17/22 ***//
                // else if($exception_field == 'registration_line1'){
                //     $client_name_array = explode(' ',$exception_value);
                //     if(isset($client_name_array[2]) && $client_name_array[2] != '')
                //     {
                //         $first_name = isset($client_name_array[0])?$this->re_db_input($client_name_array[0]):'';
                //         $middle_name = isset($client_name_array[1])?$this->re_db_input($client_name_array[1]):'';
                //         $last_name = isset($client_name_array[2])?$this->re_db_input($client_name_array[2]):'';
                //     }
                //     else
                //     {
                //         $first_name = isset($client_name_array[0])?$this->re_db_input($client_name_array[0]):'';
                //         $middle_name = '';
                //         $last_name = isset($client_name_array[1])?$this->re_db_input($client_name_array[1]):'';
                //     }

                //     $q = "SELECT * FROM `".CLIENT_MASTER."` WHERE `is_delete`=0 AND `first_name`='".$first_name."' AND `mi`='".$middle_name."' AND `last_name`='".$last_name."'";
                //     $res = $this->re_db_query($q);
                //     $return = $this->re_db_num_rows($res);
                //     if($return>0)
                //     {
                //         $this->errors = 'Client already exist.';
                //     }
                // }
                // else if($exception_field == 'mutual_fund_customer_account_number'){
                //     $q = "SELECT * FROM `".CLIENT_ACCOUNT."` WHERE `is_delete`=0 AND `account_no`='".$exception_value."' ";
                //     $res = $this->re_db_query($q);
                //     $return = $this->re_db_num_rows($res);
                //     if($return>0)
                //     {
                //         $this->errors = 'Account number already exist.';
                //     }
                // }
                
                if($this->errors!='')
                {
                    return $this->errors;
                }
            }

            //--- IDC EXCEPTIONS ---//
            if(isset($exception_file_type) && in_array($exception_file_type, ['2', $this->GENERIC_file_type])) {
                if ($exception_file_type == $this->GENERIC_file_type){
                    $commDetailTable = IMPORT_GEN_DETAIL_DATA;
                } else {
                    $commDetailTable = IMPORT_IDC_DETAIL_DATA;
                }
                //--- 02/03/22 Active Broker Licence check added to this section($exception_field=='active_check').
                //--- Populates the "resolve_broker_terminated" elements in "import.tpl.php". Re-activate Rep (resolve_broker_terminated==2) is compleletely different --//
                if(in_array($exception_field, ['u5', 'active_check', 'objectives']))
                {
                    if(!isset($data['resolve_broker_terminated'])||$data['resolve_broker_terminated']=='')
                    {
                        $this->errors = 'Please select one option.';
                        return $this->errors;
                    }
                    else{
                        if($resolveAction == 1)
                        {
                            // Put HOLD on trade
                            $q = "UPDATE `".$commDetailTable."`"
                                ." SET `on_hold`=1"
                                        .$this->update_common_sql()
                                ." WHERE `file_id`=".$exception_file_id
                                    ." AND `id`=".$exception_data_id
                            ;
                            $res = $this->re_db_query($q);

                            if($res)
                            {
                                // Update "resolve_exceptions" field to flag detail as "special handling" record
                                $res = $this->read_update_serial_field($commDetailTable, "WHERE `file_id`=$exception_file_id AND `id`=".$exception_data_id, 'resolve_exceptions', $exception_record_id);

                                $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                    ." SET `resolve_action`=1"
                                        .$this->update_common_sql()
                                    ." WHERE `id`=".$exception_record_id
                                ;
                                $res = $this->re_db_query($q);

                                $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $exception_data_id);
                            }
                        }
                        else if($resolveAction == 2)
                        {   // ADD\UPDATE CloudFox Broker\Client\Product
                            if ($exception_field=='u5'){
                                $result = $this->resolve_exception_2Reactivate($exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id, $error_code_id);
                            } else if ($exception_field=='active_check'){
                                // UPDATE BROKER LICENSING: Broker License Error
                                $result = 0;
                                //--- Broker Licence Error - Activate Licence ---//
                                $instance_client = new client_maintenance();
                                $instance_product = new product_maintenance();
                                $instance_broker = new broker_master();
                                
                                switch ($commDetailTable){
                                    case IMPORT_IDC_DETAIL_DATA:
                                        $idcDetailRow = $this->select_existing_idc_data($exception_data_id);
                                        break;
                                    case IMPORT_GEN_DETAIL_DATA:
                                        $idcDetailRow = $this->select_existing_gen_data($exception_data_id);
                                        break;
                                }
                                
                                if (empty($idcDetailRow['product_id'])){
                                    $productDetail = $instance_product->product_list_by_query("`is_delete`=0 AND `cusip` = '".$instance_client->re_db_input($idcDetailRow['cusip_number'])."'");
                                } else {
                                    $productDetail = $instance_product->edit_product($idcDetailRow['product_id']);
                                }
                                
                                $clientDetail = $instance_client->get_client_name($idcDetailRow['client_id']);
                                $licenceDetail = $this->checkStateLicence($idcDetailRow['broker_id'], $clientDetail[0]['state'], $productDetail['category'], $idcDetailRow['trade_date'], 1);

                                if (!empty($licenceDetail['licence_table']) AND !empty($licenceDetail['state_id'])){
                                    // ADD/UPDATE the existing licence record
                                    $licenceDetail['id'] = $licenceDetail['licence_id'];
                                    $licenceDetail['active_check'] = 1;
                                    // Date checks - Received & Terminated
                                    if ($idcDetailRow['trade_date']<$licenceDetail['received']){
                                        $licenceDetail['received'] = $idcDetailRow['trade_date'];
                                    }

                                    if ($idcDetailRow['trade_date']>$licenceDetail['terminated']){
                                        $licenceDetail['terminated'] = $idcDetailRow['trade_date'];
                                    }

                                    // Product Type field
                                    switch ($licenceDetail['licence_table']) {
                                        case 'BROKER_LICENCES_SECURITIES':
                                            $licenceDetail['type_of_licences'] = 1;
                                            break;
                                        case 'BROKER_LICENCES_INSURANCE':
                                            $licenceDetail['type_of_licences'] = 2;
                                            break;
                                        default:
                                            $licenceDetail['type_of_licences'] = 3;
                                            break;
                                    }
                                    // Do the Deed!
                                    if ($licenceDetail['id']){
                                        $q = "UPDATE `".constant($licenceDetail['licence_table'])."`"
                                                ." SET"
                                                    ." `broker_id`=".$licenceDetail['broker_id']
                                                    .",`type_of_licences`=".$licenceDetail['type_of_licences']
                                                    .",`state_id`=".$licenceDetail['state_id']
                                                    .",`active_check`=".$licenceDetail['active_check']
                                                    .",`received` = '".$licenceDetail['received']."'"
                                                    .",`terminated`= '".$licenceDetail['terminated']."'"
                                                    .$this->update_common_sql()
                                                ." WHERE `id`=".$licenceDetail['id']
                                        ;
                                        $result = $this->re_db_query($q);
                                    } else {
                                        $q = "INSERT INTO `".constant($licenceDetail['licence_table'])."`"
                                                ." SET"
                                                    ." `broker_id`=".$licenceDetail['broker_id']
                                                    .",`type_of_licences`=".$licenceDetail['type_of_licences']
                                                    .",`state_id`=".$licenceDetail['state_id']
                                                    .",`active_check`=".$licenceDetail['active_check']
                                                    .",`received` = '".$licenceDetail['received']."'"
                                                    .",`terminated` = '".$licenceDetail['terminated']."'"
                                                    .$this->insert_common_sql()
                                        ;
                                        $result = $this->re_db_query($q);
                                    }

                                    if ($result){
                                        // Update "resolve_exceptions" field to flag detail as "special handling" record
                                        $res = $this->read_update_serial_field($commDetailTable, "WHERE `file_id`=$exception_file_id AND `id`=".$exception_data_id, 'resolve_exceptions', $exception_record_id);

                                        $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                                ." SET `resolve_action`=2"
                                                    .",`resolve_assign_to`='LicenseType:".$licenceDetail['type_of_licences'].", State:".$licenceDetail['state_id']."'"
                                                        .$this->update_common_sql()
                                                ." WHERE `id`=".$exception_record_id
                                        ;
                                        $res = $this->re_db_query($q);

                                        $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $exception_data_id);

                                        // Check if this update can clear other exceptions in the file
                                        // Clear all the Exceptions for the same Exception and Broker #
                                        $q = "SELECT `ex`.`id` AS `exception_id`, `ex`.`temp_data_id`, `ex`.`error_code_id`, `ex`.`field`, `det`.`broker_id`"
                                                ." FROM `".IMPORT_EXCEPTION."` `ex`"
                                                ." LEFT JOIN `".$commDetailTable."` `det` ON `det`.`id`=`ex`.`temp_data_id`"
                                                ." WHERE `ex`.`file_id`=$exception_file_id"
                                                ." AND `ex`.`file_type`=$exception_file_type"
                                                ." AND `ex`.`error_code_id`=$error_code_id"
                                                ." AND `ex`.`is_delete`=0"
                                                ." AND `det`.`broker_id`=".$licenceDetail['broker_id']
                                        ;
                                        $res = $this->re_db_query($q);

                                        if ($this->re_db_num_rows($res)) {
                                            $excArray = $this->re_db_fetch_all($res);

                                            foreach ($excArray AS $excRow){
                                                $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $excRow['temp_data_id']);
                                            }
                                        }
                                    }

                                } else {
                                    $this->errors = 'Licence update failed - Product Category not found.';
                                }
                            } else if($exception_field == 'objectives') {
                                // Commission Client Objectives <> Product Objectives - add Objective to Client
                                $instance_client = new client_maintenance();
                                
                                switch ($commDetailTable){
                                    case IMPORT_IDC_DETAIL_DATA:
                                        $idcDetail = $this->select_existing_idc_data($exception_data_id);
                                        break;
                                    case IMPORT_GEN_DETAIL_DATA:
                                        $idcDetail = $this->select_existing_gen_data($exception_data_id);
                                        break;
                                }

                                $res = $instance_client->insert_update_objectives(['client_id'=>$idcDetail['client_id'], 'objectives'=>$data['objectives']], 1);

                                if($res){
                                    // Update "resolve_exceptions" field to flag detail as "special handling" record
                                    $res = $this->read_update_serial_field($commDetailTable, "WHERE `file_id`=$exception_file_id AND `id`=".$exception_data_id, 'resolve_exceptions', $exception_record_id);

                                    $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                            ." SET `resolve_action`=2"
                                                .",`resolve_assign_to`='Client:".$idcDetail['client_id'].", Objective:".$data['objectives']."'"
                                                    .$this->update_common_sql()
                                            ." WHERE `id`=".$exception_record_id
                                    ;
                                    $res = $this->re_db_query($q);

                                    if ($res){
                                        $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $exception_data_id);

                                        // Check if this update can clear other exceptions in the file
                                        // Clear all the Exceptions for the same Exception and Client #
                                        $q = "SELECT `ex`.`id` AS `exception_id`, `ex`.`temp_data_id`, `ex`.`error_code_id`, `ex`.`field`, `det`.`client_id`"
                                                ." FROM `".IMPORT_EXCEPTION."` `ex`"
                                                ." LEFT JOIN `".$commDetailTable."` `det` ON `det`.`id`=`ex`.`temp_data_id`"
                                                ." WHERE `ex`.`file_id`=$exception_file_id"
                                                ." AND `ex`.`file_type`=$exception_file_type"
                                                ." AND `ex`.`error_code_id`=$error_code_id"
                                                ." AND `ex`.`is_delete`=0"
                                                ." AND `det`.`client_id`=".$idcDetail['client_id']
                                        ;
                                        $res = $this->re_db_query($q);

                                        if ($this->re_db_num_rows($res)) {
                                            $excArray = $this->re_db_fetch_all($res);

                                            foreach ($excArray AS $excRow){
                                                $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $excRow['temp_data_id']);
                                            }
                                        }
                                    }
                                }

                            }
                        }
                        else if($resolveAction == 3)
                        { // REASSIGN Broker/Client
                            if ($exception_field == "objectives"){
                                $result = $this->resolve_exception_3Reassign('client_id', $acc_for_client, $exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                            } else {
                                $result = $this->resolve_exception_3Reassign('broker_id', $rep_for_broker, $exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                            }
                        }
                        else if($resolveAction == 4)
                        {
                            // DELETE/SKIP TRADE
                            $result = $this->resolve_exception_4Delete($exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                        }
                    }

                }
                else if($exception_field == 'representative_number')
                {
                    switch ($error_code_id){
                        case 13:
                            // MISSING DATA: "Rep #\Broker Alias"
                            // -> Assign trade to existing(or newly added) broker
                            $result = $this->resolve_exception_3Reassign('broker_id', $rep_for_broker, $exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                            break;
                        default:
                            // ADD BROKER ALIAS(user-defined -> rep_for_broker)  - "Broker #(ALIAS) not found" exception
                            $result = $this->resolve_exception_2AddAlias($exception_value, $rep_for_broker, $exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                            break;
                    }
                }
                else if(in_array($exception_field, ['customer_account_number', 'alpha_code']))
                {
                    $result = $new_client = 0;
                    $accountNo = '';
                    
                    switch ($error_code_id) {
                        case 13:
                            // MISSING DATA: "Client Account Number"
                            // -> Assign TRADE to existing(or newly added) client
                            $result = $this->resolve_exception_3Reassign('client_id', $acc_for_client, $exception_file_type, $exception_file_id, $exception_data_id, $exception_record_id);
                            break;
                        default:
                            // ACCOUNT NUMBER NOT FOUND - Add Account # to existing client (Error Code: 18)
                            // CLIENT NAME ALREADY EXISTS - (Error Code: 24)
                            $new_client = $acc_for_client;
                            $sponsorId = (int)$this->get_current_file_type($exception_file_id, 'sponsor_id');
                            $accountNo = $exception_value;
                            
                            if($sponsorId == 0 AND $exception_file_type == 2){
                                    $header_detail = $this->get_files_header_detail($exception_file_id,$exception_data_id,$exception_file_type);
                                    $sponsor_detail = $this->get_sponsor_on_system_management_code($header_detail['system_id'],$header_detail['management_code']);
                                    $sponsorId = isset($sponsor_detail['id']) ? (int)$sponsor_detail['id'] : 0;
                            }
                            
                            if ($exception_file_type == 9){
                                $commDetailData = $this->select_existing_gen_data($exception_data_id);
                                // Sponsor found
                                $sponsorId = ($sponsorId==0 ? $commDetailData['sponsor_id'] : $sponsorId);
                                $accountNo = $commDetailData['customer_account_number'];
                            }

                            $q = "INSERT `".CLIENT_ACCOUNT."`"
                                ." SET"
                                    ." `account_no` = '".$accountNo."'"
                                    .",`sponsor_company` = $sponsorId"
                                    .",`client_id` = $new_client"
                                    .$this->insert_common_sql();
                            $res = $this->re_db_query($q);

                            if ($res)
                            {
                                // Update "resolve_exceptions" field to flag detail as "special handling" record
                                $res = $this->read_update_serial_field($commDetailTable, "WHERE `file_id`=$exception_file_id AND `id`=".$exception_data_id, 'resolve_exceptions', $exception_record_id);

                                $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                        ." SET `resolve_action`=2"
                                            .",`resolve_assign_to`='".$new_client."'"
                                                .$this->update_common_sql()
                                        ." WHERE `id`=".$exception_record_id
                                ;
                                $res = $this->re_db_query($q);

                                if ($res){
                                    $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $exception_data_id);

                                    // Check if this update can clear other exceptions in the file
                                    // Clear all the Exceptions for the same Exception and Broker #
                                    $q = "SELECT `ex`.`id` AS `exception_id`, `ex`.`temp_data_id`, `ex`.`error_code_id`, `ex`.`field`, `det`.`customer_account_number`"
                                            ." FROM `".IMPORT_EXCEPTION."` `ex`"
                                            ." LEFT JOIN `".$commDetailTable."` `det` ON `det`.`id`=`ex`.`temp_data_id`"
                                            ." WHERE `ex`.`file_id`=$exception_file_id"
                                            ." AND `ex`.`file_type`=$exception_file_type"
                                            ." AND `ex`.`error_code_id`=$error_code_id"
                                            ." AND `ex`.`is_delete`=0"
                                            ." AND `det`.`customer_account_number`='".$accountNo."'"
                                    ;
                                    $res = $this->re_db_query($q);

                                    if ($this->re_db_num_rows($res)) {
                                        $excArray = $this->re_db_fetch_all($res);

                                        foreach ($excArray AS $excRow){
                                            $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $excRow['temp_data_id']);
                                        }
                                    }
                                }
                            }
                            break;
                    }
                }
                else if($exception_field == 'cusip_number')
                {
                    // Missing CUSIP -> Replace cusip number in the data for processing
                    if ($resolveAction == 5){
                        // Product added
                        $product = (isset($data['assign_cusip_number'])) ? $data['assign_cusip_number'] : 0;
                        $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                ." SET `resolve_action`=5"
                                    .",`resolve_assign_to`='".$product."'"
                                        .$this->update_common_sql()
                                ." WHERE `id`=".$exception_record_id
                        ;
                        $res = $this->re_db_query($q);
                    } else if ($error_code_id == '13') {
                        $q = "UPDATE `".$commDetailTable."` SET `".$exception_field."`='".$exception_value."'".$this->update_common_sql()." WHERE `id`='".$exception_data_id."' and `file_id`='".$exception_file_id."'";
                        $res = $this->re_db_query($q);

                        if ($res) {
                            // Update "resolve_exceptions" field to flag detail as "special handling" record
                            $res = $this->read_update_serial_field($commDetailTable, "WHERE `file_id`=$exception_file_id AND `id`=".$exception_data_id, 'resolve_exceptions', $exception_record_id);

                            $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                    ." SET `resolve_action`=2"
                                        .",`resolve_assign_to`='".$exception_value."'"
                                            .$this->update_common_sql()
                                    ." WHERE `id`=".$exception_record_id
                            ;
                            $res = $this->re_db_query($q);
                        }
                    } else if($error_code_id == '11') {
                        // 11 = Cusip not found -> Update "cusip" field in Products table
                        $product_category = isset($data['assign_cusip_product_category'])?$data['assign_cusip_product_category']:0;
                        $product = isset($data['assign_cusip_product'])?$data['assign_cusip_product']:0;
                        
                        if($product_category <= 0) {
                            $this->errors = 'Please select product category.';
                        } else if($product<= 0) {
                            $this->errors = 'Please select product.';
                        }
                        
                        if($this->errors != '') {
                            return $this->errors;
                        } else {
                            $q = "UPDATE `".PRODUCT_LIST."` SET `cusip`='".$exception_value."'".$this->update_common_sql()." WHERE `id`='".$product."'";
                            $res = $this->re_db_query($q);
                        }

                        if ($res) {
                            // Update "resolve_exceptions" field to flag detail as "special handling" record
                            $res = $this->read_update_serial_field($commDetailTable, "WHERE `file_id`=$exception_file_id AND `id`=".$exception_data_id, 'resolve_exceptions', $exception_record_id);

                            $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                    ." SET `resolve_action`=2"
                                        .",`resolve_assign_to`='".$product."'"
                                            .$this->update_common_sql()
                                    ." WHERE `id`=".$exception_record_id
                            ;
                            $res = $this->re_db_query($q);
                        }
                    }

                    if($res){
                        $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $exception_data_id);

                        // Check if this update can clear other exceptions in the file
                        // Clear all the Exceptions for the same Exception and Client #
                        $q = "SELECT `ex`.`id` AS `exception_id`, `ex`.`temp_data_id`, `ex`.`error_code_id`, `ex`.`field`, `det`.`cusip_number`"
                                ." FROM `".IMPORT_EXCEPTION."` `ex`"
                                ." LEFT JOIN `".$commDetailTable."` `det` ON `det`.`id`=`ex`.`temp_data_id`"
                                ." WHERE `ex`.`file_id`=$exception_file_id"
                                ." AND `ex`.`file_type`=$exception_file_type"
                                ." AND `ex`.`error_code_id`=$error_code_id"
                                ." AND `ex`.`is_delete`=0"
                                ." AND `det`.`cusip_number`='".$exception_value."'"
                        ;
                        $res = $this->re_db_query($q);

                        if ($this->re_db_num_rows($res)) {
                            $excArray = $this->re_db_fetch_all($res);

                            foreach ($excArray AS $excRow){
                                $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $excRow['temp_data_id']);
                            }
                        }
                    }
                }
            }

            //--- SFR Exceptions ---//
            if(isset($exception_file_type) && $exception_file_type == '3') {
                // Security Type not found - user-defined product type entry
                if($error_code_id == '17') {
                    // Validate product category #
                    if (!empty($data['assign_cusip_product_category'])) {
                        $q = "UPDATE `".IMPORT_SFR_DETAIL_DATA."`"
                                ." SET `product_category_id`={$data['assign_cusip_product_category']}"
                                        .$this->update_common_sql()
                                ." WHERE `id`='".$exception_data_id."'"
                        ;
                        $res = $this->re_db_query($q);

                        if ($res){
                            // Update "resolve_exceptions" field to flag detail as "special handling" record
                            $res = $this->read_update_serial_field(IMPORT_SFR_DETAIL_DATA, "WHERE `file_id`=$exception_file_id AND `id`=".$exception_data_id, 'resolve_exceptions', $exception_record_id);

                            $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                    ." SET `resolve_action`=3"
                                        .",`resolve_assign_to`={$data['assign_cusip_product_category']}"
                                            .$this->update_common_sql()
                                    ." WHERE `id`=".$exception_record_id
                            ;
                            $res = $this->re_db_query($q);
                        }

                        if($res){
                            $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $exception_data_id);

                            // Check if this update can clear other exceptions in the file
                            // Clear all the Exceptions for the same Exception and CUSIP #
                            $sfrDetail = $this->select_existing_sfr_data($exception_data_id);

                            $q = "SELECT `ex`.`id` AS `exception_id`, `ex`.`temp_data_id`, `ex`.`error_code_id`, `ex`.`field`, `det`.`cusip_number`"
                                    ." FROM `".IMPORT_EXCEPTION."` `ex`"
                                    ." LEFT JOIN `".IMPORT_SFR_DETAIL_DATA."` `det` ON `det`.`id`=`ex`.`temp_data_id`"
                                    ." WHERE `ex`.`file_id`=$exception_file_id"
                                    ." AND `ex`.`file_type`=$exception_file_type"
                                    ." AND `ex`.`error_code_id`=$error_code_id"
                                    ." AND `ex`.`is_delete`=0"
                                    ." AND `det`.`cusip_number`='".$sfrDetail['cusip_number']."'"
                            ;
                            $res = $this->re_db_query($q);

                            if ($this->re_db_num_rows($res)) {
                                $excArray = $this->re_db_fetch_all($res);

                                foreach ($excArray AS $excRow){
                                    $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $excArray['temp_data_id']);
                                }
                            }
                        }
                    }
                }

                if($error_code_id == '13') {
                    // Missing data - Name/Cusip/Product Category
                    $q = "UPDATE `".IMPORT_SFR_DETAIL_DATA."`"
                        ." SET `fund_name` = '".$this->re_db_input(strtoupper($exception_value))."'"
                            .", `product_category_id`={$data['assign_cusip_product_category']}"
                            .", `cusip_number`='".$this->re_db_input($data['cusip_number'])."'"
                                .$this->update_common_sql()
                        ." WHERE `id`='".$exception_data_id."'"
                        ;
                    $res = $this->re_db_query($q);

                    if ($res){
                        // Update "resolve_exceptions" field to flag detail as "special handling" record
                        $res = $this->read_update_serial_field(IMPORT_SFR_DETAIL_DATA, "WHERE `file_id`=$exception_file_id AND `id`=".$exception_data_id, 'resolve_exceptions', $exception_record_id);

                        $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                ." SET `resolve_action`=3"
                                        .$this->update_common_sql()
                                ." WHERE `id`=".$exception_record_id
                        ;
                        $res = $this->re_db_query($q);
                    }

                    if($res){
                        $result = $this->reprocess_current_files($exception_file_id, $exception_file_type, $exception_data_id);
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
                $_SESSION['warning'] = (empty($resultMessage) ? UNKWON_ERROR : $resultMessage);
                return false;
            }
        }
        
        /*** RESOLVE EXCEPTIONS FUNCTIONS ***/
        /*** Action 2: Add BROKER ALIAS ***/
        function resolve_exception_2AddAlias($brokerAlias='', $brokerId=0, $file_type=0, $file_id=0, $detail_data_id=0, $exception_record_id=0) {
            // ADD BROKER ALIAS(user-defined -> rep_for_broker)  - "Broker #(ALIAS) not found" exception
            $result = $res = 0;

            if ($brokerAlias!='' AND $brokerId AND $file_type AND $file_id AND $detail_data_id AND $exception_record_id) {
                $sponsorId = (int)$this->get_current_file_type($file_id, 'sponsor_id');
                $broker_id = (int)$this->re_db_input($brokerId);
                $importFileTable = '';

                $q = "INSERT INTO `".BROKER_ALIAS."`"
                    ." SET"
                        ." `broker_id`=$broker_id"
                        .",`alias_name`='$brokerAlias'"
                        .",`sponsor_company`=$sponsorId"
                        .",`date`='".date('Y-m-d')."'"
                        .$this->insert_common_sql()
                ;
                $res = $this->re_db_query($q);

                if($res){
                    $res = 0;
                                        
                    switch ($file_type){
                        case 1:
                            $importFileTable = IMPORT_DETAIL_DATA;
                            break;
                        case 2:
                            $importFileTable = IMPORT_IDC_DETAIL_DATA;
                            break;
                        case 3:
                            $importFileTable = IMPORT_SFR_DETAIL_DATA;
                            break;
                        case $this->GENERIC_file_type:
                            $importFileTable = IMPORT_GEN_DETAIL_DATA;
                            break;
                    }

                    // Update "resolve_exceptions" field to flag detail as "special handling" record
                    $res = $this->read_update_serial_field($importFileTable, "WHERE `file_id`=$file_id AND `id`=".$detail_data_id, 'resolve_exceptions', $exception_record_id);

                    $q = "UPDATE `".IMPORT_EXCEPTION."`"
                            ." SET `resolve_action`=2"
                                .",`resolve_assign_to`='$broker_id'"
                                .$this->update_common_sql()
                            ." WHERE `id`=".$exception_record_id
                    ;
                    $res = $this->re_db_query($q);

                    $result = $this->reprocess_current_files($file_id, $file_type, $detail_data_id);
                    
                    if ($result){
                        $q = $res = $excArray = '';
                        $excArray = $this->select_exception_data(0, $exception_record_id);
                        
                        if ($excArray){
                            // Check if this update can clear other exceptions in the file
                            // Clear all the Exceptions for the same Exception and Broker #
                            $q = "SELECT `ex`.`id` AS `exception_id`, `ex`.`temp_data_id`, `ex`.`error_code_id`, `ex`.`field`"
                                ." FROM `".IMPORT_EXCEPTION."` `ex`"
                                ." WHERE `ex`.`file_id`=$file_id"
                                ." AND `ex`.`file_type`=$file_type"
                                ." AND `ex`.`error_code_id`={$excArray[0]['error_code_id']}"
                                ." AND `ex`.`is_delete`=0"
                                ." AND `ex`.`rep`='".$brokerAlias."'"
                            ;
                            $res = $this->re_db_query($q);
                
                            if ($this->re_db_num_rows($res)) {
                                $excArray = $this->re_db_fetch_all($res);
                
                                foreach ($excArray AS $excRow){
                                    $result = $this->reprocess_current_files($file_id, $file_type, $excRow['temp_data_id']);
                                }
                            }
                        }
			        }
                }
            }
            return $result;
        }   
        /*** Action 3: Reactive TERMINATED(u5) Broker ***/
        function resolve_exception_2Reactivate($file_type=0, $file_id=0, $detail_data_id=0, $exception_record_id=0, $error_code_id=0){
            // REMOVE TERMINATED DATE(u5) FOR REP
            $result = $excArray = $excRow = 0;
            
            if($file_type AND $file_id AND $detail_data_id AND $exception_record_id AND $error_code_id){
                $importFileTable = $q = $res = $row = '';

                switch ($file_type){
                    case 1:
                        $importFileTable = IMPORT_DETAIL_DATA;
                        break;
                    case 2:
                        $importFileTable = IMPORT_IDC_DETAIL_DATA;
                        break;
                    case 3:
                        $importFileTable = IMPORT_SFR_DETAIL_DATA;
                        break;
                    case $this->GENERIC_file_type:
                        $importFileTable = IMPORT_GEN_DETAIL_DATA;
                        break;
                }

                $q = "SELECT `broker_id`"
                    ." FROM `".$importFileTable."`"
                    ." WHERE `is_delete`=0"
                    ." AND `id`=".$detail_data_id
                ;
                $res = $this->re_db_query($q);
                $row = $this->re_db_fetch_array($res);

                if (!empty($row['broker_id'])){
                    $q = "UPDATE `".BROKER_GENERAL."`"
                        ." SET `u5`=''"
                                .$this->update_common_sql()
                        ." WHERE `is_delete`=0 AND `broker_id`=".$row['broker_id']
                    ;
                    $res = $this->re_db_query($q);

                    // Program Inconsistent on checking the "Status" of the rep, but change the rep's status to Active(1) to make sure
                    if($res) {
                        $q = "UPDATE `".BROKER_MASTER."`"
                            ." SET `active_status`=1"
                                    .$this->update_common_sql()
                            ." WHERE `is_delete`=0 AND `id`=".$row['broker_id']
                        ;
                        $res = $this->re_db_query($q);
                    }
                
                    // Reprocess the updated record
                    if($res) {
                        // Not needed in Reprocess Current File() so don't save this Exception Update to the Detail Record - 02/10/22
                        $res = $this->read_update_serial_field($importFileTable, "WHERE `file_id`=$file_id AND `id`=".$detail_data_id, 'resolve_exceptions', $exception_record_id);

                        $q = "UPDATE `".IMPORT_EXCEPTION."`"
                            ." SET `resolve_action`=2"
                                .$this->update_common_sql()
                            ." WHERE `id`=".$exception_record_id
                        ;
                        $res = $this->re_db_query($q);

                        $result = $this->reprocess_current_files($file_id, $file_type, $detail_data_id);

                        // Check if this update can clear other exceptions in the file
                        // Clear all the Exceptions for the same Exception and Broker #
                        $q = "SELECT `ex`.`id` AS `exception_id`, `ex`.`temp_data_id`, `ex`.`error_code_id`, `ex`.`field`, `det`.`broker_id`"
                            ." FROM `".IMPORT_EXCEPTION."` `ex`"
                            ." LEFT JOIN `".$importFileTable."` `det` ON `det`.`id`=`ex`.`temp_data_id`"
                            ." WHERE `ex`.`file_id`=$file_id"
                            ." AND `ex`.`file_type`=$file_type"
                            ." AND `ex`.`error_code_id`=$error_code_id"
                            ." AND `ex`.`is_delete`=0"
                            ." AND `det`.`broker_id`=".$row['broker_id']
                        ;
                        $res = $this->re_db_query($q);

                        if ($this->re_db_num_rows($res)) {
                            $excArray = $this->re_db_fetch_all($res);

                            foreach ($excArray AS $excRow){
                                $result = $this->reprocess_current_files($file_id, $file_type, $excRow['temp_data_id']);
                            }
                        }
                    }
                }
            }
            return $result;
        }
        /*** Action 3: Reassign Broker or Client to Trade/Client/Objective ***/
        function resolve_exception_3Reassign($reassign_field='', $newId=0, $file_type=0, $file_id=0, $detail_data_id=0,  $exception_record_id=0){
            $result = 0;
            
            if (in_array($reassign_field,['broker_id','client_id']) AND $newId AND $file_type AND $file_id AND $detail_data_id AND $exception_record_id){
                $res = $q = '';
                
                switch ($reassign_field) {
                    case 'broker_id':
                        $q = "SELECT `id` FROM `".BROKER_MASTER."` WHERE `is_delete`=0 AND `id`=".$newId;
                        break;
                    case 'client_id':
                        $q = "SELECT `id` FROM `".CLIENT_MASTER."` WHERE `is_delete`=0 AND `id`=".$newId;
                        break;
                }
                $res = $this->re_db_query($q);

                if($this->re_db_num_rows($res)) {
                    $importFileTable = '';
                    $res = 0;
                                    
                    switch ($file_type){
                        case 1:
                            $importFileTable = IMPORT_DETAIL_DATA;
                            break;
                        case 2:
                            $importFileTable = IMPORT_IDC_DETAIL_DATA;
                            break;
                        case 3:
                            $importFileTable = IMPORT_SFR_DETAIL_DATA;
                            break;
                        case $this->GENERIC_file_type:
                            $importFileTable = IMPORT_GEN_DETAIL_DATA;
                            break;
                    }
                    
                    if ($importFileTable){
                        $q = "UPDATE `".$importFileTable."`"
                            ." SET `".$reassign_field."` = ".$newId
                                    .$this->update_common_sql()
                            ." WHERE `id`=".$detail_data_id
                            ." AND `file_id`=".$file_id
                        ;
                        $res = $this->re_db_query($q);
                    }

                    if($res) {
                        // Update "resolve_exceptions" field to flag detail as "special handling" record
                        $res = $this->read_update_serial_field($importFileTable, "WHERE `file_id`=$file_id AND `id`=".$detail_data_id, 'resolve_exceptions', $exception_record_id);

                        $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                ." SET `resolve_action`=3"
                                    .",`resolve_assign_to` = '".$newId."'"
                                    .$this->update_common_sql()
                                ." WHERE `id`=".$exception_record_id
                        ;
                        $res = $this->re_db_query($q);

                        $result = $this->reprocess_current_files($file_id, $file_type, $detail_data_id);
                    }
                }
            }
            
            return $result;
        }
        /*** Action 4: DELETE the Detail record ***/
        function resolve_exception_4Delete($file_type=0, $file_id=0, $detail_data_id=0, $exception_record_id=0){
            $importFileTable = $q = $res = '';
            $result = 0;
            
            switch ($file_type){
                case 1:
                    $importFileTable = IMPORT_DETAIL_DATA;
                    break;
                case 2:
                    $importFileTable = IMPORT_IDC_DETAIL_DATA;
                    break;
                case 3:
                    $importFileTable = IMPORT_SFR_DETAIL_DATA;
                    break;
                case $this->GENERIC_file_type:
                    $importFileTable = IMPORT_GEN_DETAIL_DATA;
                    break;
            }

            $q = "UPDATE `".$importFileTable."`"
                    ." SET `is_delete` = 1"
                        .",`process_result` = 2"
                            .$this->update_common_sql()
                    ." WHERE `id` = $detail_data_id"
                    ." AND `file_id` = $file_id"
            ;
            $res = $this->re_db_query($q);
            
            if($res){
                $res = $this->read_update_serial_field($importFileTable, "WHERE `file_id`=$file_id AND `id`=".$detail_data_id, 'resolve_exceptions', $exception_record_id);

                $q = "UPDATE `".IMPORT_EXCEPTION."`"
                        ." SET `solved` = 1"
                            .",`is_delete` = 1"
                            .",`resolve_action` = 4"
                            .$this->update_common_sql()
                        ." WHERE `file_id` = $file_id"
                        ." AND `file_type` = $file_type"
                        ." AND `temp_data_id` = $detail_data_id"
                ;
            
                $result = $this->re_db_query($q);
            }
            
            return $result;
        }
        /*** Action 5: ADD  new BROKER or CLIENT ***/
        function resolve_exception_5AddNew($reassign_field='', $newId=0, $exception_record_id=0, $accountFields=[]){
            $result = 0;
            
            if (in_array($reassign_field,['broker_id','client_id']) AND $newId AND $exception_record_id){
                $exceptionData = $this->select_exception_data(0, $exception_record_id);
                
                if ($exceptionData){
                    $res = $q = $file_type = $importFileTable = $file_id = $detail_data_id = '';
                    $file_type = $exceptionData[0]['file_type'];
                    $file_id = $exceptionData[0]['file_id'];
                    $detail_data_id = $exceptionData[0]['temp_data_id'];
                    $error_code_id = $exceptionData[0]['error_code_id'];
                    
                    switch ($reassign_field) {
                        case 'broker_id':
                            $q = "SELECT `id` FROM `".BROKER_MASTER."` WHERE `is_delete`=0 AND `id`=".$newId;
                            break;
                        case 'client_id':
                            $q = "SELECT `id` FROM `".CLIENT_MASTER."` WHERE `is_delete`=0 AND `id`=".$newId;
                            break;
                    }
                    $res = $this->re_db_query($q);
    
                    if($this->re_db_num_rows($res)) {
                        $res = 0;
                                        
                        switch ($file_type){
                            case 1:
                                $importFileTable = IMPORT_DETAIL_DATA;
                                break;
                            case 2:
                                $importFileTable = IMPORT_IDC_DETAIL_DATA;
                                break;
                            case 3:
                                $importFileTable = IMPORT_SFR_DETAIL_DATA;
                                break;
                            case $this->GENERIC_file_type:
                                $importFileTable = IMPORT_GEN_DETAIL_DATA;
                                break;
                        }
                        
                        if ($importFileTable){
                            // If other fields were populated, add that to the SET command as well
                            $setAccountFields = '';
                            
                            if (count($accountFields)){
                                foreach ($accountFields AS $field=>$value){
                                    $setAccountFields .= ", `$field` = '$value'";
                                }                             
                            }
                            
                            $q = "UPDATE `".$importFileTable."`"
                                ." SET `".$reassign_field."` = ".$newId
                                        .$setAccountFields
                                        .$this->update_common_sql()
                                ." WHERE `id`=".$detail_data_id
                                ." AND `file_id`=".$file_id
                            ;
                            $res = $this->re_db_query($q);
                        }
    
                        if($res) {
                            // Update "resolve_exceptions" field to flag detail as "special handling" record
                            $res = $this->read_update_serial_field($importFileTable, "WHERE `file_id`=$file_id AND `id`=".$detail_data_id, 'resolve_exceptions', $exception_record_id);
                            // Flag missing Representative or Client Fund #'s as error code 3, so the Reprocess() will use the "broker_id" or "client_id" field, instead of looking for alternate fund #'s
                            $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                    ." SET `resolve_action`=".($error_code_id==13 ? '3' : '5')
                                        .",`resolve_assign_to` = '".$newId."'"
                                        .$this->update_common_sql()
                                    ." WHERE `id`=".$exception_record_id
                            ;
                            $res = $this->re_db_query($q);
    
                            $result = $this->reprocess_current_files($file_id, $file_type, $detail_data_id);
                            
                            // Check if this update can clear other exceptions in the file
                            // Clear all the Exceptions for the same Exception and Broker #
                            // (Skip "Missing Data(13)" exceptions)
                            if ($error_code_id != 13){
                                $accountQuery = '';
                                
                                if ($reassign_field == 'broker_id'){
                                    $accountQuery = " AND `ex`.`rep` = '{$exceptionData[0]['rep']}'";
                                } else {
                                    $accountQuery = " AND `ex`.`account_no` = '{$exceptionData[0]['account_no']}'";
                                }
                                
                                $q = "SELECT `ex`.`id` AS `exception_id`, `ex`.`temp_data_id`, `ex`.`error_code_id`, `ex`.`field`, `det`.`broker_id`"
                                    ." FROM `".IMPORT_EXCEPTION."` `ex`"
                                    ." LEFT JOIN `".$importFileTable."` `det` ON `det`.`id`=`ex`.`temp_data_id`"
                                    ." WHERE `ex`.`file_id`=$file_id"
                                    ." AND `ex`.`file_type`=$file_type"
                                    ." AND `ex`.`error_code_id`=$error_code_id"
                                    ." AND `ex`.`is_delete`=0"
                                    .$accountQuery
                                ;
                                $res = $this->re_db_query($q);
    
                                if ($this->re_db_num_rows($res)) {
                                    $excArray = $this->re_db_fetch_all($res);
    
                                    foreach ($excArray AS $excRow){
                                        $result = $this->reprocess_current_files($file_id, $file_type, $excRow['temp_data_id']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return $result;
        }
        
        public function select_ftp(){
			$return = array();

			$q = "SELECT `at`.*
					FROM `".IMPORT_FTP_MASTER."` AS `at`
                    WHERE `at`.`is_delete`=0
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
                        WHERE `at`.`is_delete`=0 and `at`.`id`='".$id."'
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
            $id = (int)$id;

			$q = "SELECT `at`.*
					FROM `".IMPORT_FTP_MASTER."` AS `at`
                    WHERE `at`.`is_delete`=0 AND `at`.`id`=$id";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}

        public function ftp_status($id,$status){
			$id = (int)($this->re_db_input($id));
			$status = trim($this->re_db_input($status));

			if($id>0 && ($status==0 || $status==1) ){
				$q = "UPDATE `".IMPORT_FTP_MASTER."` SET `status`='".$status."' WHERE `id`=$id";
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
			$id = (int)($this->re_db_input($id));
			if($id>0){
				$q = "UPDATE `".IMPORT_FTP_MASTER."` SET `is_delete`='1' WHERE `id`=$id";
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
                                $sponsor_array = $this->get_sponsor_on_system_management_code(substr($ext_filename,0,3),substr($ext_filename,3,2));
                                if (empty($sponsor_array)){
                                    $sponsor_array = ['id'=>0, 'name'=>'*Not Found*', 'dst_code'=>substr($ext_filename,0,5)];
                                }
                                $q = "INSERT INTO `".IMPORT_CURRENT_FILES."`"
                                        ." SET "
                                        ."`user_id`='".$_SESSION['user_id']."'"
                                        .",`imported_date`='".date('Y-m-d')."'"
                                        .",`last_processed_date`=''"
                                        .",`file_name`='".$ext_filename."'"
                                        .",`file_type`='".$file_type_array[$file_type_checkkey]."'"
                                        .",`source`='".$source."'"
                                        .",`sponsor_id`=".$sponsor_array['id']
                                        .$this->insert_common_sql();
                                $res = $this->re_db_query($q);
                                $id = $this->re_db_insert_id();
                            }
                            else
                            {
                                $q = "INSERT INTO `".IMPORT_CURRENT_FILES."`"
                                    ." SET "
                                        ."`user_id`='".$_SESSION['user_id']."'"
                                        .",`imported_date`='".date('Y-m-d')."'"
                                        .",`last_processed_date`=''"
                                        .",`file_name`='".$ext_filename."'"
                                        .",`file_type`='-'"
                                        .",`source`=''"
                                        .$this->insert_common_sql();
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
           $file_array = array();
           $file_count = count($file_post['name']);
           $file_keys = array_keys($file_post);
           for ($i=0; $i<$file_count; $i++) {
               foreach ($file_keys as $key) {
                   $file_array[$i][$key] = $file_post[$key][$i];
               }
           }
           return $file_array;
       }

        public function process_current_files($id){
            $sponsorClass = new manage_sponsor();
            $data_status = false;
            
            if($id > 0)
            {
                $q = "SELECT * FROM `".IMPORT_CURRENT_FILES."` WHERE `is_delete`=0 AND `processed`='1' AND `id`='".$id."'; ";
				$res = $this->re_db_query($q);
				$return = $this->re_db_num_rows($res);
                
                // 04/04/22 Quick fix to get the Generic Import up and running.
                if ($return){
                    $return = 0;
                    $res = $this->re_db_fetch_array($res);
                    
                    if ($res['source']=='GENERIC'){
                        $instance_importGeneric = new import_generic();
                        $return = $instance_importGeneric->process_file($res['file_name']);
                    }
                    return $return;
                }
                
                $this->errors='';
                //print_r($return);
				if($return == 0){
                    $file_string_array = array();
                    $get_file = $this->select_user_files($id);
                    $file_name = $get_file['file_name'];
                    $file_path = DIR_FS."import_files/user_".$_SESSION['user_id']."/".$file_name;

                    if(!file_exists($file_path)){
                     $_SESSION['warning'] = " Import File did not existed";

                     return false;
                    }
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

                    // Get SPONSOR INFO for the file - "sponsor_id" should be populated in this class->insert_update_file()
                    if (empty($get_file['sponsor_id'])) {
                        $file_sponsor_array = $this->get_sponsor_on_system_management_code(substr($file_name_array[0],0,3), substr($file_name_array[0],3,2));
                    } else {
                        $file_sponsor_array = $sponsorClass->select_sponsor_by_id((int)$get_file['sponsor_id']);
                    }

                    if (count($file_sponsor_array)==0){
                        $file_sponsor_array['id'] = 0;
                        $file_sponsor_array['name'] = '';
                    }

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

                                        $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("record_type3" => substr($val_string, 0, 3),"sequence_number3" => substr($val_string, 3, 3),"account_registration_line5" => substr($val_string, 6, 35),"account_registration_line6" => substr($val_string, 41, 35),"account_registration_line7" => substr($val_string, 76, 35),"representative_number" => trim(substr($val_string, 111, 22)),"representative_name" => substr($val_string, 133, 17),"position_close_out_indicator" => substr($val_string, 150, 1),"account_type_indicator" => substr($val_string, 151, 4),"product_identifier_code(VA only)" => substr($val_string, 155, 3),"mutual_fund_alternative_investment_program_managers_variable_annuties_and_VUL" => substr($val_string, 158, 2));

                                    }
                                    else
                                    {
                                        $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("record_type3" => substr($val_string, 0, 3),"sequence_number3" => substr($val_string, 3, 3),"account_registration_line5" => substr($val_string, 6, 35),"account_registration_line6" => substr($val_string, 41, 35),"account_registration_line7" => substr($val_string, 76, 35),"representative_number" => trim(substr($val_string, 111, 9)),"representative_name" => substr($val_string, 120, 30),"position_close_out_indicator" => substr($val_string, 150, 1),"account_type_indicator" => substr($val_string, 151, 4),"product_identifier_code(VA only)" => substr($val_string, 155, 3),"mutual_fund_alternative_investment_program_managers_variable_annuties_and_VUL" => substr($val_string, 158, 2));
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
                                $plh_inserted_id = 0;

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
                                $detail_inserted_id = 0;

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
                                        $q = "UPDATE `".IMPORT_DETAIL_DATA."` SET `record_type2`='".$seq_val['record_type2']."',`sequence_number2`='".$seq_val['sequence_number2']."',`mutual_fund_dividend_mail_account`='".$seq_val['mutual_fund_dividend_mail_account']."',`mutual_fund_stop_purchase_account`='".$seq_val['mutual_fund_stop_purchase_account']."',`stop_mail_account`='".$seq_val['stop_mail_account']."',`mutual_fund_fractional_check`='".$seq_val['mutual_fund_fractional_check']."',`registration_line1`='".$seq_val['registration_line1']."',`registration_line2`='".$seq_val['registration_line2']."',`registration_line3`='".$seq_val['registration_line3']."',`registration_line4`='".$seq_val['registration_line4']."',`customer_date_of_birth`='".(empty(trim($seq_val['customer_date_of_birth'])) ? '' : date('Y-m-d',strtotime($seq_val['customer_date_of_birth'])))."',`mutual_fund_account_price_schedule_code`='".$seq_val['mutual_fund_account_price_schedule_code']."',`unused_002_1`='".$seq_val['unused_detail']."'".$this->update_common_sql()." WHERE `id`='".$detail_inserted_id."'";
                            			$res = $this->re_db_query($q);
                                        $data_status = true;
                                    }
                                    else if($seq_key == 003)
                                    {
                                        $q = "UPDATE `".IMPORT_DETAIL_DATA."` SET `record_type3`='".$seq_val['record_type3']."',`sequence_number3`='".$seq_val['sequence_number3']."',`account_registration_line5`='".$seq_val['account_registration_line5']."',`account_registration_line6`='".$seq_val['account_registration_line6']."',`account_registration_line7`='".$seq_val['account_registration_line7']."',`representative_number`='".trim($seq_val['representative_number'])."',`representative_name`='".$seq_val['representative_name']."',`position_close_out_indicator`='".$seq_val['position_close_out_indicator']."',`account_type_indicator`='".$seq_val['account_type_indicator']."',`product_identifier_code(VA only)`='".$seq_val['product_identifier_code(VA only)']."',`mutual_fund_alternative_investment_program_managers_variable_ann`='".$seq_val['mutual_fund_alternative_investment_program_managers_variable_annuties_and_VUL']."'".$this->update_common_sql()." WHERE `id`='".$detail_inserted_id."'";
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

                    }
                    /*****************************
                     * IMPORT IDC DATA
                    *******************************/
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
                                    $data_array[$array_key]['DETAIL'][$commission_record_type_code][] = array("commission_record_type_code" => substr($val_string, 0, 1),"dealer_number" => substr($val_string, 1, 7),"dealer_branch_number" => substr($val_string, 8, 9),"representative_number" => trim(substr($val_string, 17, 9)),"representative_name" => substr($val_string, 26, 30),"cusip_number" => substr($val_string, 56, 9),"alpha_code" => substr($val_string, 65, 10),"trade_date" => substr($val_string, 75, 8),"gross_transaction_amount" => substr($val_string, 83, 15),"gross_amount_sign_code" => substr($val_string, 98, 1),"dealer_commission_amount" => substr($val_string, 99, 15),"commission_rate" => substr($val_string, 114, 5),"customer_account_number" => substr($val_string, 119, 20),"account_number_type_code" => substr($val_string, 139, 1),"purchase_type_code" => substr($val_string, 140, 1),"social_code" => substr($val_string, 141, 3),"cumulative_discount_number" => substr($val_string, 144, 9),"letter_of_intent(LOI)_number" => substr($val_string, 153, 9),"social_security_number" => substr($val_string, 162, 9),"social_security_number_status_code" => substr($val_string, 171, 1),"transaction_share_count" => substr($val_string, 172, 15),"share_price_amount" => substr($val_string, 187, 9),"resident_state_country_code" => substr($val_string, 196, 3),"dealer_commission_sign_code" => substr($val_string, 199, 1));
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
                                        $q = "INSERT INTO `".IMPORT_IDC_DETAIL_DATA."` SET `file_id`='".$id."',`idc_header_id`='".$rhr_inserted_id."',`commission_record_type_code`='".$seq_val['commission_record_type_code']."',`dealer_number`='".$seq_val['dealer_number']."',`dealer_branch_number`='".$seq_val['dealer_branch_number']."',`representative_number`='".trim($seq_val['representative_number'])."',`representative_name`='".$seq_val['representative_name']."',`cusip_number`='".$seq_val['cusip_number']."',`alpha_code`='".$seq_val['alpha_code']."',`trade_date`='".date('Y-m-d',strtotime($seq_val['trade_date']))."',`gross_transaction_amount`='".($seq_val['gross_transaction_amount']/100)."',`gross_amount_sign_code`='".$seq_val['gross_amount_sign_code']."',`dealer_commission_amount`='".($seq_val['dealer_commission_amount']/100)."',`commission_rate`='".$seq_val['commission_rate']."',`customer_account_number`='".$seq_val['customer_account_number']."',`account_number_type_code`='".$seq_val['account_number_type_code']."',`purchase_type_code`='".$seq_val['purchase_type_code']."',`social_code`='".$seq_val['social_code']."',`cumulative_discount_number`='".$seq_val['cumulative_discount_number']."',`letter_of_intent(LOI)_number`='".$seq_val['letter_of_intent(LOI)_number']."',`social_security_number`='".$seq_val['social_security_number']."',`social_security_number_status_code`='".$seq_val['social_security_number_status_code']."',`transaction_share_count`='".$seq_val['transaction_share_count']."',`share_price_amount`='".$seq_val['share_price_amount']."',`resident_state_country_code`='".$seq_val['resident_state_country_code']."',`dealer_commission_sign_code`='".$seq_val['dealer_commission_sign_code']."'".$this->insert_common_sql();
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
                    }

                    /*** 01/10/22 Do the processing in "process_current_file() ***/
                    if($data_status) {
                        $this->reprocess_current_files($id);
                        $resUpdateCurrentFiles = true;
                    } else
                        $resUpdateCurrentFiles = false;
                }
            }
        }

        /***************************************************************
         * Reprocess EXCEPTIONS for a specific file $file_id
         * 12/13/21 Change to process all records coming into CloudFox
         *      - don't delete Client_Master & Transactions_Master that match the file $file_id
         * 02/15/22 Parameters to pull individual records. Called from this>resolve exception($data) function
         * @param int $file_id
         * @return string|bool
         ***************************************************************/
        public function reprocess_current_files($file_id, $file_type=0, $detail_record_id=0) {
            // $instance_batches = new batches();
            $reprocess_status = false;
            $_SESSION['reprocess_status'] = $reprocess_status;
            $result = 0;
            // Cast $file_id to int, some calling code was sending null and '' arguments from empty "current_file" queries
            $file_id = (int)$file_id;
            $instance_data_interface = new data_interfaces_master();
            $instance_broker_master = new broker_master();
            $instance_manage_sponsor = new manage_sponsor();
            $instance_client_maintenance = new client_maintenance();
            $instance_product_maintenance = new product_maintenance();

            if($file_id > 0){
                $q = "SELECT * FROM `".IMPORT_CURRENT_FILES."` WHERE `is_delete`=0 AND `process_completed`='1' AND `id`=$file_id";
				$res = $this->re_db_query($q);
				$return = $this->re_db_num_rows($res);
                $this->errors='';

                if($return>0){
					$this->errors = 'Data already processed.';
				}

				if($this->errors!=''){
					return $this->errors;
				} else {
                    // File information
                    $q = "SELECT * FROM `".IMPORT_CURRENT_FILES."` WHERE `is_delete`=0 AND `id`=$file_id";
                    $res = $this->re_db_query($q);
                    $file_array = $this->re_db_fetch_array($res);
                    $file_sponsor_array = $instance_manage_sponsor->edit_sponsor($file_array['sponsor_id']);

                    if (empty($file_array['sponsor_id'])) {
                        $file_sponsor_array = $this->get_sponsor_on_system_management_code(substr($file_array['file_name'],0,3), substr($file_array['file_name'],3,2));

                        if (!empty($file_sponsor_array['id'])){
                            $q = "UPDATE `".IMPORT_CURRENT_FILES."` SET `sponsor_id`='".$file_sponsor_array['id']."' WHERE `is_delete`=0 AND `id`=$file_id";
                            $res = $this->re_db_query($q);

                            $file_array['sponsor_id'] = $file_sponsor_array['id'];
                        }
                    }

                    if (empty($file_array['sponsor_id'])){
                        $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                ." SET"
                                    ." `file_id`='".$file_id."'"
                                    .",`error_code_id`='14'"
                                    .",`field`='sponsor_id'"
                                    .",`field_value`='".substr($file_array['file_name'],0,3).substr($file_array['file_name'],3,2)."'"
                                    .",`file_type`=$file_type"
                                    .",`temp_data_id`='0'"
                                    .",`date`='".date('Y-m-d')."'"
                                    .$this->insert_common_sql();
                        $res = $this->re_db_query($q);

                        $file_sponsor_array['id'] = 0;
                        $file_sponsor_array['name'] = '';
                    }

                    // Remove prior exceptions
                    // 02/15/22 Only remove for a specific record, if called from Resolve Exceptions function. So the other exceptions will remain on the Resolve Exceptions page.
                    $detailIdQuery = "";
                    if ($file_type > 0 AND $detail_record_id > 0){
                        $detailIdQuery = " AND `file_type`=$file_type AND `temp_data_id`=$detail_record_id";
                    }

                    $q = "UPDATE `".IMPORT_EXCEPTION."`"
                          ." SET `is_delete`=1"
                                .$this->update_common_sql()
                          ." WHERE `file_id`=$file_id"
                          ." AND `is_delete`=0"
                          ." AND `solved`=0"
                          .$detailIdQuery
                    ;
                    $res = $this->re_db_query($q);

                    $detailIdQuery = '';
                    /*************************************************
                     * DST CLIENT DATA (NFA(07) / NAA(08) / AMP(09))
                     *************************************************/
                    $check_fanmail_array = [];
                    if ($detail_record_id==0 OR $file_type==1) {
                        $check_fanmail_array = $this->get_fanmail_detail_data($file_id, 0, ($file_type==1 AND $detail_record_id>0) ? $detail_record_id : 0);
                    }

                    $dataSettings = $instance_data_interface->edit(2, $_SESSION['user_id']);

                    foreach($check_fanmail_array as $check_data_key=>$check_data_val) {
                        // Flag the record as processed for "Import" file grid to get an accurate count of the processed vs exception records
                        $this->re_db_perform(IMPORT_DETAIL_DATA, ["process_result"=>0], 'update', "`id`=".$check_data_val['id']);

                        $result = $broker_id = 0;
                        $first_name = $middle_name = $last_name = '';
                        $rep_number = isset($check_data_val['representative_number']) ? trim($this->re_db_input($check_data_val['representative_number'])) : '';
                        // Account Number Check
                        $existingAccountArray = $instance_client_maintenance->select_client_by_account_no($this->re_db_input($check_data_val['mutual_fund_customer_account_number']),$file_sponsor_array['id']);
                        $existingAccountExceptionId = 0;
                        $existingSocialArray = array();
                        $existingSocialExceptionId = 0;
                        $social_security_number = '';
                        $field_value = '';

                        // Exception intervention in resolve exception() by user(reassign client OR broker)
                        $reassignBroker = $reassignClient = $reassignClientExceptionId = 0;
                        $exceptionArray = [];

                        if ($check_data_val['resolve_exceptions'] != ''){
                            $exceptionArray = $this->getDetailExceptions($check_data_val['resolve_exceptions']);
                            // Cycle through the "resolves" the user entered to flag any defaults to rep, client, or hold(i.e. pass the exception through)
                            foreach ($exceptionArray AS $errorKey=>$errorRow){
                                if ($exceptionArray[$errorKey]['resolve_action'] == 'reassign' AND in_array($exceptionArray[$errorKey]['field'], ['representative_number', 'u5'])){
                                    // Assign error code so the program knows way it's skipping the Rep #alias search
                                    $reassignBroker = $errorKey;
                                }

                                if ($exceptionArray[$errorKey]['resolve_action']=='reassign' AND in_array($exceptionArray[$errorKey]['field'], ['social_security_number'])){
                                    // Assign error code so the program knows way it's skipping the Client Account # search
                                    $reassignClient = $errorKey;
                                    $reassignClientExceptionId = $exceptionArray[$errorKey]['exception_id'];
                                }
                            }
                        }

                        if($rep_number != '' OR $reassignBroker) {
                            if ($reassignBroker){
                                $broker_id = (int)$check_data_val['broker_id'];
                            } else {
                                $broker_data = $instance_broker_master->select_broker_by_fund($rep_number);
    
                                if($broker_data){
                                    $broker_id = $broker_data['id'];
                                } else {
                                    $broker_data = $instance_broker_master->select_broker_by_alias($rep_number, $file_sponsor_array['id']);
                                    $broker_id = ($broker_data ? $broker_data['broker_id'] : 0);
                                }
                            }

                            if($broker_id == 0 ) {
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                        ." SET"
                                            ." `file_id`='".$check_data_val['file_id']."'"
                                            .",`error_code_id`='1'"
                                            .",`field`='representative_number'"
                                            .",`field_value`='$rep_number'"
                                            .",`file_type`='1'"
                                            .",`temp_data_id`='".$check_data_val['id']."'"
                                            .",`date`='".date('Y-m-d')."'"
                                            .",`rep`='".trim($check_data_val['representative_number'])."'"
                                            .",`rep_name`='".$check_data_val['representative_name']."'"
                                            .",`account_no`='".$check_data_val['mutual_fund_customer_account_number']."'"
                                            .",`client`='".$check_data_val['registration_line1']."'"
                                            .",`cusip`='".$check_data_val['cusip_number']."'"
                                            .$this->insert_common_sql()
                                ;
                                $res = $this->re_db_query($q);
                                $result++;
                            } else {
                                $q = "UPDATE `".IMPORT_DETAIL_DATA."`"
                                        ." SET"
                                            ." `broker_id`=$broker_id"
                                            .$this->update_common_sql()
                                        ." WHERE `id`=".$check_data_val['id']
                                ;
                                $res = $this->re_db_query($q);

                                $check_broker_termination = $this->broker_termination_date('', $broker_id);

                                if($check_broker_termination != '') {
                                    $current_date = date('Y-m-d');

                                    if($current_date > $check_broker_termination) {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                                ." SET"
                                                    ." `file_id`='".$check_data_val['file_id']."'"
                                                    .",`error_code_id`='2'"
                                                    .",`field`='u5'"
                                                    .",`field_value`='".$check_broker_termination."'"
                                                    .",`file_type`='1'"
                                                    .",`temp_data_id`='".$check_data_val['id']."'"
                                                    .",`date`='".date('Y-m-d')."'"
                                                    .",`rep`='".trim($check_data_val['representative_number'])."'"
                                                    .",`rep_name`='".$check_data_val['representative_name']."'"
                                                    .",`account_no`='".$check_data_val['mutual_fund_customer_account_number']."'"
                                                    .",`client`='".$check_data_val['registration_line1']."'"
                                                    .",`cusip`='".$check_data_val['cusip_number']."'"
                                                    .$this->insert_common_sql()
                                        ;
                                        $res = $this->re_db_query($q);
                                        $result++;
                                    }
                                }
                            }
                        } else {
                            $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                    ." SET"
                                        ." `file_id`='".$check_data_val['file_id']."'"
                                        .",`error_code_id`='13'"
                                        .",`field`='representative_number'"
                                        .",`field_value`=''"
                                        .",`file_type`='1'"
                                        .",`temp_data_id`='".$check_data_val['id']."'"
                                        .",`date`='".date('Y-m-d')."'"
                                        .",`rep`='".$rep_number."'"
                                        .",`rep_name`='".$this->re_db_input($check_data_val['representative_name'])."'"
                                        .",`account_no`='".$this->re_db_input($check_data_val['mutual_fund_customer_account_number'])."'"
                                        .",`client`='".$this->re_db_input($check_data_val['registration_line1'])."'"
                                        .",`cusip`='".$check_data_val['cusip_number']."'"
                                        .$this->insert_common_sql();
                            $res = $this->re_db_query($q);
                            $result++;
                        }

                        // Account Number Already Exists
                        if($existingAccountArray AND !$reassignClient){
                            $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                    ." SET"
                                        ." `file_id`='".$check_data_val['file_id']."'"
                                        .",`error_code_id`='12'"
                                        .",`field`='mutual_fund_customer_account_number'"
                                        .",`field_value`='".$check_data_val['mutual_fund_customer_account_number']."'"
                                        .",`file_type`='1'"
                                        .",`temp_data_id`='".$check_data_val['id']."'"
                                        .",`date`='".date('Y-m-d')."'"
                                        .",`rep`='".$this->re_db_input($check_data_val['representative_number'])."'"
                                        .",`rep_name`='".$this->re_db_input($check_data_val['representative_name'])."'"
                                        .",`account_no`='".$this->re_db_input($check_data_val['mutual_fund_customer_account_number'])."'"
                                        .",`client`='".$this->re_db_input($check_data_val['registration_line1'])."'"
                                        .",`cusip`='".$this->re_db_input($check_data_val['cusip_number'])."'"
                                        .$this->insert_common_sql();
                            $res = $this->re_db_query($q);
                            $existingAccountExceptionId = $this->re_db_insert_id();
                            $this->re_db_perform(IMPORT_DETAIL_DATA,["client_id"=>$existingAccountArray[0]["client_id"], "account_no_id"=>$existingAccountArray[0]["account_no_id"]],"update","`id`='".$check_data_val['id']."'");
                            // Don't increment the exception "result" variable, so this record will be checked for updates in the ADD/UPDATE section below
                            // $result++;
                        } else if(!$reassignClient){
                            // SSN Check - skipped if the user chose to Reassign to Exising Client in Resolve Exception()
                            $social_security_number = preg_replace("/[^a-zA-Z0-9]/", "", $check_data_val['social_security_number']);

                            if (empty($social_security_number)) {
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                        ." SET"
                                            ." `file_id`='".$check_data_val['file_id']."'"
                                            .",`error_code_id`='13'"
                                            .",`field`='social_security_number'"
                                            .",`field_value`=''"
                                            .",`file_type`='1'"
                                            .",`temp_data_id`='".$check_data_val['id']."'"
                                            .",`date`='".date('Y-m-d')."'"
                                            .",`rep`='".$this->re_db_input($check_data_val['representative_number'])."'"
                                            .",`rep_name`='".$this->re_db_input($check_data_val['representative_name'])."'"
                                            .",`account_no`='".$this->re_db_input($check_data_val['mutual_fund_customer_account_number'])."'"
                                            .",`client`='".$this->re_db_input($check_data_val['registration_line1'])."'"
                                            .",`cusip`='".$this->re_db_input($check_data_val['cusip_number'])."'"
                                            .$this->insert_common_sql();
                                $res = $this->re_db_query($q);
                                $result++;
                            } else {
                                $q = "SELECT `id`,`last_name`,`first_name`,`client_ssn`,`client_file_number`,`clearing_account`"
                                    ." FROM `".CLIENT_MASTER."`"
                                    ." WHERE `is_delete`=0"
                                    ." AND `active`='0'"
                                    ." AND REPLACE(`client_ssn`,'-','')='".$social_security_number."'"
                                ;
                                $res = $this->re_db_query($q);
                                $existingSocialArray = ($this->re_db_num_rows($res) ? $this->re_db_fetch_all($res) : array());
                            }
                            
                            if ($existingSocialArray) {
                                $field_value = $res = '';
                                // Store a comma separated string of client id's for explode() a list of duplicate of dupclicate client's socials in reports\pages
                                foreach ($existingSocialArray AS $res){
                                    $field_value .= (empty($field_value) ? "" : ",").$this->re_db_input((string)$res['id']);
                                }
                                $res = '';
                                
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                    ." SET"
                                        ." `file_id`='".$check_data_val['file_id']."'"
                                        .",`error_code_id`='19'"
                                        .",`field`='social_security_number'"
                                        .",`field_value`='$field_value'"
                                        .",`file_type`='1'"
                                        .",`temp_data_id`='".$check_data_val['id']."'"
                                        .",`date`='".date('Y-m-d')."'"
                                        .",`rep`='".$this->re_db_input($check_data_val['representative_number'])."'"
                                        .",`rep_name`='".$this->re_db_input($check_data_val['representative_name'])."'"
                                        .",`account_no`='".$this->re_db_input($check_data_val['mutual_fund_customer_account_number'])."'"
                                        .",`client`='".$this->re_db_input($check_data_val['registration_line1'])."'"
                                        .",`cusip`='".$this->re_db_input($check_data_val['cusip_number'])."'"
                                        .$this->insert_common_sql();
                                $res = $this->re_db_query($q);
                                $existingSocialExceptionId = $this->re_db_insert_id();
                                // client_id assigned in Resolve Exceptions 3/12/22
                                // $this->re_db_perform(IMPORT_DETAIL_DATA,["client_id"=>$existingSocialArray[0]['id']],"update","`id`='".$check_data_val['id']."'");
                                $result++;
                            }
                        }

                        /*******************
                         * Enter the Client
                        ********************/
                        if ($result == 0) {
                            $res = $last_inserted_id = $last_inserted_account_no_id = $process_result = 0;
                            
                            $exceptionFields =
                                    ",`file_id`='".$check_data_val['file_id']."'"
                                .",`file_type`='1'"
                                .",`temp_data_id`='".$check_data_val['id']."'"
                                .",`date`='".date('Y-m-d')."'"
                                .",`rep`='".trim($check_data_val['representative_number'])."'"
                                .",`rep_name`='".$check_data_val['representative_name']."'"
                                .",`account_no`='".$check_data_val['mutual_fund_customer_account_number']."'"
                                .",`client`='".$check_data_val['registration_line1']."'"
                                .",`cusip`='".$check_data_val['cusip_number']."'"
                                .$this->insert_common_sql()
                            ;
                            
                            if(isset($check_data_val['registration_line1'])) {
                                $registration_line1 = isset($check_data_val['registration_line1'])?$this->re_db_input($check_data_val['registration_line1']):'';
                                $client_name_array = explode(' ',$registration_line1);

                                if(isset($client_name_array[2]) AND $client_name_array[2] != '') {
                                    $first_name = isset($client_name_array[0])?$this->re_db_input($client_name_array[0]):'';
                                    $middle_name = isset($client_name_array[1])?$this->re_db_input($client_name_array[1]):'';
                                    $last_name = isset($client_name_array[2])?$this->re_db_input($client_name_array[2]):'';
                                } else {
                                    $first_name = isset($client_name_array[0])?$this->re_db_input($client_name_array[0]):'';
                                    $middle_name = '';
                                    $last_name = isset($client_name_array[1])?$this->re_db_input($client_name_array[1]):'';
                                }
                            }
                            
                            $get_client_data = $this->get_client_data($check_data_val['file_id'],$check_data_val['id']);

                            // UPDATE CLIENT - for existing SSN or Account #
                            if ($existingSocialArray OR $existingAccountArray OR $reassignClient){
                                if ($reassignClient){
                                    $client_id = $check_data_val['client_id'];
                                    $exceptionId = $reassignClientExceptionId;
                                } else if ($existingSocialArray){
                                    $client_id = $existingSocialArray[0]['id'];
                                    $exceptionId = $existingSocialExceptionId;
                                } else {
                                    $client_id = $existingAccountArray[0]['client_id'];
                                    $exceptionId = $existingAccountExceptionId;
                                }

                                $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                    ." SET"
                                        ."`error_code_id`=".($dataSettings['update_client'] ? '`error_code_id`' : 23)
                                        .",`solved`='".$dataSettings['update_client']."'"
                                        .",`process_completed`='".$dataSettings['update_client']."'"
                                        .",`is_delete` = 0"
                                        .$exceptionFields
                                    ." WHERE `id`='$exceptionId'"
                                ;
                                $res = $this->re_db_query($q);

                                if ($dataSettings['update_client']){
                                    $updateFields = [
                                        'first_name'=>$first_name,
                                        'mi'=>$middle_name,
                                        'last_name'=>$last_name,
                                        'address1'=>$get_client_data[0]['client_address'],
                                        'birth_date'=>$check_data_val['customer_date_of_birth'],
                                        'zip_code'=>$check_data_val['zip_code'],
                                        'broker_name'=>$broker_id,
                                        'client_ssn'=>$check_data_val['social_security_number'],
                                        'client_file_number'=>$check_data_val['social_security_number'],
                                        'last_contacted'=>$check_data_val['last_maintenance_date']
                                    ];

                                    $process_result++;
                                    
                                    if ($this->update_client_master($client_id, $updateFields)){
                                        // Flag the Detail record as "updated", otherwise the update function didn't change anything
                                        $process_result++;
                                    }
                                    // Update DETAIL TABLE for added/updated clients
                                    $q = "UPDATE `".IMPORT_DETAIL_DATA."`"
                                            ." SET `process_result`='$process_result'"
                                                    .$this->update_common_sql()
                                            ." WHERE `id`='".$check_data_val['id']."' AND `is_delete`=0"
                                    ;
                                    $res = $this->re_db_query($q);
                                    
                                    if ($reassignClient AND !$existingAccountArray){
                                        $q = "INSERT INTO `".CLIENT_ACCOUNT."`"
                                                ." SET "
                                                    ."`client_id`='".$client_id."'"
                                                    .",`account_no`='".$check_data_val['mutual_fund_customer_account_number']."'"
                                                    .",`sponsor_company`='".$file_sponsor_array['id']."'"
                                                    .$this->insert_common_sql()
                                        ;
                                        $res = $this->re_db_query($q);
                                        $last_inserted_account_no_id = $this->re_db_insert_id();
                                        
                                        // Update DETAIL TABLE for added/updated clients
                                        $q = "UPDATE `".IMPORT_DETAIL_DATA."`"
                                                ." SET `process_result`='$process_result'"
                                                    .",`client_id`='$client_id'"
                                                    .",`account_no_id`='$last_inserted_account_no_id'"
                                                    .$this->update_common_sql()
                                                ." WHERE `id`='".$check_data_val['id']."'"
                                        ;
                                    }
                                }
                            } else {
                                // --- ADD CLIENT ---
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                    ." SET "
                                        ."`error_code_id`=".($dataSettings['add_client'] ? '`error_code_id`' : 23)
                                        .",`solved`='".$dataSettings['add_client']."'"
                                        .",`process_completed`='".$dataSettings['add_client']."'"
                                        .$exceptionFields
                                ;
                                $res = $this->re_db_query($q);

                                if ($dataSettings['add_client']){

                                    $get_client_data = $this->get_client_data($check_data_val['file_id'],$check_data_val['id']);

                                    $q = "INSERT INTO `".CLIENT_MASTER."`"
                                            ." SET "
                                                ."`file_id`='".$check_data_val['file_id']."'"
                                                .",`first_name`='".$first_name."'"
                                                .",`mi`='".$middle_name."'"
                                                .",`last_name`='".$last_name."'"
                                                .",`address1`='".$get_client_data[0]['client_address']."'"
                                                .",`birth_date`='".$check_data_val['customer_date_of_birth']."'"
                                                .",`zip_code`='".$check_data_val['zip_code']."'"
                                                .",`broker_name`='".$broker_id."'"
                                                .",`client_ssn`='".$check_data_val['social_security_number']."'"
                                                .",`client_file_number`='".$check_data_val['social_security_number']."'"
                                                .",`last_contacted`='".$check_data_val['last_maintenance_date']."'"
                                                .$this->insert_common_sql();
                                    $res = $this->re_db_query($q);
                                    $last_inserted_id = $this->re_db_insert_id();
                                    $reprocess_status = true;
                                    $process_result = 1;
                                }
                            }

                            // Client Account #
                            if($last_inserted_id OR ($dataSettings['update_client'] AND $existingSocialArray)) {
                                $client_id = 0;

                                if ($last_inserted_id) {
                                    $client_id = $last_inserted_id;
                                } else {
                                    // Assign the Account Number to the already existing SSN
                                    $client_id = $existingSocialArray[0]['id'];
                                }

                                $q = "INSERT INTO `".CLIENT_ACCOUNT."`"
                                    ." SET "
                                        ."`client_id`='".$client_id."'"
                                        .",`account_no`='".$check_data_val['mutual_fund_customer_account_number']."'"
                                        .",`sponsor_company`='".$file_sponsor_array['id']."'"
                                        .$this->insert_common_sql()
                                ;
                                $res = $this->re_db_query($q);
                                $last_inserted_account_no_id = $this->re_db_insert_id();
                                $process_result = 1;

                                // Update EXCEPTIONS table if CLIENT_MASTER wasn't updated
                                if ($existingSocialArray){
                                    $q = "UPDATE `".IMPORT_EXCEPTION."`"
                                            ." SET"
                                                ." `file_id`='".$check_data_val['file_id']."'"
                                                .",`field`='client_account_no'"
                                                .",`field_value`='$last_inserted_account_no_id'"
                                                .",`solved`='1'"
                                                .",`process_completed`='2'"
                                                .",`file_type`='1'"
                                                .",`temp_data_id`='".$check_data_val['id']."'"
                                                .$this->insert_common_sql()
                                            ." WHERE `id`='".$existingSocialExceptionId."'"
                                    ;
                                    $res = $this->re_db_query($q);
                                    $reprocess_status = true;
                                }

                                // Update DETAIL TABLE for added/updated clients
                                $q = "UPDATE `".IMPORT_DETAIL_DATA."`"
                                        ." SET `process_result`='$process_result'"
                                            .",`client_id`='$client_id'"
                                            .",`account_no_id`='$last_inserted_account_no_id'"
                                            .$this->update_common_sql()
                                        ." WHERE `id`='".$check_data_val['id']."' AND `is_delete`=0"
                                ;
                                $res = $this->re_db_query($q);
                            }
                        }
                    }
                    
                    /***********************************
                    * SFR PROCESS security-file data
                    ************************************/
                    $check_sfr_array = [];
                    if ($detail_record_id==0 OR $file_type==3) {
                        $check_sfr_array = $this->get_sfr_detail_data($file_id, 0, ($file_type==3 AND $detail_record_id>0) ? $detail_record_id : 0);
                    }
                    
                    foreach($check_sfr_array as $check_data_key=>$check_data_val) {
                        // Flag the record as processed for "Import" file grid to get an accurate count of the processed vs exception records
                        $this->re_db_perform(IMPORT_SFR_DETAIL_DATA, ["process_result"=>0], 'update', "`id`=".$check_data_val['id']);

                        $result = $last_inserted_id = 0;
                        $productCategoryQuery = '';

                        // Resolve exception() by user (edited Category/CUSIP and/or Name)
                        $reassignProductCategory =  0;

                        if ($check_data_val['resolve_exceptions'] != ''){
                            $exceptionArray = $this->getDetailExceptions($check_data_val['resolve_exceptions']);
                            // Cycle through the "resolves" the user entered to flag any defaults to rep, client, or hold(i.e. pass the exception through)
                            foreach ($exceptionArray AS $errorKey=>$errorRow){
                                if ($exceptionArray[$errorKey]['resolve_action']=='reassign' AND in_array($exceptionArray[$errorKey]['field'], ['major_security_type', 'cusip_number', 'fund_name'])){
                                    // Assign error code so the program knows why(error code id#) it's skipping the exception validation
                                    $reassignProductCategory = $errorKey;
                                }
                            }
                        }
                        // Missing data
                        if((empty(trim($check_data_val['major_security_type'])) AND $reassignProductCategory==0) OR (empty(trim($check_data_val['fund_name'])) AND empty(trim($check_data_val['product_name']))) ){
                            $field = 'major_security_type';
                            if (empty(trim($check_data_val['fund_name']))) {
                                $field = 'fund_name';
                            } else if (empty(trim($check_data_val['product_name']))) {
                                $field = 'product_name';
                            }

                            $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                ." SET"
                                    ." `file_id`='".$check_data_val['file_id']."'"
                                    .",`error_code_id`=13"
                                    .",`field`='$field'"
                                    .",`field_value`=''"
                                    .",`file_type`='3'"
                                    .",`temp_data_id`='".$check_data_val['id']."'"
                                    .",`date`='".date('Y-m-d')."'"
                                    .",`cusip`='".$check_data_val['cusip_number']."'"
                                    .$this->insert_common_sql()
                            ;
                            $res = $this->re_db_query($q);
                            $result += 1;
                        }
                        else {
                            // Reassign Product Category is done in Resolve Exceptions
                            if ($reassignProductCategory AND $check_data_val['product_category_id']>0){
                                $productCategoryQuery = " AND `id`=".$check_data_val['product_category_id'];
                            } else {
                                $productCategoryQuery = " AND `type_code`='".$check_data_val['major_security_type']."'";
                            }
                            // VALIDATION: Product Category
                            $q = "SELECT `id` FROM `".PRODUCT_TYPE."`"
                                    ." WHERE `is_delete`=0"
                                    ." AND `status`='1'"
                                    .$productCategoryQuery
                            ;
                            $res_ProductCategory = $this->re_db_query($q);

                            if($this->re_db_num_rows($res_ProductCategory)==0){
                                // EXCEPTION: Product Category not found
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                    ." SET"
                                        ." `file_id`='".$check_data_val['file_id']."'"
                                        .",`error_code_id`='17'"
                                        .",`field`='major_security_type'"
                                        .",`field_value`='".$check_data_val['major_security_type']."'"
                                        .",`file_type`='3'"
                                        .",`temp_data_id`='".$check_data_val['id']."'"
                                        .",`date`='".date('Y-m-d')."'"
                                        .",`cusip`='".$check_data_val['cusip_number']."'"
                                        .$this->insert_common_sql()
                                ;
                                $res = $this->re_db_query($q);
                            }
                            else {
                                // 1/19/22 Product Category Refactor - consolidate all products into 'PRODUCT_LIST', and use `ft_product_categories` i/o `ft_product_types`
                                // Check: CUSIP & SYMBOL
                                $array_ProductCategory = $this->re_db_fetch_array($res_ProductCategory);
                                $tickerSymbol = $this->re_db_input($check_data_val['ticker_symbol']);
                                $cusipNumber = $this->re_db_input($check_data_val['cusip_number']);

                                $q = "SELECT `id`,`cusip`,`ticker_symbol`,`name`"
                                    ." FROM `".PRODUCT_LIST."`"
                                    ." WHERE `is_delete`=0"
                                    ." AND (IF('$tickerSymbol'!='' AND `ticker_symbol`='".$tickerSymbol."',1,0)"
                                            ." OR "
                                            ."IF('$cusipNumber'!='' AND `cusip`='".$cusipNumber."',1,0)"
                                            .")"
                                    ." AND `category`=".$array_ProductCategory['id']
                                ;
                                $res_SymbolCusipCheck = $this->re_db_query($q);

                                if ($this->re_db_num_rows($res_SymbolCusipCheck) > 0) {
                                    // EXCEPTION: CUSIP/SYMBOL already exists - skip it
                                    $array_SymbolCusipCheck = $this->re_db_fetch_array($res_SymbolCusipCheck);

                                    // Flag the record as "solved". No need to clear the exception
                                    $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                        ." SET"
                                            ." `file_id`='".$check_data_val['file_id']."'"
                                            .",`error_code_id`='".($array_SymbolCusipCheck['cusip']==$cusipNumber ? '16' : '15')."'"
                                            .",`field`='".($array_SymbolCusipCheck['cusip']==$cusipNumber ? 'cusip_number' : 'ticker_symbol')."'"
                                            .",`field_value`='".($array_SymbolCusipCheck['cusip']==$cusipNumber ? $cusipNumber : $tickerSymbol)."'"
                                            .",`file_type`='3'"
                                            .",`temp_data_id`='".$check_data_val['id']."'"
                                            .",`date`='".date('Y-m-d')."'"
                                            .",`cusip`='".$cusipNumber."'"
                                            .",`process_completed`=1"
                                            .",`solved`=1"
                                            .$this->insert_common_sql()
                                    ;
                                    $res = $this->re_db_query($q);

                                    // Update DETAIL data TABLE for added/updated clients
                                    $q = "UPDATE `".IMPORT_SFR_DETAIL_DATA."`"
                                        ." SET"
                                            ." `product_category_id`=".$array_ProductCategory['id']
                                            .",`product_category_table`='".PRODUCT_LIST."'"
                                            .",`product_id`=".$array_SymbolCusipCheck['id']
                                            .",`process_result`=2"
                                            .$this->update_common_sql()
                                        ." WHERE `id`=".$check_data_val['id']." AND `is_delete`=0"
                                    ;
                                    $res = $this->re_db_query($q);
                                } 
                                else {
                                    // Checks Passed: INSERT PRODUCT
                                    $q = "INSERT INTO `".PRODUCT_LIST."`"
                                        ." SET"
                                            ." `category`='".$array_ProductCategory['id']."'"
                                            .",`name`='".trim($check_data_val['fund_name']).(trim($check_data_val['product_name'])!='' ? '/' : '').trim($check_data_val['product_name'])."'"
                                            .",`ticker_symbol`='".$check_data_val['ticker_symbol']."'"
                                            .",`cusip`='".$check_data_val['cusip_number']."'"
                                            .",`fund_code`='".$check_data_val['fund_code']."'"
                                            .",`sponsor`='".$file_sponsor_array['id']."'"
                                            .$this->insert_common_sql()
                                    ;
                                    $res_InsertProduct = $this->re_db_query($q);
                                    $last_inserted_id = $this->re_db_insert_id();
                                    $reprocess_status = $res_InsertProduct;

                                    $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                            ."SET"
                                                ." `file_id`='".$check_data_val['file_id']."'"
                                                .",`error_code_id`='0'"
                                                .",`solved`='1'"
                                                .",`file_type`='3'"
                                                .",`process_completed`='1'"
                                                .",`temp_data_id`='".$check_data_val['id']."'"
                                                .",`date`='".date('Y-m-d')."'"
                                                .",`cusip`='".$check_data_val['cusip_number']."'"
                                                .",`field`=''"
                                                .$this->insert_common_sql()
                                    ;
                                    $res = $this->re_db_query($q);

                                    // Update DETAIL data TABLE for added/updated clients
                                    $q = "UPDATE `".IMPORT_SFR_DETAIL_DATA."`"
                                        ." SET `process_result`=1"
                                            .",`product_category_id`=".$array_ProductCategory['id']
                                            .",`product_category_table`='".PRODUCT_LIST."'"
                                            .",`product_id`=$last_inserted_id"
                                            .$this->update_common_sql()
                                        ." WHERE `id`=".$check_data_val['id']." AND `is_delete`=0"
                                    ;
                                    $res = $this->re_db_query($q);
                                }
                            }
                        }
                    }
                    /***********************************
                    * PROCESS COMMISSION data
                    ************************************/
                    $commission_detail_array = [];
                    $commissionFileType = $commissionAddOnTheFly_Client = $commissionAddOnTheFly_Product = 0;
                    $fileSource = '';
                    
                    if (in_array($file_type, [$this->GENERIC_file_type])) {
                        $commission_detail_array = $this->get_gen_detail_data($file_id, 0, ($detail_record_id>0) ? $detail_record_id : 0);
                        $commissionFileType = $this->GENERIC_file_type;
                        // Generic file does not contain a Cusip(or any unique product id or description) and Clients are often added with the transaction, i.e. many will not be in Cloudfox DB. So, add them and let God sort it out
                        $commissionAddOnTheFly_Client = $commissionAddOnTheFly_Product = 1;
                        $commDetailTable = IMPORT_GEN_DETAIL_DATA;
                        $fileSource = 'GN';
                    } else if ($file_type==2 OR $detail_record_id==0) {
                        $commission_detail_array = $this->get_idc_detail_data($file_id, 0, ($detail_record_id>0) ? $detail_record_id : 0);
                        $commissionFileType = 2;
                        $commDetailTable = IMPORT_IDC_DETAIL_DATA;
                        $fileSource = 'DS';
                    }
                    
                    foreach($commission_detail_array as $check_data_key=>$check_data_val){
                        // Flag the record as processed for "Import" file grid to get an accurate count of the processed vs exception records
                        $this->re_db_perform($commDetailTable, ["process_result"=>0], 'update', "`id`=".$check_data_val['id']." AND `is_delete`=0");

                        $batch_id = 0;
                        $broker_id = 0;
                        $client_id = 0;
                        $product_category_id = 0;
                        $product_id = 0;
                        $result = 0;
                        $transaction_master_id = 0;
                        $updateResult = [];
                        $for_import = '';
                        $sponsor_id = 0;
                        // 04/05/22 Generic Type - sponsor dropdown added on File Fetch, so the $sponsor_id should be in the IMPORT CURRENT FILE table record
                        // if ($file_type == $this->GENERIC_file_type){
                        //     $sponsor_id = $check_data_val['sponsor_id'];
                        //     // $file_sponsor_array['id'] = $check_data_val['sponsor_id'];
                        // } else {
                            $sponsor_id = $file_sponsor_array['id'];
                        // }
                        
                        $insert_exception_string =
                             ",`file_id`='".$check_data_val['file_id']."'"
                            .",`temp_data_id`='".$check_data_val['id']."'"
                            .",`date`='".date('Y-m-d')."'"
                            .",`rep`='".$this->re_db_input($check_data_val['representative_number'])."'"
                            .",`rep_name`='".$check_data_val['representative_name']."'"
                            .",`account_no`='".$check_data_val['customer_account_number']."'"
                            .",`client`='".$this->re_db_input($check_data_val['alpha_code'])."'"
                            .",`cusip`='".$check_data_val['cusip_number']."'"
                            .",`principal`='".($check_data_val['gross_amount_sign_code']=='1' ? '-' : '').$this->re_db_input($check_data_val['gross_transaction_amount'])."'"
                            .",`commission`='".($check_data_val['dealer_commission_sign_code']=='1' ? '-' : '').$this->re_db_input($check_data_val['dealer_commission_amount'])."'"
                            .$this->insert_common_sql()
                        ;

                        // Exception intervention in resolve exception() by user(reassign client OR broker)
                        $reassignBroker = $reassignClient = 0;
                        $exceptionArray = $resolveHoldCommission = [];

                        if ($check_data_val['resolve_exceptions'] != ''){
                            $exceptionArray = $this->getDetailExceptions($check_data_val['resolve_exceptions']);
                            // Cycle through the "resolves" the user entered to flag any defaults to rep, client, or hold(i.e. pass the exception through)
                            foreach ($exceptionArray AS $errorKey=>$errorRow){
                                if ($exceptionArray[$errorKey]['resolve_action']=='reassign' AND in_array($exceptionArray[$errorKey]['field'], ['representative_number', 'u5', 'active_check'])){
                                    // Assign error code so the program knows way it's skipping the Rep #alias search
                                    $reassignBroker = $errorKey;
                                }

                                if ($exceptionArray[$errorKey]['resolve_action']=='reassign' AND in_array($exceptionArray[$errorKey]['field'], ['customer_account_number', 'objectives'])
                                    OR ($exceptionArray[$errorKey]['resolve_action']=='add' AND in_array($exceptionArray[$errorKey]['field'], ['objectives']))
                                ){
                                    // Assign error code so the program knows way it's skipping the Client Account # search
                                    $reassignClient = $errorKey;
                                }

                                if ($exceptionArray[$errorKey]['resolve_action']=='hold'){
                                    // Assign error code so the program knows way it's skipping exception entry, and entering the trade into the system with a hold, and "hold reason" corresponds to $errorKey(Exception Master ID)
                                    array_push($resolveHoldCommission, $errorKey);
                                }
                            }
                        }

                        // IDC BROKER Validation
                        if(isset($check_data_val['representative_number']) OR $check_data_val['broker_id']<0){
                            $rep_number = isset($check_data_val['representative_number'])?trim($this->re_db_input($check_data_val['representative_number'])):'';

                            if($rep_number != '' OR $reassignBroker){
                                if ($reassignBroker) {
                                    $broker = $instance_broker_master->select_broker_by_id($check_data_val['broker_id']);
                                    $brokerAlias = [];
                                } else {
                                    $broker = $instance_broker_master->select_broker_by_fund($rep_number);
                                    $brokerAlias = $instance_broker_master->select_broker_by_alias($rep_number, $sponsor_id);
                                }

                                if(empty($broker) AND empty($brokerAlias)){
                                    $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                            ." SET"
                                                ."`error_code_id`='1'"
                                                .",`field`='representative_number'"
                                                .",`field_value`='$rep_number'"
                                                .",`file_type` = $commissionFileType"
                                                .$insert_exception_string;
                                    $res = $this->re_db_query($q);
                                    $result++;
                                } else {
                                    $broker_id = (!empty($broker) ? $broker['id'] : $brokerAlias['broker_id']);
                                    // Update the Detail table for process checks later in the function
                                    $q ="UPDATE `".$commDetailTable."`"
                                        ." SET `broker_id`=".($reassignBroker ? $check_data_val['broker_id'] : $broker_id)
                                        ." WHERE `is_delete`=0 AND `id`=".$check_data_val['id']
                                    ;
                                    $res = $this->re_db_query($q);

                                    $check_broker_termination = $this->broker_termination_date('', $broker_id);

                                    if($check_broker_termination!='' AND date('Y-m-d', strtotime($check_data_val['trade_date'])) > $check_broker_termination){
                                        // User placed a hold on "Exception 2: Broker Terminated"
                                        if (in_array(2, $resolveHoldCommission)){
                                            // Should already be done in Resolve Exception, but switch the "hold" flag just in case in the Detail table
                                            if ($check_data_val['on_hold'] != 1){
                                                $check_data_val['on_hold'] = 1;

                                                $q ="UPDATE `".$commDetailTable."`"
                                                    ." SET `on_hold`=1"
                                                    ." WHERE `is_delete`=0 AND `id`=".$check_data_val['id']
                                                ;
                                                $res = $this->re_db_query($q);
                                            }
                                        } else {
                                            $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                                    ." SET `error_code_id`='2'"
                                                        .",`field`='u5'"
                                                        .",`field_value`='".$check_broker_termination."'"
                                                        .",`file_type`=$commissionFileType"
                                                        .$insert_exception_string;
                                            $res = $this->re_db_query($q);

                                            $result++;
                                        }
                                    }
                                }
                            } else {
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                        ." SET `error_code_id`='13'"
                                            .",`field`='representative_number'"
                                            .",`field_value`=''"
                                            .",`file_type`=$commissionFileType"
                                            .$insert_exception_string;
                                $res = $this->re_db_query($q);
                                $result++;
                            }
                        }
                        // IDC PRODUCT Validation
                        $productFound = 0;

                        if(!$commissionAddOnTheFly_Product AND empty($check_data_val['cusip_number'])){
                            $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                    ." SET `error_code_id`='13'"
                                        .",`field`='cusip_number'"
                                        .",`field_value`=''"
                                        .",`file_type`=$commissionFileType"
                                        .$insert_exception_string;
                            $res = $this->re_db_query($q);
                            $result++;
                            $productFound = -1;
                        } else {
                            $q = "SELECT `id`,`cusip`,`category`,`objective` ".
                                    " FROM `".PRODUCT_LIST."`".
                                    " WHERE `is_delete`=0".
                                    " AND `status`!=0".
                                    " AND TRIM(`cusip`)!='' AND `cusip`='".$check_data_val['cusip_number']."'";
                            $resCusipFind = $this->re_db_query($q);

                            // CUSIP found
                            if($this->re_db_num_rows($resCusipFind)>0){
                                $productFound++;
                                $foundProduct = $this->re_db_fetch_array($resCusipFind);
                            }
                            
                            if (!$productFound AND $commissionAddOnTheFly_Product){
                                $foundProduct = [
                                    'category'=>$check_data_val['product_category'],
                                    'name'=>"*GENERIC PRODUCT - ".strtoupper($this->re_db_input($check_data_val['fund_company'])),
                                    'security'=>"*GENERIC".strtoupper($this->re_db_input($check_data_val['fund_company'])),
                                    'id'=>0,
                                    'product_category'=>$check_data_val['product_category'],
                                    'sponsor'=>$this->re_db_input(strtoupper($check_data_val['fund_company']))
                                ];
                                // Check for the File Name & Code
                                $q = "`security` = '{$foundProduct['security']}' OR `name` = '{$foundProduct['name']}'";
                                $res = $instance_product_maintenance->product_list_by_query($q);
                                
                                if ($res){
                                    $foundProduct = $res;
                                    $productFound++;
                                }
                                // ADD THE PRODUCT
                                if ($productFound == 0){
                                    // Search for a valid sponsor by the company description in the file
                                    if (empty($sponsor_id)){
                                        $res = $instance_manage_sponsor->search_sponsor("UPPER(`clm`.`name`) LIKE '".trim($foundProduct['sponsor'])."%' ", 1);
                                    } else {
                                        $res = [['id'=> $sponsor_id]];
                                    }
                                    // Sponsor found
                                    if ($res){
                                        $foundProduct['sponsor'] = $res[0]['id'];
                                        $updateResult['insert_product'] = $instance_product_maintenance->insert_update_product($foundProduct, true);
                                        
                                        if ($updateResult['insert_product'] AND !empty($_SESSION['new_product_id'])){
                                            $foundProduct = $instance_product_maintenance->edit_product($_SESSION['new_product_id']);
                                            $productFound++;
                                        }
                                    } else {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                            ." SET `error_code_id`='14'"
                                                .",`field`='sponsor'"
                                                .",`field_value`='".strtoupper($check_data_val['fund_company'])."'"
                                                .",`file_type`=$commissionFileType"
                                                .$insert_exception_string;
                                        $res = $this->re_db_query($q);
                                        $result++;
                                    }
                                }
                            }
                            
                            if ($productFound > 0){
                                $product_id = (int)$this->re_db_input($foundProduct['id']);
                                $product_category_id = $foundProduct['category'];

                                $q = "UPDATE `".$commDetailTable."`"
                                        ." SET `product_id`=$product_id"
                                            .$this->update_common_sql()
                                        ." WHERE `id`='".$check_data_val['id']."' AND `is_delete`=0"
                                ;
                                $res = $this->re_db_query($q);

                            } else {
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                        ." SET `error_code_id`='11'"
                                            .",`field`='cusip_number'"
                                            .",`field_value`='".$check_data_val['cusip_number']."'"
                                            .",`file_type`=$commissionFileType"
                                            .$insert_exception_string;
                                $res = $this->re_db_query($q);
                                $result++;
                            }
                        }

                        // IDC CLIENT validation
                        if(!$reassignClient AND empty($check_data_val['customer_account_number'])){
                            $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                    ." SET `error_code_id`='13'"
                                        .",`field`='customer_account_number'"
                                        .",`field_value`=''"
                                        .",`file_type`=$commissionFileType"
                                        .$insert_exception_string;
                            $res = $this->re_db_query($q);
                            $result++;
                        } else {
                            if ($reassignClient){
                                $client_id = $check_data_val['client_id'];
                                $clientAccount = $instance_client_maintenance->edit($client_id);
                            } else {
                                // CLIENT SEARCH BY ACCOUNT #
                                $q =
                                    "SELECT `cm`.`id`,`ca`.`id` AS `account_id`,`ca`.`account_no`,`ca`.`sponsor_company`,`cm`.`state`"
                                        ." FROM `".CLIENT_ACCOUNT."` AS `ca`"
                                        ." LEFT JOIN `".CLIENT_MASTER."` AS `cm` ON `ca`.`client_id`=`cm`.`id` AND `cm`.`is_delete`=0"
                                        ." WHERE `ca`.`account_no`='".$this->re_db_input($check_data_val['customer_account_number'])."'"
                                            ." AND `ca`.`sponsor_company`='".$sponsor_id."'"
                                            ." AND `ca`.`is_delete`=0 AND `ca`.`client_id`=`cm`.`id`"
                                ;
                                $res = $this->re_db_query($q);

                                if ($this->re_db_num_rows($res)>0){
                                    $clientAccount = $this->re_db_fetch_array($res);
                                    $client_id = $clientAccount['id'];
                                } else {
                                    $clientAccount = [];
                                    $client_id = 0;
                                }
                            }

                            //--- ADD CLIENT ---//
                            if (empty($clientAccount) AND $commissionAddOnTheFly_Client) {
                                $updateResult['insert_client_master'] = $updateResult['insert_client_account'] = 0;
                                $for_import = 'reprocess_add_client_on_the_fly';
                                $_SESSION[$for_import] = [];
                                
                                // Add->Check sponsor
                                if (empty($sponsor_id)){
                                    // Search for a valid sponsor by the company description in the file
                                    $res = $instance_manage_sponsor->search_sponsor("UPPER(`clm`.`name`) LIKE '".trim($foundProduct['sponsor'])."%' ", 1);
                                    if (count($res)==0) {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                            ." SET `error_code_id`='14'"
                                                .",`field`='sponsor'"
                                                .",`field_value`='".strtoupper($check_data_val['fund_company'])."'"
                                                .",`file_type`=$commissionFileType"
                                                .$insert_exception_string;
                                        $res = $this->re_db_query($q);
                                        $result++;
                                    } else {
                                        // $sponsor_id should be part of the file information, and never be empty - 4/5/22
                                        // $sponsor_id = $res[0]['id'];
                                    }
                                }
                                // Add->Check Client Name
                                if (empty($check_data_val['alpha_code'])){
                                    $clientAccount = ['last_name'=>'GenericCustomer '.$this->re_db_input($check_data_val['customer_account_number'])];
                                } else {
                                    $clientAccount = nameParse($check_data_val['alpha_code']);
                                } 
                                // Actually do the add
                                // Rename the return keys so you can pass it to insert update()
                                if (!empty($sponsor_id) AND !empty($broker_id)){
                                    
                                    $updateResult['insert_client_master'] = $instance_client_maintenance->insert_update([
                                        'for_import'=>$for_import,
                                        'lname'=>$clientAccount['last_name'], 
                                        'fname'=>$clientAccount['first_name'], 
                                        'mi'=>$clientAccount['mi'], 
                                        'client_file_number'=>$check_data_val['customer_account_number'],
                                        'broker_name'=>(string)$broker_id,
                                        'file_id'=>$check_data_val['file_id']
                                    ]);
                                    
                                    if ($updateResult['insert_client_master']==0 AND !empty($instance_client_maintenance->errors)){
                                        $_SESSION['warning'] = $instance_client_maintenance->errors;
                                        
                                        if (stripos($instance_client_maintenance->errors, '(24)') !== false){
                                            $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                                ." SET `error_code_id`=24"
                                                    .",`field`='alpha_code'"
                                                    .",`field_value`='{$check_data_val['alpha_code']}'"
                                                    .",`file_type`=$commissionFileType"
                                                    .$insert_exception_string;
                                            $res = $this->re_db_query($q);
                                            $result++;
                                        }
                                    }
                                    // $_SESSION[$for_import] populated in the called function
                                    if ($updateResult['insert_client_master'] AND !empty($_SESSION[$for_import]['insert_update_master'])){
                                        // insert update account gets the "client_id" from the Global $_SESSION variable
                                        // $_SESSION['client_id'] = (int)$this->re_db_input($_SESSION[$for_import]['insert_update_master']);
                                        
                                        // Add Account #
                                        $updateResult['insert_client_account'] = $instance_client_maintenance->insert_update_account([
                                            'for_import'=>$for_import,
                                            'account_no'=>[$check_data_val['customer_account_number']], 
                                            'sponsor'=>[$sponsor_id]
                                        ]);
                                    }
                                    // Both Client Master & Account entered
                                    if ($updateResult['insert_client_master'] AND $updateResult['insert_client_account']){
                                        $q =
                                            "SELECT `cm`.`id`,`ca`.`id` AS `account_id`,`ca`.`account_no`,`ca`.`sponsor_company`,`cm`.`state`"
                                                ." FROM `".CLIENT_ACCOUNT."` AS `ca`"
                                                ." LEFT JOIN `".CLIENT_MASTER."` AS `cm` ON `ca`.`client_id`=`cm`.`id` AND `cm`.`is_delete`=0"
                                                ." WHERE `ca`.`account_no`='".$this->re_db_input($check_data_val['customer_account_number'])."'"
                                                    ." AND `ca`.`sponsor_company`='".$sponsor_id."'"
                                                    ." AND `ca`.`is_delete`=0 AND `ca`.`client_id`=`cm`.`id`"
                                        ;
                                        $res = $this->re_db_query($q);
                                        $clientAccount = ($this->re_db_num_rows($res)>0 ? $this->re_db_fetch_array($res) : '');
                                        $client_id = $clientAccount['id'];
                                    }
                                }
                                unset($_SESSION[$for_import]);
                            }
                            
                            //--- VALID CLIENT FOUND --//
                            if (!empty($clientAccount['id'])){
                                $client_id = $clientAccount['id'];

                                $q = "UPDATE `".$commDetailTable."`"
                                        ." SET `client_id`='$client_id'"
                                            .$this->update_common_sql()
                                        ." WHERE `id`='".$check_data_val['id']."' AND `is_delete`=0"
                                ;
                                $res = $this->re_db_query($q);
                            } else {
                                // Client Account # Not Found
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                    ." SET `error_code_id`='18'"
                                        .",`field`='customer_account_number'"
                                        .",`field_value`='{$check_data_val['customer_account_number']}'"
                                        .",`file_type`=$commissionFileType"
                                        .$insert_exception_string;
                                $res = $this->re_db_query($q);
                                $client_id = 0;
                                $result++;
                            } 

                            // Objectives Check
                            if (!empty($client_id) AND !empty($foundProduct['objective'])){
                                $q = "SELECT `co`.`client_id`, `co`.`objectives`".
                                        " FROM `".CLIENT_OBJECTIVES."` as `co`".
                                        " WHERE `co`.`is_delete`=0".
                                          " AND `co`.`objectives`='".$foundProduct['objective']."'".
                                          " AND `client_id`='".$this->re_db_input($client_id)."'"
                                ;
                                $resClientObjectives = $this->re_db_query($q);

                                if($this->re_db_num_rows($resClientObjectives) == 0){
                                    if (in_array(9,$resolveHoldCommission)){
                                            // Should already be done in Resolve Exception, but switch the "hold" flag just in case in the Detail table
                                            if ($check_data_val['on_hold'] != 1){
                                                $check_data_val['on_hold'] = 1;

                                                $q ="UPDATE `".$commDetailTable."`"
                                                    ." SET `on_hold`=1"
                                                    ." WHERE `is_delete`=0 AND `id`=".$check_data_val['id']
                                                ;
                                                $res = $this->re_db_query($q);
                                            }
                                    } else {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                                ." SET `error_code_id`='9'"
                                                    .",`field`='objectives'"
                                                    .",`field_value`='".$foundProduct['objective']."'"
                                                    .",`file_type`=$commissionFileType"
                                                .$insert_exception_string;
                                        $res = $this->re_db_query($q);
                                        $result++;
                                    }
                                }
                            }

                            // State Licence Check
                            // "on_hold" populated in resolve_exceptions($data) - (1)Broker Terminated, (3)Broker Licence
                            if(!empty($broker_id) AND !empty($client_id) AND !empty($clientAccount['state']) AND !empty($product_category_id)){
                                // 12/17/21 Get the correct product type licence
                                $check_result = $this->checkStateLicence($broker_id, $clientAccount['state'], $product_category_id, $check_data_val['trade_date']);

                                if($check_result == 0){
                                    // User placed a hold in "Resolve Exception" (Exception #6: Broker License Error)
                                    if (in_array(6,$resolveHoldCommission)){
                                        // Should already be done in Resolve Exception, but switch the "hold" flag just in case in the Detail table
                                        if ($check_data_val['on_hold'] != 1){
                                            $check_data_val['on_hold'] = 1;

                                            $q ="UPDATE `".$commDetailTable."`"
                                                ." SET `on_hold`=1"
                                                ." WHERE `is_delete`=0 AND `id`=".$check_data_val['id']
                                            ;
                                            $res = $this->re_db_query($q);
                                        }
                                    } else {
                                        $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                                ." SET  `error_code_id`='6'"
                                                    .",`field`='active_check'"
                                                    .",`field_value`='".$product_category_id." / ".$clientAccount['state']."'"
                                                    .",`file_type`=$commissionFileType"
                                                    .$insert_exception_string;
                                        $res = $this->re_db_query($q);
                                        $result++;
                                    }
                                }
                            }
                        }

                        // INSERT the IDC record
                        if(isset($result) && $result == 0){
                            $total_check_amount = $transaction_master_id = $isTrail = 0;
                            $commission_received = ($check_data_val['dealer_commission_sign_code']=='1' ? '-' : '') . ($this->re_db_input($check_data_val['dealer_commission_amount']));
                            $split_trade = 0;
                            $batch_id = $this->get_file_batch($check_data_val['file_id']);
                            $header_record = $this->get_files_header_detail($check_data_val['file_id'], $check_data_val['id'], 2);
                            $batch_date = (empty($header_record['transmission_date']) ? date('Y-m-d') : date('Y-m-d', strtotime($header_record['transmission_date'])));
                            $batch_description = $this->re_db_input($file_sponsor_array['name']).' - '.date('m/d/Y', strtotime($batch_date));

                            // Create new BATCH
                            if (empty($batch_id)){

                                $q = "SELECT `file_id`"
                                            ." ,SUM(CONVERT(CONCAT(IF(dealer_commission_sign_code='1','-',''),dealer_commission_amount), DECIMAL(10,2))) AS total_check_amount"
                                            ." ,MIN(`trade_date`) AS `trade_start_date`"
                                            ." , MAX(`trade_date`) AS `trade_end_date`"
                                        ." FROM `".$commDetailTable."`"
                                        ." WHERE `is_delete`=0"
                                          ." AND `file_id`='".$check_data_val['file_id']."'"
                                        ." GROUP BY `file_id`"
                                ;
                                $res = $this->re_db_query($q);

                                if($this->re_db_num_rows($res)>0){
                                    $batchArray = $this->re_db_fetch_array($res);
                                    $total_check_amount = $batchArray['total_check_amount'];
                                } else {
                                    $batchArray = ["file_id"=>$check_data_val['file_id'], "total_check_amount"=>0, "trade_start_date"=>'', "trade_end_date"=>''];
                                }

                                $q = "INSERT INTO `".BATCH_MASTER."`"
                                        ." SET `file_id`='".$check_data_val['file_id']."'"
                                            .",`pro_category`='".$product_category_id."'"
                                            .",`batch_desc`='".$this->re_db_input($batch_description)."'"
                                            .",`sponsor`='".$this->re_db_input($sponsor_id)."'"
                                            .",`check_amount`='".$total_check_amount."'"
                                            .",`batch_date`='".$batch_date."'"
                                            .",`trade_start_date`='".$this->re_db_input($batchArray['trade_start_date'])."'"
                                            .",`trade_end_date`='".$this->re_db_input($batchArray['trade_end_date'])."'"
                                            .$this->insert_common_sql();
                                $res = $this->re_db_query($q);
                                $batch_id = $this->re_db_insert_id();
                            }

                            if (!empty($batch_id)){
                                $q = "INSERT INTO `".IMPORT_EXCEPTION."`"
                                        ." SET "
                                            ." `error_code_id`='0'"
                                            .",`field`=''"
                                            .",`file_type`=$commissionFileType"
                                            .",`solved`='1'"
                                            .",`process_completed`='1'"
                                            .",`file_id`='".$check_data_val['file_id']."'"
                                            .",`temp_data_id`='".$check_data_val['id']."'"
                                            .",`date`='".date('Y-m-d')."'"
                                            .",`rep`='".$this->re_db_input($check_data_val['representative_number'])."'"
                                            .",`rep_name`='".$check_data_val['representative_name']."'"
                                            .",`account_no`='".$check_data_val['customer_account_number']."'"
                                            .",`client`='".$this->re_db_input($check_data_val['alpha_code'])."'"
                                            .",`cusip`='".$check_data_val['cusip_number']."'"
                                            .",`principal`='".($check_data_val['gross_amount_sign_code']=='1' ? '-' : '').$this->re_db_input($check_data_val['gross_transaction_amount'])."'"
                                            .",`commission`='".$commission_received."'"
                                            .$this->insert_common_sql()
                                ;
                                $res = $this->re_db_query($q);
                                $result = 0;

                                $check_broker_commission = $instance_broker_master->check_broker_commission_status($broker_id);
                                $broker_hold_commission = (empty($check_broker_commission) ? 0 : $check_broker_commission['hold_commissions']);

                                $con = '';

                                if ($check_data_val['on_hold'] AND in_array(2,$resolveHoldCommission)){
                                    $con .=",`hold_commission`=1,`hold_resoan`='BROKER TERMINATED'";
                                } else if($check_data_val['on_hold'] AND in_array(6,$resolveHoldCommission)){
                                    $con .=",`hold_commission`=1, `hold_resoan`='BROKER LICENCE ERROR'";
                                } else if($check_data_val['on_hold'] AND in_array(9,$resolveHoldCommission)){
                                    $con .=",`hold_commission`=1, `hold_resoan`='CLIENT<>PRODUCT OBJECTIVE'";
                                } else if($broker_hold_commission == 1){
                                    $con .=",`hold_commission`='".$broker_hold_commission."',`hold_resoan`='HOLD COMMISSION BY BROKER'";
                                } else {
                                    $con .=",`hold_commission`='2'";
                                }

                                $trans_ins = new transaction();
                                $get_branch_company_detail = $trans_ins->select_branch_company_ref($broker_id);
                                $branch = isset($get_branch_company_detail['branch_id'])?$get_branch_company_detail['branch_id']:'';
                                $company = isset($get_branch_company_detail['company_id'])?$get_branch_company_detail['company_id']:'';
                                // Have to check stripos() with "!==false" because if the string is in the first position, it will return "0"
                                if (!empty($check_data_val['comm_type']) AND (stripos($check_data_val['comm_type'], '12b')!==false OR stripos($check_data_val['comm_type'], 'trail')!==false)) {
                                    $isTrail = 1;
                                } else if (!empty($check_data_val['commission_record_type_code']) AND $check_data_val['commission_record_type_code']=='3') {
                                    $isTrail = 1;
                                }
                                
                                $q1 = "INSERT INTO `".TRANSACTION_MASTER."`"
                                        ." SET `file_id`='".$check_data_val['file_id']."'"
                                            .",`source`='".$fileSource."'"
                                            .",`trade_date`='".$check_data_val['trade_date']."'"
                                            .",`posting_date`='".date('Y-m-d')."'"
                                            .",`invest_amount`='".($check_data_val['gross_amount_sign_code']=='1' ? '-' : '').$this->re_db_input($check_data_val['gross_transaction_amount'])."'"
                                            .",`gross_amount_sign_code`='".$check_data_val['gross_amount_sign_code']."'"
                                            .",`dealer_commission_sign_code`='".$check_data_val['dealer_commission_sign_code']."'"
                                            .",`commission_received`='".$commission_received."'"
                                            .",`product_cate`='".$product_category_id."'"
                                            .",`product`='".$product_id."'"
                                            .",`batch`='".$batch_id."'"
                                            .",`sponsor`='".$sponsor_id."'"
                                            .",`broker_name`='".$broker_id."'"
                                            .",`client_name`='".$client_id."'"
                                            .",`client_number`='".$check_data_val['customer_account_number']."'"
                                            .",`branch`='".$branch."'"
                                            .",`company`='".$company."'"
                                            .",`split`='2'"
                                            .",`buy_sell`='1'"
                                            .",`cancel`='2'"
                                            .",`commission_received_date`='".$batch_date."'"
                                            .",`trail_trade`=$isTrail"
                                            .$con
                                            .$this->insert_common_sql();
                                $res1 = $this->re_db_query($q1);
                                $last_inserted_id = $this->re_db_insert_id();
                                $transaction_master_id = $last_inserted_id;

                                $get_client_split_rates = $trans_ins->get_client_split_rate($client_id);

                                if(isset($get_client_split_rates[0]['split_broker']) && $get_client_split_rates[0]['split_broker'] != ''){
                                    $q = "INSERT INTO `".TRANSACTION_TRADE_SPLITS."`"
                                            ." SET `transaction_id`='".$last_inserted_id."'"
                                                .",`split_client_id`='".$client_id."'"
                                                .",`split_broker_id`='0'"
                                                .",`split_broker`='".$get_client_split_rates[0]['split_broker']."'"
                                                .",`split_rate`='".$get_client_split_rates[0]['split_rate']."'"
                                                .$this->insert_common_sql();
                                    $res = $this->re_db_query($q);

                                    $split_trade++;
                                }

                                $get_broker_split_rate = $trans_ins->get_broker_split_rate($broker_id);

                                if(isset($get_broker_split_rate[0]['rap']) && $get_broker_split_rate[0]['rap'] != ''){
                                    foreach($get_broker_split_rate as $keyedit_split=>$valedit_split){
                                        $q = "INSERT INTO `".TRANSACTION_TRADE_SPLITS."`"
                                                ." SET `transaction_id`='".$last_inserted_id."'"
                                                    .",`split_client_id`='0'"
                                                    .",`split_broker_id`='".$broker_id."'"
                                                    .",`split_broker`='".$valedit_split['rap']."'"
                                                    .",`split_rate`='".$valedit_split['rate']."'"
                                                    .$this->insert_common_sql();
                                        $res = $this->re_db_query($q);
                                    }

                                    $split_trade++;
                                }

                                // Update the relevant tables with trade values
                                $q = "UPDATE `".$commDetailTable."`"
                                        ." SET `process_result`='1'"
                                            .",`transaction_master_id`='$transaction_master_id'"
                                            .$this->update_common_sql()
                                        ." WHERE `id`='".$check_data_val['id']."' AND `is_delete`=0"
                                ;
                                $res = $this->re_db_query($q);

                                if ($split_trade){
                                    $q = "UPDATE `".TRANSACTION_MASTER."`"
                                            ." SET `split`='1'"
                                                    .$this->update_common_sql()
                                            ." WHERE `id`='$transaction_master_id'  AND `is_delete`=0";
                                    $res = $this->re_db_query($q);
                                }

                                $q = "UPDATE `".BATCH_MASTER."`"
                                        ." SET `commission_amount`=`commission_amount`+$commission_received"
                                            .",`posted_amounts`=`posted_amounts`+$commission_received"
                                            .$this->update_common_sql()
                                        ." WHERE `id`='$batch_id'  AND `is_delete`=0";
                                $res = $this->re_db_query($q);

                                if($res == true){
                                    $reprocess_status = true;
                                }
                            }
                        }
                    }
                }
            }
            /*********************
             * FILE Update
             *********************/
            $check_file_exception_process = $this->check_file_exception_process($file_id,1);
            $_SESSION['reprocess_status'] = $reprocess_status;

            $q = "UPDATE `".IMPORT_CURRENT_FILES."`"
                ." SET `processed`='1'"
                    .",`last_processed_date`='".date('Y-m-d H:i:s')."'"
                    .",`process_completed`=".($check_file_exception_process['exceptions'] ? '0' : '1')
                    .$this->update_common_sql()
                ." WHERE `id`=$file_id";
            $res = $this->re_db_query($q);

            if($res){
                $_SESSION['success'] = 'Data successfully processed.';
                return true;
            } else {
                $_SESSION['warning'] = UNKWON_ERROR;
                return false;
            }
        }

        /** 01/06/22 importClass for DST
         * @param mixed $client_id
         * @param mixed $data
         * @return void
         */
        function update_client_master ($clientId=0, $data=array()){
            if (empty($clientId) OR empty($data)){
                return 0;
            }
            $clientClass = new client_maintenance();
            $updateArray = [];
            $return = 0;
            $clientMasterTable = CLIENT_MASTER;
            $masterArray = $clientClass->select_client_master($clientId);

            foreach ($data AS $column=>$value){
                if (isset($masterArray[$column]) AND empty($masterArray[$column]) AND !empty($data[$column]) AND !in_array(strtolower($column), ['id','file_id','modified_time','modified_by','modified_ip'])){
                    $updateArray[$column] = $value;
                }
            }

            if (!empty($updateArray)){
                $modifiedArray = $this->update_common_sql(2);

                foreach ($modifiedArray AS $column=>$value){
                    $updateArray[$column] = $value;
                }
                $return = $this->re_db_perform($clientMasterTable, $updateArray, 'update', "`$clientMasterTable`.`is_delete`=0 AND "."`$clientMasterTable`.`id`='$clientId'");
            }

            return $return;
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
       public function select_current_files($sfrBreakOut=0){
			$return = array();

            if ($sfrBreakOut){
                // UNION in an SFR row, if one exists
                $q = "SELECT `id`,`file_name`, `file_type`, `processed`, `process_completed`, `is_archived`, `imported_date`, `last_processed_date`, `source`, `sponsor_id`"
                        ." FROM `".IMPORT_CURRENT_FILES."`"
                        ." WHERE `is_delete`=0"
                        ." AND `is_archived`=0"
                    ." UNION"
                        ." SELECT `b`.`id` AS `id`, CONCAT(TRIM(`b`.`file_name`),'(SFR)') AS `file_name`, 'Security File' AS `file_type`, `b`.`processed`, `b`.`process_completed`, `b`.`is_archived`, `b`.`imported_date`, `b`.`last_processed_date`, `b`.`source`, `b`.`sponsor_id`"
                            ." FROM `".IMPORT_SFR_HEADER_DATA."` `a`"
                            ." LEFT JOIN `".IMPORT_CURRENT_FILES."` `b` ON `b`.`id` = `a`.`file_id`"
                            ." WHERE `b`.`is_delete`=0"
                            ." AND `b`.`is_archived`=0"
                    ." ORDER BY `last_processed_date` DESC, `file_name`, `file_type`"
                ;
            } else {
                $q = "SELECT `at`.*"
                        ." FROM `".IMPORT_CURRENT_FILES."` AS `at`"
                        ." WHERE `at`.`is_delete`=0"
                        ." AND `at`.`is_archived`=0"
                        ." ORDER BY `at`.`imported_date` DESC, `at`.`file_type`, `at`.`file_name`"
                ;
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
        public function broker_termination_date($fund_clearing_number, $broker_id=0){
			$return = '';
			$con = " AND ".($broker_id > 0 ? "`at`.`id`='".$broker_id."'" : "`at`.`fund`='".$fund_clearing_number."'");

            $q = "SELECT `bg`.`u5`".
					" FROM `".BROKER_MASTER."` AS `at`".
                    " LEFT JOIN `".BROKER_GENERAL."` AS `bg` ON `bg`.`broker_id`=`at`.`id`".
                    " WHERE `at`.`is_delete`=0 ".$con
            ;
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
                    WHERE `at`.`is_delete`=0 and `at`.`id`='".$broker_id."'
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

        /** Check the state licensing for the specified Broker and Product Category 12/17/21
         * @param int $pBroker_id
         * @param int $pClient_state
         * @param mixed $pProduct_category_id
         * @param string $pTerm_date
         * @return bool TRUE - good licensing, FALSE - broker licence not found or not active
         */
        function checkStateLicence($pBroker_id=0, $pClient_state=0, $pProduct_category_id=0, $pTrade_date='',$pDetail=0){
            $return = ['licence_id'=>0, 'broker_id'=>$pBroker_id, 'first_name'=>'', 'last_name'=>'', 'active_check'=>0, 'state_id'=>(int)$pClient_state, 'state_name'=>'',
                       'received'=>'', 'terminated'=>'', 'licence_table'=>'', 'product_category_id'=>$pProduct_category_id, 'product_category'=>'', 'trade_date'=>'', 'result'=>0];
            $BatchClass = new batches();
            $ClientClass = new client_maintenance();

            $trade_date = (empty($pTrade_date) ? date('Y-m-d', strtotime('1900-01-01')) : date('Y-m-d', strtotime($pTrade_date)));
            $product_category = strtolower($BatchClass->get_product_type($pProduct_category_id));
            $state_name = $ClientClass->get_state_name($pClient_state);
            if ($state_name){
                $state_name = $state_name['state_name'];
            } else {
                $state_name = '';
            }

            if (in_array($product_category, ['ria'])) {
                $licenceTable = 'BROKER_LICENCES_RIA';
            } else if (preg_match('(life|insurance|annuities|annuity)', $product_category)) {
                $licenceTable = 'BROKER_LICENCES_INSURANCE';
            } else {
                $licenceTable = 'BROKER_LICENCES_SECURITIES';
            }

            $return['product_category'] = $product_category;
            $return['licence_table'] = $licenceTable;
            $return['trade_date'] = $trade_date;
            $return['state_name'] = $state_name;

            $q = "SELECT `bls`.`id` AS `licence_id`, `bm`.`id` AS `broker_id`, `bm`.`first_name`, `bm`.`last_name`"
                         .", `bls`.`active_check`, `bls`.`state_id`, '$state_name' AS `state_name`, `bls`.`received`, `bls`.`terminated`"
                         .", '$licenceTable' AS `licence_table`"
                         .", '$pProduct_category_id' AS `product_category_id`"
                         .", '$product_category' AS `product_category`"
                         .", '$trade_date' AS `trade_date`"
                    ." FROM `".constant($licenceTable)."` AS `bls`"
                    ." LEFT JOIN `".BROKER_MASTER."` `bm` ON `bls`.`broker_id`=`bm`.`id` AND `bm`.`is_delete`=0"
                    ." WHERE `bls`.`is_delete`=0"
                    ." AND `bls`.`broker_id`='".$this->re_db_input($pBroker_id)."'"
                    ." AND `bls`.`state_id`='".$this->re_db_input($pClient_state)."'"
            ;
            $res = $this->re_db_query($q);

            if ($this->re_db_num_rows($res))
                $return = $this->re_db_fetch_array($res);
            $return['result'] = ($return['active_check'] AND $trade_date>=$return['received'] AND ($trade_date<=$return['terminated'] OR in_array($return['terminated'], ['1969-12-31', '1970-01-01'])));

            if ($pDetail){
                // Just return $return
            } else {
                $return = $return['result'];
            }

            return $return;
        }

        public function select_archive_files(){
			$return = array();

			$q = "SELECT `at`.*"
					." FROM `".IMPORT_CURRENT_FILES."` AS `at`"
                    ." WHERE `at`.`is_delete`=0"
                      ." AND `at`.`process_completed`='1'"
                      ." AND `at`.`is_archived`='1'"
                    ." ORDER BY `at`.`imported_date` DESC"
            ;
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
                    WHERE `at`.`is_delete`=0 and `at`.`user_id`='".$_SESSION['user_id']."'
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

			$q = "SELECT `at`.*"
					." FROM `".IMPORT_EXCEPTION."` AS `at`"
                    ." WHERE `at`.`is_delete`=0"
                      ." AND `at`.`solved`='0'"
                      ." AND `at`.`file_id`='".$file_id."'"
                    ." ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $return = 4;
            }
			return $return;
		}
        public function get_exception_data($file_id, $exceptionCountOnly=0){
			$return = array();
			$countOnlyClause = ($exceptionCountOnly ? ' GROUP BY `temp_data_id`' : '' );

            // MAX(created_time)->last_created - Only return the last set of exceptions
            $q = "SELECT `ex`.*"
                    ." FROM (SELECT `temp_data_id`, MAX(`created_time`) AS `last_created` "
                            ." FROM `".IMPORT_EXCEPTION."`"
                            ." WHERE `is_delete`=0"
                              ." AND `file_id`='$file_id'"
                            ." GROUP BY `temp_data_id`) `a`"
                    ." LEFT JOIN `".IMPORT_EXCEPTION."` `ex` ON `a`.temp_data_id=`ex`.temp_data_id AND `a`.`last_created`=`ex`.`created_time`"
                    ." WHERE `ex`.`solved`=0"
                        ." AND `ex`.`is_delete`=0"
                    .$countOnlyClause
                    ." ORDER BY `temp_data_id`,`error_code_id`"
            ;
            $res = $this->re_db_query($q);

            if ($exceptionCountOnly){
                $return = $this->re_db_num_rows($res);
            } else {
                if($this->re_db_num_rows($res)>0){
                    while($row = $this->re_db_fetch_array($res)){
                         array_push($return,$row);
                    }
                }
            }
			return $return;
		}
        public function check_processed_data($file_id){
			$return = 0;

			$q = "SELECT `at`.*
					FROM `".IMPORT_EXCEPTION."` AS `at`
                    WHERE `at`.`is_delete`=0 and `at`.`solved`='1' and `at`.`file_id`='".$file_id."'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $return = 3;
            }
			return $return;
		}
        public function check_file_exception_process($file_id, $exceptionSummary=0, $detailTableConstant=''){
			$return = 0;

            if ($exceptionSummary==0){
                $q = "SELECT `at`.*
                        FROM `".IMPORT_CURRENT_FILES."` AS `at`
                        WHERE `at`.`is_delete`=0
                          AND `at`.`processed`='1'
                          AND `at`.`id`='".$file_id."'
                        ORDER BY `at`.`id` ASC";
                $res = $this->re_db_query($q);
                if($this->re_db_num_rows($res)>0){
                    $get_exceptions = $this->get_exception_data($file_id);
                    if($get_exceptions != array())
                    {
                        $return = 1;
                    }
                }
            } else {
                $return = ["file_id"=>$file_id, "file_name"=>'*Not Found*', "exceptions"=>0, "processed"=>0];

                if (empty($detailTableConstant)){
                    $tableArray = [IMPORT_DETAIL_DATA, IMPORT_IDC_DETAIL_DATA, IMPORT_SFR_DETAIL_DATA, IMPORT_GEN_DETAIL_DATA];
                } else {
                    $tableArray = [$detailTableConstant];
                }

                foreach ($tableArray AS $table){
                    $q = "SELECT `a`.`file_id`, `b`.`file_name`, SUM(if(`a`.`process_result`>0,0,1)) AS exceptions, SUM(if(`a`.`process_result`>0,1,0)) AS processed"
                        ." FROM `$table` `a`"
                        ." LEFT JOIN `".IMPORT_CURRENT_FILES."` `b` ON `a`.`file_id`=`b`.`id` AND `b`.`is_delete`=0"
                        ." WHERE `a`.`is_delete`=0 AND `file_id`=$file_id"
                        ." GROUP BY `a`.`file_id`, `b`.`file_name`"
                    ;

                    $res = $this->re_db_query($q);

                    if($this->re_db_num_rows($res)>0){
                        $fileTotals = $this->re_db_fetch_array($res);
                        $return['file_name'] = $fileTotals['file_name'];
                        $return['exceptions'] += $fileTotals['exceptions'];
                        $return['processed'] += $fileTotals['processed'];
                    }
                }
            }
			return $return;
		}
        public function get_fanmail_detail_data($file_id, $process_result=null, $record_id=0){
			$return = array();
			$con = '';

            if (!is_null($process_result)) {
                if ($process_result==0){
                    $con = " AND (`at`.`process_result`='0'"
                                  ." OR `at`.`process_result` IS NULL)";
                } else if ($process_result==1){
                    $con = " AND `at`.`process_result` IN ('1','2')";
                } else {
                    $con = " AND `at`.`process_result` = '$process_result'";
                }
            }

            if ($record_id){
                $record_id = (int)$this->re_db_input($record_id);
                $con .= " AND `at`.`id`=$record_id";
            }

			$q = "SELECT `at`.*"
					." FROM `".IMPORT_DETAIL_DATA."` AS `at`"
                    ." WHERE `at`.`is_delete`=0"
                    ." AND `at`.`file_id`='".$file_id."'"
                    .$con
                    ." ORDER BY `at`.`id` ASC"
            ;
			$res = $this->re_db_query($q);

            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
                    array_push($return,$row);
    			}
            }
			return $return;
		}
        public function get_sfr_detail_data($id, $process_result=null, $record_id=0){
			$return = array();
            $con = '';

            if (!is_null($process_result)){
                $con = " AND (`at`.`process_result` = '".$process_result."'"
                       .($process_result==0 ? " OR `at`.`process_result` IS NULL" : "")
                       .")"
                ;
            }

            if ($record_id){
                $record_id = (int)$this->re_db_input($record_id);
                $con .= " AND `at`.`id`=$record_id";
            }

			$q = "SELECT `at`.*
					FROM `".IMPORT_SFR_DETAIL_DATA."` AS `at`
                    WHERE `at`.`is_delete`=0
                      AND `at`.`file_id`='".$id."'"
                      .$con."
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
        public function get_idc_detail_data($file_id, $process_result=null, $record_id=0){
			$return = array();
            $con = '';

            if (!is_null($process_result)) {
                $con = " AND (`at`.`process_result` = '".$process_result."'"
                       .($process_result==0 ? " OR `at`.`process_result` IS NULL" : "")
                       .")"
                ;
            }

            if ($record_id){
                $record_id = (int)$record_id;
                $con .= " AND `at`.`id`=$record_id";
            }

			$q = "SELECT `at`.*,`idch`.`system_id`,`idch`.`management_code`
					FROM `".IMPORT_IDC_DETAIL_DATA."` AS `at`
                    LEFT JOIN `".IMPORT_IDC_HEADER_DATA."` as `idch` on `idch`.`id`=`at`.`idc_header_id`
                    WHERE `at`.`is_delete`=0
                      AND `at`.`file_id`='".$file_id."'
                      ".$con."
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
        public function get_gen_detail_data($file_id, $process_result=null, $record_id=0){
			$return = array();
            $con = '';
            $file_id = (int)$file_id;
            
            if (!is_null($process_result)) {
                $con = " AND (`at`.`process_result` = '".$process_result."'"
                       .($process_result==0 ? " OR `at`.`process_result` IS NULL" : "")
                       .")"
                ;
            }

            if ($record_id){
                $record_id = (int)$record_id;
                $con .= " AND `at`.`id`=$record_id";
            }

			$q = "SELECT `at`.*"
					." FROM `".IMPORT_GEN_DETAIL_DATA."` AS `at`"
                    ." WHERE `at`.`is_delete` = 0"
                    ." AND `at`.`file_id` = $file_id"
                        .$con
                    ." ORDER BY `at`.`id`"
            ;
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
                ini_set("memory_limit", "1024M");

                $file_string_array = array();
                $get_file = $this->select_user_files($file_id);
                $file_name = $get_file['file_name'];
                // $file_path = DIR_FS."import_files/user_".$_SESSION['user_id']."/".$file_name;
                $file_path = DIR_FS."import_files/".$file_name;
                if(!file_exists($file_path)){
                    return ;
                }
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
                                $data_array[$array_key]['DETAIL'][$array_detail_key][$detail_record_type] = array("record_type3" => substr($val_string, 0, 3),"sequence_number3" => substr($val_string, 3, 3),"account_registration_line5" => substr($val_string, 6, 35),"account_registration_line6" => substr($val_string, 41, 35),"account_registration_line7" => substr($val_string, 76, 35),"representative_number" => trim(substr($val_string, 111, 9)),"representative_name" => substr($val_string, 120, 30),"position_close_out_indicator" => substr($val_string, 150, 1),"account_type_indicator" => substr($val_string, 151, 4),"product_identifier_code(VA only)" => substr($val_string, 155, 3),"mutual_fund_alternative_investment_program_managers_variable_annuties_and_VUL" => substr($val_string, 158, 2));

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
                                $data_array[$array_key]['DETAIL'][$commission_record_type_code][] = array("dst_system_id"=>$dst_system_id,"dst_management_code"=>$dst_management_code,"commission_record_type_code" => substr($val_string, 0, 1),"dealer_number" => substr($val_string, 1, 7),"dealer_branch_number" => substr($val_string, 8, 9),"representative_number" => trim(substr($val_string, 17, 9)),"representative_name" => substr($val_string, 26, 30),"cusip_number" => substr($val_string, 56, 9),"alpha_code" => substr($val_string, 65, 10),"trade_date" => substr($val_string, 75, 8),"gross_transaction_amount" => substr($val_string, 83, 15),"gross_amount_sign_code" => substr($val_string, 98, 1),"dealer_commission_amount" => substr($val_string, 99, 15),"commission_rate" => substr($val_string, 114, 5),"customer_account_number" => substr($val_string, 119, 20),"account_number_type_code" => substr($val_string, 139, 1),"purchase_type_code" => substr($val_string, 140, 1),"social_code" => substr($val_string, 141, 3),"cumulative_discount_number" => substr($val_string, 144, 9),"letter_of_intent(LOI)_number" => substr($val_string, 153, 9),"social_security_number" => substr($val_string, 162, 9),"social_security_number_status_code" => substr($val_string, 171, 1),"transaction_share_count" => substr($val_string, 172, 15),"share_price_amount" => substr($val_string, 187, 9),"resident_state_country_code" => substr($val_string, 196, 3),"dealer_commission_sign_code" => substr($val_string, 199, 1));
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
                    WHERE `dtl`.`is_delete`=0 and `dtl`.`file_id`='".$file_id."' and `dtl`.`id`='".$data_id."'
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
                    WHERE `dtl`.`is_delete`=0 and `dtl`.`file_id`='".$file_id."' and `dtl`.`id`='".$data_id." and `on_hold`=0'
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
                    WHERE `at`.`is_delete`=0
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
                    WHERE `at`.`is_delete`=0 and `at`.`solved`='1' and `at`.`process_completed`='1' and `at`.`file_id`='".$file_id."' and `cf`.`user_id`='".$_SESSION['user_id']."'
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
         * 02/11/22 "$record_id" parmater added to pull just one record at a time
         * 02/16/22 Free form "WHERE" query parameter for Resolve Exceptions, looking for specific exceptions
         * @param mixed $file_id
         * @return array
         */
        public function select_exception_data($file_id=0, $record_id=0, $customWhere=''){
			$return = array();
            $con = '';

            if (empty($file_id) AND $record_id > 0){
                $q = "SELECT `at`.*,`em`.`error` AS `error_description`"
                        ." FROM `".IMPORT_EXCEPTION."` AS `at`"
                        ." LEFT JOIN `".IMPORT_EXCEPTION_MASTER."` AS `em` on `at`.`error_code_id` = `em`.`id`"
                        ." WHERE `at`.`id` = $record_id"
                        ." ORDER BY `at`.`id` ASC"
                ;
            } else if($customWhere != '') {
                $q = "SELECT `at`.*, `em`.`error`"
                        ." FROM `".IMPORT_EXCEPTION."` `at`"
                        ." LEFT JOIN `".IMPORT_EXCEPTION_MASTER."` AS `em` on `at`.`error_code_id` = `em`.`id`"
                        ." WHERE $customWhere"
                        ." ORDER BY `id` ASC"
                ;
            } else {
                $q = "SELECT `at`.*,`em`.`error`"
                        ." FROM `".IMPORT_EXCEPTION."` AS `at`"
                        ." LEFT JOIN `".IMPORT_EXCEPTION_MASTER."` AS `em` on `at`.`error_code_id` = `em`.`id`"
                        ." LEFT JOIN `".IMPORT_CURRENT_FILES."` AS `cf` on `at`.`file_id` = `cf`.`id`"
                        ." WHERE `at`.`is_delete`=0 and `at`.`solved`='0' and `at`.`file_id`='".$file_id."'"
                        ." ORDER BY `at`.`id` ASC"
                ;
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
        public function select_single_exception_data($file_id,$exception_id){
			$return = array();

			$q = "SELECT `at`.*,`em`.`error`"
					." FROM `".IMPORT_EXCEPTION."` AS `at`"
                    ." LEFT JOIN `".IMPORT_EXCEPTION_MASTER."` AS `em` on `at`.`error_code_id` = `em`.`id`"
                    ." LEFT JOIN `".IMPORT_CURRENT_FILES."` AS `cf` on `at`.`file_id` = `cf`.`id`"
                    ." WHERE `at`.`is_delete`=0"
                    ." AND `at`.`solved`='0'"
                    ." AND `at`.`file_id`='".$file_id."'"
                    ." AND `at`.`temp_data_id`='".$exception_id."'"
                    ." ORDER BY `at`.`id` ASC"
            ;
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

			$q = "SELECT `sfr`.*"
					." FROM `".IMPORT_SFR_DETAIL_DATA."` AS `sfr`"
                    ." WHERE `sfr`.`is_delete`=0 and `sfr`.`id`='".$temp_data_id."'"
                    ." ORDER BY `sfr`.`id` ASC"
            ;
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

			$q = "SELECT `idc`.*"
					." FROM `".IMPORT_IDC_DETAIL_DATA."` AS `idc`"
                    ." WHERE `idc`.`is_delete`=0"
                    ."  AND `idc`.`id`='".$temp_data_id."'"
                    ." ORDER BY `idc`.`id` ASC"
            ;
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;

    			}
            }
			return $return;
		}
        public function select_existing_gen_data($temp_data_id){
			$return = array();

			$q = "SELECT `idc`.*"
					." FROM `".IMPORT_GEN_DETAIL_DATA."` AS `idc`"
                    ." WHERE `idc`.`is_delete`=0"
                    ."  AND `idc`.`id`='".$temp_data_id."'"
                    ." ORDER BY `idc`.`id` ASC"
            ;
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
                $con = " AND `sp`.`dst_system_id`='".strtoupper(trim($system_id))."'";
            }
            if($management_code!='')
            {
                $con .= " AND `sp`.`dst_mgmt_code`='".strtoupper(trim($management_code))."'";
            }
			//
			$q = "SELECT `sp`.*
					FROM `".SPONSOR_MASTER."` AS `sp`
                    WHERE `sp`.`is_delete`=0 ".$con."
                    ORDER BY `sp`.`id` ASC";
			$res = $this->re_db_query($q);
            // Just return the first find, instead of an array of arrays, because the
            // calling programs are expecting just one sponsor per SysId/MgtCode 12/5/21
            if($this->re_db_num_rows($res)>0){
                $return = $this->re_db_fetch_array($res);
                // while($row = $this->re_db_fetch_array($res)){
    			//      $return = $row;
    			// }
            }
			return $return;
		}
        public function select_existing_fanmail_data($temp_data_id){
			$return = array();

			$q = "SELECT `fan`.*
					FROM `".IMPORT_DETAIL_DATA."` AS `fan`
                    WHERE `fan`.`is_delete`=0 and `fan`.`id`='".$temp_data_id."'
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
        public function select_solved_exception_data($file_id, $file_type=0){
			$return = array();
            $con = ($file_type) ? " AND `at`.`file_type`=".$file_type : '';

			$q = "SELECT `at`.*"
					." FROM `".IMPORT_EXCEPTION."` AS `at`"
                    ." LEFT JOIN `".IMPORT_CURRENT_FILES."` AS `cf` on `at`.`file_id` = `cf`.`id`"
                    ." WHERE `at`.`is_delete`=0"
                      ." AND `at`.`solved`='1'"
                      ." AND `at`.`file_id`='".$file_id."'"
                      .$con
                    ." ORDER BY `at`.`id` ASC"
            ;
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
                    WHERE `at`.`is_delete`=0 and `at`.`solved`='1' and `at`.`file_id`='".$file_id."' and `cf`.`user_id`='".$_SESSION['user_id']."'
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
            $id = (int)$id;

			$q = "SELECT `at`.*"
                ." FROM `".IMPORT_CURRENT_FILES."` AS `at`"
                ." WHERE `at`.`is_delete`=0"
                  ." AND `at`.`id`=$id"
                ." ORDER BY `at`.`imported_date` DESC"
            ;
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;

    			}
            }
			return $return;
		}
        public function check_current_files($user_id=0){
			$return = array();
            $userQuery = ($user_id==0 ? '' : "`at`.`user_id`='$user_id'");
            
			$q = "SELECT `at`.`file_name`"
				    ." FROM `".IMPORT_CURRENT_FILES."` AS `at`"
                    ." WHERE `at`.`is_delete`=0"
                        .$userQuery
                    ." ORDER BY `at`.`id` ASC"
            ;
			
            $res = $this->re_db_query($q);
            
            if($this->re_db_num_rows($res)>0){
    		    while($row = $this->re_db_fetch_array($res)){
    			     $return[] = ($user_id==0 ? strtoupper($row['file_name']) : $row['file_name']);
                }
            }
			return $return;
		}
        public function check_file_type($file_id, $typeCode=0){
			$return = array();
            $returnField = $typeCode==0 ? "source" : "file_type";
            
			$q = "SELECT `at`.`source`, `at`.`file_type`"
					." FROM `".IMPORT_CURRENT_FILES."` AS `at`"
                    ." WHERE `at`.`is_delete`=0 and `at`.`id`='".$file_id."'"
                    ." ORDER BY `at`.`id` ASC"
            ;
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row[$returnField];
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
                    WHERE `fd`.`is_delete`=0 and `fd`.`id`='".$record_id."' and `fd`.`file_id`='".$file_id."'
                    ORDER BY `fd`.`id` ASC";
			    $res = $this->re_db_query($q);
            }
            else
            {
                $q = "SELECT `idcd`.`idc_header_id`,`ih`.*
					FROM `".IMPORT_IDC_DETAIL_DATA."` AS `idcd`
                    LEFT JOIN `".IMPORT_IDC_HEADER_DATA."` AS `ih` on `ih`.`id`=`idcd`.`idc_header_id`
                    WHERE `idcd`.`is_delete`=0 and `idcd`.`id`='".$record_id."' and `idcd`.`file_id`='".$file_id."'
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
        public function get_file_batch($file_id, $return_field='batch_id'){
			$return = 0;
            $q = $res = '';

			$q = "SELECT `at`.`id` as batch_id, `at`.*"
					." FROM `".BATCH_MASTER."` AS `at`"
                    ." WHERE `at`.`is_delete`=0"
                      ." AND `at`.`file_id`='".$file_id."'"
                    ."ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row[$return_field];
                }
            }
			return $return;
		}
        public function get_file_type($file_id){
			$return = '';

			$q = "SELECT `at`.`file_type`
					FROM `".IMPORT_EXCEPTION."` AS `at`
                    WHERE `at`.`is_delete`=0 and `at`.`file_id`='".$file_id."'
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
        public function get_current_file_type($file_id=0, $fieldName='source'){
			$return = '';
            $file_id = (is_null($file_id) ? 0 : $file_id);

			$q = "SELECT `at`.`$fieldName`"
				    ." FROM `".IMPORT_CURRENT_FILES."` `at`"
                    ." WHERE `at`.`is_delete`=0"
                     ." AND `at`.`id`=$file_id"
                    ." ORDER BY `at`.`id` DESC";

			$res = $this->re_db_query($q);

            if($this->re_db_num_rows($res)){
                // $a = 0;
    			$row = $this->re_db_fetch_array($res);
                $return = $row[$fieldName];
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
                  WHERE file_id='".$file_id."' AND status=1 AND is_delete=0 ".$con."
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
                    WHERE `at`.`is_delete`=0
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
                    WHERE `at`.`is_delete`=0
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
            $id = (int)$id;

			if($id>0){
				$q = "UPDATE `".$this->table."` SET `is_delete`='1' WHERE `id`=$id";
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

        public function save_note($data)
        {
            $id   = isset($data['id'])?$this->re_db_input($data['id']):0;
            $note = isset($data['note_'.$id])?$this->re_db_input($data['note_'.$id]):'';
            if (!$id) {
                $this->errors = "Missing ID";
            }
            if($this->errors!=''){
                return $this->errors;
            }
            $sql = "UPDATE `".IMPORT_CURRENT_FILES."` SET `note`='".$note."' ".$this->update_common_sql().
                   "WHERE `id`=".(int)$id;
            $res = $this->re_db_query($sql);
            if($res) {
                $_SESSION['success'] = UPDATE_MESSAGE;
                return true;
            }
            else {
                $_SESSION['warning'] = UNKWON_ERROR;
                return false;
            }

        }

        /** Iterate through array of Id's from IMPORT EXCEPTIONS table and return detail of the exception
         * @param string $serialValue -> serialize()'d array taken from IMPORT DETAIL (client, IDC, SFR) tables ("resolve exceptions")
         * @return array -> $key=Error Code Id, sub-array['field'=populated in resolve exception(), 'error'=description, 'resolve_action'=action code user chose, 'resolve_assign_to'->client, broker, or product assigned to a trade or table ]
         */
        function getDetailExceptions($serialValue=''){
            $return = [];
            $excArray = $excDetail = 0;
            $actionArray = [1=>'hold', 2=>'add', 3=>'reassign', 4=>'delete', 5=>'add_new'];

            if (!empty($serialValue) AND !is_array($serialValue)){
                $excArray = unserialize($serialValue);
            } else if (is_array($serialValue)){
                $excArray = $serialValue;
            }

            if (is_array($excArray)){
                foreach ($excArray AS $excId){
                    $excDetail = $this->select_exception_data(0, $excId);

                    if (count($excDetail)){
                        $return[$excDetail[0]['error_code_id']] = [
                            'exception_id'=>$excDetail[0]['id'],
                            'field'=>$excDetail[0]['field'],
                            'error'=>$excDetail[0]['error_description'],
                            'resolve_action'=>(array_key_exists($excDetail[0]['resolve_action'], $actionArray) ? $actionArray[$excDetail[0]['resolve_action']] : (string)$excDetail[0]['resolve_action']),
                            'resolve_assign_to'=>$excDetail[0]['resolve_assign_to']
                        ];
                    }
                }
            }

            return $return;
        }
        
        // $fileArray must be the array from a $_FILES['elementName'] that comes from an HTTP $_POST[]: contains ['name'=>'', 'tmp_name'=>'(path/name -> created by PHP)',...]
  		function upload_file($fileArray, $toFolder){
			$return = 0;
			$moveToFolder = empty($toFolder) ? 'import_files' : rtrim($toFolder, "/")."/";

            if (!empty($fileArray['name'])){
                $return = move_uploaded_file($fileArray['tmp_name'], DIR_FS.$moveToFolder.$fileArray['name']);
			}

			return $return;
		}
        
        // PHP Version of console.log(). For debugging on the server
        function PHPconsole_log($output, $with_script_tags = true) {
            $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
            if ($with_script_tags) {
                $js_code = '<script>' . $js_code . '</script>';
            }
            echo $js_code;
        }

  }
?>