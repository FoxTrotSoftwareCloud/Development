<?php

    require_once("include/config.php");
	require_once(DIR_FS."islogin.php");
	
    $instance = new rules();
    $get_rules_action = $instance->select_rules_action();
    $get_rules = $instance->select_rules();
    $get_broker = $instance->get_broker_name();
    $return = $instance->select();
    if(isset($_POST['rule'])&& $_POST['rule']=='Save'){ 
        //echo '<pre>';print_r($_POST);exit();
        $in_force = isset($_POST['in_force'])?$instance->re_db_input($_POST['in_force']):0;
        $rule = isset($_POST['rule'])?$instance->re_db_input($_POST['rule']):0;
        $action = isset($_POST['action'])?$instance->re_db_input($_POST['action']):0;
        $parameter_1 = isset($_POST['parameter_1'])?$instance->re_db_input($_POST['parameter_1']):0;
        $parameter1 = isset($_POST['parameter1'])?$instance->re_db_input($_POST['parameter1']):0;
        $parameter_2 = isset($_POST['parameter_2'])?$instance->re_db_input($_POST['parameter_2']):0;
        $parameter2 = isset($_POST['parameter2'])?$instance->re_db_input($_POST['parameter2']):0;
        $return1 = $instance->insert_update($_POST);
        
        if($return1===true){
            header("location:".CURRENT_PAGE);exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return1:'';
        }
 
    }
    
    $content = "rules_engine";
	require_once(DIR_WS_TEMPLATES."main_page.tpl.php");

?>