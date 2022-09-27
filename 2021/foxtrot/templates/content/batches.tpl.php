<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.6/jquery.inputmask.bundle.min.js"></script>

<div class="container">
    <h1 class="<?php /*if($action=='add_batches'||($action=='edit_batches' && $id>0)){?>topfixedtitle<?php }*/?>">Batches</h1>
    <div class="col-lg-12 well <?php /*if($action=='add_batches'||($action=='edit_batches' && $id>0)){ echo 'fixedwell';}*/?>">
    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <?php  
    
    if((isset($_GET['action']) && $_GET['action']=='add_batches') || (isset($_GET['action']) && $_GET['action']=='add_batches_from_trans') || (isset($_GET['action']) && ($_GET['action']=='edit_batches' && $id>0))){
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
                        <label>Batch Description <span class="text-red">*</span></label><br />
                        <input type="text" maxlength="40" class="form-control" name="batch_desc" id="batch_desc" value="<?php if(isset($batch_desc)) {echo $batch_desc;}?>"  />
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <label>Batch Number </label><br />
                        <input type="text" maxlength="10" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="batch_number" disabled="true"  value="<?php if(isset($batch_number) && $batch_number != '') {echo $batch_number;}else { echo 'Will be Assigned When Saving';}?>"/>
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
                        <input type="text"  class="form-control"  maxlength="12" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 ' name="commission_amount1" value="<?php if(isset($commission_amount) && $commission_amount!='') {echo number_format($commission_amount,2);}else{echo '0';}?>" <?php if(isset($_GET['action']) && ($action=='edit_batches' || $action=='add_batches')){ echo "disabled='true'";}?> />
                        <input type="hidden"  class="form-control"  maxlength="12" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 ' name="commission_amount" value="<?php if(isset($commission_amount) && $commission_amount!='') {echo $commission_amount;}else{echo '0';}?>" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Check Amount </label><br />
                        <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text"  class="form-control  two-decimals" maxlength="12" name="check_amount" id="check_amount" value="<?php if(isset($check_amount) && $check_amount!='') {echo number_format($check_amount,2);}else{echo '0';}?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Batch Date <span class="text-red">*</span></label><br />
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
                        <label>Product Category</label><br />
                        <select class="form-control" name="pro_category">
                            <option value="">Select Category</option>
                            <option value="<?php echo '-1';?>" <?php if(isset($pro_category) && $pro_category=='-1'){ ?>selected="true"<?php } ?>><?php echo 'Multiple Categories';?></option>
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
                             <option value="-1"> Multiple Sponsors</option>
                           
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
                        <?php } 

                        
                        ?>
                        <a href="<?php echo CURRENT_PAGE.'?action=view_batches';?>"><input type="button" name="cancel" value="Cancel" style="float: right;" /></a>
                        <input type="submit" name="batches" onclick="waitingDialog.show();" value="Save" style="float: right;"/>	
                        <?php 
                            if($_GET['action']=='add_batches' || "add_batches_from_trans" == $_GET['action'] ){
                            /*echo ' <input type="submit" name="post_trade" value="Post" style="float: right;" />';*/
                            echo '<button ype="submit" class="btn-theme" name="post_trade" value="Post" style="float: right;" >Save & Post</button>';
                        }

                        ?>
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
                            <th>AMT RECEIVED</th>
                            <th>AMT POSTED</th>
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

                        $editLink = CURRENT_PAGE . '?action=edit_batches&id=' . $val['id'];
                        ?>
    	                   <tr>
                                <td onclick="window.location.href='<?php echo $editLink ?>'" title="Click to edit" class="td_space" style="text-align:center; cursor:pointer; vertical-align: middle;"><?php echo $val['id'];;?></td>
                                <td onclick="window.location.href='<?php echo $editLink ?>'" title="Click to edit" class="td_space" style="cursor:pointer; vertical-align: middle;"><?php echo date('m/d/Y',strtotime($val['batch_date']));?></td>
                                <td onclick="window.location.href='<?php echo $editLink ?>'" title="Click to edit" class="td_space" style="width: 20%; cursor:pointer; vertical-align: middle;"><?php echo $val['batch_desc'];?></td>
                                <td onclick="window.location.href='<?php echo $editLink ?>'" title="Click to edit" class="td_space" style="width: 20%; cursor:pointer; vertical-align: middle;"><?php 
                                if($val['sponsor'] == -1){
                                       echo "Multiple Sponsors";
                                }
                                else{
                                        foreach($get_sponsor as $ke=>$va){ 
                                    if(isset($val['sponsor']) && $val['sponsor']==$va['id']){ echo $va['name']; } }
                                }
                                
                                ?></td>
                                <td onclick="window.location.href='<?php echo $editLink ?>'" title="Click to edit" class="td_space" style="text-align: right; cursor:pointer; vertical-align: middle;"><?php echo '$'.number_format($val['check_amount'],2);?></td>
                                <td onclick="window.location.href='<?php echo $editLink ?>'" title="Click to edit" class="td_space" style="text-align: right; cursor:pointer; vertical-align: middle;"><?php echo '$'.number_format($posted_commission_amount,2);?></td>
                                
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

function setnumber_format(inputtext)
{
   // var a = inputtext.value;
    var number  = inputtext.value;
    var roundedNumber = Number((Math.floor(number * 100) / 100).toFixed(2))

    var options = { style: 'currency', currency: 'USD'};
    inputtext.value=(new Intl.NumberFormat(options).format(roundedNumber));
    

}

    var action ='<?php  echo isset($_GET['action']) ? $_GET['action'] : ''; ?>';
    $(document).ready(function() {

/*$(".two-decimals").inputmask('currency', {
    prefix: '',
    rightAlign: false
  });*/

  $('.two-decimals').priceFormat({
    prefix: '',
      thousandsSeparator: '',
       limit:10
  
});

 $(".aaatwo-decimals").on("keypress", function (evnt) {
            var el= this;
          var charC = (evnt.which) ? evnt.which : evnt.keyCode;
            if (charC == 46) {
                if (el.value.indexOf('.') === -1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (charC > 31 && (charC < 48 || charC > 57))
                    return false;
            }
            return true;

       /* var $txtBox = $(this);
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46)
            return false;
        else {
            var len = $txtBox.val().length;
            var index = $txtBox.val().indexOf('.');
            if (index > 0 && charCode == 46) {
              return false;
            }
            if (index > 0) {
                var charAfterdot = (len + 1) - index;
                if (charAfterdot > 3) {
                    return false;
                }
            }
        }
        return $txtBox; //for chaining*/
    });


        var params = {
        "pageLength": 25,
        "bLengthChange": false,
      
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 6,7 ] }, 
                        { "bSearchable": false, "aTargets": [ 6,7 ] }]
        };
        if(action =='view_batches') {
            params.order = [[ 0, "desc" ]];
        }
        $('#data-table').DataTable(params);
          
        $("div.toolbar").html('<a href="<?php echo CURRENT_PAGE; ?>?action=add_batches" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Add New</a>'+
            '<div class="panel-control" style="padding-left:5px;display:inline;">'+
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
<style type="text/css">
    .btn-theme {background: #EF7623;
    border: none;
    border-radius: 0;
    color: #fff;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 13px;
    height: 34px;
    padding: 0 15px;
    text-transform: uppercase;
    border: none;
    margin: 0 7px 0 1px;
    vertical-align: middle;}

</style>