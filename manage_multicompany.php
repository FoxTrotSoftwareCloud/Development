<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
     
    $error = '';
    $type = '';
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new manage_company();
    $get_state  = $instance->select_state();
    $get_manager  = $instance->select_manager();
    $get_product  = $instance->select_product_category();
    if(isset($_POST['submit'])&& $_POST['submit']=='Save'){
        
        //echo '<pre>';print_r($_POST);exit();
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $company_name = isset($_POST['company_name'])?$instance->re_db_input($_POST['company_name']):'';
        $company_type = isset($_POST['company_type'])?$instance->re_db_input($_POST['company_type']):'';
        $manager_name = isset($_POST['manager_name'])?$instance->re_db_input($_POST['manager_name']):'';
        $address1 = isset($_POST['address1'])?$instance->re_db_input($_POST['address1']):'';
        $address2 = isset($_POST['address2'])?$instance->re_db_input($_POST['address2']):'';
        $business_city = isset($_POST['business_city'])?$instance->re_db_input($_POST['business_city']):'';
        $state_general = isset($_POST['state_general'])?$instance->re_db_input($_POST['state_general']):'';
        $zip = isset($_POST['zip'])?$instance->re_db_input($_POST['zip']):'';
        $mail_address1 = isset($_POST['mail_address1'])?$instance->re_db_input($_POST['mail_address1']):'';
        $mail_address2 = isset($_POST['mail_address2'])?$instance->re_db_input($_POST['mail_address2']):'';
        $m_city = isset($_POST['m_city'])?$instance->re_db_input($_POST['m_city']):'';
        $state_mailing=isset($_POST['state_mailing'])?$instance->re_db_input($_POST['state_mailing']):'';
        $m_zip = isset($_POST['m_zip'])?$instance->re_db_input($_POST['m_zip']):'';
        $telephone = isset($_POST['telephone'])?$instance->re_db_input($_POST['telephone']):'';
        $facsimile = isset($_POST['facsimile'])?$instance->re_db_input($_POST['facsimile']):'';
        $e_date = isset($_POST['e_date'])?$instance->re_db_input($_POST['e_date']):'';
        $i_date = isset($_POST['i_date'])?$instance->re_db_input($_POST['i_date']):'';
        $payout_level = isset($_POST['payout_level'])?$instance->re_db_input($_POST['payout_level']):'';
        $clearing_charge_calculation = isset($_POST['clearing_charge_calculation'])?$instance->re_db_input($_POST['clearing_charge_calculation']):'';
        $sliding_scale_commision = isset($_POST['sliding_scale_commision'])?$instance->re_db_input($_POST['sliding_scale_commision']):'';
        $product_category = isset($_POST['product_category'])?$instance->re_db_input($_POST['product_category']):'';
        $p_rate = isset($_POST['p_rate'])?$instance->re_db_input($_POST['p_rate']):'';
        $threshold1 = isset($_POST['threshold1'])?$instance->re_db_input($_POST['threshold1']):'';
        $l1_rate = isset($_POST['l1_rate'])?$instance->re_db_input($_POST['l1_rate']):'';
        $threshold2 = isset($_POST['threshold2'])?$instance->re_db_input($_POST['threshold2']):'';
        $l2_rate = isset($_POST['l2_rate'])?$instance->re_db_input($_POST['l2_rate']):'';
        $threshold3 = isset($_POST['threshold3'])?$instance->re_db_input($_POST['threshold3']):'';
        $l3_rate = isset($_POST['l3_rate'])?$instance->re_db_input($_POST['l3_rate']):'';
        $threshold4 = isset($_POST['threshold4'])?$instance->re_db_input($_POST['threshold4']):'';
        $l4_rate = isset($_POST['l4_rate'])?$instance->re_db_input($_POST['l4_rate']):'';
        $threshold5 = isset($_POST['threshold5'])?$instance->re_db_input($_POST['threshold5']):'';
        $l5_rate = isset($_POST['l5_rate'])?$instance->re_db_input($_POST['l5_rate']):'';
        $threshold6 = isset($_POST['threshold6'])?$instance->re_db_input($_POST['threshold6']):'';
        $l6_rate = isset($_POST['l6_rate'])?$instance->re_db_input($_POST['l6_rate']):'';
        $state = isset($_POST['state'])?$instance->re_db_input('state'):'';
        $foreign = isset($_POST['foreign'])?$instance->re_db_input($_POST['foreign']):'';
        
        
        
        $return = $instance->insert_update($_POST);
        
        if($return===true){
            header("location:".CURRENT_PAGE);
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if(isset($_POST['submit'])&& $_POST['submit']=='Search'){    
        $return = $instance->select_search_company($_POST);
    }
    else if($action=='edit' && $id>0){
        $return = $instance->edit($id);
        $multicompany_data = $instance->get_multicompany_changes($id);
        $company_name = $instance->re_db_output($return['company_name']);
        $company_type = $instance->re_db_output($return['company_type']);
        $manager_name = $instance->re_db_output($return['manager_name']);
        $address1 = $instance->re_db_output($return['address1']);
        $address2 = $instance->re_db_output($return['address2']);
        $business_city = $instance->re_db_output($return['business_city']);
        $state_general = $instance->re_db_output($return['state_general']);
        $zip = $instance->re_db_output($return['zip']);
        $mail_address1 = $instance->re_db_output($return['mail_address1']);
        $mail_address2 = $instance->re_db_output($return['mail_address2']);
        $m_city = $instance->re_db_output($return['m_city']);
        $state_mailing = $instance->re_db_output($return['m_state']);
        $m_zip = $instance->re_db_output($return['m_zip']);
        $telephone = $instance->re_db_output($return['telephone']);
        $facsimile = $instance->re_db_output($return['facsimile']);
        $e_date = $instance->re_db_output($return['e_date']);
        $i_date = $instance->re_db_output($return['i_date']);
        $payout_level = $instance->re_db_output($return['payout_level']);
        $clearing_charge_calculation = $instance->re_db_output($return['clearing_charge_calculation']);
        $sliding_scale_commision = $instance->re_db_output($return['sliding_scale_commision']);
        $product_category = $instance->re_db_output($return['product_category']);
        $p_rate = $instance->re_db_output($return['p_rate']);
        $threshold1 = $instance->re_db_output($return['threshold1']);
        $l1_rate = $instance->re_db_output($return['l1_rate']);
        $threshold2 = $instance->re_db_output($return['threshold2']);
        $l2_rate = $instance->re_db_output($return['l2_rate']);
        $threshold3 = $instance->re_db_output($return['threshold3']);
        $l3_rate = $instance->re_db_output($return['l3_rate']);
        $threshold4 = $instance->re_db_output($return['threshold4']);
        $l4_rate = $instance->re_db_output($return['l4_rate']);
        $threshold5 = $instance->re_db_output($return['threshold5']);
        $l5_rate = $instance->re_db_output($return['l5_rate']);
        $threshold6 = $instance->re_db_output($return['threshold6']);
        $l6_rate = $instance->re_db_output($return['l6_rate']);
        $state = $instance->re_db_output($return['state']);
        $foreign = $instance->re_db_output($return['forign']);
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
    else if(isset($_GET['send'])&&$_GET['send']=='previous' && isset($_GET['id'])&&$_GET['id']>0 && $_GET['id']!='')
    {
        $id = $instance->re_db_input($_GET['id']);
        
        $return = $instance->get_previous_company($id);
            
        if($return!=false){
            $id=$return['id'];
            header("location:".CURRENT_PAGE."?action=edit&id=".$id."");exit;
        }
        else{
            header("location:".CURRENT_PAGE."?action=edit&id=".$id."");exit;
        }
        
    }
    else if(isset($_GET['send'])&&$_GET['send']=='next' && isset($_GET['id'])&&$_GET['id']>0 && $_GET['id']!='')
    {
        $id = $instance->re_db_input($_GET['id']);
        
        $return = $instance->get_next_company($id);
            
        if($return!=false){
            $id=$return['id'];
            header("location:".CURRENT_PAGE."?action=edit&id=".$id."");exit;
        }
        else{
            header("location:".CURRENT_PAGE."?action=edit&id=".$id."");exit;
        }
        
    }  
    else if(isset($_POST['add_notes'])&& $_POST['add_notes']=='Add Notes'){
        $_POST['user_id']=$_SESSION['user_name'];
        $_POST['date'] = date('Y-m-d');
       
        $return = $instance->insert_update_company_notes($_POST);
        
        if($return===true){
            echo '1';exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
        echo $error;
        exit;
    } 
    else if(isset($_POST['add_attach'])&& $_POST['add_attach']=='Add Attach'){//print_r($_FILES);exit;
        $_POST['user_id']=$_SESSION['user_name'];
        $_POST['date'] = date('Y-m-d');
        $file = isset($_FILES['add_attach'])?$_FILES['add_attach']:array();
        
        $return = $instance->insert_update_company_attach($_POST);
        
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
    /*else if(isset($_GET['action'])&&$_GET['action']=='delete'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete($id);
        if($return==true){
            header('location:'.CURRENT_PAGE);exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }*/
    else if($action=='view'){
        
        $return = $instance->select_company();
        
    }
    
    $content = "manage_multicompany";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>