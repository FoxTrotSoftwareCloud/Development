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
    // 04/05/22 $_FILES['upload_generic_csv_file']['name'] not found in file_exists(<name>) on the server side(works in //localhost), use the "tmp_name" property instead
    function process_file ($postFileName, $sponsor_id=0, $product_category_id=0){
        $file_id = $return = 0;
        $instance_import = new import();
        $fileName = $postFileName;
        $filePath = DIR_FS.rtrim($this->dataInterface['local_folder'],'/').'/';
        $loadFile = $filePath.$fileName;
        $sponsor_id = (int)$this->re_db_input($sponsor_id);
        $product_category_id = (int)$this->re_db_input($product_category_id);
                
        $file_id = $this->load_current_file_table($fileName, $sponsor_id, $product_category_id);
    
        if ($file_id) { 
            $return = $this->load_detail_table($loadFile, $file_id, $sponsor_id, $product_category_id); 
        } 
        
        if ($return) { 
            $instance_import->reprocess_current_files($file_id, $this->file_type); 
        }
        
        return $return;
    }
    
    function load_current_file_table ($genericCsvFileName, $sponsor_id=0, $product_category_id=0){
        $instance_product_maintenance = new product_maintenance();
        $file_id = $currentImportFiles = 0;
        $fileType = $source = '';
        $currentImportFiles = $this->check_current_files();        
        $fileName = strtoupper(trim($genericCsvFileName));
        $sponsor_id = (empty((int)$sponsor_id) ? 0 : (int)$this->re_db_input($sponsor_id));
        $product_category_id = (empty((int)$product_category_id) ? 0 : (int)$this->re_db_input($product_category_id));
        $productCategoryArray = $instance_product_maintenance->select_category($product_category_id);
        
        if (pathInfo($fileName, PATHINFO_EXTENSION)=='CSV' AND !in_array($fileName, $currentImportFiles)){
            // File Type
            // Have to use the stripos()!==false, stripos() returns unpredictable "false" values(booleanfalse OR 0 OR <blank>) and if the position is found at the beginning the string, it returns 0
            if ($productCategoryArray) {
                $fileType = trim($productCategoryArray[0]['type']);
            } else if (stripos($fileName, 'VA')!==false OR stripos($fileName, 'VARIABLE')!==false) {
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
                        .",`sponsor_id` = $sponsor_id"
                        .$this->insert_common_sql();
            $res = $this->re_db_query($q);
            
            if ($res) {$file_id = $this->re_db_insert_id(); }
        } else if (in_array($fileName, $currentImportFiles)){
                $q = "SELECT `id`,`file_name` FROM `".IMPORT_CURRENT_FILES."` WHERE `is_delete`=0 AND `processed`='0' AND TRIM(UPPER(`file_name`))='{strtoupper(trim($fileName))}'; ";
				$res = $this->re_db_query($q);
				$return = $this->re_db_num_rows($res);

            if ($return){
                $res = $this->re_db_fetch_array($res);
                $file_id = $res['id'];
            }
        }

        return $file_id;
    }
    
    // Insert files from "local folder" into IMPORT CURRENT FILES table
    function process_directory_files ($local_dir = ''){
        $returnFilesEntered = $currentImportFiles = $file_id = 0;
        $fileType = $source = '';
        // Format the path to be function suitable - remove backslashes and add forward slash to the end
        $local_dir = $this->dataInterface['local_folder'];
        $filePath = DIR_FS.rtrim($local_dir, "/")."/";
        $genericFileArray = scandir($filePath);
        
        if ($genericFileArray) {
            $currentImportFiles = $this->check_current_files();        
            
            foreach($genericFileArray AS $genericFile) {
                $fileName = strtoupper(trim($genericFile));
                
                if (pathInfo($fileName, PATHINFO_EXTENSION)=='CSV' AND !in_array($fileName, $currentImportFiles)){
                    $file_id = $this->process_file($fileName, '', '');
                    
                    if ($file_id){
                        $returnFilesEntered += 1;             
                    }
                }
            }
        }
        return $returnFilesEntered;
    }
    
    // Load CSV Data into into the "?_detail_table"
    function load_detail_table ($filePathAndName, $file_id, $sponsor_id=0, $product_category_id=0){
        $instance_productMaster = new product_master();
        $instance_product_maintenance = new product_maintenance();
        
        $result = 0;
        $currentFileType = $this->check_file_type($file_id, 1);
        $sponsor_id = (empty((int)$sponsor_id) ? 0 : (int)$this->re_db_input($sponsor_id));
        if (empty($product_category_id)){
            $productCategory = $instance_productMaster->select_product_type(str_replace('Generic Commission - ', '', $currentFileType));
            $productCategoryId = (count($productCategory) > 0) ? $productCategory[0]['id'] : 0;
        } else {
            $productCategoryId = (int)$this->re_db_input($product_category_id);
        }

        $fileDetailArray = $this->load_csvfile_detail($filePathAndName);
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
                        ."(`file_id`, `product_category`, `sponsor_id`, ".$tableFields.$insertCommonSQL['fields'].")"
                        ." values "
                        ."($file_id, $productCategoryId, $sponsor_id, ".$fieldValues.$insertCommonSQL['values'].")"
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

    // PHP Version of console.log(). For debugging on the server
    function PHPconsole_log($output, $with_script_tags = true) {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }

}

?>