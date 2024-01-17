<?php

class import_client extends import
{
    public $table = 'ft_import_orion_detail_data';
    public $name = 'Orion';
    // Populated in the constructor
    public $file_type = 0;
    public $dim_id = 0;
    public $dataInterface = [];
    public $fileNamePrefix = 'Orion - ';

    function __construct()
    {
        $instance_dim = new data_interfaces_master();
        $this->dataInterface = $instance_dim->select("`is_delete`=0 AND TRIM(LOWER(name))='" . trim(strtolower($this->name)) . "'");
        if ($this->dataInterface) {
            $this->dataInterface = $this->dataInterface[0];
            $this->file_type = $this->dataInterface['id'];
            $this->dim_id = $this->dataInterface['dim_id'];
        }
    }

    // Fully cycle a file Decode CSV -> Process 
    // 04/05/22 $_FILES['upload_generic_csv_file']['name'] not found in file_exists(<name>) on the server side(works in //localhost), use the "tmp_name" property instead
    function process_file($postFileName, $sponsor_id = 0, $product_category_id = 0, $date = '')
    {
        $file_id = $return = 0;
        $instance_import = new import();
        $fileName = $postFileName;
        $filePath = DIR_FS . rtrim($this->dataInterface['local_folder'], '/') . '/';
        $loadFile = $filePath . $fileName;
        $sponsor_id = (int)$this->re_db_input($sponsor_id);
        $product_category_id = (int)$this->re_db_input($product_category_id);

        $file_id = $this->load_current_file_table($fileName, $sponsor_id, $product_category_id, $date);
        if ($file_id) {
            $return = $this->load_detail_table($loadFile, $file_id, $sponsor_id, $product_category_id);
        }

        return $return;
    }

    function load_current_file_table($file_name = '', $sponsor_id = 0, $product_category_id = 0, $date='',$source = 'CLIENT')
    {

        $instance_product_maintenance = new product_maintenance();
        $file_id = $currentImportFiles = 0;
        $fileType = '';
        $currentImportFiles = $this->check_current_files();
        $fileName = $this->re_db_input($file_name);
        $sponsor_id = (empty((int)$sponsor_id) ? 0 : (int)$this->re_db_input($sponsor_id));
        $product_category_id = (empty((int)$product_category_id) ? 0 : (int)$this->re_db_input($product_category_id));
        $productCategoryArray = $instance_product_maintenance->select_category($product_category_id);
        $source = $this->re_db_input($source);

        if (($source == 'CLIENT' or pathInfo($fileName, PATHINFO_EXTENSION) == 'CSV' or pathInfo($fileName, PATHINFO_EXTENSION) == 'csv') and !in_array($fileName, $currentImportFiles)) {

            // File Type
            // Have to use the stripos()!==false, stripos() returns unpredictable "false" values(booleanfalse OR 0 OR <blank>) and if the position is found at the beginning the string, it returns 0


            $q = "INSERT INTO `" . IMPORT_CURRENT_FILES . "`"
                . " SET "
                . "`user_id` = '" . $_SESSION['user_id'] . "'"
                . ",`imported_date` = '" . date('Y-m-d') . "'"
                . ",`file_name` = '$fileName'"
                . ",`file_type` = ''"
                . ",`source` = ''"
                . ",`sponsor_id` = $sponsor_id"
                . ",`is_delete` = 1"
                . ",`processed` = 1"
                . ",`process_completed` = 1"
                . ",`deposite_date` = '" . $this->re_db_input(date('Y-m-d', strtotime($date))) . "'"
                . $this->insert_common_sql();
            $res = $this->re_db_query($q);

            if ($res) {
                $file_id = $this->re_db_insert_id();
            }
        } else if (in_array($fileName, $currentImportFiles)) {
            $q = "SELECT `id`,`file_name` FROM `" . IMPORT_CURRENT_FILES . "` WHERE `is_delete`=0 AND `processed`='0' AND TRIM(`file_name`)='{trim($fileName)}'; ";
            $res = $this->re_db_query($q);
            $return = $this->re_db_num_rows($res);

            if ($return) {
                $res = $this->re_db_fetch_array($res);
                $file_id = $res['id'];
            }
        }

        return $file_id;
    }

    // Insert files from "local folder" into IMPORT CURRENT FILES table
    function process_directory_files($local_dir = '')
    {
        $returnFilesEntered = $currentImportFiles = $file_id = 0;
        $fileType = $source = '';
        // Format the path to be function suitable - remove backslashes and add forward slash to the end
        $local_dir = $this->dataInterface['local_folder'];
        $filePath = DIR_FS . rtrim($local_dir, "/") . "/";
        $genericFileArray = scandir($filePath);

        if ($genericFileArray) {
            $currentImportFiles = $this->check_current_files();

            foreach ($genericFileArray as $genericFile) {
                $fileName = strtoupper(trim($genericFile));

                if (pathInfo($fileName, PATHINFO_EXTENSION) == 'CSV' and !in_array($fileName, $currentImportFiles)) {
                    $file_id = $this->process_file($fileName, '', '');

                    if ($file_id) {
                        $returnFilesEntered += 1;
                    }
                }
            }
        }
        return $returnFilesEntered;
    }

