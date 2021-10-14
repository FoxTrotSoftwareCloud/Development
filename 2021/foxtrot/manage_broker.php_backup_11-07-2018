<?php
	require_once("include/config.php");
	require_once(DIR_FS."islogin.php");
	
    $error = '';
    $return = array();
    $fname = '';
    $lname = '';
    $mname = '';
    $suffix = '';
    $fund = '';
    $internal = '';
    $display_on_statement = '';
    $ssn = '';
    $tax_id = '';
    $crd = '';
    $active_status_cdd = '';
    $pay_method = '';
    $branch_manager = '';
    $branch_name = '';
    $branch_office = '';
    $search_text = '';
    
    $active_status_cdd1 = '';
    $telephone = '';
    $cell = '';
    $fax = '';
    $gender = '';
    $status = '';
    $spouse = '';
    $children = '';
    $email1 = '';
    $email2 = '';
    $web_id = '';
    $web_password = '';
    $dob = '';
    $prospect_date = '';
    $reassign_broker = '';
    $u4 = '';
    $u5 = '';
    $dba_name = '';
    $eft_info = '';
    $start_date = '';
    $transaction_type_general = '';
    $routing = '';
    $account_no = '';
    $summarize_trailers = '';
    $summarize_direct_imported_trades = '';
    $from_date = '';
    $to_date = '';
    $sponsor_company = '';
    $day_after_u5 = 0;
    
    $branch_broker = '';
	$branch_1 = '';
	$branch_office_1 = '';
    $branch_2 = '';
	$branch_office_2 = '';
    $branch_3 = '';
	$branch_office_3 = '';
    $assess_branch_office_fee = 0;
	$assess_audit_fee = 0;
	$stamp = 0;
	$stamp_certification = 0;
	$stamp_indemnification = 0;
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new broker_master();
    $instance_branch =  new branch_maintenance();
    $get_state  = $instance->select_state();
    $get_state_new = $instance->select_state_new();
    $get_register=$instance->select_register();
    $get_sponsor = $instance->select_sponsor();
    $select_broker= $instance->select();
    $select_branch= $instance_branch->select();
    $select_percentage= $instance->select_percentage();
    $broker_charge=$instance->select_broker_charge($id);
    $charge_type_arr=$instance->select_charge_type();
    $get_broker = $instance->select();
    $select_docs = $instance->select_docs();
    $select_broker_docs = $instance->get_broker_doc_name();
    $product_category = $instance->select_category();
    $get_payout_schedule = $instance->get_payout_schedule();
    $get_branch_office = $instance->select_branch_office();
    //echo '<pre>';print_r($product_category);exit();
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Save'){//echo '<pre>';print_r($_POST);exit;
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $fname = isset($_POST['fname'])?$instance->re_db_input($_POST['fname']):'';
    	$lname = isset($_POST['lname'])?$instance->re_db_input($_POST['lname']):'';
    	$mname = isset($_POST['mname'])?$instance->re_db_input($_POST['mname']):'';
    	$suffix = isset($_POST['suffix'])?$instance->re_db_input($_POST['suffix']):'';
    	$fund = isset($_POST['fund'])?$instance->re_db_input($_POST['fund']):'';
    	$internal = isset($_POST['internal'])?$instance->re_db_input($_POST['internal']):'';
        $display_on_statement = isset($_POST['display_on_statement'])?$instance->re_db_input($_POST['display_on_statement']):'';
    	$ssn = isset($_POST['ssn'])?$instance->re_db_input($_POST['ssn']):'';
    	$tax_id = isset($_POST['tax_id'])?$instance->re_db_input($_POST['tax_id']):'';
    	$crd = isset($_POST['crd'])?$instance->re_db_input($_POST['crd']):'';
        $active_status_cdd = isset($_POST['active_status_cdd'])?$instance->re_db_input($_POST['active_status_cdd']):'';
    	$pay_method = isset($_POST['pay_method'])?$instance->re_db_input($_POST['pay_method']):'';
    	$branch_manager = isset($_POST['branch_manager'])?$instance->re_db_input($_POST['branch_manager']):'';
        $branch_name = isset($_POST['branch_name'])?$instance->re_db_input($_POST['branch_name']):'';
        $branch_office = isset($_POST['branch_office'])?$instance->re_db_input($_POST['branch_office']):'';
        $for_import = isset($_POST['for_import'])?$instance->re_db_input($_POST['for_import']):'false';
        $file_id = isset($_POST['file_id'])?$instance->re_db_input($_POST['file_id']):0;
        //echo '<pre>';print_r($_POST);exit;
        
        $home_general = isset($_POST['home_general'])?$instance->re_db_input($_POST['home_general']):'';
        $home_address1_general = isset($_POST['home_address1_general'])?$instance->re_db_input($_POST['home_address1_general']):'';
		$home_address2_general = isset($_POST['home_address2_general'])?$instance->re_db_input($_POST['home_address2_general']):'';
		$business_address1_general = isset($_POST['business_address1_general'])?$instance->re_db_input($_POST['business_address1_general']):'';
		$business_address2_general = isset($_POST['business_address2_general'])?$instance->re_db_input($_POST['business_address2_general']):'';
		$city_general = isset($_POST['city_general'])?$instance->re_db_input($_POST['city_general']):'';
		$state_general = isset($_POST['state_general'])?$instance->re_db_input($_POST['state_general']):'';
		$zip_code_general = isset($_POST['zip_code_general'])?$instance->re_db_input($_POST['zip_code_general']):'';
        $telephone_general = isset($_POST['telephone_general'])?$instance->re_db_input($_POST['telephone_general']):'';
        $cell_general = isset($_POST['cell_general'])?$instance->re_db_input($_POST['cell_general']):'';
		$fax_general = isset($_POST['fax_general'])?$instance->re_db_input($_POST['fax_general']):'';
        $gender_general = isset($_POST['gender_general'])?$instance->re_db_input($_POST['gender_general']):'';
		$status_general = isset($_POST['status_general'])?$instance->re_db_input($_POST['status_general']):'';
		$spouse_general = isset($_POST['spouse_general'])?$instance->re_db_input($_POST['spouse_general']):'';
        $children_general = isset($_POST['children_general'])?$instance->re_db_input($_POST['children_general']):'';
		$email1_general = isset($_POST['email1_general'])?$instance->re_db_input($_POST['email1_general']):'';
		$email2_general = isset($_POST['email2_general'])?$instance->re_db_input($_POST['email2_general']):'';
		$web_id_general = isset($_POST['web_id_general'])?$instance->re_db_input($_POST['web_id_general']):'';
		$web_password_general = isset($_POST['web_password_general'])?$instance->re_db_input($_POST['web_password_general']):'';
		$dob_general = isset($_POST['dob_general'])?$instance->re_db_input($_POST['dob_general']):'';
		$prospect_date_general = isset($_POST['prospect_date_general'])?$instance->re_db_input($_POST['prospect_date_general']):'';
		$reassign_broker_general = isset($_POST['reassign_broker_general'])?$instance->re_db_input($_POST['reassign_broker_general']):'';
		$u4_general = isset($_POST['u4_general'])?$instance->re_db_input($_POST['u4_general']):'';
        $u5_general = isset($_POST['u5_general'])?$instance->re_db_input($_POST['u5_general']):'';
        $day_after_u5 = isset($_POST['day_after_u5'])?$instance->re_db_input($_POST['day_after_u5']):'';
		$dba_name_general = isset($_POST['dba_name_general'])?$instance->re_db_input($_POST['dba_name_general']):'';
		$eft_info_general = isset($_POST['eft_info_general'])?$instance->re_db_input($_POST['eft_info_general']):'';
        $start_date_general = isset($_POST['start_date_general'])?$instance->re_db_input($_POST['start_date_general']):'';
		$transaction_type_general = isset($_POST['transaction_type_general1'])?$instance->re_db_input($_POST['transaction_type_general1']):'';
		$routing_general = isset($_POST['routing_general'])?$instance->re_db_input($_POST['routing_general']):'';
		$account_no_general = isset($_POST['account_no_general'])?$instance->re_db_input($_POST['account_no_general']):'';
		$summarize_trailers_general = isset($_POST['summarize_trailers_general'])?$instance->re_db_input($_POST['summarize_trailers_general']):0;
		$summarize_direct_imported_trades = isset($_POST['summarize_direct_imported_trades'])?$instance->re_db_input($_POST['summarize_direct_imported_trades']):0;
		$from_date_general = isset($_POST['from_date_general'])?$instance->re_db_input($_POST['from_date_general']):'';
		$to_date_general = isset($_POST['to_date_general'])?$instance->re_db_input($_POST['to_date_general']):'';
		$cfp_general = isset($_POST['cfp_general'])?$instance->re_db_input($_POST['cfp_general']):0;
        $chfp_general = isset($_POST['chfp_general'])?$instance->re_db_input($_POST['chfp_general']):0;
		$cpa_general = isset($_POST['cpa_general'])?$instance->re_db_input($_POST['cpa_general']):0;
		$clu_general = isset($_POST['clu_general'])?$instance->re_db_input($_POST['clu_general']):0;
        $cfa_general = isset($_POST['cfa_general'])?$instance->re_db_input($_POST['cfa_general']):0;
        $ria_general = isset($_POST['ria_general'])?$instance->re_db_input($_POST['ria_general']):0;
		$insurance_general = isset($_POST['insurance_general'])?$instance->re_db_input($_POST['insurance_general']):0;//echo '<pre>';print_r($_POST);exit;
    
        $branch_broker = isset($_POST['branch_broker'])?$instance->re_db_input($_POST['branch_broker']):'';
		$branch_1 = isset($_POST['branch_1'])?$instance->re_db_input($_POST['branch_1']):'';
		$branch_office_1 = isset($_POST['branch_office_1'])?$instance->re_db_input($_POST['branch_office_1']):'';
        $branch_2 = isset($_POST['branch_2'])?$instance->re_db_input($_POST['branch_2']):'';
		$branch_office_2 = isset($_POST['branch_office_2'])?$instance->re_db_input($_POST['branch_office_2']):'';
        $branch_3 = isset($_POST['branch_3'])?$instance->re_db_input($_POST['branch_3']):'';
		$branch_office_3 = isset($_POST['branch_office_3'])?$instance->re_db_input($_POST['branch_office_3']):'';
        $assess_branch_office_fee = isset($_POST['assess_branch_office_fee'])?$instance->re_db_input($_POST['assess_branch_office_fee']):0;
		$assess_audit_fee = isset($_POST['assess_audit_fee'])?$instance->re_db_input($_POST['assess_audit_fee']):0;
		$stamp = isset($_POST['stamp'])?$instance->re_db_input($_POST['stamp']):0;
		$stamp_certification = isset($_POST['stamp_certification'])?$instance->re_db_input($_POST['stamp_certification']):0;
		$stamp_indemnification = isset($_POST['stamp_indemnification'])?$instance->re_db_input($_POST['stamp_indemnification']):0;
            
        $return = $instance->insert_update($_POST);
        
        $return1 = $instance->insert_update_general($_POST);
        
        //payout tab
        $return2 = $instance->insert_update_payout($_POST);
        $return_fixed_rates = $instance->insert_update_payout_fixed_rates($_POST,$_POST['id']); 
        $return3 = $instance->insert_update_payout_grid($instance->reArrayFiles_grid($_POST['leval']),$_POST['id']);
        $return4 = $instance->insert_update_payout_override($instance->reArrayFiles_override($_POST['override']),$_POST['id'],$_POST['override']['receiving_rep1']);
        $return5 = $instance->insert_update_payout_split($instance->reArrayFiles_split($_POST['split']),$_POST['id']);
        //echo '<pre>';print_r($return5);exit;
        //security tab
        $return6 = $instance->insert_update_licences($_POST);
        
        //insaurance tab
        $return7 = $instance->insert_update_licences1($_POST);
        
        //ria tab
        $return8 = $instance->insert_update_licences2($_POST);
        
        //register tab
        $return9 = $instance->insert_update_register($_POST); 
        
        //requered documents tab
        $return10 = $instance->insert_update_req_doc($instance->reArrayFiles($_POST['data']),$id);
        
        //alias & appoinments
        $return12 = $instance->insert_update_alias($instance->reArrayFiles_alias($_POST['alias']),$id);
        //charges tab
        $return11 = $instance->insert_update_charges($_POST);
        
        //for broker branches
        $return_broker_branches = $instance->insert_update_branches($_POST);
        
        
        if($return===true){
            
            if($for_import == 'true')
            {
                if(isset($file_id) && $file_id >0 )
                {
                    header("location:".SITE_URL."import.php?tab=review_files&id=".$file_id);exit;
                }
                else
                {
                    header("location:".SITE_URL."import.php");exit;
                }
            }
            else if($action == 'edit')
            {
                header("location:".CURRENT_PAGE);exit;
            }
            else
            {
                header("location:".CURRENT_PAGE);exit;
            }
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    /*else if(isset($_POST['payout'])&& $_POST['payout']=='Save'){
        //echo '<pre>';print_r($_POST['leval']);exit;
        $return2 = $instance->insert_update_payout($_POST);   
        $return1 = $instance->insert_update_payout_grid($instance->reArrayFiles_grid($_POST['leval']),$_POST['id']);
        $return2 = $instance->insert_update_payout_override($instance->reArrayFiles_override($_POST['override']),$_POST['id'],$_POST['receiving_rep']);
        $return3 = $instance->insert_update_payout_split($instance->reArrayFiles_split($_POST['split']),$_POST['id']);
        if($return===true && $return===true && $return===true && $return===true){
            if($action == 'edit')
            {
                header("location:".CURRENT_PAGE."?action=".$action."&id=".$id."&tab=charges");exit;
            }
            else
            {
                header("location:".CURRENT_PAGE."?action=".$action."&tab=charges");exit;
            }
        }
        else{            
            $error = !isset($_SESSION['warning'])?$return:'';            
        }
    }
     else if(isset($_POST['securities'])&& $_POST['securities']=='Save'){
       //echo '<pre>';print_r($_POST);exit;
        $return = $instance->insert_update_licences($_POST);   
        if($return===true){
            if($action == 'edit')
            {
                header("location:".CURRENT_PAGE."?action=".$action."&id=".$id."&tab=licences&sub_tab=insurance");exit;
            }
            else
            {
                header("location:".CURRENT_PAGE."?action=".$action."&tab=licences&sub_tab=insurance");exit;
            }
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if(isset($_POST['insurance'])&& $_POST['insurance']=='Save'){
        $return = $instance->insert_update_licences1($_POST);   
        if($return===true){
            if($action == 'edit')
            {
                header("location:".CURRENT_PAGE."?action=".$action."&id=".$id."&tab=licences&sub_tab=ria");exit;
            }
            else
            {
                header("location:".CURRENT_PAGE."?action=".$action."&tab=licences&sub_tab=ria");exit;
            }
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if(isset($_POST['ria'])&& $_POST['ria']=='Save'){
        $return = $instance->insert_update_licences2($_POST);   
        if($return===true){
            if($action == 'edit')
            {
                header("location:".CURRENT_PAGE."?action=".$action."&id=".$id."&tab=registers");exit;
            }
            else
            {
                header("location:".CURRENT_PAGE."?action=".$action."&tab=registers");exit;
            }
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if(isset($_POST['register'])&& $_POST['register']=='Save'){
        
            //echo '<pre>';print_r($_POST);exit;
            $return = $instance->insert_update_register($_POST); 
            if($return===true){
            if($action == 'edit')
            {
                header("location:".CURRENT_PAGE."?action=".$action."&id=".$id."&tab=required_docs");exit;
            }
            else
            {
                header("location:".CURRENT_PAGE."?action=".$action."&tab=required_docs");exit;
            }
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
     else if(isset($_POST['req_doc'])&& $_POST['req_doc']=='Save'){
             //echo '<pre>';print_r($_POST);exit;
             $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
             
            $return = $instance->insert_update_req_doc($instance->reArrayFiles($_POST['data']),$id); 
            if($return===true){
            if($action == 'edit')
            {
                header("location:".CURRENT_PAGE."?action=".$action."&id=".$id."&tab=required_docs");exit;
            }
            else
            {
                header("location:".CURRENT_PAGE."?action=".$action."&tab=required_docs");exit;
            }
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        } 
    }
    else if(isset($_POST['charges'])&& $_POST['charges']=='Save'){
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $return = $instance->insert_update_charges($_POST);
        
        if($return===true){
            if($action == 'edit')
            {
                header("location:".CURRENT_PAGE."?action=".$action."&id=".$id."&tab=licences");exit;
            }
            else
            {
                header("location:".CURRENT_PAGE."?action=".$action."&tab=licences");exit;
            }
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }*/
    else if(isset($_POST['submit_new_payout'])&& $_POST['submit_new_payout']=='Save'){//echo '<pre>';print_r($_POST);exit;
        $return = $instance->insert_update_payout_schedule($_POST);
        $return1 = $instance->insert_update_payout_grid($instance->reArrayFiles_grid($_POST['leval']),$_POST['id']);
        
        if($return===true){
            echo '1';exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
        echo $error;
        exit;
    }
    else if($action=='edit' && $id>0){
        $return = $instance->edit($id);
        $broker_data = $instance->get_broker_changes($id);
        $edit_general = $instance->edit_general($id);//print_r($edit_general);exit;
        $edit_licences_securities = $instance->edit_licences_securities($id);
        $edit_licences_ria = $instance->edit_licences_ria($id);
        $edit_licences_insurance = $instance->edit_licences_insurance($id);
        $edit_registers = $instance->edit_registers($id);
        $edit_required_docs = $instance->edit_required_docs($id);
        $edit_payout = $instance->edit_payout($id);
        $edit_payout_fixed_rates = $instance->edit_payout_fixed_rates($id);
        $edit_grid = $instance->edit_grid($id);
        $edit_override = $instance->edit_override($id);
        $edit_split = $instance->edit_split($id);
        $edit_charge_check =$instance->edit_charge_check($id);
        $edit_alias = $instance->edit_alias($id);
        $edit_branches = $instance->edit_branches($id);
        //echo '<pre>';print_r($edit_charge_check);exit;//echo '<pre>';print_r($edit_override);exit;
        
        $_SESSION['last_insert_id']=$id;
        $fname = isset($return['first_name'])?$instance->re_db_output($return['first_name']):'';
        $lname = isset($return['last_name'])?$instance->re_db_output($return['last_name']):'';
        $mname = isset($return['middle_name'])?$instance->re_db_output($return['middle_name']):'';
        $suffix = isset($return['suffix'])?$instance->re_db_output($return['suffix']):'';
        $fund = isset($return['fund'])?$instance->re_db_output($return['fund']):'';
    	$internal = isset($return['internal'])?$instance->re_db_output($return['internal']):'';
        $display_on_statement = isset($return['display_on_statement'])?$instance->re_db_output($return['display_on_statement']):'';
        $ssn = isset($return['ssn'])?$instance->re_db_output($return['ssn']):'';
        $tax_id = isset($return['tax_id'])?$instance->re_db_output($return['tax_id']):'';
        $crd = isset($return['crd'])?$instance->re_db_output($return['crd']):'';
        $active_status_cdd = isset($return['active_status'])?$instance->re_db_output($return['active_status']):'';
        $pay_method = isset($return['pay_method'])?$instance->re_db_output($return['pay_method']):'';
    	/*$branch_manager = isset($return['branch_manager'])?$instance->re_db_output($return['branch_manager']):'';
        $branch_name = isset($return['branch_name'])?$instance->re_db_output($return['branch_name']):'';
        $branch_office = isset($return['branch_office'])?$instance->re_db_output($return['branch_office']):'';*/
    	
    	$home = isset($edit_general['home'])?$instance->re_db_output($edit_general['home']):'';
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
        $dob = isset($edit_general['dob'])?$instance->re_db_output($edit_general['dob']):'';
        $prospect_date = isset($edit_general['prospect_date'])?$instance->re_db_output($edit_general['prospect_date']):'';
        $reassign_broker = isset($edit_general['reassign_broker'])?$instance->re_db_output($edit_general['reassign_broker']):'';
        $u4 = isset($edit_general['u4'])?$instance->re_db_output($edit_general['u4']):'';
        $u5 = isset($edit_general['u5'])?$instance->re_db_output($edit_general['u5']):'';
        $day_after_u5 = isset($edit_general['day_after_u5'])?$instance->re_db_output($edit_general['day_after_u5']):'';
        $dba_name = isset($edit_general['dba_name'])?$instance->re_db_output($edit_general['dba_name']):'';
        $eft_information = isset($edit_general['eft_information'])?$instance->re_db_output($edit_general['eft_information']):'';
        $start_date = isset($edit_general['start_date'])?$instance->re_db_output($edit_general['start_date']):'';
        $transaction_type_general = isset($edit_general['transaction_type'])?$instance->re_db_output($edit_general['transaction_type']):'';
        $routing = isset($edit_general['routing'])?$instance->re_db_output($edit_general['routing']):'';
        $account_no = isset($edit_general['account_no'])?$instance->re_db_output($edit_general['account_no']):'';
        $cfp = isset($edit_general['cfp'])?$instance->re_db_output($edit_general['cfp']):'';
        $chfp = isset($edit_general['chfp'])?$instance->re_db_output($edit_general['chfp']):'';
        $cpa = isset($edit_general['cpa'])?$instance->re_db_output($edit_general['cpa']):'';
        $clu = isset($edit_general['clu'])?$instance->re_db_output($edit_general['clu']):'';
        $cfa = isset($edit_general['cfa'])?$instance->re_db_output($edit_general['cfa']):'';
        $ria = isset($edit_general['ria'])?$instance->re_db_output($edit_general['ria']):'';
        $insurance = isset($edit_general['insurance'])?$instance->re_db_output($edit_general['insurance']):'';
        //echo '<pre>';print_r($edit_licences_securities);exit;
        $branch_broker = isset($edit_branches['broker_name'])?$instance->re_db_output($edit_branches['broker_name']):'';
		$branch_1 = isset($edit_branches['branch1'])?$instance->re_db_output($edit_branches['branch1']):'';
		$branch_office_1 = isset($edit_branches['branch_office1'])?$instance->re_db_output($edit_branches['branch_office1']):'';
        $branch_2 = isset($edit_branches['branch2'])?$instance->re_db_output($edit_branches['branch2']):'';
		$branch_office_2 = isset($edit_branches['branch_office2'])?$instance->re_db_output($edit_branches['branch_office2']):'';
        $branch_3 = isset($edit_branches['branch3'])?$instance->re_db_output($edit_branches['branch3']):'';
		$branch_office_3 = isset($edit_branches['branch_office3'])?$instance->re_db_output($edit_branches['branch_office3']):'';
        $assess_branch_office_fee = isset($edit_branches['assess_branch_office_fee'])?$instance->re_db_output($edit_branches['assess_branch_office_fee']):0;
		$assess_audit_fee = isset($edit_branches['assess_audit_fee'])?$instance->re_db_output($edit_branches['assess_audit_fee']):0;
		$stamp = isset($edit_branches['stamp'])?$instance->re_db_output($edit_branches['stamp']):0;
		$stamp_certification = isset($edit_branches['stamp_certification'])?$instance->re_db_output($edit_branches['stamp_certification']):0;
		$stamp_indemnification = isset($edit_branches['stamp_indemnification'])?$instance->re_db_output($edit_branches['stamp_indemnification']):0;
           
    }
    else if(isset($_GET['send'])&&$_GET['send']=='previous' && isset($_GET['id'])&&$_GET['id']>0 && $_GET['id']!='')
    {
        $id = $instance->re_db_input($_GET['id']);
        
        $return = $instance->get_previous_broker($id);
            
        if($return!=false){
            $id=$return['id'];
            header("location:".CURRENT_PAGE."?action=edit&id=".$id."");exit;
        }
        else{
            header("location:".CURRENT_PAGE."?action=edit&id=".$id."");exit;
        }
        
    }
    else if(isset($_GET['send'])&&$_GET['send']=='next' && isset($_GET['id'])&&$_GET['id']>0 && $_GET['id']!='')
    {
        $id = $instance->re_db_input($_GET['id']);
        
        $return = $instance->get_next_broker($id);
            
        if($return!=false){
            $id=$return['id'];
            header("location:".CURRENT_PAGE."?action=edit&id=".$id."");exit;
        }
        else{
            header("location:".CURRENT_PAGE."?action=edit&id=".$id."");exit;
        }
        
    }
    else if(isset($_POST['submit'])&&$_POST['submit']=='Search'){
        
       $search_text = isset($_POST['search_text'])?$instance->re_db_input($_POST['search_text']):''; 
       $return = $instance->search($search_text);
    }
    else if(isset($_GET['action'])&&$_GET['action']=='status'&&isset($_GET['id'])&&$_GET['id']>0&&isset($_GET['status'])&&($_GET['status']==0 || $_GET['status']==1))
    {
        $id = $instance->re_db_input($_GET['id']);
        $status = $instance->re_db_input($_GET['status']);
        $return = $instance->status($id,$status);
        if($return==true){
            header('location:'.CURRENT_PAGE);exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }  
    else if(isset($_GET['action'])&&$_GET['action']=='delete'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete($id);
        if($return==true){
            header('location:'.CURRENT_PAGE);exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if(isset($_POST['add_notes'])&& $_POST['add_notes']=='Add Notes'){
        $_POST['user_id']=$_SESSION['user_name'];
        $_POST['date'] = date('Y-m-d');
       
        $return = $instance->insert_update_broker_notes($_POST);
        
        if($return===true){
            echo '1';exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
        echo $error;
        exit;
    } 
    else if(isset($_POST['add_attach'])&& $_POST['add_attach']=='Add Attach'){//print_r($_FILES);exit;
        $_POST['user_id']=$_SESSION['user_name'];
        $_POST['date'] = date('Y-m-d');
        $file = isset($_FILES['add_attach'])?$_FILES['add_attach']:array();
        
        $return = $instance->insert_update_broker_attach($_POST);
        
        if($return===true){
            echo '1';exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
        echo $error;
        exit;
    }
    else if(isset($_GET['delete_action'])&&$_GET['delete_action']=='delete_attach'&&isset($_GET['attach_id'])&&$_GET['attach_id']>0)
    {
        $attach_id = $instance->re_db_input($_GET['attach_id']);
        $return = $instance->delete_attach($attach_id);
        if($return===true){
            echo '1';exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
        echo $error;
        exit;
    }
    else if(isset($_GET['delete_action'])&&$_GET['delete_action']=='delete_notes'&&isset($_GET['note_id'])&&$_GET['note_id']>0)
    {
        $note_id = $instance->re_db_input($_GET['note_id']);
        $return = $instance->delete_notes($note_id);
        if($return===true){
            echo '1';exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
        echo $error;
        exit;
    } 
    else if($action=='view'){
        
        $_SESSION['last_insert_id']='';
        $return = $instance->select();
        
    }    
	$content = "manage_broker";
	require_once(DIR_WS_TEMPLATES."main_page.tpl.php");
?>