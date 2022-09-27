<?php
// print_r($_GET);

require_once("include/config.php");
require_once(DIR_FS."islogin.php");

$instance = new transaction();
$get_brokers=$instance->select_broker();
$client_maintenance_instance = new client_maintenance();
$get_states = $client_maintenance_instance->select_state();
    $broker_instance = new broker_master();
    if(isset($_POST['submit']) && $_POST['submit']=='Save') {
        
        for($i=0;$i<count($_POST['state_fee']);$i++) {
            $state_fee = $_POST['state_fee'][$i];
            $state_id = $_POST['state_id'][$i];
            $res= $broker_instance->save_broker_state_fee($state_id,$state_fee);
        }
      
         header("location:/CloudFox/brokerstatefee.php?msg=success");exit;
    }
 
    $feeData=  $broker_instance->load_broker_state_fee();
    
$content = "brokerstatefee";
require_once(DIR_WS_TEMPLATES."main_page.tpl.php");