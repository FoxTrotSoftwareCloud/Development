<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new payroll();
    $instance_broker = new broker_master();
    $get_broker = $instance_broker->select();
    $instance_category = new product_master();
    $get_category = $instance_category->select_product_type();
    $instance_client = new client_maintenance();
    $get_client = $instance_client->select();
    $instance_branch =  new branch_maintenance();
    $get_branch= $instance_branch->select();
    
    $payroll_id='';
    $trade_number = '';
    $trade_date = '';
    $investment = '';
    $investment_cusip = '';
    $client_account_number = '';
    $client_name = '';
    $broker_name = '';
    $broker_amount = '';
    $quantity = '';
    $price = '';
    $investment_amount = '';
    $commission_expired = '';
    $charge = '';
    $date_received = '';
    $commission_received = '';
    $clearing_charge = '';
    $product_category = '';
    $product = '';
    
    $buy_sell = '';
    $hold = '';
    $hold_reason = '';
    $cancel = '';
    $branch = '';
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Save'){ 
        //echo '<pre>';print_r($_POST);exit;
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $payroll_id = isset($_POST['payroll_id'])?$instance->re_db_input($_POST['payroll_id']):0;
        
        $trade_number = isset($_POST['trade_number'])?$instance->re_db_input($_POST['trade_number']):'';
        $trade_date = isset($_POST['trade_date'])?$instance->re_db_input($_POST['trade_date']):'';
        //$investment = isset($_POST['investment'])?$instance->re_db_input($_POST['investment']):'';
        //$investment_cusip = isset($_POST['investment_cusip'])?$instance->re_db_input($_POST['investment_cusip']):'';
        $client_account_number = isset($_POST['client_account_number'])?$instance->re_db_input($_POST['client_account_number']):'';
        $client_name = isset($_POST['client_name'])?$instance->re_db_input($_POST['client_name']):'';
        $broker_name = isset($_POST['broker_name'])?$instance->re_db_input($_POST['broker_name']):'';
        //$broker_amount = isset($_POST['broker_amount'])?$instance->re_db_input($_POST['broker_amount']):'';
        $quantity = isset($_POST['quantity'])?$instance->re_db_input($_POST['quantity']):'';
        $price = isset($_POST['price'])?$instance->re_db_input($_POST['price']):'';
        $investment_amount = isset($_POST['investment_amount'])?$instance->re_db_input($_POST['investment_amount']):'';
        $commission_expired = isset($_POST['commission_expired'])?$instance->re_db_input($_POST['commission_expired']):'';
        $charge = isset($_POST['charge'])?$instance->re_db_input($_POST['charge']):'';
        $date_received = isset($_POST['date_received'])?$instance->re_db_input($_POST['date_received']):'';
        $commission_received = isset($_POST['commission_received'])?$instance->re_db_input($_POST['commission_received']):'';
        //$clearing_charge = isset($_POST['clearing_charge'])?$instance->re_db_input($_POST['clearing_charge']):'';
        $product_category = isset($_POST['product_category'])?$instance->re_db_input($_POST['product_category']):'';
        $product = isset($_POST['product'])?$instance->re_db_input($_POST['product']):'';
        
        $buy_sell = isset($_POST['buy_sell'])?$instance->re_db_input($_POST['buy_sell']):'';
        $hold = isset($_POST['hold'])?$instance->re_db_input($_POST['hold']):'';
        $hold_reason = isset($_POST['hold_reason'])?$instance->re_db_input($_POST['hold_reason']):'';
        $cancel = isset($_POST['cancel'])?$instance->re_db_input($_POST['cancel']):'';
        $branch = isset($_POST['branch'])?$instance->re_db_input($_POST['branch']):'';
        
        $return = $instance->insert_update($_POST);
        
        if($return===true){
            
            header("location:".CURRENT_PAGE."?action=view");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
 
    }
    else if($action=='edit' && $id>0){
        $return = $instance->edit_review_payroll($id);//echo '<pre>';print_r($return);exit;
        $transaction_id=isset($return['trade_number'])?$return['trade_number']:'';
        //$return_trade_splits = $instance->edit_review_trade_splits($id,$transaction_id);
        //$return_trade_overrides = $instance->edit_review_trade_overrides($id,$transaction_id);
        
        $id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $payroll_id = isset($return['payroll_id'])?$instance->re_db_output($return['payroll_id']):0;
        
        $trade_number = isset($return['trade_number'])?$instance->re_db_output($return['trade_number']):'';
        $trade_date = isset($return['trade_date'])?$instance->re_db_output($return['trade_date']):'';
        //$investment = isset($return['investment'])?$instance->re_db_output($return['investment']):'';
        //$investment_cusip = isset($return['investment_cusip'])?$instance->re_db_output($return['investment_cusip']):'';
        $client_account_number = isset($return['client_account_number'])?$instance->re_db_output($return['client_account_number']):'';
        $client_name = isset($return['client_name'])?$instance->re_db_output($return['client_name']):'';
        $broker_name = isset($return['broker_name'])?$instance->re_db_output($return['broker_name']):'';
        //$broker_amount = isset($return['broker_amount'])?$instance->re_db_output($return['broker_amount']):'';
        $quantity = isset($return['quantity'])?$instance->re_db_output($return['quantity']):'';
        $price = isset($return['price'])?$instance->re_db_output($return['price']):'';
        $investment_amount = isset($return['investment_amount'])?$instance->re_db_output($return['investment_amount']):'';
        $commission_expired = isset($return['commission_expired'])?$instance->re_db_output($return['commission_expired']):'';
        $charge = isset($return['charge'])?$instance->re_db_output($return['charge']):'';
        $date_received = isset($return['date_received'])?$instance->re_db_output($return['date_received']):'';
        $commission_received = isset($return['commission_received'])?$instance->re_db_output($return['commission_received']):'';
        //$clearing_charge = isset($return['clearing_charge'])?$instance->re_db_output($return['clearing_charge']):'';
        $product_category = isset($return['product_category'])?$instance->re_db_output($return['product_category']):'';
        $product = isset($return['product'])?$instance->re_db_output($return['product']):'';
        
        $buy_sell = isset($return['buy_sell'])?$instance->re_db_output($return['buy_sell']):'';
        $hold = isset($return['hold'])?$instance->re_db_output($return['hold']):'';
        $hold_reason = isset($return['hold_reason'])?$instance->re_db_output($return['hold_reason']):'';
        $cancel = isset($return['cancel'])?$instance->re_db_output($return['cancel']):'';
        $branch = isset($return['branch'])?$instance->re_db_output($return['branch']):'';  
    }
    else if(isset($action) && $action=='delete' && $id>0)
    {
        $return = $instance->delete($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
    }
    else if($action=='view'){
        $is_listing=1;
        $return = $instance->select($is_listing);
        
    }
    
    
    $content = "review_payroll";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>