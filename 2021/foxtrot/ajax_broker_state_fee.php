<?php

require_once("include/config.php");
require_once(DIR_FS."islogin.php");

if(isset($_POST['broker_id']) && $_POST['broker_id'] != '')
{   
     $instance = new broker_master();
     $res=$instance->load_broker_state_fee($_POST['broker_id']);
      $feeData= (array) $instance->load_broker_state_fee($_POST['broker_id']);
     $html='';
     $client_maintenance_instance = new client_maintenance();
     $get_states = $client_maintenance_instance->select_state();

     foreach($get_states as $state): 
        $feevalue  = isset($feeData[$state['id']]) ? $feeData[$state['id']]:"0.00";
        $html.='<div class="col-md-3"><div class="form-group"><input maxlength="8"  style="WIDTH: 50%;FLOAT: LEFT;MARGIN-RIGHT: 10PX;" type="text" class="currency-input form-control" name="state_fee['.$state['id'].']" value="'.$feevalue.'"><label>'.$state['name'].'</label>  </div> </div> ';   
        endforeach;                   
      echo json_encode(array("status"=>true,"message"=>"done","data"=>$html));
}    
else{
      echo json_encode(array("status"=>false,"message"=>"Please Select Broker first"));
}
die();