<?php

class broker_document extends db{
		
		public $errors = '';
        public $table = BROKER_DOCUMENT_MASTER;
        
    
    public function insert_update($data){
            
        $id = isset($data['id'])?$this->re_db_input($data['id']):0;
        $desc= isset($data['desc'])?$this->re_db_input($data['desc']):'';
        $required= isset($data['required'])?$this->re_db_input($data['required']):'';
                			
        if($desc==''){
        	$this->errors = 'Please enter broker documents description.';
        }
        if($this->errors!=''){
        	return $this->errors;
        }
        else{
        	if($id>=0){
        		if($id==0){
        			$q = "INSERT INTO ".$this->table." SET `desc`='".$desc."',`required`='".$required."'".$this->insert_common_sql();
        			
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
        		else if($id>0){
        			$q = "UPDATE ".$this->table." SET `desc`='".$desc."',`required`='".$required."'".$this->update_common_sql()." WHERE `id`='".$id."'";
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
    public function select(){
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
    public function edit($id){
    	$return = array();
    	$q = "SELECT `at`.*
    			FROM ".$this->table." AS `at`
                WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."'";
    	$res = $this->re_db_query($q);
        if($this->re_db_num_rows($res)>0){
    		$return = $this->re_db_fetch_array($res);
        }
    	return $return;
    }
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
}

?>