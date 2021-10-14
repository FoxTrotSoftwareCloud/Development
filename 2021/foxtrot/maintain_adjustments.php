<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new payroll();
    $instance_payroll_master = new payroll_master();
    $get_payroll_category = $instance_payroll_master->select_payroll();
    $get_recurring_type = $instance->select_recurring_type();
    $get_pay_type = $instance->select_pay_type();
    $instance_broker = new broker_master();
    $get_broker = $instance_broker->select();
    
    $adjustment_amount = '';
    $date = '';
    $pay_date = '';
    $account = '';
    $expire_date = '';
    $payroll_category = '';
    $taxable_adjustment = 0;
    $broker = 0;
    $broker_number = '';
    $broker_name = '';
    $recurring = 0;
    $recurring_type = '';
    $description = '';
    $pay_type = '';
    $pay_amount = '';
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Save'){ 
        
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        
        $adjustment_amount = isset($_POST['adjustment_amount'])?$instance->re_db_input($_POST['adjustment_amount']):'';
        $date = isset($_POST['date'])?$instance->re_db_input($_POST['date']):'';
        $pay_date = isset($_POST['pay_date'])?$instance->re_db_input($_POST['pay_date']):'';
        $account = isset($_POST['account'])?$instance->re_db_input($_POST['account']):'';
        $expire_date = isset($_POST['expire_date'])?$instance->re_db_input($_POST['expire_date']):'';
        $payroll_category = isset($_POST['payroll_category'])?$instance->re_db_input($_POST['payroll_category']):'';
        $taxable_adjustment = isset($_POST['taxable_adjustment'])?$instance->re_db_input($_POST['taxable_adjustment']):0;
        $broker = isset($_POST['broker'])?$instance->re_db_input($_POST['broker']):0;
        $broker_number = isset($_POST['broker_number'])?$instance->re_db_input($_POST['broker_number']):'';
        $broker_name = isset($_POST['broker_name'])?$instance->re_db_input($_POST['broker_name']):'';
        $recurring = isset($_POST['recurring'])?$instance->re_db_input($_POST['recurring']):0;
        $recurring_type = isset($_POST['recurring_type'])?$instance->re_db_input($_POST['recurring_type']):'';
        $description = isset($_POST['description'])?$instance->re_db_input($_POST['description']):'';
        $pay_type = isset($_POST['pay_type'])?$instance->re_db_input($_POST['pay_type']):'';
        $pay_amount = isset($_POST['pay_amount'])?$instance->re_db_input($_POST['pay_amount']):'';
        //print_r($_POST);exit;
        $return = $instance->insert_update_adjustment_master($_POST);
        
        if($return===true){
            
            header("location:".CURRENT_PAGE."?action=view");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
 
    }
    else if($action=='edit' && $id>0){
        $return = $instance->edit_adjustments_master($id);
        
        $id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $adjustment_amount = isset($return['adjustment_amount'])?$instance->re_db_output($return['adjustment_amount']):'';
        $date = isset($return['date'])?$instance->re_db_output($return['date']):'';
        $pay_date = isset($return['pay_on'])?$instance->re_db_output($return['pay_on']):'';
        $account = isset($return['gl_account'])?$instance->re_db_output($return['gl_account']):'';
        $expire_date = isset($return['expire'])?$instance->re_db_output($return['expire']):'';
        $payroll_category = isset($return['category'])?$instance->re_db_output($return['category']):'';
        $taxable_adjustment = isset($return['taxable_adjustment'])?$instance->re_db_output($return['taxable_adjustment']):0;
        $broker = isset($return['broker'])?$instance->re_db_output($return['broker']):0;
        $broker_number = isset($return['broker_number'])?$instance->re_db_output($return['broker_number']):'';
        $broker_name = isset($return['broker_name'])?$instance->re_db_output($return['broker_name']):'';
        $recurring = isset($return['recurring'])?$instance->re_db_output($return['recurring']):0;
        $recurring_type = isset($return['recurring_type'])?$instance->re_db_output($return['recurring_type']):'';
        $description = isset($return['description'])?$instance->re_db_output($return['description']):'';
        $pay_type = isset($return['pay_type'])?$instance->re_db_output($return['pay_type']):'';
        $pay_amount = isset($return['pay_amount'])?$instance->re_db_output($return['pay_amount']):'';
        
    }
    else if(isset($action) && $action=='delete' && $id>0)
    {
        $return = $instance->delete_adjustments_master($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
    }
    else if(isset($_POST['delete_selected']) && $_POST['delete_selected'] == 'Delete Selected')
    {//echo '<pre>';print_r($_POST);exit;
        $return = $instance->delete_selected_adjustments_master($_POST);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
    }
    else if($action=='view'){
        
        $return = $instance->select_adjustments_master();
        
    }
    
    
    $content = "maintain_adjustments";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>