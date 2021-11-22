<?php 
class ofac_fincen extends db{
    
    public $errors = '';
    public $table = CLIENT_MASTER;
    
    public function get_ofac_data($sdn_name = '',$sdn_desc = ''){//echo '<pre>';print_r($sdn_name);exit;
		$return = array();
        $con = '';
        $name = isset($sdn_name)?$this->re_db_input($sdn_name):'';
        
        $client_data = $this->get_client_data();
        if($name != '')
        {
            //$con .= "and `at`.`first_name` LIKE '%".$name."%' ";
            $con .= "and `at`.`first_name` LIKE '%".$name."%' OR `at`.`last_name` LIKE '%".$name."%' ";
            /*if($sdn_desc != '')
            {
                $con .= "and (`at`.`first_name` LIKE '%".$name."%' OR `at`.`last_name` LIKE '%".$name."%' OR`at`.`mi` LIKE '%".$name."%')";
            }*/
        
		
    		$q = "SELECT `at`.*
    				FROM `".$this->table."` AS `at`
                    WHERE `at`.`is_delete`='0' ".$con."
                    ";
    		$res = $this->re_db_query($q);
            
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;
    			}
            }
        }
		return $return;
	}
    public function insert_update($data,$scan_data=''){
    
            $total_scan = isset($scan_data)?$this->re_db_input($scan_data):0;
            $total_match = isset($data)?$this->re_db_input(count($data)):0;
            /*$q = "UPDATE `".OFAC_CHECK_DATA."` SET `is_delete`='1'";
	        $res = $this->re_db_query($q);*/
            
            $q = "INSERT INTO `".OFAC_CHECK_DATA_MASTER."` SET `total_scan`='".$total_scan."',`total_match`='".$total_match."'".$this->insert_common_sql();
 			$res = $this->re_db_query($q);
            $last_inserted_id = $this->re_db_insert_id();
            
			foreach($data as $key=>$val)
            {
                $q = "INSERT INTO `".OFAC_CHECK_DATA."` SET `ofac_check_data_id`='".$last_inserted_id."',`id_no`='".$val[0]['id_no']."',`sdn_name`='".$val[0]['sdn_name']."',`program`='".$val[0]['program']."',`client_id`='".$val['id']."',`client_name`='".$val['first_name']."',`rep_no`='',`rep_name`=''".$this->insert_common_sql();
    			$res = $this->re_db_query($q);
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
        public function get_client_data(){
			$return = array();
            $con = '';
            
			$q = "SELECT `at`.`first_name`,`at`.`last_name`
    				FROM `".$this->table."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ";
    		$res = $this->re_db_query($q);
            
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        
        public function select_data_master_report($id=''){
			$return = array();
            $con = '';
            
            if($id != '')
            {
                $con.= "and `ocm`.`id` =".$id."";
            }
            
			$q = "SELECT `ocm`.*
					FROM `".OFAC_CHECK_DATA_MASTER."` AS `ocm`
                    WHERE `ocm`.`is_delete`='0' ".$con."
                    ORDER BY `ocm`.`id` DESC LIMIT 1";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;
    			}
            }
			return $return;
		}
        public function select_data_report($id=''){
			$return = array();
            
			$q = "SELECT `ocd`.*
					FROM `".OFAC_CHECK_DATA."` AS `ocd`
                    WHERE `ocd`.`is_delete`='0' and `ocd`.`ofac_check_data_id`=".$id."
                    ORDER BY `ocd`.`id` DESC ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        public function delete($id){
			$id = trim($this->re_db_input($id));
			if($id>0 && ($status==0 || $status==1) ){
				$q = "DELETE FROM `".OFAC_CHECK_DATA_MASTER."` WHERE `id`='".$id."'";
				$res = $this->re_db_query($q);
                
                $q = "DELETE FROM `".OFAC_CHECK_DATA."` WHERE `ofac_check_data_id`='".$id."'";
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
        public function select_scan_file(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".OFAC_CHECK_DATA_MASTER."` AS `at`
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
    
            
}
?>