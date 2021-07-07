<div class="container">
<h1 class="<?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'topfixedtitle';}?>">Maintain Payable Transactions</h1>
<div class="col-lg-12 well <?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'fixedwell';}?>">
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <div class="tab-content col-md-12">
            <div class="tab-pane active" id="tab_a">
                    <?php 
                    if($action=='add_new'||($action=='edit' && $id>0)){
                    ?>
                    <form method="post">
                        <div class="tab-content">
                            <div class="row">
                                <div class="col-md-4" style="float: right;">
                                    <div class="form-group">
                                        
                                    </div>
                                </div>
                            </div>
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
                                        <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action=='add_new'?'Add':'Edit'; ?> Payable Transactions</h3>
                                   </div>
                                   <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Trade Number </label>
                                                    <input type="number" name="trade_number_dis" id="trade_number_dis" class="form-control" value="<?php echo $trade_number;?>" disabled="true" />
                                                    <input type="hidden" name="trade_number" id="trade_number" class="form-control" value="<?php echo $trade_number;?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date </label>
                                                    <div id="demo-dp-range">
                                                        <div class="input-daterange input-group" id="datepicker">
                                                            <input type="text" name="trade_date" id="trade_date" class="form-control" value="<?php if($trade_date != '' && $trade_date !='0000-00-00'){ echo date('m/d/Y',strtotime($trade_date)); }?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                       </div> 
                                       <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Investment </label>
                                                    <select class="form-control" name="product"  id="product">
                                                        <option value="0">Select Product</option>
                                                    </select>
                                                    <input type="hidden" name="product_category" id="product_category" class="form-control" value="<?php echo $product_category;?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Broker Name </label>
                                                    <select class="form-control" name="broker_name" id="broker_name">
                                                        <option value="0">Select Broker</option>
                                                        <?php foreach($get_broker as $key=>$val){?>
                                                        <option value="<?php echo $val['id'];?>" <?php if($broker_name != '' && $broker_name==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'].' '.$val['last_name'];?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                       </div> 
                                       <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Client</label>
                                                    <input type="number" name="client_account_number" id="client_account_number" class="form-control" value="<?php echo $client_account_number;?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>&nbsp; </label>
                                                    <select class="form-control" name="client_name" id="client_name">
                                                        <option value="0">Select Client</option>
                                                        <?php foreach($get_client as $key=>$val){?>
                                                        <option value="<?php echo $val['id'];?>" <?php if($client_name != '' && $client_name==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'].' '.$val['last_name'];?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                       </div> 
                                       <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Quantity </label>
                                                    <input type="number" onblur="get_investment_amount();" name="quantity" id="quantity"  class="form-control" value="<?php echo $quantity;?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Price </label>
                                                    <input type="number" onblur="get_investment_amount();" name="price" id="price" class="form-control" value="<?php echo $price;?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Principal </label>
                                                    <input type="number" name="investment_amount" id="investment_amount" class="form-control amount" value="<?php echo $investment_amount;?>" />
                                                </div>
                                            </div>
                                       </div> 
                                       <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Comm.Exp'd </label>
                                                    <input type="text" onkeypress="return isFloatNumber(this,event)" name="commission_expired" id="commission_expired" class="form-control amount" value="<?php echo $commission_expired;?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Comm.Recv'd </label>
                                                    <input type="text" onkeypress="return isFloatNumber(this,event)" name="commission_received" id="commission_received" class="form-control amount" value="<?php echo $commission_received;?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Date Recv'd </label>
                                                    <div id="demo-dp-range">
                                                        <div class="input-daterange input-group" id="datepicker">
                                                            <input type="text" name="date_received" id="date_received" class="form-control" value="<?php if($date_received != '' && $date_received !='0000-00-00'){ echo date('m/d/Y',strtotime($date_received)); }?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                       </div> 
                                       <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Charge </label>
                                                    <input type="number" name="charge" id="charge" class="form-control amount" value="<?php echo $charge;?>" />
                                                </div>
                                            </div>
                                       </div>
                                       <?php 
                                       if(isset($return_trade_splits) && $return_trade_splits != array())
                                       {?>
                                       <div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
                                       <div class="titlebox">Splits</div><br />
                                       <?php
                                            foreach($return_trade_splits as $splits_key=>$splits_val)
                                            {
                                                $row_sequence = $splits_key+1;
                                                ?>
                                               <?php if($row_sequence%2==1){?>
                                               <div class="row">
                                               <?php } ?>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select class="form-control" name="split_broker[]">
                                                            <option value="">Select Broker</option>
                                                             <?php foreach($get_broker as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($splits_val['split_broker'] != '' && $splits_val['split_broker']==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'].' '.$val['middle_name'].' '.$val['last_name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                        <input type="number" name="split_rate[]" id="account_no" class="form-control amount" value="<?php echo $splits_val['split_rate'];?>" />
                                                        <span class="input-group-addon">%</span>
                                                    </div>
                                                </div>
                                               <?php if($row_sequence%2==0 || count($return_trade_splits)==$splits_key+1){?>
                                               </div>
                                               <?php } ?>
                                       <?php
                                            }?>
                                       </div>
                                       <?php } ?>
                                       <?php 
                                       if(isset($return_trade_overrides) && $return_trade_overrides != array())
                                       {?>
                                       <div class="row">
                                        <div class="col-md-12">
                                        <div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
                                        <div class="titlebox">Overrides</div><br />
                                            <?php 
                                            foreach($return_trade_overrides as $overrides_key=>$overrides_val)
                                            {
                                                $sequence = $overrides_key+1;
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label style="float: right;">Override #<?php echo $sequence;?></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <select class="form-control" name="override_broker[]">
                                                                <option value="">Select Broker</option>
                                                                 <?php foreach($get_broker as $key=>$val){?>
                                                                <option value="<?php echo $val['id'];?>" <?php if($overrides_val['receiving_rep'] != '' && $overrides_val['receiving_rep']==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'].' '.$val['middle_name'].' '.$val['last_name'];?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="number" name="override_rate[]" id="account_no" class="form-control amount" value="<?php echo $overrides_val['per'];?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }?>
                                        </div>
                                        </div>
                                        <!--<div class="col-md-2">
                                            <input type="radio" class="radio" name="buy_sell" id="buy" style="display: inline;" value="1" <?php if($buy_sell==1){echo "checked='checked'";}?>/> Buy&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="radio" name="buy_sell" id="sell" style="display: inline;" value="2" <?php if($buy_sell==2){echo "checked='checked'";}?>/> Sell<br /><br />
                                            <input type="checkbox" class="checkbox" name="hold" id="hold" style="display: inline;" value="1"  <?php if($hold==1){echo "checked='checked'";}?>/> Hold<br />
                                            <input type="text" name="hold_reason" id="hold_reason" class="form-control amount" value="<?php echo $hold_reason;?>" /><br />
                                            <input type="checkbox" class="checkbox" name="cancel" id="cancel" style="display: inline;" value="1"  <?php if($cancel==1){echo "checked='checked'";}?>/> Cancel<br />
                                        </div>-->
                                       </div>
                                       <?php
                                       }?>
                                       <div class="row">
                                            <div class="col-md-4">
                                            <input type="radio" class="radio" name="buy_sell" id="buy" style="display: inline;" value="1" <?php if($buy_sell==1){echo "checked='checked'";}?>/> Buy&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="radio" name="buy_sell" id="sell" style="display: inline;" value="2" <?php if($buy_sell==2){echo "checked='checked'";}?>/> Sell
                                            </div>
                                            <div class="col-md-4">
                                            <input type="checkbox" class="checkbox" name="hold" id="hold" style="display: inline;" value="1"  <?php if($hold==1){echo "checked='checked'";}?>/> Hold&nbsp;&nbsp;
                                            <input type="text" name="hold_reason" id="hold_reason" style="<?php if($hold!=1){echo "display:none";}?>" class="form-control amount" value="<?php echo $hold_reason;?>" />
                                            </div>
                                            <div class="col-md-4">
                                            <input type="checkbox" class="checkbox" name="cancel" id="cancel" style="display: inline;" value="1"  <?php if($cancel==1){echo "checked='checked'";}?>/> Cancel
                                            </div>
                                       </div> 
                                       <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label>Branch </label>
                                                    <select class="form-control" name="branch" id="branch">
                                                        <option value="0">Select Branch</option>
                                                        <?php foreach($get_branch as $key=>$val){?>
                                                        <option value="<?php echo $val['id'];?>" <?php if(isset($branch) && $branch==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                       </div> 
                                   </div>
                                   <div class="panel-overlay">
                                       <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                   </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="transactions"){ echo "active"; }else{ echo '';} ?>" id="tab_transactions">
                                
                            </div>
                        </div>
                        <div class="panel-footer"><br />
                            <div class="selectwrap">
                				<div class="selectwrap">
                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" name="payroll_id" id="payroll_id" value="<?php echo $payroll_id; ?>" />
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
                                        <th>Trade Number</th>
                                        <th>Trade Date</th>
                                        <th>Client Name</th>
                                        <th>Client Account Number</th>
                                        <th>Broker Name</th>
                                        <th>Investment Amount</th>
                                        <th>Commission Received</th>
                                        <th>Commission Paid</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                	            </thead>
                	            <tbody>
                                 <?php
                                $count = 0;
                                foreach($return as $key=>$val){
                                    ?>
                	                   <tr>
                                            <td><?php echo $val['trade_number']; ?></td>
                                            <td><?php if(isset($val['trade_date']) && $val['trade_date']!='0000-00-00'){ echo date('m/d/Y',strtotime($val['trade_date']));}else{ echo ''; } ?></td>
                                            
                                            <td><?php echo $val['client_firstname'].' '.$val['client_lastname']; ?></td>
                                            <td><?php echo $val['client_account_number']; ?></td>
                                            <td><?php echo $val['broker_firstname'].' '.$val['broker_lastname']; ?></td>
                                            <td><?php echo $val['investment_amount']; ?></td>
                                            <td><?php echo $val['commission_received']; ?></td>
                                            <td><?php echo $val['commission_paid']; ?></td>
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
    </div>
</div>
<script type="text/javascript">
//datatable bootstrap
    $(document).ready(function() {
        $('#data-table').DataTable({
        "pageLength": 100,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ -1 ] },
                        { "width": "150px", "targets": -1 },
                        { "bSearchable": false, "aTargets": [ -1 ] }]
        });
       /* $("div.toolbar").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');*/
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
(function($) {
$.fn.amountFormat = function() {
    this.each( function( i ) {
        $(this).change( function( e ){
            if( isNaN( parseFloat( this.value ) ) ) return;
            this.value = parseFloat(this.value).toFixed(2);
        });
    });
    return this; //for chaining
}
})( jQuery );

 $("#hold").click(function () {
    if ($(this).is(":checked")) {
        $("#hold_reason").css('display','block');
    } else {
        $("#hold_reason").css('display','none');
    }
});
$( function() {
$('.amount').amountFormat();
});
function get_product(category_id,selected=''){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("product").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_get_product.php?product_category_id="+category_id+'&selected='+selected, true);
        xmlhttp.send();
}
function get_investment_amount()
{
    var units = $("#quantity").val();
    var shares = $("#price").val();
    if((units > 0) && (shares > 0))
    {
        var invest_amount = units*shares;
        $("#investment_amount").val(invest_amount);
    }
}
</script>
<style>
/* , .table>thead>tr>td, .table>thead>tr>th 
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th {
    padding: 2px;
}
*/
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 2px;
}
.toolbar {
    float: right;
    padding-left: 5px;
}
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
}
.titlebox {
    float: left;
    font-weight: bold;
    padding: 0 5px;
    margin: -20px 0 0 30px;
    background: #fff;
}
</style>
<?php 
if($product_category>0){
?>
<script type="text/javascript">
    $(document).ready(function(){
        get_product(<?php echo $product_category; ?>,'<?php echo $product; ?>');
    });
</script>
<?php
}?>