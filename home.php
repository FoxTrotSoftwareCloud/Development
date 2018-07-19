<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    $content = "home";
    
    $con = '';
    $from_date = '';
    $to_date = '';
    $month_array = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
    $dis_month_list = json_encode(array_values($month_array));
    //print_r($dis_month_list);exit;
    $instance = new home();
    //print_r($_POST);exit;
    if(isset($_POST['filter']) && $_POST['filter'] == 'Filter')
    {
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $chart_id = isset($_POST['chart_id'])?$_POST['chart_id']:'';
        
        if($chart_id == '1')
        {
            $con = '';
            if(isset($from_date) && $from_date)
            {
                $con .= " and created_time>'".date("Y-m-d H:i:s",strtotime($from_date))."' ";
            }
            if(isset($to_date) && $to_date)
            {
                $con .= " and created_time<'".date("Y-m-d 23:59:59",strtotime($to_date))."' ";
            }
            //daily importing details with filter:
            $get_di_completed_files = $instance->select_daily_import_completed_files($con);
            $get_di_process_files = $instance->select_daily_import_process_files($con);
            $get_di_new_files = $instance->select_daily_import_new_files($con);
            $_SESSION['chart_1'] = $con;
        }
        else
        {
            //daily importing details:
            $con = isset($_SESSION['chart_1'])?$_SESSION['chart_1']:'';
            $get_di_completed_files = $instance->select_daily_import_completed_files($con);
            $get_di_process_files = $instance->select_daily_import_process_files($con);
            $get_di_new_files = $instance->select_daily_import_new_files($con); 
        }
        if($chart_id == '2')
        {
            $con = '';
            if(isset($from_date) && $from_date)
            {
                $con .= " and created_time>'".date("Y-m-d H:i:s",strtotime($from_date))."' ";
            }
            if(isset($to_date) && $to_date)
            {
                $con .= " and created_time<'".date("Y-m-d 23:59:59",strtotime($to_date))."' ";
            }
            //commission chart details with filter:
            $invest_amount_array = $instance->select_invest_amount($con);
            $charge_amount_array = $instance->select_charge_amount($con);
            $commission_received_amount_array = $instance->select_commission_received_amount($con);
            $_SESSION['chart_2'] = $con;
        }
        else
        {
            $con = isset($_SESSION['chart_2'])?$_SESSION['chart_2']:'';
            $invest_amount_array = $instance->select_invest_amount($con);
            $charge_amount_array = $instance->select_charge_amount($con);
            $commission_received_amount_array = $instance->select_commission_received_amount($con);
        }
        if($chart_id == '3')
        {
            
        }
        else
        {
            
        }
        if($chart_id == '4')
        {
            $con = '';
            if(isset($from_date) && $from_date)
            {
                $con .= " and created_time>'".date("Y-m-d H:i:s",strtotime($from_date))."' ";
            }
            if(isset($to_date) && $to_date)
            {
                $con .= " and created_time<'".date("Y-m-d 23:59:59",strtotime($to_date))."' ";
            }
            //Complience chart details with filter:
            $transaction_process_array = $instance->select_processed_commission($con);
            $transaction_hold_array = $instance->select_hold_commission($con);
            $_SESSION['chart_4'] = $con;
        }
        else
        {
            //Complience chart details:
            $con = isset($_SESSION['chart_4'])?$_SESSION['chart_4']:'';
            $transaction_process_array = $instance->select_processed_commission($con);
            $transaction_hold_array = $instance->select_hold_commission($con);
        }
        if($chart_id == '5')
        {
            $con = '';
            if(isset($from_date) && $from_date)
            {
                $con .= " and `t`.`created_time`>'".date("Y-m-d H:i:s",strtotime($from_date))."' ";
            }
            if(isset($to_date) && $to_date)
            {
                $con .= " and `t`.`created_time`<'".date("Y-m-d 23:59:59",strtotime($to_date))."' ";
            }
            //YTD chart details:
            $ytd_amount_array = $instance->select_ytd_amount($con);
            $ytd_amount_array_list = $instance->select_ytd_amount_list($con);
            $_SESSION['chart_5'] = $con;
        }
        else
        {
            //YTD chart details:
            $con = isset($_SESSION['chart_5'])?$_SESSION['chart_5']:'';
            $ytd_amount_array = $instance->select_ytd_amount($con);
            $ytd_amount_array_list = $instance->select_ytd_amount_list($con);
        }
    }
    else
    {
        unset($_SESSION['chart_1']);
        unset($_SESSION['chart_2']);
        unset($_SESSION['chart_3']);
        unset($_SESSION['chart_4']);
        unset($_SESSION['chart_5']);
        //daily importing details:
        $get_di_completed_files = $instance->select_daily_import_completed_files();
        $get_di_process_files = $instance->select_daily_import_process_files();
        $get_di_new_files = $instance->select_daily_import_new_files();
        
        //commission chart details:
        $invest_amount_array = $instance->select_invest_amount();
        $charge_amount_array = $instance->select_charge_amount();
        $commission_received_amount_array = $instance->select_commission_received_amount();
        
        //Complience chart details:
        $transaction_process_array = $instance->select_processed_commission();
        $transaction_hold_array = $instance->select_hold_commission();
        
        //YTD chart details:
        $ytd_amount_array = $instance->select_ytd_amount();
        $ytd_amount_array_list = $instance->select_ytd_amount_list();
        
    }
    $di_completed_files = isset($get_di_completed_files['total_completed_files'])?$get_di_completed_files['total_completed_files']:0;
    $di_partially_completed_files = isset($get_di_process_files['total_processed_files'])?$get_di_process_files['total_processed_files']:0;
    $di_new_files = isset($get_di_new_files['total_new_files'])?$get_di_new_files['total_new_files']:0;
    
    $invest_amount = isset($invest_amount_array['count'])?$invest_amount_array['count']:0;
    $charge_amount = isset($charge_amount_array['count'])?$charge_amount_array['count']:0;
    $commission_received_amount = isset($commission_received_amount_array['count'])?$commission_received_amount_array['count']:0;
    
    foreach($month_array as $key=>$val)
    {
        if (array_key_exists($key, $transaction_process_array)) {
            $transaction_month_data_processed[] = $transaction_process_array[$key];
        }
        else
        {
            $transaction_month_data_processed[] = 0;
        }
        if (array_key_exists($key, $transaction_hold_array)) {
            $transaction_month_data_hold[] = $transaction_hold_array[$key];
        }
        else
        {
            $transaction_month_data_hold[] = 0;
        }
    }
    $transaction_processed_data = isset($transaction_month_data_processed)?implode(',',$transaction_month_data_processed):array();
    $transaction_hold_data = isset($transaction_month_data_hold)?implode(',',$transaction_month_data_hold):array();
    
    $total_processed_transaction = isset($transaction_month_data_processed)?array_sum($transaction_month_data_processed):0;
    $total_hold_transaction = isset($transaction_month_data_hold)?array_sum($transaction_month_data_hold):0;
    
    foreach($month_array as $key=>$val)
    {
        if (array_key_exists($key, $ytd_amount_array)) {
            $ytd_amount_array_['month'][] = isset($ytd_amount_array[$key]['month'])?$ytd_amount_array[$key]['month']:'';
            $ytd_amount_array_['product_cate'][] = isset($ytd_amount_array[$key]['product_cate'])?$ytd_amount_array[$key]['product_cate']:'';
            $ytd_amount_array_['product_category'][] = isset($ytd_amount_array[$key]['product_category'])?$ytd_amount_array[$key]['product_category']:'';
            $ytd_amount_array_['total_ytd_investment_amount'][] = isset($ytd_amount_array[$key]['total_ytd_investment_amount'])?$ytd_amount_array[$key]['total_ytd_investment_amount']:0;
            $ytd_amount_array_['total_ytd_commission_received'][] = isset($ytd_amount_array[$key]['total_ytd_commission_received'])?$ytd_amount_array[$key]['total_ytd_commission_received']:0;
            $ytd_amount_array_['total_ytd_commission_pending'][] = isset($ytd_amount_array[$key]['total_ytd_commission_pending'])?$ytd_amount_array[$key]['total_ytd_commission_pending']:0;
        }
        else
        {
            $ytd_amount_array_['month'][] = '';
            $ytd_amount_array_['product_cate'][] = '';
            $ytd_amount_array_['product_category'][] = '';
            $ytd_amount_array_['total_ytd_investment_amount'][] = 0;
            $ytd_amount_array_['total_ytd_commission_received'][] = 0;
            $ytd_amount_array_['total_ytd_commission_pending'][] = 0;
        }
        
    }
    
    //foreach()
    $ytd_product_category = isset($ytd_amount_array_list['product_category'])?$ytd_amount_array_list['product_category']:array();
    $ytd_total_investment_amount = isset($ytd_amount_array_list['total_ytd_investment_amount'])?$ytd_amount_array_list['total_ytd_investment_amount']:0;
    $ytd_total_commission_received = isset($ytd_amount_array_list['total_ytd_commission_received'])?$ytd_amount_array_list['total_ytd_commission_received']:0;
    $ytd_total_commission_pending = isset($ytd_amount_array_list['total_ytd_commission_pending'])?$ytd_amount_array_list['total_ytd_commission_pending']:0;
    
    $ytd_month_chart = isset($ytd_amount_array_['month'])?json_encode($ytd_amount_array_['month']):array();
    $ytd_product_category_chart = isset($ytd_amount_array_['product_category'])?json_encode($ytd_amount_array_['product_category']):array();
    $ytd_total_investment_amount_chart = isset($ytd_amount_array_['total_ytd_investment_amount'])?implode(',',$ytd_amount_array_['total_ytd_investment_amount']):0;
    $ytd_total_commission_received_chart = isset($ytd_amount_array_['total_ytd_commission_received'])?implode(',',$ytd_amount_array_['total_ytd_commission_received']):0;
    $ytd_total_commission_pending_chart = isset($ytd_amount_array_['total_ytd_commission_pending'])?implode(',',$ytd_amount_array_['total_ytd_commission_pending']):0;
    
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?> 