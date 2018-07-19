<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");

if(isset($_GET['broker_id']) && $_GET['broker_id'] != '')
{
    $broker_class = new broker_master();
    $check_broker_commission = $broker_class->check_broker_commission_status($_GET['broker_id']);
    $broker_hold_commission = $check_broker_commission['hold_commissions'];
    echo $broker_hold_commission;
}
?>