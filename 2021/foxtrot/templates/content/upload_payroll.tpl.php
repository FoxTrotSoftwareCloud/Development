<div class="container">
<h1>Upload</h1>
    <div class="col-md-12 well">
    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Clearing Business Cutoff Date <span class="text-red">*</span></label>
                    <div id="demo-dp-range">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" name="clearing_business_cutoff_date" id="clearing_business_cutoff_date" class="form-control" value="<?php echo $clearing_business_cutoff_date;?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Direct Business Cutoff Date <span class="text-red">*</span></label>
                    <div id="demo-dp-range">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" name="direct_business_cutoff_date" id="direct_business_cutoff_date" class="form-control" value="<?php echo $direct_business_cutoff_date;?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="selectwrap">
				<div class="selectwrap">
                    <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
					<input type="submit" name="reverse_payroll"  value="Reverse Payroll" style="float: right;" <?php if(isset($payroll_transactions_array) && count($payroll_transactions_array)<=0){?>disabled="true"<?php }?>/>
                    <input type="submit" name="upload_payroll"  value="Upload Payroll" style="float: right;"/>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">
<?php 
if(isset($action) && $action == 'payroll_close'){?>
    $(document).ready(function() {
        conf_close('<?php echo CURRENT_PAGE; ?>?action=payroll_close&confirm=yes');
        });
<?php } ?>
$('#demo-dp-range .input-daterange').datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
        }).on('show',function(){
            $(".datepicker-dropdown").css("z-index",'1000000');
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
function conf_close(url){
    bootbox.confirm({
        message: "Are you sure, You want to close payroll? All payroll reports have been published to PDF for the current payroll.", 
        backdrop: true,
        buttons: {
            confirm: {
                label: '<i class="ion-android-done-all"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-warning"></i> No',
                className: 'btn-warning'
            }
        },
        callback: function(result) {
            if (result) {
                window.location.href = url;
            }else{
                //return false;
            };
        }
    });
}     
</script>
<style>
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
}
</style>