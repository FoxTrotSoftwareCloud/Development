<div class="container">
<h1 class="<?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'topfixedtitle';}?>">Broker Balances</h1>
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
                    <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action=='add_new'?'Add':'Edit'; ?> Balance</h3>
        	   </div>
               <div class="panel-body">
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Broker Name </label>
                                <select class="col-md-6 form-control" name="broker_name" id="broker_name">
                                    <option value="">Select Broker</option>
                                    <?php foreach($get_broker as $key=>$val){?>
                                    <option value="<?php echo $val['id'];?>" <?php if($broker_name != '' && $broker_name==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'].' '.$val['last_name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID </label>
                                <input type="text" name="broker_number_dis" id="broker_number_dis" class="form-control" value="<?php echo $broker_number;?>" disabled="true" />
                                <input type="hidden" name="broker_number" id="broker_number" class="form-control" value="<?php echo $broker_number;?>" />
                            </div>
                        </div>
                   </div> 
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Clear# </label>
                                <input type="text" name="clearing_number_dis" id="clearing_number_dis" class="form-control" value="<?php echo $clearing_number;?>" disabled="true" />
                                <input type="hidden" name="clearing_number" id="clearing_number" class="form-control" value="<?php echo $clearing_number;?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Balance Amount </label>
                                <input type="text" name="balance_amount" id="balance_amount" class="form-control" value="<?php echo $balance_amount;?>" />
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
        <div class="table-responsive">
			<table id="data-table" class="table table-striped1 table-bordered" cellspacing="0" width="100%">
	            <thead>
	                <tr>
                        <th>Broker Name</th>
                        <th>ID</th>
                        <th>Clear#</th>
                        <th>Balance Amount</th>
                        <th class="text-center">ACTION</th>
                    </tr>
	            </thead>
	            <tbody>
                 <?php
                $count = 0;
                foreach($return as $key=>$val){
                    ?>
	                   <tr>
                            <td><?php echo $val['broker_firstname'].' '.$val['broker_lastname']; ?></td>
                            <td><?php echo $val['broker_number']; ?></td>
                            <td><?php echo $val['clearing_number']; ?></td>
                            <td><?php echo $val['balance_amount']; ?></td>
                            <td class="text-center"> 
                                <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=<?php echo $val['id'];?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
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
        "dom": '<"toolbar">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ -1 ] }, 
                        { "bSearchable": false, "aTargets": [ -1 ] }]
        });
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
$('#broker_name').change(function() {
    $("#broker_number").val(this.value);
    $("#broker_number_dis").val(this.value);
    var broker_id = this.value;
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data = this.responseText;
            
            $("#clearing_number").val(data);
            $("#clearing_number_dis").val(data);
        }          
    };             
    xmlhttp.open("GET", "ajax_prior_payrolls.php?broker_id="+broker_id, true);
    xmlhttp.send();
});
</script>
<style>
.toolbar {
    float: right;
    padding-left: 5px;
}
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
}
</style>