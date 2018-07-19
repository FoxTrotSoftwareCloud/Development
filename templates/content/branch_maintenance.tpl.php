<div class="container">
<h1 class="<?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'topfixedtitle';}?>">Branch Maintenance</h1>
<div class="col-lg-12 well <?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'fixedwell';}?>">
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
<div class="tab-content col-md-12">
<?php
if($action=='add_new'||($action=='edit' && $id>0)){
    ?><form name="frm2" method="POST">
    <!--<div class="panel-footer">
        <div class="selectwrap">
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
            <?php if($action=='edit' && $id>0){?>
            <a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=previous" style="float: left;"><input type="button" value="&laquo; Previous" style="float: right;"/></a>
            <?php } ?>
            <?php if($action=='edit' && $id>0){?>
            <a href="#view_changes" data-toggle="modal"><input type="button" name="view_changes" value="View Changes" style="margin-left: 10%;"/></a>
            <?php } ?>
            <a href="#branch_notes" data-toggle="modal"><input type="button" onclick="get_branch_notes();" name="notes" value="Notes" /></a>
            <a href="#branch_attach" data-toggle="modal"><input type="button"  onclick="get_branch_attach();" name="attach" value="Attachments" /></a>
            <a href="#" data-toggle="modal"><input type="button" value="Transactions" /></a><input type="submit" name="submit" onclick="waitingDialog.show();" value="Save"/>	
            <a href="<?php echo CURRENT_PAGE.'?action=view';?>"><input type="button" name="cancel" value="Cancel" style="margin-right: 8%;" /></a>
            
            <?php if($action=='edit' && $id>0){?>
            <a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=next" style="float: right;"><input type="button" value="Next &raquo;" style="float: right;"/></a>
            <?php } ?>
        </div><br />
   </div>-->
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
            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action=='add_new'?'Add':'Edit'; ?> Branch</h3>
		</div>
        <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Branch Name <span class="text-red">*</span></label><br />
                    <input type="text" maxlength="40" class="form-control" name="name" value="<?php echo $name;?>"  />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Manager </label><br />
                    <select class="form-control" name="broker">
                        <option value="">Select Manager</option>
                        <?php foreach($get_broker as $key=>$val){?>
                        <option value="<?php echo $val['id'];?>" <?php if($broker != '' && $broker==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'];?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Contact </label><br />
                    <input type="text" maxlength="40" class="form-control" name="contact" value="<?php echo $contact;?>"  />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status </label><br />
                    <select class="form-control" name="b_status">
                        <option value="0">Select Status</option>
                        <option value="1" <?php if($b_status==1){echo "selected='selected'";} ?>>FINRA - OSJ</option>
                        <option value="2" <?php if($b_status==2){echo "selected='selected'";} ?>>FINRA - Branch</option>
                        <option value="3" <?php if($b_status==3){echo "selected='selected'";} ?>>Individual/Sole Proprietorship</option>
                        <option value="4" <?php if($b_status==4){echo "selected='selected'";} ?>>Unregistered</option>
                        <option value="5" <?php if($b_status==5){echo "selected='selected'";} ?>>Financial Institution - Local</option>
                        <option value="6" <?php if($b_status==6){echo "selected='selected'";} ?>>Financial Institution - HQ</option>
                        <option value="7" <?php if($b_status==7){echo "selected='selected'";} ?>>State Branch</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Company </label><br />
                    <select class="form-control" name="company">
                        <option value="">Select Company</option>
                        <?php foreach($get_multi_company as $key=>$val){?>
                        <option value="<?php echo $val['id'];?>" <?php if($company != '' && $company==$val['id']){echo "selected='selected'";} ?>><?php echo $val['company_name'];?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>OSJ </label><br />
                    <input type="checkbox" name="osj" id="osj" class="checkbox" value="1" <?php if($osj>0){echo "checked='checked'";}?>/>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Non-Registered </label><br />
                    <input type="checkbox" name="non_registered" id="non_registered" class="checkbox" value="1" <?php if($non_registered>0){echo "checked='checked'";}?>/>
                </div>
            </div>
        </div>
        <br />
        <div style="display: block; border: 1px solid #ddd;">
        <div class="row" style="padding: 10px;">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Business Address </label><br />
                    <input type="text"  class="form-control" maxlength="50" name="business_address1" value="<?php echo $business_address1;?>" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label> </label><br />
                    <input type="text"  class="form-control" maxlength="50" name="business_address2" value="<?php echo $business_address2;?>" />
                </div>
            </div>
        </div>
        <div class="row" style="padding: 10px;">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Business City </label><br />
                    <input type="text"  class="form-control" maxlength="25" name="business_city" value="<?php echo $business_city;?>" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Business State </label><br />
                    <select name="business_state" id="business_state" class="form-control">
                        <option value="">Select State</option>
                        <?php foreach($get_state as $key=>$val){ ?>
                        <option value="<?php echo $val['id'];?>" <?php if($business_state != '' && $business_state==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Business Zipcode </label><br />
                    <input type="text" maxlength="10" class="form-control" name="business_zipcode" value="<?php echo $business_zipcode;?>"  />
                </div>
            </div>
        </div>
        <div class="row" style="padding: 10px;">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Mailing Address </label><br />
                    <input type="text"  class="form-control" maxlength="50" name="mailing_address1" value="<?php echo $mailing_address1;?>" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label> </label><br />
                    <input type="text"  class="form-control" maxlength="50" name="mailing_address2" value="<?php echo $mailing_address2;?>" />
                </div>
            </div>
        </div>
        <div class="row" style="padding: 10px;">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Mailing City </label><br />
                    <input type="text"  class="form-control" maxlength="25" name="mailing_city" value="<?php echo $mailing_city;?>" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Mailing State </label><br />
                    <select name="mailing_state" id="mailing_state" class="form-control">
                        <option value="">Select State</option>
                        <?php foreach($get_state as $key=>$val){ ?>
                        <option value="<?php echo $val['id'];?>" <?php if($mailing_state != '' && $mailing_state==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Mailing Zipcode </label><br />
                    <input type="text" maxlength="10" class="form-control" name="mailing_zipcode" value="<?php echo $mailing_zipcode;?>"  />
                </div>
            </div>
        </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email </label><br />
                    <input type="text" maxlength="50" class="form-control" name="email" value="<?php echo $email;?>" placeholder="yourmail@domain.com" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Website </label><br />
                    <input type="text" maxlength="50" class="form-control" name="website" value="<?php echo $website;?>" placeholder="www.website.com" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phone </label><br />
                    <input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="13" class="form-control" name="phone" value="<?php echo $phone;?>"  />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Facsimile </label><br />
                    <input type="text" maxlength="13" class="form-control" name="facsimile" value="<?php echo $facsimile;?>"  />
                </div>
            </div>
        </div>
       <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Date Established </label>
                    <div id="demo-dp-range">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" name="date_established" id="date_established" class="form-control" value="<?php if($date_established !=''){ echo date('m/d/Y',strtotime($date_established)); } ?>"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Date Terminated </label>
                    <div id="demo-dp-range">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" name="date_terminated" id="date_terminated" class="form-control" value="<?php if($date_terminated !=''){ echo date('m/d/Y',strtotime($date_terminated)); } ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>FINRA Start Date </label>
                    <div id="demo-dp-range">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" name="finra_start_date" id="finra_start_date" class="form-control" value="<?php if($finra_start_date !=''){ echo date('m/d/Y',strtotime($finra_start_date)); } ?>"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>FINRA End Date </label>
                    <div id="demo-dp-range">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" name="finra_end_date" id="finra_end_date" class="form-control" value="<?php if($finra_end_date !=''){ echo date('m/d/Y',strtotime($finra_end_date)); } ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Last Audit Date </label>
                    <div id="demo-dp-range">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" name="last_audit_date" id="last_audit_date" class="form-control" value="<?php if($last_audit_date !=''){ echo date('m/d/Y',strtotime($last_audit_date)); } ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>FINRA Fee </label><br />
                    <input type="text" onblur="round(this.value)" maxlength="50" class="form-control" name="finra_fee" id="finra_fee" value="<?php echo $finra_fee;?>"  />
                </div>
            </div>
        </div>
       </div>
       <div class="panel-footer fixedbtmenu">
            <div class="selectwrap">
                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                <?php if($action=='edit' && $id>0){?>
                <a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=previous" style="float: left;"><input type="button" value="&laquo; Previous" style="float: right;"/></a>
                <?php } ?>
                <?php if($action=='edit' && $id>0){?>
                <a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=next"><input type="button" value="Next &raquo;"/></a>
                <?php } ?>
                
                <?php if($action=='edit' && $id>0){?>
                <a href="#view_changes" data-toggle="modal"><input type="button" name="view_changes" value="View Changes" style="margin-left: 10% !important;"/></a>
                <?php } ?>
                <a href="#branch_notes" data-toggle="modal"><input type="button" onclick="get_branch_notes();" name="notes" value="Notes" /></a>
                <a href="#" data-toggle="modal"><input type="button" value="Transactions" /></a>
                <a href="#branch_attach" data-toggle="modal"><input type="button"  onclick="get_branch_attach();" name="attach" value="Attachments" style="margin-right: 10% !important;"/></a>
				<a href="<?php echo CURRENT_PAGE.'?action=view';?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
                <input type="submit" name="submit" onclick="waitingDialog.show();" value="Save" style="float: right;"/>
            </div><br />
       </div>
    
    </div></form>
    <?php
        }else{?>
    <div class="panel">
		<!--<div class="panel-heading">
            <div class="panel-control">
                <div class="btn-group dropdown" style="float: right;">
                    <button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
					<ul class="dropdown-menu dropdown-menu-right" style="">
						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new"><i class="fa fa-plus"></i> Add New</a></li>
					</ul>
				</div>
			</div>
        </div><br />-->
		<div class="panel-body">
        <!--<div class="panel-control" style="float: right;">
         <form method="post">
            <input type="text" name="search_text" id="search_text" value="<?php echo $search_text;?>"/>
            <button type="submit" name="search" id="submit" value="Search"><i class="fa fa-search"></i> Search</button>
        </form>
        </div><br /><br />-->
            <div class="table-responsive">
			<table id="data-table" class="table table-striped1 table-bordered" cellspacing="0" width="100%">
	            <thead>
	                <tr>
                        <th>BRANCH NAME</th>
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
                                <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=<?php echo $val['id']; ?>" class="btn btn-md btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete&id=<?php echo $val['id']; ?>');" class="btn btn-md btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
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
<!-- Lightbox strart -->							
	<!-- Modal for add client notes -->
	<div id="branch_notes" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
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
	<!-- Modal for add client notes -->
	<div id="branch_attach" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header" style="margin-bottom: 0px !important;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h4 class="modal-title">Branch's Attach</h4>
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
                $lable_array = array('name' => 'Branch Name','broker' => 'Manager','b_status' => 'Status','contact' => 'Contact','osj' => 'OSJ',
                'non_registered' => 'Non-Registered','finra_fee' => 'FINRA Fee','business_address1' => 'Business Address1','business_address2' => 'Business Address2','business_city' => 'Business City',
                'business_state' => 'Business State','business_zipcode' => 'Business Zipcode','mailing_address1' => 'Mailing Address1','mailing_address2' => 'Mailing Address2','mailing_city' => 'Mailing City','mailing_state' => 'Mailing State','mailing_zipcode' => 'Mailing Zipcode',
                'email' => 'Email','website' => 'Website','phone' => 'Phone','facsimile' => 'Facsimile','date_established' => 'Date Established
','date_terminated' => 'Date Terminated','finra_start_date' => 'FINRA Start Date','finra_end_date' => 'FINRA End Date','last_audit_date' => 'Last Audit Date');
                foreach($branch_data as $key=>$val){
                    
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
                        <?php 
                        if($feild_name == 'OSJ' && $val['old_value'] == 0){?>
                        <td><?php echo 'UnChecked';?></td>
                        <td><?php echo 'Checked';?></td>
                        <?php } 
                        else if($feild_name == 'OSJ' && $val['old_value'] == 1){?>
                        <td><?php echo 'Checked';?></td>
                        <td><?php echo 'UnChecked';?></td>
                        <?php }
                        else if($feild_name == 'Non-Registered' && $val['old_value'] == 0){?>
                        <td><?php echo 'UnChecked';?></td>
                        <td><?php echo 'Checked';?></td>
                        <?php } 
                        else if($feild_name == 'Non-Registered' && $val['old_value'] == 1){?>
                        <td><?php echo 'Checked';?></td>
                        <td><?php echo 'UnChecked';?></td>
                        <?php }
                        else if($feild_name == 'Manager'){
                        $broker_fname = $instance->get_broker_name($val['old_value']);?>
                        <td><?php echo $broker_fname['broker'];?></td>
                        <?php $broker_fname = $instance->get_broker_name($val['new_value']);?>
                        <td><?php echo $broker_fname['broker'];?></td>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#data-table').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 2 ] }, 
                        { "bSearchable": false, "aTargets": [ 2 ] }]
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
function get_branch_attach(){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("ajax_attach").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_branch_attach.php", true);
        xmlhttp.send();
}
function get_branch_notes(){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("ajax_notes").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_branch_notes.php", true);
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

   var url = "branch_maintenance.php"; // the script where you handle the form input.
   //alert("#add_client_notes_"+note_id);
   $.ajax({
      type: "POST",
      url: url,
      data: $("#add_client_notes_"+note_id).serialize(), // serializes the form's elements.
      success: function(data){
          if(data=='1'){
            
            get_branch_notes();
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
            url: 'branch_maintenance.php', // point to server-side PHP script 
            
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                  if(data=='1'){
                    
                    get_branch_attach();
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
                   get_branch_notes(); 
                   $('#msg_notes').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Note deleted Successfully.</div>');
                  }
                  else{
                       $('#msg_notes').html('<div class="alert alert-danger">'+data+'</div>');
                  } 
            }
        };
        xmlhttp.open("GET", "branch_maintenance.php?delete_action=delete_notes&note_id="+note_id, true);
        xmlhttp.send();
}
function delete_attach(attach_id){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = this.responseText;
                if(data=='1'){
                   get_branch_attach(); 
                   $('#msg_attach').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Attach deleted Successfully.</div>');
                  }
                  else{
                       $('#msg_attach').html('<div class="alert alert-danger">'+data+'</div>');
                  }
            }
        };
        xmlhttp.open("GET", "branch_maintenance.php?delete_action=delete_attach&attach_id="+attach_id, true);
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
.next_previous_a {
    text-decoration: none;
    display: inline-block;
    padding: 8px 16px;
}

.next_previous_a:hover {
    background-color: #ddd;
    color: black;
}

.previous {
    background-color: #f1f1f1;
    color: black;
}

.next {
    background-color: #ef7623;
    color: white;
}

.round {
    border-radius: 50%;
}
</style>
<script>
function round(fee)
{
    var round = Math.round( fee * 10 ) / 10;
    var rounded = round.toFixed(2);
    
    document.getElementById("finra_fee").value = rounded;
}
</script>