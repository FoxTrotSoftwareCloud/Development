<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new payroll();
    $instance_broker = new broker_master();
    $get_broker = $instance_broker->select();
    
    $payroll_date = '';
    $rep_number = '';
    $clearing_number = '';
    $rep_name = '';
    $gross_production = '';
    $check_amount = '';
    $net_production = '';
    $adjustments = '';
    $net_earnings = '';
        
    if(isset($_POST['submit'])&& $_POST['submit']=='Save'){ 
        
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $payroll_date = isset($_POST['payroll_date'])?$instance->re_db_input($_POST['payroll_date']):'';
        $rep_number = isset($_POST['rep_number'])?$instance->re_db_input($_POST['rep_number']):'';
        $clearing_number = isset($_POST['clearing'])?$instance->re_db_input($_POST['clearing']):'';
        $rep_name = isset($_POST['rep_name'])?$instance->re_db_input($_POST['rep_name']):'';
        $gross_production = isset($_POST['gross_production'])?$instance->re_db_input($_POST['gross_production']):'';
        $check_amount = isset($_POST['check_amount'])?$instance->re_db_input($_POST['check_amount']):'';
        $net_production = isset($_POST['net_production'])?$instance->re_db_input($_POST['net_production']):'';
        $adjustments = isset($_POST['adjustments'])?$instance->re_db_input($_POST['adjustments']):'';
        $net_earnings = isset($_POST['net_earnings'])?$instance->re_db_input($_POST['net_earnings']):'';
        
        $return = $instance->insert_update_prior_payrolls_master($_POST);
        
        if($return===true){
            
            header("location:".CURRENT_PAGE."?action=view");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
 
    }
    else if($action=='edit' && $id>0){
        $return = $instance->edit_prior_payrolls_master($id);
        
        $id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $payroll_date = isset($return['payroll_date'])?$instance->re_db_output($return['payroll_date']):'';
        $rep_number = isset($return['rep_number'])?$instance->re_db_output($return['rep_number']):'';
        $clearing_number = isset($return['clearing_number'])?$instance->re_db_output($return['clearing_number']):'';
        $rep_name = isset($return['rep_name'])?$instance->re_db_output($return['rep_name']):'';
        $gross_production = isset($return['gross_production'])?$instance->re_db_output($return['gross_production']):'';
        $check_amount = isset($return['check_amount'])?$instance->re_db_output($return['check_amount']):'';
        $net_production = isset($return['net_production'])?$instance->re_db_output($return['net_production']):'';
        $adjustments = isset($return['adjustments'])?$instance->re_db_output($return['adjustments']):'';
        $net_earnings = isset($return['net_earnings'])?$instance->re_db_output($return['net_earnings']):'';    
    }
    else if(isset($action) && $action=='delete' && $id>0)
    {
        $return = $instance->delete_prior_payrolls_master($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
    }
    else if($action=='view'){
        
        $return = $instance->select_prior_payrolls_master('');
    }
    
    
    $content = "maintain_prior_payrolls";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>