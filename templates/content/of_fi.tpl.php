<div class="container">
<h1>OFAC &amp; FINCEN</h1>
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
    <div class="col-lg-12 well">
        <ul class="nav nav-pills nav-stacked col-md-2">
          <!--<li class="<?php if(!isset($_GET['tab'])){echo "active";}else{ echo '';} ?>"><a href="#tab_a" data-toggle="pill">Connect &amp; Download OFAC</a></li>-->
          <li class="<?php if(isset($_GET['tab']) && $_GET['tab']=="tab_b" || !isset($_GET['tab'])){ echo "active"; } ?>"><a href="#tab_b" data-toggle="pill">OFAC Download and Scan</a></li>
          <!--<li><a href="#tab_c" data-toggle="pill">Connect &amp; Download FINCEN</a></li>-->
          <li class="<?php if(isset($_GET['tab'])&&$_GET['tab']=="tab_d"){ echo "active"; } ?>"><a href="#tab_d" data-toggle="pill">FINCEN Download and Scan</a></li>
        </ul>
        <div class="tab-content col-md-10">
                <!--<div class="tab-pane <?php if(!isset($_GET['tab'])){echo "active";}else{ echo '';}?>" id="tab_a">
                        <div class="selectwrap"><center>
                					<input type="button" name="connect" class="btn btn-warning btn-lg btn3d" onclick="openNewTab1();" value="Connect And Download" />
                        </div>
                </div>-->
                <div class="tab-pane <?php if(isset($_GET['tab'])&&$_GET['tab']=="tab_b" || !isset($_GET['tab'])){ echo "active"; } ?>" id="tab_b">
                    <div class="selectwrap">
                        <div class="row">
                            <form method="post" enctype="multipart/form-data">
                                <center>
                                <input type="button" name="connect" class="btn btn-warning btn-lg btn3d" onclick="openNewTab1();" value="Connect And Download" />
                                <input type="file" name="file" accept=".csv" class="btn btn-warning btn-lg btn3d" style="display: inline;"/>
            					<input type="submit" name="import" class="btn btn-warning btn-lg btn3d" value="OFAC Scan"/></center>
                            </form>
                        </div>
                    </div>
                    <br />
                    <div class="panel">
                		<div class="panel-heading">
                            <h3 class="panel-title">OFAC Scan Files</h3>
                		</div>
                		<div class="panel-body">
                        <div class="table-responsive" id="register_data">
                			<table id="data-table1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                	            <thead>
                	                <tr>
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
                                                <td><?php echo date('m/d/Y',strtotime($val['created_time'])); ?></td>
                                                <td><?php echo $val['total_scan']; ?></td>
                                                <td><?php echo $val['total_match']; ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo CURRENT_PAGE; ?>?open=ofac_view&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View</a>
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
                <!--<div class="tab-pane " id="tab_c">
                    <div class="selectwrap">
                        <div class="row"><center>
            					<input type="button" name="connect" class="btn btn-warning btn-lg btn3d" onclick="openNewTab2();" value="Connect And Download"/>	
                        </div>
                    </div>
                </div>-->
                <div class="tab-pane  <?php if(isset($_GET['tab'])&&$_GET['tab']=="tab_d"){ echo "active"; } ?>" id="tab_d">
                    <div class="selectwrap">
                        <div class="selectwrap">
                            <div class="row">
                                <form method="post" enctype="multipart/form-data">
                                    <center>
                                    <input type="button" name="connect" class="btn btn-warning btn-lg btn3d" onclick="openNewTab2();" value="Connect And Download"/>
                                    <input type="file" name="file_fincen" accept=".csv" class="btn btn-warning btn-lg btn3d" style="display: inline;"/>
                					<input type="submit" name="import_fincen" class="btn btn-warning btn-lg btn3d" value="FINCEN Scan"/></center>
                                </form>
                            </div>
                        </div>
                        <br />
                        <div class="panel">
                    		<div class="panel-heading">
                                <h3 class="panel-title">FINCEN Scan Files</h3>
                    		</div>
                    		<div class="panel-body">
                            <div class="table-responsive" id="register_data">
                    			<table id="data-table2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    	            <thead>
                    	                <tr>
                                            <th>DATE</th>
                                            <th>TOTAL SCAN</th>
                                            <th>TOTAL MATCH</th>
                                            <th class="text-center">ACTION</th>
                                        </tr>
                    	            </thead>
                    	            <tbody>
                    	                <?php
                                            $count = 0;
                                            foreach($return_fincen as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td><?php echo date('m/d/Y',strtotime($val['created_time'])); ?></td>
                                                    <td><?php echo $val['total_scan']; ?></td>
                                                    <td><?php echo $val['total_match']; ?></td>
                                                    <td class="text-center">
                                                        <a href="<?php echo CURRENT_PAGE; ?>?open=fincen_view&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View</a>
                                                        <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_fincen&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
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
                </div>
        </div>
        <div id="ofac_Modal" class="modal fade" role="dialog" aria-hidden="true" tabindex="-1">
        	<div class="modal-dialog modal-lg">
        		<!-- Modal content-->
        		<div class="modal-content">
                    <!--<div class="modal-header" style="margin-bottom: 0px !important;">
            			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            			<h4 class="modal-title">OFAC Scan View</h4>
            		</div>-->
                    
                    <div class="modal-body" id="output_screen_content">Loading...</div>
        			
                    <div class="modal-footer" style="margin-bottom: 0px !important;">
            			<a href="<?php echo SITE_URL;?>report_ofac_client_check.php?open=ofac_print&id=<?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo $_GET['id']; }else{ echo '0';}?> " class="btn btn-warning">Output to Printer</a>
                        <a href="<?php echo SITE_URL;?>report_ofac_client_check.php?id=<?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo $_GET['id']; }else{ echo '0';}?> " class="btn btn-warning">Output to PDF</a>
            		</div>
        		</div>
        	</div>
        </div>
        <div id="fincen_Modal" class="modal fade" role="dialog" aria-hidden="true" tabindex="-1">
        	<div class="modal-dialog modal-lg">
        		<!-- Modal content-->
        		<div class="modal-content">
                    <!--<div class="modal-header" style="margin-bottom: 0px !important;">
            			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
            			<h4 class="modal-title">FINCEN Scan View</h4>
            		</div>-->
                    
                    <div class="modal-body" id="fincen_output_screen_content">Loading...</div>
        			
                    <div class="modal-footer" style="margin-bottom: 0px !important;">
            			<a href="<?php echo SITE_URL;?>report_fincen_client_check.php?open=fincen_print&id=<?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo $_GET['id']; }else{ echo '0';}?> " class="btn btn-warning">Output to Printer</a>
                        <a href="<?php echo SITE_URL;?>report_fincen_client_check.php?id=<?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo $_GET['id']; }else{ echo '0';}?> " class="btn btn-warning">Output to PDF</a>
            		</div>
        		</div>
        	</div>
        </div>
   </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        
        $('#data-table1').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 3 ] }, 
                        { "bSearchable": false, "aTargets": [ 3 ] }]
        });
        $('#data-table2').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 3 ] }, 
                        { "bSearchable": false, "aTargets": [ 3 ] }]
        });
} );
</script>
<?php if(isset($_GET['open']) && $_GET['open'] == "report"){?>
<script>
location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, "");
window.open('report_ofac_client_check.php','_blank');
</script>
<?php } ?>
<?php if(isset($_GET['open']) && $_GET['open'] == "report_fincen"){?>
<script>
location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, "");
window.open('report_fincen_client_check.php','_blank');
</script>
<?php } ?>
<?php if(isset($_GET['open']) && $_GET['open'] == "ofac_view" && isset($_GET['id']) && $_GET['id'] != ''){?>
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
    xmlhttp.open("GET", "ajax_ofacscan_view.php?id="+<?php echo $_GET['id']; ?>, true);
    xmlhttp.send();


    $('#ofac_Modal').modal({
    		show: true
    	});
       //location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, ""); 
});

</script>
<?php } ?>
<?php if(isset($_GET['open']) && $_GET['open'] == "fincen_view" && isset($_GET['id']) && $_GET['id'] != ''){?>
<script>
//location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, "");
$(document).ready(function(){
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) 
        {
            document.getElementById("fincen_output_screen_content").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "ajax_fincenscan_view.php?id="+<?php echo $_GET['id']; ?>, true);
    xmlhttp.send();


    $('#fincen_Modal').modal({
    		show: true
    	});
       //location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, ""); 
});

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