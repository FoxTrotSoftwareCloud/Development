<?php
class webcrd extends db{

    public $errors = '';
    public $table = WEBCRD_MASTER;
    public $local_folder = DIR_FS.'import_files/webcrd/';

	function insert_update_master($tableData=[]){
		if (empty($tableData))
			return 0;

		$return = 0;
		$setFields = '';

		foreach ($tableData AS $dataKey=>$dataValue){
			$setFields .= (empty($setFields)?'':', ')."`$dataKey` = '$dataValue'";
		}

		$q = "INSERT INTO `".$this->table."`"
			." SET $setFields"
				.$this->insert_common_sql()
		;
		$res = $this->re_db_query($q);

		if ($res) { $return = $this->re_db_insert_id(); }

		return $return;
	}
	// 07/31/22 Pull the file date from the front of the file - assumes the format is "mm.dd.yyy_FILE_TYPE.csv", e.g. "6.30.22_CE_DOWNLOAD.csv"
	function getFileDate($fileName=''){
		if ($fileName=='')
			return 0;
    
		$fileName = basename($this->re_db_input($fileName));
		$return = strtr(substr($fileName, 0, strpos($fileName,'_')), '.', '/');
    	
		return  (bool)strtotime($return) ? $return : '';
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
		$fileDate = $this->getFileDate($filePathAndName);
		if ($fileDate!='') { 
			$fileDate = date("Y-m-d H:i:s", strtotime($fileDate));
		} else {
			$fileDate = date("Y-m-d H:i:s", filemtime($filePathAndName)); 
		}
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
								.",file_date = '".$fileDate."'"
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
		
		if (substr(basename($filePathAndName),0,3)=="php" AND isset($_FILES['file_finra_exam_status'])){
			$realFileName = $_FILES['file_finra_exam_status']['name'];
		} else {
			$realFileName = basename($filePathAndName);
		}
		
		$fileStream = fopen($filePathAndName, 'r');
		$fileFields = ['first_name', 'last_name', 'individual_crd_no', 'registrations', 'disclosures', 'exams', 'deficiencies', 'branch_locations', 'other_business', 
	                   'exam_series', 'exam_status', 'grade', 'validity', 'valid_until'];
		$fileDate = $this->getFileDate($realFileName);
		if ($fileDate!='') { 
			$fileDate = date("Y-m-d H:i:s", strtotime($fileDate));
		} else {
			$fileDate = date("Y-m-d H:i:s", filemtime($filePathAndName)); 
		}
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
				// Skip the header record - strcasecmp() returns "0" if there is a case-INsensitive(binary) MATCH (kinda bass-ackwards)
				if (strcasecmp($getData[0],'first name') AND strcasecmp($getData[0],'last name')){
					$setFields = "";

					foreach ($fileFields AS $fileFieldKey=>$fileFieldName){
                        $fileValue = $this->re_db_input($getData[$fileFieldKey]);
                        $setFields .= (empty($setFields)?"":", ")."`$fileFieldName` = '$fileValue'";
					}

					$q = "INSERT INTO `".WEBCRD_EXAM_STATUS_DATA."`"
						." SET "
							.$setFields
							.",file_date = '$fileDate'"
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
		// Skip the processing for invalid data record, Rep CRD #, Series Exam, Failed Test		
		if (empty($fileRecord) OR empty($fileRecord['individual_crd_no']) OR $fileRecord['grade']!='PASSED'
		 	OR $fileRecord['validity']=='EXPIRED' OR substr($fileRecord['exam_series'],0,1)!=='S'
		 ){
			$q = "UPDATE `".WEBCRD_EXAM_STATUS_DATA."`"
				." SET"
					." `broker_id`= $brokerId"
					.",`result`='No Change'"
				." WHERE `id`={$fileRecord['id']}"
			;
			$res = $this->re_db_query($q);
			
			return $return;
		 }

		$instance_broker = new broker_master();
		$crd = $this->re_db_input($fileRecord['individual_crd_no']);
		$brokerMaster = $instance_broker->select_broker_by_id(0, 'crd', $crd);
		$master_register = $instance_broker->select_register();
		$result = $res = '';
		
		// Load broker into BROKER REGISTER
		if ($brokerMaster) {
			// Find the correct Series
			if (strtoupper($fileRecord['exam_series'])==='SIE'){
				$license_name = 'Securities Industry Essentials';
			} else {
				$license_name = 'Series '.strtr(substr($fileRecord['exam_series'],1),['TO'=>' Top-Off']);
			}
			
			$registerRow = array_search($license_name, array_column($master_register, 'type'));
			$license_id = ($registerRow ? $master_register[$registerRow]['id'] : 0);
			
			// INSERT or UPDATE the reps' Series License
			if ($license_id AND $brokerMaster['id']){
				$brokerRegister = $instance_broker->edit_registers($brokerMaster['id'], $license_id);
				$fileDate = date("Y-m-d", strtotime($fileRecord['file_date']));
				$expireDate = date("Y-m-d", strtotime($fileRecord['valid_until']));
				
				// Just UPDATE the license record
				if ($brokerRegister){
					$expirationQuery = ($expireDate>$brokerRegister[0]['expiration_date']) ? ",`expiration_date`='$expireDate'" : "";
					
					if ($fileDate > $brokerRegister[0]['approval_date']){
						$q = "UPDATE `".BROKER_REGISTER_MASTER."`"
							." SET `approval_date`='$fileDate'"
							.$expirationQuery
							.$this->update_common_sql()
							." WHERE `id`={$brokerRegister[0]['id']}"
						;
						$res = $this->re_db_query($q);
						$result = "$license_name Updated";
					}
				} else {
					$q = "INSERT `".BROKER_REGISTER_MASTER."`"
						." SET `broker_id`={$brokerMaster['id']}"
							.",`license_id`=$license_id"
							.",`license_name`='$license_name'"
							.",`approval_date`='$fileDate'"
							.",`expiration_date`='$expireDate'"
							.$this->insert_common_sql()
					;
					$res = $this->re_db_query($q);
					$result = "$license_name Added";
				}
			}
		}
		
		if ($res){
			$q = "UPDATE `".WEBCRD_EXAM_STATUS_DATA."`"
				." SET"
					." `broker_id`= $brokerId"
					.",`result`='$result'"
				." WHERE `id`={$fileRecord['id']}"
			;
			$res = $this->re_db_query($q);
		}

		return $res;
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