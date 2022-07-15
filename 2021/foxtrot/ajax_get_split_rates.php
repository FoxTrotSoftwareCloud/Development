<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");

$instance = new transaction();
$get_broker =$instance->select_broker();
if(isset($_GET['client_id']) && $_GET['client_id'] != '')
{
    $return = [];
    $get_client_split_rates = $instance->get_client_split_rate($_GET['client_id'], 1);
    
    if(!empty($get_client_split_rates))
    {
        $instance_rules = new rules();
        $trade_date = isset($_GET['trade_date']) ? $dbins->re_db_input($_GET['trade_date']) : '';
        $product_category = isset($_GET['product_category']) ? $dbins->re_db_input($_GET['product_category']) : '';
        $validDate = $instance_rules->check_date($trade_date, $get_client_split_rates[0]['split_rate_from'],$get_client_split_rates[0]['split_rate_to']);
        
        if ((int)$get_client_split_rates[0]['split_broker'] > 0
            AND (!empty($get_client_split_rates[0]['split_rate']))
            AND ($validDate)
            AND (empty($get_client_split_rates[0]['split_rate_category']) OR $get_client_split_rates[0]['split_rate_category'] == $product_category)
        ){
            $return = ['split_broker'=>$get_client_split_rates[0]['split_broker'],'split_rate'=>$get_client_split_rates[0]['split_rate']];
        }
    }
    echo json_encode($return);
}
if(isset($_GET['broker_id']) && $_GET['broker_id'] != '')
{
    $get_broker_split_rate = $instance->get_broker_split_rate($_GET['broker_id']);
    if(isset($get_broker_split_rate[0]['rap']) && $get_broker_split_rate[0]['rap'] != '')
    {
    foreach($get_broker_split_rate as $keyedit_split=>$valedit_split){
        $split_broker = $valedit_split['rap'];?>
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <select class="form-control" name="split_broker[]">
                    <option value="">Select Broker</option>
                     <?php foreach($get_broker as $key=>$val){?>
                    <option value="<?php echo $val['id'];?>" <?php if($split_broker != '' && $split_broker==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'].' '.$val['middle_name'].' '.$val['last_name'];?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <input type="text" name="split_rate[]" onchange="handleChange(this);"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="account_no" class="form-control decimal" value="<?php echo number_format($valedit_split['rate'],2);?>" />
                <input type="hidden" name="split_client_id[]" id="split_client_id" class="form-control" value="0" />
                <input type="hidden" name="split_broker_id[]" id="split_broker_id" class="form-control" value="<?php echo $_GET['broker_id'];?>" />
                <span class="input-group-addon">%</span>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                 <button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>
            </div>
        </div>
    </div>
    <?php } 
    }
}
?>