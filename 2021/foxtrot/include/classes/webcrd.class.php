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

		$q = "INSERT INTO `".$this->table."`"
			." SET $setFields"
				.$this->insert_common_sql()
		;
		$res = $this->re_db_query($q);

		if ($res) { $return = $this->re_db_insert_id(); }

		return $return;
	}

	public function select_master($id=0, $file_type=''){
		$id = (int)$this->re_db_input($id);
		$file_type = $this->re_db_input($file_type);
		$return = [];
		$con = '';

		if($id > 0) { $con.= " AND `$this->table`.`id` =$id"; }
		if($file_type != "") { $con.= " AND `$this->table`.`file_type` ='$file_type'"; }

		$q = "SELECT *"
				." FROM `$this->table` AS `$this->table`"
				." WHERE `$this->table`.`is_delete`=0 "
					.$con
				." ORDER BY `$this->table`.`id` DESC"
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
			$fileData = $this->select_ce_download_data(0);

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
		if ($filePathAndName == '')
			return 0;
			
		$fileStream = fopen($filePathAndName, 'r');
		$fileFields = ['organization_crd_no','session_type_code','session_type','session_state','individual_crd_no','first_name','middle_name','last_name','suffix',
                       'ce_status_date','window_begin_date','window_end_date','ce_status','appointment_progress','appointment_date','last_accessed_date','military_deferral'];
		$setFields = "";
		$recsLoaded = 0;

		// Populate the Data Table
		if ($fileStream){
            // Clear out the data
            $q = "UPDATE `".WEBCRD_CE_DOWNLOAD_DATA."`"
				." SET `is_delete`=1".$this->update_common_sql()
				." WHERE `master_id`=0"
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
								.",file_date = '".date("Y-m-d H:i:s", filectime($filePathAndName))."'"
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

	public function select_ce_download_data($masterId=null, $orderBy=0){
		$masterId = (is_null($masterId) ? $masterId : (int)$this->re_db_input($masterId));
		$orderBy = (int)$this->re_db_input($orderBy);
		$return = [];
		$con = "";

		// 07/30/22 Need to check for "null", because there might be 0 master id's in the table
		if (!is_null($masterId)) { $con .= " AND `at`.`master_id` = $masterId"; }

		if ($orderBy == 0){
			$orderByQuery = "`at`.`id`"; //"`at`.`master_id` DESC, `at`.`individual_crd_no`";
		} else {
			$orderByQuery = "`at`.`last_name`,`at`.`first_name`,`at`.`middle_name`,`at`.`individual_crd_no`";
		}
		// Query data
		$q = "SELECT `at`.*"
				." FROM `".WEBCRD_CE_DOWNLOAD_DATA."` `at`"
				." WHERE `at`.`is_delete` = 0"
					.$con
				." ORDER BY $orderByQuery"
		;
		$res = $this->re_db_query($q);

		if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_all($res);
		}

		return $return;
	}

	//--------------------------------------------------------------------------------
	// FINRA Exam Status - 07/30/22
	//--------------------------------------------------------------------------------
	public function finra_exam_status_scan(){
		$filePathAndName = $_FILES["file_finra_exam_status"]["tmp_name"];
		$fileData = $get_array_data = [];
		$return = $broker_id = $added = 0;
		$file_date = $import_date = "";
		
		if (file_exists($filePathAndName)){
			$total_scan = $this->load_finra_exam_status_file($filePathAndName);

			// Cycle through SDN Data table records
			$fileData = $this->select_finra_exam_status_data(0);

			foreach($fileData as $key=>$row) {
				$broker_id = $this->check_finra_exam_status($row);
				
				if ($broker_id) { $added++; }
			}
			$file_name = $_FILES["file_finra_exam_status"]["name"];
			$file_type = "FINRA Exam Status";
			$file_date = (empty($fileData[0]['file_date']) ? date("Y-m-d H:i:s") : $fileData[0]['file_date']); 
			$import_date = (empty($fileData[0]['import_date']) ? date("Y-m-d H:i:s") : $fileData[0]['import_date']); 
			
			$return = $this->insert_update_master(['total_scan'=>$total_scan, 'added'=>$added, 'file_name'=>$file_name, 'file_type'=>"FINRA Exam Status", 'file_date'=>$file_date, 'import_date'=>$import_date]);
			
			if ($return){
				$q = "UPDATE `".WEBCRD_EXAM_STATUS_DATA."`"
					." SET `master_id`=$return"
					." WHERE `is_delete`=0 AND `master_id`=0"
				;
				$res = $this->re_db_query($q);
			}
		}
		
		return $return;
	}

	function load_finra_exam_status_file($filePathAndName=''){
		if ($filePathAndName == '')
			return 0;
			
		$fileStream = fopen($filePathAndName, 'r');
		$fileFields = ['first_name', 'last_name', 'individual_crd_no', 'registrations', 'disclosures', 'exams', 'deficiencies', 'branch_locations', 'other_business', 
	                   'exam_series', 'exam_status', 'grade', 'validity', 'valid_until'];
		$setFields = "";
		$recsLoaded = 0;

		// Populate the Data Table
		if ($fileStream){
            // Clear out the data
            $q = "UPDATE `".WEBCRD_EXAM_STATUS_DATA."`"
				." SET `is_delete`=1".$this->update_common_sql()
				." WHERE `master_id`=0"
			;
            $res = $this->re_db_query($q);

			while (($getData = fgetcsv($fileStream, 10000, ",")) !== FALSE) {
				// Skip the header record - strcasecmp() returns "0" if there is a case-INsensitive MATCH (kinda bass-ackwards)
				if (strcasecmp($getData[0],'first name') AND strcasecmp($getData[0],'last name')){
					$setFields = "";

					foreach ($fileFields AS $fileFieldKey=>$fileFieldName){
                        $fileValue = $this->re_db_input($getData[$fileFieldKey]);
                        $setFields .= (empty($setFields)?"":", ")."`$fileFieldName` = '$fileValue'";
					}

					$q = "INSERT INTO `".WEBCRD_EXAM_STATUS_DATA."`"
							." SET "
								.$setFields
								.",file_date = '".date("Y-m-d H:i:s", filectime($filePathAndName))."'"
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
	function check_finra_exam_status($fileRecord=[]){
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
			$return = $instance_broker->insert_update(['fname'=>$fname, 'lname'=>$lname, 'crd'=>$crd]);

			if ($return){
				$brokerId = $_SESSION['last_insert_id'];
				$result = "CRD #/Broker Added";
			}
		} else {
			$brokerId = $brokerMaster['id'];
			$result = "CRD # already exists";
		}

		$q = "UPDATE `".WEBCRD_EXAM_STATUS_DATA."`"
			." SET"
				." `broker_id`= $brokerId"
				.",`result`='$result'"
			." WHERE `id`={$fileRecord['id']}"
		;
		$res = $this->re_db_query($q);

		return $return;
	}

	public function select_finra_exam_status_data($masterId=null, $orderBy=0){
		$masterId = (is_null($masterId) ? $masterId : (int)$this->re_db_input($masterId));
		$orderBy = (int)$this->re_db_input($orderBy);
		$return = [];
		$con = "";

		// 07/30/22 Need to check for "null", because there might be 0 master id's in the table
		if (!is_null($masterId)) { $con .= " AND `at`.`master_id` = $masterId"; }

		if ($orderBy == 0){
			$orderByQuery = "`at`.`id`"; //"`at`.`master_id` DESC, `at`.`individual_crd_no`";
		} else {
			$orderByQuery = "`at`.`last_name`,`at`.`first_name`,`at`.`middle_name`,`at`.`individual_crd_no`";
		}
		// Query data
		$q = "SELECT `at`.*"
				." FROM `".WEBCRD_EXAM_STATUS_DATA."` `at`"
				." WHERE `at`.`is_delete` = 0"
					.$con
				." ORDER BY $orderByQuery"
		;
		$res = $this->re_db_query($q);

		if($this->re_db_num_rows($res)>0){
			$return = $this->re_db_fetch_all($res);
		}

		return $return;
	}
}
?>