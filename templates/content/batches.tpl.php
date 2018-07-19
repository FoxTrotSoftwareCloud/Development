<div class="container">
    <h1 class="<?php if($action=='add_batches'||($action=='edit_batches' && $id>0)){?>topfixedtitle<?php }?>">Batches</h1>
    <div class="col-lg-12 well <?php if($action=='add_batches'||($action=='edit_batches' && $id>0)){ echo 'fixedwell';}?>">
    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <?php  
    
    if((isset($_GET['action']) && $_GET['action']=='add_batches') || (isset($_GET['action']) && ($_GET['action']=='edit_batches' && $id>0))){
        ?>
        <form name="frm2" method="POST">
            <!--<div class="row">
                <div class="col-md-12">
                    <div class="form-group"><br /><div class="selectwrap">
                        <input type="submit" name="batches" onclick="waitingDialog.show();" value="Save"/>	
                        <a href="<?php echo CURRENT_PAGE.'?action=view_batches';?>"><input type="button" name="cancel" value="Cancel" /></a>
                        <?php if($_GET['action']=='edit_batches' && $id>0){?>
                        <a href="report_transaction_by_batch.php?batch_id=<?php echo $id; ?>" target="_blank"><input type="button" name="view_report" value="View Report" /></a>
                        <?php } ?>
                    </div>
                 </div>
                 </div>
             </div> -->
        <div class="panel">            
        
            <div class="panel-footer">
                <div class="selectwrap" style="float: right;">
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                </div>
           </div>
            <div class="panel-heading">
                <div class="panel-control" style="float: right;">
    				<div class="btn-group dropdown">
    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
    					<ul class="dropdown-menu dropdown-menu-right" style="">
    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_batches"><i class="fa fa-eye"></i> View List</a></li>
    					</ul>
    				</div>
    			</div>
                <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action=='add_batches'?'Add':'Edit'; ?> Batches</h3>
    		</div>
            <div class="panel-body">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Batch Number </label><br />
                        <input type="text" maxlength="10" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="batch_number" disabled="true"  value="<?php if(isset($batch_number) && $batch_number != '') {echo $batch_number;}else { echo 'Will be Assigned When Saving';}?>"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Batch Description </label><br />
                        <input type="text" maxlength="40" class="form-control" name="batch_desc" id="batch_desc" value="<?php if(isset($batch_desc)) {echo $batch_desc;}?>"  />
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            $(document).ready(function() {
                document.getElementById("batch_desc").focus();
            });
            </script>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Posted Commission Amount</label><br />
                        <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text"  class="form-control"  maxlength="12" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 ' name="commission_amount1" value="<?php if(isset($commission_amount) && $commission_amount!='') {echo $commission_amount;}else{echo '0';}?>" <?php if(isset($_GET['action']) && ($action=='edit_batches' || $action=='add_batches')){ echo "disabled='true'";}?> />
                        <input type="hidden"  class="form-control"  maxlength="12" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 ' name="commission_amount" value="<?php if(isset($commission_amount) && $commission_amount!='') {echo $commission_amount;}else{echo '0';}?>" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Check Amount </label><br />
                        <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text"  class="form-control" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 '  maxlength="8" name="check_amount" id="check_amount" value="<?php if(isset($check_amount) && $check_amount!='') {echo $check_amount;}else{echo '0';}?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Batch Date </label><br />
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="batch_date" id="batch_date" value="<?php if(isset($batch_date) && ($batch_date != '' && $batch_date != '0000-00-00')) {echo date('m/d/Y',strtotime($batch_date));}?>" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Deposit Date </label><br />
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="deposit_date" id="deposit_date" value="<?php if(isset($deposit_date) && ($deposit_date != '' && $deposit_date != '0000-00-00')) {echo date('m/d/Y',strtotime($deposit_date));}?>" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Product Category <span class="text-red">*</span></label><br />
                        <select class="form-control" name="pro_category">
                            <option value="">Select Category</option>
                            <?php foreach($product_category as $key=>$val){?>
                            <option value="<?php echo $val['id'];?>" <?php if(isset($pro_category) && $pro_category==$val['id']){ ?>selected="true"<?php } ?>><?php echo $val['type'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Sponsor <span class="text-red">*</span></label><br />
                        <select class="form-control" name="sponsor">
                            <option value="">Select Sponsor</option>
                             <?php foreach($get_sponsor as $key=>$val){?>
                            <option value="<?php echo $val['id'];?>" <?php if(isset($sponsor) && $sponsor==$val['id']){?> selected="true"<?php } ?>><?php echo $val['name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Beginning Trade Date</label><br />
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="trade_start_date" id="trade_start_date" value="<?php if(isset($trade_start_date) && ($trade_start_date != '' && $trade_start_date != '0000-00-00')) {echo date('m/d/Y',strtotime($trade_start_date));}?>" class="form-control" <?php if(isset($_GET['action']) && $action=='add_batches'){ echo "disabled='true'";}?>/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ending Trade Date</label><br />
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="trade_end_date" id="trade_end_date" value="<?php if(isset($trade_end_date) && ($trade_end_date != '' && $trade_end_date != '0000-00-00')) {echo date('m/d/Y',strtotime($trade_end_date));}?>" class="form-control" <?php if(isset($_GET['action']) && $action=='add_batches'){ echo "disabled='true'";}?>/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Split between product categories</label><br />
                        <label class="radio-inline">
                          <input type="radio" class="radio" onclick="open_other()" name="split" <?php if(isset($split) && $split==1){ echo'checked="true"'; }?>   value="1"/>YES
                        </label>
                        <label class="radio-inline">
                          <input type="radio" class="radio" onclick="close_other()" name="split" <?php if(isset($split) && $split==2){ echo'checked="true"'; }?> value="2" />NO
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="row">
                    <div id="other_div" class="form-group" <?php if((isset($split) && $split!=1) || !isset($split)){?>style="display: none;<?php } ?>">
                        <div class="form-group">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label>Other </label><br />
                            <div class="col-md-6">
                                <input class=" form-control" type="text" name="prompt_for_check_amount" placeholder="Enter prompt for Check Amount" value="<?php if(isset($prompt_for_check_amount) && (isset($split) && $split==1)) {echo $prompt_for_check_amount;}else{echo '';}?>"/>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="posted_amounts"  placeholder="Enter Posted amounts"  value="<?php if(isset($posted_amounts)&& (isset($split) && $split==1)) {echo $posted_amounts;}else{echo '';}?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>-->
            
            
           </div>
           <div class="panel-footer">
                <!--<div class="col-md-12">
                    <div class="form-group "><br />-->
                    <div class="selectwrap">
                        <?php if($_GET['action']=='edit_batches' && $id>0){?>
                        <a href="report_transaction_by_batch.php?batch_id=<?php echo $id; ?>" target="_blank"><input type="button" name="view_report" value="View Report" /></a>
                        <input type="submit" name="post_trade" value="Post" />
                        <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=unpost_trades&id=<?php echo $id; ?>');"><input type="button" class="confirm" name="unpost_trade" value="Unpost" /></a>
                        <a onclick="return conf_batch('<?php echo CURRENT_PAGE; ?>?action=batches_delete&id=<?php echo $id; ?>','<?php echo $batch_desc; ?>');"><input type="button" name="delete_trade" value="Delete" /></a>
                        <?php } ?>
                        <a href="<?php echo CURRENT_PAGE.'?action=view_batches';?>"><input type="button" name="cancel" value="Cancel" style="float: right;" /></a>
                        <input type="submit" name="batches" onclick="waitingDialog.show();" value="Save" style="float: right;"/>	
                    </div>
                 <!--</div>
                 </div>-->
             </div></div>
        </form>
        <?php
            }if((isset($_GET['action']) && $_GET['action']=='view_batches') || $action=='view_batches'){?>
        <div class="panel">
    		<!--<div class="panel-heading">
                <div class="panel-control">
                    <div class="btn-group dropdown" style="float: right;">
                        <button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
    					<ul class="dropdown-menu dropdown-menu-right" style="">
    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_batches"><i class="fa fa-plus"></i> Add New</a></li>
                            <li><a href="#"><i class="fa fa-minus"></i> Report</a></li> 
    					</ul>
    				</div>
    			</div>
            </div><br />-->
    		<div class="panel-body">
            <!--<div class="panel-control">
            <div class="row">
            <div class="col-md-6" style="float: right;">
             <form method="post">
                <select name="search_type" class="form-control" style="width: 50%; display: inline;" >
                    <option value="">Select Type</option>
                    <option <?php if(isset($search_type) && $search_type=='pro_category'){?>selected="true"<?php }?> value="pro_category">Product Category</option>
                    <option <?php if(isset($search_type) && $search_type=='batch_number'){?>selected="true"<?php }?> value="batch_number">Batch Number</option>
                    <option <?php if(isset($search_type) && $search_type=='sponsor'){?>selected="true"<?php }?> value="sponsor">Sponsor</option>
                    <option <?php if(isset($search_type) && $search_type=='batch_date'){?>selected="true"<?php }?> value="batch_date">Batch Date</option>
                </select>
                <input type="text"  name="search_text_batches" id="search_text_batches" value="<?php if(isset($search_text_batches)){echo $search_text_batches;}?>"/>
                <button type="submit" name="search_batches" id="submit" value="Search"><i class="fa fa-search"></i> Search</button>
            </form>
            </div>
            </div>
            </div>
            <br />-->
                <div class="table-responsive">
    			<table id="data-table" class="table table-striped1 table-bordered" cellspacing="0" width="100%">
    	            <thead>
    	                <tr>
                            <th>BATCH NUMBER</th>
                            <th>BATCH DATE</th>
                            <th>DESCRIPTION</th>
                            <th>SPONSOR</th>
                            <th>CHECK AMOUNT</th>
                            <th>POSTED AMOUNT</th>
                            <th class="text-center" colspan="2">ACTION</th>
                            <!--<th class="text-center">TRADE</th>-->
                        </tr>
    	            </thead>
    	            <tbody>
                    <?php 
                    $count = 0;
                    $posted_commission_amount = 0;
                    foreach($return as $key=>$val){
                        $get_commission_amount = $instance->get_commission_total($val['id']);
                        if(isset($get_commission_amount['posted_commission_amount']) && $get_commission_amount['posted_commission_amount']!='')
                        {
                            $posted_commission_amount = $get_commission_amount['posted_commission_amount'];
                        }else{ $posted_commission_amount = 0;}
                        ?>
    	                   <tr>
                                <td class="td_space"><?php echo $val['id'];;?></td>
                                <td class="td_space"><?php echo date('m/d/Y',strtotime($val['batch_date']));?></td>
                                <td class="td_space"><?php echo $val['batch_desc'];?></td>
                                <td class="td_space"><?php foreach($get_sponsor as $ke=>$va){ if(isset($val['sponsor']) && $val['sponsor']==$va['id']){ echo $va['name']; } }?></td>
                                <td class="td_space" style="text-align: right;"><?php echo '$'.$val['check_amount'];?></td>
                                <td class="td_space" style="text-align: right;"><?php echo '$'.$posted_commission_amount;?></td>
                                
                                <!--td class="text-center">
                                    <?php
                                        if($val['status']==1){
                                            ?>
                                            <a href="<?php echo CURRENT_PAGE; ?>?action=batches_status&id=<?php echo $val['id']; ?>&status=0" class="btn btn-sm btn-success"><i class="fa fa-check-square-o"></i> Enabled</a>
                                            <?php
                                        }
                                        else{
                                            ?>
                                            <a href="<?php echo CURRENT_PAGE; ?>?action=batches_status&id=<?php echo $val['id']; ?>&status=1" class="btn btn-sm btn-warning"><i class="fa fa-warning"></i> Disabled</a>
                                            <?php
                                        }
                                    ?>
                                </td-->
                                <td class="text-center">
                                    <a href="<?php echo CURRENT_PAGE; ?>?action=edit_batches&id=<?php echo $val['id']; ?>" class="btn btn-md btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                </td>
                                <td class="text-center">
                                    <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=batches_delete&id=<?php echo $val['id']; ?>');" class="btn btn-md btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                </td>
                                <!--<td class="text-center">
                                    <a href="#" class="btn btn-md btn-success"> Post</a>
                                    <a href="#" class="btn btn-md btn-warning"> Unpost</a>
                                </td>-->
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
    $(document).ready(function() {
        $('#data-table').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 6,7 ] }, 
                        { "bSearchable": false, "aTargets": [ 6,7 ] }]
        });
        $("div.toolbar").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_batches"><i class="fa fa-plus"></i> Add New</a></li>'+
                            '<li><a href="<?php echo SITE_URL; ?>report_transaction_by_batch.php" target="_blank"><i class="fa fa-minus"></i> Report</a></li>'+
    					'</ul>'+
    				'</div>'+
    			'</div>');
} );
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
<script>
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


$('#demo-dp-range .input-daterange').datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
    });
    
function open_other()
{
    $('#other_div').css('display','block');
}
function close_other()
{
    $('#other_div').css('display','none');
}

function conf_batch(url,batch_desc){
    bootbox.confirm({
        message: "Are you sure you want to Delete the "+batch_desc+" batch?", 
        backdrop: true,
        buttons: {
            confirm: {
                label: '<i class="ion-android-done-all"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-warning"></i> No',
                className: 'btn-warning'
            }
        },
        callback: function(result) {
            if (result) {
                window.location.href = url;
            }else{
                //return false;
            };
        }
    });
}
</script>