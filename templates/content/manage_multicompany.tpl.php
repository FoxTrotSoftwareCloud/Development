<script src="http://code.jquery.com/color/jquery.color-2.1.2.min.js" integrity="sha256-H28SdxWrZ387Ldn0qogCzFiUDDxfPiNIyJX7BECQkDE=" crossorigin="anonymous"></script>

    
<div class="container">
    <h1 class="<?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'topfixedtitle';}?>">Multi-Company Maintenance</h1>
    <div class="col-lg-12 well <?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'fixedwell';}?>">
        <div class="tab-content col-md-12">
            <div class="tab-pane active" id="tab_a">
    <!-- Add table data and some process -->
    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
    <?php
    if($action=='add_new'||($action=='edit' && $id>0)){
        ?>
        <form method="post">
            <ul class="nav nav-tabs <?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'topfixedtabs';}?>">
                <li class="active"><a href="#tab_aa" data-toggle="tab">General</a></li>
                <li><a href="#tab_bb" data-toggle="tab">Commissions</a></li>
                <li><a href="#tab_cc" data-toggle="tab">Registrations</a></li>

    			<div class="btn-group dropdown" style="float: right;">
    				<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
    				<ul class="dropdown-menu dropdown-menu-right" style="">
    					<li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-eye"></i> View List</a></li>
    				</ul>
    			</div>
    		  
            </ul>
            <div class="tab-content panel"> 
            
                 
                <div class="tab-pane active" id="tab_aa">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Company/Practice Name <span class="text-red">*</span></label>
                                <input type="text" name="company_name" id="company_name" value="<?php if($action=='edit'){echo $company_name;} ?>"  class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company/Practice Type </label>
                                <input type="text" name="company_type" id="company_type" value="<?php if($action=='edit'){echo $company_type;} ?>"  class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Manager Name </label>
                                <!--input type="text" name="manager_name" id="manager_name" value="<?php if($action=='edit'){echo $manager_name;} ?>"  class="form-control" /-->
                                <select name="manager_name" id="manager_name" class="form-control">
                                    <option value="0">Select Manager</option>
                                    <?php foreach($get_manager as $statekey=>$stateval){?>
                                    <option  <?php if($action=='edit'){if($manager_name == $stateval['id']){ ?>selected="true" <?php }} ?> value="<?php echo $stateval['id']; ?>"><?php echo $stateval['first_name'].' '.$stateval['middle_name'].' '.$stateval['last_name']; ?></option>
                                    <?php } ?>
                                </select>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Business Address 1 <span class="text-red">*</span></label>
                                <input type="text" name="address1" id="address1" value="<?php if($action=='edit'){echo $address1;} ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Business Address 2 </label>
                                <input type="text" name="address2" id="address2" value="<?php if($action=='edit'){echo $address2;} ?>" class="form-control" />
                            </div>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Business City <span class="text-red">*</span></label>
                                <input type="text" name="business_city" id="business_city" value="<?php if($action=='edit'){echo $business_city;} ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>State <span class="text-red">*</span></label>
                                <select name="state_general" id="state_general" class="form-control">
                                    <option value="">Select State</option>
                                    <?php foreach($get_state as $statekey=>$stateval){?>
                                    <option  <?php if(isset($state_general) && $state_general == $stateval['id']){ ?>selected="true" <?php } ?> value="<?php echo $stateval['id']; ?>"><?php echo $stateval['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Business Zipcode <span class="text-red">*</span></label>
                                <input type="text" name="zip" id="zip" value="<?php if($action=='edit'){echo $zip;} ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mailing Address1 </label>
                                <input type="text" name="mail_address1" id="mail_address1" value="<?php if($action=='edit'){echo $mail_address1;} ?>" class="form-control" />
                            </div>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mailing Address2 </label>
                                <input type="text" name="mail_address2" id="mail_address2" value="<?php if($action=='edit'){echo $mail_address2;} ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mailing City </label>
                                <input type="text" name="m_city" id="m_city" value="<?php if($action=='edit'){echo $m_city;} ?>" class="form-control" />
                            </div>
                        </div>
                   </div>
                   <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                <label>Mailing State </label>
                                <select name="state_mailing" id="state_mailing" class="form-control">
                                    <option value="">Select State</option>
                                    <?php foreach($get_state as $statekey=>$stateval){?>
                                    <option <?php if(isset($state_mailing) && $state_mailing == $stateval['id']){ ?>selected="true" <?php }  ?> value="<?php echo $stateval['id']; ?>"><?php  echo $stateval['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mailing Zipcode</label>
                                <input type="text" name="m_zip" id="m_zip" value="<?php if($action=='edit'){echo $m_zip;} ?>" class="form-control" />
                            </div>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telephone </label>
                                <input type="text" name="telephone" id="telephone" value="<?php if($action=='edit'){echo $telephone;} ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Facsimile </label>
                                <input type="text" name="facsimile" id="facsimile" value="<?php if($action=='edit'){echo $facsimile;} ?>" class="form-control" />
                            </div>
                        </div>
                   </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Established <span class="text-red">*</span> </label><br />
                                <div id="demo-dp-range">
	                                <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" name="e_date" id="e_date" value="<?php if($action=='edit'){echo date('m/d/Y',strtotime($e_date));} ?>" class="form-control" />
	                                </div>
	                            </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Inactive Date </label><br />
                                <div id="demo-dp-range">
                                    <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" name="i_date" id="i_date" value="<?php if($action=='edit'){echo date('m/d/Y',strtotime($i_date));} ?>" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                   </div>
                </div>
                <div class="tab-pane" id="tab_bb">
                <br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payout Level </label><br />
                                <label class="radio-inline">
                                  <input type="radio" class="radio" name="payout_level" id="payout_level" value="1" <?php if($action=='edit'){if($payout_level==1){ ?>checked="true"<?php }} ?>/>Company/Practice Level
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" class="radio" name="payout_level" id="payout_level" value="2" <?php if($action=='edit'){if($payout_level==2){ ?>checked="true"<?php }} ?>/>Broker Level
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Clearing Charge Calculation </label><br />
                                <label class="radio-inline">
                                  <input type="radio" class="radio" name="clearing_charge_calculation" id="clearing_charge_calculation" value="1" <?php if($action=='edit'){if($clearing_charge_calculation==1){ ?>checked="true"<?php }} ?>/>Gross Payout
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" class="radio" name="clearing_charge_calculation" id="clearing_charge_calculation" value="2" <?php if($action=='edit'){if($clearing_charge_calculation==2){ ?>checked="true"<?php }} ?>/>Net Payout
                                </label>
                            </div>
                        </div>
                    </div><br />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Sliding Scale Commission Basis </label><br />
                                <label class="radio-inline">
                                  <input type="radio" class="radio" name="sliding_scale_commision" id="sliding_scale_commision" value="1" <?php if($action=='edit'){if($sliding_scale_commision==1){ ?>checked="true"<?php }} ?>/>Payroll-to-Date Concession
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" class="radio" name="sliding_scale_commision" id="sliding_scale_commision" value="2" <?php if($action=='edit'){if($sliding_scale_commision==2){ ?>checked="true"<?php }} ?>/>Year-to-Date Concession
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" class="radio" name="sliding_scale_commision" id="sliding_scale_commision" value="3" <?php if($action=='edit'){if($sliding_scale_commision==3){ ?>checked="true"<?php }} ?>/>Year-to-Date Earnings
                                </label>
                            </div>
                        </div>
                    </div><br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Category </label>
                                <select name="product_category" id="product_category" class="form-control">
                                    <option value="">Select Product category</option>
                                    <?php foreach($get_product as $key=>$val){?>
                                    <option <?php if($product_category == $val['id']){ ?> selected="true" <?php } ?> value="<?php echo $val['id']; ?>"><?php echo $val['type']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                         <!--<div class="col-md-6">
                            <div class="form-group">
                                <label>Rate </label>
                                <!--input type="text" name="p_rate" id="p_rate" value="<?php if($action=='edit'){echo $p_rate;} ?>" class="form-control" /-->
                            <!--</div>
                        </div>-->
                    </div><h3>Level 1:</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Threshold(in $ Price) </label>
                                <input type="text" name="threshold1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="threshold1" value="<?php if($action=='edit'){ echo $threshold1;} ?>" class="form-control" />
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label>Rate (in percentage) </label>
                                <input type="text" name="l1_rate" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="l1_rate" value="<?php if($action=='edit'){echo $l1_rate;} ?>" class="form-control" />
                            </div>
                        </div>
                    </div><h3>Level 2:</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Threshold(in $ Price) </label>
                                <input type="text" name="threshold2" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="threshold2" value="<?php if($action=='edit'){echo $threshold2;} ?>" class="form-control" />
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label>Rate (in percentage) </label>
                                <input type="text" name="l2_rate" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="l2_rate" value="<?php if($action=='edit'){echo $l2_rate;} ?>" class="form-control" />
                            </div>
                        </div>
                    </div><h3>Level 3:</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Threshold(in $ Price) </label>
                                <input type="text" name="threshold3" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="threshold3" value="<?php if($action=='edit'){echo $threshold3;} ?>" class="form-control" />
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label>Rate (in percentage) </label>
                                <input type="text" name="l3_rate" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="l3_rate" value="<?php if($action=='edit'){echo $l3_rate;} ?>" class="form-control" />
                            </div>
                        </div>
                    </div><h3>Level 4:</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Threshold(in $ Price) </label>
                                <input type="text" name="threshold4" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="threshold4" value="<?php if($action=='edit'){echo $threshold4;} ?>" class="form-control" />
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label>Rate (in percentage) </label>
                                <input type="text" name="l4_rate" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="l4_rate" value="<?php if($action=='edit'){echo $l4_rate;} ?>" class="form-control" />
                            </div>
                        </div>
                    </div><h3>Level 5:</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Threshold(in $ Price) </label>
                                <input type="text" name="threshold5" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="threshold5" value="<?php if($action=='edit'){echo $threshold5;} ?>" class="form-control" />
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label>Rate (in percentage) </label>
                                <input type="text" name="l5_rate" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="l5_rate" value="<?php if($action=='edit'){echo $l5_rate;} ?>" class="form-control" />
                            </div>
                        </div>
                    </div><h3>Level 6:</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Threshold(in $ Price) </label>
                                <input type="text" name="threshold6" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="threshold6" value="<?php if($action=='edit'){echo $threshold6;} ?>" class="form-control" />
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <label>Rate (in percentage) </label>
                                <input type="text" name="l6_rate" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="l6_rate" value="<?php if($action=='edit'){echo $l6_rate;} ?>" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane" id="tab_cc">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-md-12"><h3>State</h3> </label>
                           
                                <?php  if (isset($state) && $state!= ''){$mystate = explode(',',$state);}
                                
                                foreach($get_state as $statekey=>$stateval){?>
                                <label class="check-inline col-md-2">
                                    <input type="checkbox" class="checkbox" <?php if(isset($mystate) && in_array($stateval['id'],$mystate)){ ?>checked="true" <?php } ?> name="state[<?php echo $stateval['id']; ?>]" id="<?php echo $stateval['name']; ?>" value="<?php echo $stateval['id']; ?>" /><?php echo $stateval['name']; ?>
                                </label>
                                <?php } ?>
                                <!--<label class="check-inline col-md-12">
                                    <br /><input type="checkbox" class="checkbox" name="foreign" <?php if($action=='edit'){if($foreign == 1){ ?> checked="true" <?php }} ?> id="foreign" value="Foreign" />Foreign
                                </label>-->
                        </div>
                    </div>
                </div>
                <div class="panel-footer fixedbtmenu">
                    <div class="selectwrap" >
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                        <?php if($_GET['action']=='edit' && $_GET['id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=previous"style="float: left;" class="previous next_previous_a" style="float: left;"><input type="button" name="next" value="&laquo; Previous" /></a><?php } ?>
                        <?php if($_GET['action']=='edit' && $_GET['id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=next" class="next next_previous_a"><input type="button" name="previous" value="Next &raquo;" /></a><?php } ?>
                        
                        <?php if($action=='edit' && $id>0){?>
                        <a href="#view_changes" data-toggle="modal"><input type="button" name="view_changes" value="View Changes" style="margin-left: 10% !important;"/></a>
                        <?php } ?>
                        <a href="#company_notes" data-toggle="modal"><input type="button" onclick="get_company_notes();" name="notes" value="Notes" /></a>
                        <a href="#company_transactions" data-toggle="modal"><input type="button"  name="transactions" value="Transactions"/></a>
                        <a href="#company_attach" data-toggle="modal"><input type="button"  onclick="get_company_attach();" name="attach" value="Attachments" style="margin-right: 10% !important;" /></a>
                        <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
                        <input type="submit" name="submit" onclick="waitingDialog.show();" value="Save" style="float: right;"/>
                    </div><br />
                </div>
         </form> 
        </div>
        </div>
        <?php
    }
    else{?>
    <div class="panel">
		<!--<div class="panel-heading">
            <div class="panel-control">
                <div class="btn-group dropdown" style="float: right;">
					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
					<ul class="dropdown-menu dropdown-menu-right" style="">
						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new"><i class="fa fa-plus"></i> Add a New Practice</a></li>
					</ul>
				</div>
			</div>
		</div>-->
		<div class="panel-body"  ><br />
        <!--<div class="panel-control" style="float: right;">
             <form method="post">
                <div class="row">
                    <input type="hidden" name="active_search" value="company_name"/>  
                    <input type="text" name="search_text" id="search_text" value="<?php //echo $search_text;?>"/>
                <button type="submit" name="submit" id="submit" value="Search"><i class="fa fa-search"></i> Search</button>
             </div> 
            </form>
        </div><br /><br />-->
        <div class="table-responsive" id="register_data">
			<table id="data-table" class="table table-striped1 table-bordered" cellspacing="0" width="100%">
	            <thead>
	                <tr>
                        <th>COMPANY/PRACTICE NAME</th>
                        <th>MANAGER NAME</th>
                        <th>PRACTICE TYPE</th>
                        <th>ESTABLISH DATE</th>
                        <th>TERMINATION DATE</th>
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
                                <td><?php echo $val['company_name']; ?></td>
                                <td><?php foreach($get_manager as $statekey=>$stateval){ if($val['manager_name'] == $stateval['id']){echo $stateval['first_name'].' '.$stateval['middle_name'].' '.$stateval['last_name']; }} ?></td>
                                <td><?php echo $val['company_type']; ?></td>
                                <td><?php echo date('m/d/Y',strtotime($val['e_date'])); ?></td>
                                <td><?php echo date('m/d/Y',strtotime($val['i_date'])); ?></td>
                                <td class="text-center">
                                    <?php
                                        if($val['status']==1){
                                            ?>
                                            <a href="<?php echo CURRENT_PAGE; ?>?action=status&id=<?php echo $val['id']; ?>&status=0" class="btn btn-sm btn-success"><i class="fa fa-check-square-o"></i> Enabled</a>
                                            <?php
                                        }
                                        else{
                                            ?>
                                            <a href="<?php echo CURRENT_PAGE; ?>?action=status&id=<?php echo $val['id']; ?>&status=1" class="btn btn-sm btn-warning"><i class="fa fa-warning"></i> Disabled</a>
                                            <?php
                                        }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
            </div>
		</div>
	</div>
    <?php } ?>
    <?php if($action=='edit' && $id>0){?>
    <!--<div class="row">
        <div class="col-md-12">
            <div class="form-group"><br /><div class="selectwrap">
                <a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=previous" class="previous next_previous_a" style="float: left;">&laquo; Previous</a>
                <a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=next" class="next next_previous_a" style="float: right;">Next &raquo;</a>
            </div>
         </div>
         </div>
     </div>-->
    <?php } ?>
    </div>
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
                    <th>#NO</th>
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
                $lable_array = array('company_name' => 'Company/Practice Name','company_type' => 'Company/Practice Type','manager_name' => 'Manager Name','address1' => 'Business Address 1','address2' => 'Business Address 2','business_city' => 'Business City','state_general' => 'State General','zip' => 'Business Zipcode','mail_address1' => 'Mailing Address1','mail_address2' => 'Mailing Address2','m_city' => 'Mailing City','m_state' => 'Mailing State','m_zip' => 'Mailing Zipcode','telephone' => 'Telephone','facsimile' => 'Facsimile','e_date' => 'Date Established','i_date' => 'Inactive Date','payout_level' => 'Payout Level','clearing_charge_calculation' => 'Clearing Charge Calculation','sliding_scale_commision' => 'Sliding Scale Commission Basis','product_category' => 'Product Category','p_rate' => 'Rate','threshold1' => 'Threshold 1','l1_rate' => 'Rate 1','threshold2' => 'Threshold 2','l2_rate' => 'Rate 2','threshold3' => 'Threshold 3','l3_rate' => 'Rate 3','threshold4' => 'Threshold 4','l4_rate' => 'Rate 4','threshold5' => 'Threshold 5','l5_rate' => 'Rate 5','threshold6' => 'Threshold 6','l6_rate' => 'Rate 6','state' => 'State','forign' => 'Foreign');
                
                foreach($multicompany_data as $key=>$val){
                    
                    if(isset($lable_array[$val['field']])){
                        $feild_name = $lable_array[$val['field']];
                    }else {
                        $feild_name = $val['field'];
                    }?>
                    <tr>
                    
                        <td><?php echo ++$count; ?></td>
                        <td><?php echo $val['user_initial'];?></td>
                        <td><?php echo date('m/d/Y',strtotime($val['modified_time']));?></td>
                        <td><?php echo $feild_name;?></td>
                       <?php if($feild_name == 'Payout Level'){?>
                        <td>
                        <?php 
                        if($val['old_value'] == 1)
                        {
                            echo 'Company/Practice Level';
                        }
                        else if($val['old_value'] == 2)
                        {
                            echo 'Broker Level';
                        }
                        ?>
                        </td>
                        <td>
                        <?php 
                        if($val['new_value'] == 1)
                        {
                            echo 'Company/Practice Level';
                        }
                        else if($val['new_value'] == 2)
                        {
                            echo 'Broker Level';
                        }
                        ?>
                        </td>
                        <?php }
                        else if($feild_name == 'Clearing Charge Calculation'){?>
                        <td>
                        <?php 
                        if($val['old_value'] == 1)
                        {
                            echo 'Gross Payout';
                        }
                        else if($val['old_value'] == 2)
                        {
                            echo 'Net Payout';
                        }
                        ?>
                        </td>
                        <td>
                        <?php 
                        if($val['new_value'] == 1)
                        {
                            echo 'Gross Payout';
                        }
                        else if($val['new_value'] == 2)
                        {
                            echo 'Net Payout';
                        }
                        ?>
                        </td>
                        <?php }
                        else if($feild_name == 'Sliding Scale Commission Basis'){?>
                        <td>
                        <?php 
                        if($val['old_value'] == 1)
                        {
                            echo 'Payroll-to-Date Concession';
                        }
                        else if($val['old_value'] == 2)
                        {
                            echo 'Year-to-Date Concession';
                        }
                        else if($val['old_value'] == 3)
                        {
                            echo 'Year-to-Date Earnings';
                        }
                        ?>
                        </td>
                        <td>
                        <?php 
                        if($val['new_value'] == 1)
                        {
                            echo 'Payroll-to-Date Concession';
                        }
                        else if($val['new_value'] == 2)
                        {
                            echo 'Year-to-Date Concession';
                        }
                        else if($val['new_value'] == 3)
                        {
                            echo 'Year-to-Date Earnings';
                        }
                        ?>
                        </td>
                        <?php }
                        else if($feild_name == 'State General'){
                        $state = $instance->get_state_name($val['old_value']);?>
                        <td><?php echo $state['state_name'];?></td>
                        <?php $state = $instance->get_state_name($val['new_value']);?>
                        <td><?php echo $state['state_name'];?></td>
                        <?php }
                        else if($feild_name == 'Mailing State'){
                        $state = $instance->get_state_name($val['old_value']);?>
                        <td><?php echo $state['state_name'];?></td>
                        <?php $state = $instance->get_state_name($val['new_value']);?>
                        <td><?php echo $state['state_name'];?></td>
                        <?php }
                        else if($feild_name == 'State'){?>
                        <td>
                        <?php 
                            $myArray1 = explode(',', $val['old_value']);
                            
                            foreach($myArray1 as $key1=>$val1)
                            {
                                $state1 = $instance->get_state_name($val1);
                                echo $state1['state_name'].',';
                            }
                            
                            
                        ?>
                        </td>
                        
                        <td>
                        <?php 
                            $myArray = explode(',', $val['new_value']);
                            foreach($myArray as $key=>$val)
                            {
                                $state = $instance->get_state_name($val);
                                echo $state['state_name'].',';
                            }
                        ?>
                        </td>
                        <?php }
                        else if($feild_name == 'Manager Name'){
                        $manager_name = $instance->get_broker_name($val['old_value']);?>
                        <td><?php echo $manager_name['broker_name'];?></td>
                        <?php $manager_name = $instance->get_broker_name($val['new_value']);?>
                        <td><?php echo $manager_name['broker_name'];?></td>
                        <?php }
                        else if($feild_name == 'Product Category'){
                        $product_category_name = $instance->get_product_category_name($val['old_value']);?>
                        <td><?php echo $product_category_name['product_type'];?></td>
                        <?php $product_category_name = $instance->get_product_category_name($val['new_value']);?>
                        <td><?php echo $product_category_name['product_type'];?></td>
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
<!-- Lightbox strart -->							
<!-- Modal for transaction list -->
<div id="company_transactions" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
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
        	<!-- Modal for add client notes -->
        	<div id="company_notes" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
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
	<div id="company_attach" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header" style="margin-bottom: 0px !important;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h4 class="modal-title">Multi-Company's Attach</h4>
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
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 6 ] }, 
                        { "bSearchable": false, "aTargets": [ 6 ] }]
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
</script>
<style type="text/css">
.toolbar {
    float: right;
    padding-left: 5px;
}
</style>
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
	
	};

})(jQuery);
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
function get_company_notes(){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("ajax_notes").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_company_notes.php", true);
        xmlhttp.send();
}
function get_company_attach(){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("ajax_attach").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_company_attach.php", true);
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

   var url = "manage_multicompany.php"; // the script where you handle the form input.
   //alert("#add_client_notes_"+note_id);
   $.ajax({
      type: "POST",
      url: url,
      data: $("#add_client_notes_"+note_id).serialize(), // serializes the form's elements.
      success: function(data){
          if(data=='1'){
            
            get_company_notes();
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
            url: 'manage_multicompany.php', // point to server-side PHP script 
            
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                  if(data=='1'){
                    
                    get_company_attach();
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
function delete_attach(attach_id){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = this.responseText;
                if(data=='1'){
                   get_company_attach(); 
                   $('#msg_attach').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Attach deleted Successfully.</div>');
                  }
                  else{
                       $('#msg_attach').html('<div class="alert alert-danger">'+data+'</div>');
                  }
            }
        };
        xmlhttp.open("GET", "manage_multicompany.php?delete_action=delete_attach&attach_id="+attach_id, true);
        xmlhttp.send();
}
function delete_notes(note_id){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = this.responseText;
                if(data=='1'){
                   get_company_notes(); 
                   $('#msg_notes').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Note deleted Successfully.</div>');
                   //get_client_notes();
                  
                  }
                  else{
                       $('#msg_notes').html('<div class="alert alert-danger">'+data+'</div>');
                  }
                
            }
        };
        xmlhttp.open("GET", "manage_multicompany.php?delete_action=delete_notes&note_id="+note_id, true);
        xmlhttp.send();
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