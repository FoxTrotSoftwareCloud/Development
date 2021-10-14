<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new payroll();
    $instance_broker = new broker_master();
    $get_broker = $instance_broker->select();
    
    $broker_number = '';
    $broker_name = '';
    $clearing_number = '';
    $balance_amount = '';
       
    if(isset($_POST['submit'])&& $_POST['submit']=='Save'){ 
        
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $broker_number = isset($_POST['broker_number'])?$instance->re_db_input($_POST['broker_number']):0;
        $broker_name = isset($_POST['broker_name'])?$instance->re_db_input($_POST['broker_name']):'';
        $clearing_number = isset($_POST['clearing_number'])?$instance->re_db_input($_POST['clearing_number']):'';
        $balance_amount = isset($_POST['balance_amount'])?$instance->re_db_input($_POST['balance_amount']):'';
        
        $return = $instance->insert_update_balances_master($_POST);
        
        if($return===true){
            
            header("location:".CURRENT_PAGE."?action=view");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
 
    }
    else if($action=='edit' && $id>0){
        
        $return = $instance->edit_balances_master($id);
        $id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $broker_number = isset($return['broker_number'])?$instance->re_db_output($return['broker_number']):0;
        $broker_name = isset($return['broker_name'])?$instance->re_db_output($return['broker_name']):'';
        $clearing_number = isset($return['clearing_number'])?$instance->re_db_output($return['clearing_number']):'';
        $balance_amount = isset($return['balance_amount'])?$instance->re_db_output($return['balance_amount']):'';    
    }
    else if(isset($action) && $action=='delete' && $id>0)
    {
        $return = $instance->delete_balances_master($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
    }
    else if($action=='view'){
        
        $return = $instance->select_balances_master();
        
    }
    
    $content = "maintain_broker_balances";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>