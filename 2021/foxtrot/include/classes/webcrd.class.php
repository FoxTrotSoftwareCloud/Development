<?php
class webcrd extends db{

    public $errors = '';
    public $table = WEBCRD_MASTER;
    public $local_folder = DIR_FS.'import_files/webcrd/';

	function insert_update_master($tableData=[]){
		if (empty($tableData))
			return 0;

		$return = 0;
		$setFields = "";

		foreach ($tableData AS $dataKey=>$dataValue){
			$setFields .= (empty($setFields)?"":", ")."`$dataKey` = '$dataValue'";
		}

		$q = "INSERT INTO `".WEBCRD_MASTER."`"
			." SET $setFields"
				.$this->insert_common_sql()
		;
		$res = $this->re_db_query($q);

		if ($res) { $return = $this->re_db_insert_id(); }

		return $return;
	}

	public function select_master($id=''){
		$id = (int)$this->re_db_input($id);
		$return = [];
		$con = '';

		if($id > 0) { $con.= " AND `ocm`.`id` =$id"; }

		$q = "SELECT `ocm`.*"
				." FROM `".WEBCRD_MASTER."` AS `ocm`"
				." WHERE `ocm`.`is_delete`=0 "
					.$con
				." ORDER BY `ocm`.`id` DESC LIMIT 1"
		;

		$res = $this->re_db_query($q);
		if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_array($res);
		}
		return $return;
	}
	public function select_ce_download_report($masterId='', $orderBy=0){
		$masterId = (int)$this->re_db_input($masterId);
		$orderBy = (int)$this->re_db_input($orderBy);
		$return = array();

		if ($orderBy == 0){
			$orderByQuery = "`wce`.`master_id` DESC, `wce`.`individual_crd_no`";
		} else {
			$orderByQuery = "`wce`.`last_name`,`wce`.`first_name`,`wce`.`middle_name`,`wce`.`individual_crd_no`";
		}

		$q = "SELECT `wce`.*"
				." FROM `".WEBCRD_CE_DOWNLOAD_DATA."` AS `wce`"
				." WHERE `wce`.`is_delete`=0"
					." AND `wce`.`master_id`=$masterId"
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
		$return = [];

		$q = "SELECT `at`.*"
			." FROM `".WEBCRD_MASTER."` AS `at`"
			." WHERE `at`.`is_delete`=0"
			." ORDER BY `at`.`id` ASC"
		;
		
		$res = $this->re_db_query($q);
		
		if($this->re_db_num_rows($res)>0){
			$return = $row = $this->re_db_fetch_all($res);
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
	public function select_ce_download_data($id=0, $individual_crd_no=''){
		$id = (int)$this->re_db_input($id);
		$individual_crd_no = $this->re_db_input($individual_crd_no);
		$return = [];
		$con = "";

		if ($id > 0) { $con .= " AND `at`.`id` = $id"; }
		if ($individual_crd_no != '') { $con .= " AND `at`.`individual_crd_no` = '$individual_crd_no'"; }

		$q = "SELECT `at`.*"
				." FROM `".WEBCRD_CE_DOWNLOAD_DATA."` `at`"
				." WHERE `at`.`is_delete` = 0"
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
	// CE Download - Basic Rep Info + CE Status
	// 07/03/22 Cycle through SDN Table records (OFAC SDN DATA) and find matches in CLIENT MASTER
	//--------------------------------------------------------------------------------
	public function ce_download_scan(){
		$filePathAndName = $_FILES["ce_download_file"]["tmp_name"];
		$fileData = $get_array_data = [];
		$return = $broker_id = $added = 0;
		$file_date = $import_date = "";
		
		if (file_exists($filePathAndName)){
			$total_scan = $this->load_ce_download_file($filePathAndName);

			// Cycle through SDN Data table records
			$fileData = $this->select_ce_download_data();

			foreach($fileData as $key=>$row) {
				$broker_id = $this->check_ce_download($row);
				
				if ($broker_id) { $added++; }
			}
			$file_name = $_FILES["ce_download_file"]["name"];
			$file_type = "CE Download";
			$file_date = (empty($fileData[0]['file_date']) ? date("Y-m-d H:i:s") : $fileData[0]['file_date']); 
			$import_date = (empty($fileData[0]['import_date']) ? date("Y-m-d H:i:s") : $fileData[0]['import_date']); 
			
			$return = $this->insert_update_master(['total_scan'=>$total_scan, 'added'=>$added, 'file_name'=>$file_name, 'file_type'=>"CE Download", 'file_date'=>$file_date, 'import_date'=>$import_date]);
			
			if ($return){
				$q = "UPDATE `".WEBCRD_CE_DOWNLOAD_DATA."`"
					." SET `master_id`=$return"
					." WHERE `is_delete`=0 AND `master_id`=0"
				;
				$res = $this->re_db_query($q);
			}
		}
		
		return $return;
	}

	function load_ce_download_file($filePathAndName=''){
		$fileName = $filePathAndName!='' ? $this->re_db_input($filePathAndName) : $this->local_folder."6.30.22_CE_download.csv";
		$fileStream = fopen($fileName, 'r');
		$fileFields = ['organization_crd_no','session_type_code','session_type','session_state','individual_crd_no','first_name','middle_name','last_name','suffix',
                       'ce_status_date','window_begin_date','window_end_date','ce_status','appointment_progress','appointment_date','last_accessed_date','military_deferral'];
		$setFields = "";
		$recsLoaded = 0;

		// Populate the Data Table
		if ($fileStream){
            // Clear out the data
            $q = "UPDATE `".WEBCRD_CE_DOWNLOAD_DATA."`"
				." SET `is_delete`=1".$this->update_common_sql()
				." WHERE `is_delete`=0"
			;
            $res = $this->re_db_query($q);

			while (($getData = fgetcsv($fileStream, 10000, ",")) !== FALSE) {
				if ((int)$getData[0] > 0){
					$setFields = "";

					foreach ($fileFields AS $fileFieldKey=>$fileFieldName){
                        $fileValue = $this->re_db_input($getData[$fileFieldKey]);
                        $setFields .= (empty($setFields)?"":", ")."`$fileFieldName` = '$fileValue'";
					}

					$q = "INSERT INTO `".WEBCRD_CE_DOWNLOAD_DATA."`"
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
	// Insert or Update BROKER MASTER per the CE Download file
	function check_ce_download($fileRecord=[]){
		$return = $brokerId = 0;
		$result = "";
		
		if (empty($fileRecord) OR empty($fileRecord['individual_crd_no']))
			return $return;

		$instance_broker = new broker_master();
		$crd = $this->re_db_input($fileRecord['individual_crd_no']);
		$brokerMaster = $instance_broker->select_broker_by_id(0, 'crd', $crd);

		// Load broker into BROKER MASTER
		if (empty($brokerMaster)) {
			$fname = $this->re_db_input($fileRecord['first_name']);
			$lname = $this->re_db_input($fileRecord['last_name']);
			$mname = $this->re_db_input($fileRecord['middle_name']);
			$suffix = $this->re_db_input($fileRecord['suffix']);
			$return = $instance_broker->insert_update(['fname'=>$fname, 'lname'=>$lname, 'mname'=>$mname, 'suffix'=>$suffix, 'crd'=>$crd]);

			if ($return){
				$brokerId = $_SESSION['last_insert_id'];
				$result = "CRD #\Broker Added";
			}
		} else {
			$brokerId = $brokerMaster['id'];
			$result = "CRD # already exists";
		}

		$q = "UPDATE `".WEBCRD_CE_DOWNLOAD_DATA."`"
			." SET"
				." `broker_id`= $brokerId"
				.",`result`='$result'"
			." WHERE `id`={$fileRecord['id']}"
		;
		$res = $this->re_db_query($q);

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
		} else {
			$loadData = 1;
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