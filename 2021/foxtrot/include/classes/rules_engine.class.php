<?php

    class rules extends db{

        public $errors = '';
        public $table = RULE_DETAILS;


        public function insert_update($data){
			//echo '<pre>';print_r($data);exit;

			$qq="UPDATE ".$this->table." SET `is_delete`='1'";
			$this->re_db_query($qq);

			foreach($data['data'] as $key=>$val){
				$in_force= isset($val['in_force'])?$this->re_db_input($val['in_force']):'0';
				$rule= isset($val['rule'])?$this->re_db_input($val['rule']):'';
				$action= isset($val['action'])?$this->re_db_input($val['action']):'0';
				$parameter_1= isset($val['parameter_1'])?$this->re_db_input($val['parameter_1']):'';
				$parameter1= isset($val['parameter1'])?$this->re_db_input($val['parameter1']):0;
				$parameter_2= isset($val['parameter_2'])?$this->re_db_input($val['parameter_2']):'';
				$parameter2= isset($val['parameter2'])?$this->re_db_input($val['parameter2']):0;

				if($parameter1=='0'&& $action=='5'){
					$this->errors = 'Please select broker1.';
				}
				/*else if($parameter2=='0'&& $action=='5'){
					$this->errors = 'Please select broker2.';
				}*/
				else if(isset($in_force) && $in_force != 0 && $action=='0'){
					$this->errors = 'Please select action.';
				}
				/*else if($parameter_1=='' && $action != 5){
					$this->errors = 'Please enter parameter1.';
				}
				else if($parameter_2==''&& $action != 5){
					$this->errors = 'Please select parameter2.';
				}*/
				if($this->errors!=''){
					return $this->errors;
				}
				else{
					$q = "INSERT INTO ".$this->table." SET `in_force`='".$in_force."',`rule`='".$rule."',`action`='".$action."',
					`parameter_1`='".$parameter_1."',`parameter1`='".$parameter1."',`parameter_2`='".$parameter_2."',
					`parameter2`='".$parameter2."'".$this->insert_common_sql();

					$res = $this->re_db_query($q);
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
        public function select_rules(){
			$return = array();

			$q = "SELECT `at`.*"
					." FROM `".RULE_MASTER."` AS `at`"
					." WHERE `at`.`is_delete`=0"
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
        public function select_rules_action($rule_id=null, $error_code_id=0){
			$return = array();

			$q = "SELECT `at`.*"
					." FROM `".RULE_ACTION_MASTER."` AS `at`"
					." WHERE `at`.`is_delete`=0"
					." ORDER BY `at`.`id` ASC";

			$res = $this->re_db_query($q);

            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        public function get_broker_name(){
			$return = array();
			$q = "SELECT `at`.id,`at`.first_name,`at`.last_name
					FROM `".BROKER_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        public function select($ruleId=0){
			$return = array();
			$con = '';
			$ruleId = (int)$this->re_db_input($ruleId);

			if ($ruleId){
				$con = " AND `rule`=$ruleId";
			}

			$q = "SELECT `at`.*"
					." FROM `".$this->table."` AS `at`"
					." WHERE `at`.`is_delete`='0'"
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

        public function get_action($rule_id=0, $error_code_id=0, $inForce=0){
			$return = [[
				'detail_id'=>0,
				'in_force'=>0,
				'rule_id'=>0,
				'error_code_id'=>0,
				'rule_name'=>'',
				'action_id'=>0,
				'import_action_id'=>0,
				'action_name'=>'',
				'parameter1'=>0,
				'parameter_1'=>'',
				'parameter2'=>0,
				'parameter_2'=>''
			]];
			$con = '';

			if (!empty($rule_id)){
				$con .= " AND `a`.`id` = ".(int)$this->re_db_input($rule_id);
			}
			if (!empty($error_code_id)){
				$con .= " AND `b`.`error_code_id` = ".(int)$this->re_db_input($error_code_id);
			}
			if (!empty($inForce)){
				$con .= " AND `a`.`in_force` = ".(int)$this->re_db_input($inForce);
			}

			$q = "SELECT"
					." `a`.`id` AS `detail_id`"
					.",`a`.`in_force`"
					.",`a`.`rule` AS `rule_id`"
					.",`b`.`error_code_id`"
					.",`b`.`rule` AS `rule_name`"
					.",`a`.`action` AS `action_id`"
					.",`c`.`import_id` AS `import_action_id`"
					.",`c`.`action` AS `action_name`"
					.",`a`.`parameter1`"
					.",`a`.`parameter_1`"
					.",`a`.`parameter2`"
					.",`a`.`parameter_2`"
				." FROM `".RULE_DETAILS."` `a` "
				." LEFT JOIN `".RULE_MASTER."` `b` ON `a`.`rule` = `b`.`id` AND `b`.`is_delete`=0"
				." LEFT JOIN `".RULE_ACTION_MASTER."` `c` ON `a`.`action` = `c`.`id` AND `c`.`is_delete`=0"
				." WHERE `a`.`is_delete` = 0"
					.$con
				." ORDER BY `a`.`rule`";

			$res = $this->re_db_query($q);

            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_all($res);
            }
			return $return;
		}
		
		
		/**
		 * NOTE: $tradeDetailArray is passed as REFERENCE, so be careful when manipulating it 
		 **/
		function import_rule($error_code_id, $fieldName='', $fieldValue='', $insert_exception_string='', $commDetailTable='', &$tradeDetailArray=[], $resolveHoldCommission=[], $ignoreException=[]){
			$instance_import = new import();
			$instance_broker_master = new broker_master();
			// Return nothing if all the parameters are not specified
			if (empty($error_code_id) OR empty($commDetailTable) OR empty($tradeDetailArray) OR in_array((int)$error_code_id, $ignoreException)){
				return $tradeDetailArray;
			}

			// Initialize the Return array
			$tradeDetailArray['importExceptionUpdate'] = '';
			$ruleAction = $this->get_action(null, $error_code_id, 1);
			$tradeDetailArray['import_action_id'] = (int)$ruleAction[0]['import_action_id'];
			$tradeDetailArray['rule_action_id'] = (int)$ruleAction[0]['action_id'];
			$tradeDetailArray['in_force'] = $ruleAction[0]['in_force'];
			
			if ($ruleAction[0]['in_force'] AND !in_array($error_code_id, $resolveHoldCommission)) {
				switch ((int)$ruleAction[0]['import_action_id']){
					case 1:
						//-- Hold Commission
						// Update the table import tables
						$q ="UPDATE `".$commDetailTable."`"
							." SET `on_hold`=1"
								.$instance_import->update_common_sql()
							." WHERE `is_delete`=0"
							." AND `id`=".$tradeDetailArray['id']
						;
						$res = $this->re_db_query($q);

						$tradeDetailArray['importExceptionUpdate'] =
							 ", `rule_action`=".($ruleAction[0]['action_id'])
							.", `is_delete`=1";
						$tradeDetailArray['ruleProceed'] = 1;
						$tradeDetailArray['on_hold'] = 1;
						array_push($resolveHoldCommission, $error_code_id);
						$tradeDetailArray['XXresolveHoldCommission'] = $resolveHoldCommission;
						break;
					case 3:
						// Reassign to another broker
						if ((int)$ruleAction[0]['parameter1']){
							$ruleBroker = $instance_broker_master->select_broker_by_id($ruleAction[0]['parameter1']);

							if (!empty($ruleBroker)){
								// Update the table import tables
								$q ="UPDATE `".$commDetailTable."`"
									." SET `broker_id`=".(int)$this->re_db_input($ruleAction[0]['parameter1'])
											.$this->update_common_sql()
									." WHERE `is_delete`=0"
									." AND `id`=".$tradeDetailArray['id']
								;
								$res = $this->re_db_query($q);

								$tradeDetailArray['importExceptionUpdate'] =
									", `rule_action`=".($ruleAction[0]['action_id'])
									.", `rule_parameter1`='".($ruleAction[0]['parameter1'])."'"
									.", `is_delete`=1";

								$tradeDetailArray['ruleProceed'] = 1;
								$tradeDetailArray['XXbroker'] = $ruleBroker;
								$tradeDetailArray['XXbrokerAlias'] = [];
								$tradeDetailArray['XXbroker_id'] = $ruleBroker['id'];
								$tradeDetailArray['XXreassignBroker'] = $error_code_id;
							}
						}
						break;
					case 4:
						// Delete/Remove Trade
						// Update the table import tables
						$q ="UPDATE `".$commDetailTable."`"
							." SET `is_delete` = 1"
									.$this->update_common_sql()
							." WHERE `is_delete`=0"
							." AND `id`=".$tradeDetailArray['id']
						;
						$res = $this->re_db_query($q);

						$tradeDetailArray['importExceptionUpdate'] =
							",`rule_action`=".($ruleAction[0]['action_id'])
							.",`is_delete`=1";

						// Increment result so the trade won't be entered, but "serial field" has to be triggered
						$tradeDetailArray['YYresult'] = 1;
						$tradeDetailArray['ruleProceed'] = 1;
						break;
					default:
						// Display Warning(Manual Entry) / Create Exception (Import)
						$tradeDetailArray['importExceptionUpdate'] =
							",`rule_action`=".($ruleAction[0]['action_id'])
							.",`is_delete`=0";
						$tradeDetailArray['YYresult'] = 1;
						$tradeDetailArray['ruleProceed'] = 0;
				}
	
				$q = "INSERT INTO `".IMPORT_EXCEPTION."`"
					." SET  `error_code_id`=$error_code_id"
							.",`field`='$fieldName'"
							.",`field_value`='".$fieldValue."'"
							.",`file_type`={$tradeDetailArray['file_type']}"
							.$tradeDetailArray['importExceptionUpdate']
							.$insert_exception_string
				;
				$res = $this->re_db_query($q);
				$last_inserted_exception = $this->re_db_insert_id();
	
				if (!empty($tradeDetailArray['ruleProceed'])) {
					$res = $this->read_update_serial_field($commDetailTable, "WHERE `id`={$tradeDetailArray['id']}", 'resolve_exceptions', $last_inserted_exception);
				}
			}

			return $tradeDetailArray;
		}

		/** Check if client's NAF date is entered - for Rule Engine/Compliance module - 5/27/2022
         * @param int $clientId
         * @param string $checkDate
         * @return bool|int
         */
        function check_client_documentation($clientId=0){
			// Rule #4
            $return = $res = 0;
            $blankDate = date("Y-m-d 00:00:00", strtotime(''));
            $clientId = (int)$this->re_db_input($clientId);

            if ($clientId){
                $instance_client_maintenance = new client_maintenance();
                $res = $instance_client_maintenance->select_client_master($clientId);
            }

            if ($res){
                // So far, only NAF (New Account Form) is mandatory - Save room for more later
                $return = (
                    !$this->isEmptyDate($res['naf_date'])
                );
            }

            return $return;
        }

		// -- Calculate Age - probably another somewhere, but I can't find it - 5/28/22
		function getAge($birthDate=''){
			$birthDate = $this->re_db_input($birthDate);
			if ( $this->isEmptyDate($birthDate)  )
				return 0;

			$age = date("Y") - date("Y", strtotime($birthDate));
			if (date("m-d", strtotime($birthDate)) > date("m-d"))
				$age--;
			return $age;
		}

		function check_client_age($clientId=0){
			// Rule #14 - Client of Legal Age - default to "true" if the "birth_date" is not populated in CLIENT MASTER
            $return = $res = $res2 = $age = 0;
			$ruleId = 14;
			// Defaults - to be populated later, per client's feedback(Beta Tes)
			$min = 18; //0;
			$max = 80; //999;
            $blankDate = date("Y-m-d 00:00:00", strtotime(''));
            $clientId = (int)$this->re_db_input($clientId);

            if ($clientId){
                $instance_client_maintenance = new client_maintenance();
                $res = $instance_client_maintenance->select_client_master($clientId);
				$res2 = $this->select($ruleId);
            }

            if ($res AND $res2){
				if ($this->isEmptyDate($res['birth_date'])){
					$return = 1;
				} else {
					$age = $this->getAge($res['birth_date']);
					$min = (empty($res2[0]['parameter_1']) ? $min : (int)$res2[0]['parameter_1']);
					$max = (empty($res2[0]['parameter_2']) ? $max : (int)$res2[0]['parameter_2']);

					$return = (
						$min <= $age
						AND $age <= $max
					);
				}
            }

			return $return;
		}

        function check_client_identity($clientId=0, $checkDate=''){
			// Rule #17
            $return = $res = 0;
            $checkDate = empty($checkDate) ? date("Y-m-d 00:00:00") : date("Y-m-d 00:00:00", strtotime($this->re_db_input($checkDate)));

            if ($clientId AND $checkDate){
                $instance_client_maintenance = new client_maintenance();
                $res = $instance_client_maintenance->select_client_employment_by_id($clientId);
            }

            if ($res){
                $return = (
                        (in_array($res['options'], [1,2]) OR ($res['options']==3 AND $res['other']!=''))
                    AND $res['number'] != ''
                    AND $res['expiration'] >= $checkDate
                    AND ($res['options']!=1 OR $res['state'] > 0)
                    AND !$this->isEmptyDate($res['date_verified'])
                );
            }

            return $return;
        }

        function check_broker_sponsor($brokerId=0, $sponsorId=0){
            $return = $res = 0;
			$brokerId = (int)$this->re_db_input($brokerId);
			$sponsorId = (int)$this->re_db_input($sponsorId);

            if ($brokerId AND $sponsorId){
                $instance_broker_master = new broker_master();
                $res = $instance_broker_master->edit_alias($brokerId, $sponsorId);
				$return = count($res);
            }

            return $return;
        }

		function check_client_field($clientId=0, $fieldName='id', $ifEqualsValue=''){
			// Rule 15(Birth Date), 18(State), 21(Import VS Client Broker)
            $return = $res = $res2 = 0;
            $clientId = (int)$this->re_db_input($clientId);
			$fieldName = strtolower($this->re_db_input($fieldName));

            if ($clientId){
                $instance_client_maintenance = new client_maintenance();
                $res = $instance_client_maintenance->select_client_master($clientId);
            }

            if ($res){
				if (array_key_exists($fieldName, $res)){
					$fieldValue = trim($this->re_db_input($res[$fieldName]));

					if (strpos($fieldName, 'date')){
						$return = (empty($ifEqualsValue) ? !$this->isEmptyDate($fieldValue) : $fieldValue==$ifEqualsValue);
					} else {
						$return = (empty($ifEqualsValue) ? !empty($fieldValue) : $fieldValue==$ifEqualsValue);
					}
				} else {
					$return = null;
				}
            }

			return $return;
		}

	}
?>