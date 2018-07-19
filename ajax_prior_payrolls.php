<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
if(isset($_GET['broker_id']) && $_GET['broker_id'] != '')
{
    $broker_id = $_GET['broker_id'];
    $instance_broker = new broker_master();
    $get_single_broker = $instance_broker->edit($broker_id);
    $fund_clearing_number = isset($get_single_broker['fund'])?$get_single_broker['fund']:'';
    echo $fund_clearing_number;exit;
}
?>