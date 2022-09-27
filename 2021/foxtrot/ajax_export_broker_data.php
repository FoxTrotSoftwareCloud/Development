<?php

require_once("include/config.php");
require_once(DIR_FS . "islogin.php");

ob_start();

$instance    = new broker_master();
$return      = $instance->alledit();
//$editgeneral = $instance->edit_allgeneral();
$rows        = array();
$rowIndex=2;
// $broker_data = $instance->get_broker_changes();
foreach ($return as $key => $returns) {
    
    $editreturns = $instance->edit_general($returns['id']);
    $fname                        = isset($returns['first_name']) ? $instance->re_db_output($returns['first_name']) : '';
    $lname                        = isset($returns['last_name']) ? $instance->re_db_output($returns['last_name']) : '';
    $mname                        = isset($returns['middle_name']) ? $instance->re_db_output($returns['middle_name']) : '';
    $_SESSION['broker_full_name'] = $returns['first_name'] . ' ' . $returns['middle_name'] . ' ' . $returns['last_name'];
    $suffix                       = isset($returns['suffix']) ? $instance->re_db_output($returns['suffix']) : '';
    $fund                         = isset($returns['fund']) ? $instance->re_db_output($returns['fund']) : '';
    $internal                     = isset($returns['internal']) ? $instance->re_db_output($returns['internal']) : '';
    $display_on_statement         = isset($returns['display_on_statement']) ? $instance->re_db_output($returns['display_on_statement']) : '';
    $ssn                          = isset($returns['ssn']) ? $instance->re_db_output($returns['ssn']) : '';
    $tax_id                       = isset($returns['tax_id']) ? $instance->re_db_output($returns['tax_id']) : '';
    $crd                          = isset($returns['crd']) ? $instance->re_db_output($returns['crd']) : '';
    $active_status_cdd            = isset($returns['active_status']) ? $instance->re_db_output($returns['active_status']) : '';
    $pay_method                   = $returns['pay_method'] == 1 ? "ACH" : "Check";
    
    /*$branch_manager = isset($returns['branch_manager'])?$instance->re_db_output($returns['branch_manager']):'';
    $branch_name = isset($returns['branch_name'])?$instance->re_db_output($returns['branch_name']):'';
    $branch_office = isset($returns['branch_office'])?$instance->re_db_output($returns['branch_office']):'';*/
    
        $home          = isset($editreturns['home']) ? $instance->re_db_output($editreturns['home']) : '';
        $business_type = '';
        if ($home == 1) {
            $address1      = $home_address1_general;
            $address2      = $home_address2_general;
            $business_type = "Home";
        } else {
            $address1      = $business_address1_general;
            $address2      = $business_address2_general;
            $business_type = "Business";
        }
        $home_address1_general     = isset($editreturns['home_address1_general']) ? $instance->re_db_output($editreturns['home_address1_general']) : '';
        $home_address2_general     = isset($editreturns['home_address2_general']) ? $instance->re_db_output($editreturns['home_address2_general']) : '';
        $business_address1_general = isset($editreturns['business_address1_general']) ? $instance->re_db_output($editreturns['business_address1_general']) : '';
        $business_address2_general = isset($editreturns['business_address2_general']) ? $instance->re_db_output($editreturns['business_address2_general']) : '';
        $city                      = isset($editreturns['city']) ? $instance->re_db_output($editreturns['city']) : '';
        $state_id                  = isset($editreturns['state_id']) ? $instance->re_db_output($editreturns['state_id']) : '';
        $zip_code                  = isset($editreturns['zip_code']) ? $instance->re_db_output($editreturns['zip_code']) : '';
        $telephone                 = isset($editreturns['telephone']) ? $instance->re_db_output($editreturns['telephone']) : '';
        $cell                      = isset($editreturns['cell']) ? $instance->re_db_output($editreturns['cell']) : '';
        $fax                       = isset($editreturns['fax']) ? $instance->re_db_output($editreturns['fax']) : '';
        $gender                    = isset($editreturns['gender']) ? $instance->re_db_output($editreturns['gender']) : '';
        $marital_status            = isset($editreturns['marital_status']) ? $instance->re_db_output($editreturns['marital_status']) : '';
        $spouse                    = isset($editreturns['spouse']) ? $instance->re_db_output($editreturns['spouse']) : '';
        $children                  = isset($editreturns['children']) ? $instance->re_db_output($editreturns['children']) : '';
        $email1                    = isset($editreturns['email1']) ? $instance->re_db_output($editreturns['email1']) : '';
        $email2                    = isset($editreturns['email2']) ? $instance->re_db_output($editreturns['email2']) : '';
        $web_id                    = isset($editreturns['web_id']) ? $instance->re_db_output($editreturns['web_id']) : '';
        $web_password              = isset($editreturns['web_password']) ? $instance->re_db_output($editreturns['web_password']) : '';
        $dob                       = isset($editreturns['dob']) ? date('m/d/Y', strtotime($instance->re_db_output($editreturns['dob']))) : '';
        $prospect_date             = isset($editreturns['prospect_date']) ? date('m/d/Y', strtotime($instance->re_db_output($editreturns['prospect_date']))) : '';
        $reassign_broker           = isset($editreturns['reassign_broker']) ? $instance->re_db_output($editreturns['reassign_broker']) : '';
        if ($reassign_broker > 0) {
            $r_broker_data   = $instance->select_broker_by_id($reassign_broker);
            $reassign_broker = $r_broker_data['last_name'] . ", " . $r_broker_data['first_name'];
        }
        
        // print_r($r_broker_data);
        $u4                       = isset($editreturns['u4']) ? date('m/d/Y', strtotime($instance->re_db_output($editreturns['u4']))) : '';
        $u5                       = isset($editreturns['u5']) ? date('m/d/Y', strtotime($instance->re_db_output($editreturns['u5']))) : '';
        $day_after_u5             = isset($editreturns['day_after_u5']) ? $instance->re_db_output($editreturns['day_after_u5']) : '';
        $dba_name                 = isset($editreturns['dba_name']) ? $instance->re_db_output($editreturns['dba_name']) : '';
        $eft_information          = $editreturns['eft_information'] == 1 ? "Pre-Notes" : "Direct Deposit";
        $start_date               = isset($editreturns['start_date']) ? date('m/d/Y', strtotime($editreturns['start_date'])) : '';
        $transaction_type_general = $editreturns['transaction_type'] == 1 ? "Checking" : "Savings";
        $routing                  = isset($editreturns['routing']) ? $instance->re_db_output($editreturns['routing']) : '';
        $account_no               = isset($editreturns['account_no']) ? $instance->re_db_output($editreturns['account_no']) : '';
        $professional_type        = array();
        $professional_type[]      = isset($editreturns['cfp']) ? $instance->re_db_output($editreturns['cfp']) : '';
        $professional_type[]      = isset($editreturns['chfp']) ? $instance->re_db_output($editreturns['chfp']) : '';
        $professional_type[]      = isset($editreturns['cpa']) ? $instance->re_db_output($editreturns['cpa']) : '';
        $professional_type[]      = isset($editreturns['clu']) ? $instance->re_db_output($editreturns['clu']) : '';
        $professional_type[]      = isset($editreturns['cfa']) ? $instance->re_db_output($editreturns['cfa']) : '';
        $professional_type[]      = isset($editreturns['ria']) ? $instance->re_db_output($editreturns['ria']) : '';
        $professional_type[]      = isset($editreturns['insurance']) ? $instance->re_db_output($editreturns['insurance']) : '';
        $professional_type        = array_filter($professional_type);
        if ($gender == 1) {
            $gender_text = "Male";
        } elseif ($gender == 2) {
            $gender_text = "Male";
        } else if ($gender == 3) {
            $gender_text = "Other";
        } else {
            $gender_text = "";
        }
        
        $columns     = array(
            "First Name" => $fname,
            "Middle Name" => $mname,
            "Last Name" => $lname,
            "Suffix" => $suffix,
            "Internal Broker ID Number" => $internal,
            "Fund/Clearing Number" => $fund,
            "Display On Statement" => $display_on_statement,
            "SSN" => $ssn,
            "Tax ID" => $tax_id,
            "CRD Number" => $crd,
            "Professional designations" => $professional_type,
            "Home/Business" => $business_type,
            "Address 1" => $address1,
            "Address 2" => $address2,
            "City" => $city,
            "State" => $state,
            "Zip code" => $zip_code,
            "Telephone" => $telephone,
            "Cell" => $cell,
            "Fax" => $fax,
            "Gender" => $gender_text,
            "Status" => $marital_status,
            "Spouse" => $spouse,
            "Children" => $children,
            "Primary Email" => $email1,
            "Secondary Email" => $email2,
            "Web ID" => $web_id,
            "Web Password " => $web_password,
            "DOB" => $dob,
            "Prospect Date" => $prospect_date,
            "U4" => $u4,
            "U5/Termination Date" => $u5,
            "Reassign Non-Trailer Business to Broker " => $reassign_broker,
            "Days after U5/Termination " => $day_after_u5,
            "DBA Name" => $dba_name,
            "Pay Method" => $pay_method,
            "EFT Information" => $eft_information,
            "Start Date" => $start_date,
            "Transaction Type " => $transaction_type_general,
            "Bank Routing Number" => $routing,
            "Account Number" => $account_no
        );
        $columnIndex = "A";
       
        foreach ($columns as $index => $column_key) {

            $rows[$columnIndex . "1"] = $index;
            $rows[$columnIndex . $rowIndex] = $column_key;
            $columnIndex++;
        }
        $rowIndex++;
        
    
}

