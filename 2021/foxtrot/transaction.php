<?php

    require_once("include/config.php");
	require_once(DIR_FS."islogin.php");
    $action = isset($_GET['action']) && $_GET['action']!='' ? $dbins->re_db_input($_GET['action']) : 'view';
    $id = isset($_GET['id']) && $_GET['id']!='' ? $dbins->re_db_input($_GET['id']) : 0;
    $ch_date ='';
    $ch_amount ='';
    $ch_no ='';
    $ch_pay_to ='';
    $instance = new transaction();
    $instance_batch = new batches();
    $product_category = $instance->select_category();
    $get_sponsor = $instance->select_sponsor();
    $client_account_array=$instance->select_all_client_account_no();
    $instance_broker_master = new broker_master();
    $instance_branches = new branch_maintenance();
    $instance_company = new manage_company();

    $get_broker =$instance_broker_master->select_broker_by_branch_company();
    $get_client= $instance->select_client();
    $get_batch = $instance->select_batch();
    $get_branch = $instance_branches->select(1);
    $get_company = $instance_company->select_company();

    $product_cate ='';
    $client_name = $client_number = '';
    $broker_name='';
    $product = '';
    $is_pending_order='';
    $batch = '';
    $split_broker = array();
    $split_rate = array();
    $return_splits = array();
    $shares = $units = $branch = $company = 0;
    $branch = isset($_POST['branch']) ? (int)$instance->re_db_input($_POST['branch']):0;
    $company = isset($_POST['company']) ? (int)$instance->re_db_input($_POST['company']):0;
    // Rule engine array should only exist AFTER submit - 06/11/22
    if ($action == "add"){
        if (isset($_POST['rule_engine_warning_action']) && in_array($_POST['rule_engine_warning_action'], ['1','2'])){
            // Adding a transaction after the Rule Engine came up
        } else {
            unset($_SESSION['transaction_rule_engine']);
        }
    }

    //-- Add Transaction
    if(
        (isset($_POST['transaction']) && in_array($_POST['transaction'],['Save','Save & Copy']))
        && ($_POST['rule_engine_warning_action']!='3')
    ){
        if (in_array($_POST['rule_engine_warning_action'],['1','2'])){
            $postData = $_SESSION['transaction_rule_engine']['data'];
            $postData['rule_engine_warning_action'] = $_POST['rule_engine_warning_action'];
            
            // user opted to "Hold Commissions"
            if ($postData['rule_engine_warning_action']=="1"){
                $postData['hold_commission'] = "1";
                $postData['hold_reason'] = $_SESSION['transaction_rule_engine']['warnings'];
            }
        } else {
            $postData = $_POST;
        }         

        $id = isset($postData['id'])?$instance->re_db_input($postData['id']):0;
        //$trade_number = isset($postData['trade_number'])?$instance->re_db_input($postData['trade_number']):0;
        $client_name = isset($postData['client_name'])?$instance->re_db_input($postData['client_name']):'';
        $client_number = isset($postData['client_number'])?$instance->re_db_input($postData['client_number']):'';
        // $client_id_from_ac_no =0;
        // $client_id_from_ac_no=$instance->select_client_id($client_number);
        // if($client_id_from_ac_no=='' || $client_id_from_ac_no)
        // {

        // }

        $broker_name = isset($postData['broker_name'])?$instance->re_db_input($postData['broker_name']):'';
        $product_cate = isset($postData['product_cate'])?$instance->re_db_input($postData['product_cate']):'';
        $sponsor = isset($postData['sponsor'])?$instance->re_db_input($postData['sponsor']):'';
        $product = isset($postData['product'])?$instance->re_db_input($postData['product']):'';
        $batch = isset($postData['batch'])?$instance->re_db_input($postData['batch']):'';
        $invest_amount = isset($postData['invest_amount'])?$instance->re_db_input($postData['invest_amount']):'';
        $charge_amount = isset($postData['charge_amount'])?$instance->re_db_input($postData['charge_amount']):'';
        $commission_received = isset($postData['commission_received'])?$instance->re_db_input($postData['commission_received']):'';
        $commission_received_date = isset($postData['commission_received_date'])?$instance->re_db_input($postData['commission_received_date']):'';

        $ch_date =isset($postData['ch_date'])?$instance->re_db_input($postData['ch_date']):'';
        $ch_amount =isset($postData['ch_amount'])?$instance->re_db_input($postData['ch_amount']):'';
        $ch_no =isset($postData['ch_no'])?$instance->re_db_input($postData['ch_no']):'';
        $ch_pay_to =isset($postData['ch_pay_to'])?$instance->re_db_input($postData['ch_pay_to']):'';

        $trade_date = isset($postData['trade_date'])?$instance->re_db_input($postData['trade_date']):'';
        $settlement_date = isset($postData['settlement_date'])?$instance->re_db_input($postData['settlement_date']):'';
        $split = isset($postData['split'])?$instance->re_db_input($postData['split']):'';
        $split_broker = isset($postData['split_broker'])?$postData['split_broker']:array();
        $split_rate = isset($postData['split_rate'])?$postData['split_rate']:array();
        $receiving_rep = isset($postData['receiving_rep'])?$postData['receiving_rep']:array();
        $per = isset($postData['per'])?$postData['per']:array();
        $another_level = isset($postData['another_level'])?$instance->re_db_input($postData['another_level']):'';
        $cancel = isset($postData['cancel'])?$instance->re_db_input($postData['cancel']):'';
        $buy_sell = isset($postData['buy_sell'])?$instance->re_db_input($postData['buy_sell']):'';
        $posting_date = isset($postData['posting_date'])?$instance->re_db_input($postData['posting_date']):'';
        $units = isset($postData['units'])?$instance->re_db_input($postData['units']):'';
        $shares = isset($postData['shares'])?$instance->re_db_input($postData['shares']):'';
        $is_1035_exchange = isset($postData['is_1035_exchange']) ? $instance->re_db_input($postData['is_1035_exchange']):0;
        $is_trail_trade = isset($postData['is_trail_trade']) ? $instance->re_db_input($postData['is_trail_trade']):0;
        $branch = isset($postData['branch']) ? (int)$instance->re_db_input($postData['branch']):0;
        $company = isset($postData['company']) ? (int)$instance->re_db_input($postData['company']):0;
        // 06/22/22 Reinsert the <br> elements so the reasons will appear correct in the HTML popups
        $hold_reason = isset($postData['hold_reason'])?$instance->re_db_input($postData['hold_reason']):'';
        $hold_reason = str_replace("\r\n", "<br>", $hold_reason);
        $hold_commission = isset($postData['hold_commission'])?$instance->re_db_input($postData['hold_commission']):'';
        
        $return = $instance->insert_update($postData);
        
        if($return===true){
            unset($_SESSION['transaction_rule_engine']);
            
            /* if(isset($_SESSION['batch_id']) && $_SESSION['batch_id'] >0)
            {
                header("location:".SITE_URL."batches.php?action=edit_batches&id=".$_SESSION['batch_id']);
                unset($_SESSION['batch_id']);
                exit;
            }
            else{*/
                
                if(isset($postData['transaction']) AND $postData['transaction']=='Save & Copy') {
                    unset($postData);
                    $trade_number='';
                    $commission_received='';
                    $units=0;
                    $invest_amount ='';
                    $shares=0;
                    $charge_amount ='';
                    $hold_reason = $hold_commission = '';
                    // header("location:".CURRENT_PAGE."?action=add");
                    // exit;
                } else {
                    header("location:".CURRENT_PAGE."?action=view");
                    exit;
                }
          /*  }*/
          
        }
        else{
            $error = (!isset($_SESSION['warning']) ? $return : '');
        }

    }
    else if(isset($_GET['action']) && $_GET['action']=='add' && isset($_GET['batch_id']) && $_GET['batch_id']>0)
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
        $is_1035_exchange = isset($return['is_1035_exchange']) ? $instance->re_db_input($return['is_1035_exchange']):0;
        $is_trail_trade = isset($return['is_trail_trade']) ? $instance->re_db_input($return['is_trail_trade']):0;
        $ch_date =isset($return['ch_date']) && $return['ch_date']!=''?$instance->re_db_output($return['ch_date']):'';
        $ch_amount =isset($return['ch_amount'])?$instance->re_db_output($return['ch_amount']):'';
        $ch_no =isset($return['ch_no'])?$instance->re_db_output($return['ch_no']):'';
        $ch_pay_to =isset($return['ch_pay_to'])?$instance->re_db_output($return['ch_pay_to']):'';

        $commission_received_date = isset($return['commission_received_date'])?$instance->re_db_output($return['commission_received_date']):'';
        $trade_date = isset($return['trade_date'])?$instance->re_db_output($return['trade_date']):'';
        $settlement_date = isset($return['settlement_date'])?$instance->re_db_output($return['settlement_date']):'';
        $split = isset($return['split'])?$instance->re_db_output($return['split']):'';
        $another_level = isset($return['another_level'])?$instance->re_db_output($return['another_level']):'';
        $cancel = isset($return['cancel'])?$instance->re_db_output($return['cancel']):'';
        $buy_sell = isset($return['buy_sell'])?$instance->re_db_output($return['buy_sell']):'';
        $hold_commission = (isset($return['hold_commission']) ? $instance->re_db_output($return['hold_commission']) : '2');
        $hold_reason = isset($return['hold_reason'])?$instance->re_db_output($return['hold_reason']):'';
        $posting_date = isset($return['posting_date'])?$instance->re_db_output($return['posting_date']):'';
        $units = isset($return['units'])?$instance->re_db_output($return['units']):'';
        $shares = isset($return['shares'])?$instance->re_db_output($return['shares']):'';
        $return_splits = $instance->edit_splits($id);
        $return_overrides = $instance->edit_overrides($id);
        $branch = isset($return['branch'])?$instance->re_db_output($return['branch']):'0';
        $company = isset($return['company'])?$instance->re_db_output($return['company']):'0';
        
        // Format the Hold Reasons for the <textarea> element - 06/22/22
        if (!empty($hold_reason)){
            $hold_reason = str_replace("&lt;br&gt;", "\r\n", trim($hold_reason));
            $a = 0;
        }
    }
    else if(isset($_GET['action']) && $_GET['action']=='transaction_delete' && isset($_GET['id']) && $_GET['id']>0)
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
    else if(isset($_POST['view_report']) && $_POST['view_report']=='View Report')
    {
        $batch_view_id = isset($_POST['view_batch'])?$instance->re_db_input($_POST['view_batch']):'';
        header('location:report_transaction_by_batch.php?batch_id='.$batch_view_id);exit;
    }
    else if(isset($_POST['search_transaction']) && $_POST['search_transaction']=='Search')
    {
        $search_type= isset($_POST['search_type'])?$instance->re_db_input($_POST['search_type']):'';
        $search_text= isset($_POST['search_text'])?$instance->re_db_input($_POST['search_text']):'';
        $return = $instance->search_transcation($_POST);
    }
    else if($action=='view'){
        $return = $instance->select();
        unset($_SESSION['transaction_rule_engine']);
    }

    if(isset($_GET['p_id']) && isset($_GET['cat_id'])){
        $product_cate= $_GET['cat_id'];
        $product= $_GET['p_id'];
        $sponsor= $_GET['sponsor'];
    }
    if(isset($_GET['client_id']) && isset($_GET['client_id'])){
        $client_name=$_GET['client_id'];
    }

    $get_accounts_no= $instance->select_client_all_account_no($client_name);

    $content = "transaction";
	require_once(DIR_WS_TEMPLATES."main_page.tpl.php");
?>