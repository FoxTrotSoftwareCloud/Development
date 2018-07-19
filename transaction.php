<?php

    require_once("include/config.php");
	require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new transaction();
    $instance_batch = new batches();
    $product_category = $instance->select_category();
    $get_sponsor = $instance->select_sponsor();
    $get_broker =$instance->select_broker();
    $get_client= $instance->select_client();
    $get_batch = $instance->select_batch();
    $product_cate ='';
    $client_name='';
    $broker_name='';
    $product = '';
    $batch = '';
    $split_broker = array();
    $split_rate = array();
    $return_splits = array();
    $units = 0;
    $shares = 0; 
    
    if(isset($_POST['transaction'])&& $_POST['transaction']=='Save'){ 
        //echo '<pre>';print_r($_POST);exit();
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        //$trade_number = isset($_POST['trade_number'])?$instance->re_db_input($_POST['trade_number']):0;
        $client_name = isset($_POST['client_name'])?$instance->re_db_input($_POST['client_name']):'';
        $client_number = isset($_POST['client_number'])?$instance->re_db_input($_POST['client_number']):'';
        $broker_name = isset($_POST['broker_name'])?$instance->re_db_input($_POST['broker_name']):'';
        $product_cate = isset($_POST['product_cate'])?$instance->re_db_input($_POST['product_cate']):'';
        $sponsor = isset($_POST['sponsor'])?$instance->re_db_input($_POST['sponsor']):'';
        $product = isset($_POST['product'])?$instance->re_db_input($_POST['product']):'';
        $batch = isset($_POST['batch'])?$instance->re_db_input($_POST['batch']):'';
        $invest_amount = isset($_POST['invest_amount'])?$instance->re_db_input($_POST['invest_amount']):'';
        $charge_amount = isset($_POST['charge_amount'])?$instance->re_db_input($_POST['charge_amount']):'';
        $commission_received = isset($_POST['commission_received'])?$instance->re_db_input($_POST['commission_received']):'';
        $commission_received_date = isset($_POST['commission_received_date'])?$instance->re_db_input($_POST['commission_received_date']):'';
        $trade_date = isset($_POST['trade_date'])?$instance->re_db_input($_POST['trade_date']):'';
        $settlement_date = isset($_POST['settlement_date'])?$instance->re_db_input($_POST['settlement_date']):'';
        $split = isset($_POST['split'])?$instance->re_db_input($_POST['split']):'';
        $split_broker = isset($_POST['split_broker'])?$_POST['split_broker']:array();
        $split_rate = isset($_POST['split_rate'])?$_POST['split_rate']:array();
        $receiving_rep = isset($_POST['receiving_rep'])?$_POST['receiving_rep']:array();
        $per = isset($_POST['per'])?$_POST['per']:array();
        $another_level = isset($_POST['another_level'])?$instance->re_db_input($_POST['another_level']):'';
        $cancel = isset($_POST['cancel'])?$instance->re_db_input($_POST['cancel']):'';
        $buy_sell = isset($_POST['buy_sell'])?$instance->re_db_input($_POST['buy_sell']):'';
        $hold_commission = isset($_POST['hold_commission'])?$instance->re_db_input($_POST['hold_commission']):'';
        $hold_resoan = isset($_POST['hold_resoan'])?$instance->re_db_input($_POST['hold_resoan']):'';
        $posting_date = isset($_POST['posting_date'])?$instance->re_db_input($_POST['posting_date']):'';
        $units = isset($_POST['units'])?$instance->re_db_input($_POST['units']):'';
        $shares = isset($_POST['shares'])?$instance->re_db_input($_POST['shares']):'';
        
        $return = $instance->insert_update($_POST);
        
        if($return===true){
            if(isset($_SESSION['batch_id']) && $_SESSION['batch_id'] >0)
            {
                header("location:".SITE_URL."batches.php?action=edit_batches&id=".$_SESSION['batch_id']);
                unset($_SESSION['batch_id']);
                exit;
            }
            else{
                header("location:".CURRENT_PAGE."?action=view");exit;
            }
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
 
    }
    else if(isset($_GET['action'])&&$_GET['action']=='add' && isset($_GET['batch_id'])&&$_GET['batch_id']>0)
    {
        $batch = $instance->re_db_input($_GET['batch_id']);
        $_SESSION['batch_id'] = isset($batch)?$instance->re_db_output($batch):'';
        $get_batch_data = $instance_batch->edit_batches($batch);
        $product_cate = isset($get_batch_data['pro_category'])?$instance->re_db_output($get_batch_data['pro_category']):0;
        $sponsor = isset($get_batch_data['sponsor'])?$instance->re_db_output($get_batch_data['sponsor']):0;
        
    }
    else if($action=='edit_transaction' && $id>0){
        $return = $instance->edit_transaction($id);
        $batch_id = isset($return['batch'])?$instance->re_db_output($return['batch']):0;
        $get_batch_date = $instance->get_batch_date($batch_id);
        //echo '<pre>';print_r($get_batch_date);exit;
        $batch_date = isset($get_batch_date)?$get_batch_date:'0000-00-00';
        $id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $trade_number = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $client_name = isset($return['client_name'])?$instance->re_db_output($return['client_name']):'';
        $client_number = isset($return['client_number'])?$instance->re_db_output($return['client_number']):'';
        $broker_name = isset($return['broker_name'])?$instance->re_db_output($return['broker_name']):'';
        $product_cate = isset($return['product_cate'])?$instance->re_db_output($return['product_cate']):'';
        $sponsor = isset($return['sponsor'])?$instance->re_db_output($return['sponsor']):'';
        $product = isset($return['product'])?$instance->re_db_output($return['product']):'';
        $batch = isset($return['batch'])?$instance->re_db_output($return['batch']):'';
        $invest_amount = isset($return['invest_amount'])?$instance->re_db_output($return['invest_amount']):'';
        $charge_amount = isset($return['charge_amount'])?$instance->re_db_output($return['charge_amount']):'';
        $commission_received = isset($return['commission_received'])?$instance->re_db_output($return['commission_received']):'';
        $commission_received_date = isset($return['commission_received_date'])?$instance->re_db_output($return['commission_received_date']):'';
        $trade_date = isset($return['trade_date'])?$instance->re_db_output($return['trade_date']):'';
        $settlement_date = isset($return['settlement_date'])?$instance->re_db_output($return['settlement_date']):'';
        $split = isset($return['split'])?$instance->re_db_output($return['split']):'';
        $another_level = isset($return['another_level'])?$instance->re_db_output($return['another_level']):'';
        $cancel = isset($return['cancel'])?$instance->re_db_output($return['cancel']):'';
        $buy_sell = isset($return['buy_sell'])?$instance->re_db_output($return['buy_sell']):'';  
        $hold_commission = isset($return['hold_commission'])?$instance->re_db_output($return['hold_commission']):'';
        $hold_resoan = isset($return['hold_resoan'])?$instance->re_db_output($return['hold_resoan']):'';
        $posting_date = isset($return['posting_date'])?$instance->re_db_output($return['posting_date']):'';
        $units = isset($return['units'])?$instance->re_db_output($return['units']):'';
        $shares = isset($return['shares'])?$instance->re_db_output($return['shares']):'';
        $return_splits = $instance->edit_splits($id);
        $return_overrides = $instance->edit_overrides($id);
           
    }
    else if(isset($_GET['action'])&&$_GET['action']=='transaction_delete'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
    }
    else if(isset($_POST['view_report'])&&$_POST['view_report']=='View Report')
    {
        $batch_view_id = isset($_POST['view_batch'])?$instance->re_db_input($_POST['view_batch']):'';
        header('location:report_transaction_by_batch.php?batch_id='.$batch_view_id);exit;
    }
    else if(isset($_POST['search_transaction'])&& $_POST['search_transaction']=='Search')
    {   //echo '<pre>';print_r($_POST);exit;
        $search_type= isset($_POST['search_type'])?$instance->re_db_input($_POST['search_type']):'';
        $search_text= isset($_POST['search_text'])?$instance->re_db_input($_POST['search_text']):'';
        $return = $instance->search_transcation($_POST);
    }
    else if($action=='view'){
        
        $return = $instance->select();//echo'<pre>';print_r($return);exit;
        
    }
    
    $content = "transaction";
	require_once(DIR_WS_TEMPLATES."main_page.tpl.php");

?>