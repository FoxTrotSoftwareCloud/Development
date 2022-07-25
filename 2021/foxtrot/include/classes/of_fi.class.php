<?php
class ofac_fincen extends db{

    public $errors = '';
    public $table = CLIENT_MASTER;
    public $local_folder = DIR_FS.'import_files/OFAC_FinCEN/';

	//
    public function insert_update($data,$scan_data='',$matches=0){
		$instance_broker = new broker_master();
		$total_scan = isset($scan_data)?$this->re_db_input($scan_data):0;
		$total_match = isset($matches) ? $matches : count($data);
		$dataKeys = array_keys($data);
		$file_date = isset($data[$dataKeys[0]]['sdn_data']['file_date']) ? $data[$dataKeys[0]]['sdn_data']['file_date'] : date('Y-m-d H:i:s');
		$import_date = isset($data[$dataKeys[0]]['sdn_data']['import_date']) ? $data[$dataKeys[0]]['sdn_data']['import_date'] : date('Y-m-d H:i:s');

		$q = "INSERT INTO `".OFAC_CHECK_DATA_MASTER."`"
			." SET `total_scan`='".$total_scan."'"
					.",`total_match`='".$total_match."'"
					.",`file_date`='$file_date'"
					.",`import_date`='$import_date'"
					.$this->insert_common_sql()
		;
		$res = $this->re_db_query($q);
		$last_inserted_id = $this->re_db_insert_id();

		foreach($data as $key=>$val)
		{
			foreach($val['client_matches'] as $clientRow){
				$broker_name = $instance_broker->get_broker_name((int)$clientRow['broker_name'], 1);
				
				$q = "INSERT INTO `".OFAC_CHECK_DATA."`"
					." SET "
						." `ofac_check_data_id`='".$last_inserted_id."'"
						.",`ofac_sdn_data_id`='".$val['sdn_data']['id']."'"
						.",`ent_num`='".$val['sdn_data']['ent_num']."'"
						.",`sdn_name`='".$val['sdn_data']['sdn_name']."'"
						.",`program`='".$val['sdn_data']['program']."'"
						.",`client_id`='".$clientRow['id']."'"
						.",`client_name`='".$clientRow['last_name'].(($clientRow['last_name']=='' OR $clientRow['first_name']=='') ? "" : ", ").$clientRow['first_name']."'"
						.",`broker_id`=".(int)$clientRow['broker_name']
						.",`broker_name`='$broker_name'"
						.$this->insert_common_sql()
				;
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

		if($id != '') { $con.= "and `ocm`.`id` =".$id.""; }

		$q = "SELECT `ocm`.*"
				." FROM `".OFAC_CHECK_DATA_MASTER."` AS `ocm`"
				." WHERE `ocm`.`is_delete`='0' "
					.$con
				." ORDER BY `ocm`.`id` DESC LIMIT 1";
				
		$res = $this->re_db_query($q);
		if($this->re_db_num_rows($res)>0){
			$a = 0;
			while($row = $this->re_db_fetch_array($res)){
				$return = $row;
			}
		}
		return $return;
	}
	public function select_data_report($id='', $orderBy=0){
		$id = (int)$this->re_db_input($id);
		$orderBy = (int)$this->re_db_input($orderBy);
		$return = array();
		
		if ($orderBy == 0){
			$orderByQuery = "`ocd`.`id` DESC";
		} else {
			$orderByQuery = "`ocd`.`sdn_name`, `ocd`.`ent_num`";
		}
		
		$q = "SELECT `ocd`.*"
				." FROM `".OFAC_CHECK_DATA."` AS `ocd`"
				." WHERE `ocd`.`is_delete`=0"
					." AND `ocd`.`ofac_check_data_id`=$id"
				." ORDER BY $orderByQuery"
		;

		$res = $this->re_db_query($q);
		
		if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_all($res);
		}
		return $return;
	}
	public function delete($id, $status=0){
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
	public function delete_fincen($id, $status=0){
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
	// 07/21/22 Added for the OFAC scan routine - load sdn.csv into OFAC SDN DATA table
	public function select_ofac_sdn_data($id=0, $ent_num=0){
		$id = (int)$this->re_db_input($id);
		$id = (int)$this->re_db_input($ent_num);
		$return = [];
		$con = "";
		
		if ($id > 0) { $con .= " AND `at`.`id` = $id"; }
		if ($ent_num > 0) { $con .= " AND `at`.`ent_num` = $ent_num"; }
		
		$q = "SELECT `at`.*"
				." FROM `".OFAC_SDN_DATA."` `at`"
				." WHERE `at`.`id` > 0"
					.$con
				." ORDER BY `at`.`id`"
		;
		
		$res = $this->re_db_query($q);
		
		if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_all($res);
		}
		
		return $return;
	}
	//-------------------------------------------------------------------------------- 
	// OFAC Scan
	// 07/03/22 Cycle through SDN Table records (OFAC SDN DATA) and find matches in CLIENT MASTER
	//-------------------------------------------------------------------------------- 
	public function OFAC_scan(){
		//-- 07/03/22 "name" key is populated, not "tmp_name"
		// $filename = $_FILES["file"]["tmp_name"];
		$filePathAndName = $this->local_folder."sdn.csv";
		$sdnData = array();
		$get_array_data = array();

		if (file_exists($filePathAndName)){
			$total_matches = 0;
			$this->load_ofac_sdn_file($filePathAndName);
			
			//-- Cycle through SDN Data table records
			$sdnData = $this->select_ofac_sdn_data();
			
			foreach($sdnData as $key=>$val)
			{
				// Skip "vessels" - don't think they can open too many accounts
				if (!in_array(strtolower($val['sdn_type']), ['vessel', 'aircraft'])) {
					$checkName = $this->check_name($val['sdn_name'], $val['remarks'], $val['sdn_type']);
					
					if(is_array($checkName) && count($checkName)>0){
						$total_matches += count($checkName);
						$get_array_data[$val['id']] = ['sdn_data'=>$val, 'client_matches'=>$checkName];
					}
					
					$q = "UPDATE `".OFAC_SDN_DATA."` SET `last_scan`=NOW() WHERE `id`={$val['id']}";
					$res = $this->re_db_query($q);
				}
			}
			$total_scan = isset($sdnData)?$this->re_db_input(count($sdnData)):0;
			// Update Master table
			$q = "UPDATE `".$this->table."`"
				." SET `ofac_check`='".date('m/d/Y h:i:s A')."'"
					.$this->update_common_sql()
				." WHERE `is_delete` = 0"
			;
			$res = $this->re_db_query($q);

			if($get_array_data != array())
			{
				$return = $this->insert_update($get_array_data, $total_scan, $total_matches);

				if($return===true){
					return $return;
				}
				else{
					$error = !isset($_SESSION['warning'])?$return:'';
				}
			}
			else
			{
				$_SESSION['warning'] = "No OFAC matches found.";
				return false;
			}
		}
	}
	
	function load_ofac_sdn_file($filePathAndName=''){
		$fileName = $filePathAndName!='' ? $this->re_db_input($filePathAndName) : $this->local_folder."sdn.csv";
		$fileStream = fopen($fileName, 'r');
		$sdnFields = ['ent_num', 'sdn_name', 'sdn_type', 'program', 'title', 'x-call_sign', 'x-vess_type', 'x-tonnage', 'x-grt', 'x-vess_flag', 'x-vess_owner', 'remarks'];
		$setFields = "";
		$recsLoaded = 0;

		// Clear out the data
		$q = "DELETE FROM `".OFAC_SDN_DATA."` WHERE `id` > 0";
		$res = $this->re_db_query($q);
		$q = "ALTER TABLE `".OFAC_SDN_DATA."` AUTO_INCREMENT = 1";
		$res = $this->re_db_query($q);

		// Populate the Data Table
		if ($fileStream){
			while (($getData = fgetcsv($fileStream, 10000, ",")) !== FALSE) {
				// "ent_num" has to be populated for valid OFAC/SDN record
				if ((int)$getData[0] > 0){
					$setFields = "";
					
					foreach ($sdnFields AS $key=>$value){
						if (substr($value,0,2)!="x-"){
							$sdnValue = $this->re_db_input($getData[$key]);
							$setFields .= (empty($setFields)?"":", ")."`$value` = '$sdnValue'";
						}
					}

					$q = "INSERT INTO `".OFAC_SDN_DATA."`"
							." SET "
								.$setFields 
								.",file_date = '".date("Y-m-d H:i:s", filectime($fileName))."'"
								.",import_date = '".date("Y-m-d H:i:s")."'"
								.$this->insert_common_sql()
					;
					$res = $this->re_db_query($q);
					
					if ($res) { $recsLoaded++; }
				}
			}
		}

		$fileClosed = fclose($fileStream);
		return $recsLoaded;
	}
	// 07/22/22 Pull client records by the SDN name
	function check_name($sdn_name = '', $sdn_desc = '', $sdn_type = ''){
        $sdn_name = isset($sdn_name)?$this->re_db_input($sdn_name):'';
        $sdn_type = isset($sdn_type)?$this->re_db_input($sdn_type):'';
		$return = array();
        $con = $res = $first_name = $last_name = $middle_name = $explode1 = $explode2 = $firstNameSearch = '';

		if($sdn_name != '')
        {
			if ($sdn_type=='individual'){
				// SDN Name format for individuals = LASTNAME(all caps), FirstName MiddleName
				$explode1 = explode(",", $sdn_name);
				$last_name = strtolower(trim($explode1[0]));
				$explode2 = explode(" ", trim($explode1[1]));
				// $first_name = strtolower(trim($explode2[0]));
				// $middle_name = isset($explode2[1]) ? substr($explode1[1],strlen($first_name)+1) : "";
				foreach($explode2 AS $first_name){
					$first_name = strtolower($first_name);
					$firstNameSearch .= (empty($firstNameSearch)?"":" OR ")."(LOWER(`at`.`first_name`) LIKE '$first_name%')";	
				}
				
				$con .= " AND (LOWER(`at`.`last_name`) LIKE '$last_name%' AND ($firstNameSearch) )";
			} else {
				$last_name = strtolower(trim($sdn_name));
				$con .= " AND ( (CONCAT(LOWER(`at`.`first_name`), ' ', LOWER(`at`.`last_name`)) LIKE '%$last_name%') OR (CONCAT(LOWER(`at`.`long_name`), ' ', LOWER(`at`.`long_name`)) LIKE '%$last_name%'))";
			}
			
			$q = "SELECT `at`.*"
					." FROM `".$this->table."` AS `at`"
					." WHERE `at`.`is_delete`='0'"
						.$con
			;

			$res = $this->re_db_query($q); 

			if($this->re_db_num_rows($res)>0){
				$return = $this->re_db_fetch_all($res);
			}

        }
		return $return;
	}
	
	//-------------------------------------------------------------------------------- 
	// FinCEN Scan
	// 07/23/22 Cycle through SDN Table records (OFAC SDN DATA) and find matches in CLIENT MASTER
	//-------------------------------------------------------------------------------- 
	public function fincen_scan(){
		$fincenData = $checkName = $get_array_data = [];
		$total_matches = $total_scan = $q = $res = $last_inserted_id = $return = $loadData = 0;
		$file_date = $import_date = "";
		
		$filePathAndName = $_FILES["file_fincen"]["tmp_name"];

		if (!file_exists($filePathAndName)){
			$fincenData = $this->select_fincen_data();
			$loadData = (count($fincenData) ? 0 : 1);
		}
		
		if (file_exists($filePathAndName) OR !$loadData){
			// Load the FinCEN Data table
			if ($loadData){
				$total_scan = $this->load_fincen_data($filePathAndName);
			}
			
			//-- Cycle through SDN Data table records
			$fincenData = $this->select_fincen_data();
			$total_scan = count($fincenData);
			$file_date = $total_scan ? $fincenData[0]['file_date'] : "";
			$import_date = $total_scan ? $fincenData[0]['import_date'] : "";
			
			// Master table update
            $q = "INSERT INTO `".FINCEN_CHECK_DATA_MASTER."`"
					." SET `total_scan`=$total_scan"
						.",`total_match`=$total_matches"
						.",`import_date`='$import_date'"
						.",`file_date`='$file_date'"
						.$this->insert_common_sql()
			;
			$res = $this->re_db_query($q);
			$last_inserted_id = $this->re_db_insert_id();
			
			foreach($fincenData as $key=>$val)
			{
                $checkName = $this->check_name_fincen($val['last_name'], $val['first_name'], $val['alias_last_name'], $val['alias_first_name']);
					
                if(is_array($checkName) && count($checkName)>0){
                    $total_matches += count($checkName);
                    $get_array_data[] = ['scan_data'=>$val, 'client_matches'=>$checkName];
                }
                
                $q = "UPDATE `".FINCEN_DATA."`"
						." SET `master_id`=$last_inserted_id, `last_scan`=NOW()"
						." WHERE `id`={$val['id']}"
				;
                $res = $this->re_db_query($q);
			}

			// Master table update
            $q = "UPDATE `".FINCEN_CHECK_DATA_MASTER."`"
				." SET `total_match`=$total_matches"
					.$this->update_common_sql()
				." WHERE `id` = $last_inserted_id"
			;
			$res = $this->re_db_query($q);

            $q = "UPDATE `".$this->table."`"
				." SET `fincen_check`='".date('m/d/Y h:i:s A')."'"
					.$this->update_common_sql()
				." WHERE `is_delete` = 0"
			;
			$res = $this->re_db_query($q);

			// Detail table update
            if(count($get_array_data)){
                $return = $this->insert_update_fincen($get_array_data, $last_inserted_id);
            } else {
                $return = "No FinCEN matches found.";
            }
		} else {
			$return = "No FinCEN file found.";
		}
		
		return $return;
	}
    
    function load_fincen_data($filePathAndName=''){
		$fileName = $filePathAndName!='' ? $this->re_db_input($filePathAndName) : $this->local_folder."/fincen/FinCen Persons List.csv";
		$fincenFields = ['tracking_number', 'last_name', 'first_name', 'middle_name', 'suffix', 
                         'alias_last_name', 'alias_first_name', 'alias_middle_name', 'alias_suffix', 
                         'number', 'number_type', 'dob', 'street', 'city', 'state', 'zip', 'country', 'phone'];
		$setFields = "";
		$recsLoaded = 0;

		// Clear out the data
		$q = "DELETE FROM `".FINCEN_DATA."` WHERE `id` > 0";
		$res = $this->re_db_query($q);
		$q = "ALTER TABLE `".FINCEN_DATA."` AUTO_INCREMENT = 1";
		$res = $this->re_db_query($q);

		// Populate the Data Table
        $fileStream = fopen($fileName, 'r');
		if ($fileStream){
			while (($getData = fgetcsv($fileStream, 10000, ",")) !== FALSE) {
				// "ent_num" has to be populated for valid OFAC/SDN record
				if ((int)$getData[0] > 0){
					$setFields = "";
					
					foreach ($fincenFields AS $key=>$value){
                        $fincenValue = $this->re_db_input($getData[$key]);
                        $setFields .= (empty($setFields)?"":", ")."`$value` = '$fincenValue'";
					}

					$q = "INSERT INTO `".FINCEN_DATA."`"
							." SET "
								.$setFields 
								.",file_date = '".date("Y-m-d H:i:s", filectime($fileName))."'"
								.",import_date = '".date("Y-m-d H:i:s")."'"
								.$this->insert_common_sql()
					;
					$res = $this->re_db_query($q);
					
					if ($res) { $recsLoaded++; }
				}
			}
		}

		$fileClosed = fclose($fileStream);
		return $recsLoaded;
	}
    // 07/21/22 Added for the Fincen scan routine 
	public function select_fincen_data($id=0, $number=''){
		$id = (int)$this->re_db_input($id);
		$number = $this->re_db_input($number);
		$return = [];
		$con = "";
		
		if ($id > 0) { $con .= " AND `at`.`id` = $id"; }
		if (!empty($number)) { $con .= " AND `at`.`number` = '$number'"; }
		
		$q = "SELECT `at`.*"
				." FROM `".FINCEN_DATA."` `at`"
				." WHERE `at`.`id` > 0"
					.$con
				." ORDER BY `at`.`id`"
		;
		
		$res = $this->re_db_query($q);
		
		if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_all($res);
		}
		
		return $return;
	}
	public function insert_update_fincen($masterData=[], $last_inserted_id=0){
		$res = 0;
		
		foreach($masterData as $masterRow){
			$fincen_tracking_no = isset($masterRow['scan_data']['tracking_number'])?$this->re_db_input($masterRow['scan_data']['tracking_number']):0;
			$fincen_firstname = isset($masterRow['scan_data']['first_name'])?$this->re_db_input($masterRow['scan_data']['first_name']):'';
			$fincen_miname = isset($masterRow['scan_data']['middle_name'])?$this->re_db_input($masterRow['scan_data']['middle_name']):'';
			$fincen_lastname = isset($masterRow['scan_data']['last_name'])?$this->re_db_input($masterRow['scan_data']['last_name']):'';
			$fincen_address = isset($masterRow['scan_data']['street'])?$this->re_db_input($masterRow['scan_data']['street']):'';
			$fincen_country = isset($masterRow['scan_data']['country'])?$this->re_db_input($masterRow['scan_data']['country']):'';
			$fincen_phone = isset($masterRow['scan_data']['phone'])?$this->re_db_input($masterRow['scan_data']['phone']):'';
			$fincen_number = isset($masterRow['scan_data']['number'])?$this->re_db_input($masterRow['scan_data']['number']):'';
			$fincen_number_type = isset($masterRow['scan_data']['number_type'])?$this->re_db_input($masterRow['scan_data']['number_type']):'';
			$fincen_dob = isset($masterRow['scan_data']['dob']) && $masterRow['scan_data']['dob'] != ''?$this->re_db_input(date('Y-m-d',strtotime($masterRow['scan_data']['dob']))):'0000-00-00';
			
			foreach($masterRow['client_matches'] AS $clientRow){
				$client_id = isset($clientRow['id']) ? $this->re_db_input($clientRow['id']) : 0;

				if($fincen_tracking_no>0){
					$q = "INSERT INTO `".FINCEN_CHECK_DATA."`"
							." SET `fincen_scan_id`=$last_inserted_id"
								.",`fincen_tracking_no`=$fincen_tracking_no"
								.",`fincen_firstname`='".$fincen_firstname."'"
								.",`fincen_miname`='".$fincen_miname."'"
								.",`fincen_lastname`='".$fincen_lastname."'"
								.",`fincen_address`='".$fincen_address."'"
								.",`fincen_country`='".$fincen_country."'"
								.",`fincen_phone`='".$fincen_phone."'"
								.",`fincen_number`='".$fincen_number."'"
								.",`fincen_number_type`='".$fincen_number_type."'"
								.",`fincen_dob`='".$fincen_dob."'"
								.",`client_id`='".$client_id."'"
								.$this->insert_common_sql()
					;
					$res = $this->re_db_query($q);
				}
			}
		}

		if($res){
			return true;
		}
		else{
			return false;
		}

	}
	function check_name_fincen($fincen_lname = '',$fincen_fname = '', $alias_lname='', $alias_fname=''){
		$return = array();
		// 07/24/22 Check the "alias_?" fields as well
		$con = $conAlias = '';
		
		$con = " AND (`at`.`last_name` LIKE '%$fincen_lname%' AND `at`.`first_name` LIKE '%$fincen_fname%')";
		// 07/24/22 Add "alias" search
		if ($alias_lname!='' AND $alias_fname!=''){
			$aliasQuery = "(`at`.`last_name` LIKE '%$alias_lname%' AND `at`.`first_name` LIKE '%$alias_fname%')";
			$con = empty($con) ? " AND ".$aliasQuery : " AND (".substr($con,5)." OR ".$aliasQuery.")";
		}

		$q = "SELECT `at`.*"
				." FROM `".$this->table."` AS `at`"
				." WHERE `at`.`is_delete`=0"
					.$con
		;
		$res = $this->re_db_query($q);

		if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_all($res);
		}

		return $return;
	}

}
?>