<div class="container">
<h1 class="<?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'topfixedtitle';}?>">Maintain Adjustments</h1>
  <div class="col-lg-12 well <?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'fixedwell';}?>">
    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
    <?php 
    if($action=='add_new'||($action=='edit' && $id>0)){
    ?>
    <form method="post">
        <div class="panel-overlay-wrap">
            <div class="panel">
               <div class="panel-heading">
                    <div class="panel-control" style="float: right;">
        				<div class="btn-group dropdown">
        					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
        					<ul class="dropdown-menu dropdown-menu-right" style="">
        						<li><a href="<?php echo CURRENT_PAGE; ?>?action=view"><i class="fa fa-eye"></i> View List</a></li>
        					</ul>
        				</div>
        			</div>
                    <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action=='add_new'?'Add':'Edit'; ?> Adjustments</h3>
        	   </div>
               <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Adjustment Amount <span class="text-red"> (Deductions Must be Entered with a Negative Sign)</span></label>
                                <input type="number" name="adjustment_amount" id="adjustment_amount" class="form-control" value="<?php echo $adjustment_amount;?>" />
                            </div>
                        </div>
                   </div> 
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date </label>
                                <div id="demo-dp-range">
                                    <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" name="date" id="date" class="form-control" value="<?php if($date != '' && $date != '0000-00-00'){ echo date('m/d/Y',strtotime($date)); }?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pay On\After Payroll </label>
                                <div id="demo-dp-range">
                                    <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" name="pay_date" id="pay_date" class="form-control" value="<?php if($pay_date != '' && $pay_date != '0000-00-00'){ echo date('m/d/Y',strtotime($pay_date)); }?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                   </div> 
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>G/L Account </label>
                                <input type="text" name="account" id="account" class="form-control" value="<?php echo $account;?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Expire </label>
                                <div id="demo-dp-range">
                                    <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" name="expire_date" id="expire_date" class="form-control" value="<?php if($expire_date != '' && $expire_date != '0000-00-00'){ echo date('m/d/Y',strtotime($expire_date)); }?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                   </div> 
                   <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Category </label>
                                <select class="form-control" name="payroll_category" id="payroll_category">
                                    <option value="0">Select Category</option>
                                     <?php foreach($get_payroll_category as $key=>$val){?>
                                    <option value="<?php echo $val['id'];?>" <?php if($payroll_category != '' && $payroll_category==$val['id']){echo "selected='selected'";} ?>><?php echo $val['type'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp; </label><br />
                                <a href="<?php echo SITE_URL.'payroll_adjustment.php?action=add_new';?>" class="btn btn-default"><i class="fa fa-plus"></i> Add New Category</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>&nbsp; </label><br />
                                <input type="checkbox" class="checkbox" name="taxable_adjustment" id="taxable_adjustment" style="display: inline;" value="1" <?php if($taxable_adjustment==1){echo "checked='checked'";}else if(!isset($taxable_adjustment) || $taxable_adjustment == ''){echo "checked='checked'";}?>/> Taxable Adjustment
                            </div>
                        </div>
                   </div> 
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Broker </label>
                                <div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
                                    <input type="radio" class="radio" name="broker" id="broker_1" style="display: inline;" value="1" <?php if($broker==1){echo "checked='checked'";}else if($broker==0){echo "checked='checked'";}?>/> All Active Brokers<br />
                                    <input type="radio" class="radio" name="broker" id="broker_2" style="display: inline;" value="2" <?php if($broker==2){echo "checked='checked'";}?>/> Choose A Broker
                                <div id="choose_broker_div" class="row" style="<?php if($broker!=2){echo "display:none";}?>">
                                    <div class="col-md-2">
                                        <input type="number" name="broker_number_dis" id="broker_number_dis" class=" form-control" value="<?php echo $broker_number;?>" disabled="true"/>
                                        <input type="hidden" name="broker_number" id="broker_number" class=" form-control" value="<?php echo $broker_number;?>"/>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="col-md-6 form-control" name="broker_name" id="broker_name">
                                            <option value="">Select Broker</option>
                                            <?php foreach($get_broker as $key=>$val){?>
                                            <option value="<?php echo $val['id'];?>" <?php if($broker_name != '' && $broker_name==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'].' '.$val['last_name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>&nbsp; </label><br />
                                <div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
                                <input type="checkbox" class="checkbox" name="recurring" id="recurring" style="display: inline;" value="1"  <?php if($recurring>0){echo "checked='checked'";}?>/> Recurring<br /><br />
                                    
                                    <select class="form-control" name="recurring_type" id="recurring_type" style="<?php if($recurring!=1){echo "display:none";}?>">
                                        <?php foreach($get_recurring_type as $key=>$val){?>
                                        <option value="<?php echo $val['id'];?>" <?php if($recurring_type != '' && $recurring_type==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>  
                   </div> 
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description </label><br />
                                <input class="form-control" type="text" name="description" id="description" value="<?php echo $description;?>"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pay Type </label><br />
                                <select class="form-control" name="pay_type" id="pay_type">
                                    <?php foreach($get_pay_type as $key=>$val){?>
                                    <option value="<?php echo $val['id'];?>" <?php if($pay_type != '' && $pay_type==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp; </label><br />
                                <input type="number" name="pay_amount" id="pay_amount" class="form-control" value="<?php echo $pay_amount;?>" />
                            </div>
                        </div>
                   </div> 
               </div>                                                              
               <div class="panel-overlay">
                   <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
               </div>
            </div>
        </div>
        <div class="panel-footer"><br />
            <div class="selectwrap">
				<div class="selectwrap">
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                    <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
					<input type="submit" name="submit"  value="Save" style="float: right;"/>
                </div>
            </div>
        </div>
    </form>
        <?php
    }else{?>
    <div class="panel">
	<div class="panel-body">
        <form method="post" id="maintain_adjustment_frm">
        <div class="table-responsive">
			<table id="data-table" class="table table-striped1 table-bordered" cellspacing="0" width="100%">
	            <thead>
	                <tr>
                        <th><input type="checkbox" class="checkbox" name="delete_all" id="delete_all" style="display: inline;" value="1" /></th>
                        <th>Date</th>
                        <th>Broker Name</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Recurring</th>
                        <th class="text-center">ACTION</th>
                    </tr>
	            </thead>
	            <tbody>
                 <?php
                $count = 0;
                foreach($return as $key=>$val){
                    ?>
	                   <tr>
                            <td><input type="checkbox" class="checkbox delete_selected_class" name="delete[<?php echo $val['id']; ?>]" id="delete[<?php echo $val['id']; ?>]" style="display: inline;" value="1" /></td>
                            <td><?php echo date('m/d/Y',strtotime($val['date'])); ?></td>
                            <td><?php echo $val['broker_firstname'].' '.$val['broker_lastname']; ?></td>
                            <td><?php echo $val['adjustment_amount']; ?></td>
                            <td><?php echo $val['category']; ?></td>
                            <td><?php echo $val['description']; ?></td>
                            <td><?php echo $val['recurring_type']; ?></td>
                            <td class="text-center">
                                <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=<?php echo $val['id'];?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        </form>
    </div>
    </div>
    <?php } ?>
 </div>
</div>
<script type="text/javascript">
//datatable bootstrap
    $(document).ready(function() {
        $('#data-table').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar"><"toolbar1">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0,-1 ] }, 
                        { "bSearchable": false, "aTargets": [ 0,-1 ] }]
        });
        $("div.toolbar1").html('<div class="panel-control">'+
                    '<button type="submit" class="btn btn-default" name="delete_selected" id="delete_selected" value="Delete Selected"><i class="fa fa-trash-o"></i> Delete Selected</button>'+
                '</div>');
        $("div.toolbar").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
} );
//date format
$('#demo-dp-range .input-daterange').datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
        }).on('show',function(){
            $(".datepicker-dropdown").css("z-index",'1000000');
        });
//for delete confirm dialog
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
$('#recurring').click(function() {
   if($('#recurring').is(':checked')) { 
    $('#recurring_type').css('display','block'); 
   }
   else
   {
    $('#recurring_type').css('display','none');
   }
});
$('#broker_2').click(function() {
   if($('#broker_2').is(':checked')) { 
    $('#choose_broker_div').css('display','block'); 
   }
});
$('#broker_1').click(function() {
   $('#broker_name').val("");
   $('#choose_broker_div').css('display','none'); 
});
$('#broker_name').change(function() {
    $("#broker_number").val(this.value);
    $("#broker_number_dis").val(this.value);
});
$('#payroll_category').change(function() {
    var desc = $( "#payroll_category option:selected" ).text();
    $("#description").val(desc);
});
$('#delete_all').click(function() {
    $(".delete_selected_class").prop("checked", $("#delete_all").prop("checked"));
});
</script>
<style>
.toolbar {
    float: right;
    padding-left: 5px;
}
.toolbar1 {
    float: left;
}
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
}
</style>