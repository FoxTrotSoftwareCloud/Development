<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
     
    $error = '';
    $type = '';
    $type_code = '';
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new product_master();
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Save'){
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $type = isset($_POST['type'])?$instance->re_db_input($_POST['type']):'';
        $type_code = isset($_POST['type_code'])?$instance->re_db_input($_POST['type_code']):'';
        $sponsor = isset($_POST['sponsor'])?$instance->re_db_input($_POST['sponsor']):'0'; 
        $return = $instance->insert_update($_POST);
        
        if($return===true){
            header("location:".CURRENT_PAGE);exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
     else if(isset($_POST['submit_account']) && $_POST['submit_account']=='Ok')
     {

        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $type = isset($_POST['type'])?$instance->re_db_input($_POST['type']):'';
        $sponsor = isset($_POST['sponsor'])?$instance->re_db_input($_POST['sponsor']):'0';        
        $type_code = isset($_POST['type_code'])?$instance->re_db_input($_POST['type_code']):'';
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
    else if(isset($_POST['submit_move_category']) && $_POST['submit_move_category']=='Ok')
     {

        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $to_category = isset($_POST['to_category'])?$instance->re_db_input($_POST['to_category']):'0';

        $return = $instance->move_category($id,$to_category);
        
        
        if($return===true){
            echo '1';
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
        echo $error;
        exit;
    }
    else if($action=='edit' && $id>0){
        $return = $instance->edit($id);
        $type = $instance->re_db_output($return['type']);
        $type_code = $instance->re_db_output($return['type_code']);
        
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
        
        $return = $instance->select_product_type();
        
    }
    $content = "product_category_maintenance";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>