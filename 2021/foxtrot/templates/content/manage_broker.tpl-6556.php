
<script>
function addMoreDocs(){
    $('#demo-dp-range .input-daterange').datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
    });
    var html = '<tr class="tr">'+
                    '<td>'+
                        '<input type="checkbox" name="data[docs_receive][]" class="checkbox" value="1" id="docs_receive"/>'+
                    '</td>'+
                    '<td>'+
                        '<input class="form-control" value="" name="data[docs_description][]" id="docs_description" type="text" />'+
                    '</td>'+
                    '<td>'+
                        '<div id="demo-dp-range">'+
                            '<div class="input-daterange input-group" id="datepicker">'+
                                '<input type="text" name="data[docs_date][]" id="docs_date" value="" class="form-control" />'+
                            '</div>'+
                        '</div>'+
                    '</td>'+
                    '<td>'+
                        '<input type="checkbox" name="data[docs_required][]" class="checkbox" value="1" id="docs_required"/>'+
                    '</td>'+
                    '<td>'+
                        '<button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>'+
                    '</td>'+
                '</tr>';
                
            
    $(html).insertAfter('#add_row_docs');
}
$(document).on('click','.remove-row',function(){
    $(this).closest('.tr').remove();
});
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
<div class="container">
<h1>Broker Maintenance</h1>
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
<div class="col-lg-12 well">
    <div class="tab-content col-md-12">
         <?php
        if($action=='add_new'||($action=='edit' && $id>0)){
            ?>
        <ul class="nav nav-tabs">
          <!--<li class="active"><a href="#tab_default" data-toggle="pill">Home</a></li>-->
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="general"){ echo "active"; }else if(!isset($_GET['tab'])){echo "active";}else{ echo '';} ?>"><a href="#tab_a" data-toggle="pill">General</a></li>
          <?php if(isset($_SESSION['last_insert_id']) && $_SESSION['last_insert_id']!=''){?>
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="payouts"){ echo "active"; } ?>"><a href="#tab_b" data-toggle="pill">Payouts</a></li>
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="charges"){ echo "active"; } ?>"><a href="#tab_c" data-toggle="pill">Charges</a></li>
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="licences"){ echo "active"; } ?>"><a href="#tab_d" data-toggle="pill">Licences</a></li>
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="registers"){ echo "active"; } ?>"><a href="#tab_e" data-toggle="pill">Registers</a></li>
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="required_docs"){ echo "active"; } ?>"><a href="#tab_f" data-toggle="pill">Required Docs</a></li>
          <?php }else{ ?>
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="payouts"){ echo "active"; } ?>" style="pointer-events: none;"><a href="#tab_b" data-toggle="pill">Payouts</a></li>
          <li class="<<?php if(isset($_GET['tab'])&&$_GET['tab']=="charges"){ echo "active"; } ?>"  style="pointer-events: none;"><a href="#tab_c" data-toggle="pill">Charges</a></li>
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="licences"){ echo "active"; } ?>" style="pointer-events: none;"><a href="#tab_d" data-toggle="pill">Licences</a></li>
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="registers"){ echo "active"; } ?>" style="pointer-events: none;"><a href="#tab_e" data-toggle="pill">Registers</a></li>
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="required_docs"){ echo "active"; } ?>" style="pointer-events: none;"><a href="#tab_f" data-toggle="pill">Required Docs</a></li>
          <?php } ?>
       </ul><br />
       <div class="row">
            <div class="col-md-3" style="float: right;">
                <div class="form-group">
                    <a href="#client_notes" data-toggle="modal"><input type="button" name="notes" value="Notes" /></a>
                    <a href="#client_transactions" data-toggle="modal"><input type="button" name="transactions" value="Transactions" /></a>
                    <a href="#client_attachment" data-toggle="modal"><input type="button" name="attach" value="Attach" /></a><br />
                </div>
             </div>
         </div>
                              
            <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="general"){ echo "active"; }else if(!isset($_GET['tab'])){echo "active";}else{ echo '';} ?>" id="tab_a">
                <form method="post">
                            <div class="panel-overlay-wrap">
                                <div class="panel">
                					<div class="panel-heading">
                                        <div class="panel-control" style="float: right;">
                							<div class="btn-group dropdown">
                								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                								<ul class="dropdown-menu dropdown-menu-right" style="">
                									<li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-eye"></i> View List</a></li>
                								</ul>
                							</div>
                						</div>
                                        <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action=='add_new'?'Add':'Edit'; ?> New Broker/Advisor</h3>
                					</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>First Name </label>
                                                    <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Last Name </label><span class="text-red">*</span>
                                                    <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>" class="form-control" />
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Middle Name </label>
                                                    <input type="text" name="mname" id="mname" value="<?php echo $mname; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Suffix </label>
                                                    <input type="text" name="suffix" id="suffix" value="<?php echo $suffix; ?>" class="form-control" />
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Fund/Clear </label>
                                                    <input type="text" name="fund" id="fund" value="<?php echo $fund; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Internal </label>
                                                    <input type="text" name="internal" id="internal" value="<?php echo $internal; ?>" class="form-control" />
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>SSN </label>
                                                    <input type="text" name="ssn" value="<?php echo $ssn; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tax Id </label>
                                                    <input type="text" name="tax_id" id="tax_id" value="<?php echo $tax_id; ?>" class="form-control" />
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>CRD </label>
                                                    <input type="text" name="crd" id="crd" value="<?php echo $crd; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Active status</label>
                                                    <select name="active_status_cdd" id="active_status_cdd" class="form-control">
                                                        <option value="">Select Status</option>
                                                        <option <?php if(isset($active_status_cdd) && $active_status_cdd == 1){echo "selected='selected'";}?> value="1">Active</option>
                                                        <option <?php if(isset($active_status_cdd) && $active_status_cdd == 2){echo "selected='selected'";}?> value="2">Terminated</option>
                                                        <option <?php if(isset($active_status_cdd) && $active_status_cdd == 3){echo "selected='selected'";}?> value="3">Retired</option>
                                                        <option <?php if(isset($active_status_cdd) && $active_status_cdd == 4){echo "selected='selected'";}?> value="4">Deceased</option>
                                                    </select>
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Pay Method </label>
                                                    <select name="pay_method" id="pay_method" class="form-control">
                                                        <option value="">Select Pay Type</option>
                                                        <option <?php if(isset($pay_method) && $pay_method == 1){echo "selected='selected'";}?> value="1">ACH</option>
                                                        <option <?php if(isset($pay_method) && $pay_method == 2){echo "selected='selected'";}?> value="2">Check</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Branch Manager </label><br />
                                                    <input type="checkbox" class="checkbox" name="branch_manager" value="1" id="branch_manager" class="regular-checkbox big-checkbox" <?php if($branch_manager == 1){echo "checked='true'";} ?> /><label for="checkbox-2-1"></label>
                                                </div>
                                            </div>
                                       </div>
                                 <div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Home/Business</label>
                                                <select name="home_general" id="home_general" class="form-control">
                                                    <option <?php if(isset($home)&& $home == 0){ ?>selected="true"<?php } ?>  value="">Select Option</option>
                                                    <option <?php if(isset($home)&& $home == 1){ ?>selected="true"<?php } ?> value="1">Home</option>
                                                    <option <?php if(isset($home)&& $home == 2){ ?>selected="true"<?php } ?> value="2">Business</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Address 1 </label>
                                                <input type="text" name="address1_general" id="address1_general" value="<?php if($action=='edit'){ echo $address1; } ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Address 2 </label>
                                                <input type="text" name="address2_general" id="address2_general" value="<?php if($action=='edit'){ echo $address2; } ?>" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City </label>
                                                <input type="text" name="city_general" id="city_general" value="<?php if($action=='edit'){ echo $city; } ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>State </label>
                                                <select name="state_general" id="state_general" class="form-control">
                                                    <option value="">Select State</option>
                                                    <?php foreach($get_state as $statekey=>$stateval){?>
                                                    <option <?php if($action == 'edit' && $state_id == $stateval['id'] ){ echo 'selected="true"';} ?> value="<?php echo $stateval['id']; ?>"><?php echo $stateval['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Zip code </label>
                                                    <input type="number" name="zip_code_general" id="zip_code_general" value="<?php if($action=='edit'){echo $zip_code;} ?>" class="form-control" />
                                                </div>
                                            </div>
                                    </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Telephone </label>
                                                <input type="text" name="telephone_general" id="telephone_general" value="<?php if($action=='edit'){echo $telephone;} ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Cell </label>
                                                <input type="text" name="cell_general" id="cell_general" value="<?php if($action=='edit'){echo $cell;} ?>" class="form-control" />
                                            </div>
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Fax </label>
                                                <input type="text" name="fax_general" id="fax_general" value="<?php if($action=='edit'){ echo $fax;} ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Gender </label>
                                                <select name="gender_general" id="gender_general" class="form-control">
                                                    <option <?php if($gender=="0"){echo 'selected="true"'; }?> value="0">Select Gender</option>
                                                    <option <?php if($gender=="1"){echo 'selected="true"'; }?> value="1">Male</option>
                                                    <option <?php if($gender=="2"){echo 'selected="true"'; }?> value="2">Female</option>
                                                    <option <?php if($gender=="3"){echo 'selected="true"'; }?> value="3">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Status  </label>
                                                <select name="status_general" id="status_general" class="form-control">
                                                    <option <?php if(isset($marital_status) && $marital_status=="0"){echo 'selected="true"'; }?> value="0">Select Status</option>
                                                    <option <?php if(isset($marital_status) && $marital_status=="1"){echo 'selected="true"'; }?> value="1">Single</option>
                                                    <option <?php if(isset($marital_status) && $marital_status=="2"){echo 'selected="true"'; }?> value="2">Married</option>
                                                    <option <?php if(isset($marital_status) && $marital_status=="3"){echo 'selected="true"'; }?> value="3">Divorced</option>
                                                    <option <?php if(isset($marital_status) && $marital_status=="4"){echo 'selected="true"'; }?> value="4">Widowed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Spouse </label>
                                                <input type="text" name="spouse_general" id="spouse_general" value="<?php if($action=='edit'){ echo $spouse; } ?>" class="form-control" />
                                            </div>
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Children </label>
                                                <select name="children_general" id="children_general" class="form-control">
                                                    <option value="0">Select Children</option>
                                                    <?php for($i=1;$i<10;$i++){?>
                                                    <option <?php if($children==$i){echo 'selected="true"'; }?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email 1 </label>
                                                <input type="text" name="email1_general" id="email1_general" value="<?php if($action=='edit'){ echo $email1; } ?>" class="form-control" />
                                            </div>
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email 2 </label>
                                                 <input type="text" name="email2_general" id="email2_general" value="<?php if($action=='edit'){ echo $email2; } ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Web ID </label>
                                                <input type="text" name="web_id_general" id="web_id_general" value="<?php if($action=='edit'){ echo $web_id; } ?>" class="form-control" />
                                            </div>
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Web Password </label><br />
                                                <input type="password" name="web_password_general" id="web_password_general" value="<?php if($action=='edit'){ echo $web_password; } ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>DOB </label>
                                                <div id="demo-dp-range">
					                                <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" name="dob_general" id="dob_general" value="<?php if($action=='edit'){ echo $dob;} ?>" class="form-control" />
					                                </div>
					                            </div>
                                            </div>
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Prospect Date </label><br />
                                                <div id="demo-dp-range">
					                                <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" name="prospect_date_general" id="prospect_date_general" value="<?php if($action=='edit'){ echo $prospect_date; } ?>" class="form-control" />
					                                </div>
					                            </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>U4 </label><br />
                                                <div id="demo-dp-range">
					                                <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" name="u4_general" id="u4_general" value="<?php if($action=='edit'){ echo $u4; } ?>" class="form-control" />
					                                </div>
					                            </div>
                                            </div>
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Reassign Broker </label>
                                                <select name="reassign_broker_general" id="reassign_broker_general" class="form-control">
                                                    <option value="0">Select Days</option>
                                                    <?php for($i=0;$i<1000;$i++){?>
                                                    <option <?php if($reassign_broker==$i){echo 'selected="true"';}?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php } ?>
                                                 </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>U5/Termination Date </label><br />
                                                <div id="demo-dp-range">
    				                                <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" name="u5_general" id="u5_general" value="<?php if($action=='edit'){ echo $u5;} ?>" class="form-control" />
    				                                </div>
    				                            </div>
                                            </div>
                                        </div>
                                   </div>
                                   <div class="row">
                                       <div class="col-md-6">
                                            <div class="form-group">
                                                <label>DBA Name </label><br />
                                                <input type="text" name="dba_name_general" id="dba_name_general" value="<?php if($action=='edit'){ echo $dba_name; } ?>" class="form-control" />
                                            </div>
                                        </div>
                                   </div>
                                   <h3>EFT Information</h3>
                                   <div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>EFT Information </label><br />
                                                <label class="radio-inline">
                                                  <input type="radio" class="radio" name="eft_info_general" <?php if(isset($eft_information) && $eft_information==1){ echo'checked="true"'; }?>   value="1" checked="checked" />Pre-Notes
                                                </label>
                                                <label class="radio-inline">
                                                  <input type="radio" class="radio" name="eft_info_general" <?php if(isset($eft_information) && $eft_information==2){ echo'checked="true"'; }?> value="2" />Direct Deposit
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Start Date </label><br />
                                                <div id="demo-dp-range">
					                                <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" name="start_date_general" id="start_date_general" value="<?php if($action=='edit'){ echo $start_date; } ?>" class="form-control" />
					                                </div>
					                            </div>
                                            </div>
                                        </div>
                                 </div>
                                 
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Transaction Type </label><br />
                                                <label class="radio-inline">
                                                  <input type="radio" class="radio" name="transaction_type_general" <?php if($transaction_type==1){ echo'checked="true"'; }?> value="1"  checked="checked" /> Checking
                                                </label>
                                                <label class="radio-inline">
                                                  <input type="radio" class="radio" name="transaction_type_general" <?php if($transaction_type==2){ echo'checked="true"'; }?> value="2" /> Savings
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Routing </label><br />
                                                <input type="text" name="routing_general" onkeypress='return event.charCode >= 48 && event.charCode <= 57' onblur="return chech()" id="routing_general" value="<?php if($action=='edit'){ echo $routing;} ?>" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Account No </label>
                                                <input type="number" name="account_no_general" id="account_no_general" value="<?php if($action=='edit'){ echo $account_no; } ?>" class="form-control" />
                                            </div>
                                        </div>
                                   </div></div>
                                   <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Professional designations </label><br />
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                      <span class="input-group-addon">
                                                         <input type="checkbox"  name="cfp_general" <?php if(isset($cfp) && $cfp==1){ echo'checked="true"'; }?> id="cfp_general" style="display: inline;" value="1" />
                                                      </span>
                                                      <label class="form-control">CFP</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                      <span class="input-group-addon">
                                                         <input type="checkbox" name="chfp_general" <?php if(isset($chfp) && $chfp==1){ echo'checked="true"'; }?> id="chfp_general" value="1" style="display: inline;" />
                                                      </span>
                                                      <label class="form-control">ChFP</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                      <span class="input-group-addon">
                                                         <input type="checkbox"  name="cpa_general" <?php if(isset($cpa) &&$cpa==1){ echo'checked="true"'; }?> id="cpa_general" value="1" style="display: inline;" />
                                                      </span>
                                                      <label class="form-control">CPA</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                      <span class="input-group-addon">
                                                         <input type="checkbox" name="clu_general" <?php if(isset($clu) &&$clu==1){ echo'checked="true"'; }?> id="clu_general" value="1" style="display: inline;" />
                                                      </span>
                                                      <label class="form-control">CLU</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                      <span class="input-group-addon">
                                                         <input type="checkbox" name="cfa_general" <?php if(isset($cfa) &&$cfa==1){ echo'checked="true"'; }?> id="cfa_general" value="1" style="display: inline;" />
                                                      </span>
                                                      <label class="form-control">CFA</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                      <span class="input-group-addon">
                                                         <input type="checkbox" name="ria_general" <?php if(isset($ria) &&$ria==1){ echo'checked="true"'; }?> id="ria_general" value="1" style="display: inline;" />
                                                      </span>
                                                      <label class="form-control">RIA</label>
                                                    </div>
                                                </div><br />
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                      <span class="input-group-addon">
                                                         <input type="checkbox" name="insurance_general" <?php if(isset($insurance) &&$insurance==1){ echo'checked="true"'; }?> id="insurance_general" value="1" style="display: inline;" />
                                                      </span>
                                                      <label class="form-control">Insurance</label>
                                                    </div>
                                                </div>
                                             </div>
                                        </div>
                                    </div>
                                
                                    </div>
                                    <div class="panel-overlay">
                                        <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="selectwrap">
                                            <input type="hidden" name="id"  value="<?php echo $id; ?>" />
                        					<input type="submit" name="submit" onclick="waitingDialog.show();" value="Save"/>	
                                            <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                        </div>
                                   </div>
                                </div>
                            </div>
                        </form>
                        <?php
                    }else{?>
                    <div class="panel">
            		<div class="panel-heading">
                        <div class="panel-control">
                            <div class="btn-group dropdown" style="float: right;">
                                <button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
            					<ul class="dropdown-menu dropdown-menu-right" style="">
            						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new"><i class="fa fa-plus"></i> Add New</a></li>
            					</ul>
            				</div>
            			</div>
                        <h3 class="panel-title">List</h3>
            		</div>
            		<div class="panel-body">
                        <div class="panel-control" style="float: right;">
                         <form method="post">
                            <input type="text" name="search_text" id="search_text" value="<?php echo $search_text;?>"/>
                            <button type="submit" name="submit" id="submit" value="Search"><i class="fa fa-search"></i> Search</button>
                        </form>
                        </div><br /><br />
                        <div class="table-responsive">
            			<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            	            <thead>
            	                <tr>
                                    <th class="text-center">#NO</th>
                                    <th>Broker Name</th>
                                    <th>Fund</th>
                                    <th>SSN</th>
                                    <th>Tax ID</th>
                                    <th>CRD</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
            	            </thead>
            	            <tbody>
                            <?php
                            $count = 0;
                            foreach($return as $key=>$val){
                                ?>
            	                   <tr>
                                        <td class="text-center"><?php echo ++$count; ?></td>
                                        <td><?php echo $val['first_name']." ".$val['last_name']; ?></td>
                                        <td><?php echo $val['fund']; ?></td>
                                        <!--td><?php echo $val['internal']; ?></td-->
                                        <td><?php echo $val['ssn']; ?></td>
                                        <td><?php echo $val['tax_id']; ?></td>
                                        <td><?php echo $val['crd']; ?></td>
                                        <td>
                                        <?php 
                                        if($val['active_status']==1)
                                        {
                                            echo "Active";
                                        }
                                        else if($val['active_status']==2)
                                        {
                                            echo "Terminated";
                                        }
                                        else if($val['active_status']==3)
                                        {
                                            echo "Retired";
                                        }
                                        else
                                        {
                                            echo "Deceased";
                                        }
                                        ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
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
                <?php if($action!='view'){?> 
                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="payouts"){ echo "active"; } ?>" id="tab_b">
                <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
                    <form method="post">
                        <div class="panel-overlay-wrap">
                            <div class="panel">
                                <div class="panel-control" style="float: right;">
        							<div class="btn-group dropdown">
        								<a href="<?php echo CURRENT_PAGE;?> "><i class="fa fa-mail-forward"></i></a>
        							</div>
        						</div>
                                <ul class=" nav nav-pills nav-stacked col-md-2">
                                    <li class="active"><a href="#" data-toggle="pill">Payouts</a></li>
                                    <li><a href="#" data-toggle="pill">Overrides</a></li>
                                    <li><a href="#" data-toggle="pill">Splits</a></li>
                                </ul>
                                <div class="tab-content col-md-10">                                
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Summarize Trailers </label><br />
                                                <input type="checkbox" class="checkbox" value="1" name="summarize_trailers_general" id="summarize_trailers_general" class="regular-checkbox big-checkbox" /><label for="checkbox-2-1"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Summarize Adjustments: </label><br />
                                                <input type="checkbox" class="checkbox" value="1" name="summarize_direct_imported_trades" id="summarize_direct_imported_trades" class="regular-checkbox big-checkbox" /><label for="checkbox-2-1"></label>
                                            </div>
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Hold Commissions </label><br />
                                                <div id="demo-dp-range">
					                                <div class="input-daterange input-group" id="datepicker">
                                                        <span class="input-group-addon">From</span>
					                                    <input type="text" class="form-control" name="from_date_general" value="<?php echo $from_date ?>" />
					                                    <span class="input-group-addon">To</span>
					                                    <input type="text" class="form-control" name="to_date_general" value="<?php echo $to_date ?>" />
					                                </div>
					                            </div>
                                           </div>
                                        </div>
                                   </div>
                                </div>
                                
                                <div class="panel-overlay">
                                    <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                </div>
                                <div class="panel-footer">
                                    <div class="selectwrap">
                                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    					<input type="submit" name="submit" onclick="waitingDialog.show();" value="Save"/>	
                                        <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                    </div>
                               </div>
                               </div>                               
                            </div>
                        </div>
                    </form>
                 </div>
                 <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="charges"){ echo "active"; } ?>" id="tab_c">
                 <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
                    <form method="post">
                        <div class="panel-overlay-wrap">
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="panel-control" style="float: right;">
            							<div class="btn-group dropdown">
            								<a href="<?php echo CURRENT_PAGE;?> "><i class="fa fa-mail-forward"></i></a>
            							</div>
            						</div>
                                    <h2 class="panel-title" style="font-size: 25px;"><input type="checkbox" class="checkbox" name="pass_through" style="display: inline !important;"/><b> None (Pass Through)</b></h2>
                                </div>
            					<div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h4><b>Non-Managed Accounts</b></h4>
                                           </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h4><b>Managed Accounts</b></h4>
                                           </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                         <div class="col-md-4">
                                            <div class="form-group">
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h4>Clearing</h4>
                                           </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h4>Execution</h4>
                                           </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h4>Clearing</h4>
                                           </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h4>Execution</h4>
                                           </div>
                                        </div>
                                    </div>
                                    <?php 
                                    $broker_charge=$instance->select_broker_charge($id);
                                    //echo "<pre>"; print_r($broker_charge);
                                    $charge_type_arr=$instance->select_charge_type();
                                    foreach($charge_type_arr as $charge_type){
                                        ?>
                                        <div class="row">
                                             <div class="col-md-12">
                                                <div class="form-group">
                                                    <h4><b><?php echo $charge_type['charge_type']; ?></b></h4>
                                                </div>
                                             </div>
                                        </div>
                                        <?php
                                        $charge_name_arr=$instance->select_charge_name($charge_type['charge_type_id']);
                                        foreach($charge_name_arr as $charge_name){
                                        ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h4 style="float: right;"><?php echo $charge_name['charge_name']; ?></h4>
                                                </div>
                                            </div>
                                            <?php
                                            $charge_detail_arr=$instance->select_charge_detail($charge_type['charge_type_id'],$charge_name['charge_name_id']);
                                            foreach($charge_detail_arr as $charge_detail){
                                                
                                                if($charge_detail['account_type']=='1' && $charge_detail['account_process']=='1')
                                                {
                                                    ?>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                          <input class="form-control charge" onkeypress="return isFloatNumber(this,event)" value="<?php echo (isset($broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['1']['1']) && $broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['1']['1']!='')?$broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['1']['1']:'0.00'; ?>" name="inp_type[<?php echo $charge_type['charge_type_id']; ?>][<?php echo $charge_name['charge_name_id']; ?>][1][1]" type="text" />
                                                       </div>
                                                    </div>
                                                    <?php
                                                    if(count($charge_detail_arr)=='2')
                                                    {
                                                        ?>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                           </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                else if($charge_detail['account_type']=='1' && $charge_detail['account_process']=='2')
                                                {
                                                    ?>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                          <input class="form-control charge" onkeypress="return isFloatNumber(this,event)" value="<?php echo (isset($broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['1']['2']) && $broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['1']['2']!='')?$broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['1']['2']:'0.00'; ?>" name="inp_type[<?php echo $charge_type['charge_type_id']; ?>][<?php echo $charge_name['charge_name_id']; ?>][1][2]" type="text" />
                                                       </div>
                                                    </div>
                                                    <?php
                                                }
                                                else if($charge_detail['account_type']=='2' && $charge_detail['account_process']=='1')
                                                {
                                                    ?>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                          <input class="form-control charge" onkeypress="return isFloatNumber(this,event)" value="<?php echo (isset($broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['2']['1']) && $broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['2']['1']!='')?$broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['2']['1']:'0.00'; ?>" name="inp_type[<?php echo $charge_type['charge_type_id']; ?>][<?php echo $charge_name['charge_name_id']; ?>][2][1]" type="text" />
                                                       </div>
                                                    </div>
                                                    <?php
                                                }
                                                else if($charge_detail['account_type']=='2' && $charge_detail['account_process']=='2')
                                                {
                                                    ?>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                          <input class="form-control charge" onkeypress="return isFloatNumber(this,event)" value="<?php echo (isset($broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['2']['2']) && $broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['2']['2']!='')?$broker_charge[$charge_type['charge_type_id']][$charge_name['charge_name_id']]['2']['2']:'0.00'; ?>" name="inp_type[<?php echo $charge_type['charge_type_id']; ?>][<?php echo $charge_name['charge_name_id']; ?>][2][2]" type="text" />
                                                       </div>
                                                    </div>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                       </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="panel-overlay">
                                    <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                </div>
                                <div class="panel-footer">
                                    <div class="selectwrap">
                                        <input type="hidden" name="id"  value="<?php echo $id; ?>" />
                    					<input type="submit" name="charges" onclick="waitingDialog.show();" value="Save"/>	
                                        <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                    </div>
                               </div>
                            </div>
                        </div>
                    </form>
                 </div>
                 <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="licences"){ echo "active"; } ?>" id="tab_d">
                 <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
                    <form method="POST">
                        <div class="panel-overlay-wrap">
                            <div class="panel">
                                <div class="panel-control" style="float: right;">
        							<div class="btn-group dropdown">
        								<a href="<?php echo CURRENT_PAGE;?> "><i class="fa fa-mail-forward"></i></a>
        							</div>
        						</div>
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="<?php echo CURRENT_PAGE; ?>#tab_securities" data-toggle="tab">Securities</a></li>
                                    <li><a href="<?php echo CURRENT_PAGE; ?>#tab_insurance" data-toggle="tab">Insurance</a></li>
                                    <li><a href="<?php echo CURRENT_PAGE; ?>#tab_ria" data-toggle="tab">RIA</a></li>
                                </ul>
                                <div id="my-tab-content" class="tab-content">
                                    <div class="tab-pane active" id="tab_securities">
                                        <form method="post">
                                            <div class="panel-overlay-wrap">
                                                <div class="panel">
                                                   <div class="panel-heading">
                                                        <h4 class="panel-title" style="font-size: 20px;"><input type="checkbox" class="checkbox" name="pass_through" style="display: inline !important;"/> Waive Home State Fee</h4>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Product Category </label>
                                                                    <select name="product_category" id="product_category" class="form-control">
                                                                        <option value="0">Select Category</option>
                                                                        <option value="1">Active</option>
                                                                        <option value="2">Received</option>
                                                                        <option value="1">Terminated</option>
                                                                        <option value="2">Reason</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="type" value="1"/>
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <h4>Active</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <h4>State</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <h4>Fee</h4>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <h4>Received</h4>  
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <h4>Terminated</h4>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <h4>Reason</h4>
                                                               </div>
                                                            </div>
                                                        </div>
                                                        <?php foreach($get_state_new   as $statekey=>$stateval){?>
                                                        <div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <input type="checkbox" name="data1[<?php echo $stateval['id'] ?>][active_check]" value="1" id="data1[<?php echo $stateval['id'] ?>][active_check]" class="checkbox"  />
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                        <label><?php echo $stateval['name']; ?></label>
                                                                    
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <input class="form-control charge" onkeypress="return isFloatNumber(this,event)" value="" name="data1[<?php echo $stateval['id'] ?>][fee]" type="text" />
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <div id="demo-dp-range">
                    					                                <div class="input-daterange input-group" id="datepicker">
                                                                            <input type="text" name="data1[<?php echo $stateval['id'] ?>][received]" id="data1[<?php echo $stateval['id'] ?>][received]" value="" class="form-control" />
                    					                                </div>
                    					                            </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <div id="demo-dp-range">
                					                                <div class="input-daterange input-group" id="datepicker">
                                                                        <input type="text" name="data1[<?php echo $stateval['id'] ?>][terminated]" id="data1[<?php echo $stateval['id'] ?>][terminated]" value="" class="form-control" />
                					                                </div>
                					                              </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <input class="form-control" value="" name="data1[<?php echo $stateval['id'] ?>][reason]" id="data1[<?php echo $stateval['id'] ?>][reason]" type="text" />
                                                               </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        <?php } ?>
                                                        
                                                       
                                                    </div>
                                                    <div class="panel-overlay">
                                                        <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                                    </div>
                                                    <div class="panel-footer">
                                                        <div class="selectwrap">
                                                            <input type="hidden" name="id"  value="<?php echo $id; ?>" />
                                        					<input type="submit" name="securities" onclick="waitingDialog.show();" value="Save"/>	
                                                            <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                                        </div>
                                                   </div>
                                                </div>
                                            </div>
                                        </form>     
                                     </div>
                                     <div class="tab-pane" id="tab_insurance">
                                        <form method="post">
                                            <div class="panel-overlay-wrap">
                                                <div class="panel">
                                                   <div class="panel-heading">
                                                        <h4 class="panel-title" style="font-size: 20px;"><input type="checkbox" class="checkbox" name="pass_through" style="display: inline !important;"/> Waive Home State Fee</h4>
                                                    </div>
                                                    <input type="hidden" name="type" value="2"/>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <h4>Active</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <h4>State</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <h4>Fee</h4>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <h4>Received</h4>  
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <h4>Terminated</h4>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <h4>Reason</h4>
                                                               </div>
                                                            </div>
                                                        </div>
                                                        <?php foreach($get_state_new as $statekey=>$stateval){?>
                                                        <div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <input type="checkbox" name="data2[<?php echo $stateval['id'] ?>][active_check]" value="1" id="data2[<?php echo $stateval['id'] ?>][active_check]" class="checkbox"  />
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                        <label><?php echo $stateval['name']; ?></label>
                                                                    
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <input class="form-control charge" onkeypress="return isFloatNumber(this,event)" value="" name="data2[<?php echo $stateval['id'] ?>][fee]" type="text" />
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <div id="demo-dp-range">
                    					                                <div class="input-daterange input-group" id="datepicker">
                                                                            <input type="text" name="data2[<?php echo $stateval['id'] ?>][received]" id="data2[<?php echo $stateval['id'] ?>][received]" value="" class="form-control" />
                    					                                </div>
                    					                            </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <div id="demo-dp-range">
                					                                <div class="input-daterange input-group" id="datepicker">
                                                                        <input type="text" name="data2[<?php echo $stateval['id'] ?>][terminated]" id="data2[<?php echo $stateval['id'] ?>][terminated]" value="" class="form-control" />
                					                                </div>
                					                              </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <input class="form-control" value="" name="data2[<?php echo $stateval['id'] ?>][reason]" id="data2[<?php echo $stateval['id'] ?>][reason]" type="text" />
                                                               </div>
                                                            </div>
                                                        </div></div>
                                                        <?php } ?>
                                                        </div>
                                                    <div class="panel-overlay">
                                                        <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                                    </div>
                                                    <div class="panel-footer">
                                                        <div class="selectwrap">
                                                            <input type="hidden" name="id"  value="<?php echo $id; ?>" />
                                        					<input type="submit" name="insurance" onclick="waitingDialog.show();" value="Save"/>	
                                                            <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                                        </div>
                                                   </div>
                                                </div>
                                            </div>
                                        </form>
                                     </div>
                                     <div class="tab-pane" id="tab_ria">
                                        <form method="post">
                                            <div class="panel-overlay-wrap">
                                                <div class="panel">
                                                   <div class="panel-heading">
                                                        <h4 class="panel-title" style="font-size: 20px;"><input type="checkbox" class="checkbox" name="pass_through"  style="display: inline !important;"/> Waive Home State Fee</h4>
                                                    </div>
                                                    <input type="hidden" name="type" value="3"/>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <h4>Active</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <h4>State</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <h4>Fee</h4>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <h4>Received</h4>  
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <h4>Terminated</h4>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <h4>Reason</h4>
                                                               </div>
                                                            </div>
                                                        </div>
                                                        <?php foreach($get_state_new as $statekey=>$stateval){?>
                                                        <div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <input type="checkbox" name="data3[<?php echo $stateval['id'] ?>][active_check]" value="1" id="data3[<?php echo $stateval['id'] ?>][active_check]" class="checkbox"  />
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                        <label><?php echo $stateval['name']; ?></label>
                                                                    
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <input class="form-control charge" onkeypress="return isFloatNumber(this,event)" value="" name="data3[<?php echo $stateval['id'] ?>][fee]" type="text" />
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <div id="demo-dp-range">
                    					                                <div class="input-daterange input-group" id="datepicker">
                                                                            <input type="text" name="data3[<?php echo $stateval['id'] ?>][received]" id="data3[<?php echo $stateval['id'] ?>][received]" value="" class="form-control" />
                    					                                </div>
                    					                            </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                  <div id="demo-dp-range">
                					                                <div class="input-daterange input-group" id="datepicker">
                                                                        <input type="text" name="data3[<?php echo $stateval['id'] ?>][terminated]" id="data3[<?php echo $stateval['id'] ?>][terminated]" value="" class="form-control" />
                					                                </div>
                					                              </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <input class="form-control" value="" name="data3[<?php echo $stateval['id'] ?>][reason]" id="data3[<?php echo $stateval['id'] ?>][reason]" type="text" />
                                                               </div>
                                                            </div>
                                                        </div></div>
                                                        <?php } ?>
                                                        
                                                        </div>
                                                    <div class="panel-overlay">
                                                        <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                                    </div>
                                                    <div class="panel-footer">
                                                        <div class="selectwrap">
                                                            <input type="hidden" name="id"  value="<?php echo $id; ?>" />
                                        					<input type="submit" name="ria" onclick="waitingDialog.show();" value="Save"/>	
                                                            <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                                        </div>
                                                   </div>
                                                </div>
                                            </div>
                                        </form>       
                                     </div>
                                </div>
                            </div>
                        </div>
                    </form>
                 </div>
                 <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="registers"){ echo "active"; } ?>" id="tab_e">
                 <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
                    <form method="post">
                        <div class="panel-overlay-wrap">
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="panel-control" style="float: right;">
            							<div class="btn-group dropdown">
            								<a href="<?php echo CURRENT_PAGE;?> "><i class="fa fa-mail-forward"></i></a>
            							</div>
            						</div>
                                    <h3 class="panel-title" style="font-size: 25px;"><b>Register</b></h3>
                                </div>
            					<div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive" id="table-scroll">
                                                <table class="table table-bordered table-stripped table-hover">
                                                    <thead>
                                                        <th>Series</th>
                                                        <th>License Name / Description</th>
                                                        <th>Approval Date</th>
                                                        <th>Expiration Date</th>
                                                        <th>Reason</th>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($get_register as $regkey=>$regval){?>
                                                        <tr>
                                                            <td><?php echo $regval['id'];?></a></td>
                                                            <td><?php echo $regval['type'];?></td>
                                                            <td>
                                                                <div id="demo-dp-range">
                					                                <div class="input-daterange input-group" id="datepicker">
                                                                        <input type="text" name="data4[<?php echo $regval['id'];?>][approval_date]"  value="" class="form-control" />
                					                                </div>
             					                                </div>
                                                            </td>
                                                            <td>
                                                                <div id="demo-dp-range">
                					                                <div class="input-daterange input-group" id="datepicker">
                                                                        <input type="text" name="data4[<?php echo $regval['id'];?>][expiration_date]" value="" class="form-control" />
                					                                </div>
             					                                </div>
                                                            </td>
                                                            <td><input class="form-control" value="" name="data4[<?php echo $regval['id'];?>][register_reason]"  type="text" /></td>
                                                            <input type="hidden" name="data4[<?php echo $regval['id'];?>][type]" value="<?php echo $regval['type'];?>"/>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                   </div>
                                </div>
                                <div class="panel-overlay">
                                    <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                </div>
                                <div class="panel-footer">
                                    <div class="selectwrap">
                                        <input type="hidden" name="id"  value="<?php echo $id; ?>" />
                    					<input type="submit" name="register" onclick="waitingDialog.show();" value="Save"/>	
                                        <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                    </div>
                               </div>
                            </div>
                        </div>
                    </form>
                 </div>
                 <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="required_docs"){ echo "active"; } ?>" id="tab_f">
                 <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
                    <form method="post">
                        <div class="panel-overlay-wrap">
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="panel-control" style="float: right;">
            							<div class="btn-group dropdown">
            								<a href="<?php echo CURRENT_PAGE;?> "><i class="fa fa-mail-forward"></i></a>
            							</div>
            						</div>
                                    <h3 class="panel-title" style="font-size: 25px;"><b>Required Documents</b></h3>
                                </div>
            					<div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive" id="table-scroll">
                                                <table class="table table-bordered table-stripped table-hover">
                                                    <thead>
                                                        <th>Received</th>
                                                        <th>Description</th>
                                                        <th>Date</th>
                                                        <th>Required</th>
                                                        <th>Add/Remove</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr id="add_row_docs">
                                                            <td>
                                                                <input type="checkbox" name="data[docs_receive][]" class="checkbox" id="docs_receive"/>
                                                            </td>
                                                            <td><input class="form-control" value="" name="data[docs_description][]" id="docs_description" type="text" /></td>
                                                            <td>
                                                                <div id="demo-dp-range">
                					                                <div class="input-daterange input-group" id="datepicker">
                                                                        <input type="text" name="data[docs_date][]" id="docs_date" value="" class="form-control" />
                					                                </div>
             					                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="data[docs_required][]" class="checkbox" id="docs_required"/>
                                                            </td>
                                                            <td>
                                                                <button type="button" onclick="addMoreDocs();" class="btn btn-purple btn-icon btn-circle"><i class="fa fa-plus"></i></button>
                                                            </td>
                                                        </tr>
                                                  </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="panel-footer">
                                            <div class="selectwrap">
                                                <input type="hidden" name="id"  value="<?php echo $id; ?>" />
                            					<input type="submit" name="req_doc" onclick="waitingDialog.show();" value="Save"/>	
                                                <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </form>
                 </div>
                 <?php }?>
                 <div id="client_notes" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header" style="margin-bottom: 0px !important;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h4 class="modal-title">Client's Notes</h4>
				</div>
				<div class="modal-body">
                <form method="post" id="add_client_notes" name="add_client_notes" onsubmit="return notessubmit();">
                <div class="inputpopup">
                    <a class="btn btn-sm btn-success" style="float: right !important; margin-right: 5px !important;" onclick="addMoreNotes();"><i class="fa fa-plus"></i> Add New</a></li>
    			</div>
                
                <div class="col-md-12">
                    <div id="msg">
                    </div>
                </div>
                
                <div class="inputpopup">
                    <div class="table-responsive" id="table-scroll" style="margin: 0px 5px 0px 5px;">
                        <table class="table table-bordered table-stripped table-hover">
                            <thead>
                                <th>#NO</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Notes</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            <?php foreach($get_notes as $key=>$val){?>
                                <tr id="add_row_notes">
                                    <td>1</td>
                                    <td><?php echo date('d/m/Y',strtotime($val['date']));?></td>
                                    <input type="hidden" name="date" id="date" value="<?php echo date('d/m/Y');?>"/>
                                    <td><?php echo $val['user_id'];?></td>
                                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id'];?>"/>
                                    <td><input type="text" name="client_note" class="form-control" id="client_note" value="<?php echo $val['notes'];?>"/></td>
                                    <td class="text-center">
                                       <input type="hidden" name="notes_id" id="notes_id" value=""/>
                                       <input type="hidden" name="add_notes" value="Add Notes" />
							           <button type="submit" class="btn btn-sm btn-warning" name="add_notes" value="Add Notes"><i class="fa fa-save"></i> Save</button>
                                       <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=" class="btn btn-sm btn-primary" ><i class="fa fa-edit"></i> Edit</a>
                                       <a href="<?php echo CURRENT_PAGE; ?>?action=delete&id=" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                    </div>
				</div>
                </form>
                </div><!-- End of Modal body -->
				</div><!-- End of Modal content -->
				</div><!-- End of Modal dialog -->
		</div><!-- End of Modal -->
        <!-- Lightbox strart -->							
			<!-- Modal for transaction list -->
			<div id="client_transactions" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header" style="margin-bottom: 0px !important;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h4 class="modal-title">Client's Transactions</h4>
				</div>
				<div class="modal-body">
                <form method="post">
                <div class="inputpopup">
                    <div class="table-responsive" id="table-scroll" style="margin: 0px 5px 0px 5px;">
                        <table class="table table-bordered table-stripped table-hover">
                            <thead>
                                <th>#NO</th>
                                <th>Trade No</th>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Client No</th>
                                <th>Trade Amount</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>30</td>
                                    <td>28/11/2017</td>
                                    <td>Electronics</td>
                                    <td>20</td>
                                    <td>$200</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>30</td>
                                    <td>28/11/2017</td>
                                    <td>Mobile accessories</td>
                                    <td>20</td>
                                    <td>$200</td>
                                </tr>
                          </tbody>
                        </table>
                    </div>
				</div>
                </form>
                </div><!-- End of Modal body -->
				</div><!-- End of Modal content -->
				</div><!-- End of Modal dialog -->
		  </div><!-- End of Modal -->
          <!-- Lightbox strart -->							
			<!-- Modal for joint account -->
			
        <!-- Lightbox strart -->							
			<!-- Modal for add joint account -->
			<div id="add_joint_account" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header" style="margin-bottom: 0px !important;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h4 class="modal-title">Add Joint Account</h4>
				</div>
				<div class="modal-body">
                <form method="post" id="add_new_note" name="add_new_note" onsubmit="return formsubmit_addnotes();">
                    <div class="inputpopup">
    					<label>Joint Name:<span>*</span></label>
                        <input type="text" name="joint_name" id="joint_name" class="form-control" />
    				</div>
                    <div class="inputpopup">
    					<label>SSN:<span>*</span></label>
                        <input type="text" name="ssn"  class="form-control" />
    				</div>
                    <div class="inputpopup">
    					<label>DOB:<span>*</span></label>
                        <input type="text" name="dob" id="dob" class="form-control" />
                    </div>
                    <div class="inputpopup">
    					<label>Income:<span>*</span></label>
                        <input type="text" name="income" id="income" class="form-control" />
    				</div>
                    <div class="inputpopup">
    					<label>Occupation:<span>*</span></label>
                        <input type="text" name="occupation" id="occupation" class="form-control" />
    				</div>
                    <div class="inputpopup">
    					<label>Position:<span>*</span></label>
                        <input type="text" name="position" id="position" class="form-control" />
    				</div>
                    <div class="inputpopup">
    					<label>Securities-Related Firm?:<span>*</span></label>
                        <input type="checkbox" name="security_related_firm" id="security_related_firm" class="checkbox" />
    				</div>
                    <div class="inputpopup">
    					<label>Employer:<span>*</span></label>
                        <input type="text" name="employer" id="employer" class="form-control" />
    				</div>
                    <div class="inputpopup">
    					<label>Emp. Address:<span>*</span></label>
                        <input type="text" name="employer_add" id="employer_add" class="form-control" />
    				</div>
    				<div class="col-md-12">
                        <div id="msg">
                        </div>
                    </div>
                   	<div class="inputpopup">
    				<label class="labelblank">&nbsp;</label>
                        <input type="hidden" name="submit" value="Ok" />
    					<input type="submit" onclick="waitingDialog.show();" value="Ok" name="submit" />
    				</div>
				</form>					
                
				</div><!-- End of Modal body -->
				</div><!-- End of Modal content -->
			</div><!-- End of Modal dialog -->
		</div><!-- End of Modal -->
        <!-- Lightbox strart -->							
			<!-- Modal for attach -->
			<div id="client_attachment" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header" style="margin-bottom: 0px !important;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h4 class="modal-title">Attachments</h4>
				</div>
				<div class="modal-body">
                <form method="post">
                <div class="inputpopup">
                    <a class="btn btn-sm btn-success" style="float: right !important; margin-right: 5px !important;" onclick="addMoreAttach();"><i class="fa fa-plus"></i> Add New</a></li>
    			</div>
                <div class="inputpopup">
                    <div class="table-responsive" id="table-scroll" style="margin: 0px 5px 0px 5px;">
                        <table class="table table-bordered table-stripped table-hover">
                            <thead>
                                <th>#NO</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Files Name</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                <tr id="add_row_attach">
                                    <td>1</td>
                                    <td><?php echo date('d/m/Y');?></td>
                                    <td><?php echo $_SESSION['user_name'];?></td>
                                    <td><input type="file" name="attach" class="form-control" id="attach"/></td>
                                    <td class="text-center">
                                       <a href="<?php echo CURRENT_PAGE; ?>?action=add&id=" class="btn btn-sm btn-warning" onclick="waitingDialog.show();"><i class="fa fa-save"></i> Ok</a>
                                       <a href="<?php echo CURRENT_PAGE; ?>?action=download&id=" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Download</a>
                                       <a href="<?php echo CURRENT_PAGE; ?>?action=delete&id=" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                          </tbody>
                        </table>
                    </div>
				</div>
                </form>
                </div><!-- End of Modal body -->
				</div><!-- End of Modal content -->
				</div><!-- End of Modal dialog -->
		  </div><!-- End of Modal -->
          </div>
   </div>
</div>
<style>
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
}
#table-scroll {
  height:300px;
  overflow:auto;  
  margin-top:20px;
}
</style>
<script>
/*function addMoreDocs(){
    var html = '<div class="row">'+
                    '<div class="col-md-4">'+
                        '<div class="form-group">'+
                            '<label></label><br />'+
                            '<input type="text" name="account_no[]" id="account_no" class="form-control" />'+
                        '</div>'+
                    '</div>'+
                    
                    '<div class="col-md-4">'+
                        '<div class="form-group">'+
                            '<label></label><br />'+
                            '<select class="form-control" name="sponsor[]">'+
                            '<option value="">Select Sponsor</option>'+
                            <?php foreach($get_sponsor as $key=>$val){?>
                            '<option value="<?php echo $val['id'];?>" <?php if($sponsor_company != '' && $sponsor_company==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>'+
                            <?php } ?>
                            '</select>'+
                        '</div>'+
                        /*'<div class="form-group">'+
                            '<label></label><br />'+
                            '<input type="text" name="company" id="company" class="form-control" />'+
                        '</div>'+*/
                    /*'</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label></label><br />'+
                            '<button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>'+
                        '</div>'+
                    '</div>'+
                '</div>';
                
            
    $(html).insertAfter('#account_no_row');
}
$(document).on('click','.remove-row',function(){
    $(this).closest('.row').remove();
});*/
</script>
<script>
function addMoreNotes(){
    var html = '<tr class="add_row_notes">'+
                    '<td>2</td>'+
                    '<td><?php echo date('d/m/Y');?></td>'+
                    '<input type="hidden" name="date" id="date" value="<?php echo date('d/m/Y');?>"/>'+
                    '<td><?php echo $_SESSION['user_name'];?></td>'+
                    '<input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id'];?>"/>'+
                    '<td><input type="text" name="client_note" class="form-control" id="client_note"/></td>'+
                    '<td class="text-center">'+
                    '<input type="hidden" name="notes_id" id="notes_id" value=""/>'+
                    '<input type="hidden" name="add_notes" value="Add Notes" />'+
                    '<button type="submit" class="btn btn-sm btn-warning" name="add_notes" value="Add Notes"><i class="fa fa-save"></i> Save</button>&nbsp;'+
                    '<a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;'+
                    '<a href="<?php echo CURRENT_PAGE; ?>?action=delete&id=" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>'+
                    '</td>'+
                '</tr>';
                
            
    $(html).insertBefore('#add_row_notes');
}
$(document).on('click','.remove-row',function(){
    $(this).closest('tr').remove();
});
</script>
<script>
function addMoreAttach(){
    var html = '<tr class="add_row_attach">'+
                    '<td>2</td>'+
                    '<td><?php echo date('d/m/Y');?></td>'+
                    '<td><?php echo $_SESSION['user_name'];?></td>'+
                    '<td><input type="file" name="attach" class="form-control" id="attach"/></td>'+
                    '<td class="text-center">'+
                    '<a href="<?php echo CURRENT_PAGE; ?>?action=add&id=" class="btn btn-sm btn-warning"><i class="fa fa-save"></i> Ok</a>&nbsp;'+
                    '<a href="<?php echo CURRENT_PAGE; ?>?action=download&id=" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Download</a>&nbsp;'+
                    '<a href="<?php echo CURRENT_PAGE; ?>?action=delete&id=" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>'+
                    '</td>'+
                '</tr>';
                
            
    $(html).insertBefore('#add_row_attach');
}
$(document).on('click','.remove-row',function(){
    $(this).closest('tr').remove();
});
</script>
<script type="text/javascript">
function validation()
{
    var x = document.forms["frm"]["uname"].value;
    if (x == "") {
        alert("Username must be filled out");
        document.forms["frm"]["uname"].focus();
        return false;
        }
    var x = document.forms["frm"]["pass"].value;
    if (x == "") {
        alert("Password must be filled out");
        document.forms["frm"]["pass"].focus();
        return false;
        }
}


</script>
<script>
$('#demo-dp-range .input-daterange').datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
    });
</script>
<script type="text/javascript">
$(document).ready(function(){
    $('#telephone_general').mask("(999)-999-9999");
});
$(document).ready(function(){
    $('#cell_general').mask("(999)-999-9999");
});
$(document).ready(function(){
    $('#fax_general').mask("(999)-999-9999");
});
</script>
<script>
$("#home_general").on("change", function () {
    document.getElementById("address1_general").value='';
    document.getElementById("address2_general").value='';
    document.getElementById("city_general").value='';
    document.getElementById("state_general").value='';
    document.getElementById("zip_code_general").value='';
});
function chech(){
    var ro=document.getElementById('routing_general').value;
    if (ro != '' && ro.length < 9)
    {
        alert("Enter nine digits.");
        document.getElementById('routing_general').value='';
    return false;
    }
}
(function($) {
$.fn.chargeFormat = function() {
    this.each( function( i ) {
        $(this).change( function( e ){
            if( isNaN( parseFloat( this.value ) ) ) return;
            this.value = parseFloat(this.value).toFixed(2);
        });
    });
    return this; //for chaining
}
})( jQuery );


$( function() {
$('.charge').chargeFormat();
});
</script>