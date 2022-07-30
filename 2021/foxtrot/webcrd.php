<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    $return = 0;
    $instance = new webcrd();

    if(isset($_POST['import_ce_download'])&& $_POST['import_ce_download']=='CE Download'){
        $return = $instance->ce_download_scan();
        
        if ($return===true){
			$_SESSION['success'] = INSERT_MESSAGE;
            header('location:'.CURRENT_PAGE.'?tab=tab_a&open=report');
            exit;
        } else {
            $_SESSION['warning'] = UNKWON_ERROR;
            header('location:'.CURRENT_PAGE.'?tab=tab_a');
            exit;
        }
    }
    else if(isset($_POST['import_fincen'])&& $_POST['import_fincen']=='FINCEN Scan'){
        // 07/23/22 Moved to class file
        $return = $instance->fincen_scan();
                        
        if($return===true){
            $_SESSION['success'] = INSERT_MESSAGE;
            header('location:'.CURRENT_PAGE.'?tab=tab_d&open=report_fincen');
            exit;
        } else {
            $_SESSION['warning'] = UNKWON_ERROR;
            $error = !isset($_SESSION['warning']) ? $return : '';
            header('location:'.CURRENT_PAGE.'?tab=tab_d');
            exit;
        }
    }
    else if(isset($_GET['action'])&&$_GET['action']=='delete'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?tab=tab_b');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?tab=tab_b');exit;
        }
    }
    else if(isset($_GET['action'])&&$_GET['action']=='delete_fincen'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_fincen($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?tab=tab_d');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?tab=tab_d');exit;
        }
    }
    else if($action=='view'){
        $return = $instance->select_scan_file();
        $return_fincen = $instance->select_fincen_scan_file();
        unset($_SESSION['warning']);
    }	 
    // 07/23/22 Not sure if this code is needed. Print Options are in the TPL file
    /*else if(isset($_GET['action'])&&$_GET['action']=='print'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        header("location:report_ofac_client_check.php?id=".$id);exit;
    }
    else if(isset($_GET['action'])&&$_GET['action']=='print_fincen'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        header("location:report_fincen_client_check.php?id=".$id);exit;
    }*/

    $content = "webcrd";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>