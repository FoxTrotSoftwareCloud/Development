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
<style>

#sticky {
    padding: 0.5ex;
    width: 600px;
    background-color: #333;
    color: #fff;
    font-size: 2em;
    border-radius: 0.5ex;
}

#sticky.stick {
    margin-top: 0 !important;
    position: fixed;
    top: 0;
    z-index: 10000;
    border-radius: 0 0 0.5em 0.5em;
}
body {
    margin: 0em !important;
}

</style>
<script>
function sticky_relocate() {
    var window_top = $(window).scrollTop();
    var div_top = $('#sticky-anchor').offset().top;
    if (window_top > div_top) {
        $('#sticky').addClass('stick');
        $('#sticky-anchor').height($('#sticky').outerHeight());
    } else {
        $('#sticky').removeClass('stick');
        $('#sticky-anchor').height(0);
    }
}

$(function() {
    //$(window).scroll(sticky_relocate);
    //sticky_relocate();
});

var dir = 1;
var MIN_TOP = 200;
var MAX_TOP = 350;

function autoscroll() {
    var window_top = $(window).scrollTop() + dir;
    if (window_top >= MAX_TOP) {
        window_top = MAX_TOP;
        dir = -1;
    } else if (window_top <= MIN_TOP) {
        window_top = MIN_TOP;
        dir = 1;
    }
    $(window).scrollTop(window_top);
    window.setTimeout(autoscroll, 100);
}

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
<h1 class="<?php /*if($action=='add_sponsor'||($action=='edit_sponsor' && $_GET['sponsor_id']>0)){ echo 'topfixedtitle';}*/?>">Sponsor Maintenance</h1>
<div class="col-lg-12 well <?php /*if($action=='add_sponsor'||($action=='edit_sponsor' && $_GET['sponsor_id']>0)){ echo 'fixedwell';}*/?>">
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
<?php
    if((isset($_GET['action']) && $_GET['action']=='add_sponsor') || (isset($_GET['action']) && ($_GET['action']=='edit_sponsor' && $sponsor_id>0))){
        ?>
        <form name="frm2" method="POST" >
            <!--<div class="row">
                <div class="col-md-12">
                    <div class="form-group"><br /><div class="selectwrap">
                       <?php if($_GET['action']=='edit_sponsor' && $_GET['sponsor_id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $sponsor_id;?>&send=previous" class="previous next_previous_a" style="float: left;"><input type="button" name="previous" value="&laquo; Previous" /></a><?php } ?>
                        <?php if($action=='edit_sponsor' && $sponsor_id>0){?>
                        <a href="#view_changes" data-toggle="modal"><input type="button" name="view_changes" value="View Changes" style="margin-left: 5%;"/></a>
                        <?php } ?>
                        <a href="#sponsor_notes" data-toggle="modal"><input type="button" onclick="get_sponsor_notes();" name="notes" value="Notes" /></a>
                        <a href="#sponsor_attach" data-toggle="modal"><input type="button"  onclick="get_sponsor_attach();" name="attach" value="Attachments" /></a>
                        <a href="#sponsor_transactions" data-toggle="modal"><input type="button"  name="transactions" value="Transactions" /></a>
                        <input type="submit" name="sponsor" onclick="waitingDialog.show();" value="Save"/>
                        <a href="<?php echo CURRENT_PAGE.'?action=view_sponsor';?>"><input type="button" name="cancel" value="Cancel" /></a>
                        <?php if($_GET['action']=='edit_sponsor' && $_GET['sponsor_id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $sponsor_id;?>&send=next" class="next next_previous_a" style="float: right;"><input type="button" name="next" value="Next &raquo;" /></a><?php } ?>
                    </div>
                 </div>
                 </div>
             </div>
            <br />-->
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-control" style="float: right;">
    				<div class="btn-group dropdown">
    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
    					<ul class="dropdown-menu dropdown-menu-right">
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
                        <label>Sponsor Name <span class="text-red">*</span></label><br />
                        <input type="text" maxlength="50" class="form-control" name="sponsor_name" value="<?php echo $sponsor_name;?>"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address 1 </label><br />
                        <input type="text" maxlength="50" class="form-control" name="saddress1"  value="<?php echo $saddress1;?>" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address 2 </label><br />
                        <input type="text" maxlength="50" class="form-control" name="saddress2" value="<?php echo $saddress2;?>"  />
                    </div>
                </div>
            </div><div id="sticky-anchor"></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>City </label><br />
                        <input type="text" maxlength="25" class="form-control" name="scity" value="<?php echo $scity;?>"  />
                    </div>
                </div>
                <div class="col-md-4">
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
                <div class="col-md-4">
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
            <?php if(isset($_GET['file_id']) && $_GET['file_id'] != ''){
                  $file_id = isset($_GET['file_id'])?$_GET['file_id']:'';
                  $temp_data_id = isset($_GET['exception_data_id'])?$_GET['exception_data_id']:'';
                  $import_instance = new import();
                  $idc_data_details = $import_instance->get_idc_record_details($file_id,$temp_data_id);
                  if($idc_data_details != array()){
                ?>
             <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>DST System ID </label><br />
                        <input type="text" maxlength="15" class="form-control" name="sdst_system_id_disp" value="<?php echo $idc_data_details['system_id'];?>" disabled="true" />
                        <input type="hidden" maxlength="15" class="form-control" name="sdst_system_id" value="<?php echo $idc_data_details['system_id'];?>"  />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>DST Mgmt Code </label><br />
                        <input type="text" maxlength="15" class="form-control" name="sdst_mgmt_code_disp" value="<?php echo $idc_data_details['management_code'];?>" disabled="true"  />
                        <input type="hidden" maxlength="15" class="form-control" name="sdst_mgmt_code" value="<?php echo $idc_data_details['management_code'];?>"  />
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php }else{ ?>
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
           <?php } ?>
           <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Exclude from DST Importing  <input type="checkbox" class="checkbox"  name="sdst_import" id="cpa" value="1" style="display: inline;" <?php if($sdst_import>0){echo "checked='checked'"; }?> />
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Exclude from DAZL Importing  <input type="checkbox" class="checkbox"  name="sdazl_import" id="" value="1" style="display: inline;" <?php if($sdazl_import>0){echo "checked='checked'"; }?> />
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>DAZL Code </label><br />
                        <input type="text" maxlength="15" class="form-control" name="sdazl_code" value="<?php echo $sdazl_code;?>"  />
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
           </div>
           <div class="panel-footer fixedbtmenu">
            <div class="selectwrap">
                <?php if(isset($_GET['file_id']) && $_GET['file_id'] != ''){?>
                <input type="hidden" name="for_import" id="for_import" class="form-control" value="true" />
                <input type="hidden" name="file_id" id="file_id" class="form-control" value="<?php echo $_GET['file_id']; ?>" />
                <input type="hidden" name="temp_data_id" id="temp_data_id" class="form-control" value="<?php echo $_GET['exception_data_id']; ?>" />
                <?php }?>
                <input type="hidden" name="sponsor_id" id="sponsor_id" value="<?php echo $sponsor_id; ?>" />
                <?php if($_GET['action']=='edit_sponsor' && $_GET['sponsor_id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $sponsor_id;?>&send=previous" class="previous next_previous_a" style="float: left;"><input type="button" name="previous" value="&laquo; Previous" /></a><?php } ?>
                <?php if($_GET['action']=='edit_sponsor' && $_GET['sponsor_id']>0){?><a href="<?php echo CURRENT_PAGE; ?>?id=<?php echo $sponsor_id;?>&send=next" class="next next_previous_a"><input type="button" name="next" value="Next &raquo;" /></a><?php } ?>

                <?php if($action=='edit_sponsor' && $sponsor_id>0){?>
                <a href="#view_changes" data-toggle="modal"><input type="button" name="view_changes" value="View Changes" style="margin-left: 10% !important;"/></a>
                <?php } ?>
                <a href="#sponsor_notes" data-toggle="modal"><input type="button" onclick="get_sponsor_notes();" name="notes" value="Notes" /></a>
                <a href="#sponsor_transactions" data-toggle="modal"><input type="button"  name="transactions" value="Transactions" /></a>
                <a href="#sponsor_attach" data-toggle="modal"><input type="button"  onclick="get_sponsor_attach();" name="attach" value="Attachments" style="margin-right: 10% !important;"/></a>
                <a href="<?php echo CURRENT_PAGE.'?action=view_sponsor';?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
                <input type="submit" name="sponsor" onclick="waitingDialog.show();" value="Save" style="float: right;"/>
            </div>
           </div>
      </div>
    </form>
        <?php
            }if((isset($_GET['action']) && $_GET['action']=='view_sponsor') || $action=='view_sponsor'){?>
        <div class="panel">
    		<!--<div class="panel-heading">
                <div class="panel-control">
                    <div class="btn-group dropdown" style="float: right;">
                        <button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
    					<ul class="dropdown-menu dropdown-menu-right" style="">
    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_sponsor"><i class="fa fa-plus"></i> Add New</a></li>
    					</ul>
    				</div>
    			</div>
            </div><br />-->
    		<div class="table-responsive panel-body">
            <!--<div class="panel-control" style="float: right;">
             <form method="post">
                <input type="text" name="search_text_sponsor" id="search_text_sponsor" value="<?php echo $search_text_sponsor;?>"/>
                <button type="submit" name="search_sponsor" id="submit" value="Search"><i class="fa fa-search"></i> Search</button>
            </form>
            </div><br /><br />-->
                <div class="">
    			<table id="data-table" class="table table-striped1 table-bordered">
    	            <thead>
    	                <tr>
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
                $lable_array = array('name' => 'Sponsor Name','address1' => 'Address 1','address2' => 'Address 2
','city' => 'City','state' => 'State','zip_code' => 'Zip Code','email' => 'E-Mail','website' => 'Web Site','general_contact' => 'General Contact','general_phone' => 'General Phone','operations_contact' => 'Operations Contact','operations_phone' => 'Operations Phone','dst_system_id' => 'DST System ID','dst_mgmt_code' => 'DST Mgmt Code','dst_importing' => 'Exclude from DST Importing','dazl_importing' => 'Exclude from DAZL Importing','dazl_code' => 'DAZL Code','dtcc_nscc_id' => 'DTCC/NSCC ID','clearing_firm_id' => 'Clearing Firm ID');
                foreach($sponsor_data as $key=>$val){

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
                        if($feild_name == 'Exclude from DST Importing' && $val['old_value'] == 0){?>
                        <td><?php echo 'UnChecked';?></td>
                        <td><?php echo 'Checked';?></td>
                        <?php }
                        else if($feild_name == 'Exclude from DST Importing' && $val['old_value'] == 1){?>
                        <td><?php echo 'Checked';?></td>
                        <td><?php echo 'UnChecked';?></td>
                        <?php }
                        else if($feild_name == 'Exclude from DAZL Importing' && $val['old_value'] == 0){?>
                        <td><?php echo 'UnChecked';?></td>
                        <td><?php echo 'Checked';?></td>
                        <?php }
                        else if($feild_name == 'Exclude from DAZL Importing' && $val['old_value'] == 1){?>
                        <td><?php echo 'Checked';?></td>
                        <td><?php echo 'UnChecked';?></td>
                        <?php }
                        else if($feild_name == 'State'){
                        $state = $instance->get_state_name($val['old_value']);?>
                        <td><?php echo $state['state_name'];?></td>
                        <?php $state = $instance->get_state_name($val['new_value']);?>
                        <td><?php echo $state['state_name'];?></td>
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
	<!-- Modal for add client notes -->
	<div id="sponsor_notes" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header" style="margin-bottom: 0px !important;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h4 class="modal-title">Sponsor's Notes</h4>
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
	<div id="sponsor_attach" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header" style="margin-bottom: 0px !important;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h4 class="modal-title">Sponsor's Attach</h4>
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
<div id="sponsor_transactions" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
	<div class="modal-content">
	<div class="modal-header" style="margin-bottom: 0px !important;">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
		<h4 class="modal-title"><?php echo $sponsor_name ?> Transactions (past year)</h4>
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
                    <th>Client</th>
                    <th>Broker</th>
                    <th>Trade Amount</th>
                    <th>Commission Received</th>
                </thead>

                <?php if (count($sponsor_trades)){ 
                    $instance_payroll = new payroll(); ?>
                    <tbody>
                        <?php foreach ($sponsor_trades AS $trade){ ?>
                            <tr>
                                <td><?php echo $trade['id']; ?></td>
                                <td><?php echo date('m/d/Y', strtotime($trade['trade_date'])); ?></td>
                                <td><?php echo $trade['product_name']; ?></td>
                                <td><?php echo $trade['client_name']; ?></td>
                                <td><?php echo $trade['broker_name']; ?></td>
                                <td><?php echo $instance_payroll->payroll_accounting_format($trade['invest_amount'],2); ?></td>
                                <td><?php echo $instance_payroll->payroll_accounting_format($trade['commission_received'],2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                <?php } else { ?>
                        <tr><td>* No trades found *</td></tr>
                <?php } ?>
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
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 2 ] },
                        { "bSearchable": false, "aTargets": [ 2 ] }]
        });

        $("div.toolbar").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_sponsor"><i class="fa fa-plus"></i> Add New</a></li>'+
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
function get_sponsor_attach(){

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200)
            {
                document.getElementById("ajax_attach").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_sponsor_attach.php", true);
        xmlhttp.send();
}
function get_sponsor_notes(){

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200)
            {
                console.info(this.responseText);
                document.getElementById("ajax_notes").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "ajax_sponsor_notes.php", true);
        xmlhttp.send();
}
function openedit(note_id){

    var frm_element = document.getElementById("add_sponsor_notes_"+note_id);
    //var ele = frm_element.getElementById("client_note");
    name = frm_element.elements["sponsor_note"].removeAttribute("style");
    //$(name).css('pointer-events','');
    console.log(name);
}
</script>
<script>
//submit share form data
function notessubmit(note_id)
{
   $('#msg').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');

   var url = "manage_sponsor.php"; // the script where you handle the form input.
   //alert("#add_client_notes_"+note_id);
   $.ajax({
      type: "POST",
      url: url,
      data: $("#add_sponsor_notes_"+note_id).serialize(), // serializes the form's elements.
      success: function(data){
          if(data=='1'){

            get_sponsor_notes();
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
            url: 'manage_sponsor.php', // point to server-side PHP script

            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                  if(data=='1'){

                    get_sponsor_attach();
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
                   get_sponsor_notes();
                   $('#msg_notes').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Note deleted Successfully.</div>');
                  }
                  else{
                       $('#msg_notes').html('<div class="alert alert-danger">'+data+'</div>');
                  }
            }
        };
        xmlhttp.open("GET", "manage_sponsor.php?delete_action=delete_notes&note_id="+note_id, true);
        xmlhttp.send();
}
function delete_attach(attach_id){

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = this.responseText;
                if(data=='1'){
                   get_sponsor_attach();
                   $('#msg_attach').html('<div class="alert alert-success alert-dismissable" style="opacity: 500;"><a href="#" class="close" data-dismiss="alert" aria-label="close">x</a><strong>Success!</strong> Attach deleted Successfully.</div>');
                  }
                  else{
                       $('#msg_attach').html('<div class="alert alert-danger">'+data+'</div>');
                  }
            }
        };
        xmlhttp.open("GET", "manage_sponsor.php?delete_action=delete_attach&attach_id="+attach_id, true);
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