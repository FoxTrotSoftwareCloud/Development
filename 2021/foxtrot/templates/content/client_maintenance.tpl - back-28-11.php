<script>
function addMoreDocs(){
    var html = '<div class="row">'+
                    '<div class="col-md-4">'+
                        '<div class="form-group">'+
                            '<label></label><br />'+
                            '<input type="text" name="account_no" id="account_no" class="form-control" />'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-4">'+
                        '<div class="form-group">'+
                            '<label></label><br />'+
                            '<input type="text" name="company" id="company" class="form-control" />'+
                        '</div>'+
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
<h1>Client Maintenance</h1>
    <div class="col-lg-12 well">
        <ul class="nav nav-pills nav-stacked col-md-2">
          <li class="active"><a href="#tab_a" data-toggle="pill">List of Clients</a></li>
          <li><a href="#tab_b" data-toggle="pill">Client Maintenance</a></li>
        </ul>
        <div class="tab-content col-md-10">
                <div class="tab-pane active" id="tab_a">
                    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
                    <?php
                    if($action=='add_new'||($action=='edit' && $id>0)){
                        ?>
                        <form method="post">
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
                                        <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action=='add_new'?'Add':'Edit'; ?> New Client</h3>
                					</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>First Name <span class="text-red">*</span></label>
                                                    <input type="text" name="fname" id="fname" value="" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Last Name <span class="text-red">*</span></label>
                                                    <input type="text" name="lname" id="lname" value="" class="form-control" />
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Client File <span class="text-red">*</span></label>
                                                    <input type="file" name="client_file" id="client_file" value="" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Account Type<span class="text-red">*</span></label>
                                                    <select name="account_type" id="account_type" class="form-control">
                                                        <option value="0">Select Type</option>
                                                        <option value="1">Saving</option>
                                                        <option value="2">Current</option>
                                                    </select>
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Broker's Name <span class="text-red">*</span></label><br />
                                                    <input type="text" name="broker_name" id="broker_name" value="" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Telephone <span class="text-red">*</span></label><br />
                                                    <input type="number" name="telephone" id="telephone" value="" class="form-control" />
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Contact Status <span class="text-red">*</span></label>
                                                    <select name="contact_status" id="contact_status" class="form-control">
                                                        <option value="0">Select Status</option>
                                                        <option value="1">Yes</option>
                                                        <option value="2">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                       </div>
                                    </div>
                                    <div class="panel-overlay">
                                        <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="selectwrap">
                                            <input type="hidden" name="id" id="id" value="" />
                        					<input type="submit" name="submit" value="Save"/>	
                                            <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                        </div>
                                   </div>
                                </div>
                            </div>
                        </form>
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
                            <input type="text" name="search" id="search" value=""/>
                            <button  name="search" id="search"><i class="fa fa-search"></i> Search</button>
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
                                    <th class="text-center">ACTION</th>
                                </tr>
            	            </thead>
            	            <tbody>
            	                   <tr>
                                        <td class="text-center">1</td>
                                        <td>Marcus Desoza</td>
                                        <td>main.png</td>
                                        <td>saving</td>
                                        <td>Broker1</td>
                                        <td>125478963</td>
                                        <td>Yes</td>
                                        <td class="text-center">
                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                            <a href="<?php echo CURRENT_PAGE; ?>?action=delete&id=" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                        </div>
            		</div>
            	</div>
                <?php } ?>
                </div>
                <div class="tab-pane" id="tab_b">
                    <ul class="nav nav-tabs ">
                      <li class="active"><a href="#tab_aa" data-toggle="tab">Primary</a></li>
                      <li><a href="#tab_bb" data-toggle="tab">Employment</a></li>
                      <li><a href="#tab_cc" data-toggle="tab">Objectives</a></li>
                      <li><a href="#tab_dd" data-toggle="tab">Account No</a></li>
                      <li><a href="#tab_ee" data-toggle="tab">Documents</a></li>
                      <li><a href="#tab_ff" data-toggle="tab">Suitability</a></li>
                    </ul>
                    <!-- Tab 1 is started -->
                    <form method="post">
                    <div class="tab-content">
                        
                    
                        <div class="tab-pane active" id="tab_aa">
                          
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                                       <div class="panel-heading">
                                            <h4 class="panel-title" style="font-size: 20px;"><input type="checkbox" class="checkbox" name="do_not_contact" id="do_not_contact" style="display: inline !important;"/> Do Not Contact</h4>
                                       </div>
                                    <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>First Name <span class="text-red">*</span></label><br />
                                                <input type="text" name="fname" id="fname" class="form-control" />
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last Name <span class="text-red">*</span></label><br />
                                                <input type="text" name="lname" id="lname" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>MI <span class="text-red">*</span></label><br />
                                                <input type="text" name="mi" id="mi" class="form-control" />
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Client's File <span class="text-red">*</span></label><br />
                                                <input type="file" name="client_file_maintain" id="client_file_maintain" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Clearing Acct <span class="text-red">*</span></label><br />
                                                <input type="text" name="clearing_acct" id="clearing_acct" class="form-control" />
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Client SSN <span class="text-red">*</span></label><br />
                                                <input type="file" name="client_ssn" id="client_ssn" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Account Type  <span class="text-red">*</span></label>
                                                <select name="account_type" id="account_type" class="form-control">
                                                    <option value="0">Select Type</option>
                                                    <option value="1">Saving</option>
                                                    <option value="2">Current</option>
                                                </select>
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Household / Link Code <span class="text-red">*</span></label><br />
                                                <input type="text" name="household" id="household" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Broker <span class="text-red">*</span></label>
                                                <select name="broker" id="broker" class="form-control">
                                                    <option value="0">Select Broker</option>
                                                    <option value="1">broker1</option>
                                                    <option value="2">broker2</option>
                                                </select>
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Split Broker <span class="text-red">*</span></label>
                                                <select name="split_broker" id="split_broker" class="form-control">
                                                    <option value="0">Select Split Broker</option>
                                                    <option value="1">split broker1</option>
                                                    <option value="2">split broker2</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><span class="text-red"></span></label>
                                                <input type="number" name="spinner" id="spinner" class="form-control"  />
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Address1 <span class="text-red">*</span></label>
                                                <input type="text" name="address1" id="address1" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Address2 <span class="text-red"></span></label>
                                                <input type="text" name="address1" id="address1" class="form-control" />
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City <span class="text-red">*</span></label>
                                                <input type="text" name="city" id="city" class="form-control" />
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>State <span class="text-red">*</span></label>
                                                <select name="state" id="state" class="form-control">
                                                    <option value="0">Select State</option>
                                                    <option value="1">Alaska</option>
                                                    <option value="2">Kibondo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Zip Code <span class="text-red">*</span></label>
                                                <input type="text" name="zip_code" id="zip_code" class="form-control" />
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Telephone <span class="text-red">*</span></label>
                                                <input type="text" name="telephone_maintain" id="telephone_maintain" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Citizenship <span class="text-red">*</span></label>
                                                <input type="text" name="citizenship" id="citizenship" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Long Name <span class="text-red">*</span></label>
                                                <textarea name="long_name" id="long_name" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date Established <span class="text-red">*</span></label>
                                                <div id="demo-dp-range">
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" name="date_established" id="date_established" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Birth Date <span class="text-red">*</span></label>
                                                <div id="demo-dp-range">
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" name="birth_date" id="birth_date" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Age <span class="text-red">*</span></label>
                                                <textarea name="age" id="age" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Open Date <span class="text-red">*</span></label>
                                                <div id="demo-dp-range">
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" name="open_date" id="open_date" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Active <span class="text-red">*</span></label>
                                                <input type="checkbox" name="active" id="active" class="checkbox" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>NAF Date <span class="text-red">*</span></label>
                                                <div id="demo-dp-range">
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" name="naf_date" id="naf_date" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>OFAC Check <span class="text-red">*</span></label>
                                                <input type="text" name="ofak_check" id="ofak_check" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>FinCEN Check <span class="text-red">*</span></label>
                                                <input type="text" name="fincen_check" id="fincen_check" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last Contacted <span class="text-red">*</span></label>
                                                <input type="text" name="last_contacted" id="last_contacted" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="panel-overlay">
                                        <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                    </div>
                                    <!--div class="panel-footer">
                                        <div class="selectwrap">
                                            <input type="hidden" name="id" id="id" value="" />
                        					<input type="submit" name="submit" value="Save"/>	
                                            <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                        </div>
                                   </div-->
                                </div>
                            </div>
                        
                        </div>
                        <!-- Tab 1 is ends -->
                        <div class="tab-pane" id="tab_bb">
                            
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                                       <div class="panel-heading">
                                            <h4 class="panel-title" style="font-size: 20px;">Client Marcus[filename]</h4>
                                       </div>
                                    <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4><b>Employment Information</b></h4><br />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Occupation <span class="text-red">*</span></label><br />
                                                <input type="text" name="occupation" id="occupation" class="form-control" />
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Employer <span class="text-red">*</span></label><br />
                                                <input type="text" name="employer" id="employer" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Address <span class="text-red">*</span></label><br />
                                                <input type="text" name="address_employement" id="address_employement" class="form-control" />
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Position <span class="text-red">*</span></label><br />
                                                <input type="text" name="position" id="position" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Telephone  <span class="text-red">*</span></label>
                                                <input type="number" name="telephone_employement" id="telephone_employement" class="form-control" />
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Securities-Related Firm <span class="text-red">*</span></label><br />
                                                <input type="checkbox" name="security_related_firm" id="security_related_firm" class="checkbox" />
                                            </div>
                                        </div>
                                    </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>FINRA Affiliation <span class="text-red">*</span></label>
                                                <input type="checkbox" name="finra_affiliation" id="finra_affiliation" class="checkbox" />
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
                                                <label>Spouse Name<span class="text-red">*</span></label>
                                                <input type="text" name="spouse_name" id="spouse_name" class="form-control"  />
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Dependents <span class="text-red">*</span></label>
                                                <input type="text" name="dependents" id="Dependents" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Spouse SSN <span class="text-red"></span></label>
                                                <input type="text" name="spouse_ssn" id="spouse_ssn" class="form-control" />
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Salutation <span class="text-red">*</span></label>
                                                <input type="text" name="salutation" id="salutation" class="form-control" />
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
                                                <input type="radio" onclick="close_other()" name="options" id="options" class="radio" style="display: inline;" />&nbsp;<label>Driver License</label>&nbsp;&nbsp;
                                                <input type="radio" onclick="close_other()" name="options" id="options" class="radio" style="display: inline;" />&nbsp;<label>Passport</label>&nbsp;&nbsp;
                                                <input type="radio" onclick="open_other()" name="options" id="options" class="radio" style="display: inline;" />&nbsp;<label>Other</label>&nbsp;
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="display: none;" id="other_div">
                                            <div class="form-group">
                                                <label>Other <span class="text-red">*</span></label>
                                                <input type="text" name="other" id="other" class="form-control" />
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Number <span class="text-red">*</span></label>
                                                <input type="number" name="number" id="number" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Expiration <span class="text-red">*</span></label>
                                                <input type="text" name="expiration" id="expiration" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>State <span class="text-red">*</span></label>
                                                <input type="text" name="state" id="state" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date Verified <span class="text-red">*</span></label>
                                                <div id="demo-dp-range">
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" name="date_verified" id="date_verified" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    </div>
                                    <div class="panel-overlay">
                                        <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                    </div>
                                    <!--div class="panel-footer">
                                        <div class="selectwrap">
                                            <input type="hidden" name="id" id="id" value="" />
                        					<input type="submit" name="submit" value="Save"/>	
                                            <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                        </div>
                                   </div-->
                                </div>
                            </div>
                       
                        </div>
                        <div class="tab-pane" id="tab_cc">
                           Objectives
                        </div>
                        <div class="tab-pane" id="tab_dd">
                           
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                                       <div class="panel-heading">
                                            <h4 class="panel-title" style="font-size: 20px;">Client Marcus[filename]</h4>
                                       </div>
                                    <div class="panel-body">
                                    <div class="row" id="account_no_row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Account No <span class="text-red">*</span></label><br />
                                                <input type="text" name="account_no" id="account_no" class="form-control" />
                                            </div>
                                        </div>
                                    	<div class="col-md-4">
                                            <div class="form-group">
                                                <label>Company <span class="text-red">*</span></label><br />
                                                <input type="text" name="company" id="company" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label></label><br />
                                                <button type="button" onclick="addMoreDocs();" class="btn btn-purple btn-icon btn-circle"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="panel-overlay">
                                        <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                    </div>
                                    <!--div class="panel-footer">
                                        <div class="selectwrap">
                                            <input type="hidden" name="id" id="id" value="" />
                        					<input type="submit" name="submit" value="Save"/>	
                                            <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                        </div>
                                   </div-->
                                </div>
                            </div>
                       
                        </div>
                        <div class="tab-pane" id="tab_ee">
                            
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                                       <div class="panel-heading">
                                            <h4 class="panel-title" style="font-size: 20px;">Client Marcus[filename]</h4>
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
                                        <div class="selectwrap">
                                            <input type="hidden" name="id" id="id" value="" />
                        					<input type="submit" name="submit" value="Save"/>	
                                            <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                        </div>
                                   </div-->
                                </div>
                            </div>
                      
                        </div>
                        <div class="tab-pane" id="tab_ff">
                            
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                                       <div class="panel-heading">
                                            <h4 class="panel-title" style="font-size: 20px;">Client Marcus[filename]</h4>
                                       </div>
                                    <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Income <span class="text-red">*</span></label>
                                                <select name="income" id="income" class="form-control">
                                                    <option value="0">Select Income</option>
                                                </select>
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Goal Horizon <span class="text-red">*</span></label>
                                                <select name="goal_horizone" id="goal_horizone" class="form-control">
                                                    <option value="0">Select Goal Horizon</option>
                                                </select>
                                            </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Net Worth <span class="text-red">*</span></label>
                                                <select name="net_worth" id="net_worth" class="form-control">
                                                    <option value="0">Select Net Worth</option>
                                                </select>
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Risk Tolerance <span class="text-red">*</span></label>
                                                <select name="risk_tolerance" id="risk_tolerance" class="form-control">
                                                    <option value="0">Select Risk Tolerance</option>
                                                </select>
                                            </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Annual Expenses <span class="text-red">*</span></label>
                                                <select name="annual_expenses" id="annual_expenses" class="form-control">
                                                    <option value="0">Select Annual Expenses</option>
                                                </select>
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Liquidity Needs <span class="text-red">*</span></label>
                                                <select name="liquidity_needs" id="liquidity_needs" class="form-control">
                                                    <option value="0">Select Liquidity Needs</option>
                                                </select>
                                            </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Liquid Net Worth <span class="text-red">*</span></label>
                                                <select name="liquid_net_worth" id="liquid_net_worth" class="form-control">
                                                    <option value="0">Select Liquid Net Worth</option>
                                                </select>
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Special Expenses <span class="text-red">*</span></label>
                                                <select name="special_expenses" id="special_expenses" class="form-control">
                                                    <option value="0">Select Special Expenses</option>
                                                </select>
                                            </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>% of Portfolio <span class="text-red">*</span></label>
                                                <select name="per_of_portfolio" id="per_of_portfolio" class="form-control">
                                                    <option value="0">Select % of Portfolio</option>
                                                </select>
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Timeframe for Special Exp <span class="text-red">*</span></label>
                                                <select name="timeframe_for_special_exp" id="timeframe_for_special_exp" class="form-control">
                                                    <option value="0">Select Timeframe for Special Exp</option>
                                                </select>
                                            </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Account Use <span class="text-red">*</span></label>
                                                <select name="account_use" id="account_use" class="form-control">
                                                    <option value="0">Select Account Use</option>
                                                </select>
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Signed By <span class="text-red">*</span></label>
                                                <input type="text" name="signed_by" id="signed_by" class="form-control"/>
                                            </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Sign Date <span class="text-red">*</span></label>
                                                <div id="demo-dp-range">
					                                <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" name="sign_date" id="sign_date" value="" class="form-control" />
					                                </div>
				                                </div>
                                            </div>
                                        </div>
                                    	<div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tax Bracket <span class="text-red">*</span></label>
                                                <input type="number" name="tax_bracket" id="tax_bracket" class="form-control"/>
                                            </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                          <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tax ID <span class="text-red">*</span></label>
                                                <input type="text" name="tax_id" id="tax_id" class="form-control"/>
                                            </div>
                                         </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                <label></label><br />
                                                <a href="#client_notes" data-toggle="modal"><input type="button" name="notes" value="Notes" /></a>
                                                <a href="#transactions"><input type="button" name="transactions" value="Transactions" /></a>
                                                <a href="#joint_account"><input type="button" name="joint_account" value="Joint Account" /></a>
                                                <a href="#attach"><input type="button" name="attach" value="Attach" /></a>
                                            </div>
                                         </div>
                                     </div>
                                    </div>
                                    <div class="panel-overlay">
                                        <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="selectwrap">
                                            <input type="hidden" name="id" id="id" value="" />
                        					<input type="submit" name="submit" value="Save"/>	
                                            <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                        </div>
                                   </div>
                                </div>
                            </div>
                        
                        </div>
                       
                    </div>
                     </form>
                </div>
        </div>
        <!-- Lightbox strart -->							
			<!-- Modal -->
			<div id="client_notes" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header" style="margin-bottom: 0px !important;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h4 class="modal-title">Client's Notes</h4>
				</div>
				<div class="modal-body">
                <div class="inputpopup">
                    <div class="btn-group dropdown" style="float: right;">
                        <button type="button" class="dropdown-toggle btn btn-default" style="margin-right: 5px !important;" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
    					<ul class="dropdown-menu dropdown-menu-right" style="">
    						<li><a href="#add_client_notes" data-toggle="modal" ><i class="fa fa-plus"></i> Add New</a></li>
    					</ul>
    				</div>
                    <!--<a href="<?php echo CURRENT_PAGE; ?>?action=add_new" style="float: right; margin-right: 5px;" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add New</a>-->
                </div>
                <form method="post">
                <div class="inputpopup">
					<div class="table-responsive" id="table-scroll" style="margin: 0px 5px 0px 5px !important;">
                        <table class="table table-bordered table-stripped table-hover">
                            <thead>
                                <th>#NO</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Notes</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td></td>
                                    <td>user01</td>
                                    <td></td>
                                    <td class="text-center">
                                        <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="<?php echo CURRENT_PAGE; ?>?action=delete&id=" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td></td>
                                    <td>user02</td>
                                    <td></td>
                                    <td class="text-center">
                                        <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="<?php echo CURRENT_PAGE; ?>?action=delete&id=" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td></td>
                                    <td>user03</td>
                                    <td></td>
                                    <td class="text-center">
                                        <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="<?php echo CURRENT_PAGE; ?>?action=delete&id=" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td></td>
                                    <td>user04</td>
                                    <td></td>
                                    <td class="text-center">
                                        <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="<?php echo CURRENT_PAGE; ?>?action=delete&id=" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td></td>
                                    <td>user05</td>
                                    <td></td>
                                    <td class="text-center">
                                        <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
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
			<!-- Modal -->
			<div id="add_client_notes" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header" style="margin-bottom: 0px !important;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h4 class="modal-title">Client's Notes</h4>
				</div>
				<div class="modal-body">
                <form method="post" id="add_new_note" name="add_new_note" onsubmit="return formsubmit_addnotes();">
                    <div class="inputpopup">
    					<label>Date:<span>*</span></label>
    					<label class="form-control" style="text-align: left !important;"><?php echo date('m/d/Y');?></label>
    				</div>
                    <div class="inputpopup">
    					<label>User:<span>*</span></label>
                        <input type="hidden" value="<?php echo $_SESSION['user_id'];?>" name="note_user" id="note_user"/>
                        <label class="form-control" style="text-align: left !important;"><?php echo $_SESSION['user_name'];?></label>
    				</div>
    				<div class="inputpopup">
    					<label>Notes:<span>*</span></label>
                        <textarea class="form-control" name="client_notes" id="client_notes"></textarea>
    				</div>
    				<div class="col-md-12">
                        <div id="msg">
                        </div>
                    </div>
                   	<div class="inputpopup">
    				<label class="labelblank">&nbsp;</label>
                    <input type="hidden" name="submit" value="Add Note" />
    					<input type="submit" value="Add Note" name="submit" />
    					<input type="reset" value="Cancel"/>
    				</div>
				</form>					
                
				</div><!-- End of Modal body -->
				</div><!-- End of Modal content -->
				</div><!-- End of Modal dialog -->
		</div><!-- End of Modal -->
    </div>
</div>
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
<style>
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
}
#table-scroll {
  height:200px;
  overflow:auto;  
  margin-top:20px;
}
</style>