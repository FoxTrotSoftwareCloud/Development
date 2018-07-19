<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $instance = new client_ress();
    $get_broker =$instance->select_broker();
    $return_from_broker_client = array();
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Proceed'){ 
        //echo '<pre>';print_r($_POST);exit();
        $from_broker = isset($_POST['from_broker'])?$instance->re_db_input($_POST['from_broker']):0;
        $to_broker= isset($_POST['to_broker'])?$instance->re_db_input($_POST['to_broker']):'';
        $output = isset($_POST['output'])?$instance->re_db_input($_POST['output']):'';
        $broker_name = isset($_POST['broker_name'])?$instance->re_db_input($_POST['broker_name']):'';
        $return = $instance->insert_update($_POST);
        
        if($return===true){
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?open=output_screen&from_broker='.$from_broker);exit;
            }
            else if($output == 2)
            {
                header('location:'.SITE_URL.'client_ress_report.php?open=output_print&from_broker='.$from_broker);exit;
            }
            else if($output == 3){
                
                header("location:".SITE_URL."client_ress_report.php?from_broker=".$from_broker);exit;
            }
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
 
    }
    
    $content = "client_ress";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>