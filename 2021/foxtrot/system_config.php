<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $error = '';
    $company_name = '';
    $address1 = '';
    $address2 = '';
    $city = '';
    $state = '';
    $zip = '';
    $minimum_check_amount = '';
    $finra = 0;
    $sipc = 0;
    $brocker_pick_lists = '';
    $branch_pick_lists = '';
    $brocker_statement = '';
    $firm_does_not_participate = '';
    $logo = array();
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new system_master();
    $get_state = $instance->select_state();
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Save'){
        
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $user_id = isset($_SESSION['user_id'])?$instance->re_db_input($_SESSION['user_id']):0;
        $company_name= isset($_POST['company_name'])?$instance->re_db_input($_POST['company_name']):'';
        $address1= isset($_POST['address1'])?$instance->re_db_input($_POST['address1']):'';
        $address2= isset($_POST['address2'])?$instance->re_db_input($_POST['address2']):'';
        $city= isset($_POST['city'])?$instance->re_db_input($_POST['city']):'';
        $state= isset($_POST['state'])?$instance->re_db_input($_POST['state']):'';
        $zip= isset($_POST['zip'])?$instance->re_db_input($_POST['zip']):'';
        $minimum_check_amount= isset($_POST['minimum_check_amount'])?$instance->re_db_input($_POST['minimum_check_amount']):'';
        $finra= isset($_POST['finra'])?$instance->re_db_input($_POST['finra']):'';
        $sipc= isset($_POST['sipc'])?$instance->re_db_input($_POST['sipc']):'';
        $brocker_pick_lists= isset($_POST['brocker_pick_lists'])?$instance->re_db_input($_POST['brocker_pick_lists']):'';
        $branch_pick_lists= isset($_POST['branch_pick_lists'])?$instance->re_db_input($_POST['branch_pick_lists']):'';
        $brocker_statement = isset($_POST['brocker_statement'])?$instance->re_db_input($_POST['brocker_statement']):'';
        $firm_does_not_participate = isset($_POST['firm_does_not_participate'])?$instance->re_db_input($_POST['firm_does_not_participate']):'';
        $logo= isset($_FILES['logo']['name'])?$_FILES['logo']['name']:'';
        $return = $instance->insert_update($_POST);
        
        if($return===true){
            header("location:".CURRENT_PAGE);exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='view'){
        $return = $instance->edit();
        $id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $company_name = isset($return['company_name'])?$instance->re_db_output($return['company_name']):'';
        $address1 = isset($return['address1'])?$instance->re_db_output($return['address1']):'';
        $address2 = isset($return['address2'])?$instance->re_db_output($return['address2']):'';
        $city = isset($return['city'])?$instance->re_db_output($return['city']):'';
        $state = isset($return['state'])?$instance->re_db_output($return['state']):'';
        $zip = isset($return['zip'])?$instance->re_db_output($return['zip']):'';
        $minimum_check_amount = isset($return['minimum_check_amount'])?$instance->re_db_output($return['minimum_check_amount']):'';
        $finra = isset($return['finra'])?$instance->re_db_output($return['finra']):'';
        $sipc = isset($return['sipc'])?$instance->re_db_output($return['sipc']):'';
        $brocker_pick_lists = isset($return['brocker_pick_lists'])?$instance->re_db_output($return['brocker_pick_lists']):'';
        $branch_pick_lists = isset($return['branch_pick_lists'])?$instance->re_db_output($return['branch_pick_lists']):'';
        $brocker_statement = isset($return['brocker_statement'])?$instance->re_db_output($return['brocker_statement']):'';
        $firm_does_not_participate = isset($return['firm_does_not_participate'])?$instance->re_db_output($return['firm_does_not_participate']):'';
        $logo = isset($return['logo'])?$instance->re_db_output($return['logo']):'';
    }
    
    $content = "system_config";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>