    // Load CSV Data into into the "?_detail_table"
    function load_detail_table($filePathAndName, $file_id, $sponsor_id = 0, $product_category_id = 0)
    {

        $fileDetailArray = $this->load_file_detail($filePathAndName, $file_id);

        if ($fileDetailArray) {
            $_SESSION['success'] = 'Insert client data successfully.';
            return true;
        } else {
            $_SESSION['warning'] = 'Something wents wrong.';
            return false;
        }
    }

    function load_file_detail($filePathAndName = '', $file_id)
    {

        $result = 0;

        // Get File Creds
        $resultArray = [];

        // Validate & Load the file
        if (file_exists($filePathAndName)) {
            $fileStream = fopen($filePathAndName, "r");

            while (($getCsv = fgetcsv($fileStream, 10000, ",")) !== FALSE) {
                array_push($resultArray, $getCsv);
            }

            $fileStream = fclose($fileStream);
        } else {
            $this->errors = 'File: ' . $filePathAndName . ' not found.';
        }


        if ($resultArray) {
            $insertCommonSQL = $this->insert_common_sql(3);

            foreach ($resultArray as $rowNumber => $rowArray) {

                $sponser_id = $broker_id = '';
                // Skip header row
                if ($rowNumber == 0) {
                } else {

                    $instance_broker = new broker_master();
                    $get_broker_list = $instance_broker->select_broker();
                    foreach ($get_broker_list as $key => $val) {
                        if (($val['last_name'] . ", " . $val['first_name']) == $rowArray[12]) {
                            $broker_id = $val['id'];
                        }
                    }

                    if($broker_id == ''){
                        $commaSeparatedString = explode(", ",  $rowArray[12]);
                        $lname = trim($commaSeparatedString[0]);
                        $fname = trim($commaSeparatedString[1]);
                        $internal = trim($rowArray[11]);
                        $q = "INSERT INTO `" . BROKER_MASTER . "` SET `first_name`='" . $fname . "',`last_name`='" . $lname . "',`middle_name`='',`suffix`='',`fund`='',`internal`='" . $internal . "',`display_on_statement`='',`tax_id`='0',`crd`='',`ssn`='0',`active_status`='1',`pay_method`='0'" . $this->insert_common_sql();
                        $res = $this->re_db_query($q);
                        $id = $this->re_db_insert_id();
                        $broker_id = $id;
                    }

                    $instance_sponser = new manage_sponsor();
                    $get_sponser_list = $instance_sponser->select_sponsor();
                    foreach ($get_sponser_list as $key => $val) {
                        if (trim($val['name']) == trim(strtoupper($rowArray[14]))) {
                            $sponser_id = $val['id'];
                        }
                    }

                    if($sponser_id == ''){
                        $q = "INSERT INTO `".SPONSOR_MASTER."` SET `name`='".strtoupper($rowArray[14])."',`address1`='',`address2`='',`city`='',`state`='0',`zip_code`='',`email`='',`website`='',`general_contact`='',`general_phone`='',`operations_contact`='',`operations_phone`='',`dst_system_id`='',`dst_mgmt_code`='',`dst_importing`='',`dazl_code`='',`dazl_importing`='',`dtcc_nscc_id`='',`clearing_firm_id`=''".$this->insert_common_sql();
                        $res = $this->re_db_query($q);
                        $id = $this->re_db_insert_id();
                        $sponser_id = $id;
                    }

                    $from = new DateTime($rowArray[9]);
                    $to = new DateTime('today');
                    $age = $from->diff($to)->y;
                    $today = date('Y-m-d');

                    $check_digit = preg_match('/^[0-9]+$/', $rowArray[1]);

                    if ($check_digit) {
                        $set_ssn = str_pad($rowArray[1], 10, '0', STR_PAD_LEFT);
                    } else {
                        $set_ssn = $rowArray[1];
                    }

                    $q = "SELECT `cm`.*
					FROM `" . CLIENT_MASTER . "` AS `cm`
                    WHERE `cm`.`is_delete`='0' AND `cm`.`first_name`='" . str_replace("'", "''", $rowArray[2]) . "'
                    AND `cm`.`last_name`='" . str_replace("'", "''", $rowArray[3]) . "'
                    AND `cm`.`client_file_number`='" . str_replace("'", "''", $rowArray[1]) . "'
                    ";
                    $res = $this->re_db_query($q);
                    if ($this->re_db_num_rows($res) > 0) {
                        $get_client = $this->re_db_fetch_array($res);
                        $get_client_id = $get_client['id'];

                            $q = "UPDATE " . CLIENT_MASTER . ""
                                . " SET address1='" . str_replace("'", "''", $rowArray[4]) . "'"
                                . ",`address2`='" . str_replace("'", "''", $rowArray[5]) . "'"
                                . ",`client_ssn`='" . str_replace("'", "''", $set_ssn) . "'"
                                . ",`city`='" . str_replace("'", "''", $rowArray[6]) . "'"
                                . ",`state`='" . str_replace("'", "''", $rowArray[7]) . "'"
                                . ",`zip_code`='" . str_replace("'", "''", $rowArray[8]) . "'"
                                . ",`age`='" . $age . "'"
                                . ",`birth_date`='" . $this->re_db_input(date('Y-m-d', strtotime($rowArray[9]))) . "'"
                                . ",`broker_name`='" . $broker_id . "'"
                                . ",`email`='" . str_replace("'", "''", $rowArray[10]) . "'"
                                . ",`file_id`='" . $file_id . "'"
                                . $this->update_common_sql()
                                . " WHERE id='" . $get_client_id . "' AND is_delete=0";
                            $res = $this->re_db_query($q);

                        $q = "SELECT `at`.*
                        FROM `" . CLIENT_ACCOUNT . "` AS `at`
                        WHERE `at`.`is_delete`='0' and `at`.`client_id`=" . $get_client_id . "
                        ORDER BY `at`.`id` ASC";
                        $res = $this->re_db_query($q);
                        if ($this->re_db_num_rows($res) > 0) {
                            $get_account = $this->re_db_fetch_all($res);
                            $found_account = false;
                            foreach ($get_account as $key => $val) {
                                if ($val['account_no'] == $rowArray[0] && $val['is_delete'] == 0) {
                                    if($val['sponsor_company'] == $sponser_id){
                                        $found_account = true;
                                    }else{
                                        $q = "UPDATE `".CLIENT_ACCOUNT."` SET `sponsor_company`='" . $sponser_id . "'".$this->update_common_sql()." WHERE `id`='".$val['id']."' AND `is_delete`=0";
				                        $res = $this->re_db_query($q);
                                        $found_account = true;
                                    }
                                }
                            }
                            if (!$found_account) {
                                $q = "INSERT INTO `" . CLIENT_ACCOUNT . "` SET `client_id`='" . $get_client_id . "',`account_no`='" . $rowArray[0] . "',`sponsor_company`='" . $sponser_id . "'" . $this->insert_common_sql();
                                $res = $this->re_db_query($q);
                            }
                        } else {
                            $q = "INSERT INTO `" . CLIENT_ACCOUNT . "` SET `client_id`='" . $get_client_id . "',`account_no`='" . $rowArray[0] . "',`sponsor_company`='" . $sponser_id . "'" . $this->insert_common_sql();
                            $res = $this->re_db_query($q);
                        }
                    } else {

                        $open_date = '0000-00-00';
                        $last_contacted = $telephone = $ressign = $reviewed_at = '0000-00-00';

                        $q = "INSERT INTO `" . CLIENT_MASTER . "`"
                            . " SET `first_name`='" . str_replace("'", "''", $rowArray[2]) . "'"
                            . ",`last_name`='" . str_replace("'", "''", $rowArray[3]) . "'"
                            . ",`mi`=''"
                            . ",`do_not_contact`=0"
                            . ",`active`=0"
                            . ",`ofac_check`=''"
                            . ",`fincen_check`=''"
                            . ",`long_name`=''"
                            . ",`client_file_number`='" . str_replace("'", "''", $rowArray[1]) . "'"
                            . ",`clearing_account`=''"
                            . ",`client_ssn`='" . str_replace("'", "''", $set_ssn) . "'"
                            . ",`house_hold`=''"
                            . ",`split_broker`=''"
                            . ",`split_rate`=0"
                            . ",`address1`='" . str_replace("'", "''", $rowArray[4]) . "'"
                            . ",`address2`='" . str_replace("'", "''", $rowArray[5]) . "'"
                            . ",`city`='" . str_replace("'", "''", $rowArray[6]) . "'"
                            . ",`state`='" . str_replace("'", "''", $rowArray[7]) . "'"
                            . ",`zip_code`='" . str_replace("'", "''", $rowArray[8]) . "'"
                            . ",`citizenship`=''"
                            . ",`birth_date`='" . $this->re_db_input(date('Y-m-d', strtotime($rowArray[9]))) . "'"
                            . ",`date_established`='" . $today . "'"
                            . ",`age`='" . $age . "'"
                            . ",`gender`=0"
                            . ",`open_date`='" . $open_date . "'"
                            . ",`naf_date`='" . $today . "'"
                            . ",`last_contacted`='" . $last_contacted . "'"
                            . ",`account_type`=0"
                            . ",`broker_name`='" . $broker_id . "'"
                            . ",`broker_old_name`=0"
                            . ",`ressign_date`='" . $ressign . "'"
                            . ",`telephone`=''"
                            . ",`contact_status`=0"
                            . ",`email`='" . str_replace("'", "''", $rowArray[10]) . "'"
                            . ",`status`=1"
                            . ",`reviewed_at`='" . $reviewed_at . "'"
                            . ",`reviewed_by`='" . $_SESSION['user_id'] . "'"
                            . ",`is_reviewed`=0"
                            . ",`split_rate_to`=''"
                            . ",`split_rate_from`=''"
                            . ",`split_rate_category`=''"
                            . ",`file_id`='" . $file_id . "'"
                            . $this->insert_common_sql();

                        $res = $this->re_db_query($q);
                        $client_id = $this->re_db_insert_id();

                        $expiration = $date_verified = "0000-00-00 00:00:00";
                        $occupation = $employer = $address_employement = $position = $security_related_firm = $spouse_name = $spouse_ssn = $dependents = $salutation = $other = $number = $state_employe = $telephone_employment = '';
                        $security_related_firm = $finra_affiliation = $options = 0;

                        $q = "INSERT INTO `" . CLIENT_EMPLOYMENT . "` SET `client_id`='" . $client_id . "',`occupation`='" . $occupation . "',`employer`='" . $employer . "',`address`='" . $address_employement . "',`position`='" . $position . "',`security_related_firm`='" . $security_related_firm . "',`finra_affiliation`='" . $finra_affiliation . "',`spouse_name`='" . $spouse_name . "',`spouse_ssn`='" . $spouse_ssn . "',`dependents`='" . $dependents . "',`salutation`='" . $salutation . "',`options`='" . $options . "',`other`='" . $other . "',`number`='" . $number . "',`expiration`='" . $expiration . "',`state`='" . $state_employe . "',`date_verified`='" . $date_verified . "',`telephone`='" . $telephone_employment . "'" . $this->insert_common_sql();
                        $res = $this->re_db_query($q);
                        $e_id = $this->re_db_insert_id();

                        $income = $net_worth = $liquid_net_worth = 2;
                        $goal_horizone = $risk_tolerance = $annual_expenses = $liquidity_needs = $special_expenses = $per_of_portfolio = $timeframe_for_special_exp = $account_use = $tax_id = 0;
                        $signed_by = $tax_bracket = '';
                        $sign_date = "0000-00-00 00:00:00";

                        $q = "INSERT INTO `" . CLIENT_SUITABILITY . "`"
                            . " SET `client_id`='" . $client_id . "',`income`='" . $income . "',`goal_horizon`='" . $goal_horizone . "',`net_worth`='" . $net_worth . "'"
                            . " ,`risk_tolerance`='" . $risk_tolerance . "',`annual_expenses`='" . $annual_expenses . "',`liquidity_needs`='" . $liquidity_needs . "',`liquid_net_worth`='" . $liquid_net_worth . "'"
                            . " ,`special_expenses`='" . $special_expenses . "',`per_of_portfolio`='" . $per_of_portfolio . "',`time_frame_for_special_exp`='" . $timeframe_for_special_exp . "'"
                            . " ,`account_use`='" . $account_use . "',`signed_by`='" . $signed_by . "',`sign_date`='" . $sign_date . "',`tax_bracket`='" . $tax_bracket . "',`tax_id`='" . $tax_id . "'"
                            . $this->insert_common_sql();
                        $res = $this->re_db_query($q);
                        $s_id = $this->re_db_insert_id();

                        $q = "INSERT INTO `" . CLIENT_ACCOUNT . "` SET `client_id`='" . $client_id . "',`account_no`='" . $rowArray[0] . "',`sponsor_company`='" . $sponser_id . "'" . $this->insert_common_sql();
                        $res = $this->re_db_query($q);
                        $a_id = $this->re_db_insert_id();
                    }
                    if ($res) {
                        $result += 1;
                    } else {
                        $this->errors = 'File: ' . $filePathAndName . ' not found.';
                        $_SESSION['warning'] = $this->errors;
                    }
                }
            }
            return $result;
        }
    }

    // PHP Version of console.log(). For debugging on the server
    function PHPconsole_log($output, $with_script_tags = true)
    {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }
}
