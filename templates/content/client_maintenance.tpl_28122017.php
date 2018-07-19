<script>
function addMoreDocs(){
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
                    '</div>'+
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
});
</script>
<script>
function addMoreNotes(){
    var html = '<tr class="add_row_notes">'+
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
                
            
    $(html).insertAfter('#add_row_notes');
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
<h1>Client Maintenance</h1>
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
    <div class="col-lg-12 well">
        <div class="tab-content col-md-12">
            <div class="tab-pane active" id="tab_a">
                    <?php
                    if($action=='add_new'||($action=='edit' && $id>0)){
                        ?>
                            <ul class="nav nav-tabs ">
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="primary"){ echo "active"; }else if(!isset($_GET['tab'])){echo "active";}else{ echo '';} ?>"><a href="#tab_aa" data-toggle="tab">Primary</a></li>
                              <?php if(isset($_SESSION['client_id']) && $_SESSION['client_id']!=''){?>
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="account_no"){ echo "active"; } ?>"><a href="#tab_dd" data-toggle="tab">Account No's</a></li>
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="employment"){ echo "active"; } ?>"><a href="#tab_bb" data-toggle="tab">Employment</a></li>
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="suitability"){ echo "active"; } ?>"><a href="#tab_ff" data-toggle="tab">Suitability</a></li>
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="objectives"){ echo "active"; } ?>"><a href="<?php echo CURRENT_PAGE; ?>#tab_cc" data-toggle="tab">Objectives</a></li>
                              <?php }else{ ?> 
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="account_no"){ echo "active"; } ?>" style="pointer-events: none;"><a href="#tab_dd" data-toggle="tab">Account No's</a></li>
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="employment"){ echo "active"; } ?>" style="pointer-events: none;"><a href="#tab_bb" data-toggle="tab">Employment</a></li>
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="suitability"){ echo "active"; } ?>" style="pointer-events: none;"><a href="#tab_ff" data-toggle="tab">Suitability</a></li>
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="objectives"){ echo "active"; } ?>" style="pointer-events: none;"><a href="<?php echo CURRENT_PAGE; ?>#tab_cc" data-toggle="tab">Objectives</a></li>
                              <?php } ?>
                              <!--<li><a href="#tab_ee" data-toggle="tab">Documents</a></li>-->
                                <div class="btn-group dropdown" style="float: right;">
    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
    								<ul class="dropdown-menu dropdown-menu-right" style="">
    									<li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-eye"></i> View List</a></li>
    								</ul>
    							</div>
    						</ul>
                            
                            <!-- Tab 1 is started -->
                            <div class="tab-content">
                                <?php if($action=='edit' && $id>0){?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"><br /><div class="selectwrap">
                                            <a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=previous" class="previous next_previous_a" style="float: left;">&laquo; Previous</a>
                                            <a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=next" class="next next_previous_a" style="float: right;">Next &raquo;</a>
                                        </div>
                                     </div>
                                     </div>
                                 </div>
                                <?php } ?>
                                <br />
                                <div class="row">
                                    <div class="col-md-4" style="float: right;">
                                        <div class="form-group">
                                            <a href="#client_notes" data-toggle="modal"><input type="button" onclick="get_client_notes();" name="notes" value="Notes" /></a>
                                            <a href="#client_transactions" data-toggle="modal"><input type="button" name="transactions" value="Transactions" /></a>
                                            <a href="#joint_account" data-toggle="modal"><input type="button" name="joint_account" value="Joint Account" /></a>
                                            <a href="#client_attachment" data-toggle="modal"><input type="button" name="attach" value="Attach" /></a><br />
                                        </div>
                                     </div>
                                 </div>
                                
                                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="primary"){ echo "active"; }else if(!isset($_GET['tab'])){echo "active";}else{ echo '';} ?>" id="tab_aa">
                                <form method="post">
                                    <div class="panel-overlay-wrap">
                                            <div class="panel">
                                               <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <h4 class="panel-title" style="font-size: 20px;"><input type="checkbox" class="checkbox" name="do_not_contact" value="1" id="do_not_contact" style="display: inline !important;" <?php if($do_not_contact>0){echo "checked='checked'";}?>/> Do Not Contact&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" value="1" name="active" id="active" class="checkbox" style="display: inline !important;" <?php if($active>0){echo "checked='checked'";}?>/> Active</h4>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label style="display: inline;">OFAC Check </label>
                                                                <div id="demo-dp-range">
                                                                    <div class="input-daterange input-group" id="datepicker">
                                                                        <input type="text" name="ofak_check" disabled="true" id="ofak_check" class="form-control" value="<?php echo $ofak_check; ?>"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    	<div class="col-md-4">
                                                            <div class="form-group">
                                                                <label style="display: inline;">FinCEN Check </label>
                                                                <div id="demo-dp-range">
                                                                    <div class="input-daterange input-group" id="datepicker">
                                                                        <input type="text" name="fincen_check" disabled="true" id="fincen_check" class="form-control" value="<?php echo $fincen_check; ?>"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               </div>
                                            <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>First Name </label><br />
                                                        <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>MI </label><br />
                                                        <input type="text" name="mi" id="mi" value="<?php echo $mi; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            	<div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Last Name <span class="text-red">*</span></label><br />
                                                        <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Long Name / Registration </label>
                                                        <textarea name="long_name" id="long_name" class="form-control"><?php echo $long_name; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Client File Number <span class="text-red">*</span></label><br />
                                                        <input type="text" name="client_file_number" id="client_file_number" maxlength="12" class="form-control" value="<?php echo $client_file_number; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Clearing Acct </label><br />
                                                        <input type="text" name="clearing_account" id="clearing_account" class="form-control" maxlength="20" value="<?php echo $clearing_account; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Client SSN </label><br />
                                                        <input type="text" name="client_ssn" id="client_ssn" class="form-control" value="<?php echo $client_ssn; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Account Type  </label>
                                                        <select name="account_type" id="account_type" class="form-control">
                                                            <option value="0">Select Type</option>
                                                            <?php foreach($get_account_type as $key=>$val)
                                                            {?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($account_type != '' && $account_type==$val['id']){echo "selected='selected'";} ?>><?php echo $val['type'];?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Household / Link Code </label><br />
                                                        <input type="text" name="household" id="household" class="form-control" maxlength="20" value="<?php echo $household; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Broker <span class="text-red">*</span>
                                                        </label>
                                                        <select name="broker_name" id="broker_name" class="form-control">
                                                           <option value="">Select Broker</option>
                                                            <?php foreach($get_broker as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($broker_name != '' && $broker_name==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                           <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Split Broker </label>
                                                        <select name="split_broker" id="split_broker" class="form-control">
                                                            <option value="">Select Broker</option>
                                                            <?php foreach($get_broker as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($split_broker != '' && $split_broker==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Split Rate<span class="text-red"></span></label>
                                                        <input type="text" onblur="round(this.value);" min="0" name="split_rate" id="split_rate" placeholder='00.0' class="currency1 form-control" value="<?php echo $split_rate; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Address1 </label>
                                                        <input type="text" name="address1" id="address1" class="form-control" value="<?php echo $address1; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Address2 <span class="text-red"></span></label>
                                                        <input type="text" name="address2" id="address2" class="form-control" value="<?php echo $address2; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row"> 
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>City </label>
                                                        <input type="text" name="city" id="city" class="form-control" value="<?php echo $city; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>State </label>
                                                        <select name="state" id="state" class="form-control">
                                                            <option value="">Select State</option>
                                                            <?php foreach($get_state as $key=>$val){ ?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($state != '' && $state==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row">
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Zip Code </label>
                                                        <input type="text" name="zip_code" id="zip_code" class="form-control" value="<?php echo $zip_code; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Telephone </label>
                                                        <input type="text" name="telephone" id="telephone" class="form-control" value="<?php echo $telephone; ?>"/>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row">
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Citizenship </label>
                                                        <input type="text" name="citizenship" id="citizenship" class="form-control" value="<?php echo $citizenship; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Contact Status </label>
                                                        <select name="contact_status" id="contact_status" class="form-control">
                                                            <option value="">Select Status</option>
                                                            <option value="1" <?php if($contact_status != '' && $contact_status==1){ echo "selected='selected'";}?>>Yes</option>
                                                            <option value="2" <?php if($contact_status != '' && $contact_status==2){ echo "selected='selected'";}?>>No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Birth Date </label>
                                                        <div id="demo-dp-range">
                                                            <div class="input-daterange input-group" id="datepicker">
                                                                <input type="text" name="birth_date" onchange="getAge(this.value);" id="birth_date" class="form-control" value="<?php if($birth_date !=''){ echo date('m/d/Y',strtotime($birth_date));} ?>"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Age </label>
                                                        <input type="text" name="age1" id="age1" class="form-control" disabled="true" value="<?php echo $age; ?>"/>
                                                        <input type="hidden" name="age" id="age" class="form-control" value="<?php echo $age; ?>"/>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Date Established </label>
                                                        <input type="text" name="date_established1" id="date_established1" disabled="true" value="<?php if($date_established !=''){ echo date('m/d/Y',strtotime($date_established)); }else{ echo date("m/d/Y"); }?>" class="form-control" />
                                                        <input type="hidden" name="date_established" id="date_established" value="<?php if($date_established !=''){ echo $date_established; }else{ echo date("m/d/Y"); }?>" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Open Date </label>
                                                        <div id="demo-dp-range">
                                                            <div class="input-daterange input-group" id="datepicker">
                                                                <input type="text" name="open_date" id="open_date" class="form-control" value="<?php if($open_date !=''){ echo date('m/d/Y',strtotime($open_date)); } ?>"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>NAF Date </label>
                                                        <div id="demo-dp-range">
                                                            <div class="input-daterange input-group" id="datepicker">
                                                                <input type="text" name="naf_date" id="naf_date" class="form-control" value="<?php if($naf_date !=''){ echo date('m/d/Y',strtotime($naf_date)); } ?>"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Last Contacted </label>
                                                        <div id="demo-dp-range">
                                                            <div class="input-daterange input-group" id="datepicker">
                                                                <input type="text" name="last_contacted" id="last_contacted" class="form-control" value="<?php if($last_contacted !=''){ echo date('m/d/Y',strtotime($last_contacted)); } ?>" />
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
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                					<input type="submit" name="submit" onclick="waitingDialog.show();" value="Save"/>	
                                                    <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                   </form>
                                </div>
                                
                                <!-- Tab 1 is ends -->
                                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="employment"){ echo "active"; } ?>" id="tab_bb">
                                <form method="post">
                                        <div class="panel-overlay-wrap">
                                            <div class="panel">
                                               <div class="panel-heading">
                                                    <h4 class="panel-title" style="font-size: 20px;">
                                                    <?php if(isset($_SESSION['client_full_name'])){echo $_SESSION['client_full_name'];}?></h4>
                                               </div>
                                            <div class="panel-body">
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Occupation </label><br />
                                                        <input type="text" name="occupation" id="occupation" class="form-control" value="<?php echo $occupation; ?>" />
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Employer </label><br />
                                                        <input type="text" name="employer" id="employer" class="form-control" value="<?php echo $employer; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Address </label><br />
                                                        <input type="text" name="address_employement" id="address_employement" class="form-control" value="<?php echo $address_employement; ?>"/>
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Position </label><br />
                                                        <input type="text" name="position" id="position" class="form-control" value="<?php echo $position; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Telephone  </label>
                                                        <input type="text" name="telephone_employment" id="telephone_employment" class="form-control" value="<?php echo $telephone_employment; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Securities-Related Firm </label><br />
                                                        <input type="checkbox" name="security_related_firm" id="security_related_firm" class="checkbox" value="1" <?php if($security_related_firm>0){echo "checked='checked'";}?>/>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>FINRA Affiliation </label>
                                                        <input type="checkbox" name="finra_affiliation" id="finra_affiliation" class="checkbox" value="1" <?php if($finra_affiliation>0){echo "checked='checked'";}?>/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4><b>Secondary Information</b></h4><br />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Spouse Name</label>
                                                        <input type="text" name="spouse_name" id="spouse_name" class="form-control" value="<?php echo $spouse_name; ?>" />
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Spouse SSN <span class="text-red"></span></label>
                                                        <input type="text" name="spouse_ssn" id="spouse_ssn" class="form-control" value="<?php echo $spouse_ssn; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row"> 
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Dependents </label>
                                                        <input type="text" name="dependents" id="Dependents" class="form-control" value="<?php echo $dependents; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Salutation </label>
                                                        <input type="text" name="salutation" id="salutation" class="form-control" value="<?php echo $salutation; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4><b>CIP</b></h4><br />
                                                </div>
                                            </div> 
                                            <div class="row">
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><span class="text-red"></span></label>
                                                        <input type="radio" onclick="close_other()" name="options" id="options" class="radio" style="display: inline;" value="1" <?php if($options == 1){echo "checked='checked'";}?>/>&nbsp;<label>Driver License</label>&nbsp;&nbsp;
                                                        <input type="radio" onclick="close_other()" name="options" id="options" class="radio" style="display: inline;" value="2" <?php if($options == 2){echo "checked='checked'";}?>/>&nbsp;<label>Passport</label>&nbsp;&nbsp;
                                                        <input type="radio" onclick="open_other()" name="options" id="options" class="radio" style="display: inline;" value="3" <?php if($options == 3){echo "checked='checked'";}?>/>&nbsp;<label>Other</label>&nbsp;
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="display: none;" id="other_div">
                                                    <div class="form-group">
                                                        <label>Other </label>
                                                        <input type="text" name="other" id="other" class="form-control" value="<?php echo $other; ?>"/>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row">
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Number </label>
                                                        <input type="number" name="number" id="number" class="form-control" value="<?php echo $number; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Expiration </label>
                                                        <div id="demo-dp-range">
                                                            <div class="input-daterange input-group" id="datepicker">
                                                               <input type="text" name="expiration" id="expiration" class="form-control" value="<?php echo date('m/d/Y',strtotime($expiration)); ?>"/>
                                                            </div>
                                                        </div>                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>State </label>
                                                        <select name="state_employe" id="state_employe" class="form-control">
                                                            <option value="">Select State</option>
                                                            <?php foreach($get_state as $key=>$val){ ?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($state_employe != '' && $state_employe==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Date Verified </label>
                                                        <div id="demo-dp-range">
                                                            <div class="input-daterange input-group" id="datepicker">
                                                                <input type="text" name="date_verified" id="date_verified" class="form-control" value="<?php echo date('m/d/Y',strtotime($date_verified)); ?>"/>
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
                                                    <input type="hidden" name="employment_id" id="employment_id" value="<?php echo $id;?>" />
                                					<input type="submit" name="employment" onclick="waitingDialog.show();" value="Save"/>	
                                                    <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                </form>
                                </div>
                                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="objectives"){ echo "active"; } ?>" id="tab_cc">
                                   
                                        <div class="panel-overlay-wrap">
                                            <div class="panel">
                                            <div id="msg"></div>
                                               <div class="panel-heading">
                                                    <h4 class="panel-title" style="font-size: 20px;"><?php if(isset($_SESSION['client_full_name'])){echo $_SESSION['client_full_name'];}?></h4>
                                               </div>
                                            <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                       <h4 class="panel-title" style="font-size: 20px;">Source Objectives <a href="#" onclick="return add_allobjectives()"><i class="fa fa-angle-double-right"></i></a></h4>
                                                        <form id="add_all_objectives" name="add_all_objectives" method="post">
                                                        <?php foreach($get_objectives as $key=>$val){
                                                            ?>
                                                            <?php
                                                            $obj_id = $val['id'];//echo '<pre>';print_r($trans_check_id);
                                                            if(!in_array($obj_id, $objectives_check_id)){?>
                                                           
                                                                <input type="hidden" name="allobjectives[]" id="allobjectives" value="<?php echo $val['id'];?>" />
                                                                <input type="hidden" name="allobjectives_id" id="allobjectives_id" value="<?php echo $id; ?>" />
                                                                <input type="hidden" name="add_allobjectives"  value="Add_AllObjectives" id="add_allobjective"/>
                                                        <?php }} ?>
                                                        </form>
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <h4 class="panel-title" style="font-size: 20px;"><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&delete_action=delete_allobjectives&delete_id=<?php echo $_SESSION['client_id'];?>"><i class="fa fa-angle-double-left"></i></a> Selected Objectives</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div style="display: block; border: 1px solid #ddd;">
                                                        <div class="table-responsive" style="padding: 5px !important;">
                                            			<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            	            <tbody>
                                                                <?php foreach($get_objectives as $key=>$val){
                                                                    ?>
                                                                    
                                                                    <?php
                                                                    $obj_id = $val['id'];//echo '<pre>';print_r($trans_check_id);
                                                                    if(!in_array($obj_id, $objectives_check_id)){?>
                                                                    
                                                                   <tr>
                                                                   <td class="text-center">
                                                                   <form id="add_objectives_<?php echo $val['id'];?>" name="add_objectives_<?php echo $val['id'];?>" method="post">
                                                                        <a href="#" onclick="return add_objectives(<?php echo $val['id'];?>)" style="color: black !important;"><?php echo $val['option'];?>  <i class="fa fa-angle-right"></i></a>
                                                                        <input type="hidden" name="objectives" id="objectives" value="<?php echo $val['id'];?>" />
                                                                        <input type="hidden" name="objectives_id" id="objectives_id" value="<?php echo $id; ?>" />
                                                                        <input type="hidden" name="add_objective"  value="Add_Objectives" id="add_objective"/>
                                                                   </form>
                                                                   </td>
                                                                   </tr>
                                                                <?php }} ?>
                                                            </tbody>
                                                        </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div style="display: block; border: 1px solid #ddd;">
                                                    <div class="table-responsive" style="padding: 5px !important;">
                                            			<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            	            <tbody>
                                                                 <?php foreach($get_current_objectives as $key=>$val){?>
                                                                   <tr>
                                                                        <td class="text-center"><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&delete_action=delete_objectives&objectives_id=<?php echo $val['id']; ?>" style="color: black !important;"><i class="fa fa-angle-left"></i>  <?php echo $val['oname'];?></a></td>
                                                                   </tr>
                                                                 <?php } ?>
                                                            </tbody>
                                                        </table>
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
                                                    <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="complete" value="Complete" /></a>
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                
                                </div>
                                <style>
  
                                </style>
                                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="account_no"){ echo "active"; } ?>" id="tab_dd">
                                   <form method="post">
                                        <div class="panel-overlay-wrap">
                                            <div class="panel">
                                               <div class="panel-heading">
                                                    <h4 class="panel-title" style="font-size: 20px;"><?php if(isset($_SESSION['client_full_name'])){echo $_SESSION['client_full_name'];}?></h4>
                                               </div>
                                            <div class="panel-body">
                                            <div class="row" id="account_no_row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Account No's </label><br />
                                                        <input type="text" name="account_no[]" id="account_no" class="form-control" value="<?php //echo //$valedit['account_no'];?>" />
                                                    </div>
                                                </div>
                                            	<div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Sponsor Company </label><br />
                                                        <select class="form-control" name="sponsor[]">
                                                            <option value="">Select Sponsor</option>
                                                             <?php foreach($get_sponsor as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($sponsor_company != '' && $sponsor_company==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label></label><br />
                                                        <button type="button" onclick="addMoreDocs();" class="btn btn-purple btn-icon btn-circle"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php 
                                            if($return_account != '')
                                            {
                                            foreach($return_account as $key=>$valedit){
                                                $sponsor_company = $valedit['sponsor_company'];?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label></label><br />
                                                        <input type="text" name="account_no[]" id="account_no" class="form-control" value="<?php echo $valedit['account_no'];?>" />
                                                    </div>
                                                </div>
                                            	<div class="col-md-4">
                                                    <div class="form-group">
                                                        <label></label><br />
                                                        <select class="form-control" name="sponsor[]">
                                                            <option value="">Select Sponsor</option>
                                                             <?php foreach($get_sponsor as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($sponsor_company != '' && $sponsor_company==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label></label><br />
                                                        <button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } }?>
                                            
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <input type="hidden" name="account_id" id="account_id" value="<?php echo $id;?>" />
                                					<input type="submit" name="account" onclick="waitingDialog.show();" value="Save"/>	
                                                    <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                </form>
                                </div>
                                <!--<div class="tab-pane " id="tab_ee">
                                    
                                        <div class="panel-overlay-wrap">
                                            <div class="panel">
                                               <div class="panel-heading">
                                                    <h4 class="panel-title" style="font-size: 20px;">Client Marcus</h4>
                                               </div>
                                            <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive" id="table-scroll">
                                                        <table class="table table-bordered table-stripped table-hover">
                                                            <thead>
                                                                <th>#NO</th>
                                                                <th>Client's Document</th>
                                                                <th>Action</th>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>file1.png</td>
                                                                    <td><a href="<?php echo CURRENT_PAGE; ?>?action=download&id=" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Download</a></td>
                                                                </tr>
                                                          </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            <!--div class="panel-footer">
                                               <!-- <div class="selectwrap">
                                                    <input type="hidden" name="id" id="id" value="" />
                                					<input type="submit" name="submit" value="Save"/>	
                                                    <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                                </div>
                                           </div-->
                                        <!--</div>
                                    </div>
                              
                                </div>-->
                                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="suitability"){ echo "active"; } ?>" id="tab_ff">
                                    <form method="post">
                                        <div class="panel-overlay-wrap">
                                            <div class="panel">
                                               <div class="panel-heading">
                                                    <h4 class="panel-title" style="font-size: 20px;"><?php if(isset($_SESSION['client_full_name'])){echo $_SESSION['client_full_name'];}?></h4>
                                               </div>
                                            <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Income </label>
                                                        <select name="income" id="income" class="form-control">
                                                            <option value="">Select Income</option>
                                                            <?php foreach($get_income as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($income != '' && $income==$val['id']){echo "selected='selected'";} ?>><?php echo $val['option'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Goal Horizon </label>
                                                        <select name="goal_horizone" id="goal_horizone" class="form-control">
                                                            <option value="">Select Goal Horizon</option>
                                                            <?php foreach($get_horizon as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($goal_horizone != '' && $goal_horizone==$val['id']){echo "selected='selected'";} ?>><?php echo $val['option'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Net Worth </label>
                                                        <select name="net_worth" id="net_worth" class="form-control">
                                                            <option value="">Select Net Worth</option>
                                                            <?php foreach($get_networth as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($net_worth != '' && $net_worth==$val['id']){echo "selected='selected'";} ?>><?php echo $val['option'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Risk Tolerance </label>
                                                        <select name="risk_tolerance" id="risk_tolerance" class="form-control">
                                                            <option value="">Select Risk Tolerance</option>
                                                            <?php foreach($get_risk_tolerance as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($risk_tolerance != '' && $risk_tolerance==$val['id']){echo "selected='selected'";} ?>><?php echo $val['option'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Annual Expenses </label>
                                                        <select name="annual_expenses" id="annual_expenses" class="form-control">
                                                            <option value="">Select Annual Expenses</option>
                                                            <?php foreach($get_annual_expenses as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($annual_expenses != '' && $annual_expenses==$val['id']){echo "selected='selected'";} ?>><?php echo $val['option'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Liquidity Needs </label>
                                                        <select name="liquidity_needs" id="liquidity_needs" class="form-control">
                                                            <option value="">Select Liquidity Needs</option>
                                                            <?php foreach($get_liqudity_needs as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($liquidity_needs != '' && $liquidity_needs==$val['id']){echo "selected='selected'";} ?>><?php echo $val['option'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Liquid Net Worth </label>
                                                        <select name="liquid_net_worth" id="liquid_net_worth" class="form-control">
                                                            <option value="">Select Liquid Net Worth</option>
                                                            <?php foreach($get_liquid_net_worth as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($liquid_net_worth != '' && $liquid_net_worth==$val['id']){echo "selected='selected'";} ?>><?php echo $val['option'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Special Expenses </label>
                                                        <select name="special_expenses" id="special_expenses" class="form-control">
                                                            <option value="">Select Special Expenses</option>
                                                            <?php foreach($get_special_expenses as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($special_expenses != '' && $special_expenses==$val['id']){echo "selected='selected'";} ?>><?php echo $val['option'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>% of Portfolio </label>
                                                        <select name="per_of_portfolio" id="per_of_portfolio" class="form-control">
                                                            <option value="">Select % of Portfolio</option>
                                                            <?php foreach($get_portfolio as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($per_of_portfolio != '' && $per_of_portfolio==$val['id']){echo "selected='selected'";} ?>><?php echo $val['option'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Timeframe for Special Exp </label>
                                                        <select name="timeframe_for_special_exp" id="timeframe_for_special_exp" class="form-control">
                                                            <option value="">Select Timeframe for Special Exp</option>
                                                            <?php foreach($get_time_for_exp as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($timeframe_for_special_exp != '' && $timeframe_for_special_exp==$val['id']){echo "selected='selected'";} ?>><?php echo $val['option'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Account Use </label>
                                                        <select name="account_use" id="account_use" class="form-control">
                                                            <option value="">Select Account Use</option>
                                                            <?php foreach($get_account_use as $key=>$val){?>
                                                            <option value="<?php echo $val['id'];?>" <?php if($account_use != '' && $account_use==$val['id']){echo "selected='selected'";} ?>><?php echo $val['option'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Signed By </label>
                                                        <input type="text" name="signed_by" id="signed_by" class="form-control" value="<?php echo $signed_by;?>"/>
                                                    </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Sign Date </label>
                                                        <div id="demo-dp-range">
        					                                <div class="input-daterange input-group" id="datepicker">
                                                                <input type="text" name="sign_date" id="sign_date" value="<?php echo date('m/d/Y',strtotime($sign_date));?>" class="form-control" />
        					                                </div>
        				                                </div>
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tax Bracket </label>
                                                        <input type="number" name="tax_bracket" id="tax_bracket" class="form-control" value="<?php echo $tax_bracket;?>"/>
                                                    </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                  <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tax ID </label>
                                                        <input type="text" name="tax_id" id="tax_id" class="form-control" value="<?php echo $tax_id;?>"/>
                                                    </div>
                                                 </div>
                                             </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <input type="hidden" name="suitability_id" id="suitability_id" value="<?php echo $id;?>" />
		                         			       <input type="submit" onclick="waitingDialog.show();" name="suitability" value="Save"/>	
                                                    <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                  </form>
                                </div>
                                <?php if($action=='edit' && $id>0){?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"><br /><div class="selectwrap">
                                            <a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=previous" class="previous next_previous_a" style="float: left;">&laquo; Previous</a>
                                            <a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=next" class="next next_previous_a" style="float: right;">Next &raquo;</a>
                                        </div>
                                     </div>
                                     </div>
                                 </div>
                                <?php } ?>
                                 
                            </div>
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
                                    <th>Name</th>
                                    <th>File</th>
                                    <th>Account Type</th>
                                    <th>Broker's Name</th>
                                    <th>Telephone</th>
                                    <th>Contact Status</th>
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
                                        <td><?php echo $val['first_name']." ".$val['last_name']; ?></td>
                                        <td><?php echo $val['client_file_number']; ?></td>
                                        <td><?php echo $val['account_type']; ?></td>
                                        <td><?php echo $val['broker']; ?></td>
                                        <td><?php echo '('.substr($val['telephone'], 0, 3).')'.'-'.substr($val['telephone'], 3, 3).'-'.substr($val['telephone'],6); //echo $val['telephone']; ?></td>
                                        <td>
                                        <?php 
                                        if($val['contact_status']==1)
                                        {
                                            echo "Yes";
                                        }
                                        else
                                        {
                                            echo "No"; 
                                        } ?>
                                        </td>
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
			<div id="joint_account" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header" style="margin-bottom: 0px !important;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h4 class="modal-title">Joint Accounts</h4>
				</div>
				<div class="modal-body">
                <form method="post">
                <div class="inputpopup">
                    <a class="btn btn-sm btn-success" href="#add_joint_account" data-toggle="modal" style="float: right !important; margin-right: 5px !important;"><i class="fa fa-plus"></i> Add New</a>
    			</div>
                <div class="inputpopup">
                    <div class="table-responsive" id="table-scroll" style="margin: 0px 5px 0px 5px;">
                        <table class="table table-bordered table-stripped table-hover">
                            <thead>
                                <th>#NO</th>
                                <th>Joint Name</th>
                                <th>SSN</th>
                                <th>DOB</th>
                                <th>Income</th>
                                <th>Occupation</th>
                                <th>Position</th>
                                <th>Securities-Related Firm?</th>
                                <th>Employer</th>
                                <th>Emp. Address</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><a href="#add_joint_account" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><a href="#add_joint_account" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a></td>
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
                        <input type="text" name="ssn" id="ssn" class="form-control" />
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
<style>
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
function open_newnotes()
{
    document.getElementById("add_row_notes").style.display = "";
}
</script>
<script>
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
</script>
<script type="text/javascript">
$(document).ready(function(){
    $('#telephone').mask("(999)-999-9999");
});
$(document).ready(function(){
    $('#telephone_dis').mask("(999)-999-9999");
});
$(document).ready(function(){
    $('#telephone_employment').mask("(999)-999-9999");
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
<script>
$(document).ready(function(){
    $('#client_ssn').mask("999-99-9999");
    $('#spouse_ssn').mask("999-99-9999");
});
function checkLength(el) {
  if (el.value.length != 12) {
    document.getElementById("client_file_msg").innerHTML="*Length must be exactly 12 characters";
  }else{
    document.getElementById("client_file_msg").innerHTML="";
  }
}
function checkLengthhlc(el) {
  if (el.value.length != 20) {
    document.getElementById("household_msg").innerHTML="*Length must be exactly 20 characters";
  }else{
    document.getElementById("household_msg").innerHTML="";
  }
}
function checkLengthclr(el) {
  if (el.value.length != 20) {
    document.getElementById("clearing_acct_msg").innerHTML="*Length must be exactly 20 characters";
  }else{
    document.getElementById("clearing_acct_msg").innerHTML="";
  }
}
function getAge(dateString) 
{
    var today = new Date();
    var birthDate = new Date(dateString);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
    {
        age--;
    }
    document.getElementById("age").value=age;
    document.getElementById("age1").value=age;
}
(function($) {
        $.fn.currencyFormat1 = function() {
            this.each( function( i ) {
                $(this).change( function( e ){
                    if( isNaN( parseFloat( this.value ) ) ) return;
                    this.value = parseFloat(this.value).toFixed(2);

                });
                $(this).change( function( e ){
                    if(  this.value  < 101 ) return;
                    this.value = 100.00;
                    this.value = parseFloat(this.value).toFixed(1);
                });
            });
            return this; //for chaining
        }
    })( jQuery );

  
    $( function() {
        $('.currency1').currencyFormat1();
    });
</script>
<script>
function get_client_notes(){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("ajax_notes").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_client_notes.php", true);
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

   var url = "client_maintenance.php"; // the script where you handle the form input.
   //alert("#add_client_notes_"+note_id);
   $.ajax({
      type: "POST",
      url: url,
      data: $("#add_client_notes_"+note_id).serialize(), // serializes the form's elements.
      success: function(data){
          if(data=='1'){
            
            get_client_notes();
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
function delete_notes(note_id){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = this.responseText;
                if(data=='1'){
                   get_client_notes(); 
                   $('#msg_notes').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Note deleted Successfully.</div>');
                   //get_client_notes();
                  
                  }
                  else{
                       $('#msg_notes').html('<div class="alert alert-danger">'+data+'</div>');
                  }
                
            }
        };
        xmlhttp.open("GET", "client_maintenance.php?delete_action=delete_notes&note_id="+note_id, true);
        xmlhttp.send();
}
/*{
   $('#msg').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');

   var url = "client_maintenance.php"; // the script where you handle the form input.
   //alert("#add_client_notes_"+note_id);
   $.ajax({
      type: "POST",
      url: url,
      data: $("#add_client_notes_"+note_id).serialize(), // serializes the form's elements.
      success: function(data){
          if(data=='1'){
            $('#msg_notes').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Note deleted Successfully.</div>');
               
          }
          else{
               $('#msg').html('<div class="alert alert-danger">'+data+'</div>');
          }
          
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
           $('#msg').html('<div class="alert alert-danger">Something went wrong, Please try again.</div>')
      }
      
   });

   //e.preventDefault(); // avoid to execute the actual submit of the form.
   return false;
       
}*/
</script>

<style>
.validation
{
  color: red;
  margin-bottom: 20px;
}
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
<script>
  function changeHandler(val)
  {alert(parseFloat(val.toFixed(2)));
    document.getElementById("split_rate").value = val.toFixed(2)
  }
</script>

<script>
//submit share form data
function add_objectives(objectives_id)
{
    $('form[name=add_objectives_'+objectives_id+']').submit();/*$('form[name=add_objectives_'objectives_id+']').submit();*/
    
     return false;   
}
function add_allobjectives()
{
    $('form[name=add_all_objectives]').submit();
}
/*function formsubmit_objectives(objectives_id,action)
{
    $('#msg').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');

    var url = "client_maintenance.php"; // the script where you handle the form input.
  
    $.ajax({
       type: "POST",
       url: url,
       data: $("#add_objectives_"+objectives_id).serialize(), // serializes the form's elements.
       success: function(data){
           if(data=='1'){
            //$('#msg').html('<div class="alert alert-success">Data Successfully Saved.</div>');
            window.location.href = "client_maintenance.php?action=add_new&tab=objectives";
           }
           else{
               $('#msg').html('<div class="alert alert-danger">'+data+'Something went wrong.</div>');
           }
           
       },
       error: function(XMLHttpRequest, textStatus, errorThrown) {
            $('#msg').html('<div class="alert alert-danger">Something went wrong, Please try again.</div>')
       }
       
    });
    return false;
        
}*/

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
    document.getElementById("split_rate").value = rounded;
    
}
</script>