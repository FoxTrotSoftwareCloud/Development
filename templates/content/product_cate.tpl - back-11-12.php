<script>
function addMoreNotes(){
    var html = '<tr class="add_row_notes">'+
                    '<td>2</td>'+
                    '<td name="date"><?php echo date('d/m/Y');?></td>'+
                    '<td name="user"><?php echo $_SESSION['user_name'];?></td>'+
                    '<td name="notes"><input type="text" name="client_note" class="form-control" id="client_note"/></td>'+
                    '<td class="text-center">'+
                    '<a href="<?php echo CURRENT_PAGE; ?>?action=add" class="btn btn-sm btn-warning"><i class="fa fa-save"></i> Save</a>&nbsp;'+
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
<div class="container">
<h1>Product Maintenance</h1>
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
<div class="col-lg-12 well">
    <ul class="nav nav-pills nav-stacked col-md-2">
      <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_product') || (isset($_GET['action'])&& $_GET['action']=='view_product') || (isset($_GET['action'])&& $_GET['action']=='edit_product')){ ?> class="active"<?php }if($action=='view_product'){?>class="active" <?php }?>><a href="<?php echo CURRENT_PAGE; ?>?action=view_product" >Products</a></li>
      <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_sponsor')||(isset($_GET['action'])&& $_GET['action']=='edit_sponsor') || (isset($_GET['action'])&& $_GET['action']=='view_sponsor') ){ ?> class="active"<?php }?>><a href="<?php echo CURRENT_PAGE; ?>?action=view_sponsor">Sponsors</a></li>
    </ul>
    <div class="tab-content col-md-10">
            <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_product') || (isset($_GET['action'])&& $_GET['action']=='view_product')|| $action=='view_product' || (isset($_GET['action'])&& $_GET['action']=='edit_product')){?>active<?php }?>" id="tab_a">
            <?php
             if($action=='add_product' || ($action=='edit_product' && $id>0)){
                ?>            
                <form name="frm" method="POST" onsubmit="return validation();" enctype="multipart/form-data">
					<div class="row">
                        <div class="panel-control" style="float: right;">
							<div class="btn-group dropdown">
								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
								<ul class="dropdown-menu dropdown-menu-right" style="">
									<li><a href="<?php echo CURRENT_PAGE; ?>?view_product"><i class="fa fa-eye"></i> View List</a></li>
								</ul>
							</div>
						</div>
                        <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_product'?'Add':'Edit'; ?> Product</h3>
					</div><br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Category </label><br />
                                <select class="form-control" name="product_category">
                                    <option value="">Select Category</option>
                                    <?php foreach($product_category as $key=>$val){?>
                                    <option value="<?php echo $val['id'];?>" <?php if($category==$val['id']){echo "selected='selected'";} ?>><?php echo $val['type'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Name </label><br />
                                <input type="text" maxlength="40" class="form-control" name="name" value="<?php echo $name; ?>"  />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sponsor </label><br />
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
                                <input type="text" maxlength="11" class="form-control" name="cusip" value="<?php echo $cusip; ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Security Number </label><br />
                                <input type="text" maxlength="10" class="form-control" name="security" value="<?php echo $security; ?>"   />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Allowable Receivable </label><br />
                                <input type="checkbox" class="checkbox" name="allowable_receivable" id="allowable_receivable" value="1" style="display: inline;" <?php if($receive>0){echo "checked='checked'";}?>  />
                            </div>
                        </div>
                    </div>
                   
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Non-Commissionable </label><br />
                                <input type="checkbox"  class="checkbox" name="non_commissionable" id="non_commissionable" value="1" style="display: inline;" <?php if($non_commissionable>0){echo "checked='checked'";}?>/>
                            </div>
                        </div>
                    </div><br />
                    
                   <div class="row">
                        <div class="col-md-12">
                            <h4><b>Mutual Funds</b></h4><br />
                        </div>
                   </div>
                   
                   <div class="row">
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
                    </div>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Waive Sweep Fee </label><br />
                                <input type="checkbox" class="checkbox" name="sweep_fee" id="sweep_fee" value="1" style="display: inline;" <?php if($sweep_fee>0){echo "checked='checked'";}?>/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Threshold </label><br />
                                <input type="text" value="<?php echo $threshold; ?>"  maxlength="9" class="form-control" name="threshold"  />
                            </div>
                        </div>
                    </div>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rate </label><br />
                                <input type="text" value="<?php echo $rate; ?>"   maxlength="5" class="form-control" name="rate"  />
                            </div>
                        </div>
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
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>RIA specific Type </label><br />
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
                                <label>Based Type </label><br />
                                <input type="radio"  name="based_type" class="radio"  value="1" style="display: inline;" <?php if($based==1){echo "checked='checked'";}?>/>&nbsp;<label>Asset Based</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio"  name="based_type" class="radio"  value="2" style="display: inline;" <?php if($based==2){echo "checked='checked'";}?>/>&nbsp;<label>Fee Based</label>
                            </div>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fee Rate </label><br />
                                <input type="text" value="<?php echo $fee_rate; ?>"  maxlength="5"   class="form-control" name="fee_rate"  />
                            </div>
                        </div>
                    </div><br />   
                   
                   <div class="row">
                        <div class="col-md-12">
                            <h4><b>Stocks, Bonds</b></h4><br />
                        </div>
                   </div>
                   
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="radio" class="radio" name="stocks_bonds" value="1" style="display: inline;" <?php if($st_bo==1){echo "checked='checked'";}?>/>&nbsp;<label>Listed </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                                <input type="radio" class="radio" name="stocks_bonds"  value="2" style="display: inline;" <?php if($st_bo==2){echo "checked='checked'";}?>/>&nbsp;<label>OTC </label>
                            </div>
                        </div>
                    </div><br />
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h4><b>CDs, UITs, Bonds</b></h4><br />
                        </div>
                    </div>
                   
                    <div class="row">
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
                    </div> <br />  
                   
                   <div class="row">
                        <div class="col-md-12">
                            <h4><b>Variable Annuities</b></h4><br />
                        </div>
                   </div>
                   
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="radio" class="radio" name="variable_annuities" value="1" style="display: inline;" <?php if($var==1){echo "checked='checked'";}?>/>&nbsp;<label>Single </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                                <input type="radio" class="radio" name="variable_annuities"  value="2" style="display: inline;" <?php if($var==2){echo "checked='checked'";}?>/>&nbsp;<label>Recurring </label>
                            </div>
                        </div>
                    </div><br />
                   
                   <div class="row">
                        <div class="col-md-12">
                            <h4><b>Agency Tax Credits, Alternative Investments, Hedge Funds, Secondary Limited Partnerships</b></h4><br />
                        </div>
                   </div>
                   
                   <div class="row">
                       <div class="col-md-6">
                            <div class="form-group">
                                <label>Registrtion Type </label><br />
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
                        <!--<div class="col-md-6">
                            <div class="form-group">
                                <label></label><br />
                                <a href="#client_notes" data-toggle="modal"><input type="button" name="notes" value="Notes" /></a>
                                <a href="#client_attachment" data-toggle="modal"><input type="button" name="attach" value="Attach" /></a>
                            </div>
                         </div>-->
                    </div>
                   
                    <div class="panel-footer">
                        <div class="selectwrap">
                            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
        					<input type="submit" name="product" onclick="waitingDialog.show();" value="Save"/>	
                            <a href="<?php echo CURRENT_PAGE.'?action=view_product';?>"><input type="button" name="cancel" value="Cancel" /></a>
                        </div>
                    </div>
			    </form> 
                <?php
                    }if(isset($_GET['action'])=='' || $_GET['action']=='view_product' || $action =='view_product'){?>
                <div class="panel">
            		<div class="panel-heading">
                        <div class="panel-control">
                            <div class="btn-group dropdown" style="float: right;">
                                <button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
            					<ul class="dropdown-menu dropdown-menu-right" style="">
            						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_product"><i class="fa fa-plus"></i> Add New</a></li>
            					</ul>
            				</div>
            			</div>
                    </div><br />
            		<div class="panel-body">
                        <div class="panel-control" >
                         <form method="post">
                            <div class="row"> 
                            <div class="col-md-5"></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="search_product_category">
                                        <?php foreach($product_category as $key=>$val){?>
                                        <option value="<?php echo $val['id'];?>" <?php if($search_product_category==$val['id']){echo "selected='selected'";} ?>><?php echo $val['type'];?></option>
                                        <?php } ?>
                                    </select>
                                 </div>
                                 <div class="col-md-4" style="float: right; width: 31.33333333% !important;">   
                                    <input type="text" name="search_text_product" id="search_text_product" value="<?php echo $search_text_product;?>"/>
                                    <button type="submit" name="search_product" id="submit" value="Search"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                        </div><br /><br />
                       <div class="table-responsive">
            			<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            	            <thead>
            	                <tr>
                                    <th class="text-center">#NO</th>
                                    <th>Product Category</th>
                                    <th>Product Name</th>
                                    <th>Sponsor Name</th>
                                    <th>Status</th>
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
                                        <td><?php echo $val['type'];?></td>
                                        <td><?php echo $val['name']; ?></td>
                                        <td><?php echo $val['sponsor']; ?></td>
                                        <td class="text-center">
                                            <?php
                                                if($val['status']==1){
                                                    ?>
                                                    <a href="<?php echo CURRENT_PAGE; ?>?action=product_status&category=<?php echo $val['category']; ?>&id=<?php echo $val['id']; ?>&status=0" class="btn btn-sm btn-success"><i class="fa fa-check-square-o"></i> Enabled</a>
                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                    <a href="<?php echo CURRENT_PAGE; ?>?action=product_status&category=<?php echo $val['category']; ?>&id=<?php echo $val['id']; ?>&status=1" class="btn btn-sm btn-warning"><i class="fa fa-warning"></i> Disabled</a>
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
            </div>
            <!-- Lightbox strart -->							
			<!-- Modal for add client notes -->
    			<div id="client_notes" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    				<div class="modal-dialog">
    				<div class="modal-content">
    				<div class="modal-header" style="margin-bottom: 0px !important;">
    					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
    					<h4 class="modal-title">Client's Notes</h4>
    				</div>
    				<div class="modal-body">
                    <form method="post" id="product_notes" onsubmit="return formsubmitnotes();">
                    <div class="inputpopup">
                        <a class="btn btn-sm btn-success" style="float: right !important; margin-right: 5px !important;" onclick="addMoreNotes();"><i class="fa fa-plus"></i> Add New</a></li>
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
                                    <tr id="add_row_notes">
                                        <td>1</td>
                                        <td><?php echo date('d/m/Y');?></td>
                                        <td><?php echo $_SESSION['user_name'];?></td>
                                        <td><input type="text" name="product_note" class="form-control" id="product_note"/></td>
                                        <td class="text-center">
                                           <a href="<?php echo CURRENT_PAGE; ?>?action=add" class="btn btn-sm btn-warning"><i class="fa fa-save"></i> Save</a>
                                           <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=" class="btn btn-sm btn-primary" ><i class="fa fa-edit"></i> Edit</a>
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
            <div class="tab-pane  <?php if((isset($_GET['action'])&& $_GET['action']=='add_sponsor') || (isset($_GET['action'])&& $_GET['action']=='view_sponsor') || (isset($_GET['action'])&& $_GET['action']=='edit_sponsor')){?>active<?php }?>" id="tab_b">
            <?php  
            if($_GET['action']=='add_sponsor' || ($_GET['action']=='edit_sponsor' && $sponsor_id>0)){
                ?>
                <div class="panel">            
                <form name="frm2" method="POST">
                    <div class="panel-heading">
                        <div class="panel-control" style="float: right;">
							<div class="btn-group dropdown">
								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
								<ul class="dropdown-menu dropdown-menu-right" style="">
									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_sponsor"><i class="fa fa-eye"></i> View List</a></li>
								</ul>
							</div>
						</div>
                        <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action=='add_sponsor'?'Add':'Edit'; ?> Sponsor</h3>
					</div>
                    <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sponsor Name </label><br />
                                <input type="text" maxlength="25" class="form-control" name="sponser_name" value="<?php echo $sponser_name;?>"  />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address 1 </label><br />
                                <input type="text" maxlength="50" class="form-control" name="saddress1"  value="<?php echo $saddress1;?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address 2 </label><br />
                                <input type="text" maxlength="50" class="form-control" name="saddress2" value="<?php echo $saddress2;?>"  />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City </label><br />
                                <input type="text" maxlength="25" class="form-control" name="scity" value="<?php echo $scity;?>"  />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>State </label><br />
                                <select class="form-control" name="sstate">
                                    <option value="">Select State</option>
                                    <?php foreach($get_state as $key=>$val){ ?>
                                        <option value="<?php echo $val['id'];?>" <?php if($sstate == $val['id']){echo "selected='selected'";}?>><?php echo $val['name'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Zip Code </label><br />
                                <input type="text" maxlength="10" class="form-control" name="szip_code" value="<?php echo $szip_code;?>"  />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>E-Mail </label><br />
                                <input type="text" maxlength="50" class="form-control" name="semail" value="<?php echo $semail;?>"  />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Web Site </label><br />
                                <input type="text" maxlength="50" class="form-control" name="swebsite" value="<?php echo $swebsite;?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>General Contact </label><br />
                                <input type="text"  class="form-control" maxlength="30" name="sgeneral_contact" value="<?php echo $sgeneral_contact;?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>General Phone </label><br />
                                <input type="text"  class="form-control" maxlength="13" name="sgeneral_phone" value="<?php echo $sgeneral_phone;?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Operations Contact </label><br />
                                <input type="text"  class="form-control" maxlength="30" name="soperations_contact" value="<?php echo $soperations_contact;?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Operations Phone </label><br />
                                <input type="text"  class="form-control" maxlength="13" name="soperations_phone" value="<?php echo $soperations_phone;?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>DST System ID </label><br />
                                <input type="text" maxlength="15" class="form-control" name="sdst_system_id" value="<?php echo $sdst_system_id;?>"  />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>DST Mgmt Code </label><br />
                                <input type="text" maxlength="15" class="form-control" name="sdst_mgmt_code" value="<?php echo $sdst_mgmt_code;?>"  />
                            </div>
                        </div>
                    </div>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Exclude from DST Importing </label><br />
                                <input type="checkbox" class="checkbox"  name="sdst_import" id="cpa" value="1" style="display: inline;" <?php if($sdst_import>0){echo "checked='checked'"; }?> />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>DAZL Code </label><br />
                                <input type="text" maxlength="15" class="form-control" name="sdazl_code" value="<?php echo $sdazl_code;?>"  />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Exclude from DAZL Importing </label><br />
                                <input type="checkbox" class="checkbox"  name="sdazl_import" id="" value="1" style="display: inline;" <?php if($sdazl_import>0){echo "checked='checked'"; }?> />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>DTCC/NSCC ID </label><br />
                                <input type="text" maxlength="15" class="form-control" name="sdtcc_nscc" value="<?php echo $sdtcc_nscc;?>"  />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Clearing Firm ID </label><br />
                                <input type="text" class="form-control"  name="sclr_firm" value="<?php echo $sclr_firm;?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="selectwrap">
                            <input type="hidden" name="sponsor_id" id="sponsor_id" value="<?php echo $sponsor_id; ?>" />
        					<input type="submit" name="sponser" onclick="waitingDialog.show();" value="Save"/>	
                            <a href="<?php echo CURRENT_PAGE.'?action=view_sponsor';?>"><input type="button" name="cancel" value="Cancel" /></a>
                        </div>
                   </div>
                   </div>
                </form>
                </div>
                <?php
                    }if($_GET['action']=='view_sponsor'){?>
                <div class="panel">
            		<div class="panel-heading">
                        <div class="panel-control">
                            <div class="btn-group dropdown" style="float: right;">
                                <button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
            					<ul class="dropdown-menu dropdown-menu-right" style="">
            						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_sponsor"><i class="fa fa-plus"></i> Add New</a></li>
            					</ul>
            				</div>
            			</div>
                    </div><br />
            		<div class="panel-body">
                    <div class="panel-control" style="float: right;">
                     <form method="post">
                        <input type="text" name="search_text_sponsor" id="search_text_sponsor" value="<?php echo $search_text_sponsor;?>"/>
                        <button type="submit" name="search_sponsor" id="submit" value="Search"><i class="fa fa-search"></i> Search</button>
                    </form>
                    </div><br /><br />
                        <div class="table-responsive">
            			<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            	            <thead>
            	                <tr>
                                    <th class="text-center">#NO</th>
                                    <th>Sponsor Name</th>
                                    <th>Status</th>
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
                                        <td><?php echo $val['name'];?></td>
                                        <td class="text-center">
                                            <?php
                                                if($val['status']==1){
                                                    ?>
                                                    <a href="<?php echo CURRENT_PAGE; ?>?action=sponsor_status&sponsor_id=<?php echo $val['id']; ?>&status=0" class="btn btn-sm btn-success"><i class="fa fa-check-square-o"></i> Enabled</a>
                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                    <a href="<?php echo CURRENT_PAGE; ?>?action=sponsor_status&sponsor_id=<?php echo $val['id']; ?>&status=1" class="btn btn-sm btn-warning"><i class="fa fa-warning"></i> Disabled</a>
                                                    <?php
                                                }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_sponsor&sponsor_id=<?php echo $val['id']; ?>" class="btn btn-md btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=sponsor_delete&sponsor_id=<?php echo $val['id']; ?>');" class="btn btn-md btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
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
<script>
//submit add product notes form data
function formsubmitnotes()
{
    $('#msgnotes').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');

    var url = "product_cate.php"; // the script where you handle the form input.
    //alert($("#add_notes").serialize());
    $.ajax({
       type: "POST",
       url: url,
       data: $("#product_notes").serialize(), // serializes the form's elements.
       success: function(data){
           if(data=='1'){
                window.location.href = "product_cate.php";
                
                /*$('#msgnotes').html('<div class="alert alert-success">Thank you.</div>');
                $('#add_notes')[0].reset();
                setTimeout(function(){
    				$('#myModalShare').modal('hide');				
    			}, 2000);*/
                
           }
           else{
                $('#msgnotes').html('<div class="alert alert-danger">'+data+'</div>');
           }
           
       },
       error: function(XMLHttpRequest, textStatus, errorThrown) {
            $('#msgnotes').html('<div class="alert alert-danger">Something went wrong, Please try again.</div>')
       }
       
    });

    //e.preventDefault(); // avoid to execute the actual submit of the form.
    return false;
        
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