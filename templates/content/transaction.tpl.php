<script>
function addMoreRow(){
    var html = '<div class="row">'+
                    
                    '<div class="col-md-5">'+
                        '<div class="form-group">'+
                            '<select class="form-control" name="split_broker[]">'+
                            '<option value="">Select Broker</option>'+
                            <?php foreach($get_broker as $key=>$val){?>
                            '<option value="<?php echo $val['id'];?>" <?php if($split_broker != '' && $split_broker==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'].' '.$val['middle_name'].' '.$val['last_name'];?></option>'+
                            <?php } ?>
                            '</select>'+
                        '</div>'+
                        /*'<div class="form-group">'+
                            '<label></label><br />'+
                            '<input type="text" name="company" id="company" class="form-control" />'+
                        '</div>'+*/
                    '</div>'+
                    '<div class="col-md-5">'+
                        '<div class="input-group">'+
                            '<input type="text" name="split_rate[]" onchange="handleChange(this);" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="split_rate" class="form-control decimal" />'+
                            '<input type="hidden" name="split_client_id[]" id="split_client_id" class="form-control" value="0" />'+
                            '<input type="hidden" name="split_broker_id[]" id="split_broker_id" class="form-control" value="0" />'+
                            '<span class="input-group-addon">%</span>'+
                        '</div>'+
                    '</div>'+
                    
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>'+
                        '</div>'+
                    '</div>'+
                '</div>';
                
            
    $(html).insertAfter('#add_other_split');
    $( function() {
    $('.decimal').chargeFormat();
    });
}
$(document).on('click','.remove-row',function(){
    $(this).closest('.row').remove();
});

