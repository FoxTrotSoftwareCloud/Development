<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");

if(isset($_GET['file_id']) && $_GET['file_id'] != '' && isset($_GET['data_id']) && $_GET['data_id'] != '') 
{
    $client_detail = array();
    $file_id = isset($_GET['file_id'])?$_GET['file_id']:0;
    $data_id = isset($_GET['data_id'])?$_GET['data_id']:0;
    $instance = new import();   
    $get_client_data = $instance->get_client_data($file_id,$data_id);
    if($get_client_data != array())
    {
        foreach($get_client_data as $key=>$val)
        {
            $client_detail['address']=$val['client_address'];
            $client_detail['zipcode']=$val['zip_code'];
        }
    }
    echo json_encode($client_detail);exit;
}   
?>
