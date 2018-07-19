<script>
function addMoreDocs(){
    var html = '<div class="row">'+
                    '<div class="col-md-4">'+
                        '<div class="form-group">'+
                            '<label></label><br />'+
                            '<input type="text" name="account_no[]" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="account_no" class="form-control" />'+
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
<div class="container">
<h1 class="<?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'topfixedtitle';}?>">Client Maintenance</h1>
<div class="col-lg-12 well <?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'fixedwell';}?>">
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <div class="tab-content col-md-12">
            <div class="tab-pane active" id="tab_a">
                    <?php
                    if($action=='add_new'||($action=='edit' && $id>0)){
                        if($action=='add_new')
                        {
                            $_SESSION['client_full_name']='';
                        }
                        ?>
                            <ul class="nav nav-tabs <?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'topfixedtabs';}?>">
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="primary"){ echo "active"; }else if(!isset($_GET['tab'])){echo "active";}else{ echo '';} ?>"><a href="#tab_aa" data-toggle="tab">Primary</a></li>
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="account_no"){ echo "active"; } ?>"><a href="#tab_dd" data-toggle="tab">Account No's</a></li>
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="employment"){ echo "active"; } ?>"><a href="#tab_bb" data-toggle="tab">Employment</a></li>
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="suitability"){ echo "active"; } ?>"><a href="#tab_ff" data-toggle="tab">Suitability</a></li>
                              <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="objectives"){ echo "active"; } ?>"><a href="<?php echo CURRENT_PAGE; ?>#tab_cc" data-toggle="tab">Objectives</a></li>
                              
                              <!--<li><a href="#tab_ee" data-toggle="tab">Documents</a></li>-->
                                <div class="btn-group dropdown" style="float: right;">
    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
    								<ul class="dropdown-menu dropdown-menu-right" style="">
    									<li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-eye"></i> View List</a></li>
    								</ul>
    							</div>
    						</ul><form method="post">
                            <!--<div class="panel-footer"><br />
                                    <div class="selectwrap">
                                        <?php if($_GET['action']=='edit' && $_GET['id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=previous" class="previous next_previous_a" style="float: left;"><input type="button" name="previos" value="&laquo; Previous" /></a><?php } ?>
                                        <?php if($action=='edit' && $id>0){?>
                                        <a href="#view_changes" data-toggle="modal"><input type="button" name="view_changes" style="margin-left: 3% !important;" value="View Changes"/></a>
                                        <?php } ?>
                                        <a href="#client_notes" data-toggle="modal"><input type="button" onclick="get_client_notes();" name="notes" value="Notes" /></a>
                                        <a href="#client_transactions" data-toggle="modal"><input type="button" name="transactions" value="Transactions" /></a>
                                        <a href="#joint_account" data-toggle="modal"><input type="button" onclick="get_client_account();" name="joint_account" value="Joint Account" /></a>
                                        <a href="#client_attach" data-toggle="modal"><input type="button"  onclick="get_client_attach();" name="attach" value="Attachments" /></a>
                                        <input type="submit" name="submit" onclick="waitingDialog.show();" value="Save"/>	
                                        <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                        <?php if($_GET['action']=='edit' && $_GET['id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=next" class="next next_previous_a" style="float: right;"><input type="button" name="next" value="Next &raquo;" /></a><?php } ?>
                                    </div>
                                 </div>
                                <br />-->
                            <!-- Tab 1 is started -->
                            <div class="tab-content">
                                
                                <div class="row">
                                    <div class="col-md-4" style="float: right;">
                                        <div class="form-group">
                                            
                                        </div>
                                     </div>
                                 </div>
                                
                                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="primary"){ echo "active"; }else if(!isset($_GET['tab'])){echo "active";}else{ echo '';} ?>" id="tab_aa">
                                
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
                                                        <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>" class="form-control" autofocus="true" />
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
                                                            <option value="<?php echo $val['id'];?>" <?php if($broker_name != '' && $broker_name==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'].' '.$val['last_name'];?></option>
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
                                                            <option value="<?php echo $val['id'];?>" <?php if($split_broker != '' && $split_broker==$val['id']){echo "selected='selected'";} ?>><?php echo $val['first_name'].' '.$val['last_name'];?></option>
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
                                            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tab 1 is ends -->
                                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="employment"){ echo "active"; } ?>" id="tab_bb">
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
                                                <?php if($options == 3){?>
                                                <div class="col-md-6" id="other_div">
                                                    <div class="form-group">
                                                        <label>Other </label>
                                                        <input type="text" name="other" id="other" class="form-control" value="<?php echo $other; ?>"/>
                                                    </div>
                                                </div>
                                                <?php } else {?>
                                                <div class="col-md-6" style="display: none;" id="other_div">
                                                    <div class="form-group">
                                                        <label>Other </label>
                                                        <input type="text" name="other" id="other" class="form-control" value=""/>
                                                    </div>
                                                </div>
                                                <?php } ?>
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
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                              
                                </div>
                                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="objectives"){ echo "active"; } ?>" id="tab_cc">
                                        <div class="panel-overlay-wrap">
                                            <div class="panel">
                                            <div id="message"></div>
                                               <div class="panel-heading">
                                                    <h4 class="panel-title" style="font-size: 20px;"><?php if(isset($_SESSION['client_full_name'])){echo $_SESSION['client_full_name'];}?></h4>
                                               </div>
                                            <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                       <h4 class="panel-title" style="font-size: 20px;">Source Objectives <a href="#" onclick="send_allobjectives()"><i class="fa fa-angle-double-right"></i></a></h4>
                                                        <?php 
                                                        foreach($get_objectives as $key=>$val){
                                                            ?>
                                                            <?php 
                                                            $obj_id = $val['id'];//echo '<pre>';print_r($trans_check_id);
                                                            if(!in_array($obj_id, $objectives_check_id)){?>
                                                           
                                                                <input type="hidden" class="all_objective" name="allobjectives[]" id="allobjectives" value="<?php echo $val['id'];?>" />
                                                                <input type="hidden" name="allobjectives_id" id="allobjectives_id" value="<?php echo $id; ?>" />
                                                                <input type="hidden" name="add_allobjectives"  value="Add_AllObjectives" id="add_allobjective"/>
                                                        <?php }} ?>
                                                    </div>
                                                </div>
                                            	<div class="col-md-6">
                                                    <div class="form-group">
                                                        <h4 class="panel-title" style="font-size: 20px;"><a href="#" onclick="delete_allobjectives(<?php echo $_SESSION['client_id'];?>)"><i class="fa fa-angle-double-left"></i></a> Selected Objectives</h4>
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
                                                                    <a href="#" onclick="send_objectives(<?php echo $val['id'];?>)" style="color: black !important;"><?php echo $val['option'];?>  <i class="fa fa-angle-right"></i></a>
                                                                        <input type="hidden" name="objectives" id="objectives" value="<?php echo $val['id'];?>" />
                                                                        <input type="hidden" name="objectives_id" id="objectives_id" value="<?php echo $id; ?>" />
                                                                        <input type="hidden" name="add_objective"  value="Add_Objectives" id="add_objective"/>
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
                                                                        <td class="text-center"><a href="#" onclick="delete_objectives(<?php echo $val['id'];?>)" style="color: black !important;"><i class="fa fa-angle-left"></i>  <?php echo $val['oname'];?></a></td>
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
                                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="account_no"){ echo "active"; } ?>" id="tab_dd">
                                  
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
                                                        <?php if(isset($_GET['account_no']) && $_GET['account_no'] != '')
                                                        {
                                                        ?>
                                                            <input type="text" disabled="true" name="account_no_dis[]" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="account_no_dis" class="form-control" value="<?php echo $_GET['account_no'];?>" />
                                                            <input type="hidden" name="account_no[]" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="account_no" class="form-control" value="<?php echo $_GET['account_no'];?>" />
                                                        <?php 
                                                        }else{
                                                        ?>
                                                            <input type="text" name="account_no[]" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="account_no" class="form-control" value="<?php //echo //$valedit['account_no'];?>" />
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            	<div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Sponsor Company </label><br />
                                                        <?php if(isset($_GET['account_no']) && $_GET['account_no'] != ''){
                                                              $file_id = isset($_GET['file_id'])?$_GET['file_id']:0;
                                                              $data_id = isset($_GET['exception_data_id'])?$_GET['exception_data_id']:0;
                                                              $get_idc_sponsor = $instance_import->get_idc_record_details($file_id,$data_id);
                                                              $management_code = $get_idc_sponsor['management_code'];
                                                              $system_id = $get_idc_sponsor['system_id'];
                                                              $sponsor_on_system = $instance_sponsor->get_sponsor_on_system_management_code($system_id,$management_code);
                                                              $sponsor_company = isset($sponsor_on_system['id'])?$sponsor_on_system['id']:'';
                                                        }
                                                        ?>
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
                                                        <input type="text" name="account_no[]" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="account_no" class="form-control" value="<?php echo $valedit['account_no'];?>" />
                                                    </div>
                                                </div>
                                            	<div class="col-md-4">
                                                    <div class="form-group">
                                                        <label></label><br />
                                                        <select class="form-control" name="sponsor[]">
                                                            <option value="0">Select Sponsor</option>
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
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                
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
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                  
                                </div>
                                 
                            </div>
                            <div class="panel-footer fixedbtmenu"><br />
                                    <div class="selectwrap">
                                        <?php if(isset($_GET['account_no']) && ($_GET['account_no'] != '' || $_GET['account_no'] == '')){?>
                                        <input type="hidden" name="for_import" id="for_import" class="form-control" value="true" />
                                        <input type="hidden" name="file_id" id="file_id" class="form-control" value="<?php echo $_GET['file_id']; ?>" />
                                        <input type="hidden" name="temp_data_id" id="temp_data_id" class="form-control" value="<?php echo $_GET['exception_data_id']; ?>" />
                                        <?php }?>
                                        <?php if($_GET['action']=='edit' && $_GET['id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=previous" class="previous next_previous_a" style="float: left;"><input type="button" name="previos" value="&laquo; Previous" /></a><?php } ?>
                                        <?php if($_GET['action']=='edit' && $_GET['id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $id;?>&send=next" class="next next_previous_a"><input type="button" name="next" value="Next &raquo;" /></a><?php } ?>
                                        <?php if($action=='edit' && $id>0){?>
                                        <a href="#view_changes" data-toggle="modal"><input type="button" name="view_changes" style="margin-left: 4% !important;" value="View Changes"/></a>
                                        <?php } ?>
                                        <a href="#client_notes" data-toggle="modal"><input type="button" onclick="get_client_notes();" name="notes" value="Notes" /></a>
                                        <a href="#client_transactions" data-toggle="modal"><input type="button"  name="transactions" value="Transactions" /></a>
                                        <a href="#joint_account" data-toggle="modal"><input type="button" onclick="get_client_account();" name="joint_account" value="Joint Account" /></a>
                                        <a href="#client_attach" data-toggle="modal"><input type="button"  onclick="get_client_attach();" name="attach" value="Attachments" style="margin-right: 4% !important;" /></a>
                                        <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
                                        <input type="submit" name="submit" onclick="waitingDialog.show();" value="Save" style="float: right;"/>
                                    </div>
                                 </div>
                                 </form>
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
                        <h3 class="panel-title">List</h3>
            		</div>-->
            		<div class="panel-body">
                        <!--<div class="panel-control" style="float: right;">
                        <form method="post">
                            <input type="text" name="search_text" id="search_text" value="<?php echo $search_text;?>"/>
                            <button type="submit" name="submit" id="submit" value="Search"><i class="fa fa-search"></i> Search</button>
                        </form>
                        </div><br /><br />-->
                        <div class="table-responsive">
            			<table id="data-table" class="table table-striped1 table-bordered" cellspacing="0" width="100%">
            	            <thead>
            	                <tr>
                                    <th>NAME</th>
                                    <th>FILE</th>
                                    <th>ACCOUNT TYPE</th>
                                    <th>BROKER NAME</th>
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
                                        <td><?php echo $val['first_name']." ".$val['last_name']; ?></td>
                                        <td><?php echo $val['client_file_number']; ?></td>
                                        <td><?php echo $val['account_type']; ?></td>
                                        <td><?php echo $val['broker_fname']." ".$val['broker_lname']; ?></td>
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
	<!-- Modal for add client notes -->
	<div id="joint_account" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog" style="margin-left: 19% !important;">
		<div class="modal-content" style="width: 150% !important;">
		<div class="modal-header" style="margin-bottom: 0px !important;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h4 class="modal-title">Client's Joint Account</h4>
		</div>
		<div class="modal-body">
        
        <div class="inputpopup">
            <a class="btn btn-sm btn-success" href="#add_joint_account" onclick="get_client_edit(0);" data-toggle="modal" style="float: right !important; margin-right: 5px !important;"><i class="fa fa-plus"></i> Add New</a>
		</div>
        
        <div class="col-md-12">
            <div id="msg_account">
            </div>
        </div>
       
        <div class="inputpopup">
            <div class="table-responsive" id="ajax_account" style="margin: 0px 5px 0px 5px;">
                
            </div>
		</div>
        </div><!-- End of Modal body -->
		</div><!-- End of Modal content -->
		</div><!-- End of Modal dialog -->
</div><!-- End of Modal -->
<!-- Lightbox strart -->	
          
          
        <!-- Lightbox strart -->							
			<!--Modal for add joint account -->
			<div id="add_joint_account" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
			
                <div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header" style="margin-bottom: 0px !important;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h4 class="modal-title">Add Joint Account</h4>
				</div>
				<div class="modal-body">
                <form method="post" id="add_new_account" name="add_new_account" onsubmit="return formsubmit_account();">
                    
				</form>					
                
				</div><!-- End of Modal body -->
				</div><!-- End of Modal content -->
			</div><!-- End of Modal dialog -->
		</div><!-- End of Modal -->
        <!-- Lightbox strart -->							
	<!-- Modal for add client notes -->
	<div id="client_attach" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header" style="margin-bottom: 0px !important;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h4 class="modal-title">Client's Attach</h4>
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
                            $lable_array = array('first_name' => 'First Name','mi' => 'MI','last_name' => 'Last Name
            ','do_not_contact' => 'Do Not Contact','active' => 'Active','ofac_check' => 'OFAC Check','fincen_check' => 'FinCEN Check','long_name' => 'Long Name','client_file_number' => 'Client File Number','clearing_account' => 'Clearing Acct','client_ssn' => 'Client SSN','house_hold' => 'Household','split_broker' => 'Split Broker','split_rate' => 'Split Rate','address1' => 'Address1','address2' => 'Address2','city' => 'City','state' => 'State','zip_code' => 'Zip Code','citizenship' => 'Citizenship','birth_date' => 'Birth Date','age' => 'Age','date_established' => 'Date Established','open_date' => 'Open Date','naf_date' => 'NAF Date','last_contacted' => 'Last Contacted','account_type' => 'Account Type','broker_name' => 'Broker','telephone' => 'Telephone','contact_status' => 'Contact Status',

'occupation' => 'Occupation','employer' => 'Employer','address' => 'Address','position' => 'Position','telephone' => 'Telephone','security_related_firm' => 'Securities-Related Firm','finra_affiliation' => 'FINRA Affiliation','spouse_name' => 'Spouse Name','spouse_ssn' => 'Spouse SSN','dependents' => 'Dependents','salutation' => 'Salutation','options' => 'CIP Options','other' => 'Other','number' => 'Number','expiration' => 'Expiration','state' => 'State','date_verified' => 'Date Verified',

'income' => 'Income','goal_horizon' => 'Goal Horizon','net_worth' => 'Net Worth','risk_tolerance' => 'Risk Tolerance','annual_expenses' => 'Annual Expenses','liquidity_needs' => 'Liquidity Needs','liquid_net_worth' => 'Liquid Net Worth','special_expenses' => 'Special Expenses','per_of_portfolio' => '% of Portfolio','time_frame_for_special_exp' => 'Timeframe for Special Exp','account_use' => 'Account Use','signed_by' => 'Signed By','sign_date' => 'Sign Date','tax_bracket' => 'Tax Bracket','tax_id' => 'Tax ID');
                            foreach($client_change_data as $key=>$val){
                                
                                if(isset($lable_array[$val['field']])){
                                    $feild_name = $lable_array[$val['field']];
                                }else {
                                    $feild_name = $val['field'];
                                }?>
                                <tr>
                                
                                    <td><?php echo ++$count; ?></td>
                                    <td><?php echo $val['user_initial'];?></td>
                                    <td><?php echo date('m/d/Y',strtotime($val['created_time']));?></td>
                                    <td><?php echo $feild_name;?></td>
                                    <?php 
                                    if($feild_name == 'Do Not Contact' && $val['old_value'] == 0){?>
                                    <td><?php echo 'UnChecked';?></td>
                                    <td><?php echo 'Checked';?></td>
                                    <?php } 
                                    else if($feild_name == 'Do Not Contact' && $val['old_value'] == 1){?>
                                    <td><?php echo 'Checked';?></td>
                                    <td><?php echo 'UnChecked';?></td>
                                    <?php }
                                    else if($feild_name == 'Active' && $val['old_value'] == 0){?>
                                    <td><?php echo 'UnChecked';?></td>
                                    <td><?php echo 'Checked';?></td>
                                    <?php } 
                                    else if($feild_name == 'Active' && $val['old_value'] == 1){?>
                                    <td><?php echo 'Checked';?></td>
                                    <td><?php echo 'UnChecked';?></td>
                                    <?php }
                                    else if($feild_name == 'State'){
                                    $state_name = $instance->get_state_name($val['old_value']);?>
                                    <td><?php echo $state_name['state_name'];?></td>
                                    <?php $state_name = $instance->get_state_name($val['new_value']);?>
                                    <td><?php echo $state_name['state_name'];?></td>
                                    <?php }
                                    else if($feild_name == 'Account Type'){
                                    $account_name = $instance->get_account_name($val['old_value']);?>
                                    <td><?php echo $account_name['account_type'];?></td>
                                    <?php $account_name = $instance->get_account_name($val['new_value']);?>
                                    <td><?php echo $account_name['account_type'];?></td>
                                    <?php }
                                    else if($feild_name == 'Broker'){
                                    $broker_name = $instance->get_broker_name($val['old_value']);?>
                                    <td><?php echo $broker_name['broker_name'];?></td>
                                    <?php $broker_name = $instance->get_broker_name($val['new_value']);?>
                                    <td><?php echo $broker_name['broker_name'];?></td>
                                    <?php }
                                    else if($feild_name == 'Split Broker'){
                                    $broker_name = $instance->get_broker_name($val['old_value']);?>
                                    <td><?php echo $broker_name['broker_name'];?></td>
                                    <?php $broker_name = $instance->get_broker_name($val['new_value']);?>
                                    <td><?php echo $broker_name['broker_name'];?></td>
                                    <?php }
                                    else if($feild_name == 'Contact Status'){?>
                                    <td>
                                    <?php 
                                    if($val['old_value'] == 1)
                                    {
                                        echo 'Yes';
                                    }
                                    else if($val['old_value'] == 2)
                                    {
                                        echo 'No';
                                    }
                                    ?>
                                    </td>
                                    <td>
                                    <?php 
                                    if($val['new_value'] == 1)
                                    {
                                        echo 'Yes';
                                    }
                                    else if($val['new_value'] == 2)
                                    {
                                        echo 'No';
                                    }
                                    ?>
                                    </td>
                                    <?php }
                                    else if($feild_name == 'Securities-Related Firm' && $val['old_value'] == 0){?>
                                    <td><?php echo 'UnChecked';?></td>
                                    <td><?php echo 'Checked';?></td>
                                    <?php } 
                                    else if($feild_name == 'Securities-Related Firm' && $val['old_value'] == 1){?>
                                    <td><?php echo 'Checked';?></td>
                                    <td><?php echo 'UnChecked';?></td>
                                    <?php }
                                    else if($feild_name == 'FINRA Affiliation' && $val['old_value'] == 0){?>
                                    <td><?php echo 'UnChecked';?></td>
                                    <td><?php echo 'Checked';?></td>
                                    <?php } 
                                    else if($feild_name == 'FINRA Affiliation' && $val['old_value'] == 1){?>
                                    <td><?php echo 'Checked';?></td>
                                    <td><?php echo 'UnChecked';?></td>
                                    <?php }
                                    else if($feild_name == 'CIP Options'){?>
                                    <td>
                                    <?php 
                                    if($val['old_value'] == 1)
                                    {
                                        echo 'Driver License';
                                    }
                                    else if($val['old_value'] == 2)
                                    {
                                        echo 'Passport';
                                    }
                                    else if($val['old_value'] == 3)
                                    {
                                        echo 'Other';
                                    }
                                    ?>
                                    </td>
                                    <td>
                                    <?php 
                                    if($val['new_value'] == 1)
                                    {
                                        echo 'Driver License';
                                    }
                                    else if($val['new_value'] == 2)
                                    {
                                        echo 'Passport';
                                    }
                                    else if($val['new_value'] == 3)
                                    {
                                        echo 'Other';
                                    }
                                    ?>
                                    </td>
                                    <?php }
                                    else if($feild_name == 'Income'){
                                    $income_name = $instance->get_income_name($val['old_value']);?>
                                    <td><?php echo $income_name['income'];?></td>
                                    <?php $income_name = $instance->get_income_name($val['new_value']);?>
                                    <td><?php echo $income_name['income'];?></td>
                                    <?php }
                                    else if($feild_name == 'Goal Horizon'){
                                    $goal_name = $instance->get_goal_horizon_name($val['old_value']);?>
                                    <td><?php echo $goal_name['goal'];?></td>
                                    <?php $goal_name = $instance->get_goal_horizon_name($val['new_value']);?>
                                    <td><?php echo $goal_name['goal'];?></td>
                                    <?php }
                                    else if($feild_name == 'Net Worth'){
                                    $net_worth_name = $instance->get_net_worth_name($val['old_value']);?>
                                    <td><?php echo $net_worth_name['net_worth'];?></td>
                                    <?php $net_worth_name = $instance->get_net_worth_name($val['new_value']);?>
                                    <td><?php echo $net_worth_name['net_worth'];?></td>
                                    <?php }
                                    else if($feild_name == 'Risk Tolerance'){
                                    $risk_tolerance_name = $instance->get_risk_tolerance_name($val['old_value']);?>
                                    <td><?php echo $risk_tolerance_name['risk'];?></td>
                                    <?php $risk_tolerance_name = $instance->get_risk_tolerance_name($val['new_value']);?>
                                    <td><?php echo $risk_tolerance_name['risk'];?></td>
                                    <?php }
                                    else if($feild_name == 'Annual Expenses'){
                                    $annual_exp_name = $instance->get_annual_expenses_name($val['old_value']);?>
                                    <td><?php echo $annual_exp_name['annual_expense'];?></td>
                                    <?php $annual_exp_name = $instance->get_annual_expenses_name($val['new_value']);?>
                                    <td><?php echo $annual_exp_name['annual_expense'];?></td>
                                    <?php }
                                    else if($feild_name == 'Liquidity Needs'){
                                    $liquid_need = $instance->get_liquidity_needs_name($val['old_value']);?>
                                    <td><?php echo $liquid_need['liquidity_needs'];?></td>
                                    <?php $liquid_need = $instance->get_liquidity_needs_name($val['new_value']);?>
                                    <td><?php echo $liquid_need['liquidity_needs'];?></td>
                                    <?php }
                                    else if($feild_name == 'Liquid Net Worth'){
                                    $liquid_net_worth_name = $instance->get_liquid_net_worth_name($val['old_value']);?>
                                    <td><?php echo $liquid_net_worth_name['liquid_net_worth'];?></td>
                                    <?php $liquid_net_worth_name = $instance->get_liquid_net_worth_name($val['new_value']);?>
                                    <td><?php echo $liquid_net_worth_name['liquid_net_worth'];?></td>
                                    <?php }
                                    else if($feild_name == 'Special Expenses'){
                                    $spc_exp_name = $instance->get_special_expenses_name($val['old_value']);?>
                                    <td><?php echo $spc_exp_name['special_expense'];?></td>
                                    <?php $spc_exp_name = $instance->get_special_expenses_name($val['new_value']);?>
                                    <td><?php echo $spc_exp_name['special_expense'];?></td>
                                    <?php }
                                    else if($feild_name == '% of Portfolio'){
                                    $per_port_name = $instance->get_per_of_portfolio_name($val['old_value']);?>
                                    <td><?php echo $per_port_name['per_of_portfolio'];?></td>
                                    <?php $per_port_name = $instance->get_per_of_portfolio_name($val['new_value']);?>
                                    <td><?php echo $per_port_name['per_of_portfolio'];?></td>
                                    <?php }
                                    else if($feild_name == 'Timeframe for Special Exp'){
                                    $time_frm_name = $instance->get_time_frame_for_special_exp_name($val['old_value']);?>
                                    <td><?php echo $time_frm_name['time_frame'];?></td>
                                    <?php $time_frm_name = $instance->get_time_frame_for_special_exp_name($val['new_value']);?>
                                    <td><?php echo $time_frm_name['time_frame'];?></td>
                                    <?php }
                                    else if($feild_name == 'Account Use'){
                                    $account_name = $instance->get_account_use_name($val['old_value']);?>
                                    <td><?php echo $account_name['account_use'];?></td>
                                    <?php $account_name = $instance->get_account_use_name($val['new_value']);?>
                                    <td><?php echo $account_name['account_use'];?></td>
                                    <?php }
                                    else {?>
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
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 5 ] }, 
                        { "bSearchable": false, "aTargets": [ 5 ] }]
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
function open_newaccount()
{
    document.getElementById("add_row_account").style.display = "";
    
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
function send_allobjectives()
{
    $('#message').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
    var values = $("input[name='allobjectives[]']") .map(function(){return $(this).val();}).get();
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                $( "#tab_cc" ).load(window.location.href + " #tab_cc" );
            }
        };
        xmlhttp.open("GET", "ajax_objectives.php?all_objectives="+values, true);
        xmlhttp.send();
}
function send_objectives(id)
{
    $('#message').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                $( "#tab_cc" ).load(window.location.href + " #tab_cc" );
            }
        };
        xmlhttp.open("GET", "ajax_objectives.php?objectives="+id, true);
        xmlhttp.send();
}
function delete_allobjectives(id)
{
    $('#message').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                $( "#tab_cc" ).load(window.location.href + " #tab_cc" );
            }
        };
        xmlhttp.open("GET", "ajax_objectives.php?delete_allobjective="+id, true);
        xmlhttp.send();
}
function delete_objectives(id)
{
    $('#message').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                $( "#tab_cc" ).load(window.location.href + " #tab_cc" );
            }
        };
        xmlhttp.open("GET", "ajax_objectives.php?delete_objective="+id, true);
        xmlhttp.send();
}
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
function get_client_edit(account_id){
   
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("add_new_account").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_client_edit_account.php?&id="+account_id, true);
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
function get_client_attach(){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                //alert(this.responseText);
                document.getElementById("ajax_attach").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_client_attach.php", true);
        xmlhttp.send();
}
function get_client_account(){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) 
            {
                document.getElementById("ajax_account").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_client_account.php", true);
        xmlhttp.send();
}
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
function formsubmit_account()
{
   $('#msg').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');

   var url = "client_maintenance.php"; // the script where you handle the form input.
   
   $.ajax({
      type: "POST",
      url: url,
      data: $("#add_new_account").serialize(), // serializes the form's elements.
      success: function(data){
          if(data=='1'){
         	 $("#add_joint_account").modal('hide');
            get_client_account();
            $('#msg_account').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Data Successfully Saved.</div>');
            //window.location.href = "client_maintenance.php";//get_client_notes();   
          }
          else{
               $('#msg_account').html('<div class="alert alert-danger">'+data+'</div>');
          }
          
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
           $('#msg_account').html('<div class="alert alert-danger">Something went wrong, Please try again.</div>')
      }
      
   });

   //e.preventDefault(); // avoid to execute the actual submit of the form.
   return false;
       
}
/*function formsubmit_account()
{ 
        $('#msg').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
    var myForm = document.getElementById('add_new_account');
   var url = "client_maintenance.php"; // the script where you handle the form input.
   $.ajax({
      type: "POST",
      url: url,
      data: myForm.serialize(), // serializes the form's elements.
      success: function(data){
          if(data=='12'){
            alert(data);
            get_client_account();
            $('#msg_notes').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Data Successfully Saved.</div>');  
          }
          else{
               $('#msg_notes').html('<div class="alert alert-danger">'+data+'</div>');
          }
          
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
           $('#msg_notes').html('<div class="alert alert-danger">Something went wrong, Please try again.</div>')
      } 
   });
   return false;
}*/
function attachsubmit(attach_id)
{ 
        var myForm = document.getElementById('add_client_attach_'+attach_id);
        form_data = new FormData(myForm);
        $.ajax({
            url: 'client_maintenance.php', // point to server-side PHP script 
            
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                  if(data=='1'){
                    
                    get_client_attach();
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
function delete_attach(attach_id){
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = this.responseText;
                if(data=='1'){
                   get_client_attach(); 
                   $('#msg_attach').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Attach deleted Successfully.</div>');
                  }
                  else{
                       $('#msg_attach').html('<div class="alert alert-danger">'+data+'</div>');
                  }
            }
        };
        xmlhttp.open("GET", "client_maintenance.php?delete_action=delete_attach&attach_id="+attach_id, true);
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
  {//alert(parseFloat(val.toFixed(2)));
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
<script type="text/javascript">
/*$(document).ready(function() {
    alert(document.getElementById("fname"));//.focus();
});*/
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