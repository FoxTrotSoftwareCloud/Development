<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
if(isset($_GET['payout_schedule_id']) && $_GET['payout_schedule_id'] >0)
{
$id = isset($_GET['payout_schedule_id'])?$instance->re_db_input($_GET['payout_schedule_id']):0;    
$instance = new payout_schedule_master();
$get_product_category = $instance->select_category();
$select_broker= $instance->select_broker();
$edit_payout = $instance->edit_payout($id);
$edit_grid = $instance->edit_grid($id);//echo '<pre>';//print_r($edit_grid);exit;
$schedule_name = isset($edit_payout['payout_schedule_name'])?$instance->re_db_output($edit_payout['payout_schedule_name']):'';
$transaction_type_general = isset($edit_payout['transaction_type_general'])?$instance->re_db_output($edit_payout['transaction_type_general']):'';
$product_category = isset($edit_payout['product_category'])?$instance->re_db_output($edit_payout['product_category']):'';
$basis = isset($edit_payout['basis'])?$instance->re_db_output($edit_payout['basis']):'';
$cumulative = isset($edit_payout['cumulative'])?$instance->re_db_output($edit_payout['cumulative']):'';
$year = isset($edit_payout['year'])?$instance->re_db_output($edit_payout['year']):'';
$calculation_detail = isset($edit_payout['calculation_detail'])?$instance->re_db_output($edit_payout['calculation_detail']):'';
$clearing_charge_deducted_from = isset($edit_payout['clearing_charge_deducted_from'])?$instance->re_db_output($edit_payout['clearing_charge_deducted_from']):'';
$reset = isset($edit_payout['reset'])?$instance->re_db_output(date('Y-m-d',strtotime($edit_payout['reset']))):'';
$description_type = isset($edit_payout['description_type'])?$instance->re_db_output($edit_payout['description_type']):'';
$minimum_trade_gross = isset($edit_payout['minimum_trade_gross'])?$instance->re_db_output($edit_payout['minimum_trade_gross']):'';
$minimum_12B1_gross = isset($edit_payout['minimum_12B1_gross'])?$instance->re_db_output($edit_payout['minimum_12B1_gross']):'';
$team_member = isset($edit_payout['team_member'])?explode(',',$edit_payout['team_member']):array();
?>
<script type="text/javascript">
var flag=0;
function addlevel(leval){
        
    if(flag==0){
        flag=leval+1;
        }
    else{ flag++ ; }

    var html = '<tr>'+
                    /*'<td>'+
                        '<div class="input-group dollar">'+
                          '<input type="number" name="leval[sliding_rates]['+flag+']" class="form-control" />'+
                          '<span class="input-group-addon">$</span>'+
                        '</div>'+
                    '</td>'+*/
                    '<td>'+
                    '<div class="input-group dollar">'+
                        '<input type="number" name="leval[from]['+flag+']" class="form-control" />'+
                        '<span class="input-group-addon">$</span>'+
                    '</div>'+
                    '</td>'+
                    '<td>'+
                    '<div class="input-group dollar">'+
                        '<input type="number" name="leval[to]['+flag+']" class="form-control" />'+
                        '<span class="input-group-addon">$</span>'+
                    '</div>'+
                    '</td>'+
                    '<td>'+
                        '<input type="number" step="0.001" name="leval[per]['+flag+']" value="" class="form-control" />'+
                    '</td>'+
                    '<td>'+
                        '<button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>'+
                    '</td>'+
                '</tr>';
                
            
    $(html).insertAfter('#add_level');
    
    
    var radio = $('input[name=transaction_type_general]:checked').val();
        if(radio=='1')
        {
            $('.dollar').css('display','');
            $(".dollar").children().prop('disabled',false);
            $(".percentage").children().prop('disabled',true);
            $('.percentage').css('display','none');
        }
        else if(radio == '2'){
           $('.percentage').css('display','');
           $(".percentage").children().prop('disabled',false);
           $(".dollar").children().prop('disabled',true);
           $('.dollar').css('display','none');
        }
        
    
}
$(document).ready(function(){    
    var radio = $('input[name=transaction_type_general]:checked').val();
        if(radio=='1')
        {
            $('.dollar').css('display','');
            $(".dollar").children().prop('disabled',false);
            $(".percentage").children().prop('disabled',true);
            $('.percentage').css('display','none');
        }
        else if(radio == '2'){
           $('.percentage').css('display','');
           $(".percentage").children().prop('disabled',false);
           $(".dollar").children().prop('disabled',true);
           $('.dollar').css('display','none');
        }
        
    });
$(document).on('click','.remove-row',function(){
    $(this).closest('tr').remove();
});
</script>

<div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
    <!--<div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Schedule Name </label><br />-->
                <input type="hidden" name="schedule_name" id="schedule_name" class="form-control" value="<?php if(isset($schedule_name) && $schedule_name!=''){ echo $schedule_name; } ?>"/>
                <input type="hidden" name="schedule_id" id="schedule_id" class="form-control" value="<?php if(isset($id) && $id!=''){ echo $id; } ?>"/>
            <!--</div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Payout on </label><br />
                <label class="radio-inline">
                  <input type="radio" class="radio" name="transaction_type_general" <?php if(isset($transaction_type_general) && $transaction_type_general=='1'){?>checked="true"<?php } ?> value="1"  checked="checked" onclick="display_icon(this.value);"/> Amount
                </label>
                <label class="radio-inline">
                  <input type="radio" class="radio" name="transaction_type_general" <?php if(isset($transaction_type_general) && $transaction_type_general=='2'){?>checked="true"<?php } ?> value="2" onclick="display_icon(this.value);"/> Percentage
                </label>
            </div>
        </div>
   </div>-->
   <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Payout Grid </label>
                <div class="table-responsive">
                    <table class="table table-bordered table-stripped table-hover">
                        <thead>
                            <!--<th>Sliding Rates</th>-->
                            <th>Lower Threshold</th>
                            <th>Upper Threshold</th>
                            <th>Rate</th>
                            <th>Add Level</th>
                        </thead>
                        <tbody>
                                <?php $doc_id1=0; 
                            if(isset($edit_grid) && !empty($edit_grid)){ 
                            foreach($edit_grid as $regkey=>$regval){ $doc_id1++; 
                                    ?>
                                <tr>
                                    <!--<td>
                                        <div class="input-group">
                                          <input type="number" name="leval[sliding_rates][<?php echo $doc_id1;?>]" value="<?php echo $regval['sliding_rates']; ?>" class="form-control" />
                                          <span class="input-group-addon">$</span>
                                        </div>
                                    </td>-->
                                    <td>
                                        <?php if(isset($transaction_type_general) && $transaction_type_general == '1'){?>
                                        <div class="input-group">
                                          <input type="number" name="leval[from][<?php echo $doc_id1;?>]" value="<?php echo $regval['from']; ?>" class="form-control" />
                                          <span class="input-group-addon">$</span>
                                        </div>
                                        <?php } else if(isset($transaction_type_general) && $transaction_type_general == '2'){?>
                                        <div class="input-group">
                                          <input type="number" step="0.001" name="leval[from][<?php echo $doc_id1;?>]" value="<?php echo $regval['from']; ?>" class="form-control" />
                                          <span class="input-group-addon">%</span>
                                        </div>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if(isset($transaction_type_general) && $transaction_type_general == '1'){?>
                                        <div class="input-group">
                                          <input type="number" name="leval[to][<?php echo $doc_id1;?>]" value="<?php echo $regval['to']; ?>" class="form-control" />
                                          <span class="input-group-addon">$</span>
                                        </div>
                                        <?php } else if(isset($transaction_type_general) && $transaction_type_general == '2'){?>
                                        <div class="input-group">
                                          <input type="number" step="0.001" name="leval[to][<?php echo $doc_id1;?>]" value="<?php echo $regval['to']; ?>" class="form-control" />
                                          <span class="input-group-addon">%</span>
                                        </div>
                                        <?php } ?>
                                    </td>
                                    <td>
                                    <input type="number" step="0.001" name="leval[per][<?php echo $doc_id1;?>]" value="<?php echo $regval['per']; ?>" class="form-control" />
                                    </td>
                                    <td>
                                        <button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>
                                        <!--<button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>-->
                                    </td>
                                </tr>
                            <?php } }  $doc_id1++;?>
                                 <tr id="add_level">
                                    <!--<td>
                                        <div class="input-group">
                                          <input type="number" name="leval[sliding_rates][<?php echo $doc_id1;?>]" class="form-control" />
                                          <span class="input-group-addon">$</span>
                                        </div>
                                    </td>-->
                                    <td>
                                        <div class="input-group dollar">
                                        <input type="number"  name="leval[from][<?php  echo $doc_id1;?>]" class="form-control" max="999999999"/>
                                        <span class="input-group-addon">$</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group dollar">
                                        <input type="number" name="leval[to][<?php  echo $doc_id1;?>]" class="form-control" max="999999999"/>
                                        <span class="input-group-addon">$</span>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" step="0.001" name="leval[per][<?php echo $doc_id1;?>]" value="" class="form-control" />
                                    </td>
                                    <td>
                                        <button type="button" onclick="addlevel(<?php  echo $doc_id1;?>);" class="btn btn-purple btn-icon btn-circle"><i class="fa fa-plus"></i></button>
                                    </td>
                                </tr>
                                <?php   ?>
                            
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
   </div>
   </div>
   <!--<div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Apply to Product Categories </label><br />
                <select name="product_category"  class="form-control">
                    <option value="">Select Product Category</option>
                    <option <?php if(isset($product_category) && $product_category=='0'){?> selected="true"<?php } ?> value="0">All Product Categories</option>
                    <?php foreach($get_product_category as $key=>$val){?>
                    <option value="<?php echo $val['id'];?>" <?php if(isset($product_category) && $product_category==$val['id']){?> selected="true"<?php } ?>><?php echo $val['type'];?></option>
                    <?php } ?>
                </select>
           </div>
        </div>
   </div>-->
   <div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
   <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Basis </label><br />
                <!--<input type="radio" name="basis" <?php if(isset($basis) && $basis=='1'){?>checked="true"<?php } ?>  class="radio" style="display: inline;" value="1"/>&nbsp;<label>Net Earnings</label>&nbsp;&nbsp;-->
                <input type="radio" name="basis" <?php if(isset($basis) && $basis=='1'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="1"/>&nbsp;<label>Gross Concessions</label>&nbsp;&nbsp;
                <input type="radio" name="basis" <?php if(isset($basis) && $basis=='2'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="2"/>&nbsp;<label>Principal</label>&nbsp;&nbsp;
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Cumulative </label><br />
                <input type="radio" name="cumulative" <?php if(isset($cumulative) && $cumulative=='1'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="1"/>&nbsp;<label>Payroll-To-Date</label>&nbsp;&nbsp;
                <!--<input type="radio" name="cumulative" <?php if(isset($cumulative) && $cumulative=='2'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="2"/>&nbsp;<label>Month-To-Date</label>&nbsp;&nbsp;-->
                <input type="radio" name="cumulative" <?php if(isset($cumulative) && $cumulative=='2'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="2"/>&nbsp;<label>Year-To-Date</label>&nbsp;&nbsp;
            </div>
        </div>
   </div>
   <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Year </label><br />
                <input type="radio" name="year" <?php if(isset($year) && $year=='1'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="1"/>&nbsp;<label>Calendar</label>&nbsp;&nbsp;
                <input type="radio" name="year" <?php if(isset($year) && $year=='2'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="2"/>&nbsp;<label>Rolling</label>&nbsp;&nbsp;
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Reset </label>
                <div id="demo-dp-range">
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" name="reset"  value="<?php if(isset($reset) && $reset!=''){ echo date('m/d/Y',strtotime($reset)); } ?>"  class="form-control"  />
                        <label class="input-group-addon btn" for="reset">
                           <span class="fa fa-calendar"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
   </div>
   <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Calculation Detail </label><br />
                <input type="radio" name="calculation_detail" <?php if(isset($calculation_detail) && $calculation_detail=='1'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="1"/>&nbsp;<label>Apply Incremental Payout Rate</label>&nbsp;&nbsp;
                <input type="radio" name="calculation_detail" <?php if(isset($calculation_detail) && $calculation_detail=='2'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="2"/>&nbsp;<label>Apply Higher Payout Rate</label>&nbsp;&nbsp;
                <!--<input type="radio" name="calculation_detail" <?php if(isset($calculation_detail) && $calculation_detail=='3'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="3"/>&nbsp;<label>Use Lower Level Rate</label>&nbsp;&nbsp;-->
            </div>
        </div>
        <!--<div class="col-md-6">
            <div class="form-group">
                <label>Clearing Charge Deducted From</label><br />
                <input type="radio" name="clearing_charge_deducted_from" <?php if(isset($clearing_charge_deducted_from) && $clearing_charge_deducted_from=='1'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="1"/>&nbsp;<label>Net</label>&nbsp;&nbsp;
                <input type="radio" name="clearing_charge_deducted_from" <?php if(isset($clearing_charge_deducted_from) && $clearing_charge_deducted_from=='2'){?>checked="true"<?php } ?> class="radio" style="display: inline;" value="2"/>&nbsp;<label>Gross</label>&nbsp;&nbsp;
            </div>
        </div>-->
   </div>
   </div>
   <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Team Name </label>
                <input type="text" name="description_type"  value="<?php if(isset($description_type) && $description_type!=''){ echo $description_type; } ?>"  class="form-control"  />
            </div>
            <!--<div class="form-group">
                <label>Description</label>
                <select name="description_type"  class="form-control">
                    <option value="0">Select Option</option>
                    <option <?php if(isset($description_type) && $description_type=='1'){?>selected="true"<?php }?> value="1">This is product type</option>
                    <option <?php if(isset($description_type) && $description_type=='2'){?>selected="true"<?php }?> value="2">This is product type</option>
                </select>
            </div>-->
        </div>
        <div class="col-md-6">
            <!--<div class="form-group">
                <label>Team Members </label>
                <select name="team_member"  class="multiselect-ui form-control" multiple="multiple" >
                    <option value="">Select Broker</option>
                    <?php foreach($select_broker as $key => $val) {?>
                    <option <?php if(isset($team_member) && $team_member==$val['id']){?>selected="true"<?php }?> value="<?php echo $key?>"><?php echo $val['first_name']?></a></option>
                    <?php } ?>
                </select>
            </div>-->
        <label>Team Members </label>
        <select name="team_member[]" id="team_member" class="form-control chosen-select" multiple="true">
            <option value="" disabled="true">Select Broker</option>
            <?php foreach($select_broker as $key => $val) {?>
                    <option <?php echo in_array($val['id'],$team_member)?'selected="selected"':''; ?> value="<?php echo $val['id'];?>"><?php echo $val['first_name']?></a></option>
            <?php } ?>
        </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Minimum Trade Gross Commission </label>
                <input type="text" name="minimum_trade_gross"  value="<?php if(isset($minimum_trade_gross) && $minimum_trade_gross!=''){ echo $minimum_trade_gross; } ?>"  class="form-control"  />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Minimum 12B1 Gross Commission </label>
                <input type="text" name="minimum_12B1_gross"  value="<?php if(isset($minimum_12B1_gross) && $minimum_12B1_gross!=''){ echo $minimum_12B1_gross; } ?>"  class="form-control"  />
            </div>
        </div>
    </div>
                    
<style>
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;}
</style>
<script type="text/javascript">
var waitingDialog = waitingDialog || (function ($) {
    'use strict';

	// Creating modal dialog's DOM
	var $dialog = $(
		'<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
		'<div class="modal-dialog modal-m">' +
		'<div class="modal-content">' +
			'<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
			'<div class="modal-body">' +
				'<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
			'</div>' +
		'</div></div></div>');

	return {
		/**
		 * Opens our dialog
		 * @param message Custom message
		 * @param options Custom options:
		 * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
		 * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
		 */
		show: function (message, options) {
			// Assigning defaults
			if (typeof options === 'undefined') {
				options = {};
			}
			if (typeof message === 'undefined') {
				message = 'Saving...';
			}
			var settings = $.extend({
				dialogSize: 'm',
				progressType: '',
				onHide: null // This callback runs after the dialog was hidden
			}, options);

			// Configuring dialog
			$dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
			$dialog.find('.progress-bar').attr('class', 'progress-bar');
			if (settings.progressType) {
				$dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
			}
			$dialog.find('h3').text(message);
			// Adding callbacks
			if (typeof settings.onHide === 'function') {
				$dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
					settings.onHide.call($dialog);
				});
			}
			// Opening dialog
			$dialog.modal();
		},
		/**
		 * Closes dialog
		 */
	
	};

})(jQuery);
</script>
<script type="text/javascript">
function display_icon(value)
{
    if(value=='1')
    {
        $('.dollar').css('display','');
        $(".dollar").children().prop('disabled',false);
        $(".percentage").children().prop('disabled',true);
        $('.percentage').css('display','none');
    }
    else if(value == '2'){
        $('.percentage').css('display','');
        $(".percentage").children().prop('disabled',false);
        $(".dollar").children().prop('disabled',true);
        $('.dollar').css('display','none');
    }
    
}
$('body').on('focus',".input-daterange",function(){
    $(this).datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
    });
});
</script>
<link href="<?php echo SITE_PLUGINS; ?>chosen/chosen.min.css" rel="stylesheet" />
<script src="<?php echo SITE_PLUGINS; ?>chosen/chosen.jquery.min.js"></script>

<script type="text/javascript">         
    $(document).ready(function(e){
        $( document ).on( 'click', '.bs-dropdown-to-select-group .dropdown-menu li', function( event ) {
        	var $target = $( event.currentTarget );
    		$target.closest('.bs-dropdown-to-select-group')
    			.find('[data-bind="bs-drp-sel-value"]').val($target.attr('data-value'))
    			.end()
    			.children('.dropdown-toggle').dropdown('toggle');
    		$target.closest('.bs-dropdown-to-select-group')
        		.find('[data-bind="bs-drp-sel-label"]').text($(this).find('a').html());
    		return false;
    	});
        
        $('.sel').trigger('click');
        $('.bs-dropdown-to-select-group').removeClass('open');
        
        $('.chosen-select').chosen();
        
    });
</script>
<?php } ?>