<script>
function addMoreThreshold(){
    var html = '<div class="row main_row" style="padding: 5px;">'+
                    '<div class="col-md-6">'+
                        '<div class="row" style="padding: 5px;">'+
                            '<div class="col-md-5">'+
                                '<div class="input-group">'+
                                    '<span class="input-group-addon">$</span>'+
                                    '<input type="number" value=""  maxlength="9" class="form-control" id="min_threshold" name="min_threshold[]" placeholder="$0"  />'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                '<div class="form-group">'+
                                    '<label>To </label>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-5">'+
                                '<div class="input-group">'+
                                    '<span class="input-group-addon">$</span>'+
                                    '<input type="number" value=""  maxlength="9" class="form-control" id="max_threshold" name="max_threshold[]" placeholder="$99,999,999"  />'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-6">'+
                        '<div class="row" style="padding: 5px;">'+
                            '<div class="col-md-4">'+
                                '<div class="form-group">'+
                                    '<label>with a Rate of </label>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-6">'+
                                '<div class="input-group">'+
                                    '<input type="number" value="" maxlength="5" step="0.01" class="form-control" name="min_rate[]" id="min_rate" placeholder="0.0%"  />'+
                                    '<span class="input-group-addon">%</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                '<div class="form-group">'+
                                    '<button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>'+
                                '</div>'+
                            '</div>'+
                            /*'<div class="col-md-6">'+
                                '<div class="form-group">'+
                                    '<input type="text" value="" maxlength="5" class="form-control" name="max_rate[]" id="max_rate" placeholder="99.9%" />'+
                                '</div>'+
                            '</div>'+*/
                        '</div>'+
                    '</div>'+
                    
                    /*'<div class="col-md-1">'+
                        '<div class="row" style="padding: 5px;">'+
                            '<div class="col-md-12">'+
                                '<div class="form-group">'+
                                    '<button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+*/
                '</div>';
                
            
    $(html).insertAfter('#add_more_threshold');
}
$(document).on('click','.remove-row',function(){
    $(this).closest('.main_row').remove();
});
</script>
<div class="container">
<h1 class="<?php if($action=='add_product'||($action=='edit_product' && $id>0)){ echo 'topfixedtitle';}?>">  Product Maintenance  </h1>
<div class="col-lg-12 well <?php if($action=='add_product'||($action=='edit_product' && $id>0)){ echo 'fixedwell';}?>">
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
            <?php
                    if(isset($_GET['action']) && $_GET['action']=='view_product') {?>
                <div class="panel">
            		<!--<div class="panel-heading">
                        <div class="panel-control">
                            <div class="btn-group dropdown" style="float: right;">
                                <button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
            					<ul class="dropdown-menu dropdown-menu-right" style="">
            						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_product&category=<?php echo $category; ?>"><i class="fa fa-plus"></i> Add New</a></li>
            					   <li><a href="<?php echo CURRENT_PAGE; ?>?action=select_cat"><i class="fa fa-minus"></i> Back To Category</a></li>
                                </ul>
            				</div>
            			</div>
                    </div><br />-->
            		<div class="panel-body">
                        <!--<div class="panel-control" style="float: right;" >
                         <form method="post">
                            <div class="row"> 
                            <div class="col-md-5"></div>
                                <!--div class="col-md-3">
                                    <!--<select class="form-control" name="search_product_category">
                                        <?php foreach($product_category as $key=>$val){?>
                                        <option value="<?php echo $val['id'];?>" <?php if($search_product_category==$val['id']){echo "selected='selected'";} ?>><?php echo $val['type'];?></option>
                                        <?php } ?>
                                    </select>
                                 </div--> 
                                   <!-- <input type="hidden" name="search_product_category" value="<?php echo $category; ?>"/> 
                                    <input type="text" name="search_text_product" style=" width:60% !important;"  placeholder="Search Name , Cusip , Ticker" id="search_text_product" value="<?php echo $search_text_product;?>"/>
                                    <button type="submit" name="search_product" id="submit" value="Search"><i class="fa fa-search"></i> Search</button>
                                 
                            </div>
                        </form>
                        </div><br /><br />-->
                       <div class="table-responsive">
            			<table id="data-table" class="table table-striped1 table-bordered" cellspacing="0" width="100%">
            	            <thead>
            	                <tr>
                                    <th>PRODUCT NAME</th>
                                    <th>CUSIP</th>
                                    <th>SPONSOR NAME</th>
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
                                        <td><?php echo $val['name'];?></td>
                                        <td><?php echo $val['cusip']; ?></td>
                                        <td><?php echo $val['sponsor']; ?></td>
                                        <td class="text-center">
                                            <?php
                                                if($val['status']==1){
                                                    ?>
                                                    <a href="<?php echo CURRENT_PAGE; ?>?action=product_status&category=<?php echo $val['category']; ?>&id=<?php echo $val['id']; ?>&status=0" class="btn btn-sm btn-success"><i class="fa fa-check-square-o"></i> Active</a>
                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                    <a href="<?php echo CURRENT_PAGE; ?>?action=product_status&category=<?php echo $val['category']; ?>&id=<?php echo $val['id']; ?>&status=1" class="btn btn-sm btn-warning"><i class="fa fa-warning"></i> Terminated</a>
                                                    <?php
                                                }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_product&category=<?php echo $val['category']; ?>&id=<?php echo $val['id']; ?>" class="btn btn-md btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=product_delete&category=<?php echo $val['category']; ?>&id=<?php echo $val['id']; ?>');" class="btn btn-md btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        </div>
            		</div>
            	</div>
                <?php } ?>  
            <?php  
            if($action=='select_cat'){
                ?>
                <div class="panel">
                           
                <form name="frm2" method="POST">
                    <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Category </label><br />
                                <select class="form-control" name="set_category" style="display: inline !important;">
                                    <option value="">Select Category</option>
                                    <?php foreach($product_category as $key=>$val){?>
                                    <option value="<?php echo $val['id'];?>" <?php if($category==$val['id']){echo "selected='selected'";} ?>><?php echo $val['type'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label>&nbsp;</label><br />
                                <div class="selectwrap">
                                    <input type="submit" name="next" value="Next" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                   </div>
                </form>
                </div>
                <?php
                    }else if($action=='add_product' || ($action=='edit_product' && $id>0)){
                ?>   
                <ul class="nav nav-tabs <?php if($action=='add_product'||($action=='edit_product' && $id>0)){ echo 'topfixedtabs';}?>">
                  <li class="active"><a href="#tab_aa" data-toggle="tab">General</a></li>
                  <li><a href="#tab_bb" data-toggle="tab">Suitability</a></li>
                  <!--<li><a href="#tab_ee" data-toggle="tab">Documents</a></li>-->
                    <div class="btn-group dropdown" style="float: right;">
						<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
						<ul class="dropdown-menu dropdown-menu-right" style="">
							<li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-eye"></i> View List</a></li>
						</ul>
					</div>
				</ul> 
                 <form name="frm" method="POST" onsubmit="return validation();" enctype="multipart/form-data">    
                 <!--<div class="panel-footer">
                        <div class="selectwrap col-md-12">
                            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                            <?php if($_GET['action']=='edit_product' && $_GET['id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=previous&category=<?php echo $category;?>" class="previous next_previous_a" style="float: left;" ><input type="button" style="float: left;" name="Previous" value="&laquo; Previous" /></a> <?php } ?>
        					<?php if($action=='edit_product' && $id>0){?>
                            <a href="#view_changes" data-toggle="modal"><input type="button" name="view_changes" value="View Changes" style="margin-left: 10%;"/></a>
                            <?php } ?>
                            <a href="#product_notes" data-toggle="modal"><input type="button" onclick="get_product_notes();" name="notes" value="Notes" /></a>
                            <a href="#client_attachment" data-toggle="modal"><input type="button" name="attach" value="Transaction" /></a>
                            <a href="#product_attach" data-toggle="modal"><input type="button"  onclick="get_product_attach();" name="attach" value="Attachments" /></a>
                            <input type="submit" name="product" onclick="waitingDialog.show();" value="Save"/>	
                            <a href="<?php echo CURRENT_PAGE.'?action=view_product';?>"><input type="button" name="cancel" value="Cancel" /></a>
                            <?php if($_GET['action']=='edit_product' && $_GET['id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=next&category=<?php echo $category;?>" class="next next_previous_a" style="float: right;"><input type="button" name="Next" value="Next &raquo;" /></a><?php } ?>
                        </div>
                 </div><br /><br />-->
                 <div class="tab-content col-md-12 panel">
                    
                    <div class="tab-pane active" id="tab_aa"> 
                        
        					<div class="row"><br />
                                <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_product'?'Add':'Edit'; ?> Product</h3>
        					</div><br />
                            <input type="hidden" name="product_category" id="product_category1" value="<?php echo $category;?>" />
                            <div class="row">
                                <!--div class="col-md-6">
                                    <?php if($action=='edit_product' && $id>0){?>
                                    <div class="form-group">
                                        <label>Product Category </label><br />
                                        <select class="form-control" name="product_category" id="product_category" disabled="true">
                                            <option value="">Select Category</option>
                                            <?php foreach($product_category as $key=>$val){?>
                                            <option value="<?php echo $val['id'];?>" <?php if($category==$val['id']){echo "selected='selected'";} ?>><?php echo $val['type'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php } else {?>
                                    <input type="text" name="product_category" id="product_category1" value="<?php echo $category;?>" />
                                    <?php } ?>
                                </div-->
                                                            
                                    
                                 
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product Name <span class="text-red">*</span></label><br />
                                        <input type="text" maxlength="40" class="form-control" name="name" value="<?php echo $name; ?>"  />
                                    </div>
                                </div>
                                <!--div class="col-md-6">
                                    <div class="form-group">
                                        <label></label><br />
                                        <a href="#client_notes" data-toggle="modal"><input type="button" class="col-md-6" name="notes" value="Notes" /></a>
                                        <a href="#client_attachment" data-toggle="modal"><input type="button" class="col-md-6" name="attach" value="Attach" /></a>
                                    </div>
                                 </div-->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sponsor <span class="text-red">*</span></label><br />
                                        <select class="form-control" name="sponsor">
                                            <option value="">Select Sponsor</option>
                                             <?php foreach($get_sponsor as $key=>$val){?>
                                            <option value="<?php echo $val['id'];?>" <?php if($sponsor != '' && $sponsor==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ticker Symbol </label><br />
                                        <input type="text" maxlength="6" class="form-control" name="ticker_symbol" value="<?php echo $ticker_symbol; ?>"  />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>CUSIP </label><br />
                                        <?php 
                                        if(isset($_GET['cusip_number']) && $_GET['cusip_number'] != '')
                                        {
                                        ?>
                                            <input type="text" disabled="true" name="cusip_disp" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="cusip_disp" class="form-control" value="<?php echo $_GET['cusip_number'];?>" />
                                            <input type="hidden" maxlength="11" class="form-control" name="cusip" value="<?php echo $_GET['cusip_number'];?>" />
                                        <?php 
                                        }else{
                                        ?>
                                            <input type="text" maxlength="11" class="form-control" name="cusip" value="<?php echo $cusip; ?>" />
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Security Number </label><br />
                                        <input type="text" maxlength="10" class="form-control" name="security" value="<?php echo $security; ?>"   />
                                    </div>
                                </div>
                            </div>
                            <?php if(isset($_GET['category']) && $_GET['category'] == 1){ ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Allowable Receivable </label><br />
                                        <input type="checkbox" class="checkbox" name="allowable_receivable" id="allowable_receivable" value="1" style="display: inline;" <?php if($receive>0){echo "checked='checked'";}?>  />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Non-Commissionable </label><br />
                                        <input type="checkbox"  class="checkbox" name="non_commissionable" id="non_commissionable" value="1" style="display: inline;" <?php if($non_commissionable>0){echo "checked='checked'";}?>/>
                                    </div>
                                </div>
                            </div>
                           
                           <div style="display: block; border: 1px solid #ddd;">
                           <div class="row" style="padding: 5px;"> 
                                <div class="col-md-12">
                                    <h4><b>Mutual Funds</b></h4><br />
                                </div>
                           </div>
                           <div class="row" style="padding: 5px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Class Type </label><br />
                                        <input type="radio" class="radio" name="class_type" id="cpa" value="1" style="display: inline;" <?php if($class_type==1){echo "checked='checked'";}?>/>&nbsp;<label>A</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="radio" name="class_type" id="cpa" value="2" style="display: inline;" <?php if($class_type==2){echo "checked='checked'";}?>/>&nbsp;<label>B</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="radio" name="class_type" id="cpa" value="3" style="display: inline;" <?php if($class_type==3){echo "checked='checked'";}?>/>&nbsp;<label>C</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="radio" name="class_type" id="cpa" value="4" style="display: inline;" <?php if($class_type==4){echo "checked='checked'";}?>/>&nbsp;<label>other</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fund Code </label><br />
                                        <input type="text" maxlength="7" value="<?php echo $fund_code; ?>" class="form-control" name="fund_code"  />
                                    </div>
                                </div>
                            </div><br />
                            <label>Threshold </label><br />
                           <?php
                            if($return_rates != '')
                            {
                            foreach($return_rates as $keyedit_rates=>$valedit_rates){
                            ?>
                           <div class="row main_row" style="padding: 5px;">
                                <div class="col-md-6">
                                    <div class="row" style="padding: 5px;">
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="number" value="<?php echo $valedit_rates['min_threshold']; ?>"  maxlength="9" class="form-control" id="min_threshold" name="min_threshold[]" placeholder="$0"  />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label>To </label>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="number" value="<?php echo $valedit_rates['max_threshold']; ?>"  maxlength="9" class="form-control" id="max_threshold" name="max_threshold[]" placeholder="$99,999,999"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row" style="padding: 5px;">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>with a Rate of </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="number" value="<?php echo $valedit_rates['min_rate']; ?>" step="0.01" maxlength="5" class="form-control" name="min_rate[]" id="min_rate" placeholder="0.0%"  />
                                                <span class="input-group-addon">%</span>
                                            </div>
                                        </div>
                                        <!--<div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" value="<?php echo $valedit_rates['max_rate']; ?>"  onblur="maxrate(this.value)"  maxlength="5" class="form-control" name="max_rate[]" id="max_rate" placeholder="99.9%" />
                                            </div>
                                        </div>-->
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                           </div>
                           <?php } }?>
                           <div class="row" style="padding: 5px;" id="add_more_threshold">
                                <div class="col-md-6">
                                    <!--<label>Threshold </label><br />-->
                                    <div class="row" style="padding: 5px;">
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="number" value=""  maxlength="9" class="form-control" id="min_threshold" name="min_threshold[]" placeholder="$0"  />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label>To </label>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="number" value=""  maxlength="9" class="form-control" id="max_threshold" name="max_threshold[]" placeholder="$99,999,999"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!--<label>Rate </label><br />-->
                                    <div class="row" style="padding: 5px;">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>with a Rate of </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="number" value="" step="0.01"  maxlength="5" class="form-control" name="min_rate[]" id="min_rate" placeholder="0.00%"  />
                                                <span class="input-group-addon">%</span>
                                                <!--<input type="text" value=""  maxlength="5" class="form-control" name="max_rate[]" id="max_rate" placeholder="99.9%" />-->
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button type="button" onclick="addMoreThreshold();" class="btn btn-purple btn-icon btn-circle"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           </div>
                           <div class="row" style="padding: 5px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Waive Sweep Fee </label><br />
                                        <input type="checkbox" class="checkbox" name="sweep_fee" id="sweep_fee" value="1" style="display: inline;" <?php if($sweep_fee>0){echo "checked='checked'";}?>/>
                                    </div>
                                </div>
                           </div>
                           </div><br />
                           <?php } ?>
                           <?php if(isset($_GET['category']) && $_GET['category'] == 11 ){ ?>
                           <div style="display: block; border: 1px solid #ddd;">
                           <div class="row" style="padding: 5px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Investment Banking Type </label><br />
                                        <select class="form-control" name="investment_banking_type">
                                            <option value="">Select Banking Type</option>
                                            <option value="1" <?php if($investment_banking_type==1){echo "selected='selected'";} ?>>IPO</option>
                                            <option value="2" <?php if($investment_banking_type==2){echo "selected='selected'";} ?>>Bridge</option>
                                            <option value="3" <?php if($investment_banking_type==3){echo "selected='selected'";} ?>>Reg S</option>
                                            <option value="4" <?php if($investment_banking_type==4){echo "selected='selected'";} ?>>Reg D</option>
                                            <option value="5" <?php if($investment_banking_type==5){echo "selected='selected'";} ?>>Private Placement</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            </div><br />
                           <?php } ?>
                           <?php if(isset($_GET['category']) && $_GET['category'] == 12 ){ ?>
                           <div style="display: block; border: 1px solid #ddd;">
                           <div class="row" style="padding: 5px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>RIA Type </label><br />
                                        <select class="form-control" name="ria_specific_type">
                                            <option value="">Select Type</option>
                                            <option value="1" <?php if($ria_specific_type==1){echo "selected='selected'";} ?>>Fee Based Mutual Funds</option>
                                            <option value="2" <?php if($ria_specific_type==2){echo "selected='selected'";} ?>>Fee Based Stocks, Bonds &amp; Mutual Funds</option>
                                            <option value="3" <?php if($ria_specific_type==3){echo "selected='selected'";} ?>>Financial Planning</option>
                                            <option value="4" <?php if($ria_specific_type==4){echo "selected='selected'";} ?>>Money Managers</option>
                                            <option value="5" <?php if($ria_specific_type==5){echo "selected='selected'";} ?>>Non-Discretionary</option>
                                            <option value="6" <?php if($ria_specific_type==6){echo "selected='selected'";} ?>>Socially Screened</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label></label><br />
                                        <input type="radio"  name="based_type" class="radio"  value="1" style="display: inline;" <?php if($based==1){echo "checked='checked'";}?>/>&nbsp;<label>Asset Based</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio"  name="based_type" class="radio"  value="2" style="display: inline;" <?php if($based==2){echo "checked='checked'";}?>/>&nbsp;<label>Fee Based</label>
                                    </div>
                                </div>
                            </div>   
                            <div class="row" style="padding: 5px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fee Rate </label><br />
                                        <input type="text" value="<?php echo $fee_rate; ?>" onblur="round(this.value);"  maxlength="5"   class="form-control" name="fee_rate" id="fee_rate" placeholder="0.00"  />
                                    </div>
                                </div>
                            </div>
                            </div>
                            <br />  
                           <?php } ?>
                           <?php if(isset($_GET['category']) && ($_GET['category'] == 2 || $_GET['category'] == 3)){ ?> 
                           <div style="display: block; border: 1px solid #ddd;">
                           <div class="row" style="padding: 5px;">
                                <div class="col-md-12">
                                    <h4><b>Stocks, Bonds</b></h4><br />
                                </div>
                           </div>
                           
                           <div class="row" style="padding: 5px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="radio" class="radio" name="stocks_bonds" value="1" style="display: inline;" <?php if($st_bo==1){echo "checked='checked'";}?>/>&nbsp;<label>Listed </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    
                                        <input type="radio" class="radio" name="stocks_bonds"  value="2" style="display: inline;" <?php if($st_bo==2){echo "checked='checked'";}?>/>&nbsp;<label>OTC </label>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <br />
                           <?php } ?>
                           <?php if(isset($_GET['category']) && ($_GET['category'] == 3 || $_GET['category'] == 7 || $_GET['category'] == 8)){ ?>
                           <div style="display: block; border: 1px solid #ddd;">
                            <div class="row" style="padding: 5px;">
                            
                                <div class="col-md-12">
                                    <h4><b>CDs, UITs, Bonds</b></h4><br />
                                </div>
                            </div>
                           
                            <div class="row" style="padding: 5px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Maturity Date </label><br />
                                        <div id="demo-dp-range">
                                            <div class="input-daterange input-group" id="datepicker">
                                                <input type="text" value="<?php echo $m_date; ?>" name="maturity_date" id="maturity_date" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type </label><br />
                                        <select class="form-control" name="type">
                                            <option value="">Select Type...</option>
                                            <option value="1" <?php if($type==1){echo "selected='selected'";} ?>>Government Municipal</option>
                                            <option value="2" <?php if($type==2){echo "selected='selected'";} ?>>Corporate</option>
                                            <option value="3" <?php if($type==3){echo "selected='selected'";} ?>>Treasury</option>
                                            <option value="4" <?php if($type==4){echo "selected='selected'";} ?>>Zero Coupon</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <br /> 
                           <?php } ?>
                           <?php if(isset($_GET['category']) && $_GET['category'] == 4){ ?> 
                           <div style="display: block; border: 1px solid #ddd;">
                            <div class="row" style="padding: 5px;">
                           
                                <div class="col-md-12">
                                    <h4><b>Variable Annuities</b></h4><br />
                                </div>
                            </div>
                           <div class="row" style="padding: 5px;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="radio" class="radio" name="variable_annuities" value="1" style="display: inline;" <?php if($var==1){echo "checked='checked'";}?>/>&nbsp;<label>Single </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    
                                        <input type="radio" class="radio" name="variable_annuities"  value="2" style="display: inline;" <?php if($var==2){echo "checked='checked'";}?>/>&nbsp;<label>Recurring </label>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <br />
                           <?php } ?>
                           <?php if(isset($_GET['category']) && ($_GET['category'] == 9 || $_GET['category'] == 10)){ ?>
                           <div style="display: block; border: 1px solid #ddd;">
                            <div class="row" style="padding: 5px;">
                                <div class="col-md-12">
                                    <h4><b>Partnerships, Alternative Investments, REITs, Agency Tax Credits, Hedge Funds</b></h4><br />
                                </div>
                            </div>
                           
                            <div class="row" style="padding: 5px;">
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Registration Type </label><br />
                                        <select class="form-control" name="registration_type">
                                            <option value="">Select Registration Type</option>
                                            <option value="1" <?php if($reg_type==1){echo "selected='selected'";} ?>>Public Real Estate</option>
                                            <option value="2" <?php if($reg_type==2){echo "selected='selected'";} ?>>Private Real Estate</option>
                                            <option value="3" <?php if($reg_type==3){echo "selected='selected'";} ?>>Public Oil &amp; Gas</option>
                                            <option value="4" <?php if($reg_type==4){echo "selected='selected'";} ?>>Private Oil &amp; Gas</option>
                                            <option value="5" <?php if($reg_type==5){echo "selected='selected'";} ?>>Public Leasing</option>
                                            <option value="6" <?php if($reg_type==6){echo "selected='selected'";} ?>>Private Leasing</option>
                                            <option value="7" <?php if($reg_type==7){echo "selected='selected'";} ?>>Public Mortgage</option>
                                            <option value="8" <?php if($reg_type==8){echo "selected='selected'";} ?>>Private Mortgage</option>
                                            <option value="9" <?php if($reg_type==9){echo "selected='selected'";} ?>>Public Raw Land</option>
                                            <option value="10" <?php if($reg_type==10){echo "selected='selected'";} ?>>Private Raw Land</option>
                                            <option value="11" <?php if($reg_type==11){echo "selected='selected'";} ?>>REIT</option>
                                            <option value="12" <?php if($reg_type==12){echo "selected='selected'";} ?>>Subsidized Housing</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                          </div><br />
                          <?php } ?>
                    </div>  
                    <div class="tab-pane" id="tab_bb"> 
                        <div class="row">
                                <div class="col-md-12">
                                    <h4><b>Suitability</b></h4><br />
                                </div>
                           </div>
                           <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Income </label><br />
                                        <input type="text" maxlength="9" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control" value="<?php echo $income; ?>"  name="income"  />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Net Worth </label><br />
                                        <input type="text" maxlength="9" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control" value="<?php echo $networth; ?>"  name="networth"  />
                                    </div>
                                </div>
                            </div>
                           <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Net Worth Only </label><br />
                                        <input type="text" maxlength="9" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control" value="<?php echo $networthonly; ?>"  name="networthonly"  />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Minimum Investment </label><br />
                                        <input type="text" maxlength="9" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control" name="minimum_investment" value="<?php echo $minimum_investment; ?>"  />
                                    </div>
                                </div>
                            </div>
                           <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Minimum Offer </label><br />
                                        <input type="text" maxlength="9" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control" name="minimum_offer" value="<?php echo $minimum_offer; ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Maximum Offer </label><br />
                                        <input type="text" maxlength="9" value="<?php echo $maximum_offer; ?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control" name="maximum_offer"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Objectives </label><br />
                                        <select class="form-control" name="objectives">
                                            <option value="">Select Objectives</option>
                                            <option value="1" <?php if($objective==1){echo "selected='selected'";} ?>>Growth</option>
                                            <option value="2" <?php if($objective==2){echo "selected='selected'";} ?>>Income</option>
                                            <option value="3" <?php if($objective==3){echo "selected='selected'";} ?>>Growth &amp; Income</option>
                                            <option value="4" <?php if($objective==4){echo "selected='selected'";} ?>>Speculative</option>
                                            <option value="5" <?php if($objective==5){echo "selected='selected'";} ?>>Preservation of Capital</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                    </div>
                   
                </div>
                <div class="panel-footer fixedbtmenu">
                        <div class="selectwrap">
                            <?php if(isset($_GET['cusip_number']) && ($_GET['cusip_number'] != '' || $_GET['cusip_number'] == '')){?>
                            <input type="hidden" name="for_import" id="for_import" class="form-control" value="true" />
                            <input type="hidden" name="file_id" id="file_id" class="form-control" value="<?php echo $_GET['file_id']; ?>" />
                            <input type="hidden" name="temp_data_id" id="temp_data_id" class="form-control" value="<?php echo $_GET['exception_data_id']; ?>" />
                            <?php }?>
                            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                            <?php if($_GET['action']=='edit_product' && $_GET['id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=previous&category=<?php echo $category;?>" class="previous next_previous_a" style="float: left;"><input type="button" name="Previous" value="&laquo; Previous" /></a><?php } ?>
        					<?php if($_GET['action']=='edit_product' && $_GET['id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=next&category=<?php echo $category;?>" class="next next_previous_a"><input type="button" name="Next" value="Next &raquo;" /></a><?php } ?>
                            
                            <?php if($action=='edit_product' && $id>0){?>
                            <a href="#view_changes" data-toggle="modal"><input type="button" name="view_changes" value="View Changes" style="margin-left: 10%;"/></a>
                            <?php } ?>
                            <a href="#product_notes" data-toggle="modal"><input type="button" onclick="get_product_notes();" name="notes" value="Notes" /></a>
                            <a href="#product_transactions" data-toggle="modal"><input type="button" name="attach" value="Transactions" /></a>
                            <a href="#product_attach" data-toggle="modal"><input type="button"  onclick="get_product_attach();" name="attach" value="Attachments" style="margin-right: 10%;"/></a>
                            <a href="<?php echo CURRENT_PAGE.'?action=view_product';?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
                            <input type="submit" name="product" onclick="waitingDialog.show();" value="Save" style="float: right;"/>
                        </div>
                    </div>
			    </form> 
                <?php
                    }
                ?> 
            <!-- Lightbox strart -->							
            	<!-- Modal for add client notes -->
            	<div id="product_notes" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            		<div class="modal-dialog">
            		<div class="modal-content">
            		<div class="modal-header" style="margin-bottom: 0px !important;">
            			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            			<h4 class="modal-title">Branch's Notes</h4>
            		</div>
            		<div class="modal-body">
                    
                    <div class="inputpopup">
                        <a class="btn btn-sm btn-success" style="float: right !important; margin-right: 5px !important;" onclick="open_newnotes();"><i class="fa fa-plus"></i> Add New</a></li>
            		</div>
                    
                    <div class="col-md-12">
                        <div id="msg_notes">
                        </div>
                    </div>
                   
                    <div class="inputpopup">
                        <div class="table-responsive" id="ajax_notes" style="margin: 0px 5px 0px 5px;">
                            
                        </div>
            		</div>
                    </div><!-- End of Modal body -->
            		</div><!-- End of Modal content -->
            		</div><!-- End of Modal dialog -->
            </div><!-- End of Modal -->
            <!-- Lightbox strart -->							
			<!-- Lightbox strart -->							
	<!-- Modal for add client notes -->
	<div id="product_attach" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header" style="margin-bottom: 0px !important;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h4 class="modal-title">Product's Attach</h4>
		</div>
		<div class="modal-body">
        
        <div class="inputpopup">
            <a class="btn btn-sm btn-success" style="float: right !important; margin-right: 5px !important;" onclick="open_newattach();"><i class="fa fa-plus"></i> Add New</a></li>
		</div>
        
        <div class="col-md-12">
            <div id="msg_attach">
            </div>
        </div>
       
        <div class="inputpopup">
            <div class="table-responsive" id="ajax_attach" style="margin: 0px 5px 0px 5px;">
                
            </div>
		</div>
        </div><!-- End of Modal body -->
		</div><!-- End of Modal content -->
		</div><!-- End of Modal dialog -->
</div><!-- End of Modal -->
<!-- Lightbox strart -->							
<!-- Modal for transaction list -->
<div id="product_transactions" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
	<div class="modal-content">
	<div class="modal-header" style="margin-bottom: 0px !important;">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
		<h4 class="modal-title">Product's Transactions</h4>
	</div>
	<div class="modal-body">
    <form method="post">
    <div class="inputpopup">
        <div class="table-responsive" id="table-scroll" style="margin: 0px 5px 0px 5px;">
            <table class="table table-bordered table-stripped table-hover">
                <thead>
                    <th>Trade No</th>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Client No</th>
                    <th>Trade Amount</th>
                </thead>
                <tbody>
                <?php foreach($get_product_transactions As $key_trans=>$val_trans){?>
                    <tr>
                        <td><?php echo $val_trans['id'];?></td>
                        <td><?php echo date('m/d/Y',strtotime($val_trans['trade_date']));?></td>
                        <td><?php echo $val_trans['product_name'];?></td>
                        <td><?php echo $val_trans['client_number'];?></td>
                        <td><?php echo '$'.$val_trans['charge_amount'];?></td>
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
              <!-- Lightbox strart -->							
                <!-- Modal for transaction list -->
                <div id="view_changes" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                	<div class="modal-dialog">
                	<div class="modal-content">
                	<div class="modal-header" style="margin-bottom: 0px !important;">
                		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                		<h4 class="modal-title">View Changes</h4>
                	</div>
                	<div class="modal-body">
                    <form method="post">
                    <div class="inputpopup">
                        <div class="table-responsive" id="table-scroll" style="margin: 0px 5px 0px 5px;">
                            <table class="table table-bordered table-stripped table-hover">
                                <thead>
                                    <th>User Initials</th>
                                    <th>Date of Change</th>
                                    <th>Field Changed</th>
                                    <th>Previous Value</th>
                                    <th>New Value</th>
                                </thead>
                                <tbody>
                                <?php 
                                $count = 0;
                                $feild_name='';
                                $lable_array = array();
                                $lable_array = array('name' => 'Product Name','sponsor' => 'Sponsor','ticker_symbol' => 'Ticker Symbol','cusip' => 'CUSIP','security' => 'Security Number',
                                'receive' => 'Allowable Receivable','income' => 'Income','networth' => 'Net Worth','networthonly' => 'Net Worth Only','minimum_investment' => 'Minimum Investment','minimum_offer' => 'Minimum Offer','maximum_offer' => 'Maximum Offer','objective' => 'Objectives', 'non_commissionable' => 'Non-Commissionable','class_type' => 'Class Type','fund_code' => 'Fund Code','min_rate' => 'Min Rate','max_rate' => 'Max Rate',
                                'min_threshold' => 'Min Threshold','max_threshold' => 'Max Threshold','sweep_fee' => 'Waive Sweep Fee','ria_specific' => 'Investment Banking Type','ria_specific_type' => 'RIA Type','based' => 'Based Type','fee_rate' => 'Fee Rate','st_bo' => 'Stocks, Bonds',
                                'm_date' => 'Maturity Date','type' => 'Type','var' => 'Variable Annuities','reg_type' => 'Registration Type');
                                foreach($product_data as $key=>$val){
                                    
                                    if(isset($lable_array[$val['field']])){
                                        $feild_name = $lable_array[$val['field']];
                                    }else {
                                        $feild_name = $val['field'];
                                    }?>
                                    <tr>
                                    
                                        <td><?php echo $val['user_initial'];?></td>
                                        <td><?php echo date('m/d/Y',strtotime($val['modified_time']));?></td>
                                        <td><?php echo $feild_name;?></td>
                                        <?php 
                                        if($feild_name == 'Allowable Receivable' && $val['old_value'] == 0){?>
                                        <td><?php echo 'UnChecked';?></td>
                                        <td><?php echo 'Checked';?></td>
                                        <?php } 
                                        else if($feild_name == 'Allowable Receivable' && $val['old_value'] == 1){?>
                                        <td><?php echo 'Checked';?></td>
                                        <td><?php echo 'UnChecked';?></td>
                                        <?php }
                                        else if($feild_name == 'Non-Commissionable' && $val['old_value'] == 0){?>
                                        <td><?php echo 'UnChecked';?></td>
                                        <td><?php echo 'Checked';?></td>
                                        <?php } 
                                        else if($feild_name == 'Non-Commissionable' && $val['old_value'] == 1){?>
                                        <td><?php echo 'Checked';?></td>
                                        <td><?php echo 'UnChecked';?></td>
                                        <?php }
                                        else if($feild_name == 'Class Type'){?>
                                        <td>
                                        <?php 
                                        if($val['old_value'] == 1)
                                        {
                                            echo 'A';
                                        }
                                        else if($val['old_value'] == 2)
                                        {
                                            echo 'B';
                                        }
                                        else if($val['old_value'] == 3)
                                        {
                                            echo 'C';
                                        }
                                        else if($val['old_value'] == 4)
                                        {
                                            echo 'other';
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                        if($val['new_value'] == 1)
                                        {
                                            echo 'A';
                                        }
                                        else if($val['new_value'] == 2)
                                        {
                                            echo 'B';
                                        }
                                        else if($val['new_value'] == 3)
                                        {
                                            echo 'C';
                                        }
                                        else if($val['new_value'] == 4)
                                        {
                                            echo 'other';
                                        }
                                        ?>
                                        </td>
                                        <?php }
                                        else if($feild_name == 'Waive Sweep Fee' && $val['old_value'] == 0){?>
                                        <td><?php echo 'UnChecked';?></td>
                                        <td><?php echo 'Checked';?></td>
                                        <?php } 
                                        else if($feild_name == 'Waive Sweep Fee' && $val['old_value'] == 1){?>
                                        <td><?php echo 'Checked';?></td>
                                        <td><?php echo 'UnChecked';?></td>
                                        <?php }
                                        else if($feild_name == 'Investment Banking Type'){?>
                                        <td>
                                        <?php 
                                        if($val['old_value'] == 1)
                                        {
                                            echo 'IPO';
                                        }
                                        else if($val['old_value'] == 2)
                                        {
                                            echo 'Bridge';
                                        }
                                        else if($val['old_value'] == 3)
                                        {
                                            echo 'Reg S';
                                        }
                                        else if($val['old_value'] == 4)
                                        {
                                            echo 'Reg D';
                                        }
                                        else if($val['old_value'] == 5)
                                        {
                                            echo 'Private Placement';
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                        if($val['new_value'] == 1)
                                        {
                                            echo 'IPO';
                                        }
                                        else if($val['new_value'] == 2)
                                        {
                                            echo 'Bridge';
                                        }
                                        else if($val['new_value'] == 3)
                                        {
                                            echo 'Reg S';
                                        }
                                        else if($val['new_value'] == 4)
                                        {
                                            echo 'Reg D';
                                        }
                                        else if($val['new_value'] == 5)
                                        {
                                            echo 'Private Placement';
                                        }
                                        ?>
                                        </td>
                                        <?php }
                                        else if($feild_name == 'RIA Type'){?>
                                        <td>
                                        <?php 
                                        if($val['old_value'] == 1)
                                        {
                                            echo 'Fee Based Mutual Funds';
                                        }
                                        else if($val['old_value'] == 2)
                                        {
                                            echo 'Fee Based Stocks, Bonds &amp; Mutual Funds';
                                        }
                                        else if($val['old_value'] == 3)
                                        {
                                            echo 'Financial Planning';
                                        }
                                        else if($val['old_value'] == 4)
                                        {
                                            echo 'Money Managers';
                                        }
                                        else if($val['old_value'] == 5)
                                        {
                                            echo 'Non-Discretionary';
                                        }
                                        else if($val['old_value'] == 6)
                                        {
                                            echo 'Socially Screened';
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                        if($val['new_value'] == 1)
                                        {
                                            echo 'Fee Based Mutual Funds';
                                        }
                                        else if($val['new_value'] == 2)
                                        {
                                            echo 'Fee Based Stocks, Bonds &amp; Mutual Funds';
                                        }
                                        else if($val['new_value'] == 3)
                                        {
                                            echo 'Financial Planning';
                                        }
                                        else if($val['new_value'] == 4)
                                        {
                                            echo 'Money Managers';
                                        }
                                        else if($val['new_value'] == 5)
                                        {
                                            echo 'Non-Discretionary';
                                        }
                                        else if($val['new_value'] == 6)
                                        {
                                            echo 'Socially Screened';
                                        }
                                        ?>
                                        </td>
                                        <?php }
                                        else if($feild_name == 'Based Type'){?>
                                        <td>
                                        <?php 
                                        if($val['old_value'] == 1)
                                        {
                                            echo 'Asset Based';
                                        }
                                        else if($val['old_value'] == 2)
                                        {
                                            echo 'Fee Based';
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                        if($val['new_value'] == 1)
                                        {
                                            echo 'Asset Based';
                                        }
                                        else if($val['new_value'] == 2)
                                        {
                                            echo 'Fee Based';
                                        }
                                        ?>
                                        </td>
                                        <?php }
                                        else if($feild_name == 'Stocks, Bonds'){?>
                                        <td>
                                        <?php 
                                        if($val['old_value'] == 1)
                                        {
                                            echo 'Listed';
                                        }
                                        else if($val['old_value'] == 2)
                                        {
                                            echo 'OTC';
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                        if($val['new_value'] == 1)
                                        {
                                            echo 'Listed';
                                        }
                                        else if($val['new_value'] == 2)
                                        {
                                            echo 'OTC';
                                        }
                                        ?>
                                        </td>
                                        <?php }
                                        else if($feild_name == 'Type'){?>
                                        <td>
                                        <?php 
                                        if($val['old_value'] == 1)
                                        {
                                            echo 'Government Municipal';
                                        }
                                        else if($val['old_value'] == 2)
                                        {
                                            echo 'Corporate';
                                        }
                                        else if($val['old_value'] == 3)
                                        {
                                            echo 'Treasury';
                                        }
                                        else if($val['old_value'] == 4)
                                        {
                                            echo 'Zero Coupon';
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                        if($val['new_value'] == 1)
                                        {
                                            echo 'Government Municipal';
                                        }
                                        else if($val['new_value'] == 2)
                                        {
                                            echo 'Corporate';
                                        }
                                        else if($val['new_value'] == 3)
                                        {
                                            echo 'Treasury';
                                        }
                                        else if($val['new_value'] == 4)
                                        {
                                            echo 'Zero Coupon';
                                        }
                                        ?>
                                        </td>
                                        <?php }
                                        else if($feild_name == 'Variable Annuities'){?>
                                        <td>
                                        <?php 
                                        if($val['old_value'] == 1)
                                        {
                                            echo 'Single';
                                        }
                                        else if($val['old_value'] == 2)
                                        {
                                            echo 'Recurring';
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                        if($val['new_value'] == 1)
                                        {
                                            echo 'Single';
                                        }
                                        else if($val['new_value'] == 2)
                                        {
                                            echo 'Recurring';
                                        }
                                        ?>
                                        </td>
                                        <?php }
                                        else if($feild_name == 'Registration Type'){?>
                                        <td>
                                        <?php 
                                        if($val['old_value'] == 1)
                                        {
                                            echo 'Public Real Estate';
                                        }
                                        else if($val['old_value'] == 2)
                                        {
                                            echo 'Private Real Estate';
                                        }
                                        else if($val['old_value'] == 3)
                                        {
                                            echo 'Public Oil &amp; Gas';
                                        }
                                        else if($val['old_value'] == 4)
                                        {
                                            echo 'Private Oil &amp; Gas';
                                        }
                                        else if($val['old_value'] == 5)
                                        {
                                            echo 'Public Leasing';
                                        }
                                        else if($val['old_value'] == 6)
                                        {
                                            echo 'Private Leasing';
                                        }
                                        else if($val['old_value'] == 7)
                                        {
                                            echo 'Public Mortgage';
                                        }
                                        else if($val['old_value'] == 8)
                                        {
                                            echo 'Private Mortgage';
                                        }
                                        else if($val['old_value'] == 9)
                                        {
                                            echo 'Public Raw Land';
                                        }
                                        else if($val['old_value'] == 10)
                                        {
                                            echo 'Private Raw Land';
                                        }
                                        else if($val['old_value'] == 11)
                                        {
                                            echo 'REIT';
                                        }
                                        else if($val['old_value'] == 12)
                                        {
                                            echo 'Subsidized Housing';
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                        if($val['new_value'] == 1)
                                        {
                                            echo 'Public Real Estate';
                                        }
                                        else if($val['new_value'] == 2)
                                        {
                                            echo 'Private Real Estate';
                                        }
                                        else if($val['new_value'] == 3)
                                        {
                                            echo 'Public Oil &amp; Gas';
                                        }
                                        else if($val['new_value'] == 4)
                                        {
                                            echo 'Private Oil &amp; Gas';
                                        }
                                        else if($val['new_value'] == 5)
                                        {
                                            echo 'Public Leasing';
                                        }
                                        else if($val['new_value'] == 6)
                                        {
                                            echo 'Private Leasing';
                                        }
                                        else if($val['new_value'] == 7)
                                        {
                                            echo 'Public Mortgage';
                                        }
                                        else if($val['new_value'] == 8)
                                        {
                                            echo 'Private Mortgage';
                                        }
                                        else if($val['new_value'] == 9)
                                        {
                                            echo 'Public Raw Land';
                                        }
                                        else if($val['new_value'] == 10)
                                        {
                                            echo 'Private Raw Land';
                                        }
                                        else if($val['new_value'] == 11)
                                        {
                                            echo 'REIT';
                                        }
                                        else if($val['new_value'] == 12)
                                        {
                                            echo 'Subsidized Housing';
                                        }
                                        ?>
                                        </td>
                                        <?php }
                                        else if($feild_name == 'Objectives'){?>
                                        <td>
                                        <?php 
                                        if($val['old_value'] == 1)
                                        {
                                            echo 'Growth';
                                        }
                                        else if($val['old_value'] == 2)
                                        {
                                            echo 'Income';
                                        }
                                        else if($val['old_value'] == 3)
                                        {
                                            echo 'Growth &amp; Income';
                                        }
                                        else if($val['old_value'] == 4)
                                        {
                                            echo 'Speculative';
                                        }
                                        else if($val['old_value'] == 5)
                                        {
                                            echo 'Preservation of Capital';
                                        }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                        if($val['new_value'] == 1)
                                        {
                                            echo 'Growth';
                                        }
                                        else if($val['new_value'] == 2)
                                        {
                                            echo 'Income';
                                        }
                                        else if($val['new_value'] == 3)
                                        {
                                            echo 'Growth &amp; Income';
                                        }
                                        else if($val['new_value'] == 4)
                                        {
                                            echo 'Speculative';
                                        }
                                        else if($val['new_value'] == 5)
                                        {
                                            echo 'Preservation of Capital';
                                        }
                                        ?>
                                        </td>
                                        <?php }
                                        else if($feild_name == 'Sponsor'){
                                        $sponsor_fname = $instance->get_sponsor_name($val['old_value']);?>
                                        <td><?php echo $sponsor_fname['sponsor'];?></td>
                                        <?php $sponsor_fname = $instance->get_sponsor_name($val['new_value']);?>
                                        <td><?php echo $sponsor_fname['sponsor'];?></td>
                                        <?php } else {?>
                                        <td><?php echo $val['old_value'];?></td>
                                        <td><?php echo $val['new_value'];?></td>
                                        <?php } ?>
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
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 4 ] }, 
                        { "bSearchable": false, "aTargets": [ 4 ] }]
        });
        
        $("div.toolbar").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
                            '<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_product&category=<?php echo $category; ?>"><i class="fa fa-plus"></i> Add New</a></li>'+
                            '<li><a href="<?php echo CURRENT_PAGE; ?>?action=select_cat"><i class="fa fa-minus"></i> Back To Category</a></li>'+
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
</style>
<script>
function set_Category(val){
    var category = val;
    var a = document.getElementById("product_category").value;
    alert(a);
}
</script>
<script>
function round(feerate)
{
    if(feerate>100)
    {
        var rounded = 99.9;
    }
    else
    {
        var round = Math.round( feerate * 10 ) / 10;
        var rounded = round.toFixed(1);
    }
    document.getElementById("fee_rate").value = rounded;
    
}
function minrate(feerate)
{
    if(feerate>100)
    {
        var rounded = 99.9;
    }
    else
    {
        var round = Math.round( feerate * 10 ) / 10;
        var rounded = round.toFixed(1);
    }
    document.getElementById("min_rate").value = rounded;
    
}
function maxrate(feerate)
{
    if(feerate>100)
    {
        var rounded = 99.9;
    }
    else
    {
        var round = Math.round( feerate * 10 ) / 10;
        var rounded = round.toFixed(1);
    }
    document.getElementById("max_rate").value = rounded;
    
}
function minthreshold(feerate)
{
    if(feerate>99999999)
    {
        var rounded = 99999999;
    }
    else if(feerate == '')
    {
        var rounded = 0;
    }
    else{
        var rounded = feerate;
    }
    document.getElementById("min_threshold").value = rounded;
    
}
function maxthreshold(feerate)
{
    if(feerate>99999999)
    {
        var rounded = 99999999;
    }
    else if(feerate == '')
    {
        var rounded = 0;
    }
    else{
        var rounded = feerate;
    }
    document.getElementById("max_threshold").value = rounded;
    
}
</script>
<script>
function open_newnotes()
{
    document.getElementById("add_row_notes").style.display = "";
}
function open_newattach()
{
    document.getElementById("add_row_attach").style.display = "";
}
</script>
<script>
function get_product_notes(){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("ajax_notes").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_product_notes.php", true);
        xmlhttp.send();
}
function get_product_attach(){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("ajax_attach").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_product_attach.php", true);
        xmlhttp.send();
}
function openedit(note_id){
    
    var frm_element = document.getElementById("add_client_notes_"+note_id);
    //var ele = frm_element.getElementById("client_note");
    name = frm_element.elements["client_note"].removeAttribute("style"); 
    //$(name).css('pointer-events','');
    console.log(name);
}
</script>
<script>
//submit share form data
function notessubmit(note_id)
{
   $('#msg').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');

   var url = "product_cate.php"; // the script where you handle the form input.
   //alert("#add_client_notes_"+note_id);
   $.ajax({
      type: "POST",
      url: url,
      data: $("#add_client_notes_"+note_id).serialize(), // serializes the form's elements.
      success: function(data){
          if(data=='1'){
            
            get_product_notes();
            $('#msg_notes').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Data Successfully Saved.</div>');
            //window.location.href = "client_maintenance.php";//get_client_notes();   
          }
          else{
               $('#msg_notes').html('<div class="alert alert-danger">'+data+'</div>');
          }
          
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
           $('#msg_notes').html('<div class="alert alert-danger">Something went wrong, Please try again.</div>')
      }
      
   });

   //e.preventDefault(); // avoid to execute the actual submit of the form.
   return false;
       
}
function attachsubmit(attach_id)
{ 
        var myForm = document.getElementById('add_client_attach_'+attach_id);
        form_data = new FormData(myForm);
        $.ajax({
            url: 'product_cate.php', // point to server-side PHP script 
            
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                  if(data=='1'){
                    
                    get_product_attach();
                    $('#msg_attach').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Data Successfully Saved.</div>');
                    //window.location.href = "client_maintenance.php";//get_client_attach();   
                  }
                  else{
                       $('#msg_attach').html('<div class="alert alert-danger">'+data+'</div>');
                  } 
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                   $('#msg_attach').html('<div class="alert alert-danger">Something went wrong, Please try again.</div>')
              }
        });
               
        
   return false; 
       
}
function delete_notes(note_id){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = this.responseText;
                if(data=='1'){
                   get_product_notes(); 
                   $('#msg_notes').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Note deleted Successfully.</div>');
                   //get_client_notes();
                  
                  }
                  else{
                       $('#msg_notes').html('<div class="alert alert-danger">'+data+'</div>');
                  }
                
            }
        };
        xmlhttp.open("GET", "product_cate.php?delete_action=delete_notes&note_id="+note_id, true);
        xmlhttp.send();
}
function delete_attach(attach_id){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = this.responseText;
                if(data=='1'){
                   get_product_attach(); 
                   $('#msg_attach').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Attach deleted Successfully.</div>');
                  }
                  else{
                       $('#msg_attach').html('<div class="alert alert-danger">'+data+'</div>');
                  }
            }
        };
        xmlhttp.open("GET", "product_cate.php?delete_action=delete_attach&attach_id="+attach_id, true);
        xmlhttp.send();
}


$('#demo-dp-range .input-daterange').datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
    });
function checkLength(el) {
  if (el.value.length != 2) {
    alert("length grater than 2 characters")
  }
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
		 * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "md", "m";
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
<style>
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
    }
</style>