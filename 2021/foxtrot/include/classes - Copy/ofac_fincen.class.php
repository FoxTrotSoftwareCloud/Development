<?php 
class ofac_fincen extends db{
    
    public $errors = '';
    public $table = CLIENT_MASTER;
    
    public function get_ofac_data($sdn_name = '',$sdn_desc = ''){//echo '<pre>';print_r($sdn_name);exit;
		$return = array();
        $name_array = array();
        $con = '';
        $name = isset($sdn_name)?$this->re_db_input($sdn_name):'';
        
        $client_data = $this->get_client_data();
        if($name != '')
        {
            $name_array = explode(' ',$name,2);

            $i=1;
            foreach($name_array as $key_name=>$val_name)
            {
                if(strlen($val_name)>2)
                {
                    if($i == 1)
                    {
                        $con .= " and `at`.`first_name` LIKE '%".$val_name."%' OR `at`.`last_name` LIKE '%".$val_name."%' ";
                    }
                    else
                    {
                        $con .= " OR `at`.`first_name` LIKE '%".$val_name."%' OR `at`.`last_name` LIKE '%".$val_name."%' ";
                    }
                    $i++;
                }
            }
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
                $q = "INSERT INTO `".OFAC_CHECK_DATA."` SET `ofac_check_data_id`='".$last_inserted_id."',`id_no`='".$val[0]['id_no']."',`sdn_name`='".$val[0]['sdn_name']."',`program`='".$val[0]['program']."',`client_id`='".$val['id']."',`client_name`='".$val['first_name']." ".$val['last_name']."',`rep_no`='',`rep_name`=''".$this->insert_common_sql();
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
        public function get_pdf_data(){
            $return = array();
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
        public function get_fincen_data($fincen_lname = '',$fincen_fname = ''){//echo '<pre>';print_r($sdn_name);exit;
    		$return = array();
            
            $con = '';
            
            if($fincen_lname != '')
            {
                $con .= " and `at`.`last_name` LIKE '%".$fincen_lname."%'";
            }
            if($fincen_fname != '')
            {
                $con .= " and `at`.`first_name` LIKE '%".$fincen_fname."%' ";
            }
            
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
            
    		return $return;
    	}
        public function insert_update_fincen($data,$scan_data=''){
            
    
            $total_scan = isset($scan_data)?$this->re_db_input($scan_data):0;
            $total_match = isset($data)?$this->re_db_input(count($data)):0;
            
            $q = "INSERT INTO `".FINCEN_CHECK_DATA_MASTER."` SET `total_scan`='".$total_scan."',`total_match`='".$total_match."'".$this->insert_common_sql();
 			$res = $this->re_db_query($q);
            $last_inserted_id = $this->re_db_insert_id();
            
			foreach($data as $key=>$val)
            {
                $fincen_tracking_no = isset($val[0]['fincen_tracking_no'])?$this->re_db_input($val[0]['fincen_tracking_no']):0;
                $fincen_firstname = isset($val[0]['fincen_firstname'])?$this->re_db_input($val[0]['fincen_firstname']):'';
                $fincen_miname = isset($val[0]['fincen_miname'])?$this->re_db_input($val[0]['fincen_miname']):'';
                $fincen_lastname = isset($val[0]['fincen_lastname'])?$this->re_db_input($val[0]['fincen_lastname']):'';
                $fincen_address = isset($val[0]['fincen_address'])?$this->re_db_input($val[0]['fincen_address']):'';
                $fincen_country = isset($val[0]['fincen_country'])?$this->re_db_input($val[0]['fincen_country']):'';
                $fincen_phone = isset($val[0]['fincen_phone'])?$this->re_db_input($val[0]['fincen_phone']):'';
                $fincen_keyno = isset($val[0]['fincen_keyno'])?$this->re_db_input($val[0]['fincen_keyno']):'';
                $fincen_no_type = isset($val[0]['fincen_no_type'])?$this->re_db_input($val[0]['fincen_no_type']):'';
                $fincen_dob = isset($val[0]['fincen_dob']) && $val[0]['fincen_dob'] != ''?$this->re_db_input(date('Y-m-d',strtotime($val[0]['fincen_dob']))):'0000-00-00';
                $client_id = isset($val['id'])?$this->re_db_input($val['id']):0;
                
                
                if($fincen_tracking_no>0)
                {
                $q = "INSERT INTO `".FINCEN_CHECK_DATA."` SET `fincen_scan_id`='".$last_inserted_id."',`fincen_tracking_no`='".$fincen_tracking_no."',`fincen_firstname`='".$fincen_firstname."',`fincen_miname`='".$fincen_miname."',`fincen_lastname`='".$fincen_lastname."',`fincen_address`='".$fincen_address."',`fincen_country`='".$fincen_country."',`fincen_phone`='".$fincen_phone."',`fincen_number`='".$fincen_keyno."',`fincen_number_type`='".$fincen_no_type."',`fincen_dob`='".$fincen_dob."',`client_id`='".$client_id."'".$this->insert_common_sql();
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
        public function delete_fincen($id){
			$id = trim($this->re_db_input($id));
			if($id>0 && ($status==0 || $status==1) ){
				$q = "DELETE FROM `".FINCEN_CHECK_DATA_MASTER."` WHERE `id`='".$id."'";
				$res = $this->re_db_query($q);
                
                $q = "DELETE FROM `".FINCEN_CHECK_DATA."` WHERE `fincen_scan_id`='".$id."'";
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
        public function select_fincen_data_master_report($id=''){
			$return = array();
            $con = '';
            
            if($id != '')
            {
                $con.= "and `fcm`.`id` =".$id."";
            }
            
			$q = "SELECT `fcm`.*
					FROM `".FINCEN_CHECK_DATA_MASTER."` AS `fcm`
                    WHERE `fcm`.`is_delete`='0' ".$con."
                    ORDER BY `fcm`.`id` DESC LIMIT 1";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;
    			}
            }
			return $return;
		}
        public function select_fincen_scan_report($id=''){
			$return = array();
			
			$q = "SELECT `at`.*,`cm`.*
					FROM `".FINCEN_CHECK_DATA ."` AS `at` INNER JOIN `".CLIENT_MASTER."` AS `cm` ON `at`.`client_id` = `cm`.`id`
                    WHERE `at`.`is_delete`='0' and `at`.`fincen_scan_id`=".$id."
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
        public function select_fincen_scan_file(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".FINCEN_CHECK_DATA_MASTER."` AS `at`
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