<div class="container">
    <h1 class="<?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'topfixedtitle';}?>">User Profiles</h1>
	<div class="col-lg-12 well <?php if($action=='add_new'||($action=='edit' && $id>0)){ echo 'fixedwell';}?>">
	<div class="row">
    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
    <!-- Add table data and some process -->
    <?php
    if($action=='add_new'||($action=='edit' && $id>0)){
    ?>
    <form name="frm" method="POST" enctype="multipart/form-data">
        <div class="panel-heading">
            <div class="panel-control" style="float: right;">
    			<div class="btn-group dropdown">
    				<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
    				<ul class="dropdown-menu dropdown-menu-right" style="">
    					<li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-eye"></i> View List</a></li>
    				</ul>
    			</div>
    		</div>
            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action=='add_new'?'Add':'Edit'; ?> User</h3>
    	</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6 form-group">
					<label>First Name <span class="text-red">*</span></label>
					<input type="text" name="fname" class="form-control" value="<?php echo $fname; ?>" />
				</div>
				<div class="col-sm-6 form-group">
					<label>Last Name <span class="text-red">*</span></label>
					<input type="text" name="lname" class="form-control" value="<?php echo $lname; ?>" />
				</div>
			</div>	
            <div class="row">
                <div class="col-sm-6 form-group">
					<label>User Name <span class="text-red">*</span></label>
					<input type="text"  maxlength="10" name="uname" class="form-control" value="<?php echo $uname; ?>" />
				</div>
				<div class="col-sm-6 form-group">
					<label>Email Address <span class="text-red">*</span></label>
                    <input type="text" name="email" class="form-control" value="<?php echo $email; ?>" />
				</div>
            </div>
            <div class="row">
				<div class="col-sm-6 form-group">
					<label>Password <span class="text-red">*</span></label>
					<input type="text" maxlength="10" name="password" class="form-control" value="<?php echo $instance->decryptor($password); ?>" />
				</div>
                <div class="col-sm-6 form-group">
					<label>Confirm Password <span class="text-red">*</span></label>
					<input type="text" name="confirm_password" class="form-control" value="<?php echo $instance->decryptor($password); ?>" />
				</div>	
			</div>
            <div class="row">
				<div class="col-sm-6 form-group">
					<label>Upload Image </label>
				    <input type="file" class="form-control" name="file_image" onchange="readURL(this);" id="file_image" value="<?php echo $user_image;?>" />
                </div>
                <div class="col-sm-6 form-group">
					<label><img src="<?php echo SITE_URL."upload/"."thumb_".$user_image;?>" id="user_img" class="img img-thumbnail" height="100" width="100"/></label>
                </div>
            </div>
            <div class="row">
				<div class="col-sm-6 form-group">
					<label><h3>User Menu Rights:</h3></label><br /><br />
                    <div class="row"> 
                        <div class="col-sm-6 form-group">
                            <label>Check All</label>
                        </div>
                        <div class="col-sm-6 form-group">
                            <input type="checkbox" class="checkbox" name="check all" onclick="chk_all_class(this.checked)"/>
                        </div>
                    </div>
                    <?php 
                        foreach($menu11 as $menukey=>$menudata)
                        { 
                    ?>      <div class="row"> 
                                <div class="col-sm-6 form-group">
                                    <label><?php echo $menudata['link_text']; ?></label><br />
                                </div>
                                <div class="col-sm-6 form-group">
                                    <input type="checkbox" class="checkbox" name="check_parent[]<?php echo $menudata['link_id']; ?>" onclick="check_main(this,<?php echo $menudata['link_id']; ?>)" value="<?php echo $menudata['link_id']; ?>" checked="true"/>
                                </div>
                            </div>
					       <?php
                                foreach($menudata['submenu'] as $subkey=>$subdata)
                                {?>    
                                   <div class="row"> 
                                        <div class="col-sm-6 form-group">
                                            <?php echo $subdata['link_text'];if($subdata['submenu']!= array()){echo ' <i class="fa fa-chevron-down"></i>';}?>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <input type="checkbox" class="checkbox"  name="check_sub[<?php echo $menudata['link_id']; ?>][]<?php echo $subdata['link_id']; ?>" value="<?php echo $subdata['link_id']; ?>" <?php if(in_array($subdata['link_id'],$menu_rights)){echo "checked='true'";} ?>/>
                                        </div>
                                   </div>
                                    <?php
                                    foreach($subdata['submenu'] as $sub_k=>$sub_v)
                                    {?>    
                                       <div class="row"> 
                                            <div class="col-sm-6 form-group">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <?php echo $sub_v['link_text'];?>
                                            </div>
                                            <div class="col-sm-6 form-group">
                                                <input type="checkbox" class="checkbox"  name="check_sub[<?php echo $subdata['link_id']; ?>][]<?php echo $sub_v['link_id']; ?>" value="<?php echo $sub_v['link_id']; ?>" <?php if(in_array($sub_v['link_id'],$menu_rights)){echo "checked='true'";} ?>/>
                                            </div>
                                       </div>
                                    <?php }
                                    ?>
                                <?php }
                                ?>
                    <?php }?>
				</div>	
			</div>
            				
		</div>
        <div class="panel-footer fixedbtmenu">
        <div class="selectwrap">
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
			<a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
            <input type="submit" name="submit" onclick="waitingDialog.show();" value="Save" style="float: right;"/>	
        </div>
        </div>
	</form> 
        <?php }else{?>
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
            <div class="table-responsive" id="register_data">
    			<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    	            <thead>
    	                <tr>
                            <th>FIRST NAME</th>
                            <th>LAST NAME</th>
                            <th>USER NAME</th>
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
                                    <td><?php echo $val['first_name']; ?></td>
                                    <td><?php echo $val['last_name']; ?></td>
                                    <td><?php echo $val['user_name']; ?></td>
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
                                        <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
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
	   </div>
	</div>
</div>
<style>
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
}
</style>
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
<script type="text/javascript">
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#user_img')
                .attr('src', e.target.result)
                .width(150)
                .height(200);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
function chk_all_class(chk)
{
    with(document.frm)
	{
		var d;
		d=document.getElementsByTagName("input");
		for(i=0;i<d.length;i++)
		{
            var elm_name=d[i].name;
            if(d[i].type=="checkbox")
			{
                if(chk==true)
				{
					d[i].checked=true;
                }
				else
				{
					d[i].checked=false;				  
                }
			}
		}
	}
}
/*function checkLength(el) {//alert('hii');
  if (el.length != 4) {
    alert("Length should be greater than 4 characters")
  }
}*/
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