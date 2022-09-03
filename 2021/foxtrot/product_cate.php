<?php
    /* error_reporting(E_ALL);
    ini_set("display_errors", "On");*/
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    $error = '';
    $redirect='';
    $return = array();
    $search_text = '';
    $category = '';
    $name = '';
    $sponsor = '';
    $ticker_symbol = '';
    $cusip = '';
    $security = '';
    $receive = '';
    $income = '';
    $networth = '';
    $networthonly = '';
    $minimum_investment = '';
    $minimum_offer = '';
    $maximum_offer = '';
    $objective = '';
    $non_commissionable = '';
    $class_type = '';
    $fund_code = '';
    $sweep_fee = '';
    $min_threshold = array();
    $max_threshold = array();
    $min_rate = array();
    $max_rate = array();
    $investment_banking_type = '';
    $ria_specific_type = '';
    $based = '';
    $fee_rate = '';
    $st_bo = '';
    $m_date = '';
    $type = '';
    $var = '';
    $reg_type = '';
    $search_text_product = '';
    $search_product_category = '';
    $return_rates = array();

    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'select_cat';
    $redirect = isset($_GET['redirect'])&&$_GET['redirect']!=''?$dbins->re_db_input($_GET['redirect']):'';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    $category = isset($_GET['category'])&&$_GET['category']!=''?$dbins->re_db_input($_GET['category']):'';
    $cancelEdit = (!empty($_GET['cancel']) AND $action == 'view_product') ? 1 : 0;

    // Store redirect arguments for Add New Product from a calling program
    if($redirect=='add_product_from_trans') {
        $_SESSION['product_cate_add_product_from_trans']['action'] = 'add_product_from_trans';
        $_SESSION['product_cate_add_product_from_trans']['category'] = (empty($_GET['category'])) ? 0 : ((int)$dbins->re_db_input($_GET['category']));
        $_SESSION['product_cate_add_product_from_trans']['redirect'] = $redirect;
        $_SESSION['product_cate_add_product_from_trans']['redirect_id'] = (empty($_GET['transaction_id'])) ? 0 : ((int)$dbins->re_db_input($_GET['transaction_id']));
    }

    $instance_import = new import();
    $instance = new product_maintenance();
    $product_category = $instance->select_category();
    $get_sponsor = $instance->select_sponsor();
    // 06/09/22 Remove hardcoded Objectives - didn't correspond to Maintain Client objectives #'s (<select class="form-control" name="objectives">)
    $instance_client_suitability = new client_suitability_master();
    $get_objective = $instance_client_suitability->select_objective();

    $get_income = $instance_client_suitability->select_income();
    $get_networth = $instance_client_suitability->select_networth();
    $get_liquid_net_worth = $instance_client_suitability->select_liquid_net_worth();

    if($category=='' || $category=='0') {
        $get_product_transactions='';
    } else {
        $get_product_transactions = $instance->get_transaction_on_product($id,$category);//print_r($get_product_transactions);exit;
    }

    //-- Process the user inputs --//
    if(isset($_POST['next'])&& $_POST['next']=='Next')
    {
        $category = isset($_POST['set_category'])?$instance->re_db_input($_POST['set_category']):'';

        if($category!='') {
            header("location:".CURRENT_PAGE.'?action=view_product&category='.$category.'');exit;
        }
    }
    else if((isset($_POST['product']) && $_POST['product']=='Save')
        || (isset($_POST['submit']) && $_POST['submit']=='Previous')
        || (isset($_POST['submit']) && $_POST['submit']=='Next'))
    {
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $category = isset($_POST['product_category'])?$instance->re_db_input($_POST['product_category']):'';
        $name = isset($_POST['name'])?$instance->re_db_input($_POST['name']):'';
        $sponsor = isset($_POST['sponsor'])?$instance->re_db_input($_POST['sponsor']):'';
        $ticker_symbol = isset($_POST['ticker_symbol'])?$instance->re_db_input($_POST['ticker_symbol']):'';
        $cusip = isset($_POST['cusip'])?$instance->re_db_input($_POST['cusip']):'';
        $security = isset($_POST['security'])?$instance->re_db_input($_POST['security']):'';
        $receive = isset($_POST['allowable_receivable'])?$instance->re_db_input($_POST['allowable_receivable']):'';
        $income = isset($_POST['income'])?$instance->re_db_input($_POST['income']):'';
        $networth = isset($_POST['networth'])?$instance->re_db_input($_POST['networth']):'';
        $networthonly = isset($_POST['networthonly'])?$instance->re_db_input($_POST['networthonly']):'';
        $minimum_investment = isset($_POST['minimum_investment'])?$instance->re_db_input($_POST['minimum_investment']):'';
        $minimum_offer = isset($_POST['minimum_offer'])?$instance->re_db_input($_POST['minimum_offer']):'';
        $maximum_offer = isset($_POST['maximum_offer'])?$instance->re_db_input($_POST['maximum_offer']):'';
        $objective = isset($_POST['objectives'])?$instance->re_db_input($_POST['objectives']):'';
        $non_commissionable = isset($_POST['non_commissionable'])?$instance->re_db_input($_POST['non_commissionable']):'';
        $class_type = isset($_POST['class_type'])?$instance->re_db_input($_POST['class_type']):'';
        $fund_code = isset($_POST['fund_code'])?$instance->re_db_input($_POST['fund_code']):'';
        $sweep_fee = isset($_POST['sweep_fee'])?$instance->re_db_input($_POST['sweep_fee']):'';
        $min_threshold = isset($_POST['min_threshold'])?$_POST['min_threshold']:array();
        $max_threshold = isset($_POST['max_threshold'])?$_POST['max_threshold']:array();
        $min_rate = isset($_POST['min_rate'])?$_POST['min_rate']:array();
        $max_rate = isset($_POST['max_rate'])?$_POST['max_rate']:array();
        $investment_banking_type = isset($_POST['investment_banking_type'])?$instance->re_db_input($_POST['investment_banking_type']):'';
        $ria_specific_type = isset($_POST['ria_specific_type'])?$instance->re_db_input($_POST['ria_specific_type']):'';
        $based = isset($_POST['based_type'])?$instance->re_db_input($_POST['based_type']):'';
        $fee_rate = isset($_POST['fee_rate'])?$instance->re_db_input($_POST['fee_rate']):'';
        $st_bo = isset($_POST['stocks_bonds'])?$instance->re_db_input($_POST['stocks_bonds']):'';
        $m_date = isset($_POST['maturity_date'])?$instance->re_db_input($_POST['maturity_date']):'';
        $type = isset($_POST['type'])?$instance->re_db_input($_POST['type']):'';
        $var = isset($_POST['variable_annuities'])?$instance->re_db_input($_POST['variable_annuities']):'';
        $reg_type = isset($_POST['registration_type'])?$instance->re_db_input($_POST['registration_type']):'';

        $for_import = isset($_POST['for_import'])?$instance->re_db_input($_POST['for_import']):'false';
        $file_id = isset($_POST['file_id'])?$instance->re_db_input($_POST['file_id']):0;

        $return = $instance->insert_update_product($_POST,true);

        if($_POST['product']=='Save') {
            if (!$return)
                $error = (!isset($_SESSION['warning'])) ? $return : '';

            if($for_import == 'true') {
                $file_type = 0;
                
                if($file_id > 0) {
                    if($return) {
                        $exceptionDetail = $instance_import->select_exception_data(0, $_POST['exception_record_id']);
                        $importSelect = $instance_import->import_table_select($exceptionDetail[0]['file_id'], $exceptionDetail[0]['file_type']);
                        $dataDetail = $instance_import->select_existing_idc_data($_POST['detail_data_id'], $importSelect['source']);

                        $data = [
                            'resolveAction'=>5,
                            'exception_field'=>'cusip_number',
                            'assign_cusip_number'=>$dataDetail['cusip_number'],
                            'exception_record_id'=>$_POST['exception_record_id'],
                            'exception_file_id'=>$file_id,
                            'exception_data_id'=>$_POST['detail_data_id'],
                            'exception_file_type'=>$exceptionDetail[0]['file_type'],
                            'error_code_id'=>$exceptionDetail[0]['error_code_id'],
                            'exception_value'=>$exceptionDetail[0]['cusip']
                        ];

                        $instance_import->resolve_exceptions($data);
                        $file_type = $exceptionDetail[0]['file_type'];
                    }
                }

                forImport($file_id, $file_type);
            } else {
                if($redirect=='add_product_from_trans') {
                    $args = '';

                    if ($return){
                        $args= http_build_query(array("p_id"=>$_SESSION['new_product_id'],"cat_id"=>$category,"sponsor"=>$sponsor));
                    }

                    fromTrans($return, $args);
                } else {
                    header("location:".CURRENT_PAGE.'?action=select_cat');
                    exit;
                }
            }
        } else if($return==true && $_POST['submit']=='Next') {
            $id = $instance->re_db_input($_GET['id']);
            $category =$instance->re_db_input($_GET['category']);

            $return = $instance->get_next_product($id,$category);
            //print($return);exit();
            if($return!=false) {
                $id=$return['id'];
                header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."&category=".$category."");exit;
            } else {
                header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."&category=".$category."");exit;
            }
        } else if($return && $_POST['submit']=='Previous') {
            $id = $instance->re_db_input($_GET['id']);
            $category =$instance->re_db_input($_GET['category']);
            $return = $instance->get_previous_product($id,$category);

            if($return){
                $id=$return['id'];
                header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."&category=".$category."");exit;
            } else {
                header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."&category=".$category."");exit;
            }
        } else {
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if(isset($_POST['submit']) && ($_POST['submit']=='Add Note' || $_POST['submit']=='Update Note'))
    {
        $note_id = isset($_POST['note_id'])?$instance->re_db_input($_POST['note_id']):0;
        $note_date = isset($_POST['note_date'])?$instance->re_db_input($_POST['note_date']):'';
        $note_user = isset($_POST['note_user'])?$instance->re_db_input($_POST['note_user']):'';
        $product_note = isset($_POST['product_note'])?$instance->re_db_input($_POST['product_note']):'';
        $return = $instance->insert_update_product($_POST);

        if($return===true){
            echo '1';
            exit;
        } else {
            $error = (!isset($_SESSION['warning'])) ? $return : '';
        }

        echo $error;
        exit;
    }
    else if($action=='edit_product' && $id>0 && $category !='')
    {
        $return = $instance->edit_product($id,$category);
        $product_data = $instance->get_product_changes($id,$category);
        $id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $category = isset($return['category'])?$instance->re_db_output($return['category']):'';
        $name = isset($return['name'])?$instance->re_db_output($return['name']):'';
        $sponsor = isset($return['sponsor'])?$instance->re_db_output($return['sponsor']):'';
        $ticker_symbol = isset($return['ticker_symbol'])?$instance->re_db_output($return['ticker_symbol']):'';
        $cusip = isset($return['cusip'])?$instance->re_db_output($return['cusip']):'';
        $security = isset($return['security'])?$instance->re_db_output($return['security']):'';
        $receive = isset($return['receive'])?$instance->re_db_output($return['receive']):0;
        $income = isset($return['income'])?$instance->re_db_output($return['income']):'';
        $networth = isset($return['networth'])?$instance->re_db_output($return['networth']):'';
        $networthonly = isset($return['networthonly'])?$instance->re_db_output($return['networthonly']):'';
        $minimum_investment = isset($return['minimum_investment'])?$instance->re_db_output($return['minimum_investment']):'';
        $minimum_offer = isset($return['minimum_offer'])?$instance->re_db_output($return['minimum_offer']):'';
        $maximum_offer = isset($return['maximum_offer'])?$instance->re_db_output($return['maximum_offer']):'';
        $objective = isset($return['objective'])?$instance->re_db_output($return['objective']):'';
        $non_commissionable = isset($return['non_commissionable'])?$instance->re_db_output($return['non_commissionable']):0;
        $class_type = isset($return['class_type'])?$instance->re_db_output($return['class_type']):0;
        $fund_code = isset($return['fund_code'])?$instance->re_db_output($return['fund_code']):'';
        $sweep_fee = isset($return['sweep_fee'])?$instance->re_db_output($return['sweep_fee']):0;
        //$min_threshold = isset($return['min_threshold'])?$instance->re_db_output($return['min_threshold']):'';
        //$max_threshold = isset($return['max_threshold'])?$instance->re_db_output($return['max_threshold']):'';
        //$min_rate = isset($return['min_rate'])?$instance->re_db_output($return['min_rate']):'';
        //$max_rate = isset($return['max_rate'])?$instance->re_db_output($return['max_rate']):'';
        $investment_banking_type = isset($return['ria_specific'])?$instance->re_db_output($return['ria_specific']):'';
        $ria_specific_type = isset($return['ria_specific_type'])?$instance->re_db_output($return['ria_specific_type']):'';
        $based = isset($return['based'])?$instance->re_db_output($return['based']):0;
        $fee_rate = isset($return['fee_rate'])?$instance->re_db_output($return['fee_rate']):'';
        $st_bo = isset($return['st_bo'])?$instance->re_db_output($return['st_bo']):0;
        $m_date = isset($return['m_date'])?$instance->re_db_output(date('m/d/Y',strtotime($return['m_date']))):'';
        $type = isset($return['type'])?$instance->re_db_output($return['type']):'';
        $var = isset($return['var'])?$instance->re_db_output($return['var']):0;
        $reg_type = isset($return['reg_type'])?$instance->re_db_output($return['reg_type']):'';
        $return_rates = $instance->edit_product_rates($id,$category);
    }
    else if(isset($_POST['add_notes'])&& $_POST['add_notes']=='Add Notes')
    {
        $_POST['user_id']=$_SESSION['user_name'];
        $_POST['date'] = date('Y-m-d');

        $return = $instance->insert_update_product_notes($_POST);

        if($return===true){
            echo '1';exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }

        echo $error;
        exit;
    }
    else if(isset($_GET['send'])&&$_GET['send']=='previous' && isset($_GET['id'])&&$_GET['id']>0 && $_GET['id']!='')
    {
        $id = $instance->re_db_input($_GET['id']);
        $category =$instance->re_db_input($_GET['category']);
        $return = $instance->get_previous_product($id,$category);

        if($return!=false){
            $id=$return['id'];
            header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."&category=".$category."");exit;
        } else {
            header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."&category=".$category."");exit;
        }
    }
    else if(isset($_GET['send'])&&$_GET['send']=='next' && isset($_GET['id'])&&$_GET['id']>0 && $_GET['id']!='')
    {
        $id = $instance->re_db_input($_GET['id']);
        $category =$instance->re_db_input($_GET['category']);

        $return = $instance->get_next_product($id,$category);
        //print($return);exit();
        if($return!=false){
            $id=$return['id'];
            header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."&category=".$category."");exit;
        } else {
            header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."&category=".$category."");exit;
        }
    }
    else if(isset($_POST['add_attach'])&& $_POST['add_attach']=='Add Attach')
    {
        //print_r($_FILES);exit;
        $_POST['user_id']=$_SESSION['user_name'];
        $_POST['date'] = date('Y-m-d');
        $file = isset($_FILES['add_attach'])?$_FILES['add_attach']:array();

        $return = $instance->insert_update_product_attach($_POST);

        if($return===true){
            echo '1';exit;
        } else {
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
        } else {
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
            echo '1';
            exit;
        } else {
            $error = !isset($_SESSION['warning'])?$return:'';
        }

        echo $error;
        exit;
    }
    else if(isset($_GET['action'])&&$_GET['action']=='product_delete' && $_GET['category']!='' &&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $category = $instance->re_db_input($_GET['category']);
        $return = $instance->product_delete($id,$category);

        if($return==true){
            header('location:'.CURRENT_PAGE);
            exit;
        } else {
            header('location:'.CURRENT_PAGE);
            exit;
        }
    }
    else if(isset($_GET['action'])&&$_GET['action']=='product_status'&&isset($_GET['id'])&&$_GET['id']>0&&isset($_GET['status'])&&($_GET['status']==0 || $_GET['status']==1))
    {
        $id = $instance->re_db_input($_GET['id']);
        $status = $instance->re_db_input($_GET['status']);
        $category = $instance->re_db_input($_GET['category']);
        $return = $instance->product_status($id,$status,$category);

        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_product&category='.$category);exit;
        } else {
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if(isset($_POST['search_product'])&&$_POST['search_product']=='Search')
    {
       $search_text_product = isset($_POST['search_text_product'])?$instance->re_db_input($_POST['search_text_product']):'';
       $search_product_category = isset($_POST['search_product_category'])?$instance->re_db_input($_POST['search_product_category']):'';
       $return = $instance->search_product($search_text_product,$search_product_category);
    }
    else if($action=='add_new' && !empty($_GET['exception_data_id']) && !empty($_GET['exception_record_id']))
    {
        //--- 02/23/22 Called from import template page -> "Resolve Exceptions" tab ---//
        $instance_import = new import();
        $dataDetail = $exceptionDetail = $sponsor = $category = 0;
        $cusip = $name = $action = '';
        $exceptionDetail = $instance_import->select_exception_data(0, $_GET['exception_record_id']);

        if ($exceptionDetail){
            $importSelect = $instance_import->import_table_select($exceptionDetail[0]['file_id'], $exceptionDetail[0]['file_type']);

            switch ($exceptionDetail[0]['file_type']){
                case 2:
                    $dataDetail = $instance_import->select_existing_idc_data($_GET['exception_data_id'],$importSelect['source']);
                    break;
            }
        }

        if ($dataDetail AND $exceptionDetail){
            $fileDetail = $instance_import->select_user_files($dataDetail['file_id']);
            $sponsor = (isset($dataDetail['sponsor_id']) ? $dataDetail['sponsor_id'] : $fileDetail['sponsor_id']);
            $category = (isset($dataDetail['commission_record_type_code']) AND $dataDetail['commission_record_type_code']=='V') ? 4 : 1;
            $cusip = (empty($_GET['cusip_number'])) ? '' : $instance->re_db_input($_GET['cusip_number']);
            $name = (empty($_GET['name'])) ? (trim($cusip).': Cusip Number') : $instance->re_db_input($_GET['name']);
            $action = 'add_product';

            $_SESSION['product_cate_for_import'] = ['file_id'=>$fileDetail['id'], 'detail_id'=>$dataDetail['id'], 'exception_id'=>$_GET['exception_record_id']];
        }
    }
    else if($cancelEdit AND isset($_SESSION['product_cate_for_import'])){
        forImport($_SESSION['product_cate_for_import']['file_id']);
    }
    else if($cancelEdit AND isset($_SESSION['product_cate_add_product_from_trans'])){
        fromTrans(0,'');
    }
    else if($action=='view_product'){
        if($category=='' || $category=='0')
        {
            $category='1';
            $return = $instance->select_product_category($category);
        } else {
            $return = $instance->select_product_category($category);
        }
    }

    if($action == 'select_cat'){
            $return =$instance->load_product_list();
    }

    //-- Display Page, finally! --//
    $content = "product_cate";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");


    //-- USER DEFINED FUNCTIONS --//
    function forImport($file_id,$file_type=0) {
        GLOBAL $dbins;
        $args = '';
        $file_type = (int)$dbins->re_db_input($file_type);
        
        if (!empty($file_id)){
            $args = "?tab=review_files&id=".$file_id;
        }
        if ($file_type != 0){
            $args .= "&file_type=$file_type";
        }

        unset($_SESSION['product_cate_for_import']);
        header("location:".SITE_URL."import.php".$args);
    }
    //-- Callback to Maintain Transactions --//
    function fromTrans($added=0, $pArgs='') {
        // 07/06/22 if user is Adding a trade, it's not pulling up the New Transactio screen
        $sessionId = 'product_cate_add_product_from_trans';
        $sendArguments =
            (empty($_SESSION['product_cate_add_product_from_trans']['redirect_id']) ? '?action=add' : '?action='.'edit_transaction&id='.$_SESSION['product_cate_add_product_from_trans']['redirect_id'])
            .(empty($pArgs) ? '' : '&'.trim($pArgs))
            .'&redirectedFromProdCate=1'
        ;

        header("location:".SITE_URL."transaction.php".$sendArguments);

        if (isset($_SESSION[$sessionId])) {
            unset($_SESSION[$sessionId]);
        }
    }
?>