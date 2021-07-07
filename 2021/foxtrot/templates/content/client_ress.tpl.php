<div class="container">
<h1>Client Reassignment</h1>
    <div class="col-md-12 well">
        <form method="POST" id="client_ress_form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>From Broker </label>
                    <select class="form-control" name="from_broker">
                        <option value="0">Select Broker</option>
                            <?php foreach($get_broker as $key=>$val){?>
                            <option value="<?php echo $val['id'];?>" ><?php echo $val['first_name'].' '.$val['middle_name'].' '.$val['last_name'];?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>To Broker </label>
                    <select class="form-control" name="to_broker">
                        <option value="0">Select Broker</option>
                            <?php foreach($get_broker as $key=>$val){?>
                            <option value="<?php echo $val['id'];?>" ><?php echo $val['first_name'].' '.$val['middle_name'].' '.$val['last_name'];?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>List Of Ressigned Client </label><br />
                    <input type="radio" class="radio" name="output" id="output_to_screen" style="display: inline;" checked="checked" value="1"/> Output to Screen&nbsp;&nbsp;&nbsp;
                    <input type="radio" class="radio" name="output" id="output_to_printer" style="display: inline;" value="2"/> Output to Printer&nbsp;&nbsp;&nbsp;
                    <input type="radio" class="radio" name="output" id="output_to_pdf" style="display: inline;" value="3"/> Output to PDF
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="selectwrap">
				<div class="selectwrap">
                    <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
					<input type="submit" name="submit"  value="Proceed" style="float: right;"/>	
                </div>
            </div>
        </div>
        <div id="myModal" class="modal fade" role="dialog" aria-hidden="true" tabindex="-1">
        	<div class="modal-dialog modal-lg">
        		<!-- Modal content-->
        		<div class="modal-content">
                    <!--<div class="modal-header" style="margin-bottom: 0px !important;">
            			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            			<h4 class="modal-title">Client Reassignment List</h4>
            		</div>-->
                    
        			<div class="modal-body" id="output_screen_content">Loading...</div>
                    <div class="modal-footer" style="margin-bottom: 0px !important;">
            			<a href="<?php echo SITE_URL;?>client_ress_report.php?open=output_print&from_broker=<?php if(isset($_GET['from_broker']) && $_GET['from_broker']){ echo $_GET['from_broker']; }else{ echo '0';}?> " class="btn btn-warning">Output to Printer</a>
                        <a href="<?php echo SITE_URL;?>client_ress_report.php?from_broker=<?php if(isset($_GET['from_broker']) && $_GET['from_broker']){ echo $_GET['from_broker']; }else{ echo '0';}?> " class="btn btn-warning">Output to PDF</a>
            			
            		</div>
        		</div>
        	</div>
        </div>
        </form>
    </div>
</div>
<?php if(isset($_GET['open']) && $_GET['open'] == "output_screen" && isset($_GET['from_broker']) && $_GET['from_broker'] != ''){?>
<script>
//location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, "");
$(document).ready(function(){
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) 
        {
            document.getElementById("output_screen_content").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "ajax_client_reassign.php?from_broker="+<?php echo $_GET['from_broker']; ?>, true);
    xmlhttp.send();


    $('#myModal').modal({
    		show: true
    	});
       //location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, ""); 
});

</script>
<?php } ?>
<?php if(isset($_GET['open']) && $_GET['open'] == "output_print" && isset($_GET['from_broker']) && $_GET['from_broker'] != ''){?>
<script>
window.print();
</script>
<?php } ?>

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
				message = 'processing...';
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