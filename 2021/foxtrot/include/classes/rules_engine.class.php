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

			$q = "SELECT `at`.*
					FROM `".RULE_MASTER."` AS `at`
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
        public function select_rules_action($rule_id=null, $error_code_id=0){
			$return = array();

			$q = "SELECT `at`.*
					FROM `".RULE_ACTION_MASTER."` AS `at`
					ORDER BY `at`.`id` ASC";

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

        public function get_action($rule_id=0, $error_code_id=0){
			$return = [[
				'id'=>0,
				'in_force'=>0,
				'rule'=>0,
				'rule_name'=>'',
				'action'=>0,
				'import_action_id'=>0,
				'action_name'=>'',
				'parameter1'=>'',
				'parameter2'=>'',
				'error_code_id'=>0
			]];
			$con = '';

			if (!empty($rule_id)){
				$con .= " AND `a`.`id` = ".(int)$this->re_db_input($rule_id);
			}
			if (!empty($error_code_id)){
				$con .= " AND `b`.`error_code_id` = ".(int)$this->re_db_input($error_code_id);
			}

			$q =
				"SELECT `a`.`id`, `a`.`in_force`, `a`.`rule`, "
					 ." `b`.`rule` AS `rule_name`, `a`.`action`, `c`.`import_id` AS `import_action_id`, `c`.`action` AS `action_name`,"
					 ." `a`.`parameter_1`, `a`.`parameter1`, `a`.`parameter_2`, `a`.`parameter2`, `b`.`error_code_id`"
				." FROM `".RULE_DETAILS."` `a` "
				." LEFT JOIN `".RULE_MASTER."` `b` ON `a`.`rule` = `b`.`id`"
				." LEFT JOIN `".RULE_ACTION_MASTER."` `c` ON `a`.`action` = `c`.`id`"
				." WHERE `a`.`is_delete` = 0"
					.$con
				." ORDER BY `a`.`rule`";

			$res = $this->re_db_query($q);

            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_all($res);
            }
			return $return;
		}
	}
?>