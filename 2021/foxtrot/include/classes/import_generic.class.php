<?php 

class import_generic extends import {
    public $table = 'ft_import_genfile_detail_data';
    public $name = 'Generic CSV';
    // Populated in the constructor
    public $file_type = 0;
    public $dim_id = 0;
    public $dataInterface = [];
    public $fileNamePrefix = 'Generic Commission - ';
    
    function __construct(){
        $instance_dim = new data_interfaces_master();
        $this->dataInterface = $instance_dim->select("`is_delete`=0 AND TRIM(LOWER(name))='".trim(strtolower($this->name))."'");
        
        if ($this->dataInterface){
            $this->dataInterface = $this->dataInterface[0];
            $this->file_type = $this->dataInterface['id'];
            $this->dim_id = $this->dataInterface['dim_id'];
        }
    }

    // Fully cycle a file Decode CSV -> Process 
    function process_file ($genericCsvFileName){
        $file_id = $return = 0;
        $filePathAndName = DIR_FS.str_replace("\\", "/", rtrim($this->dataInterface['local_folder'], "/")."/").
                           strtoupper(trim($genericCsvFileName));

        $file_id = $this->load_current_file_table($genericCsvFileName);
        if ($file_id) {$return = $this->load_detail_table($filePathAndName, $file_id);}
        
        return $return;
    }
    
    function load_current_file_table ($genericCsvFileName, $action=1){
        $file_id = $currentImportFiles = $processed = 0;
        $fileType = $source = '';
        // Format the path to be function suitable - some string functions treat backslashes as Unicode or char escapes
        $filePath = DIR_FS.str_replace("\\", "/", rtrim($this->dataInterface['local_folder'], "/")."/");
        $currentImportFiles = $this->check_current_files();        
        $fileName = strtoupper(trim($genericCsvFileName));
                
        if (pathInfo($fileName, PATHINFO_EXTENSION)=='CSV' AND !in_array($fileName, $currentImportFiles)){
            // File Type
            // Have to use the stripos()!===false, stripos() returns unpredictable "false" values(boolean OR <blank>) and 1st char returns as position = 0
            if (stripos($fileName, 'VA')!==false OR stripos($fileName, 'VARIABLE')!==false) {
                $fileType = 'Variable Annuities';
            } else if (stripos($fileName, 'RIA')!==false OR stripos($fileName, 'ADVISORY')!==false) {
                $fileType = 'RIA';
            } else {
                $fileType = 'Mutual Funds';
            }
            $fileType = $this->fileNamePrefix.$fileType;
            $source = 'GENERIC';
            
            $q = "INSERT INTO `".IMPORT_CURRENT_FILES."`"
                    ." SET "
                        ."`user_id` = '".$_SESSION['user_id']."'"
                        .",`imported_date` = '".date('Y-m-d')."'"
                        .",`file_name` = '$fileName'"
                        .",`file_type` = '$fileType'"
                        .",`source` = '$source'"
                        .$this->insert_common_sql();
            $res = $this->re_db_query($q);
            
            if ($res){$file_id = $this->re_db_insert_id(); }
        }

        return $file_id;
    }
    
    // Insert files from "local folder" into IMPORT CURRENT FILES table
    function process_directory_files ($local_dir = ''){
        $returnFilesEntered = $currentImportFiles = $file_id = 0;
        $fileType = $source = '';
        // Format the path to be function suitable - remove backslashes and add forward slash to the end
        $local_dir = !empty($local_dir) ? trim($local_dir) : $this->dataInterface['local_folder'];
        $filePath = DIR_FS.str_replace("\\", "/", rtrim($local_dir, "/")."/");
        $genericFileArray = scandir($filePath);
        
        if ($genericFileArray) {
            $currentImportFiles = $this->check_current_files();        
            
            foreach($genericFileArray AS $genericFile) {
                $fileName = strtoupper(trim($genericFile));
                
                if (pathInfo($fileName, PATHINFO_EXTENSION)=='CSV' AND !in_array($fileName, $currentImportFiles)){
                    $file_id = $this->process_file($fileName);
                    
                    if ($file_id){
                        $returnFilesEntered += 1;             
                    }
                }
            }
        }
        return $returnFilesEntered;
    }
    
    // Load CSV Data into into the "?_detail_table"
    function load_detail_table ($filePathAndName, $file_id){
        $instance_productMaster = new product_master();
        $result = 0;
        $fileDetailArray = $this->load_csvfile_detail($filePathAndName);
        $currentFileType = $this->check_file_type($file_id, 1);
        $productCategory = $instance_productMaster->select_product_type(str_replace('Generic Commission - ', '', $currentFileType));
        $productCategoryId = (count($productCategory) > 0) ? $productCategory[0]['id'] : 0;
        
        if ($fileDetailArray){
            $insertCommonSQL = $this->insert_common_sql(3);
            
            foreach ($fileDetailArray AS $rowNumber=>$rowArray){
                // Skip header row
                if ($rowNumber == 0){
                    
                } else {
                    // Validate the file values
                    $fieldValues = "";
                    foreach ($rowArray AS $fieldKey=>$fieldValue){
                        // Check if field is a date and convert it to a 'Y-m-d' format
                        if ($fieldKey == 2){
                            $addValue = '"'.date('Y-m-d', strtotime($fieldValue)).'"';
                        } else {
                            $addValue = '"'.($this->re_db_input(str_replace('"', "", $fieldValue))).'"';
                        }
                        $fieldValues .= (empty($fieldValues) ? '' : ', ').$addValue;
                    }
                    // Table Fields to populate
                    $tableFields = '';
                    if ($currentFileType == $this->fileNamePrefix.'RIA') {
                        $tableFields =  "`representative_name`, `representative_number`, `trade_date`,`fund_company`,"
                            ." `customer_account_number`, `alpha_code`, `gross_commission_amount`, `dealer_commission_amount`";
                    } else {
                        $tableFields = "`representative_name`, `representative_number`, `trade_date`,`fund_company`,"
                            ." `customer_account_number`, `alpha_code`, `comm_type`,`gross_transaction_amount`,"
                            ." `gross_commission_amount`, `rep_regular`, `rep_trail`, `dealer_commission_amount`";
                    }
                    // Insert record into the detail table
                    $q = "INSERT INTO `".IMPORT_GEN_DETAIL_DATA."` "
                        ."(`file_id`, `product_category`, ".$tableFields.$insertCommonSQL['fields'].")"
                        ." values "
                        ."($file_id, $productCategoryId, ".$fieldValues.$insertCommonSQL['values'].")"
                    ;
                    $res = $this->re_db_query($q);
                    
                    if ($res){
                        $result += 1;
                    } else {
                        $this->errors = 'File: '.$filePathAndName.' not found.';            
                        $_SESSION['warning'] = $this->errors;
                    }
                }
            }
        }
        
        return $result;
    }
    
    // Load CSV Data into an array for easier reading into the "?_detail_table"
    function load_csvfile_detail($filePathAndName=''){
        // Get File Creds
        $resultArray = [];    

        // Validate & Load the file
        if (file_exists($filePathAndName)){
            $fileStream = fopen($filePathAndName, "r");
                        
            while (($getCsv = fgetcsv($fileStream, 10000, ",")) !== FALSE) {
                array_push($resultArray, $getCsv);
            }
            
            $fileStream = fclose($fileStream);
        } else {
            $this->errors = 'File: '.$filePathAndName.' not found.';            
        }
        
        return $resultArray;
    }

}

?>