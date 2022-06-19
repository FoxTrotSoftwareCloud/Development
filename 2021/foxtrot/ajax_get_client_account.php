<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new transaction();  
if(isset($_GET['client_id']) && $_GET['client_id'] > 0)
{
    $client_id = isset($_GET['client_id'])?$instance->re_db_input($_GET['client_id']):'';
    if(isset($_GET['action']) && $_GET['action'] == 'all'){
        $get_client_account = json_encode($instance->select_client_all_account_no($client_id));
    }
    else
    $get_client_account = $instance->select_client_account_no($client_id);    
    echo $get_client_account;
}

if(isset($_GET['client_number']) && $_GET['client_number'] > 0)
{
    $client_number = isset($_GET['client_number'])?$instance->re_db_input($_GET['client_number']):'';
    $get_client_id = $instance->select_client_id($client_number);    
    echo $get_client_id;
}
if(isset($_GET['batch_id']) && $_GET['batch_id'] > 0)
{
    $batch_id = isset($_GET['batch_id'])?$instance->re_db_input($_GET['batch_id']):'';
    $get_batch_date = $instance->select_batch_date($batch_id);
    if(!empty($get_batch_date))
    {
        foreach($get_batch_date as $key=>$val)
        {
            if(isset($val['batch_date']) && $val['batch_date'] != '')
            {
                $get_batch_date[$key]['batch_date'] = date('m/d/Y',strtotime($val['batch_date']));
            }
        }
    }
    echo json_encode($get_batch_date);
}
    
?>
