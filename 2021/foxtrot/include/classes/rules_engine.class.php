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
        public function select(){
			$return = array();

			$q = "SELECT `at`.*
					FROM `".$this->table."` AS `at` WHERE `at`.`is_delete`='0'
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

		function import_rule($error_code_id, $commDetailTable='', $tradeDetailParameter=[], $resolveHoldCommission=[]){
			$instance_import = new import();
			$instance_broker_master = new broker_master();
			// Return nothing if all the parameters are not specified
			if (empty($error_code_id) OR empty($commDetailTable) OR empty($tradeDetailParameter)){
				return [];
			}

			// Initialize the Return array
			$tradeDetailArray = $tradeDetailParameter;
			$tradeDetailArray['ruleExceptionUpdate'] = '';
			// $tradeDetailArray['broker'] = [];
			// $tradeDetailArray['result'] = $tradeDetailArray['ruleProceed'] = 0;

			$ruleAction = $this->get_action(null, $error_code_id, 1);
			$tradeDetailArray['import_action_id'] = (int)$ruleAction[0]['import_action_id'];
			$tradeDetailArray['rule_action_id'] = (int)$ruleAction[0]['action_id'];

			if ($ruleAction[0]['in_force']) {
				// DO nothing, populate variables to let the trade through
				if ($tradeDetailArray['rule_action_id']==1){
					$tradeDetailArray['ruleExceptionUpdate'] =
						",`rule_action`=".($ruleAction[0]['action_id'])
						.",`is_delete`=1";

					// Increment result so the trade won't be entered, but "serial field" has to be triggered
					$tradeDetailArray['resultIncrement'] = 0;
					$tradeDetailArray['ruleProceed'] = 1;
				}

				switch ((int)$ruleAction[0]['import_action_id']){
					case 1:
						// Hold Commission
						$tradeDetailArray['on_hold'] = 1;
						array_push($resolveHoldCommission, $error_code_id);
						$tradeDetailArray['resolveHoldCommission'] = $resolveHoldCommission;

						// Update the table import tables
						$q ="UPDATE `".$commDetailTable."`"
							." SET `on_hold`=1"
								.$instance_import->update_common_sql()
							." WHERE `is_delete`=0"
							." AND `id`=".$tradeDetailArray['id']
						;
						$res = $this->re_db_query($q);

						$tradeDetailArray['ruleExceptionUpdate'] =
							", `rule_action`=".($ruleAction[0]['action_id'])
							.", `is_delete`=1";

						$tradeDetailArray['ruleProceed'] = 1;
						break;
					case 3:
						// Reassign to another broker
						if ((int)$ruleAction[0]['parameter1']){
							$ruleBroker = $instance_broker_master->select_broker_by_id($ruleAction[0]['parameter1']);

							if (!empty($ruleBroker)){
								$tradeDetailArray['broker'] = $ruleBroker;
								$tradeDetailArray['brokerAlias'] = [];
								$tradeDetailArray['broker_id'] = $ruleBroker['id'];
								$tradeDetailArray['reassignBroker'] = $error_code_id;

								// Update the table import tables
								$q ="UPDATE `".$commDetailTable."`"
									." SET `broker_id`=".(int)$this->re_db_input($ruleAction[0]['parameter1'])
											.$this->update_common_sql()
									." WHERE `is_delete`=0"
									." AND `id`=".$tradeDetailArray['id']
								;
								$res = $this->re_db_query($q);

								$tradeDetailArray['ruleExceptionUpdate'] =
										", `rule_action`=".($ruleAction[0]['action_id'])
									.", `rule_parameter1`='".($ruleAction[0]['parameter1'])."'"
									.", `is_delete`=1";

								$tradeDetailArray['ruleProceed'] = 1;
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

						$tradeDetailArray['ruleExceptionUpdate'] =
							",`rule_action`=".($ruleAction[0]['action_id'])
							.",`is_delete`=1";

						// Increment result so the trade won't be entered, but "serial field" has to be triggered
						$tradeDetailArray['resultIncrement'] = 1;
						$tradeDetailArray['ruleProceed'] = 1;
						break;
				}
			}

			return $tradeDetailArray;
		}

	}
?>