<div class="container">
    <h1>Rules Engine</h1>

    <div class="col-lg-12 well">
        <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <form method="post">
            <div class="table-responsive">
    			<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    	            <thead>
    	                <tr>
                            <th>Active</th>
                            <th>Rule</th>
                            <th>Action</th>
                            <th>Parameter1(Minimum)</th>
                            <th>Parameter2(Maximum)</th>
                        </tr>
    	            </thead>
    	            <tbody>
                    <?php
                    if(!empty($rulesDetailArray)){
                        $count = 0;
                        foreach($rulesDetailArray as $detailKey=>$detailRow){
                            foreach($rulesMasterArray as $masterKey=>$masterRow){
                                if($detailRow['rule']==$masterRow['id']){
                                ?>
            	                    <tr>
                                        <input type="hidden" name="data[<?php echo $masterKey; ?>][detail_id]" value="<?php echo $detailRow['id'] ?>"/>
                                        <td><input type="checkbox" class="checkbox" <?php if(isset($detailRow['in_force']) && $detailRow['in_force']==1){?>checked="true"<?php } ?> value="1" name="data[<?php echo $masterKey; ?>][in_force]"/></td>
                                        <td><?php echo $masterRow['rule'];?></td><input type="hidden" value="<?php echo $masterRow['id'];?>" name="data[<?php echo $masterKey; ?>][rule]"/>
                                        <td style="width: 20%;">
                                            <select class="form-control" id="select_action_<?php echo $masterKey;?>" onchange="open_other(<?php echo $masterKey;?>)" name="data[<?php echo $masterKey; ?>][action]" style="padding-right: 30px;">
                                                <option value="0">Select Action</option>
                                                <?php foreach($get_rules_action as $key1=>$val1){?>
                                                <option value="<?php echo $val1['id'];?>" <?php if(isset($detailRow['action']) && $detailRow['action']==$val1['id']){ ?>selected="true"<?php } ?>><?php echo $val1['action'];?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <?php if($masterRow['id'] == 3){?>
                                                <div id="other_div<?php echo $masterKey;?>" class="form-group">
                                                    <div class="input-group">
                                                    <input type="text" class="form-control decimal" onchange="handleChange(this);" value="<?php if(isset($detailRow['parameter_1']) && $detailRow['parameter_1']!=''){echo $detailRow['parameter_1'];}?>" name="data[<?php echo $masterKey; ?>][parameter_1]" maxlength="13" />
                                                    <span class="input-group-addon">%</span>
                                                    </div>
                                                </div>
                                            <?php } else if($masterRow['id'] == 16){?>
                                                <div id="other_div<?php echo $masterKey;?>" class="form-group" <?php if(isset($detailRow['action']) && $detailRow['action']=='5'){?>style="display: none;"<?php }?>>
                                                    <div class="input-group">
                                                    <input type="text" class="form-control" value="<?php if(isset($detailRow['parameter_1']) && $detailRow['parameter_1']!=''){echo $detailRow['parameter_1'];}?>" name="data[<?php echo $masterKey; ?>][parameter_1]" maxlength="4" />
                                                    <span class="input-group-addon">$</span>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div id="other_div<?php echo $masterKey;?>" class="form-group" <?php if(isset($detailRow['action']) && $detailRow['action']=='5'){?>style="display: none;"<?php }?>>
                                                    <input type="text" class="form-control" value="<?php if(isset($detailRow['parameter_1']) && $detailRow['parameter_1']!=''){echo $detailRow['parameter_1'];}?>" name="data[<?php echo $masterKey; ?>][parameter_1]" maxlength="13" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45' />
                                                </div>
                                            <?php } ?>
                                                <div id="other_div_1<?php echo $masterKey;?>" class="form-group" <?php if((isset($detailRow['action']) && $detailRow['action']!='5') || $masterRow['id']=='3'){?>style="display: none;"<?php } ?>>
                                                    <select class="form-control" name="data[<?php echo $masterKey; ?>][parameter1]">
                                                        <option value="0">Select Broker</option>
                                                        <?php foreach($get_broker as $key2=>$val2){?>
                                                        <option value="<?php echo $val2['id'];?>" <?php if(isset($detailRow['parameter1']) && $detailRow['parameter1']==$val2['id']){ ?>selected="true"<?php } ?>><?php echo $val2['first_name'].' '.$val2['last_name'];?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                        </td>
                                        <td>
                                            <?php if ($masterRow['id'] == 3){?>
                                                <div id="other_div_3<?php echo $masterKey;?>"  class="form-group" <?php if(isset($detailRow['action']) && $detailRow['action']=='5'){?>style="display: block;"<?php }else{ ?>style="display: none;"<?php } ?>>
                                                    <select class="form-control" name="data[<?php echo $masterKey; ?>][parameter2]">
                                                        <option value="0">Select Broker</option>
                                                        <?php foreach($get_broker as $key2=>$val2){?>
                                                            <option value="<?php echo $val2['id'];?>" <?php echo ($val2['id']==$detailRow['parameter2'] ? "selected" : "") ?>><?php echo $val2['last_name'].(empty($val2['first_name']) || empty($val2['last_name'])?"":", ").$val2['first_name'];?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } else if($masterRow['id'] != 16){?>
                                                <div id="other_div_2<?php echo $masterKey;?>" class="form-group" <?php if(isset($detailRow['action']) && $detailRow['action']=='5'){?>style="display: none;"<?php }?>>
                                                    <input type="text" class="form-control" value="<?php if(isset($detailRow['parameter_2']) && $detailRow['parameter_2']!=''){echo $detailRow['parameter_2'];}?>" name="data[<?php echo $masterKey; ?>][parameter_2]" maxlength="13" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45'/>
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                    <?php } } } } else {
                        $count = 0;
                        
                        foreach($rulesMasterArray as $masterKey=>$masterRow){ ?>
    	                   <tr>
                                <td><input type="checkbox" class="checkbox" value="1" name="data[<?php echo $masterKey; ?>][in_force]"/></td>
                                <td><?php echo $masterRow['rule'];?></td><input type="hidden" value="<?php echo $masterRow['id'];?>" name="data[<?php echo $masterKey; ?>][rule]"/>
                                <td>
                                    <select class="form-control" id="select_action_<?php echo $masterKey;?>" onchange="open_other(<?php echo $masterKey;?>)" name="data[<?php echo $masterKey; ?>][action]">
                                        <option value="0">Select Action</option>
                                        <?php foreach($get_rules_action as $key1=>$val1){?>
                                        <option value="<?php echo $val1['id'];?>" <?php /*if(isset($pro_category) && $pro_category==$masterRow['id']){ ?>selected="true"<?php } */?>><?php echo $val1['action'];?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <?php if($masterRow['id'] == 3){?>
                                    <div id="other_div<?php echo $masterKey;?>" class="form-group">
                                         <div class="input-group">
                                          <input type="text" class="form-control decimal" onchange="handleChange(this);" name="data[<?php echo $masterKey; ?>][parameter_1]" maxlength="13" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45' />
                                          <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                    <?php } else if($masterRow['id'] == 16){?>
                                    <div id="other_div<?php echo $masterKey;?>" class="form-group">
                                         <div class="input-group">
                                          <input type="text" class="form-control" name="data[<?php echo $masterKey; ?>][parameter_1]" maxlength="4" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45' />
                                          <span class="input-group-addon">$</span>
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                    <div id="other_div<?php echo $masterKey;?>" class="form-group" >
                                        <input type="text" class="form-control" name="data[<?php echo $masterKey; ?>][parameter_1]" maxlength="13" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45' />
                                    </div>
                                    <?php } ?>
                                    <div id="other_div_1<?php echo $masterKey;?>" class="form-group" style="display: none;">
                                        <select class="form-control" name="data[<?php echo $masterKey; ?>][parameter1]">
                                            <option value="0">Select Broker</option>
                                            <?php foreach($get_broker as $key2=>$val2){?>
                                            <option value="<?php echo $val2['id'];?>" <?php /*if(isset($pro_category) && $pro_category==$val2['id']){ ?>selected="true"<?php }*/ ?>><?php echo $val2['first_name'].' '.$val2['last_name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($masterRow['id'] == 3){?>
                                        <div id="other_div_3<?php echo $masterKey;?>"  class="form-group" style="display: none;">
                                            <select class="form-control" name="data[<?php echo $masterKey; ?>][parameter2]">
                                                <option value="0">Select Broker</option>
                                                <?php foreach($get_broker as $key2=>$val2){?>
                                                    <option value="<?php echo $val2['id'];?>"><?php echo strtoupper($val2['last_name']).(empty($val2['first_name']) || empty($val2['last_name'])?"":", ").strtoupper($val2['first_name']);?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    <?php } else if($masterRow['id'] != 16){?>
                                        <div id="other_div_2<?php echo $masterKey;?>" class="form-group" >
                                            <input type="text" class="form-control" name="data[<?php echo $masterKey; ?>][parameter_2]" maxlength="13" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45'/>
                                        </div>
                                    <?php } ?>
                                </td>

                            </tr>

                            <?php } }?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer fixedbtmenu">
                <!--<div class="col-md-12">
                    <div class="form-group "><br />-->
                    <div class="selectwrap container">
                        <a href="<?php echo SITE_URL.'home.php';?>"><input type="button" name="cancel" value="Cancel" style="float: right;" /></a>
                        <input type="submit" name="rule" onclick="waitingDialog.show();" value="Save" style="float: right;" />
                    </div>
                   <!-- </div>
                 </div>-->
            </div>
        </form>

    </div>
</div>

<script>
(function($) {
$.fn.decimalFormat = function() {
    this.each( function( i ) {
        $(this).change( function( e ){
            if( isNaN( parseFloat( this.value ) ) ) return;
            this.value = parseFloat(this.value).toFixed(2);
        });
    });
    return this; //for chaining
}
let dataTable = $('#data-table')
    .DataTable({
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar">frtip',
        pageLength: 25,
        paging: true,
        stateSave: false
    })
})( jQuery );


$( function() {
    $('.decimal').decimalFormat();
});
function handleChange(input) {
    if (input.value < 0) input.value = 0;
    if (input.value > 100) input.value = 100;
}
function open_other(tag)
{
    var selectVal = $('#select_action_'+tag).val();
    // Reassign Broker
    if(selectVal == '5')
    {
        // tag #2: Commission Rate Excessive
        if(tag == '2')
        {
            $('#other_div'+tag).css('display','block');
            $('#other_div_1'+tag).css('display','none');
            $('#other_div_2'+tag).css('display','none');
            $('#other_div_3'+tag).css('display','block');
        } else {
            $('#other_div'+tag).css('display','none');
            $('#other_div_1'+tag).css('display','block');
            $('#other_div_2'+tag).css('display','none');
            $('#other_div_3'+tag).css('display','block');
        }
    }
    else
    {
        $('#other_div'+tag).css('display','block');
        $('#other_div_1'+tag).css('display','none');
        $('#other_div_2'+tag).css('display','block');
        $('#other_div_3'+tag).css('display','none');
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