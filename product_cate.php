<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    $error = '';
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
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    $category = isset($_GET['category'])&&$_GET['category']!=''?$dbins->re_db_input($_GET['category']):1;
    
    $instance = new product_maintenance();
    $product_category = $instance->select_category();
    $get_sponsor = $instance->select_sponsor();
    $get_product_transactions = $instance->get_transaction_on_product($id,$category);//print_r($get_product_transactions);exit;
    
    if(isset($_POST['next'])&& $_POST['next']=='Next'){
        
        $category = isset($_POST['set_category'])?$instance->re_db_input($_POST['set_category']):'';
        
        if($category!=''){
            header("location:".CURRENT_PAGE.'?action=view_product&category='.$category.'');exit;
        }
    }
    else if(isset($_POST['product'])&& $_POST['product']=='Save'){
        //echo '<pre>'; print_r($_POST);exit;
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
        
        $return = $instance->insert_update($_POST);
        
        if($return===true){
            
            if($for_import == 'true')
            {
                if(isset($file_id) && $file_id >0 )
                {
                    header("location:".SITE_URL."import.php?tab=review_files&id=".$file_id);exit;
                }
                else
                {
                    header("location:".SITE_URL."import.php");exit;
                }
            }
            else
            {
                header("location:".CURRENT_PAGE.'?action=select_cat');exit;
            }
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if(isset($_POST['submit']) && ($_POST['submit']=='Add Note' || $_POST['submit']=='Update Note')){
        $note_id = isset($_POST['note_id'])?$instance->re_db_input($_POST['note_id']):0;
        $note_date = isset($_POST['note_date'])?$instance->re_db_input($_POST['note_date']):'';
        $note_user = isset($_POST['note_user'])?$instance->re_db_input($_POST['note_user']):'';
        $product_note = isset($_POST['product_note'])?$instance->re_db_input($_POST['product_note']):'';
        $return = $instance->insert_update($_POST);
        if($return===true){
            echo '1';exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
        echo $error;
        exit;
        
    }
    else if($action=='edit_product' && $id>0 && $category !=''){
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
        $return_rates = $instance->edit_product_rates($id,$category);//echo '<pre>';print_r($return_rates);exit;
    }
    else if(isset($_POST['add_notes'])&& $_POST['add_notes']=='Add Notes'){
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
            header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."");exit;
        }
        else{
            header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."");exit;
        }
        
    }
    else if(isset($_GET['send'])&&$_GET['send']=='next' && isset($_GET['id'])&&$_GET['id']>0 && $_GET['id']!='')
    {
        $id = $instance->re_db_input($_GET['id']);
        $category =$instance->re_db_input($_GET['category']);
        
        $return = $instance->get_next_product($id,$category);
            
        if($return!=false){
            $id=$return['id'];
            header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."");exit;
        }
        else{
            header("location:".CURRENT_PAGE."?action=edit_product&id=".$id."");exit;
        }
        
    } 
    else if(isset($_POST['add_attach'])&& $_POST['add_attach']=='Add Attach'){//print_r($_FILES);exit;
        $_POST['user_id']=$_SESSION['user_name'];
        $_POST['date'] = date('Y-m-d');
        $file = isset($_FILES['add_attach'])?$_FILES['add_attach']:array();
        
        $return = $instance->insert_update_product_attach($_POST);
        
        if($return===true){
            echo '1';exit;
        }
        else{
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
        }
        else{
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
            echo '1';exit;
        }
        else{
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
            header('location:'.CURRENT_PAGE);exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
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
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    } 
    else if(isset($_POST['search_product'])&&$_POST['search_product']=='Search'){
        
       $search_text_product = isset($_POST['search_text_product'])?$instance->re_db_input($_POST['search_text_product']):''; 
       $search_product_category = isset($_POST['search_product_category'])?$instance->re_db_input($_POST['search_product_category']):'';
       $return = $instance->search_product($search_text_product,$search_product_category);
    }
    else if($action=='view_product'){
        
        $return = $instance->select_product_category($category);//echo'<pre>';print_r($return);exit;
        
    }
    $content = "product_cate";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>