var flag1=0;
function add_override(){
    
    var html = '<tr class="tr">'+
                    '<td>'+
                        '<select name="receiving_rep[]"  class="form-control" >'+
                        '<option value="">Select Broker</option>'+
                        <?php foreach($get_broker as $key => $val) {?>
                        '<option value="<?php echo $val['id']?>"><?php echo $val['first_name'].' '.$val['last_name']?></option>'+
                        <?php } ?>
                        '</select>'+
                    '</td>'+
                    '<td>'+
                        '<div class="input-group">'+
                        '<input type="number" step="0.001" name="per[]" value="" class="form-control" />'+
                        '<span class="input-group-addon">%</span>'+
                        '</div>'+
                    '</td>'+
                    '<td>'+
                        '<button type="button" tabindex="-1" class="btn remove-row_override btn-icon btn-circle"><i class="fa fa-minus"></i></button>'+
                    '</td>'+
                '</tr>';
                
            
    $(html).insertAfter('#add_override');
}
$(document).on('click','.remove-row_override',function(){
    $(this).closest('.tr').remove();
});
</script>
<div class="container">
<h1 class="<?php if($action=='add'||($action=='edit_transaction' && $id>0)){ echo 'topfixedtitle';}?>">Transactions</h1> 
    <div class="col-lg-12 well <?php if($action=='add'||($action=='edit_transaction' && $id>0)){ echo 'fixedwell';}?>">
    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
   
    
        <?php  
    
    if((isset($_GET['action']) && $_GET['action']=='add') || (isset($_GET['action']) && ($_GET['action']=='edit_transaction' && $id>0))){ 
        
          //if((isset($_GET['action']) && ($_GET['action']=='edit_transaction')) || isset($product_cate)){ get_product($product_cate); }
        ?>
        <form name="frm2" method="POST" >
            <!--<div class="row">
                <div class="col-md-12">
                    <div class="form-group"><br /><div class="selectwrap">
                        <input type="submit" name="transaction" onclick="waitingDialog.show();" value="Save"/>	
                        <a href="<?php echo CURRENT_PAGE.'?action=view';?>"><input type="button" name="cancel" value="Cancel" /></a>
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
    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=view"><i class="fa fa-eye"></i> View List</a></li>
    					</ul>
    				</div>
    			</div>
                <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action=='add'?'Add':'Edit'; ?> Transactions</h3>
    		</div>
            <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Trade Number </label><br />
                        <input type="text" name="trade_number" id="trade_number" value="<?php if(isset($trade_number)) {echo $trade_number;}else{echo 'Assigned after saving';}?>" disabled="true" class="form-control" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Trade Date <span class="text-red">*</span></label><br />
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="trade_date" id="trade_date" value="<?php if(isset($trade_date) && $trade_date != '0000-00-00') {echo date('m/d/Y',strtotime($trade_date));}?>" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Settlement Date <span class="text-red">*</span></label><br />
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="settlement_date" id="settlement_date" value="<?php if(isset($settlement_date) && $settlement_date != '0000-00-00') {echo date('m/d/Y',strtotime($settlement_date));}?>" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Client Name <span class="text-red">*</span></label><br />
                        <select class="livesearch form-control" name="client_name" onchange="get_client_account_no(this.value);">
                            <option value="0">Select Client</option>
                            <?php foreach($get_client as $key=>$val){?>
                            <option value="<?php echo $val['id'];?>" <?php if(isset($client_name) && $client_name==$val['id']){ ?>selected="true"<?php } ?>><?php echo $val['first_name'].' '.$val['mi'].' '.$val['last_name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Client Number <span class="text-red">*</span></label><br />
                        <input type="text" maxlength="26" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="client_number"  id="client_number" value="<?php if(isset($client_number)) {echo $client_number;}?>"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Broker Name <span class="text-red">*</span></label><br />
                        <select class="livesearch form-control" name="broker_name" onchange="get_broker_hold_commission(this.value);">
                            <option value="0">Select Broker</option>
                            <?php foreach($get_broker as $key=>$val){?>
                            <option value="<?php echo $val['id'];?>" <?php if(isset($broker_name) && $broker_name==$val['id']){ ?>selected="true"<?php } ?>><?php echo $val['last_name'].' '.$val['first_name'].' '.$val['middle_name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <label>Product Category <span class="text-red">*</span></label><br />
                        <select class="form-control" name="product_cate" id="product_cate" onchange="get_product(this.value);">
                            <option value="0">Select Product category</option>
                             <?php foreach($product_category as $key=>$val){?>
                            <option value="<?php echo $val['id'];?>" <?php if(isset($product_cate) && $product_cate==$val['id']){?> selected="true"<?php } ?>><?php echo $val['type'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Sponsor </label><br />
                        <select class="form-control" name="sponsor" id="sponsor" onchange="get_product();">
                            <option value="">Select Sponsor</option>
                             <?php foreach($get_sponsor as $key=>$val){?>
                            <option value="<?php echo $val['id'];?>" <?php if(isset($sponsor) && $sponsor==$val['id']){?> selected="true"<?php } ?>><?php echo $val['name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Product <span class="text-red">*</span></label><br />
                        <select class="form-control" name="product"  id="product">
                            <option value="0">Select Product</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Units </label><br />
                        <input type="text" class="form-control" onblur="get_investment_amount();" id="units" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 ' name="units"  value="<?php if(isset($units)) {echo $units;}?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Price </label><br />
                        <input type="text"  class="form-control" onblur="get_investment_amount();" id="shares" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 ' value="<?php if(isset($shares)) {echo $shares;}?>" name="shares"  />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Investment Amount</label><br />
                        <div id="demo-dp-range">
                            <input type="text" maxlength="12" class="form-control" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 ' name="invest_amount" id="invest_amount"  value="<?php if(isset($invest_amount)) {echo $invest_amount;}?>"/>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Charge Amount </label><br />
                        <input type="text" maxlength="9" class="form-control" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 ' name="charge_amount"  value="<?php if(isset($charge_amount) && $charge_amount != '') {echo $charge_amount;}else{echo '0';}?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Commission Received Amount <span class="text-red">*</span></label><br />
                        <input type="text" maxlength="12" class="form-control" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 ' name="commission_received"  value="<?php if(isset($commission_received)) {echo $commission_received;}?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Commission Received Date </label><br />
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="commission_received_date" id="commission_received_date" value="<?php if(isset($commission_received_date) && $commission_received_date!='0000-00-00 00:00:00') {echo date('m/d/Y',strtotime($commission_received_date));}else{ echo ''; }?>" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Batch <span class="text-red">*</span></label><br />
                        <select class="form-control" name="batch" onchange="get_commission_date(this.value);">
                            <option value="0">Select Batch</option>
                             <?php foreach($get_batch as $key=>$val){?>
                            <option value="<?php echo $val['id'];?>" <?php if(isset($batch) && $batch==$val['id']){?> selected="true"<?php } ?>><?php echo $val['id'].' '.$val['batch_desc'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Posting Date </label><br />
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="posting_date" id="posting_date" disabled="true" value="<?php if(isset($posting_date) && $posting_date!='0000-00-00'){ echo date('m/d/Y',strtotime($posting_date));}else if(isset($_GET['action']) && $_GET['action']=='add'){ echo date('m/d/Y'); } else { echo '';}?>" class="form-control" />
                                <input type="hidden" name="posting_date" id="posting_date" value="<?php if(isset($posting_date) && $posting_date!='0000-00-00'){ echo date('m/d/Y',strtotime($posting_date));}else if(isset($_GET['action']) && $_GET['action']=='add'){ echo date('m/d/Y'); } else { echo '';}?>" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Split Commission<span class="text-red">*</span></label><br />
                        <label class="radio-inline">
                          <input type="radio" class="radio" onclick="open_other()" name="split" id="split_yes" <?php if(isset($split) && $split==1){ echo'checked="true"'; }?>   value="1"/>YES
                        </label>
                        <label class="radio-inline">
                          <input type="radio" class="radio" onclick="close_other()" name="split" id="split_no" <?php if((isset($split) && $split==2) || (isset($_GET['action']) && $_GET['action']=='add')){ echo'checked="true"'; }?>  value="2" />NO
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Hold Commission <span class="text-red">*</span></label><br />
                        <label class="radio-inline">
                          <input type="radio" class="radio" id="hold_commission_1"  name="hold_commission" onclick="open_hold_reason();"<?php if(isset($hold_commission) && $hold_commission==1){ echo'checked="true"'; }?> value="1"/>YES
                        </label>
                        <label class="radio-inline">
                          <input type="radio" class="radio" id="hold_commission_2" name="hold_commission" onclick="hide_hold_reason();" <?php if((isset($hold_commission) && $hold_commission==2) || (isset($_GET['action']) && $_GET['action']=='add')){ echo'checked="true"'; }?> value="2" />NO
                        </label>
                    </div>
                </div>
                <div class="col-md-4" id="div_hold_reason" style="<?php if(isset($hold_commission) && $hold_commission==1){ echo'display:true'; }else{ echo'display:none'; }?>">
                    <div class="form-group">
                        <label>Hold Reason </label><br />
                        <input type="text"  class="form-control" value="<?php if(isset($hold_resoan)) {echo $hold_resoan;}?>" name="hold_resoan" id="hold_resoan"  />
                    </div>
                </div>
                <!--<div class="col-md-8" id="split_div" <?php  if((isset($split) && $split!=1) || (isset($_GET['action']) && $_GET['action']=='add')){?>style="display: none;"<?php } ?>>
                    <div class="row" id="add_other_split">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Split Broker </label><br />
                            <select class="form-control" name="split_broker[]">
                                <option value="">Select Broker</option>
                                 <?php foreach($get_broker as $key=>$val){?>
                                <option value="<?php echo $val['id'];?>" <?php if($split_broker != '' && $split_broker==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'].' '.$val['middle_name'].' '.$val['last_name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Split Rate </label><br />
                            <div class="input-group">
                                <input type="text" name="split_rate[]" onchange="handleChange(this);" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="account_no" class="form-control decimal" value="" />
                                <input type="hidden" name="split_client_id[]" id="split_client_id" class="form-control" value="0" />
                                <input type="hidden" name="split_broker_id[]" id="split_broker_id" class="form-control" value="0" />
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label></label><br />
                            <button type="button" onclick="addMoreRow();" class="btn btn-purple btn-icon btn-circle"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <?php if(isset($action) && ($action=='add'||$return_splits==array())){?>
                <div id="client_split_row"></div>
                <div id="broker_split_row"></div>
                <?php } ?> 
                <?php
                if($return_splits != '')
                {
                $is_client = 0;
                $is_broker = 0;
                foreach($return_splits as $keyedit_split=>$valedit_split){
                    $split_broker = $valedit_split['split_broker'];?>
                <?php if($is_client==0){?>
                <div id="client_split_row">
                <?php } ?>
                <?php if($valedit_split['split_client_id']>0){
                      ?>
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
                            <input type="text" name="split_rate[]" onchange="handleChange(this);"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="account_no" class="form-control decimal" value="<?php echo number_format($valedit_split['split_rate'],2);?>" />
                            <input type="hidden" name="split_client_id[]" id="split_client_id" class="form-control" value="<?php echo $valedit_split['split_client_id'];?>" />
                            <input type="hidden" name="split_broker_id[]" id="split_broker_id" class="form-control" value="<?php echo $valedit_split['split_broker_id'];?>" />
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                             <button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php if($is_client==0){
                    $is_client++; ?>
                </div>
                <?php } ?>
                <?php
                if($is_broker==0)
                {?>    
                <div id="broker_split_row">
                <?php } 
                if($valedit_split['split_broker_id']>0){ 
                     ?>
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
                            <input type="text" name="split_rate[]" onchange="handleChange(this);"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="account_no" class="form-control decimal" value="<?php echo number_format($valedit_split['split_rate'],2);?>" />
                            <input type="hidden" name="split_client_id[]" id="split_client_id" class="form-control" value="<?php echo $valedit_split['split_client_id'];?>" />
                            <input type="hidden" name="split_broker_id[]" id="split_broker_id" class="form-control" value="<?php echo $valedit_split['split_broker_id'];?>" />
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
                if($is_broker==0){
                $is_broker++; ?> 
                </div>
                <?php } 
                if($valedit_split['split_broker_id']==0 && $valedit_split['split_client_id']==0) {?>
                <div class="row split_edit_row">
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
                            <input type="text" name="split_rate[]" onchange="handleChange(this);"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="account_no" class="form-control decimal" value="<?php echo number_format($valedit_split['split_rate'],2);?>" />
                            <input type="hidden" name="split_client_id[]" id="split_client_id" class="form-control" value="<?php echo $valedit_split['split_client_id'];?>" />
                            <input type="hidden" name="split_broker_id[]" id="split_broker_id" class="form-control" value="<?php echo $valedit_split['split_broker_id'];?>" />
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                             <button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                </div>
                <?php } } }?>
                </div>-->
            </div>
            <!--<h4>Overrides </h4>
            <div class="panel" style="border: 1px solid #cccccc !important; padding: 10px !important;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="table-responsive">
                                <table class="table table-bordered table-stripped table-hover">
                                    <thead>
                                        <th>Receiving Rep</th>
                                        <th>Rate</th>
                                        <th>Add More</th>
                                    </thead>
                                    <tbody>
                                        <tr id="add_override">
                                            <td>
                                                <select name="receiving_rep[]"  class="form-control">
                                                    <option value="">Select Broker</option>
                                                    <?php foreach($get_broker as $key => $val) {?>
                                                    <option value="<?php echo $val['id']?>"><?php echo $val['first_name'].' '.$val['last_name']?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="per[]" value="" class="form-control" />
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" onclick="add_override();" class="btn btn-purple btn-icon btn-circle"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        <?php 
                                        if(isset($action) && $action=='edit_transaction' && !empty($return_overrides)){
                                        foreach($return_overrides as $regkey=>$regval){
                                                ?>
                                        <tr class="tr">
                                            <td>
                                                <select name="receiving_rep[]"  class="form-control">
                                                    <option value="">Select Broker</option>
                                                    <?php foreach($get_broker as $key => $val) {?>
                                                    <option <?php if(isset($regval['receiving_rep']) && $regval['receiving_rep']==$val['id']) {?>selected="true"<?php } ?> value="<?php echo $val['id']?>"><?php echo $val['first_name'].' '.$val['last_name']?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="per[]" value="<?php echo $regval['per'];?>" class="form-control" />
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" tabindex="-1" class="btn remove-row_override btn-icon btn-circle"><i class="fa fa-minus"></i></button>
                                            </td>
                                        </tr>
                                        <?php } }  ?>
                                  </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
           </div>-->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Buy/Sell </label><br />
                        <label class="radio-inline">
                          <input type="radio" class="radio"  name="buy_sell" <?php if((isset($buy_sell) && $buy_sell==1) || (isset($_GET['action']) && $_GET['action']=='add')){ echo'checked="true"'; }?> value="1"/>Buy    
                        </label>
                        <label class="radio-inline">
                          <input type="radio" class="radio" name="buy_sell" <?php if(isset($buy_sell) && $buy_sell==2){ echo'checked="true"'; }?> value="2" />Sell
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Cancel </label><br />
                        <label class="radio-inline">
                          <input type="radio" class="radio" name="cancel" <?php if(isset($cancel) && $cancel==1){ echo'checked="true"'; }?> value="1"/>YES
                        </label>
                        <label class="radio-inline">
                          <input type="radio" class="radio" name="cancel" <?php if((isset($cancel) && $cancel==2) || (isset($_GET['action']) && $_GET['action']=='add')){ echo'checked="true"'; }?> value="2" />NO
                        </label>
                    </div>
                </div>
            </div> 
          </div>
          <div class="panel-footer fixedbtmenu">
            <div class="selectwrap">
                <a href="<?php echo CURRENT_PAGE.'?action=view';?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
                <input type="submit" name="transaction" onclick="waitingDialog.show();" value="Save" style="float: right;"/>	
            </div>
          </div>
          </div>
        </form>
        <?php
            }if((isset($_GET['action']) && $_GET['action']=='view') || $action=='view'){?>
        <div class="panel">
    		<!--<div class="panel-heading">
                <div class="panel-control">
                    <div class="btn-group dropdown" style="float: right;">
                        <button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
    					<ul class="dropdown-menu dropdown-menu-right" style="">
    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add"><i class="fa fa-plus"></i> Add New</a></li>
                            <li><a href="<?php echo CURRENT_PAGE; ?>?action=view_report"><i class="fa fa-minus"></i> Report</a></li> 
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
                                    <option <?php if(isset($search_type) && $search_type=='id'){?>selected="true"<?php }?> value="id">Trade Number</option>
                                    <option <?php if(isset($search_type) && $search_type=='client_name'){?>selected="true"<?php }?> value="client_name">Client Name</option>
                                    <option <?php if(isset($search_type) && $search_type=='client_number'){?>selected="true"<?php }?> value="client_number">Client Account</option>
                                    <option <?php if(isset($search_type) && $search_type=='broker_name'){?>selected="true"<?php }?> value="broker_name">Broker Name</option>
                                    <option <?php if(isset($search_type) && $search_type=='commission_received'){?>selected="true"<?php }?> value="commission_received">Commission Received</option>
                                    <option <?php if(isset($search_type) && $search_type=='trade_date'){?>selected="true"<?php }?> value="trade_date">Trade Date</option>
                                    <option <?php if(isset($search_type) && $search_type=='batch'){?>selected="true"<?php }?> value="batch">Batch Number</option>
                                </select>
                                <input type="text"  name="search_text" id="search_text_batches" value="<?php if(isset($search_text_batches)){echo $search_text_batches;}?>"/>
                                <button type="submit" name="search_transaction" id="submit" value="Search"><i class="fa fa-search"></i> Search</button>
                            </form>
                        </div>
                    </div>
                </div><br /><br />-->
                <div class="table-responsive">
    			<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    	            <thead>
    	                <tr>
                            
                            <th>Trade Number</th>
                            <th>Trade Date</th>
                            <th>Client Name</th>
                            <th>Client Account Number</th>
                            <th>Broker Name</th>
                            <th>Batch Number</th>
                            <th>Investment Amount</th>
                            <th>Commission Received</th>
                            <th class="text-center" colspan="2">ACTION</th>
                        </tr>
    	            </thead>
    	            <tbody>
                    <?php 
                    $count = 0;
                    foreach($return as $key=>$val){
                        ?>
    	                   <tr>
                                
                                <td><?php echo $val['id'];?></td>
                                <td><?php echo date('m/d/Y',strtotime($val['trade_date']));?></td>
                                <td><?php if(isset($val['client_lastname']) && $val['client_lastname'] != ''){ echo $val['client_lastname'].','.$val['client_firstname'];}?></td>
                                <td><?php echo $val['client_number'];?></td>
                                <td><?php echo $val['broker_firstname'];?></td>
                                <td><?php echo $val['batch_number'];?></td>
                                <td style="text-align: right;"><?php echo '$'.$val['invest_amount']; ?></td>
                                <td style="text-align: right;"><?php echo '$'.$val['commission_received'];?></td>
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
                                    <a href="<?php echo CURRENT_PAGE; ?>?action=edit_transaction&id=<?php echo $val['id']; ?>" class="btn btn-md btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                </td>
                                <td class="text-center">
                                    <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=transaction_delete&id=<?php echo $val['id']; ?>');" class="btn btn-md btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                    <?php }  ?>
                    </tbody>
                </table>
                </div>
            </div>
    	</div>
        <?php } ?> 
        <?php if(isset($_GET['action']) && $_GET['action']=='view_report'){?>
        <div id="view_report">
            <form method="post" target="_blank">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Batch </label><br />
                        <select class="form-control" name="view_batch">
                            <option value="">All Batches</option>
                             <?php foreach($get_batch as $key=>$val){?>
                            <option value="<?php echo $val['id'];?>" <?php if(isset($batch) && $batch==$val['id']){?> selected="true"<?php } ?>><?php echo $val['id'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group "><br /><div class="selectwrap">
                        <input type="submit" name="view_report" value="View Report"/>
                        <a href="<?php echo CURRENT_PAGE.'?action=view';?>"><input type="button" name="cancel" value="Cancel" /></a>
                        </div>
                    </div>
                 </div>
             </div>
             </form>
        </div>
        <?php } ?>
    </div>
</div>
<style>
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
}
#table-scroll {
  height:400px;
  overflow:auto;  
  margin-top:20px;
}
</style>
<script type="text/javascript">
function hide_hold_reason()
{
    $("#hold_resoan").val("");
    $("#div_hold_reason").css('display','none');
}
function open_hold_reason()
{
    $("#div_hold_reason").css('display','block');
}
function handleChange(input) {
    if (input.value < 0) input.value = 0.00;
    if (input.value > 100) input.value = 100.00;
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
$('.decimal').chargeFormat();
});
    $(document).ready(function() {
        
        $('#data-table').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 8,9 ] }, 
                        { "bSearchable": false, "aTargets": [ 8,9 ] }]
        });
        
        $("div.toolbar").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						/*'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add"><i class="fa fa-plus"></i> Add New</a></li>'+*/
                            '<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_report"><i class="fa fa-minus"></i> Report</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
} );
</script>
<style type="text/css">
.toolbar {
    float: right;
    padding-left: 5px;
}
.chosen-container-single .chosen-single {
    height: 34px !important;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
      $(".livesearch").chosen();
      });
</script>
<script type="text/javascript">
function get_product(category_id,selected=''){
        category_id = document.getElementById("product_cate").value;
        sponsor = document.getElementById("sponsor").value;
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("product").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_get_product.php?product_category_id="+category_id+'&sponsor='+sponsor+'&selected='+selected, true);
        xmlhttp.send();
}
//get client account no on client select
function get_client_account_no(client_id){
        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("client_number").value = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_get_client_account.php?client_id="+client_id, true);
        xmlhttp.send();
}
//get default commission date on batch date
function get_commission_date(batch_id)
{
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("commission_received_date").value = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_get_client_account.php?batch_id="+batch_id, true);
        xmlhttp.send();
}
//get client split rate on client select
function get_client_split_rates(client_id){
        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                $( "#split_yes" ).prop( "checked", true );
                open_other();
                //$('#client_split_row').replaceWith(this.responseText);
                
                document.getElementById("client_split_row").innerHTML = this.responseText;
                //$(this.responseText).insertAfter('#add_other_split');
            }
        };
        xmlhttp.open("GET", "ajax_get_split_rates.php?client_id="+client_id, true);
        xmlhttp.send();
}
//get broker split rate on broker select
function get_broker_split_rates(broker_id){
        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                $( "#split_yes" ).prop( "checked", true );
                open_other();
                //$('#broker_split_row').replaceWith(this.responseText);
                document.getElementById("broker_split_row").innerHTML = this.responseText;
                //$(this.responseText).insertAfter('#add_other_split');
                //document.getElementById("split").value = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_get_split_rates.php?broker_id="+broker_id, true);
        xmlhttp.send();
}
//get broker override rate on broker select
function get_broker_override_rates(broker_id){
        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                $('.broker_override_class').remove();
                $(this.responseText).insertAfter('#add_override');
            }
        };
        xmlhttp.open("GET", "ajax_get_override_rates.php?broker_id="+broker_id, true);
        xmlhttp.send();
}
//get broker hold commission on broker select
function get_broker_hold_commission(broker_id){
        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                hold_commissions = this.responseText;
                if(hold_commissions==1)
                {
                    $("#hold_commission_1").prop("checked", true );
                    $("#div_hold_reason").css('display','block');
                    $("#hold_resoan").val( "HOLD COMMISSION BY BROKER");
                }
                else
                {
                    $("#hold_commission_1").prop( "checked", false );
                    $("#hold_commission_2").prop( "checked", true );
                    $("#div_hold_reason").css('display','none');
                    $("#hold_resoan").val("");
                }
            }
        };
        xmlhttp.open("GET", "ajax_hold_commissions.php?broker_id="+broker_id, true);
        xmlhttp.send();
}
function open_other()
{
    $('#split_div').css('display','block');
    //$('.split_edit_row').css('display','block');
}
function close_other()
{
    $('#split_div').css('display','none');
    //$('.split_edit_row').css('display','none');
}
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
function get_investment_amount()
{
    var units = $("#units").val();
    var shares = $("#shares").val();
    if((units > 0) && (shares > 0))
    {
        var invest_amount = units*shares;
        $("#invest_amount").val(invest_amount);
    }
}
</script>
<?php

    if($product_cate>0){
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                get_product(<?php echo $product_cate; ?>,'<?php echo $product; ?>');
            });
        </script>
        <?php
    }
    /*if($broker_name>0){
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                get_broker_hold_commission(<?php echo $broker_name; ?>);
            });
        </script>
        <?php
    }*/
    if($client_name>0){
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                get_client_account_no(<?php echo $client_name; ?>);
            });
        </script>
        <?php
    }
    if(isset($_GET['batch_id']) && ($_GET['batch_id'] != '' || $batch>0)){
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                get_commission_date(<?php echo $batch; ?>);
            });
        </script>
        <?php
    }
    /*if($product_cate>0 && $product != ''){
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                get_product(<?php echo $product_cate; ?>,'<?php echo $product; ?>');
            });
        </script>
        <?php
    }*/

?>