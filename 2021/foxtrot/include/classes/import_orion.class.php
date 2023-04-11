<?php 

class import_orion extends import {
    public $table = 'ft_import_orion_detail_data';
    public $name = 'Orion';
    // Populated in the constructor
    public $file_type = 0;
    public $dim_id = 0;
    public $dataInterface = [];
    public $fileNamePrefix = 'Orion - ';
    
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
        
        $instance_sponsor = new manage_sponsor();
        $get_sponsor_list = $instance_sponsor->select_sponsor(1);

        foreach ($get_sponsor_list as $key => $val){
            if($val['name'] == "ORION"){
                $sponsor_id = $val['id'];
            }
        }
        if($sponsor_id == 0)
        {
            header('location:http://localhost/CloudFox/Development/2021/foxtrot/manage_sponsor.php?action=add_sponsor');exit;
        }

        $file_id = $this->load_current_file_table($fileName, $sponsor_id, $product_category_id);
        if ($file_id) { 
            $return = $this->load_detail_table($loadFile, $file_id, $sponsor_id, $product_category_id); 
        } 
        // echo '<pre>';
        // print_r($return);die;
        if ($return) { 
            $instance_import->reprocess_current_files($file_id, $this->file_type); 
        }
        
        return $return;
    }
    
    function load_current_file_table ($file_name='', $sponsor_id=0, $product_category_id=0, $source='ORION'){
        
        $instance_product_maintenance = new product_maintenance();
        $file_id = $currentImportFiles = 0;
        $fileType = '';
        $currentImportFiles = $this->check_current_files();        
        $fileName = $this->re_db_input($file_name);
        $sponsor_id = (empty((int)$sponsor_id) ? 0 : (int)$this->re_db_input($sponsor_id));
        $product_category_id = (empty((int)$product_category_id) ? 0 : (int)$this->re_db_input($product_category_id));
        $productCategoryArray = $instance_product_maintenance->select_category($product_category_id);
        $source = $this->re_db_input($source);
        
        if (($source!='ORION' OR pathInfo($fileName, PATHINFO_EXTENSION)=='CSV' OR pathInfo($fileName, PATHINFO_EXTENSION)=='csv') AND !in_array($fileName, $currentImportFiles)){

            // File Type
            // Have to use the stripos()!==false, stripos() returns unpredictable "false" values(booleanfalse OR 0 OR <blank>) and if the position is found at the beginning the string, it returns 0

            $fileType = 'RIA';

            $fileType = ($source=='ORION' ? $this->fileNamePrefix.$fileType : $source);
        
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
            
            if ($res) {
                $file_id = $this->re_db_insert_id();
            }
        } else if (in_array($fileName, $currentImportFiles)){
                $q = "SELECT `id`,`file_name` FROM `".IMPORT_CURRENT_FILES."` WHERE `is_delete`=0 AND `processed`='0' AND TRIM(`file_name`)='{trim($fileName)}'; ";
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
            $productCategory = $instance_productMaster->select_product_type(str_replace('Orion - ', '', $currentFileType));
            $productCategoryId = (count($productCategory) > 0) ? $productCategory[0]['id'] : 0;
        } else {
            $productCategoryId = (int)$this->re_db_input($product_category_id);
        }
    
        $fileDetailArray = $this->load_csvfile_detail($filePathAndName);
        // echo '<pre>';
        // print_r($fileDetailArray);die;
        $repnumber = "";
        if ($fileDetailArray){
            $insertCommonSQL = $this->insert_common_sql(3);
            
            foreach ($fileDetailArray AS $rowNumber=>$rowArray){

                $brokername = "";
                $broker_id = 0;
                $data = [];
                // Skip header row
                if ($rowNumber == 0 || $rowNumber == 1||$rowNumber == 2||$rowNumber == 3 ){
                    
                } else {
                   
                    // Validate the file values
                    $fieldValues = "";
                    $repname="repname";
                    // $rowArray[12]=$rowArray[3];
                    
                    if(empty($rowArray[1]) || strpos($rowArray[6], "Net Fees") !== false || strpos($rowArray[6], "Rep No:") !== false || strpos($rowArray[9], "Broker/Dealer:") !== false){
                        if(strpos($rowArray[6], "Rep No:") !== false){
                            $repnumber = trim($rowArray[6], "Rep No:");
                        }
                        
                        if(strpos($rowArray[9], "Broker/Dealer:") !== false){
                            $brokername = trim(str_replace(array("Broker/Dealer:"),"",$rowArray[9])," ");
                        }

                        if(!empty($brokername)){
                            $instance_broker = new broker_master();
                            $broker_id = $instance_broker->get_borker_id_by_name($brokername);
                            
                            if($broker_id == 0)
                            {
                                $data['active_status_cdd'] = 0;
                                $data['fund'] = $repnumber;
                                $data['lname'] = $brokername;
                                $borker_add=$instance_broker->insert_update($data);
                                if($borker_add){
                                    $broker_id = $instance_broker->get_borker_id_by_name($brokername);
                                }
                            }
                        }
                        
                        $fieldValues = "";
                        continue;
                        
                    }else{
                        foreach ($rowArray AS $fieldKey=>$fieldValue){
                                                
                            if($fieldKey == 0){
                                $addValue = (empty($fieldValue) ? "null" : $fieldValue);
                                $fieldValues = '"'.$repname.'"'.",".'"'.$repnumber.'"'.",".'"'.$addValue.'"';
                            }
                            if($fieldKey == 1){
                                
                                $addValue = $fieldValue; 
                            }
                            if($fieldKey == 2){
                                $addValue = '"'.$addValue.$fieldValue.'"';
                                $fieldValues .= (empty($fieldValues) ? '' : ', ').$addValue;
                            }
                            if($fieldKey == 3){
                                $addValue = (empty($fieldValue) ? "null" : $fieldValue);
                                $fieldValues .= (empty($fieldValues) ? '' : ', ').$addValue;
                            }
                            if($fieldKey == 4){
                                continue;
                            }
                            if($fieldKey == 5){
                                $addValue = str_replace(array("$",","),"",$fieldValue);
                                $fieldValues .= (empty($fieldValues) ? '' : ', ').$addValue;
                            }
                            if($fieldKey == 6){
                                
                                if(strpos($fieldValue, "Rep No:") !== false || strpos($fieldValue, "Net Fees") !== false){
                                    $fieldValues = "";
                                    continue;
                                }else{
                                    $addValuefeecheck = str_replace(array("$","-",","," "),"",$fieldValue);
                                    $addValue = empty($addValuefeecheck) ? 0.00 : $addValuefeecheck;
                                    $delercommision = $addValue;
                                    $fieldValues .= (empty($fieldValues) ? '' : ', ').$addValue;
                                }                         
                            }
                            if($fieldKey == 7){
                                $get_sign = str_replace(array("$",","," "),"",$fieldValue);
                                if($get_sign == "-"){
                                    $addValue = 1;
                                }else{
                                    $addValue = 0;
                                    
                                }
                                $fieldValues .= (empty($fieldValues) ? '' : ', ').$addValue;
                                $addValuecheck = str_replace(array("$","-",","," "),"",$fieldValue);
                                $addValue = empty($addValuecheck) ? 0.00 : $addValuecheck;
                                $fieldValues .= (empty($fieldValues) ? '' : ', ').$addValue;
                            }
                            if($fieldKey == 8){
                                $addValuecheck = str_replace(array("$","-",","," "),"",$fieldValue);
                                $addValue = empty($addValuecheck) ? 0.00 : $addValuecheck;
                                $fieldValues .= (empty($fieldValues) ? '' : ', ').$addValue;
                                $fieldValues .= (empty($fieldValues) ? '' : ', ').$delercommision;
                            }
                            if ($fieldKey > 9){
                                break;
                            }                            
                        }
                    }
                    
                    // echo '<pre>';
                    // print_r($fieldValues);die;
                    // Table Fields to populate
                    // 20-12-2022 considered column D as cusip_number so it is added in detail table
                    $tableFields = '';
                    $today_date = date('Y-m-d');

                    // $tableFields = "`representative_name`, `representative_number`,`client_name`, `invest_product`, `customer_account_number`,`net_amount`, `comm_rec`, `charge`, `rep_comm`, `dealer_commission_amount`";
                    $tableFields = "`representative_name`, `representative_number`,`client_name`, `invest_product`, `customer_account_number`,`gross_transaction_amount`, `gross_commission_amount`, `charge_sign`, `charge`, `rep_comm`, `dealer_commission_amount`";
                    
                    $q = "INSERT INTO `".IMPORT_ORION_DETAIL_DATA."` "
                        ."(`file_id`,`broker_id`, `product_category`, `sponsor_id`, `trade_date`, ".$tableFields.$insertCommonSQL['fields'].")"
                        ." values "
                        ."($file_id, $broker_id, $productCategoryId, $sponsor_id, ".$today_date.", ".$fieldValues.$insertCommonSQL['values'].")"
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
            // echo '<pre>';
            // print_r($fieldValues);die;

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