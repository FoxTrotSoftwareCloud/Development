<div class="container">
<h1>OFAC &amp; FINCEN</h1>
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
    <div class="col-lg-12 well">
        <ul class="nav nav-pills nav-stacked col-md-2">
          <li class="<?php if(!isset($_GET['tab'])){echo "active";}else{ echo '';} ?>"><a href="#tab_a" data-toggle="pill">Connect &amp; Download OFAC</a></li>
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="tab_b"){ echo "active"; } ?>"><a href="#tab_b" data-toggle="pill">OFAC Complete System Scan</a></li>
          <li><a href="#tab_c" data-toggle="pill">Connect &amp; Download FINCEN</a></li>
          <li><a href="#tab_d" data-toggle="pill">FINCEN Complete System Scan</a></li>
        </ul>
        <div class="tab-content col-md-10">
                <div class="tab-pane <?php if(!isset($_GET['tab'])){echo "active";}else{ echo '';}?>" id="tab_a">
                        <div class="selectwrap"><center>
                					<input type="button" name="connect" class="btn btn-warning btn-lg btn3d" onclick="openNewTab1();" value="Connect And Download" />
                        </div>
                </div>
                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="tab_b"){ echo "active"; } ?>" id="tab_b">
                    <div class="selectwrap">
                        <div class="row">
                            <form method="post" enctype="multipart/form-data">
                                <center><input type="file" name="file" accept=".csv" class="btn btn-warning btn-lg btn3d" style="display: inline;"/>
            					<input type="submit" name="import" class="btn btn-warning btn-lg btn3d" value="OFAC System Scan"/></center>
                            </form>
                        </div>
                    </div>
                    <br />
                    <div class="panel">
                		<div class="panel-heading">
                            <h3 class="panel-title">Ofac Scan Files</h3>
                		</div>
                		<div class="panel-body">
                        <div class="table-responsive" id="register_data">
                			<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                	            <thead>
                	                <tr>
                                        <th class="text-center">#NO</th>
                                        <th>DATE</th>
                                        <th>TOTAL SCAN</th>
                                        <th>TOTAL MATCH</th>
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
                                                <td><?php echo date('m/d/Y',strtotime($val['created_time'])); ?></td>
                                                <td><?php echo $val['total_scan']; ?></td>
                                                <td><?php echo $val['total_match']; ?></td>
                                                <td class="text-center">
                                                    <a target="_blank" href="<?php echo CURRENT_PAGE; ?>?action=print&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-print"></i> Print</a>
                                                    <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
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
                </div>
                <div class="tab-pane " id="tab_c">
                    <div class="selectwrap">
                        <div class="row"><center>
            					<input type="button" name="connect" class="btn btn-warning btn-lg btn3d" onclick="openNewTab2();" value="Connect And Download"/>	
                        </div>
                    </div>
                </div>
                <div class="tab-pane " id="tab_d">
                    <div class="selectwrap">
                        <div class="row"><center>
            					<input type="button" name="connect" class="btn btn-warning btn-lg btn3d" onclick="waitingDialog.show();setTimeout(function () {waitingDialog.hide();}, 5000);" value="FINCEN System Scan"/></center>
                        </div>
                    </div>
                </div>
        </div>
   </div>
</div>
<?php if(isset($_GET['open']) && $_GET['open'] == "report"){?>
<script>
location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, "");
window.open('report_ofac_client_check.php','_blank');
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
				message = 'Connecting...';
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
		hide: function () {
			$dialog.modal('hide');
            window.open('http://www.website.com/page');
		}
	};

})(jQuery);
 
function openNewTab1(){
var win=window.open('https://www.treasury.gov/resource-center/sanctions/SDN-List/Pages/sdn_data.aspx');
   win.location.reload();
   win.focus();
};
function openNewTab2(){
var win=window.open('https://www.fincen.gov/314a/Login');
   win.location.reload();
   win.focus();
};
</script>