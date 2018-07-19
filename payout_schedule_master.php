<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
     
    $error = '';
    $schedule_name = '';
    $transaction_type_general = 0;
    $product_category = '';
    $basis = '';
    $cumulative = 0;
    $year = '';
    $calculation_detail = '';
    $clearing_charge_deducted_from = '';
    $reset = '';
    $description_type = '';
    $minimum_trade_gross = '';
    $minimum_12B1_gross = '';
    $is_default = 0;
    $team_member = array();
    $edit_grid = array();
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new payout_schedule_master();
    $get_product_category = $instance->select_category();
    $select_broker= $instance->select_broker();
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Save'){//echo'<pre>';print_r($_POST);exit;
        
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
		$schedule_name = isset($_POST['schedule_name'])?$instance->re_db_input($_POST['schedule_name']):'';
        $transaction_type_general = isset($_POST['transaction_type_general'])?$instance->re_db_input($_POST['transaction_type_general']):0;
        $product_category = isset($_POST['product_category'])?$instance->re_db_input($_POST['product_category']):'';
        $basis = isset($_POST['basis'])?$instance->re_db_input($_POST['basis']):'';
        $cumulative = isset($_POST['cumulative'])?$instance->re_db_input($_POST['cumulative']):0;
        $year = isset($_POST['year'])?$instance->re_db_input($_POST['year']):'';
        $calculation_detail = isset($_POST['calculation_detail'])?$instance->re_db_input($_POST['calculation_detail']):'';
        $clearing_charge_deducted_from = isset($_POST['clearing_charge_deducted_from'])?$instance->re_db_input($_POST['clearing_charge_deducted_from']):'';
        $reset = isset($_POST['reset'])?$instance->re_db_input(date('Y-m-d',strtotime($_POST['reset']))):'';
        $description_type = isset($_POST['description_type'])?$instance->re_db_input($_POST['description_type']):'';
        $minimum_trade_gross = isset($_POST['minimum_trade_gross'])?$instance->re_db_input($_POST['minimum_trade_gross']):'';
        $minimum_12B1_gross = isset($_POST['minimum_12B1_gross'])?$instance->re_db_input($_POST['minimum_12B1_gross']):'';
        $team_member = isset($_POST['team_member'])?$_POST['team_member']:array();
        $edit_grid = isset($_POST['leval'])?$instance->reArrayFiles_grid($_POST['leval']):array();
        $is_default = isset($_POST['is_default'])?$instance->re_db_input($_POST['is_default']):0;
        //echo '<pre>';print_r($team_member);exit;
        
        $return = $instance->insert_update($_POST);
        $return1 = $instance->insert_update_payout_schedule_grid($instance->reArrayFiles_grid($_POST['leval']),$_POST['id']);
        
        if($return===true){
            header("location:".CURRENT_PAGE);exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit' && $id>0){
        
        $edit_payout = $instance->edit_payout($id);
        $edit_grid = $instance->edit_grid($id);
        $schedule_name = isset($edit_payout['payout_schedule_name'])?$instance->re_db_output($edit_payout['payout_schedule_name']):'';
        $transaction_type_general = isset($edit_payout['transaction_type_general'])?$instance->re_db_output($edit_payout['transaction_type_general']):'';
        $product_category = isset($edit_payout['product_category'])?$instance->re_db_output($edit_payout['product_category']):'';
        $basis = isset($edit_payout['basis'])?$instance->re_db_output($edit_payout['basis']):'';
        $cumulative = isset($edit_payout['cumulative'])?$instance->re_db_output($edit_payout['cumulative']):'';
        $year = isset($edit_payout['year'])?$instance->re_db_output($edit_payout['year']):'';
        $calculation_detail = isset($edit_payout['calculation_detail'])?$instance->re_db_output($edit_payout['calculation_detail']):'';
        $clearing_charge_deducted_from = isset($edit_payout['clearing_charge_deducted_from'])?$instance->re_db_output($edit_payout['clearing_charge_deducted_from']):'';
        $reset = isset($edit_payout['reset'])?$instance->re_db_output(date('Y-m-d',strtotime($edit_payout['reset']))):'';
        $description_type = isset($edit_payout['description_type'])?$instance->re_db_output($edit_payout['description_type']):'';
        $minimum_trade_gross = isset($edit_payout['minimum_trade_gross'])?$instance->re_db_output($edit_payout['minimum_trade_gross']):'';
        $minimum_12B1_gross = isset($edit_payout['minimum_12B1_gross'])?$instance->re_db_output($edit_payout['minimum_12B1_gross']):'';
        $team_member = isset($edit_payout['team_member'])?explode(',',$edit_payout['team_member']):array();
        $is_default = isset($edit_payout['is_default'])?$instance->re_db_output($edit_payout['is_default']):0;
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
    else if($action=='view'){
        
        $return = $instance->select_payout_schedule();
        
    }
    
    $content = "payout_schedule_master";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>