<?php

class client_ress extends db{
    
    public $errors = '';
    public $table = TRANSACTION_MASTER;
    
    public function insert_update($data){
            
			$from_broker = isset($data['from_broker'])?$this->re_db_input($data['from_broker']):'0';
            $to_broker = isset($data['to_broker'])?$this->re_db_input($data['to_broker']):'0';
            $output = isset($data['output'])?$this->re_db_input($data['output']):'0';
            $date = date('Y-m-d');        	
			if($from_broker=='0'){
				$this->errors = 'Please select from client name.';
			}
            else if($to_broker=='0' ){
                $this->errors = 'Please select to client name.';
            }
			if($this->errors!=''){
				return $this->errors;
			}
			else{
			    $q="UPDATE `".CLIENT_MASTER."` SET `broker_old_name`='0' WHERE `broker_name`='".$to_broker."'";
                $res = $this->re_db_query($q);
             
				$q="UPDATE `".CLIENT_MASTER."` SET `broker_name`='".$to_broker."',`broker_old_name`='".$from_broker."',`ressign_date`='".$date."' WHERE `broker_name`='".$from_broker."'";
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
    public function select_data_report($from_broker = ''){
		$return = array();
        $con = '';
        
        if($from_broker != '')
        {
            $con .= "and `broker_old_name` != '0'";
        }
		
		$q = "SELECT * FROM ".CLIENT_MASTER."
                WHERE `is_delete`='0' ".$con."
                    ORDER BY `id` ASC";
             
		$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
			while($row = $this->re_db_fetch_array($res)){
			     array_push($return,$row);
			}
        }//echo '<pre>';print_r($return);exit;
		return $return;
	}
}
?>