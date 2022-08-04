<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    // 07/30/22 Check if the Process File button was clicked in the form
    $action = (isset($_POST) AND substr(array_key_first($_POST),0,7)=="import_") ? array_key_first($_POST) : $action;
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    $return = 0;
    $activeTab = '';
    $instance = new webcrd();

    if(substr($action,0,7)=='import_' AND $_POST[$action]=='Process File'){
        $return = false;
        
        if ($action === "import_ce_download"){
            $return = $instance->ce_download_scan();
            $pageTab = "tab_a";
        } else if ($action === 'import_finra_exam_status'){
            $return = $instance->finra_exam_status_scan();
            $pageTab = "tab_b";
        } else if ($action === 'import_registration_status'){
            $return = $instance->registration_status_scan();
            $pageTab = "tab_c";
        }
        
        if ($return===true){
			$_SESSION['success'] = INSERT_MESSAGE;
            header('location:'.CURRENT_PAGE."?tab=$pageTab&open=report");
            exit;
        } else {
            $_SESSION['warning'] = UNKWON_ERROR;
            header('location:'.CURRENT_PAGE."?tab=$pageTab");
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
        unset($_SESSION['warning']);
    }	 

    $content = "webcrd";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>