$Excel = new Excel();
/*for($i="A";$i!=='AZ';$i++){
$rows
}*/



$creator                = "Foxtrot Broker";
$last_modified_by       = "Foxtrot User";
$title                  = "Foxtrot Broker Excel";
$subject                = "Foxtrot Broker";
$description            = "Foxtrot Broker Report. Generated on : " . date('Y-m-d h:i:s');
$keywords               = "Foxtrot Transaction report office 2007";
$category               = "Foxtrot Transaction Report.";
$total_sub_sheets       = 1;
$sub_sheet_title_array  = array(
    "General"
);
$default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
$excel_name             = 'General';
$sheet_data             = array();
$sheet_data             = array(0=>$rows);
$formPost               = $Excel->generate(array(
    'creator' => $creator,
    'last_modified_by' => $last_modified_by,
    'title' => $title,
    'subject' => $subject,
    'description' => $description,
    'keywords' => $keywords,
    'category' => $category,
    'total_sub_sheets' => $total_sub_sheets,
    'sub_sheet_title' => $sub_sheet_title_array,
    'default_open_sub_sheet' => $default_open_sub_sheet,
    'sheet_data' => $sheet_data,
    'excel_name' => $excel_name
));
$xlsData                = ob_get_contents();
ob_end_clean();


$response = array(
    'op' => 'ok',
    'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
);

die(json_encode($response));
