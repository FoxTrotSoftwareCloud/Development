<div class="container">
<h1><?php echo (isset($action) AND $action=='payroll_close')?"Close Payroll":"Upload" ?></h1>
    <div class="col-md-12 well">
    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <form id="upload_payroll" method="POST">
        <div class="row">
            <?php if (isset($action) AND $action!='payroll_close'){ ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Company</label>    
                        <select class="form-control" id="company-select" name="company-select">`
                            <option value="0">All Companies</option>
                            <?php foreach ($get_multi_company AS $companyRow) { ?>
                                <option value="<?php echo $companyRow['id'] ?>" <?php echo ($company_id==$companyRow['id'] ? 'selected':'') ?> ><?php echo $companyRow['company_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Payroll Date <span class="text-red">*</span></label>
                    <?php if(!isset($action) OR $action != 'payroll_close') { ?>
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="payroll_date" id="payroll_date" class="form-control" value="<?php echo $payroll_date; ?>" data-format="00/00/0000" placeholder="MM/DD/YYYY" />
                            </div>
                        </div>
                    <?php } else { ?>
                        <select class="form-control" name="payroll_id">
                            <?php $get_payroll_uploads = $instance->get_payroll_uploads(0,1);  
                                if (count($get_payroll_uploads)>0) {
                                    foreach($get_payroll_uploads as $key=>$val){?>
                                        <option value="<?php echo $val['id'];?>" <?php echo ($val['id']==$payroll_id)?'selected':''?> ><?php echo date('m/d/Y', strtotime($val['payroll_date']));?></option>
                                <?php }} else {?>
                                    <option value='0'>No Open Payrolls Found</option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php if(!isset($action) OR $action != 'payroll_close') { ?>
            <!-- <div class="row"> -->
                <!-- <div class="col-md-6">
                    <div class="form-group">
                        <label>Clearing Business Cutoff Date <span class="text-red">*</span></label>
                        <div id="demo-dp-range">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" name="clearing_business_cutoff_date" id="clearing_business_cutoff_date" class="form-control" value="<?php echo $clearing_business_cutoff_date;?>"/>
                            </div>
                        </div>
                    </div>
                </div> -->
            <!-- </div> -->
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
            <div class="panel">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-striped1 table-bordered" cellspacing="0" width="100%">
                        <thead class="thead_fixed_title">
                            <tr>
                                <th name="select_all" id="select_all"></th>
                                <th>BROKER NAME</th>
                                <th>CLOUDFOX #</th>
                                <th>FUND/CLEAR#</th>
                                <th class="text-center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 0;
                            foreach($select_brokers as $key=>$val){ 
                                $compClasses = 
                                    (empty($val['company_id1']) ? '' : ' company'.$val['company_id1']).
                                    (empty($val['company_id2']) ? '' : ' company'.$val['company_id2']).
                                    (empty($val['company_id3']) ? '' : ' company'.$val['company_id3']) 
                                ;
                            ?>
                                <tr id="repId_<?php echo $val['id']; ?>" class="<?php echo $compClasses; ?>" >
                                    <td></td>
                                    <td><?php echo $val['last_name'].", ".$val['first_name']; ?></td>
                                    <td><?php echo $val['id']; ?></td>
                                    <td><?php echo $val['fund']; ?></td>
                                    <td>
                                        <?php echo $instance_broker_master->active_statuses((int)$val['active_status']); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
            </div>

        <div class="row">
        </div>
        <?php } ?>
        <div class="panel-footer">
            <div class="selectwrap">
				<div class="selectwrap">
                    <a href="home.php"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>

                    <?php if(isset($action) && $action == 'payroll_close') { ?>
                        <input type="submit" name="close_payroll"  value="Close Payroll" style="float: right;"/>
                    <?php } else {?>
					    <input type="submit" name="reverse_payroll"  value="Reverse Payroll" style="float: right;" <?php if(isset($payroll_transactions_array) && count($payroll_transactions_array)<=0){?>disabled="true"<?php }?>/>
                        <input type="submit" name="upload_payroll"  value="Upload Payroll" style="float: right;"/>
                        <input type="hidden" name="duplicate_payroll_proceed" id="duplicate_payroll_proceed" value="" />
                    <?php } ?>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    let dataTable = $('#data-table')
        .DataTable({
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            "dom": '<"toolbar">frtip',
            pageLength: 25,
            paging: true,
            stateSave: false,
            select: true,
            // 04/14/22 Remvoved for the checkbox/select column properties
            // "aoColumnDefs": [
            //                     { "bSortable": true, "aTargets": [ 1 ] }, 
            //                     { "bSearchable": false, "aTargets": [ 1 ]},
            //                 ],
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   0
            } ],
            select: {
                style:    'multi',
                selector: 'tr'
            },
            "order": [<?php echo !empty($dataTableOrder) ? $dataTableOrder : '[1, "asc"], [2, "asc"]';?>]
        });

    dataTable
    .on("click", "th.select-checkbox", function() {
        if ($("th.select-checkbox").hasClass("selected")) {
            dataTable.rows().deselect();
            $("th.select-checkbox").removeClass("selected");
        } else {
            dataTable.rows().select();
            $("th.select-checkbox").addClass("selected");
        }
    }).on("select", function(e, dt, type, indexes) {
        if (dataTable.rows({
                selected: true
            }).count() !== dataTable.rows().count()) {
            $("th.select-checkbox").removeClass("selected");
        } else {
            $("th.select-checkbox").addClass("selected");
        }
    }).on("deselect", function(e, dt, type, indexes) {
        $("th.select-checkbox").removeClass("selected");
    }).on("page.dt", function(){
        // Page change code removed. Data initialized in "Initialize Broker table" code. Keep this open for future mods - i.e. element formatting        
    });

    // Initialize the Broker table
    <?php if(!empty($_SESSION['upload_payroll']['upload']) AND in_array("0", $_SESSION['upload_payroll']['upload'])){ 
        foreach ($_SESSION['upload_payroll']['upload'] AS $repId=>$rowValue){ 
    ?>
            // Create hidden <input> elements for the $_POST array
            var inputName = "<?php echo 'upload['.$repId.']' ?>";
            var isSelected = <?php echo $rowValue ?>;

            $("#upload_payroll").append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', inputName)
                .attr('value', isSelected)
            )
        <?php } ?>
        
        // Select the appropriate rows from $_SESSION['upload_payroll']['upload'] (translated into the <input>'s above)
        // Have to use the rows().every() method, because that was the only way to update(select/deselect) the rows that aren't visible on the current page.
        dataTable.rows().every(function(rowIdx, tableLoop, rowLoop) {
            isSelected = $("input[name=upload\\[" + this.node().id.replace("repId_","") + "\\]]").val();
                    
            if (isSelected == 1) {
                this.select();
            } else {
                this.deselect();
            }
        });
    <?php } else { ?>
        $("#select_all").trigger("click");
    <?php } ?>

    //-- 
    $('#company-select').on('change', function(event){
        companyClass = "company" + event.target.value;
        // Reset the search array every time, if the user searches companies the stack will go down to nothing
        $.fn.dataTable.ext.search.pop();
        
        if (event.target.value > 0){
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    return $(dataTable.row(dataIndex).node()).hasClass(companyClass);
                }
            );
        }
        // Important: trigger the draw(), or the table will look the same
        dataTable.draw();
    });

    // Add/Remove reps to the hidden <input> elements for POST - 4/22/22
    $('#upload_payroll').on('submit', function(e){
        var form = this;
        
        dataTable.rows().every( function (rowIdx, tableLoop, rowLoop){
            var inputName =(this.node().id).replace("repId_", "upload[") + "]";
            var isSelected = ($("#select_all").hasClass("selected") || (this.node().className).indexOf("selected")>-1) ? "1" : "0";
            var companyClass = 'company' + $("#company-select").val();
            
            // If a specific company is selected, only append Brokers associated with that company
            if (companyClass == 'company0' || $("#" + this.node().id).hasClass(companyClass)){
                //--- Saturday 4/23/22 1:28 PM - Try to append all the <inputs> on initialization ---//
                if(!$.contains(document, form[this.name])){
                    $(form).append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', inputName)
                        // .attr('value', isSelected)
                    )
                }
                var jQInput = $("input[name=" + inputName.replace("[", "\\\[").replace("]", "\\\]") + "]");
                jQInput.attr('value', isSelected);
            } else {
                // Remove <input> from the DOM so it won't POST
                var jQInput = $("input[name=" + inputName.replace("[", "\\\[").replace("]", "\\\]") + "]");
                
                console.log(jQInput + ", Length: " + $(jQInput).length)
                
                if ($(jQInput).length > 0){
                    $(jQInput).remove();
                }
            }
        });
    });

    // Filter the Broker table for specific companies
    if ($('#company-select').val() != 0){
        $('#company-select').trigger('change');
    }
    
    <?php if(isset($action) && $action == 'payroll_close') {?>
        // $(document).ready(function() {
            //     conf_close('<?php echo CURRENT_PAGE; ?>?action=payroll_close&confirm=yes');
            // });
    <?php } ?>
            
    <?php if(isset($_POST['upload_payroll']) && $_POST['upload_payroll']=="Upload Payroll" && !empty($payroll_date) && isset($_SESSION['upload_payroll']['duplicate_payroll'])) {  ?>
        $(document).ready(function() {
            duplicate_payroll('<?php echo CURRENT_PAGE; ?>?action=upload_payroll_duplicate_proceed', "<?php echo 'Payroll '.date('m/d/Y', strtotime($payroll_date)).' already exists.<br>Do you want to add trades to the existing payroll?<br>(If not, closeout the payroll and upload again.)'; ?>");
        });
    <?php } ?>
});
        
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
                //window.location.href = url + '?action=upload_payroll_duplicate_cancel';
            };
        }
    });
}
function duplicate_payroll(url, message){
    bootbox.confirm({
        message: message, 
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
            document.getElementById("duplicate_payroll_proceed").value = result;
            if (result) {
                $("#upload_payroll").trigger("submit");
                // document.getElementById("upload_payroll").submit();
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