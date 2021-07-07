<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $search_text_sponsor = '';
    $sponser_name = '';
    $saddress1 = '';
    $saddress2 = '';
    $scity = '';
    $sstate = '';
    $szip_code = '';
    $semail = '';
    $swebsite = '';
    $sgeneral_contact = '';
    $sgeneral_phone = '';
    $soperations_contact = '';
    $soperations_phone = '';
    $sdst_system_id = '';
    $sdst_mgmt_code = '';
    $sdst_import = '';
    $sdazl_code = '';
    $sdazl_import = '';
    $sdtcc_nscc = '';
    $sclr_firm = '';

    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view_sponsor';
    $sponsor_id = isset($_GET['sponsor_id'])&&$_GET['sponsor_id']!=''?$dbins->re_db_input($_GET['sponsor_id']):0;
    
    
    $instance = new manage_sponsor();
    $get_sponsor = $instance->select_sponsor();
    $get_state = $instance->select_state();
    
    if(isset($_POST['next'])&& $_POST['next']=='Next'){
        
        $category = isset($_POST['set_category'])?$instance->re_db_input($_POST['set_category']):'';
        
        if($category!=''){
            header("location:".CURRENT_PAGE.'?action=add_product&category='.$category.'');exit;
        }
    }
    else if(isset($_POST['sponser'])&& $_POST['sponser']=='Save'){
        
        $sponsor_id = isset($_POST['sponsor_id'])?$instance->re_db_input($_POST['sponsor_id']):0;
        $sponser_name = isset($_POST['sponser_name'])?$instance->re_db_input($_POST['sponser_name']):'';
        $saddress1 = isset($_POST['saddress1'])?$instance->re_db_input($_POST['saddress1']):'';
        $saddress2 = isset($_POST['saddress2'])?$instance->re_db_input($_POST['saddress2']):'';
        $scity = isset($_POST['scity'])?$instance->re_db_input($_POST['scity']):'';
        $sstate = isset($_POST['sstate'])?$instance->re_db_input($_POST['sstate']):'';
        $szip_code = isset($_POST['szip_code'])?$instance->re_db_input($_POST['szip_code']):'';
        $semail = isset($_POST['semail'])?$instance->re_db_input($_POST['semail']):'';
        $swebsite = isset($_POST['swebsite'])?$instance->re_db_input($_POST['swebsite']):'';
        $sgeneral_contact = isset($_POST['sgeneral_contact'])?$instance->re_db_input($_POST['sgeneral_contact']):'';
        $sgeneral_phone = isset($_POST['sgeneral_phone'])?$instance->re_db_input($_POST['sgeneral_phone']):'';
        $soperations_contact = isset($_POST['soperations_contact'])?$instance->re_db_input($_POST['soperations_contact']):'';
        $soperations_phone = isset($_POST['soperations_phone'])?$instance->re_db_input($_POST['soperations_phone']):'';
        $sdst_system_id = isset($_POST['sdst_system_id'])?$instance->re_db_input($_POST['sdst_system_id']):'';
        $sdst_mgmt_code = isset($_POST['sdst_mgmt_code'])?$instance->re_db_input($_POST['sdst_mgmt_code']):'';
        $sdst_import = isset($_POST['sdst_import'])?$instance->re_db_input($_POST['sdst_import']):'';
        $sdazl_code = isset($_POST['sdazl_code'])?$instance->re_db_input($_POST['sdazl_code']):'';
        $sdazl_import = isset($_POST['sdazl_import'])?$instance->re_db_input($_POST['sdazl_import']):'';
        $sdtcc_nscc = isset($_POST['sdtcc_nscc'])?$instance->re_db_input($_POST['sdtcc_nscc']):'';
        $sclr_firm = isset($_POST['sclr_firm'])?$instance->re_db_input($_POST['sclr_firm']):'';
        
        //for import module
        $for_import = isset($_POST['for_import'])?$instance->re_db_input($_POST['for_import']):'false';
        $file_id = isset($_POST['file_id'])?$instance->re_db_input($_POST['file_id']):0;
        
        $return = $instance->insert_update_sponsor($_POST);
        
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
                header("location:".CURRENT_PAGE.'?action=view_sponsor');exit;
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
    else if($action=='edit_sponsor' && $sponsor_id>0){
        $return = $instance->edit_sponsor($sponsor_id);
        $sponsor_data = $instance->get_sponsor_changes($sponsor_id);
        $sponsor_id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $sponser_name = isset($return['name'])?$instance->re_db_output($return['name']):'';
        $saddress1 = isset($return['address1'])?$instance->re_db_output($return['address1']):'';
        $saddress2 = isset($return['address2'])?$instance->re_db_output($return['address2']):'';
        $scity = isset($return['city'])?$instance->re_db_output($return['city']):'';
        $sstate = isset($return['state'])?$instance->re_db_output($return['state']):'';
        $szip_code = isset($return['zip_code'])?$instance->re_db_output($return['zip_code']):'';
        $semail = isset($return['email'])?$instance->re_db_output($return['email']):'';
        $swebsite = isset($return['website'])?$instance->re_db_output($return['website']):'';
        $sgeneral_contact = isset($return['general_contact'])?$instance->re_db_output($return['general_contact']):'';
        $sgeneral_phone = isset($return['general_phone'])?$instance->re_db_output($return['general_phone']):'';
        $soperations_contact = isset($return['operations_contact'])?$instance->re_db_output($return['operations_contact']):'';
        $soperations_phone = isset($return['operations_phone'])?$instance->re_db_output($return['operations_phone']):'';
        $sdst_system_id = isset($return['dst_system_id'])?$instance->re_db_output($return['dst_system_id']):'';
        $sdst_mgmt_code = isset($return['dst_mgmt_code'])?$instance->re_db_output($return['dst_mgmt_code']):'';
        $sdst_import = isset($return['dst_importing'])?$instance->re_db_output($return['dst_importing']):0;
        $sdazl_code = isset($return['dazl_code'])?$instance->re_db_output($return['dazl_code']):'';
        $sdazl_import = isset($return['dazl_importing'])?$instance->re_db_output($return['dazl_importing']):0;
        $sdtcc_nscc = isset($return['dtcc_nscc_id'])?$instance->re_db_output($return['dtcc_nscc_id']):'';
        $sclr_firm = isset($return['clearing_firm_id'])?$instance->re_db_output($return['clearing_firm_id']):0;        
    }
    else if(isset($_GET['action'])&&$_GET['action']=='sponsor_delete'&&isset($_GET['sponsor_id'])&&$_GET['sponsor_id']>0)
    {
        $id = $instance->re_db_input($_GET['sponsor_id']);
        $return = $instance->sponsor_delete($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_sponsor');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?action=view_sponsor');exit;
        }
    }
    else if(isset($_POST['add_notes'])&& $_POST['add_notes']=='Add Notes'){
        $_POST['user_id']=$_SESSION['user_name'];
        $_POST['date'] = date('Y-m-d');
       
        $return = $instance->insert_update_sponsor_notes($_POST);
        
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
    else if(isset($_POST['add_attach'])&& $_POST['add_attach']=='Add Attach'){//print_r($_FILES);exit;
        $_POST['user_id']=$_SESSION['user_name'];
        $_POST['date'] = date('Y-m-d');
        $file = isset($_FILES['add_attach'])?$_FILES['add_attach']:array();
        
        $return = $instance->insert_update_sponsor_attach($_POST);
        
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
    else if(isset($_GET['send'])&&$_GET['send']=='previous' && isset($_GET['id'])&&$_GET['id']>0 && $_GET['id']!='')
    {
        $id = $instance->re_db_input($_GET['id']);
        
        $return = $instance->get_previous_sponsor($id);
            
        if($return!=false){
            $id=$return['id'];
            header("location:".CURRENT_PAGE."?action=edit_sponsor&sponsor_id=".$id."");exit;
        }
        else{
            header("location:".CURRENT_PAGE."?action=edit_sponsor&sponsor_id=".$id."");exit;
        }
        
    }
    else if(isset($_GET['send'])&&$_GET['send']=='next' && isset($_GET['id'])&&$_GET['id']>0 && $_GET['id']!='')
    {
        $id = $instance->re_db_input($_GET['id']);
        
        $return = $instance->get_next_sponsor($id);
            
        if($return!=false){
            $id=$return['id'];
            header("location:".CURRENT_PAGE."?action=edit_sponsor&sponsor_id=".$id."");exit;
        }
        else{
            header("location:".CURRENT_PAGE."?action=edit_sponsor&sponsor_id=".$id."");exit;
        }
        
    }   
    else if(isset($_GET['action'])&&$_GET['action']=='sponsor_status'&&isset($_GET['sponsor_id'])&&$_GET['sponsor_id']>0&&isset($_GET['status'])&&($_GET['status']==0 || $_GET['status']==1))
    {
        $sponsor_id = $instance->re_db_input($_GET['sponsor_id']);
        $status = $instance->re_db_input($_GET['status']);
        $return = $instance->sponsor_status($sponsor_id,$status);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_sponsor');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?action=view_sponsor');exit;
        }
    }  
    else if(isset($_POST['search_sponsor'])&&$_POST['search_sponsor']=='Search'){
       $search_text_sponsor = isset($_POST['search_text_sponsor'])?$instance->re_db_input($_POST['search_text_sponsor']):''; 
       $return = $instance->search_sponsor($search_text_sponsor);
    }
    else if($action=='view_sponsor'){
        
        $return = $instance->select_sponsor();
        
    }
    $content = "manage_sponsor";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");

?>