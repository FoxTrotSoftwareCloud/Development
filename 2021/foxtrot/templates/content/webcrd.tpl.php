<div class="container">
<h1>WebCRD Download</h1>
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
    <div class="col-lg-12 well">
        <ul class="nav nav-pills nav-stacked col-md-2">
          <li id="pill_tab_a" name="pill_tabs" class=""><a href="#tab_a" data-toggle="pill">CE Download</a></li>
          <li id="pill_tab_b" name="pill_tabs" class=""><a href="#tab_b" data-toggle="pill">FINRA Exam Status</a></li>
          <li id="pill_tab_c" name="pill_tabs" class=""><a href="#tab_c" data-toggle="pill">Registration Status</a></li>
        </ul>
        
        <div class="tab-content col-md-10">
            <div class="tab-pane" id="tab_a" name="file_type_tabs">
                <div class="selectwrap">
                    <div class="row">
                        <form method="post" enctype="multipart/form-data">
                            <center>
                            <input type="file" name="ce_download_file" id="ce_download_file" accept=".csv" class="btn btn-warning btn-lg btn3d" style="display: inline;"/>
                            <input type="submit" name="import_ce_download" class="btn btn-warning btn-lg btn3d" value="Process File" onclick="waitingDialog.show('Importing WebCRD CE Download file. Please wait. . .')" /></center>
                        </form>
                    </div>
                </div>
                <br />
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">CE Download File</h3>
                    </div>
                    <div class="panel-body">
                    <div class="table-responsive" id="register_data">
                        <table id="data-table1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>FILE NAME</th>
                                    <th>IMPORT DATE</th>
                                    <th>TOTAL RECORDS</th>
                                    <th>BROKERS ADDED</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $count = 0;
                                    $return = $instance->select_master(0,'ce download');

                                    foreach($return as $key=>$val){
                                        ?>
                                        <tr>
                                            <td><?php echo $val['file_name']; ?></td>
                                            <td><?php echo date('m/d/Y h:i:s A',strtotime($val['import_date'])); ?></td>
                                            <td><?php echo $val['total_scan']; ?></td>
                                            <td><?php echo $val['added']; ?></td>
                                            <td class="text-center">
                                                <a href="<?php echo CURRENT_PAGE; ?>?open=ce_download_view&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View</a>
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
            
            <!-- 2. FINRA Exam Status -->
            <div class="tab-pane" id="tab_b" name="file_type_tabs">
                <div class="selectwrap">
                    <div class="selectwrap">
                        <div class="row">
                            <form method="post" enctype="multipart/form-data">
                                <center>
                                <input type="file" name="file_finra_exam_status" id="file_finra_exam_status" accept=".csv" class="btn btn-warning btn-lg btn3d" style="display: inline;"/>
                                <input type="submit" name="import_finra_exam_status" class="btn btn-warning btn-lg btn3d" value="Process File" onclick="waitingDialog.show('Importing FINRA Exam file. Please wait. . .')"/></center>
                            </form>
                        </div>
                    </div>
                    <br />
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">FINRA Exam Status</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive" id="register_data">
                    			<table id="data-table2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>FILE NAME</th>
                                        <th>IMPORT DATE</th>
                                        <th>TOTAL RECORDS</th>
                                        <th>BROKERS UPDATED</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $count = 0;
                                            $return = $instance->select_master(0,'finra exam status');

                                            foreach($return as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td><?php echo $val['file_name']; ?></td>
                                                    <td><?php echo date('m/d/Y h:i:s A',strtotime($val['import_date'])); ?></td>
                                                    <td><?php echo $val['total_scan']; ?></td>
                                                    <td><?php echo $val['added']; ?></td>
                                                    <td class="text-center">
                                                        <a href="<?php echo CURRENT_PAGE; ?>?open=finra_exam_status_view&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View</a>
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
            </div>

            <!-- 3. Registration Status -->
            <div class="tab-pane" id="tab_c" name="file_type_tabs">
                <div class="selectwrap">
                    <div class="selectwrap">
                        <div class="row">
                            <form method="post" enctype="multipart/form-data">
                                <center>
                                <input type="file" name="file_registration_status" id="file_registration_status" accept=".csv" class="btn btn-warning btn-lg btn3d" style="display: inline;"/>
                                <input type="submit" name="import_registration_status" class="btn btn-warning btn-lg btn3d" value="Process File" onclick="waitingDialog.show('Importing Registration Status file. Please wait. . .')"/></center>
                            </form>
                        </div>
                    </div>
                    <br />
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Registration Status</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive" id="register_data">
                                <table id="data-table2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>FILE NAME</th>
                                        <th>IMPORT DATE</th>
                                        <th>TOTAL RECORDS</th>
                                        <th>BROKERS UPDATED</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $count = 0;
                                            $return = $instance->select_master(0,'registration status');

                                            foreach($return as $key=>$val){
                                        ?>
                                            <tr>
                                                <td><?php echo $val['file_name']; ?></td>
                                                <td><?php echo date('m/d/Y h:i:s A',strtotime($val['import_date'])); ?></td>
                                                <td><?php echo $val['total_scan']; ?></td>
                                                <td><?php echo $val['added']; ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo CURRENT_PAGE; ?>?open=registration_status_view&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> View</a>
                                                    <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- 07/29/22 DELETE ME TEST - CE Download/OFAC Modal window removed - just go straight to the PDF Print Preview window -->
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
            			<a href="<?php echo SITE_URL;?>report_ofac_client_check.php?open=ofac_print&id=<?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo $_GET['id']; }else{ echo '0';}?> " class="btn btn-warning">Print Preview</a>
                        <!-- <a href="<?php echo SITE_URL;?>report_ofac_client_check.php?id=<?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo $_GET['id']; }else{ echo '0';}?> " class="btn btn-warning">Output to PDF</a> -->
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
            			<a href="<?php echo SITE_URL;?>report_fincen_client_check.php?open=fincen_print&id=<?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo $_GET['id']; }else{ echo '0';}?> " class="btn btn-warning">Print Preview</a>
                        <!-- <a href="<?php echo SITE_URL;?>report_fincen_client_check.php?id=<?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo $_GET['id']; }else{ echo '0';}?> " class="btn btn-warning">Output to PDF</a> -->
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
                            { "bSearchable": false, "aTargets": [ 3 ] }],
            "order": [1, "desc"]
        });
        $('#data-table2').DataTable({
            "pageLength": 25,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            "dom": '<"toolbar">frtip',
            "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 3 ] },
                            { "bSearchable": false, "aTargets": [ 3 ] }],
            "order": [1, "desc"]
        });
    } );

    // 07/29/22 Skip the modal and just display the report in a separate window
    <?php if(isset($_GET['open']) && $_GET['open'] == "ce_download_view" && isset($_GET['id']) && $_GET['id'] != ''){?>
        location.href=location.href.replace(/\?(.*)/i, "?tab=tab_a");
        window.open('report_webcrd_ce_download.php?id=' + <?php echo $_GET['id']; ?>,'_blank');
    <?php } else if(isset($_GET['open']) && $_GET['open'] == "finra_exam_status_view"){?>
        window.open('report_webcrd_finra_exam_status.php?id=' + <?php echo $_GET['id'] ?>,'_blank');
        location.href=location.href.replace(/\?(.*)/i, "?tab=tab_b");
    <?php } else if(isset($_GET['open']) && $_GET['open'] == "registration_status_view"){?>
        window.open('report_webcrd_registration_status.php?id=' + <?php echo $_GET['id'] ?>,'_blank');
        location.href=location.href.replace(/\?(.*)/i, "?tab=tab_c");
    <?php } ?>
    
    // Default File Type tabs
    document.getElementsByName("file_type_tabs").forEach(function(el){
        el.classList.remove('active')
    })
    document.getElementsByName("pill_tabs").forEach(function(el){
        el.classList.remove('active')
    })

    <?php if((isset($_GET['open']) && $_GET['open'] == "finra_exam_status_view") || (isset($_GET['tab']) && $_GET['tab'] == 'tab_b')) { ?>
        document.getElementById('pill_tab_b').classList.add('active')
        document.getElementById('tab_b').classList.add('active')
    <?php } else if((isset($_GET['open']) && $_GET['open'] == "registration_status_view") || (isset($_GET['tab']) && $_GET['tab'] == 'tab_c')) { ?>
        document.getElementById('pill_tab_c').classList.add('active')
        document.getElementById('tab_c').classList.add('active')
    <?php } else { ?>
        document.getElementById('pill_tab_a').classList.add('active')
        document.getElementById('tab_a').classList.add('active')
    <?php } ?>
    
</script>

<!-- 08/01/22 Keep this code. May need this as a template for a pop up modal later
<?php //if(isset($_GET['open']) && $_GET['open'] == "fincen_view" && isset($_GET['id']) && $_GET['id'] != ''){?>
<script>
location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, "");
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
<?php //} ?>
-->

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

</script>