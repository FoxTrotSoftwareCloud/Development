<?php

require_once("include/config.php");
require_once(DIR_FS."islogin.php");

if(isset($_GET['broker_id']) && $_GET['broker_id'] != '')
{   
    $id=$_GET['broker_id'];
    $instance = new broker_master();
    $return = $instance->edit($id);
    $broker_data = $instance->get_broker_changes($id);
    $edit_general = $instance->edit_general($id);
    $fname = isset($return['first_name'])?$instance->re_db_output($return['first_name']):'';
        $lname = isset($return['last_name'])?$instance->re_db_output($return['last_name']):'';
        $mname = isset($return['middle_name'])?$instance->re_db_output($return['middle_name']):'';
        $_SESSION['broker_full_name'] = $return['first_name'].' '.$return['middle_name'].' '.$return['last_name'];
        $suffix = isset($return['suffix'])?$instance->re_db_output($return['suffix']):'';
        $fund = isset($return['fund'])?$instance->re_db_output($return['fund']):'';
        $internal = isset($return['internal'])?$instance->re_db_output($return['internal']):'';
        $display_on_statement = isset($return['display_on_statement'])?$instance->re_db_output($return['display_on_statement']):'';
        $ssn = isset($return['ssn'])?$instance->re_db_output($return['ssn']):'';
        $tax_id = isset($return['tax_id'])?$instance->re_db_output($return['tax_id']):'';
        $crd = isset($return['crd'])?$instance->re_db_output($return['crd']):'';
        $active_status_cdd = isset($return['active_status'])?$instance->re_db_output($return['active_status']):'';
        $pay_method = $return['pay_method']== 1 ? "ACH": "Check"; 

        /*$branch_manager = isset($return['branch_manager'])?$instance->re_db_output($return['branch_manager']):'';
        $branch_name = isset($return['branch_name'])?$instance->re_db_output($return['branch_name']):'';
        $branch_office = isset($return['branch_office'])?$instance->re_db_output($return['branch_office']):'';*/
        
        $home = isset($edit_general['home'])?$instance->re_db_output($edit_general['home']):'';
        $business_type = '';
        if($home == 1){
              $address1=$home_address1_general;
               $address2= $home_address2_general;
               $business_type="Home";
        }
        else{
               $address1=$business_address1_general;
               $address2= $business_address2_general;
               $business_type="Business";
        }
        $home_address1_general = isset($edit_general['home_address1_general'])?$instance->re_db_output($edit_general['home_address1_general']):'';
        $home_address2_general = isset($edit_general['home_address2_general'])?$instance->re_db_output($edit_general['home_address2_general']):'';
        $business_address1_general = isset($edit_general['business_address1_general'])?$instance->re_db_output($edit_general['business_address1_general']):'';
        $business_address2_general = isset($edit_general['business_address2_general'])?$instance->re_db_output($edit_general['business_address2_general']):'';
        $city = isset($edit_general['city'])?$instance->re_db_output($edit_general['city']):'';
        $state_id = isset($edit_general['state_id'])?$instance->re_db_output($edit_general['state_id']):'';
        $zip_code = isset($edit_general['zip_code'])?$instance->re_db_output($edit_general['zip_code']):'';
        $telephone = isset($edit_general['telephone'])?$instance->re_db_output($edit_general['telephone']):'';
        $cell = isset($edit_general['cell'])?$instance->re_db_output($edit_general['cell']):'';
        $fax = isset($edit_general['fax'])?$instance->re_db_output($edit_general['fax']):'';
        $gender = isset($edit_general['gender'])?$instance->re_db_output($edit_general['gender']):'';
        $marital_status = isset($edit_general['marital_status'])?$instance->re_db_output($edit_general['marital_status']):'';
        $spouse = isset($edit_general['spouse'])?$instance->re_db_output($edit_general['spouse']):'';
        $children = isset($edit_general['children'])?$instance->re_db_output($edit_general['children']):'';
        $email1 = isset($edit_general['email1'])?$instance->re_db_output($edit_general['email1']):'';
        $email2 = isset($edit_general['email2'])?$instance->re_db_output($edit_general['email2']):'';
        $web_id = isset($edit_general['web_id'])?$instance->re_db_output($edit_general['web_id']):'';
        $web_password = isset($edit_general['web_password'])?$instance->re_db_output($edit_general['web_password']):'';
        $dob = isset($edit_general['dob']) ? date('m/d/Y',strtotime($instance->re_db_output($edit_general['dob']))):'';
        $prospect_date = isset($edit_general['prospect_date'])?date('m/d/Y',strtotime($instance->re_db_output($edit_general['prospect_date']))):'';
        $reassign_broker = isset($edit_general['reassign_broker'])?$instance->re_db_output($edit_general['reassign_broker']):'';
        if($reassign_broker > 0){
            $r_broker_data= $instance->select_broker_by_id($reassign_broker);
            $reassign_broker = $r_broker_data['last_name'].", ".$r_broker_data['first_name'];
        }
        
        print_r($r_broker_data);
        $u4 = isset($edit_general['u4'])?date('m/d/Y',strtotime($instance->re_db_output($edit_general['u4']))):'';
        $u5 = isset($edit_general['u5'])?date('m/d/Y',strtotime($instance->re_db_output($edit_general['u5']))):'';
        $day_after_u5 = isset($edit_general['day_after_u5'])?$instance->re_db_output($edit_general['day_after_u5']):'';
        $dba_name = isset($edit_general['dba_name'])?$instance->re_db_output($edit_general['dba_name']):'';
        $eft_information = $edit_general['eft_information'] == 1 ? "Pre-Notes" : "Direct Deposit";
        $start_date = isset($edit_general['start_date'])? date('m/d/Y',strtotime($edit_general['start_date'])):'';
        $transaction_type_general = $edit_general['transaction_type'] == 1 ? "Checking" : "Savings";
        $routing = isset($edit_general['routing'])?$instance->re_db_output($edit_general['routing']):'';
        $account_no = isset($edit_general['account_no'])?$instance->re_db_output($edit_general['account_no']):'';
        $professional_type=array();
        $professional_type[] = isset($edit_general['cfp'])?$instance->re_db_output($edit_general['cfp']):'';
        $professional_type[] = isset($edit_general['chfp'])?$instance->re_db_output($edit_general['chfp']):'';
        $professional_type[] = isset($edit_general['cpa'])?$instance->re_db_output($edit_general['cpa']):'';
        $professional_type[] = isset($edit_general['clu'])?$instance->re_db_output($edit_general['clu']):'';
        $professional_type[] = isset($edit_general['cfa'])?$instance->re_db_output($edit_general['cfa']):'';
        $professional_type[] = isset($edit_general['ria'])?$instance->re_db_output($edit_general['ria']):'';
        $professional_type[] = isset($edit_general['insurance'])?$instance->re_db_output($edit_general['insurance']):'';
        $professional_type=array_filter($professional_type);
         if($gender == 1){
                                                $gender_text="Male";
                                              }
                                              elseif($gender == 2){
                                                $gender_text="Male";
                                              }
                                              else if($gender == 3){
                                                $gender_text="Other";
                                              }
                                              else{
                                                 $gender_text="";
                                              }
        $rows=array();
        $columns=array("First Name"=>$fname,"Middle Name"=>$mname,"Last Name"=>$lname,"Suffix"=>$suffix,"Internal Broker ID Number"=>$internal,"Fund/Clearing Number"=>$fund,"Display On Statement"=>$display_on_statement,"SSN"=>$ssn,
            "Tax ID"=>$tax_id,"CRD Number"=>$crd,"Professional designations"=>$professional_type,"Home/Business"=>$business_type,"Address 1"=>$address1,"Address 2"=>$address2,"City"=>$city,"State"=>$state,"Zip code"=>$zip_code,"Telephone"=>$telephone,"Cell"=>$cell,"Fax"=>$fax,"Gender"=>$gender_text,"Status"=>$marital_status,"Spouse"=>$spouse,"Children"=>$children,"Primary Email"=>$email1,"Secondary Email"=>$email2,"Web ID"=>$web_id,"Web Password "=>$web_password,"DOB"=>$dob,"Prospect Date"=>$prospect_date,"U4"=>$u4,"U5/Termination Date"=>$u5,"Reassign Non-Trailer Business to Broker "=>$reassign_broker,"Days after U5/Termination "=>$day_after_u5,"DBA Name"=>$dba_name,"Pay Method"=>$pay_method,"EFT Information"=>$eft_information,"Start Date"=>$start_date,"Transaction Type "=>$transaction_type_general,"Bank Routing Number"=>$routing,"Account Number"=>$account_no
        );
         $Excel = new Excel();
         /*for($i="A";$i!=='AZ';$i++){
           $rows
         }*/
         $columnIndex="A";
        foreach ( $columns as $index=>$column_key) {
            $rows[$columnIndex."1"]=$index;
            $rows[$columnIndex."2"]=$column_key;  
            $columnIndex++;   
        }
         $creator                = "Foxtrot Broker";
        $last_modified_by       = "Foxtrot User";
        $title                  = "Foxtrot Broker Excel";
        $subject                = "Foxtrot Broker";
        $description            = "Foxtrot Broker Report. Generated on : ".date('Y-m-d h:i:s');
        $keywords               = "Foxtrot Transaction report office 2007";
        $category               = "Foxtrot Transaction Report.";
        $total_sub_sheets       = 1;
        $sub_sheet_title_array  = array("General");
        $default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
        $excel_name             = 'General';
        $sheet_data             = array();
        $sheet_data             = array(0=>$rows);
        $formPost = $Excel->generate(
            array(
                'creator'=>$creator,
                'last_modified_by'=>$last_modified_by,
                'title'=>$title,
                'subject'=>$subject,
                'description'=>$description,
                'keywords'=>$keywords,
                'category'=>$category,
                'total_sub_sheets'=>$total_sub_sheets,
                'sub_sheet_title'=>$sub_sheet_title_array,
                'default_open_sub_sheet'=>$default_open_sub_sheet,
                'sheet_data'=>$sheet_data,
                'excel_name'=>$excel_name
            )
        );
        

}    