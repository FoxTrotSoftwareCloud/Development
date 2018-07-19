<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");

$instance = new transaction();
$get_broker =$instance->select_broker();
if(isset($_GET['broker_id']) && $_GET['broker_id'] != '')
{
    $get_broker_override_rate = $instance->get_broker_override_rate($_GET['broker_id']);
    if(isset($get_broker_override_rate[0]['rap']) && $get_broker_override_rate[0]['rap'] != '')
    {
        foreach($get_broker_override_rate as $keyedit_=>$valedit){
            $broker = $valedit['rap'];?>
            <tr class="tr broker_override_class"> 
                <td>
                    <select name="receiving_rep[]"  class="form-control">
                        <option value="">Select Broker</option>
                        <?php foreach($get_broker as $key => $val) {?>
                        <option <?php if(isset($broker) && $broker==$val['id']) {?>selected="true"<?php } ?> value="<?php echo $val['id']?>"><?php echo $val['first_name'].' '.$val['last_name']?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" step="0.001" name="per[]" value="<?php echo $valedit['per'];?>" class="form-control" />
                        <span class="input-group-addon">%</span>
                    </div>
                </td>
                <td>
                    <button type="button" tabindex="-1" class="btn remove-row_override btn-icon btn-circle"><i class="fa fa-minus"></i></button>
                </td>
            </tr>
        <?php 
        } 
    }
}
?>